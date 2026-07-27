@extends('layouts.admin')
@section('title', 'My Profile')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Account Overview</h1>
            <p class="text-slate-400 text-sm mt-1">View your profile information and account statistics.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.profile.security') }}"
                class="px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl transition-all">
                Security Settings
            </a>
            <a href="{{ route('admin.profile.edit') }}"
                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-lg shadow-indigo-600/20 transition-all">
                Edit Profile
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Left Column: Identity Card -->
        <div class="lg:col-span-4 space-y-6">
            <div
                class="bg-slate-800/50 border border-slate-700 rounded-2xl p-8 text-center backdrop-blur-sm relative overflow-hidden">
                <!-- Subtle Background Decorative Glow -->
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-600/10 rounded-full blur-3xl"></div>

                <!-- Avatar with Gradient Ring -->
                <div
                    class="relative w-32 h-32 mx-auto mb-5 p-1 rounded-2xl bg-gradient-to-tr from-indigo-500 to-cyan-400 shadow-xl">
                    <div
                        class="w-full h-full rounded-[calc(1rem-1px)] overflow-hidden bg-slate-900 flex items-center justify-center">
                        @if ($user->avatar)
                            <img src="{{ asset('/' . $user->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-white text-4xl font-bold italic">{{ substr($user->name, 0, 1) }}</span>
                        @endif
                    </div>
                </div>

                <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                <p class="text-indigo-400 font-medium text-sm">{{ $user->title ?: 'System Administrator' }}</p>

                @if ($user->bio)
                    <p class="text-slate-400 text-sm mt-4 leading-relaxed italic">
                        "{{ $user->bio }}"
                    </p>
                @endif

                <!-- Social Links -->
                <div class="flex justify-center gap-3 mt-6">
                    @php
                        $socials = [
                            'github_url' => ['bg' => 'hover:bg-slate-700', 'icon' => 'GitHub'],
                            'linkedin_url' => ['bg' => 'hover:bg-blue-600', 'icon' => 'LinkedIn'],
                            'twitter_url' => ['bg' => 'hover:bg-sky-500', 'icon' => 'Twitter'],
                        ];
                    @endphp
                    @foreach ($socials as $field => $data)
                        @if ($user->$field)
                            <a href="{{ $user->$field }}" target="_blank"
                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-900 border border-slate-700 text-slate-400 hover:text-white transition-all {{ $data['bg'] }} shadow-sm"
                                title="{{ $data['icon'] }}">
                                <span class="text-[10px] font-bold uppercase">{{ substr($data['icon'], 0, 2) }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Quick Status Mini-Card -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Account Status</span>
                </div>
                <span
                    class="text-xs font-bold text-white bg-emerald-500/10 px-3 py-1 rounded-lg border border-emerald-500/20">Verified</span>
            </div>
        </div>

        <!-- Right Column: Details Grid -->
        <div class="lg:col-span-8">
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl overflow-hidden backdrop-blur-sm h-full">
                <div class="px-6 py-4 border-b border-slate-700 bg-slate-800/30">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Detailed Information</h2>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    @foreach ([['Email Address', $user->email, 'mail'], ['Phone Number', $user->phone ?: 'Not provided', 'phone'], ['Primary Location', $user->location ?: 'Global', 'map-pin'], ['Personal Website', $user->website ?: 'None', 'globe'], ['Account Created', $user->created_at->format('F d, Y'), 'calendar'], ['Last Active', $user->last_login_at?->diffForHumans() ?: 'First session', 'clock'], ['Two-Factor Auth', $user->two_factor_enabled ? 'Active (' . $user->two_factor_method . ')' : 'Disabled', 'shield'], ['Resume / CV', $user->resume_url ? 'Document Attached' : 'Not Uploaded', 'file']] as [$label, $value, $icon])
                        <div class="group">
                            <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-1">
                                {{ $label }}</p>
                            <div class="flex items-center gap-2">
                                <p class="text-white font-medium group-hover:text-indigo-300 transition-colors">
                                    {{ $value }}</p>
                                @if ($label == 'Resume / CV' && $user->resume_url)
                                    <a href="{{ $user->resume_url }}" target="_blank"
                                        class="text-indigo-400 hover:text-indigo-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Footer / Metadata Area -->
                <div class="mt-auto p-6 bg-slate-900/30 border-t border-slate-700/50 flex flex-wrap gap-6">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Role:</span>
                        <span class="text-xs text-slate-300 font-mono">Super_Admin</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">ID:</span>
                        <span
                            class="text-xs text-slate-300 font-mono">#USR-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
