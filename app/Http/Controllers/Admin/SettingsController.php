<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'chat_enabled'     => Setting::bool('chat_enabled', true),
            'years_experience' => Setting::get('years_experience', ''),
            'client_count'     => Setting::get('client_count', ''),
        ];

        $timeline = json_decode(Setting::get('timeline', '[]'), true) ?: [];

        $openRouterConfigured = app(\App\Services\OpenRouterService::class)->isConfigured();
        $maintenanceToken = (string) env('MAINTENANCE_TOKEN');

        return view('admin.settings.index', compact('settings', 'timeline', 'openRouterConfigured', 'maintenanceToken'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'chat_enabled'     => 'nullable|boolean',
            'years_experience' => 'nullable|integer|min:0|max:80',
            'client_count'     => 'nullable|integer|min:0|max:100000',
        ]);

        Setting::set('chat_enabled', $request->boolean('chat_enabled') ? '1' : '0');
        Setting::set('years_experience', $request->input('years_experience', ''));
        Setting::set('client_count', $request->input('client_count', ''));

        return back()->with('success', 'Settings saved.');
    }

    public function updateTimeline(Request $request)
    {
        $request->validate([
            'timeline_year.*'  => 'nullable|string|max:20',
            'timeline_title.*' => 'nullable|string|max:150',
            'timeline_place.*' => 'nullable|string|max:150',
            'timeline_desc.*'  => 'nullable|string|max:500',
        ]);

        $years  = $request->input('timeline_year', []);
        $titles = $request->input('timeline_title', []);
        $places = $request->input('timeline_place', []);
        $descs  = $request->input('timeline_desc', []);

        $entries = [];
        foreach ($years as $i => $year) {
            if (trim((string) $year) === '' || trim((string) ($titles[$i] ?? '')) === '') {
                continue;
            }
            $entries[] = [
                'year'  => trim($year),
                'title' => trim($titles[$i]),
                'place' => trim($places[$i] ?? ''),
                'desc'  => trim($descs[$i] ?? ''),
            ];
        }

        Setting::set('timeline', json_encode(array_values($entries)));

        return back()->with('success', 'Timeline updated.');
    }
}
