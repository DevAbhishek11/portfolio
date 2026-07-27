<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Blog;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProjects = Project::published()
            ->featured()
            ->with('techStacks')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        $latestBlogs = Blog::published()
            ->latest('published_at')
            ->limit(3)
            ->get();

        $projectCount = Project::published()->count();
        $blogCount    = Blog::published()->count();

        return view('frontend.home', compact(
            'featuredProjects',
            'latestBlogs',
            'projectCount',
            'blogCount'
        ));
    }
}
