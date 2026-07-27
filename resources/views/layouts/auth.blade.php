<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — {{ config('portfolio.site_name') }}</title>
    <link rel="icon" href="{{ asset('icon.png') }}" type="image/png">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>

<body class="auth-page min-h-screen flex items-center justify-center relative overflow-hidden">

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
