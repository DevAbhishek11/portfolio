@extends('layouts.admin')
@section('title', 'New Project')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <style>
        /* Quill Dark Mode UI */
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
        }

        .ql-editor {
            min-height: 250px;
            color: #cbd5e1;
            font-size: 0.95rem;
        }

        .ql-editor.ql-blank::before {
            color: #64748b !important;
            font-style: normal;
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
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Create New Project</h1>
            <p class="text-slate-400 text-sm mt-1">Fill in the details to showcase your latest work.</p>
        </div>
        <a href="{{ route('admin.projects.index') }}"
            class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-bold rounded-xl border border-slate-700 transition-all">
            ← Back to Projects
        </a>
    </div>

    <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data" id="projectForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Left Column: Core Content -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Project Details Card -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Project
                                Title *</label>
                            <input type="text" name="title" id="titleInput"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all placeholder:text-slate-600"
                                value="{{ old('title') }}" placeholder="Enter a catchy title..." required>
                            @error('title')
                                <p class="text-rose-500 text-xs mt-2 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">URL
                                Slug</label>
                            <input type="text" name="slug" id="slugInput"
                                class="w-full bg-slate-900/50 border border-slate-700 rounded-xl px-4 py-3 text-slate-400 font-mono text-sm outline-none"
                                value="{{ old('slug') }}" placeholder="auto-generated-slug">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Short
                                Description *</label>
                            <textarea name="short_description" rows="2"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-indigo-500 placeholder:text-slate-600"
                                required placeholder="Briefly explain what this project is about...">{{ old('short_description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Full
                                Case Study / Content *</label>
                            <div id="quill-editor"></div>
                            <input type="hidden" name="description" id="description">
                        </div>
                    </div>
                </div>

                <!-- Tech Stack Card -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-bold text-white uppercase tracking-widest flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            Technologies Used
                        </h2>
                        <button type="button" id="addTechBtn"
                            class="text-indigo-400 hover:text-white text-[10px] font-bold uppercase tracking-wider transition-colors">
                            + Add Technology
                        </button>
                    </div>
                    <div id="tech-rows" class="space-y-3">
                        <!-- Rows injected via JS -->
                    </div>
                </div>

                <!-- Media Gallery Card -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">Project
                        Screenshots</label>
                    <div class="relative group">
                        <input type="file" name="images[]" id="extraImages" multiple accept="image/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div
                            class="border-2 border-dashed border-slate-700 group-hover:border-indigo-500/50 rounded-2xl p-10 text-center transition-all bg-slate-900/30">
                            <svg class="w-10 h-10 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm text-slate-400">Click or drag images to build your gallery</p>
                        </div>
                    </div>
                    <div id="extra-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6"></div>
                </div>
            </div>

            <!-- Right Column: Sidebar Options -->
            <div class="lg:col-span-4 space-y-6">

                <!-- Main Settings Sidebar -->
                <div
                    class="bg-slate-800/80 border border-slate-700 rounded-2xl p-6 backdrop-blur-md sticky top-6 shadow-xl">
                    <h2
                        class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-700/50 pb-4">
                        Project Config</h2>

                    <div class="space-y-5">
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Category
                                *</label>
                            <select name="category"
                                class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2.5 text-white text-sm outline-none focus:border-indigo-500">
                                @foreach (['frontend', 'backend', 'fullstack'] as $cat)
                                    <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ ucfirst($cat) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Main
                                Thumbnail *</label>
                            <div
                                class="mb-4 rounded-xl overflow-hidden border-2 border-dashed border-slate-700 aspect-video bg-slate-900 flex items-center justify-center relative group">
                                <img id="thumb-preview" class="absolute inset-0 w-full h-full object-cover hidden">
                                <div id="thumb-placeholder" class="text-center p-4">
                                    <span
                                        class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter group-hover:text-slate-300">No
                                        Image Selected</span>
                                </div>
                                <input type="file" name="thumbnail" id="thumbnail" accept="image/*" required
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Visibility</label>
                                <select name="status" id="statusSelect"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-white text-xs outline-none">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Sorting</label>
                                <input type="number" name="sort_order"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-white text-xs"
                                    value="{{ old('sort_order', 0) }}">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Links</label>
                                <input type="url" name="github_url"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-white text-xs mb-2"
                                    placeholder="GitHub URL">
                                <input type="url" name="live_url"
                                    class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-white text-xs"
                                    placeholder="Live Site URL">
                            </div>
                        </div>

                        <label
                            class="flex items-center gap-3 p-3 bg-slate-900/50 rounded-xl border border-slate-700/50 cursor-pointer group">
                            <input type="checkbox" name="is_featured" value="1"
                                class="w-5 h-5 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-500/50">
                            <span class="text-xs font-bold text-slate-400 group-hover:text-white transition-colors">Feature
                                on Homepage</span>
                        </label>

                        <div class="pt-4 flex flex-col gap-3">
                            <button type="submit"
                                class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Create Project
                            </button>
                            <button type="button" id="saveDraftBtn"
                                class="w-full py-3 bg-slate-700 hover:bg-slate-600 text-slate-300 font-bold rounded-xl text-sm transition-all">
                                Save as Draft
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        {{-- Load Quill JS first as a plain script tag so it's available when the inline script runs --}}
        <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // ── Quill Editor ─────────────────────────────────────────
                const quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Write your project case study here...',
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

                document.getElementById('projectForm').addEventListener('submit', function() {
                    document.getElementById('description').value = quill.root.innerHTML;
                });

                // ── Save as Draft ─────────────────────────────────────────
                document.getElementById('saveDraftBtn').addEventListener('click', function() {
                    document.getElementById('statusSelect').value = 'draft';
                    document.getElementById('projectForm').submit();
                });

                // ── Slug Generator ────────────────────────────────────────
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
                document.getElementById('thumbnail').addEventListener('change', function() {
                    if (!this.files || !this.files[0]) return;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.getElementById('thumb-preview');
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                        document.getElementById('thumb-placeholder').classList.add('hidden');
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
                                'aspect-video rounded-xl overflow-hidden border border-slate-700';
                            div.innerHTML = '<img src="' + e.target.result +
                                '" class="w-full h-full object-cover">';
                            container.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                });

                // ── Tech Stack Manager ────────────────────────────────────
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
                        'flex flex-wrap md:flex-nowrap gap-3 items-center bg-slate-900/40 p-3 rounded-xl border border-slate-700/50';
                    row.innerHTML =
                        '<input type="text" name="tech_names[]" class="flex-[2] bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white text-xs outline-none" placeholder="Name (e.g. React)" value="' +
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

                // Add one empty row on load
                addTechRow();

            }); // end DOMContentLoaded
        </script>
    @endpush
@endsection
