@extends('layouts.admin')
@section('title', 'View Query')

@section('content')
    <!-- Header with Breadcrumbs & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">
                <a href="{{ route('admin.contacts.index') }}" class="hover:text-indigo-400 transition-colors">Contacts</a>
                <span>/</span>
                <span class="text-slate-300">View Query</span>
            </nav>
            <h1 class="text-2xl font-bold text-white tracking-tight">Conversation Details</h1>
        </div>
        <a href="{{ route('admin.contacts.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-bold rounded-xl border border-slate-700 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Inbox
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Conversation Thread -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Original Message -->
            <div
                class="bg-slate-800/50 border border-slate-700 rounded-2xl overflow-hidden backdrop-blur-sm shadow-xl shadow-black/20">
                <div class="px-6 py-4 bg-slate-900/40 border-b border-slate-700 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-full bg-indigo-600/20 flex items-center justify-center text-indigo-400 font-bold border border-indigo-500/20">
                            {{ substr($contact->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-white leading-tight">{{ $contact->subject }}</h2>
                            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">
                                {{ $contact->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                    @php
                        $statusColors = [
                            'unread' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                            'read' => 'bg-slate-700/30 text-slate-400 border-slate-600/30',
                            'replied' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                            'archived' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                        ];
                    @endphp
                    <span
                        class="px-3 py-1 rounded-full border text-[10px] font-bold uppercase {{ $statusColors[$contact->status] ?? $statusColors['read'] }}">
                        {{ $contact->status }}
                    </span>
                </div>
                <div class="p-8">
                    <div
                        class="bg-slate-900/50 rounded-2xl p-6 text-slate-300 leading-relaxed text-sm border border-slate-700/50 whitespace-pre-wrap">
                        {{ $contact->message }}
                    </div>
                </div>
            </div>

            <!-- Thread Replies -->
            @foreach ($contact->replies as $reply)
                <div class="relative pl-8 md:pl-12">
                    <!-- Thread Connector Line -->
                    <div class="absolute left-0 md:left-4 top-0 bottom-0 w-px bg-slate-700"></div>

                    <div class="bg-slate-800/30 border border-slate-700 rounded-2xl p-6 relative">
                        <!-- Reply Indicator Dot -->
                        <div
                            class="absolute -left-[33px] md:-left-[21px] top-8 w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.5)]">
                        </div>

                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Admin Reply</span>
                                <span class="text-slate-600 text-xs">•</span>
                                <span
                                    class="text-[10px] text-slate-500 font-bold">{{ $reply->sent_at?->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                        <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-wrap">{{ $reply->message }}</p>
                    </div>
                </div>
            @endforeach

            <!-- Reply Composition Area -->
            @if ($contact->status !== 'archived')
                <div
                    class="bg-slate-800/80 border border-indigo-500/30 rounded-2xl p-8 backdrop-blur-md shadow-2xl shadow-indigo-500/5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-white uppercase tracking-widest">Compose Reply</h2>
                    </div>

                    <form method="POST" action="{{ route('admin.contacts.reply', $contact->id) }}">
                        @csrf
                        <div class="space-y-5">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Email
                                    Subject</label>
                                <input type="text" name="subject"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all"
                                    value="{{ old('subject', 'Re: ' . $contact->subject) }}" required>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Message
                                    Body</label>
                                <textarea name="message" rows="6"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="Type your response here..." required>{{ old('message') }}</textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="group px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl transition-all flex items-center gap-2 shadow-lg shadow-indigo-600/20">
                                    Send Reply
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Sender Details -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl overflow-hidden backdrop-blur-sm">
                <div class="px-5 py-4 border-b border-slate-700 bg-slate-900/20">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sender Metadata</h2>
                </div>
                <div class="p-6 space-y-5">
                    @foreach ([['Name', $contact->name, 'user'], ['Email', $contact->email, 'mail'], ['Phone', $contact->phone ?: 'Not provided', 'phone'], ['IP Address', $contact->ip_address ?: 'Unknown', 'globe']] as [$label, $value, $icon])
                        <div>
                            <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-1">
                                {{ $label }}</p>
                            <p class="text-sm text-white font-medium truncate">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Status Control -->
            <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 backdrop-blur-sm">
                <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 text-center">Manage Lifecycle
                </h2>
                <form method="POST" action="{{ route('admin.contacts.update-status', $contact->id) }}">
                    @csrf
                    <div class="relative">
                        <select name="status" onchange="this.form.submit()"
                            class="w-full bg-slate-900 border border-slate-700 text-white text-sm rounded-xl px-4 py-3 outline-none focus:border-indigo-500 appearance-none transition-all cursor-pointer">
                            @foreach (['unread', 'read', 'replied', 'archived'] as $s)
                                <option value="{{ $s }}" @selected($contact->status === $s)>{{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </form>
                <p class="mt-4 text-[10px] text-slate-500 text-center italic">Changing the status will automatically log the
                    action in the system history.</p>
            </div>
        </div>
    </div>
@endsection
