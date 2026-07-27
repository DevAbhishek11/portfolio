@extends('layouts.admin')
@section('title', 'Security')

@section('content')
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Security Settings</h1>
            <p class="text-slate-400 text-sm mt-1">Manage your account protection and authentication methods.</p>
        </div>
        <a href="{{ route('admin.profile.index') }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Change Password Card --}}
        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 backdrop-blur-sm">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-indigo-500/10 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-white">Update Password</h2>
            </div>

            <form method="POST" action="{{ route('admin.profile.update-password') }}" class="space-y-5">
                @csrf @method('PUT')

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-slate-300 ml-1">Current Password</label>
                    <input type="password" name="current_password" required
                        class="w-full bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                    @error('current_password')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-slate-300 ml-1">New Password</label>
                    <input type="password" name="password" required oninput="checkStrength(this.value)"
                        class="w-full bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">

                    {{-- Strength Meter --}}
                    <div class="h-1.5 w-full bg-slate-700 rounded-full mt-3 overflow-hidden">
                        <div id="strength-bar" class="h-full w-0 transition-all duration-500 bg-red-500"></div>
                    </div>
                    <p id="strength-text" class="text-[10px] uppercase tracking-wider font-bold mt-1 text-slate-500"></p>

                    @error('password')
                        <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-slate-300 ml-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                </div>

                <button type="submit"
                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all active:scale-[0.98]">
                    Change Password
                </button>
            </form>
        </div>

        {{-- 2FA Card --}}
        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 backdrop-blur-sm">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-500/10 rounded-lg">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-white">Two-Factor Auth</h2>
                </div>
                <span
                    class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full {{ $user->two_factor_enabled ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-slate-700 text-slate-400' }}">
                    {{ $user->two_factor_enabled ? 'Active' : 'Inactive' }}
                </span>
            </div>

            @if (session('qr_url'))
                <div class="p-6 rounded-2xl mb-6 text-center bg-white">
                    <img src="{{ session('qr_url') }}" alt="QR Code" class="mx-auto w-40 h-40">
                    <p class="text-[11px] text-slate-500 mt-4 font-mono uppercase tracking-tight">Secret:
                        {{ session('tf_secret') }}</p>
                </div>
            @endif

            @if (session('setup_mode'))
                <form method="POST" action="{{ route('admin.profile.verify-2fa-setup') }}"
                    class="mb-6 p-4 bg-slate-900/50 rounded-xl border border-slate-700">
                    @csrf
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Verification Code</label>
                    <div class="flex gap-2">
                        <input type="text" name="code" placeholder="000000" maxlength="6" required
                            class="flex-1 bg-slate-800 border border-slate-600 text-white text-center text-lg tracking-[0.5em] font-bold rounded-lg px-4 py-2 outline-none focus:border-indigo-500">
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 rounded-lg font-bold transition-all">Verify</button>
                    </div>
                </form>
            @endif

            @if (!$user->two_factor_enabled)
                <form method="POST" action="{{ route('admin.profile.enable-2fa') }}" class="space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-slate-300 ml-1">Preferred Method</label>
                        <select name="method"
                            class="w-full bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl px-4 py-2.5 outline-none focus:border-indigo-500 appearance-none">
                            <option value="email_otp">Email OTP</option>
                            <option value="auth_app">Authenticator App (TOTP)</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full py-3 border border-indigo-500/50 text-indigo-400 hover:bg-indigo-500 hover:text-white font-bold rounded-xl transition-all">
                        Setup 2FA
                    </button>
                </form>
            @else
                <div class="space-y-4">
                    <div class="p-4 bg-slate-900/50 rounded-xl border border-slate-700">
                        <p class="text-slate-400 text-sm italic text-center">Protected via <span
                                class="text-white font-bold">{{ strtoupper(str_replace('_', ' ', $user->two_factor_method)) }}</span>
                        </p>
                    </div>
                    <form method="POST" action="{{ route('admin.profile.disable-2fa') }}" class="space-y-3">
                        @csrf
                        <input type="password" name="password" placeholder="Confirm password to disable" required
                            class="w-full bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl px-4 py-2.5 outline-none focus:border-red-500">
                        <button type="submit"
                            class="w-full py-3 bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white font-bold rounded-xl transition-all">
                            Disable Protection
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Login History --}}
        <div class="bg-slate-800/50 border border-slate-700 rounded-2xl overflow-hidden lg:col-span-2 backdrop-blur-sm">
            <div class="px-6 py-4 border-b border-slate-700 flex items-center justify-between bg-slate-800/30">
                <h2 class="text-sm font-bold text-white uppercase tracking-widest">Recent Login Activity</h2>
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900/30">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Device/IP
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @forelse($loginHistory as $attempt)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 text-sm text-slate-300">{{ $attempt->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-xs font-mono text-slate-400 bg-slate-900 px-2 py-1 rounded">{{ $attempt->ip_address }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($attempt->successful)
                                        <span class="flex items-center text-xs font-bold text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-2"></span> Success
                                        </span>
                                    @else
                                        <span class="flex items-center text-xs font-bold text-red-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-400 mr-2"></span> Failed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-500">{{ $attempt->failure_reason ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-500 italic text-sm">No recent
                                    activity detected.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function checkStrength(val) {
                let s = 0;
                if (val.length >= 8) s++;
                if (/[A-Z]/.test(val)) s++;
                if (/[a-z]/.test(val)) s++;
                if (/[0-9]/.test(val)) s++;
                if (/[^A-Za-z0-9]/.test(val)) s++;

                const pcts = ['0%', '20%', '40%', '60%', '80%', '100%'];
                const bgClasses = ['bg-red-500', 'bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-emerald-500',
                    'bg-emerald-400'
                ];
                const textColors = ['text-red-500', 'text-red-500', 'text-orange-500', 'text-yellow-500', 'text-emerald-500',
                    'text-emerald-400'
                ];
                const labels = ['', 'Very weak', 'Weak', 'Fair', 'Strong', 'Very strong'];

                const bar = document.getElementById('strength-bar');
                const text = document.getElementById('strength-text');

                bar.style.width = pcts[s];
                // Reset classes
                bar.className = 'h-full transition-all duration-500 ' + bgClasses[s];
                text.className = 'text-[10px] uppercase tracking-wider font-bold mt-1 ' + textColors[s];
                text.textContent = labels[s];
            }
        </script>
    @endpush
@endsection
