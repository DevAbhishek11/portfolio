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
            font-size: 1.25rem;
        }

        .header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.875rem;
            margin: 0.25rem 0 0;
        }

        .body {
            padding: 2rem;
        }

        .reply-box {
            background: #f9f7ff;
            border-left: 3px solid #8b5cf6;
            border-radius: 0 10px 10px 0;
            padding: 1.25rem;
            margin: 1rem 0;
            font-size: 0.9rem;
            color: #374151;
            line-height: 1.7;
        }

        .original {
            background: #f9f9fb;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: #71717a;
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
            <p>Reply to your inquiry</p>
        </div>
        <div class="body">
            <p>Hello <strong>{{ $query->name }}</strong>,</p>
            <p>Thank you for reaching out. Here's a response to your message:</p>

            <div class="reply-box">{!! nl2br(e($reply->message)) !!}</div>

            <div class="original">
                <strong>Your original message:</strong><br>
                <em>{{ $query->subject }}</em><br><br>
                {{ $query->message }}
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('portfolio.site_name') }} &mdash;
            This is a reply to your inquiry submitted on {{ $query->created_at->format('M d, Y') }}.
        </div>
    </div>
</body>

</html>
