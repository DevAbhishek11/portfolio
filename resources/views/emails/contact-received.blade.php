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
            background: linear-gradient(135deg, #06b6d4, #8b5cf6);
            padding: 2rem;
            text-align: center;
        }

        .header h1 {
            color: #fff;
            margin: 0;
            font-size: 1.25rem;
        }

        .body {
            padding: 2rem;
        }

        .field {
            margin-bottom: 1rem;
        }

        .field-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #8b5cf6;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .field-value {
            color: #1e1b4b;
            font-size: 0.9rem;
            margin-top: 0.2rem;
        }

        .message-box {
            background: #f9f7ff;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
            font-size: 0.875rem;
            color: #374151;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #8b5cf6, #06b6d4);
            color: #fff;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
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
            <h1>📬 New Contact Query</h1>
        </div>
        <div class="body">
            <p>Hey <strong>{{ $admin->name }}</strong>, you have a new message from your portfolio contact form.</p>

            <div class="field">
                <div class="field-label">From</div>
                <div class="field-value">{{ $query->name }} &lt;{{ $query->email }}&gt;</div>
            </div>
            @if ($query->phone)
                <div class="field">
                    <div class="field-label">Phone</div>
                    <div class="field-value">{{ $query->phone }}</div>
                </div>
            @endif
            <div class="field">
                <div class="field-label">Subject</div>
                <div class="field-value">{{ $query->subject }}</div>
            </div>
            <div class="field">
                <div class="field-label">Message</div>
                <div class="message-box">{{ $query->message }}</div>
            </div>
            <div class="field">
                <div class="field-label">Received</div>
                <div class="field-value">{{ $query->created_at->format('M d, Y \a\t h:i A') }}</div>
            </div>

            <div style="text-align:center;margin-top:1.5rem;">
                <a href="{{ route('admin.contacts.show', $query->id) }}" class="btn">View & Reply in Admin →</a>
            </div>
        </div>
        <div class="footer">&copy; {{ date('Y') }} {{ config('portfolio.site_name') }}</div>
    </div>
</body>

</html>
