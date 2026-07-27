<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::where('user_id', session('admin_user_id'))
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('admin.skills.index', compact('skills'));
    }

    public function create()
    {
        $categories = ['Frontend', 'Backend', 'Database', 'Mobile', 'Tools', 'Other'];
        return view('admin.skills.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'category'    => 'required|string|max:100',
            'level'       => 'required|integer|min:1|max:100',
            'color'       => 'required|string|max:20',
            'icon'        => 'nullable|string|max:255',
            'sort_order'  => 'integer|min:0',
            'is_featured' => 'boolean',
        ]);

        $data['user_id']     = session('admin_user_id');
        $data['is_featured'] = $request->boolean('is_featured');

        Skill::create($data);

        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill added successfully.');
    }

    public function edit(Skill $skill)
    {
        $categories = ['Frontend', 'Backend', 'Database', 'Mobile', 'Tools', 'Other'];
        return view('admin.skills.edit', compact('skill', 'categories'));
    }

    public function update(Request $request, Skill $skill)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'category'    => 'required|string|max:100',
            'level'       => 'required|integer|min:1|max:100',
            'color'       => 'required|string|max:20',
            'icon'        => 'nullable|string|max:255',
            'sort_order'  => 'integer|min:0',
            'is_featured' => 'boolean',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $skill->update($data);

        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill updated.');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();
        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill deleted.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);
        foreach ($request->order as $position => $id) {
            Skill::where('id', $id)
                ->where('user_id', session('admin_user_id'))
                ->update(['sort_order' => $position]);
        }
        return response()->json(['success' => true]);
    }

    // API endpoint for radar chart data
    public function radarApi()
    {
        $userId = optional(\App\Models\User::where('is_admin', true)->first())->id;
        if (! $userId) return response()->json([]);

        $data = Skill::radarData($userId);
        return response()->json($data);
    }
}
