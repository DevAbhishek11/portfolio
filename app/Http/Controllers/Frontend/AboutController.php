<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;

class AboutController extends Controller
{
    public function index()
    {
        $admin = User::where('is_admin', true)->first();

        $skills = \App\Models\Skill::where('user_id', $admin?->id ?? 0)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();

        $grouped = $skills->groupBy('category');

        // Admin-managed via Settings — no career history is invented here;
        // the section simply doesn't render until real entries are added.
        $timeline = json_decode(Setting::get('timeline', '[]'), true) ?: [];

        return view('frontend.about', compact('admin', 'skills', 'grouped', 'timeline'));
    }
}
