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

        // Primary model first (picked for reliably following instructions),
        // then the free-model auto-router as a fallback if it's rate-limited
        // or unavailable, rather than surfacing an error to the visitor.
        $models = array_unique(array_filter([
            config('services.openrouter.model'),
            'openrouter/free',
        ]));

        foreach ($models as $model) {
            $reply = $this->attempt($model, $messages);
            if ($reply !== null) {
                return $reply;
            }
        }

        return null;
    }

    private function attempt(string $model, array $messages): ?string
    {
        try {
            $client = Http::withToken(config('services.openrouter.key'))
                ->withHeaders([
                    'HTTP-Referer' => config('app.url'),
                    'X-Title' => config('portfolio.site_name'),
                ])
                ->timeout(20);

            // Local Windows PHP installs commonly ship without a CA bundle
            // configured for curl, which makes every outbound HTTPS request
            // fail SSL verification. Point at a bundled cert as a local-only
            // fallback rather than touching php.ini or disabling verification.
            if (app()->environment('local')) {
                $localCert = storage_path('certs/cacert.pem');
                if (is_file($localCert)) {
                    $client = $client->withOptions(['verify' => $localCert]);
                }
            }

            $response = $client->post(rtrim(config('services.openrouter.base_url'), '/') . '/chat/completions', [
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.6,
                'max_tokens' => 300,
            ]);
        } catch (\Throwable $e) {
            Log::warning('OpenRouter request threw an exception', ['model' => $model, 'error' => $e->getMessage()]);
            return null;
        }

        if (! $response->successful()) {
            Log::warning('OpenRouter request failed', [
                'model' => $model,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        $content = $response->json('choices.0.message.content');

        return is_string($content) && trim($content) !== '' ? trim($content) : null;
    }
}
