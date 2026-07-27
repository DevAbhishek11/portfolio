<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Inter, sans-serif;
            background: #f4f4f8;
            margin: 0;
            padding: 0;
        }

        .wrap {
            max-width: 520px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #f43f5e, #8b5cf6);
            padding: 2rem;
            text-align: center;
        }

        .header h1 {
            color: #fff;
            margin: 0;
            font-size: 1.5rem;
        }

        .header p {
            color: rgba(255, 255, 255, 0.8);
            margin: 0.25rem 0 0;
            font-size: 0.875rem;
        }

        .body {
            padding: 2rem;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #8b5cf6, #06b6d4);
            color: #fff;
            text-decoration: none;
            padding: 0.875rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            margin: 1.5rem 0;
        }

        .otp-box {
            background: #f9f7ff;
            border: 2px solid #8b5cf6;
            border-radius: 12px;
            text-align: center;
            padding: 1rem;
            margin: 1rem 0;
        }

        .otp-code {
            font-size: 2rem;
            font-weight: 700;
            color: #6d28d9;
            letter-spacing: 0.4rem;
        }

        .notice {
            background: #fff7ed;
            border-left: 3px solid #f97316;
            padding: 0.75rem 1rem;
            border-radius: 0 8px 8px 0;
            font-size: 0.825rem;
            color: #9a3412;
            margin-top: 1.5rem;
        }

        .url-fallback {
            font-size: 0.75rem;
            color: #a1a1aa;
            word-break: break-all;
        }

        .footer {
            background: #f9f9fb;
            padding: 1rem 2rem;
            text-align: center;
            font-size: 0.75rem;
            color: #a1a1aa;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="header">
            <h1>{{ config('portfolio.site_name') }}</h1>
            <p>Password Reset Request</p>
        </div>
        <div class="body">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            <p>We received a request to reset your admin panel password. Click the button below to proceed:</p>

            <div style="text-align:center;">
                <a href="{{ $resetUrl }}" class="btn">Reset My Password →</a>
            </div>

            <p style="font-size:0.875rem;color:#71717a;">You'll also need this OTP code on the reset page:</p>

            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
                <p style="font-size:0.75rem;color:#71717a;margin:0.25rem 0 0;">⏱ Expires in 60 minutes</p>
            </div>

            <p class="url-fallback">If the button doesn't work, copy and paste this link:<br>{{ $resetUrl }}</p>

            <div class="notice">
                If you did not request a password reset, ignore this email. Your account remains secure.
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('portfolio.site_name') }} &mdash; Do not reply to this email.
        </div>
    </div>
</body>

</html>
