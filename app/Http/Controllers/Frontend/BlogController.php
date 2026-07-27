<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class BlogController extends Controller
{
    public function index(Request $request, ?string $tag = null, ?string $category = null)
    {
        // Accept either path-segment params or query params (backward compat)
        $activeTag      = $tag      ?? $request->query('tag');
        $activeCategory = $category ?? $request->query('category');
        $search         = $request->query('search');
        $sort           = $request->query('sort', 'newest');

        $query = Blog::published()->with('user')->withCount('approvedComments');

        if ($activeCategory) {
            $query->where('category', $activeCategory);
        }
        if ($activeTag) {
            $query->whereJsonContains('tags', $activeTag);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        match ($sort) {
            'oldest'    => $query->orderBy('published_at'),
            'popular'   => $query->orderByDesc('view_count'),
            default     => $query->orderByDesc('published_at'),
        };

        $featured = Blog::published()->featured()->latest('published_at')->first();
        $blogs    = $query->paginate(9)->withQueryString();

        $categories = Blog::published()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        $allTags = Blog::published()
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->values();

        $viewData = compact('blogs', 'featured', 'categories', 'allTags', 'activeTag', 'activeCategory', 'search', 'sort');

        if ($request->ajax() || $request->boolean('ajax')) {
            return response()->json([
                'html'       => view('frontend.blogs._grid', $viewData)->render(),
                'pagination' => $blogs->hasPages() ? view('frontend.blogs._pagination', $viewData)->render() : '',
                'count'      => $blogs->total(),
            ]);
        }

        return view('frontend.blogs.index', $viewData);
    }

    public function ajaxList(Request $request)
    {
        $request->merge(['ajax' => true]);
        return $this->index($request);
    }

    public function show(string $slug)
    {
        $blog = Blog::published()
            ->with(['user', 'approvedComments.replies'])
            ->where('slug', $slug)
            ->firstOrFail();

        $blog->increment('view_count');

        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->where(function ($q) use ($blog) {
                $q->where('category', $blog->category)
                  ->orWhereJsonOverlaps('tags', $blog->tags ?? []);
            })
            ->limit(3)
            ->get();

        $prev = Blog::published()->where('id', '<', $blog->id)->orderByDesc('id')->first();
        $next = Blog::published()->where('id', '>', $blog->id)->orderBy('id')->first();

        return view('frontend.blogs.show', compact('blog', 'related', 'prev', 'next'));
    }

    public function comment(Request $request, string $slug)
    {
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();

        // Rate limit: 3 comments per hour per IP
        $key = 'comment:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            if ($request->ajax()) {
                return response()->json(['ok' => false, 'message' => 'Too many comments. Please wait before trying again.'], 429);
            }
            return back()->with('error', 'Too many comments. Please wait before trying again.');
        }
        RateLimiter::hit($key, 3600);

        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|max:255',
            'comment'   => 'required|string|min:5|max:2000',
            'parent_id' => 'nullable|integer|exists:blog_comments,id',
            'honeypot'  => 'max:0', // spam trap — must be empty
        ]);

        BlogComment::create([
            'blog_id'    => $blog->id,
            'parent_id'  => $data['parent_id'] ?? null,
            'name'       => $data['name'],
            'email'      => $data['email'],
            'comment'    => $data['comment'],
            'ip_address' => $request->ip(),
            'is_approved'=> true,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'ok'      => true,
                'message' => 'Comment submitted. Thank you!',
            ]);
        }

        return back()->with('success', 'Comment submitted. Thank you!');
    }
}
