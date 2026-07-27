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
            background: linear-gradient(135deg, #8b5cf6, #06b6d4);
            padding: 2rem;
            text-align: center;
        }

        .header h1 {
            color: #fff;
            margin: 0;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.8);
            margin: 0.25rem 0 0;
            font-size: 0.875rem;
        }

        .body {
            padding: 2rem;
        }

        .otp-box {
            background: #f9f7ff;
            border: 2px solid #8b5cf6;
            border-radius: 12px;
            text-align: center;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .otp-code {
            font-size: 2.5rem;
            font-weight: 700;
            color: #6d28d9;
            letter-spacing: 0.5rem;
        }

        .otp-expiry {
            color: #71717a;
            font-size: 0.8rem;
            margin-top: 0.5rem;
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
            <p>Admin Panel — Login Verification</p>
        </div>
        <div class="body">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            <p>Someone (hopefully you) is trying to log in to the admin panel. Use the code below to complete
                verification:</p>

            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-expiry">⏱ Expires in 10 minutes</div>
            </div>

            <div class="notice">
                🔒 If you did not attempt to log in, your password may be compromised. Please change it immediately.
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('portfolio.site_name') }} &mdash; Do not reply to this email.
        </div>
    </div>
</body>

</html>
