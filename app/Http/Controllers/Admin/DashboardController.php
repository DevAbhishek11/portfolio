<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactQueries;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private AnalyticsService $analytics) {}

    public function index()
    {
        $stats          = $this->analytics->getDashboardStats();
        $recentContacts = ContactQueries::latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentContacts'));
    }

    public function stats(Request $request)
    {
        $days  = (int) $request->get('days', 30);
        $days  = min(max($days, 7), 90);

        return response()->json([
            'views_chart' => $this->analytics->getViewsChartData($days),
            'login_chart' => $this->analytics->getLoginAttemptsChart(14),
        ]);
    }
}
