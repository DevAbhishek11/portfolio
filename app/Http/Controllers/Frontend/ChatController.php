<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ChatLead;
use App\Models\User;
use App\Services\MailService;
use App\Services\OpenRouterService;
use App\Services\PortfolioContextService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ChatController extends Controller
{
    private const HISTORY_KEY = 'chat_history';
    private const LEAD_STATE_KEY = 'chat_lead_state';
    private const MAX_HISTORY_MESSAGES = 12;

    public function __construct(
        private OpenRouterService $openRouter,
        private PortfolioContextService $context,
        private MailService $mailService,
    ) {}

    public function status()
    {
        return response()->json(['enabled' => $this->openRouter->isConfigured()]);
    }

    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $key = 'chat:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 20)) {
            return response()->json([
                'reply' => "You've sent quite a few messages — please wait a bit before continuing.",
            ], 429);
        }
        RateLimiter::hit($key, 600); // 20 messages / 10 minutes per IP

        if (! $this->openRouter->isConfigured()) {
            return response()->json([
                'reply' => "Thanks for the message! The AI assistant isn't fully set up yet — please use the contact form and I'll get back to you directly.",
                'enabled' => false,
            ]);
        }

        $userMessage = trim($request->input('message'));
        $history = session(self::HISTORY_KEY, []);

        $messages = array_merge(
            [['role' => 'system', 'content' => $this->context->systemPrompt()]],
            $history,
            [
                ['role' => 'system', 'content' => "PORTFOLIO CONTEXT:\n" . $this->context->scopedContext($userMessage)],
                ['role' => 'user', 'content' => $userMessage],
            ]
        );

        $reply = $this->openRouter->chat($messages);

        if ($reply === null) {
            return response()->json([
                'reply' => "Sorry, I'm having trouble responding right now. Please try again in a moment, or use the contact form.",
            ], 200);
        }

        $history[] = ['role' => 'user', 'content' => $userMessage];
        $history[] = ['role' => 'assistant', 'content' => $reply];
        $history = array_slice($history, -self::MAX_HISTORY_MESSAGES);
        session([self::HISTORY_KEY => $history]);

        $this->maybeCaptureLead($request, $userMessage, $history);

        return response()->json(['reply' => $reply]);
    }

    public function reset(Request $request)
    {
        $request->session()->forget([self::HISTORY_KEY, self::LEAD_STATE_KEY]);
        return response()->json(['ok' => true]);
    }

    /**
     * Lightweight heuristic lead capture: watches the conversation for an
     * email address (and, if stated, a name), and fires one notification
     * email per session once both are seen — no ML classification, just
     * pattern matching, kept intentionally simple and false-positive-light.
     */
    private function maybeCaptureLead(Request $request, string $latestMessage, array $history): void
    {
        if (session()->has(self::LEAD_STATE_KEY . '_sent')) {
            return;
        }

        if (preg_match('/[\w.+-]+@[\w-]+\.[a-z]{2,}/i', $latestMessage, $emailMatch)) {
            session([self::LEAD_STATE_KEY . '.email' => $emailMatch[0]]);
        }

        // Two-step match: find the trigger phrase case-insensitively, then
        // extract the name case-sensitively (mixing /i into the character
        // class would make [A-Z] match lowercase letters too).
        if (preg_match('/\b(?:i\'?m|i am|my name is|this is)\s+/i', $latestMessage, $trigger, PREG_OFFSET_CAPTURE)) {
            $afterTrigger = substr($latestMessage, $trigger[0][1] + strlen($trigger[0][0]));
            if (preg_match('/^([A-Z][a-zA-Z]+(?:\s+[A-Z][a-zA-Z]+)?)/', $afterTrigger, $nameMatch)) {
                session([self::LEAD_STATE_KEY . '.name' => trim($nameMatch[1])]);
            }
        }

        $email = session(self::LEAD_STATE_KEY . '.email');
        if (! $email) {
            return;
        }

        $admin = User::where('is_admin', true)->first();
        if (! $admin) {
            return;
        }

        $name = session(self::LEAD_STATE_KEY . '.name', 'Website Visitor');
        $snippet = collect($history)
            ->map(fn ($m) => ($m['role'] === 'user' ? 'Visitor: ' : 'Assistant: ') . $m['content'])
            ->implode("\n");

        $lead = ChatLead::create([
            'name' => $name,
            'email' => $email,
            'message' => $latestMessage,
            'conversation_snippet' => $snippet,
            'ip_address' => $request->ip(),
            'emailed' => false,
        ]);

        try {
            $this->mailService->sendChatLead($admin, $lead);
            $lead->update(['emailed' => true]);
        } catch (\Throwable $e) {
            Log::error('Chat lead notification email failed', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage(),
            ]);
        }

        session([self::LEAD_STATE_KEY . '_sent' => true]);
    }
}
