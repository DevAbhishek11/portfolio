<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — {{ config('portfolio.site_name') }}</title>
    <link rel="icon" href="{{ asset('icon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Playfair Display"', 'serif'],
                        body: ['Inter', 'sans-serif']
                    },
                    colors: {
                        'anime-purple': '#8b5cf6',
                        'anime-cyan': '#06b6d4',
                        'anime-pink': '#f43f5e',
                        'anime-dark': '#0a0a0f',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                    },
                }
            }
        }
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --bg-primary: #0a0a0f;
            --bg-secondary: #12121a;
            --bg-tertiary: #1a1a2e;
            --accent-primary: #8b5cf6;
            --accent-secondary: #06b6d4;
            --accent-tertiary: #f43f5e;
        }

        body {
            background: var(--bg-primary);
            font-family: 'Inter', sans-serif;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes glow {
            from {
                box-shadow: 0 0 20px rgba(139, 92, 246, 0.2);
            }

            to {
                box-shadow: 0 0 40px rgba(139, 92, 246, 0.5), 0 0 80px rgba(6, 182, 212, 0.2);
            }
        }

        @keyframes speed-lines {
            0% {
                opacity: 0;
                transform: scaleX(0) translateX(-50%);
            }

            50% {
                opacity: 0.6;
            }

            100% {
                opacity: 0;
                transform: scaleX(1) translateX(50%);
            }
        }

        .auth-card {
            background: rgba(26, 26, 46, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 1.5rem;
        }

        .anime-input {
            background: rgba(10, 10, 15, 0.8);
            border: 1px solid rgba(139, 92, 246, 0.25);
            color: #e4e4e7;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            outline: none;
        }

        .anime-input:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.15);
        }

        .anime-input::placeholder {
            color: #52525b;
        }

        .anime-btn {
            background: linear-gradient(135deg, #8b5cf6, #06b6d4);
            color: white;
            border: none;
            border-radius: 0.75rem;
            padding: 0.875rem 2rem;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .anime-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(139, 92, 246, 0.4);
        }

        .anime-btn:active {
            transform: translateY(0);
        }

        .anime-btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 40%;
            height: 200%;
            background: rgba(255, 255, 255, 0.15);
            transform: skewX(-20deg);
            transition: left 0.4s ease;
        }

        .anime-btn:hover::after {
            left: 120%;
        }

        .bg-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
        }

        .otp-input {
            width: 3rem;
            height: 3.5rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            background: rgba(10, 10, 15, 0.8);
            border: 1px solid rgba(139, 92, 246, 0.25);
            color: #e4e4e7;
            border-radius: 0.75rem;
            outline: none;
            transition: all 0.3s;
        }

        .otp-input:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }

        .alert-success {
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.3);
            color: #67e8f9;
            border-radius: 0.75rem;
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
        }

        .alert-error {
            background: rgba(244, 63, 94, 0.1);
            border: 1px solid rgba(244, 63, 94, 0.3);
            color: #fb7185;
            border-radius: 0.75rem;
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
        }

        .field-error {
            color: #fb7185;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        label {
            color: #a1a1aa;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.4rem;
            display: block;
        }

        .anime-link {
            color: #a78bfa;
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s;
        }

        .anime-link:hover {
            color: #c4b5fd;
        }

        .strength-bar {
            height: 4px;
            border-radius: 9999px;
            transition: all 0.3s;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center relative overflow-hidden">

    {{-- Ambient background orbs --}}
    <div class="bg-orb w-96 h-96 bg-purple-600/20 top-[-10%] left-[-5%]" style="position:fixed;"></div>
    <div class="bg-orb w-80 h-80 bg-cyan-500/15 bottom-[-5%] right-[-5%]" style="position:fixed;"></div>
    <div class="bg-orb w-64 h-64 bg-pink-500/10 top-[40%] right-[20%]" style="position:fixed;"></div>

    {{-- Grid pattern overlay --}}
    <div
        style="position:fixed;inset:0;background-image:linear-gradient(rgba(139,92,246,0.03) 1px,transparent 1px),linear-gradient(90deg,rgba(139,92,246,0.03) 1px,transparent 1px);background-size:50px 50px;pointer-events:none;">
    </div>

    <div class="relative z-10 w-full max-w-md px-4">
        {{-- Logo / Brand --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <h1 class="font-display text-3xl font-bold"
                    style="background:linear-gradient(135deg,#8b5cf6,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                    {{ config('portfolio.site_name') }}
                </h1>
            </a>
            <p class="text-zinc-500 text-sm mt-1">Admin Panel</p>
        </div>

        @yield('content')

        <p class="text-center text-zinc-600 text-xs mt-6">
            &copy; {{ date('Y') }} {{ config('portfolio.site_name') }}. All rights reserved.
        </p>
    </div>
</body>

</html>
