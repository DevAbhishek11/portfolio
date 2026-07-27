@extends('layouts.admin')
@section('title', 'Edit Project')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <style>
        /* Quill Dark Theme Overrides */
        .ql-toolbar.ql-snow {
            border: 1px solid #334155 !important;
            background: #1e293b !important;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .ql-container.ql-snow {
            border: 1px solid #334155 !important;
            background: #0f172a !important;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            font-family: inherit;
        }

        .ql-editor {
            min-height: 300px;
            color: #cbd5e1;
            font-size: 0.95rem;
        }

        .ql-snow .ql-stroke {
            stroke: #94a3b8 !important;
        }

        .ql-snow .ql-fill {
            fill: #94a3b8 !important;
        }

        .ql-snow .ql-picker {
            color: #94a3b8 !important;
        }
    </style>
@endpush

@section('content')
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2">
                <a href="{{ route('admin.projects.index') }}" class="hover:text-indigo-400 transition-colors">Projects</a>
                <span>/</span>
                <span class="text-slate-300">Edit Mode</span>
            </nav>
            <h1 class="text-2xl font-bold text-white tracking-tight">{{ $project->title }}</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('projects.show', $project->slug) }}" target="_blank"
                class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-bold rounded-xl border border-slate-700 transition-all">
                Preview Live ↗
            </a>
            <a href="{{ route('admin.projects.index') }}"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-bold rounded-xl transition-all">
                Back to List
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.projects.update', $project->id) }}" enctype="multipart/form-data"
        id="projectForm">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Primary Content -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Basic Info Card -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 backdrop-blur-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Project
                                Title *</label>
                            <input type="text" name="title" id="titleInput"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all"
                                value="{{ old('title', $project->title) }}" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">URL
                                Slug</label>
                            <input type="text" name="slug" id="slugInput"
                                class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-slate-400 font-mono text-sm"
                                value="{{ old('slug', $project->slug) }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Short
                                Teaser *</label>
                            <input type="text" name="short_description"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm"
                                value="{{ old('short_description', $project->short_description) }}" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Full
                                Case Study</label>
                            <div id="quill-editor"></div>
                            <input type="hidden" name="description" id="description">
                        </div>
                    </div>
                </div>

                <!-- Tech Stack Manager -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-bold text-white uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            Technology Stack
                        </h2>
                        <button type="button" id="addTechBtn"
                            class="px-3 py-1 bg-indigo-600/10 text-indigo-400 hover:bg-indigo-600 hover:text-white text-[10px] font-bold rounded-lg border border-indigo-500/20 transition-all uppercase">
                            Add Tech
                        </button>
                    </div>
                    <div id="tech-rows" class="space-y-3"></div>
                </div>

                <!-- Gallery Section -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 backdrop-blur-sm">
                    <h2 class="text-xs font-bold text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Project Gallery
                    </h2>

                    @if ($project->images->count())
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                            @foreach ($project->images as $img)
                                <div class="group relative aspect-video rounded-xl overflow-hidden border border-slate-700">
                                    <img src="{{ asset('/' . $img->image_path) }}" class="w-full h-full object-cover">
                                    <div
                                        class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button type="button"
                                            class="delete-image-btn p-2 bg-rose-600 text-white rounded-full hover:scale-110 transition-transform"
                                            data-url="{{ route('admin.projects.delete-image', [$project->id, $img->id]) }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="relative group">
                        <input type="file" name="images[]" id="extraImages" multiple accept="image/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div
                            class="border-2 border-dashed border-slate-700 rounded-2xl p-8 text-center group-hover:border-indigo-500/50 transition-colors">
                            <svg class="w-8 h-8 text-slate-500 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" />
                            </svg>
                            <p class="text-sm text-slate-400 font-medium">Click or drag to add more screenshots</p>
                        </div>
                    </div>
                    <div id="extra-preview" class="grid grid-cols-4 gap-4 mt-4"></div>
                </div>
            </div>

            <!-- Right Column: Sidebar Settings -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Publication Card -->
                <div
                    class="bg-slate-800/80 border border-slate-700 rounded-2xl p-6 backdrop-blur-md sticky top-6 shadow-2xl shadow-black/20">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Publication Settings</h2>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Main
                                Category</label>
                            <select name="category"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm outline-none focus:border-indigo-500">
                                @foreach (['frontend', 'backend', 'fullstack'] as $cat)
                                    <option value="{{ $cat }}" @selected(old('category', $project->category) === $cat)>{{ ucfirst($cat) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Thumbnail
                                (Card Image)</label>
                            <div class="mb-3 rounded-xl overflow-hidden border border-slate-700 aspect-video bg-slate-900">
                                <img id="thumb-preview" src="{{ asset('/' . $project->thumbnail) }}"
                                    class="w-full h-full object-cover {{ !$project->thumbnail ? 'hidden' : '' }}">
                                @if (!$project->thumbnail)
                                    <div id="thumb-placeholder"
                                        class="w-full h-full flex items-center justify-center text-slate-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <input type="file" name="thumbnail" id="thumbInput" accept="image/*"
                                class="text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600/10 file:text-indigo-400 hover:file:bg-indigo-600/20">
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Demo
                                Video <span class="normal-case font-normal text-slate-600">(optional, max
                                    {{ round(config('portfolio.upload.max_video_size_kb') / 1024) }}MB)</span></label>
                            @if ($project->video_path)
                                <div class="flex items-center gap-3 p-3 bg-slate-900/50 rounded-xl border border-slate-700 mb-2">
                                    <video src="{{ asset('/' . $project->video_path) }}" class="w-24 h-14 object-cover rounded-lg" muted></video>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-slate-300 truncate">Current video attached</p>
                                        <label class="flex items-center gap-1.5 mt-1 text-[11px] text-red-400 cursor-pointer">
                                            <input type="checkbox" name="remove_video" value="1" class="rounded">
                                            Remove video
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime"
                                class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-white hover:file:bg-slate-600 cursor-pointer">
                            @error('video')
                                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Visibility</label>
                                <select name="status"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-white text-xs outline-none">
                                    @foreach (['draft', 'published', 'archived'] as $s)
                                        <option value="{{ $s }}" @selected(old('status', $project->status) === $s)>
                                            {{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Order</label>
                                <input type="number" name="sort_order"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-white text-xs"
                                    value="{{ old('sort_order', $project->sort_order) }}">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-700/50">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project->is_featured))
                                    class="w-5 h-5 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500/50 transition-all">
                                <span
                                    class="text-sm font-bold text-slate-300 group-hover:text-white transition-colors">Mark
                                    as Featured</span>
                            </label>
                        </div>

                        <button type="submit"
                            class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Sync Changes
                        </button>

                        <button type="button" id="deleteProjectBtn"
                            class="w-full py-3 bg-rose-500/10 hover:bg-rose-600 text-rose-500 hover:text-white font-bold rounded-xl border border-rose-500/20 transition-all">
                            Delete Project
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Hidden delete form — CSRF and method are rendered by Blade correctly here --}}
    <form id="del-project" method="POST" action="{{ route('admin.projects.destroy', $project->id) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        {{-- Load Quill JS first as a plain script tag --}}
        <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // ── Quill Editor ──────────────────────────────────────────
                const quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{
                                header: [2, 3, false]
                            }],
                            ['bold', 'italic', 'underline'],
                            ['link', 'image', 'code-block'],
                            ['clean']
                        ]
                    }
                });

                // Pre-fill existing content
                quill.root.innerHTML = {!! json_encode($project->description) !!};

                document.getElementById('projectForm').addEventListener('submit', function() {
                    document.getElementById('description').value = quill.root.innerHTML;
                });

                // ── Slug Logic ────────────────────────────────────────────
                let slugEdited = false;

                document.getElementById('slugInput').addEventListener('input', function() {
                    slugEdited = true;
                });

                document.getElementById('titleInput').addEventListener('input', function() {
                    if (slugEdited) return;
                    document.getElementById('slugInput').value = this.value
                        .toLowerCase().trim()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                });

                // ── Thumbnail Preview ─────────────────────────────────────
                document.getElementById('thumbInput').addEventListener('change', function() {
                    if (!this.files || !this.files[0]) return;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.getElementById('thumb-preview');
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                        const placeholder = document.getElementById('thumb-placeholder');
                        if (placeholder) placeholder.classList.add('hidden');
                    };
                    reader.readAsDataURL(this.files[0]);
                });

                // ── Gallery Preview ───────────────────────────────────────
                document.getElementById('extraImages').addEventListener('change', function() {
                    const container = document.getElementById('extra-preview');
                    container.innerHTML = '';
                    Array.from(this.files).forEach(function(file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className =
                                'aspect-video rounded-xl overflow-hidden border border-indigo-500/30';
                            div.innerHTML = '<img src="' + e.target.result +
                                '" class="w-full h-full object-cover">';
                            container.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                });

                // ── Tech Stack Rows ───────────────────────────────────────
                const techData = @json($project->techStacks);

                function addTechRow(name, category, icon) {
                    name = name || '';
                    category = category || 'framework';
                    icon = icon || '';

                    const categories = ['language', 'framework', 'database', 'tool', 'other'];
                    const options = categories.map(function(o) {
                        return '<option value="' + o + '"' + (o === category ? ' selected' : '') + '>' + o
                            .toUpperCase() + '</option>';
                    }).join('');

                    const container = document.getElementById('tech-rows');
                    const row = document.createElement('div');
                    row.className =
                        'flex flex-wrap md:flex-nowrap gap-3 items-center bg-slate-900/50 p-3 rounded-xl border border-slate-700/50';
                    row.innerHTML =
                        '<input type="text" name="tech_names[]" class="flex-[2] bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white text-xs outline-none" placeholder="Name (e.g. Laravel)" value="' +
                        name + '">' +
                        '<select name="tech_categories[]" class="flex-[1] bg-slate-900 border border-slate-700 rounded-lg px-2 py-2 text-slate-400 text-[10px] outline-none">' +
                        options + '</select>' +
                        '<input type="text" name="tech_icons[]" class="flex-[1] bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white text-xs outline-none" placeholder="Icon Class" value="' +
                        icon + '">' +
                        '<button type="button" class="remove-tech p-2 text-rose-500 hover:bg-rose-500/10 rounded-lg transition-colors">' +
                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>' +
                        '</button>';

                    row.querySelector('.remove-tech').addEventListener('click', function() {
                        row.remove();
                    });

                    container.appendChild(row);
                }

                document.getElementById('addTechBtn').addEventListener('click', function() {
                    addTechRow();
                });

                // Seed existing tech stacks
                if (techData.length) {
                    techData.forEach(function(t) {
                        addTechRow(t.name, t.category, t.icon || '');
                    });
                } else {
                    addTechRow();
                }

                // ── Delete Existing Gallery Image ─────────────────────────
                // Uses data-url attribute; CSRF token injected via Blade meta tag approach
                document.querySelectorAll('.delete-image-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        if (!confirm('Permanently remove this image from gallery?')) return;

                        const url = this.dataset.url;

                        // Build a proper form — Blade can't run inside JS strings,
                        // so we read the CSRF token from the existing meta tag or
                        // the hidden input already present in #projectForm.
                        const csrfToken = document.querySelector('input[name="_token"]').value;

                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = url;

                        const tokenInput = document.createElement('input');
                        tokenInput.type = 'hidden';
                        tokenInput.name = '_token';
                        tokenInput.value = csrfToken;

                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';

                        form.appendChild(tokenInput);
                        form.appendChild(methodInput);
                        document.body.appendChild(form);
                        form.submit();
                    });
                });

                // ── Delete Project ────────────────────────────────────────
                document.getElementById('deleteProjectBtn').addEventListener('click', function() {
                    if (confirm('Are you sure you want to permanently delete this project?')) {
                        document.getElementById('del-project').submit();
                    }
                });

            }); // end DOMContentLoaded
        </script>
    @endpush
@endsection
