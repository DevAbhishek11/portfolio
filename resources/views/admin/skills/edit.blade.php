@extends('layouts.admin')
@section('title', 'Edit Skill')
@section('breadcrumb', 'Skills / Edit')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-txt-primary">Edit: {{ $skill->name }}</h1>
        <a href="{{ route('admin.skills.index') }}" class="btn-outline text-sm px-4 py-2">← Back</a>
    </div>

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.skills.update', $skill->id) }}">
            @csrf @method('PUT')
            <div class="admin-card p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Skill Name *</label>
                        <input type="text" name="name" class="admin-input" value="{{ old('name', $skill->name) }}"
                            required>
                    </div>
                    <div>
                        <label class="admin-label">Category *</label>
                        <input type="text" name="category" class="admin-input"
                            value="{{ old('category', $skill->category) }}" list="cat-list" required>
                        <datalist id="cat-list">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <div>
                    <label class="admin-label">
                        Proficiency: <span id="lvlOut"
                            class="text-accent-1 font-semibold">{{ old('level', $skill->level) }}%</span>
                    </label>
                    <input type="range" name="level" min="1" max="100"
                        value="{{ old('level', $skill->level) }}"
                        class="w-full accent-accent-1 h-2 rounded-full cursor-pointer mt-2"
                        oninput="document.getElementById('lvlOut').textContent = this.value + '%'">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label">Colour</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color" id="editColor" value="{{ old('color', $skill->color) }}"
                                class="w-12 h-10 rounded-lg cursor-pointer border border-admin-border bg-transparent p-0.5"
                                oninput="document.getElementById('editHex').value = this.value">
                            <input type="text" id="editHex" value="{{ old('color', $skill->color) }}"
                                class="admin-input flex-1 font-mono text-sm"
                                oninput="document.getElementById('editColor').value = this.value">
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Sort Order</label>
                        <input type="number" name="sort_order" class="admin-input"
                            value="{{ old('sort_order', $skill->sort_order) }}" min="0">
                    </div>
                </div>

                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $skill->is_featured))
                        class="w-4 h-4 accent-accent-1">
                    <span class="text-sm text-txt-secondary">Show on radar chart</span>
                </label>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-anime">Save Changes</button>
                    <form id="del-this" method="POST" action="{{ route('admin.skills.destroy', $skill->id) }}"
                        class="inline">
                        @csrf @method('DELETE')
                    </form>
                    <button type="button" onclick="confirmDelete('del-this')"
                        class="px-5 py-2.5 rounded-xl bg-accent-3/10 border border-accent-3/25 text-red-400 text-sm hover:bg-accent-3/20 transition-all">
                        Delete Skill
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
