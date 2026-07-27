@extends('layouts.admin')
@section('title', 'Edit Blog Post')
@section('breadcrumb', 'Blogs / Edit')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <style>
        /* Quill Custom Dark Theme UI - Same as Create */
        .ql-toolbar.ql-snow {
            border: 1px solid #2a2a3a !important;
            background: #16161e !important;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }

        .ql-container.ql-snow {
            border: 1px solid #2a2a3a !important;
            background: #0f0f14 !important;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }

        .ql-editor {
            min-height: 400px;
            color: #e4e4e7;
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            line-height: 1.6;
        }

        .ql-editor.ql-blank::before {
            color: #3f3f46 !important;
            font-style: normal;
        }

        .ql-snow .ql-stroke {
            stroke: #71717a !important;
        }

        .ql-snow .ql-fill {
            fill: #71717a !important;
        }

        .ql-snow .ql-picker {
            color: #71717a !important;
        }
    </style>
@endpush

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Edit Blog Post</h1>
            <p class="text-zinc-400 text-sm mt-1">Update your post</p>
        </div>
        <a href="{{ route('admin.blogs.index') }}"
            class="px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 text-sm font-medium rounded-lg border border-zinc-700 transition-colors">
            ← Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data" id="blogForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- Main Editor Section --}}
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl p-6 space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Post Title
                            *</label>
                        <input type="text" name="title" id="blogTitle"
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-violet-500/50 outline-none transition-all placeholder:text-zinc-700"
                            value="{{ old('title', $blog->title) }}" placeholder="Enter an engaging title..." required>
                        @error('title')
                            <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Slug (URL
                            Path)</label>
                        <input type="text" name="slug" id="blogSlug"
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-zinc-400 font-mono text-sm outline-none"
                            value="{{ old('slug', $blog->slug) }}" placeholder="auto-generated-slug">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Excerpt</label>
                        <textarea name="excerpt" rows="2"
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-zinc-300 text-sm outline-none focus:border-violet-500 transition-all placeholder:text-zinc-700"
                            placeholder="A short summary for the blog feed...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                        <p class="text-[10px] text-zinc-600 mt-1">If left blank, it will be generated from the content.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Article Content
                            *</label>
                        <div id="quill-editor">{!! old('content', $blog->content) !!}</div>
                        <input type="hidden" name="content" id="content">
                        @error('content')
                            <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Sidebar: Settings & Meta --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- Publishing Card --}}
                <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl p-6">
                    <h2 class="text-xs font-bold text-white uppercase tracking-widest mb-6 border-b border-zinc-800 pb-4">
                        Publishing</h2>

                    <div class="space-y-5">
                        <div>
                            <label
                                class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Status</label>
                            <select name="status" id="statusSelect"
                                class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2.5 text-white text-sm outline-none focus:border-violet-500">
                                <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>
                                    Draft</option>
                                <option value="published"
                                    {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived"
                                    {{ old('status', $blog->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Featured
                                Image</label>
                            <div
                                class="relative group cursor-pointer border-2 border-dashed border-zinc-800 rounded-xl p-4 bg-zinc-950 hover:border-violet-500/50 transition-all text-center">
                                <input type="file" name="featured_image" id="featuredImageInput" accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                <div id="preview-container" class="{{ $blog->featured_image ? 'hidden' : '' }} space-y-2">
                                    <svg class="w-8 h-8 text-zinc-700 mx-auto" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-[10px] text-zinc-500 uppercase font-bold">Upload New Cover</p>
                                </div>

                                <img id="image-preview"
                                    src="{{ $blog->featured_image ? asset($blog->featured_image) : '' }}"
                                    class="{{ $blog->featured_image ? '' : 'hidden' }} w-full h-32 object-cover rounded-lg">
                            </div>
                            @if ($blog->featured_image)
                                <p class="text-[10px] text-zinc-500 mt-2">Current image shown above. Upload new one to
                                    replace.</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Publish
                                Date</label>
                            <input type="datetime-local" name="published_at"
                                class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2 text-zinc-300 text-xs outline-none"
                                value="{{ old('published_at', $blog->published_at?->format('Y-m-d\TH:i')) }}">
                        </div>

                        <label
                            class="flex items-center gap-3 p-3 bg-zinc-950 border border-zinc-800 rounded-xl cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $blog->is_featured))
                                class="w-4 h-4 rounded border-zinc-800 bg-zinc-900 text-violet-600 focus:ring-violet-500/50">
                            <span class="text-xs font-medium text-zinc-400">Mark as Featured</span>
                        </label>

                        <div class="pt-4 space-y-3">
                            <button type="submit"
                                class="w-full py-3 bg-violet-600 hover:bg-violet-500 text-white font-bold rounded-xl shadow-lg shadow-violet-600/20 transition-all">
                                Update Post
                            </button>
                            <button type="button" id="saveDraftBtn"
                                class="w-full py-3 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold rounded-xl text-sm transition-all border border-zinc-700">
                                Save as Draft
                            </button>
                        </div>
                    </div>
                </div>

                {{-- SEO & Categorization --}}
                <div class="bg-zinc-900/50 border border-zinc-800 rounded-2xl p-6 space-y-5">
                    <h2 class="text-xs font-bold text-white uppercase tracking-widest mb-2 border-b border-zinc-800 pb-4">
                        SEO & Metadata</h2>

                    <div>
                        <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Category</label>
                        <input type="text" name="category" value="{{ old('category', $blog->category) }}"
                            placeholder="e.g., Tech"
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2 text-white text-sm outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Tags</label>
                        <input type="text" name="tags"
                            value="{{ old('tags', is_array($blog->tags) ? implode(', ', $blog->tags) : $blog->tags) }}"
                            placeholder="laravel, php, web"
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2 text-white text-sm outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Meta
                            Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}"
                            placeholder="Search engine title"
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2 text-white text-sm outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Meta
                            Description</label>
                        <textarea name="meta_description" rows="3" placeholder="SEO summary..."
                            class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-2 text-white text-sm outline-none">{{ old('meta_description', $blog->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const quill = new Quill('#quill-editor', {
                    theme: 'snow',
                    placeholder: 'Start writing your story...',
                    modules: {
                        toolbar: [
                            [{
                                header: [2, 3, 4, false]
                            }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{
                                list: 'ordered'
                            }, {
                                list: 'bullet'
                            }],
                            ['link', 'image', 'code-block'],
                            ['clean']
                        ]
                    }
                });

                // Sync Quill → Hidden Input
                document.getElementById('blogForm').addEventListener('submit', function() {
                    document.getElementById('content').value = quill.root.innerHTML;
                });

                // Slug Auto-generation
                let slugEdited = false;
                const slugInput = document.getElementById('blogSlug');
                const titleInput = document.getElementById('blogTitle');

                slugInput.addEventListener('input', () => slugEdited = true);

                titleInput.addEventListener('input', function() {
                    if (slugEdited) return;
                    slugInput.value = this.value
                        .toLowerCase().trim()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                });

                // Save as Draft
                document.getElementById('saveDraftBtn').addEventListener('click', function() {
                    document.getElementById('statusSelect').value = 'draft';
                    document.getElementById('blogForm').submit();
                });

                // Featured Image Preview (New Upload)
                document.getElementById('featuredImageInput').addEventListener('change', function() {
                    if (!this.files || !this.files[0]) return;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.getElementById('image-preview');
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                        document.getElementById('preview-container').classList.add('hidden');
                    };
                    reader.readAsDataURL(this.files[0]);
                });

            });
        </script>
    @endpush
@endsection
