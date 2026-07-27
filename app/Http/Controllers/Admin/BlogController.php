<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function __construct(private ImageUploadService $imageService) {}

    public function index(Request $request)
    {
        $blogs = $this->queryBlogs($request)->paginate(15)->withQueryString();

        if ($request->ajax() || $request->boolean('ajax')) {
            return response()->json([
                'html' => view('admin.blogs._table', compact('blogs'))->render(),
            ]);
        }

        return view('admin.blogs.index', compact('blogs'));
    }

    public function ajaxList(Request $request)
    {
        $request->merge(['ajax' => true]);
        return $this->index($request);
    }

    private function queryBlogs(Request $request)
    {
        $query = Blog::with('user')->withCount(['comments', 'approvedComments'])->latest();

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('search'))   $query->where('title', 'like', "%{$request->search}%");

        return $query;
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:blogs,slug',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'category'         => 'nullable|string|max:100',
            'tags'             => 'nullable|string',
            'status'           => 'required|in:draft,published,archived',
            'is_featured'      => 'boolean',
            'published_at'     => 'nullable|date',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
        ]);

        $data['slug']       = $data['slug'] ?: Str::slug($data['title']);
        $data['user_id']    = session('admin_user_id');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['tags']       = $request->filled('tags')
            ? array_map('trim', explode(',', $request->tags))
            : null;
        $data['read_time']  = $this->calcReadTime($data['content']);

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->imageService->upload(
                $request->file('featured_image'),
                'blogs'
            );
        }

        Blog::create($data);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function show(int $id)
    {
        $blog = Blog::with(['user'])
            ->withCount(['comments', 'approvedComments'])
            ->findOrFail($id);

        $recentComments = BlogComment::where('blog_id', $id)
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.blogs.show', compact('blog', 'recentComments'));
    }

    public function edit(int $id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, int $id)
    {
        $blog = Blog::findOrFail($id);

        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => "nullable|string|max:255|unique:blogs,slug,{$id}",
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'category'         => 'nullable|string|max:100',
            'tags'             => 'nullable|string',
            'status'           => 'required|in:draft,published,archived',
            'is_featured'      => 'boolean',
            'published_at'     => 'nullable|date',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
        ]);

        $data['slug']        = $data['slug'] ?: Str::slug($data['title']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['tags']        = $request->filled('tags')
            ? array_map('trim', explode(',', $request->tags))
            : null;
        $data['read_time']   = $this->calcReadTime($data['content']);

        if ($data['status'] === 'published' && empty($blog->published_at) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $this->imageService->delete($blog->featured_image);
            $data['featured_image'] = $this->imageService->upload(
                $request->file('featured_image'),
                'blogs'
            );
        } else {
            unset($data['featured_image']);
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(int $id)
    {
        $blog = Blog::findOrFail($id);
        $this->imageService->delete($blog->featured_image);
        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post deleted.');
    }

    public function toggleFeatured(int $id)
    {
        $blog = Blog::findOrFail($id);
        $blog->update(['is_featured' => ! $blog->is_featured]);

        if (request()->ajax()) {
            return response()->json(['ok' => true, 'is_featured' => $blog->is_featured]);
        }
        return back()->with('success', 'Featured status updated.');
    }

    public function toggleStatus(int $id)
    {
        $blog = Blog::findOrFail($id);
        $next = $blog->status === 'published' ? 'draft' : 'published';
        if ($next === 'published' && ! $blog->published_at) {
            $blog->published_at = now();
        }
        $blog->status = $next;
        $blog->save();
        return back()->with('success', "Status changed to {$next}.");
    }

    // ── Comments management ────────────────────────────────────────────────
    public function comments(Request $request, int $id)
    {
        $blog = Blog::withCount(['comments', 'approvedComments'])->findOrFail($id);
        $comments = $this->queryComments($request, $id)->paginate(20)->withQueryString();

        if ($request->ajax() || $request->boolean('ajax')) {
            return response()->json([
                'html' => view('admin.blogs._comments_table', compact('blog', 'comments'))->render(),
            ]);
        }

        return view('admin.blogs.comments', compact('blog', 'comments'));
    }

    public function commentsAjax(Request $request, int $id)
    {
        $request->merge(['ajax' => true]);
        return $this->comments($request, $id);
    }

    private function queryComments(Request $request, int $blogId)
    {
        $query = BlogComment::where('blog_id', $blogId)->latest();

        if ($request->filled('filter')) {
            match ($request->filter) {
                'approved' => $query->where('is_approved', true),
                'pending'  => $query->where('is_approved', false),
                default    => null,
            };
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('comment', 'like', "%{$s}%");
            });
        }
        return $query;
    }

    public function approveComment(int $id, int $commentId)
    {
        $comment = BlogComment::where('blog_id', $id)->findOrFail($commentId);
        $comment->update(['is_approved' => true]);

        if (request()->ajax()) {
            return response()->json(['ok' => true, 'is_approved' => true]);
        }
        return back()->with('success', 'Comment approved.');
    }

    public function toggleComment(int $id, int $commentId)
    {
        $comment = BlogComment::where('blog_id', $id)->findOrFail($commentId);
        $comment->update(['is_approved' => ! $comment->is_approved]);

        if (request()->ajax()) {
            return response()->json(['ok' => true, 'is_approved' => $comment->is_approved]);
        }
        return back()->with('success', $comment->is_approved ? 'Comment is now visible.' : 'Comment hidden from site.');
    }

    public function deleteComment(int $id, int $commentId)
    {
        BlogComment::where('blog_id', $id)->findOrFail($commentId)->delete();

        if (request()->ajax()) {
            return response()->json(['ok' => true]);
        }
        return back()->with('success', 'Comment deleted.');
    }

    private function calcReadTime(string $content): int
    {
        $words = str_word_count(strip_tags($content));
        return max(1, (int) ceil($words / 200));
    }
}
