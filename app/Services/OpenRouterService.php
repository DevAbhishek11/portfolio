<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService
{
    public function isConfigured(): bool
    {
        return filled(config('services.openrouter.key'));
    }

    /**
     * @param array $messages OpenAI-style [{role, content}, ...]
     * @return string|null Assistant reply text, or null on failure / not configured.
     */
    public function chat(array $messages): ?string
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::withToken(config('services.openrouter.key'))
                ->withHeaders([
                    'HTTP-Referer' => config('app.url'),
                    'X-Title' => config('portfolio.site_name'),
                ])
                ->timeout(20)
                ->post(rtrim(config('services.openrouter.base_url'), '/') . '/chat/completions', [
                    'model' => config('services.openrouter.model'),
                    'messages' => $messages,
                    'temperature' => 0.6,
                    'max_tokens' => 500,
                ]);
        } catch (\Throwable $e) {
            Log::warning('OpenRouter request threw an exception', ['error' => $e->getMessage()]);
            return null;
        }

        if (! $response->successful()) {
            Log::warning('OpenRouter request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        $content = $response->json('choices.0.message.content');

        return is_string($content) && trim($content) !== '' ? trim($content) : null;
    }
}
