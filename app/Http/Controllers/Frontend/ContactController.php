<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactQueries as ContactQuery;
use App\Models\User;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ContactController extends Controller
{
    public function __construct(private MailService $mailService) {}

    public function index()
    {
        return view('frontend.contact');
    }

    public function store(Request $request)
    {
        // Rate limit: 3 per hour per IP
        $key = 'contact:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $wait = RateLimiter::availableIn($key);
            return back()->with('error', "Too many submissions. Please wait {$wait} seconds.");
        }
        RateLimiter::hit($key, 3600);

        $data = $request->validate([
            'name'     => 'required|string|min:2|max:100',
            'email'    => 'required|email|max:255',
            'phone'    => 'nullable|string|max:30',
            'subject'  => 'required|string|min:5|max:200',
            'message'  => 'required|string|min:20|max:5000',
            'honeypot' => 'max:0',
        ]);

        $query = ContactQuery::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'subject'    => $data['subject'],
            'message'    => $data['message'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status'     => 'unread',
        ]);

        // Notify admin — log failure but don't break UX
        $admin = User::where('is_admin', true)->first();
        if ($admin) {
            try {
                $this->mailService->sendContactReceived($admin, $query);
            } catch (\Throwable $e) {
                Log::error('Contact notification email failed', [
                    'query_id' => $query->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        return back()->with('success', 'Message sent! I\'ll get back to you as soon as possible.');
    }
}
