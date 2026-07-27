<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin</title>
    <link rel="icon" href="{{ asset('icon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        admin: {
                            bg: '#0f0f14',
                            surface: '#17171f',
                            card: '#1e1e2a',
                            border: '#2a2a3a',
                            accent: '#8b5cf6',
                            'accent-lt': '#a78bfa',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @stack('styles')

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* collapsed sidebar */
        #sidebar.collapsed {
            width: 70px !important;
            min-width: 70px !important;
        }

        #sidebar.collapsed .nav-label,
        #sidebar.collapsed .nav-badge,
        #sidebar.collapsed .brand-text {
            display: none !important;
        }

        #sidebar.collapsed .nav-item {
            justify-content: center !important;
            padding: 0.75rem !important;
        }

        /* thin scrollbars */
        .sidebar-nav::-webkit-scrollbar {
            width: 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #2a2a3a;
            border-radius: 2px;
        }

        .scroll-zone::-webkit-scrollbar {
            width: 5px;
        }

        .scroll-zone::-webkit-scrollbar-track {
            background: #0f0f14;
        }

        .scroll-zone::-webkit-scrollbar-thumb {
            background: #2a2a3a;
            border-radius: 3px;
        }

        .scroll-zone::-webkit-scrollbar-thumb:hover {
            background: #3a3a4a;
        }
    </style>
</head>

