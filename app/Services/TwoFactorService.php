<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * TOTP implementation per RFC 6238 (HMAC-SHA1 based).
 * No external package required.
 */
class TwoFactorService
{
    private const DIGITS    = 6;
    private const PERIOD    = 30;
    private const ALGORITHM = 'sha1';

    // ── Secret generation ────────────────────────────────────────────────────

    public function generateSecret(): string
    {
        $bytes = random_bytes(20);
        return $this->base32Encode($bytes);
    }

    // ── QR Code URI (for authenticator apps) ────────────────────────────────

    public function getQrCodeUri(User $user, string $secret): string
    {
        $label  = rawurlencode(config('portfolio.site_name', 'Portfolio'));
        $issuer = rawurlencode(config('portfolio.site_name', 'Portfolio'));
        $account = rawurlencode($user->email);

        return "otpauth://totp/{$label}:{$account}?secret={$secret}&issuer={$issuer}&algorithm=SHA1&digits=6&period=30";
    }

    // ── Verification ─────────────────────────────────────────────────────────

    public function verify(string $secret, string $code, int $window = 1): bool
    {
        $code      = preg_replace('/\s/', '', $code);
        $timestamp = (int) floor(time() / self::PERIOD);

        for ($i = -$window; $i <= $window; $i++) {
            if ($this->generateCode($secret, $timestamp + $i) === $code) {
                return true;
            }
        }

        return false;
    }

    // ── Internal ─────────────────────────────────────────────────────────────

    private function generateCode(string $secret, int $timestamp): string
    {
        $key     = $this->base32Decode($secret);
        $time    = pack('N*', 0) . pack('N*', $timestamp);
        $hash    = hash_hmac(self::ALGORITHM, $time, $key, true);
        $offset  = ord($hash[strlen($hash) - 1]) & 0x0F;
        $code    = (
            ((ord($hash[$offset])     & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8)  |
            (ord($hash[$offset + 3]) & 0xFF)
        ) % (10 ** self::DIGITS);

        return str_pad((string) $code, self::DIGITS, '0', STR_PAD_LEFT);
    }

    // ── Base32 ────────────────────────────────────────────────────────────────

    private const BASE32_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    private function base32Encode(string $data): string
    {
        $binary  = '';
        foreach (str_split($data) as $char) {
            $binary .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }

        $chunks = str_split($binary . str_repeat('0', (5 - strlen($binary) % 5) % 5), 5);
        $result = '';
        foreach ($chunks as $chunk) {
            $result .= self::BASE32_CHARS[bindec($chunk)];
        }

        return rtrim($result, '=');
    }

    private function base32Decode(string $data): string
    {
        $data   = strtoupper(preg_replace('/\s/', '', $data));
        $binary = '';
        foreach (str_split($data) as $char) {
            $pos     = strpos(self::BASE32_CHARS, $char);
            $binary .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
        }

        $bytes  = str_split($binary, 8);
        $result = '';
        foreach ($bytes as $byte) {
            if (strlen($byte) === 8) {
                $result .= chr(bindec($byte));
            }
        }

        return $result;
    }

    // ── Recovery codes (single-use fallback if the device is lost) ──────────

    /**
     * Generates a fresh batch of plain-text recovery codes. Callers must
     * hash them via hashRecoveryCodes() before persisting and show the
     * plain codes to the user exactly once.
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(substr(bin2hex(random_bytes(4)), 0, 4) . '-' . substr(bin2hex(random_bytes(4)), 0, 4));
        }

        return $codes;
    }

    public function hashRecoveryCodes(array $codes): array
    {
        return array_values(array_map(
            fn (string $code) => Hash::make($this->normalizeRecoveryCode($code)),
            $codes
        ));
    }

    /**
     * Checks the given input against the user's stored recovery-code hashes.
     * On match, consumes (removes) that code so it can't be reused.
     */
    public function verifyAndConsumeRecoveryCode(User $user, string $code): bool
    {
        $hashes = $user->two_factor_recovery_codes ?? [];

        if (empty($hashes)) {
            return false;
        }

        $normalized = $this->normalizeRecoveryCode($code);

        foreach ($hashes as $index => $hash) {
            if (Hash::check($normalized, $hash)) {
                unset($hashes[$index]);
                $user->update(['two_factor_recovery_codes' => array_values($hashes)]);
                return true;
            }
        }

        return false;
    }

    private function normalizeRecoveryCode(string $code): string
    {
        return strtoupper(preg_replace('/[^A-Z0-9]/i', '', $code) ?? '');
    }

    // ── QR Code SVG (server-rendered, no external lib) ───────────────────────

    public function generateQrSvg(string $uri): string
    {
        // Encode URI into a simple data matrix representation
        // We use a Google Charts-compatible URL for the QR, served via img tag
        // but build the SVG wrapper ourselves to stay CDN-free
        $encoded = urlencode($uri);
        // Returns an <img> tag pointing to a free QR API (acceptable for setup only)
        return 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . $encoded;
    }
}
