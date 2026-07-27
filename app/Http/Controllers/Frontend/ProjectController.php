<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request, ?string $category = null)
    {
        $activeCategory = $category ?? $request->query('category');
        $search         = $request->query('search');
        $sort           = $request->query('sort', 'order');

        $query = Project::published()->with('techStacks');

        if ($activeCategory && $activeCategory !== 'all') {
            $query->where('category', $activeCategory);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        match ($sort) {
            'newest'  => $query->orderByDesc('created_at'),
            'popular' => $query->orderByDesc('view_count'),
            default   => $query->orderBy('sort_order')->orderByDesc('created_at'),
        };

        $projects   = $query->paginate(9)->withQueryString();
        $categories = ['all', 'frontend', 'backend', 'fullstack'];
        $active     = $activeCategory ?: 'all';

        $viewData = compact('projects', 'categories', 'active', 'search', 'sort');

        if ($request->ajax() || $request->boolean('ajax')) {
            return response()->json([
                'html'       => view('frontend.projects._grid', $viewData)->render(),
                'pagination' => $projects->hasPages() ? view('frontend.projects._pagination', $viewData)->render() : '',
                'count'      => $projects->total(),
            ]);
        }

        return view('frontend.projects.index', $viewData);
    }

    public function ajaxList(Request $request)
    {
        $request->merge(['ajax' => true]);
        return $this->index($request);
    }

    public function show(string $slug)
    {
        $project = Project::published()
            ->with(['images', 'techStacks'])
            ->where('slug', $slug)
            ->firstOrFail();

        $project->increment('view_count');

        $related = Project::published()
            ->where('category', $project->category)
            ->where('id', '!=', $project->id)
            ->with('techStacks')
            ->limit(3)
            ->get();

        $prev = Project::published()->where('id', '<', $project->id)->orderByDesc('id')->first();
        $next = Project::published()->where('id', '>', $project->id)->orderBy('id')->first();

        return view('frontend.projects.show', compact('project', 'related', 'prev', 'next'));
    }
}
