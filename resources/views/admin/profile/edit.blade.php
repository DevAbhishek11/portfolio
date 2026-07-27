@extends('layouts.admin')
@section('title', 'Edit Profile')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Edit Profile</h1>
            <p class="text-slate-400 text-sm mt-1">Manage your public information and social presence.</p>
        </div>
        <a href="{{ route('admin.profile.index') }}"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-lg transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Profile
        </a>
    </div>

    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Main Form Area -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Information Card -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl overflow-hidden backdrop-blur-sm">
                    <div class="px-6 py-4 border-b border-slate-700 bg-slate-800/30">
                        <h2 class="text-xs font-bold text-indigo-400 uppercase tracking-widest">General Information</h2>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach ([['name', 'Full Name', 'text', true], ['title', 'Job Title', 'text', false], ['phone', 'Phone Number', 'text', false], ['location', 'Location', 'text', false], ['website', 'Website', 'url', false], ['github_url', 'GitHub URL', 'url', false], ['linkedin_url', 'LinkedIn URL', 'url', false], ['twitter_url', 'Twitter URL', 'url', false]] as [$field, $label, $type, $req])
                            <div class="space-y-1.5">
                                <label class="text-sm font-medium text-slate-300 ml-1">
                                    {{ $label }}{{ $req ? ' *' : '' }}
                                </label>
                                <input type="{{ $type }}" name="{{ $field }}"
                                    value="{{ old($field, $user->$field) }}" {{ $req ? 'required' : '' }}
                                    class="w-full bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-600"
                                    placeholder="Enter your {{ strtolower($label) }}">
                                @error($field)
                                    <p class="text-xs text-red-400 mt-1 ml-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach

                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-sm font-medium text-slate-300 ml-1">Bio</label>
                            <textarea name="bio" rows="4"
                                class="w-full bg-slate-900/50 border border-slate-600 text-white text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all"
                                placeholder="Write a short professional bio...">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Area -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Avatar Upload -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 text-center backdrop-blur-sm">
                    <h2 class="text-sm font-semibold text-white mb-4 text-left">Profile Photo</h2>
                    <div class="relative group w-32 h-32 mx-auto mb-4">
                        @if ($user->avatar)
                            <img src="{{ $user->avatar }}"
                                class="w-full h-full rounded-2xl object-cover ring-4 ring-slate-700 group-hover:ring-indigo-500/50 transition-all shadow-xl">
                        @else
                            <div
                                class="w-full h-full rounded-2xl bg-slate-700 flex items-center justify-center text-slate-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <input type="file" name="avatar" accept="image/*"
                        class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer">
                </div>

                <!-- Resume Upload -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 backdrop-blur-sm">
                    <h2 class="text-sm font-semibold text-white mb-2">Resume (PDF)</h2>
                    @if ($user->resume_url)
                        <div
                            class="flex items-center gap-3 p-3 bg-indigo-500/10 rounded-xl border border-indigo-500/20 mb-4">
                            <div class="bg-indigo-500/20 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-xs text-indigo-200 font-medium truncate">Current_CV.pdf</p>
                                <a href="{{ $user->resume_url }}" target="_blank"
                                    class="text-[10px] text-indigo-400 hover:underline">View File ↗</a>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="resume" accept=".pdf"
                        class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-white hover:file:bg-slate-600 cursor-pointer">
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl shadow-lg shadow-indigo-600/20 transition-all active:scale-[0.98]">
                    Save All Changes
                </button>
            </div>
        </div>
    </form>
@endsection
