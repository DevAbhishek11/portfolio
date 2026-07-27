@extends('layouts.admin')
@section('title', 'Add Skill')
@section('breadcrumb', 'Skills / Add')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold text-txt-primary">Add Skill</h1>
        <a href="{{ route('admin.skills.index') }}" class="btn-outline text-sm px-4 py-2">← Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.skills.store') }}">
                @csrf
                <div class="admin-card p-6 space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="admin-label">Skill Name *</label>
                            <input type="text" name="name" class="admin-input" value="{{ old('name') }}"
                                placeholder="e.g. Laravel" required>
                            @error('name')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="admin-label">Category *</label>
                            <select name="category" class="admin-input" required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ $cat }}
                                    </option>
                                @endforeach
                                <option value="{{ old('category') }}" @if (!in_array(old('category'), $categories)) selected @endif>
                                    {{ old('category') }}
                                </option>
                            </select>
                            <p class="text-xs text-txt-secondary mt-1">Or type a custom category</p>
                        </div>
                    </div>

                    <div>
                        <label class="admin-label">Proficiency Level: <span id="levelDisplay"
                                class="text-accent-1 font-semibold">{{ old('level', 80) }}%</span></label>
                        <input type="range" name="level" id="levelSlider" min="1" max="100"
                            value="{{ old('level', 80) }}"
                            class="w-full accent-accent-1 h-2 rounded-full cursor-pointer mt-2"
                            oninput="document.getElementById('levelDisplay').textContent = this.value + '%'">
                        <div class="flex justify-between text-xs text-txt-secondary mt-1">
                            <span>Beginner</span><span>Intermediate</span><span>Expert</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="admin-label">Colour *</label>
                            <div class="flex items-center gap-3">
                                <input type="color" name="color" id="colorPicker" value="{{ old('color', '#8b5cf6') }}"
                                    class="w-12 h-10 rounded-lg cursor-pointer border border-admin-border bg-transparent p-0.5"
                                    oninput="document.getElementById('colorHex').value = this.value">
                                <input type="text" id="colorHex" value="{{ old('color', '#8b5cf6') }}"
                                    class="admin-input flex-1 font-mono text-sm"
                                    oninput="document.getElementById('colorPicker').value = this.value">
                            </div>
                            @error('color')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="admin-label">Sort Order</label>
                            <input type="number" name="sort_order" class="admin-input" value="{{ old('sort_order', 0) }}"
                                min="0">
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', true))
                                class="w-4 h-4 accent-accent-1">
                            <span class="text-sm text-txt-secondary">Show on radar chart</span>
                        </label>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="btn-anime">Save Skill</button>
                        <a href="{{ route('admin.skills.index') }}" class="btn-outline">Cancel</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Preview --}}
        <div>
            <div class="admin-card p-5 sticky top-24">
                <h3 class="text-sm font-semibold text-txt-primary mb-4">Live Preview</h3>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-admin-surface border border-admin-border mb-4">
                    <div id="previewDot" class="w-3 h-3 rounded-full flex-shrink-0" style="background:#8b5cf6;"></div>
                    <div class="flex-1 min-w-0">
                        <p id="previewName" class="text-sm font-medium text-txt-primary">Skill Name</p>
                        <div class="mt-1.5 h-1.5 bg-admin-border rounded-full overflow-hidden">
                            <div id="previewBar" class="h-full rounded-full transition-all duration-300"
                                style="width:80%;background:#8b5cf6;"></div>
                        </div>
                    </div>
                    <span id="previewPct" class="text-xs font-mono text-txt-secondary">80%</span>
                </div>
                <p class="text-xs text-txt-secondary">The radar chart updates live when you save. Featured skills appear on
                    the about page chart.</p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const nameInput = document.querySelector('[name="name"]');
            const colorPicker = document.getElementById('colorPicker');
            const levelSlider = document.getElementById('levelSlider');

            function updatePreview() {
                const name = nameInput.value || 'Skill Name';
                const color = colorPicker.value;
                const level = levelSlider.value;
                document.getElementById('previewName').textContent = name;
                document.getElementById('previewDot').style.background = color;
                document.getElementById('previewBar').style.background = color;
                document.getElementById('previewBar').style.width = level + '%';
                document.getElementById('previewPct').textContent = level + '%';
            }
            nameInput.addEventListener('input', updatePreview);
            colorPicker.addEventListener('input', updatePreview);
            levelSlider.addEventListener('input', updatePreview);
        </script>
    @endpush
@endsection