<body class="h-screen overflow-hidden bg-[#17171f] text-zinc-200">

    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar"
            class="flex flex-col h-screen sticky top-0 flex-shrink-0 overflow-hidden transition-all duration-300 bg-[#17171f]"
            style="width:260px; min-width:260px;">

            {{-- Brand --}}
            <div class="flex items-center gap-3 px-4 py-[1.05rem] flex-shrink-0 border-b border-[#2a2a3a]">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                    style="background:linear-gradient(135deg,#8b5cf6,#06b6d4)">
                    <svg width="16" height="16" fill="none" stroke="white" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                </div>
                <span class="brand-text font-semibold text-white text-sm whitespace-nowrap">Admin Panel</span>
            </div>

            {{-- Scrollable nav --}}
            <nav class="sidebar-nav flex-1 overflow-y-auto p-3">

                @php
                    $unread = \App\Models\ContactQueries::where('status', 'unread')->count();
                    $route = request()->route()->getName();
                @endphp

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item flex items-center gap-3 px-4 py-2.5 my-0.5 rounded-xl
                          text-sm font-medium no-underline whitespace-nowrap transition-all duration-200
                          {{ str_starts_with($route, 'admin.dashboard')
                              ? 'bg-violet-500/15 text-violet-400 border-r-2 border-violet-500'
                              : 'text-zinc-500 hover:bg-[#1e1e2a] hover:text-zinc-200' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="nav-label">Dashboard</span>
                </a>

                {{-- Projects --}}
                <a href="{{ route('admin.projects.index') }}"
                    class="nav-item flex items-center gap-3 px-4 py-2.5 my-0.5 rounded-xl
                          text-sm font-medium no-underline whitespace-nowrap transition-all duration-200
                          {{ str_starts_with($route, 'admin.projects')
                              ? 'bg-violet-500/15 text-violet-400 border-r-2 border-violet-500'
                              : 'text-zinc-500 hover:bg-[#1e1e2a] hover:text-zinc-200' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="nav-label">Projects</span>
                </a>

                {{-- Blog Posts --}}
                <a href="{{ route('admin.blogs.index') }}"
                    class="nav-item flex items-center gap-3 px-4 py-2.5 my-0.5 rounded-xl
                          text-sm font-medium no-underline whitespace-nowrap transition-all duration-200
                          {{ str_starts_with($route, 'admin.blogs')
                              ? 'bg-violet-500/15 text-violet-400 border-r-2 border-violet-500'
                              : 'text-zinc-500 hover:bg-[#1e1e2a] hover:text-zinc-200' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="nav-label">Blog Posts</span>
                </a>

                {{-- Contacts --}}
                <a href="{{ route('admin.contacts.index') }}"
                    class="nav-item flex items-center gap-3 px-4 py-2.5 my-0.5 rounded-xl
                          text-sm font-medium no-underline whitespace-nowrap transition-all duration-200
                          {{ str_starts_with($route, 'admin.contacts')
                              ? 'bg-violet-500/15 text-violet-400 border-r-2 border-violet-500'
                              : 'text-zinc-500 hover:bg-[#1e1e2a] hover:text-zinc-200' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="nav-label">Contacts</span>
                    @if ($unread > 0)
                        <span
                            class="nav-badge ml-auto bg-rose-500 text-white text-[0.7rem]
                                     font-semibold px-1.5 py-px rounded-full min-w-[18px] text-center">
                            {{ $unread }}
                        </span>
                    @endif
                </a>

                <div class="my-3 h-px bg-[#2a2a3a]"></div>

                {{-- Profile --}}
                <a href="{{ route('admin.profile.index') }}"
                    class="nav-item flex items-center gap-3 px-4 py-2.5 my-0.5 rounded-xl
                          text-sm font-medium no-underline whitespace-nowrap transition-all duration-200
                          {{ str_starts_with($route, 'admin.profile')
                              ? 'bg-violet-500/15 text-violet-400 border-r-2 border-violet-500'
                              : 'text-zinc-500 hover:bg-[#1e1e2a] hover:text-zinc-200' }}">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="nav-label">Profile</span>
                </a>

                {{-- View Site --}}
                <a href="{{ route('home') }}" target="_blank"
                    class="nav-item flex items-center gap-3 px-4 py-2.5 my-0.5 rounded-xl
                          text-sm font-medium no-underline whitespace-nowrap transition-all duration-200
                          text-zinc-500 hover:bg-[#1e1e2a] hover:text-zinc-200">
                    <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    <span class="nav-label">View Site</span>
                </a>

            </nav>

            {{-- Logout — pinned to sidebar bottom --}}
            <div class="flex-shrink-0 p-3">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                        class="nav-item flex items-center gap-3 w-full px-4 py-2.5 rounded-xl
                                   text-sm font-medium whitespace-nowrap
                                   text-zinc-500 bg-transparent border-0 cursor-pointer
                                   hover:bg-[#1e1e2a] hover:text-zinc-200 transition-all duration-200">
                        <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="nav-label">Logout</span>
                    </button>
                </form>
            </div>

        </aside>

        <div class="flex flex-col flex-1 min-w-0 h-screen overflow-hidden">

            {{-- ── TOPBAR — flex-shrink-0 so it never compresses ── --}}
            <header
                class="flex-shrink-0 sticky top-0 z-40
                           flex items-center gap-3 h-[60px] px-5
                           bg-[#17171f]">

                {{-- Hamburger toggle --}}
                <button onclick="toggleSidebar()"
                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-transparent border-0 cursor-pointer flex-shrink-0 text-zinc-500 hover:text-zinc-300 hover:bg-[#2a2a3a] transition-colors duration-200">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- Breadcrumb --}}
                <div class="flex-1 text-sm text-zinc-400 truncate min-w-0">
                    @yield('breadcrumb', 'Dashboard')
                </div>

                {{-- Right-side controls --}}
                <div class="flex items-center gap-3 flex-shrink-0">

                    {{-- Bell --}}
                    @if ($unread > 0)
                        <a href="{{ route('admin.contacts.index', ['status' => 'unread']) }}"
                            class="relative text-zinc-500 hover:text-zinc-300 transition-colors duration-200">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span
                                class="absolute -top-1 -right-1
                                         w-4 h-4 rounded-full bg-rose-500 text-white
                                         text-[0.6rem] font-bold flex items-center justify-center">
                                {{ min($unread, 99) }}
                            </span>
                        </a>
                    @endif

                    {{-- User dropdown --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">

                        <button @click="open = !open"
                            class="flex items-center gap-2 bg-transparent border-0
                                       cursor-pointer text-zinc-300 text-sm">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center
                                        flex-shrink-0 overflow-hidden"
                                style="background:linear-gradient(135deg,#8b5cf6,#06b6d4)">
                                @if ($adminUser->avatar)
                                    <img src="{{ asset('/' . $adminUser->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-white font-semibold text-xs">
                                        {{ substr($adminUser->name, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                            <span class="hidden md:block">{{ $adminUser->name }}</span>
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 top-[calc(100%+8px)] w-44 z-50
                                    bg-[#1e1e2a] border border-[#2a2a3a] rounded-xl
                                    shadow-2xl p-1.5"
                            style="display:none" :style="open ? 'display:block' : 'display:none'">

                            <a href="{{ route('admin.profile.index') }}"
                                class="flex items-center px-3 py-2 text-sm text-zinc-300
                                      rounded-lg no-underline hover:bg-[#2a2a3a] transition-colors duration-150">
                                Profile
                            </a>
                            <a href="{{ route('admin.profile.security') }}"
                                class="flex items-center px-3 py-2 text-sm text-zinc-300
                                      rounded-lg no-underline hover:bg-[#2a2a3a] transition-colors duration-150">
                                Security
                            </a>
                            {{-- <div class="my-1 h-px bg-[#2a2a3a]"></div> --}}
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-3 py-2 text-sm text-rose-400 rounded-lg bg-transparent border-0 cursor-pointer hover:bg-rose-500/10 transition-colors duration-150">
                                    Logout
                                </button>
                            </form>
                        </div>

                    </div>{{-- /dropdown --}}
                </div>{{-- /right controls --}}

            </header>
            
            <div class="scroll-zone flex-1 overflow-y-auto min-h-0 p-4 rounded-2xl bg-white/10 mr-2">

                {{-- Page content — pr-8 gives the right-side margin you asked for --}}
                <main class="p-4 min-h-[calc(100%-48px)] bg-[#17171f] rounded-2xl border-2 border-white/25">

                    {{-- Flash: success --}}
                    @if (session('success'))
                        <div
                            class="mb-5 px-4 py-3 rounded-xl text-sm font-medium
                                    bg-emerald-500/10 border border-emerald-500/25 text-emerald-400">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Flash: error --}}
                    @if (session('error'))
                        <div
                            class="mb-5 px-4 py-3 rounded-xl text-sm font-medium
                                    bg-rose-500/10 border border-rose-500/25 text-rose-400">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Validation errors --}}
                    @if ($errors->any())
                        <div
                            class="mb-5 px-4 py-3 rounded-xl text-sm
                                    bg-rose-500/10 border border-rose-500/25 text-rose-400">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')

                </main>

                {{-- ── FOOTER — sits at the very end of the scroll zone ── --}}

            </div>{{-- /.scroll-zone --}}
            <footer
                class="flex items-center justify-between h-12 px-8 pl-6 bg-[#17171f] text-xs text-zinc-600">

                <span>© {{ date('Y') }} Admin Panel. All rights reserved.</span>

                {{-- <div class="flex items-center gap-5">
                    <a href="{{ route('home') }}" target="_blank"
                        class="text-zinc-500 no-underline
                              hover:text-violet-400 transition-colors duration-200">
                        View Site
                    </a>
                    <a href="{{ route('admin.profile.index') }}"
                        class="text-zinc-500 no-underline
                              hover:text-violet-400 transition-colors duration-200">
                        Profile
                    </a>
                    <span class="text-zinc-700">v1.0.0</span>
                </div> --}}

            </footer>

        </div>{{-- /.main-column --}}

    </div>{{-- /.shell --}}

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js" defer></script>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }

        function confirmDelete(formId) {
            if (confirm('Are you sure? This action cannot be undone.')) {
                document.getElementById(formId).submit();
            }
        }
    </script>

    @stack('scripts')
</body>

</html>
