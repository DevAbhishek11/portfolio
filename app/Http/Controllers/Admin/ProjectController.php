<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\ProjectTechStack;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function __construct(private ImageUploadService $imageService) {}

    public function index(Request $request)
    {
        $query = Project::with('techStacks')->latest();

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('search'))   $query->where('title', 'like', "%{$request->search}%");

        $projects = $query->paginate(15)->withQueryString();

        if ($request->ajax() || $request->boolean('ajax')) {
            return response()->json([
                'html' => view('admin.projects._table', compact('projects'))->render(),
            ]);
        }

        return view('admin.projects.index', compact('projects'));
    }

    public function ajaxList(Request $request)
    {
        $request->merge(['ajax' => true]);
        return $this->index($request);
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:projects,slug',
            'short_description' => 'required|string|max:255',
            'description'       => 'required|string',
            'thumbnail'         => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'github_url'        => 'nullable|url',
            'live_url'          => 'nullable|url',
            'category'          => 'required|in:frontend,backend,fullstack',
            'status'            => 'required|in:draft,published,archived',
            'is_featured'       => 'boolean',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'sort_order'        => 'integer|min:0',
            'images.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tech_names.*'      => 'nullable|string|max:100',
            'tech_categories.*' => 'nullable|in:language,framework,database,tool,other',
            'tech_icons.*'      => 'nullable|string|max:255',
        ]);

        $data['slug']        = $data['slug'] ?: Str::slug($data['title']);
        $data['user_id']     = session('admin_user_id');
        $data['is_featured'] = $request->boolean('is_featured');

        // 🔥 Remove fields that don't exist in projects table
        unset($data['tech_names'], $data['tech_categories'], $data['tech_icons'], $data['images']);

        // Upload Thumbnail
        $data['thumbnail'] = $this->imageService->upload($request->file('thumbnail'), 'projects');

        // Create Project
        $project = Project::create($data);

        // Additional Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $this->imageService->upload($image, 'projects');

                ProjectImage::create([
                    'project_id' => $project->id,
                    'image_path' => $path,
                    'sort_order' => $index,
                    'alt_text'   => $data['title'] . ' - Image ' . ($index + 1),
                ]);
            }
        }

        // Tech Stacks
        $this->syncTechStacks($project, $request);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(int $id)
    {
        $project = Project::with(['images', 'techStacks'])->findOrFail($id);
        return view('admin.projects.show', compact('project'));
    }

    public function edit(int $id)
    {
        $project = Project::with(['images', 'techStacks'])->findOrFail($id);
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, int $id)
    {
        $project = Project::findOrFail($id);

        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => "nullable|string|max:255|unique:projects,slug,{$id}",
            'short_description' => 'required|string|max:255',
            'description'       => 'required|string',
            'thumbnail'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'github_url'        => 'nullable|url',
            'live_url'          => 'nullable|url',
            'category'          => 'required|in:frontend,backend,fullstack',
            'status'            => 'required|in:draft,published,archived',
            'is_featured'       => 'boolean',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'sort_order'        => 'integer|min:0',
            'images.*'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tech_names.*'      => 'nullable|string|max:100',
            'tech_categories.*' => 'nullable|in:language,framework,database,tool,other',
            'tech_icons.*'      => 'nullable|string|max:255',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['is_featured'] = $request->boolean('is_featured');

        // === Remove fields that are NOT in $fillable ===
        unset(
            $data['tech_names'],
            $data['tech_categories'],
            $data['tech_icons'],
            $data['images']           // safety
        );

        // Handle Thumbnail
        if ($request->hasFile('thumbnail')) {
            // Optional: Delete old thumbnail if not using Media Library yet
            if ($project->thumbnail) {
                $this->imageService->delete($project->thumbnail);
            }
            $data['thumbnail'] = $this->imageService->upload($request->file('thumbnail'), 'projects');
        } else {
            unset($data['thumbnail']);
        }

        $project->update($data);

        // Additional Images
        if ($request->hasFile('images')) {
            $existingCount = $project->images()->count();

            foreach ($request->file('images') as $index => $image) {
                $path = $this->imageService->upload($image, 'projects');

                ProjectImage::create([
                    'project_id' => $project->id,
                    'image_path' => $path,
                    'sort_order' => $existingCount + $index,
                    'alt_text'   => $project->title ?? 'Project Image',
                ]);
            }
        }

        $this->syncTechStacks($project, $request);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(int $id)
    {
        $project = Project::with('images')->findOrFail($id);

        $this->imageService->delete($project->thumbnail);
        foreach ($project->images as $img) {
            $this->imageService->delete($img->image_path);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted.');
    }

    public function toggleFeatured(int $id)
    {
        $project = Project::findOrFail($id);
        $project->update(['is_featured' => ! $project->is_featured]);
        return back()->with('success', 'Featured status updated.');
    }

    public function toggleStatus(int $id)
    {
        $project = Project::findOrFail($id);
        $next = match ($project->status) {
            'draft'     => 'published',
            'published' => 'archived',
            default     => 'draft',
        };
        $project->update(['status' => $next]);
        return back()->with('success', "Status changed to {$next}.");
    }

    public function deleteImage(int $id, int $imageId)
    {
        $image = ProjectImage::where('project_id', $id)->findOrFail($imageId);
        $this->imageService->delete($image->image_path);
        $image->delete();
        return back()->with('success', 'Image removed.');
    }

    private function syncTechStacks(Project $project, Request $request): void
    {
        $project->techStacks()->delete();

        $names      = $request->input('tech_names', []);
        $categories = $request->input('tech_categories', []);
        $icons      = $request->input('tech_icons', []);

        foreach ($names as $index => $name) {
            if (empty(trim($name))) continue;
            ProjectTechStack::create([
                'project_id' => $project->id,
                'name'       => trim($name),
                'category'   => $categories[$index] ?? 'other',
                'icon'       => $icons[$index] ?? null,
            ]);
        }
    }
}
