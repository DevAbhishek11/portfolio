<?php

namespace App\Services;

use App\Models\PageView;
use App\Models\Project;
use App\Models\Blog;
use App\Models\ContactQueries as ContactQuery;
use App\Models\LoginAttempt;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getDashboardStats(): array
    {
        return [
            'page_views'   => $this->getPageViewStats(),
            'projects'     => $this->getProjectStats(),
            'blogs'        => $this->getBlogStats(),
            'contacts'     => $this->getContactStats(),
            'devices'      => $this->getDeviceBreakdown(),
            'browsers'     => $this->getBrowserBreakdown(),
            'top_projects' => $this->getTopProjects(),
            'top_blogs'    => $this->getTopBlogs(),
            'recent_views' => $this->getRecentPageViews(),
            'views_chart'  => $this->getViewsChartData(30),
            'login_chart'  => $this->getLoginAttemptsChart(14),
        ];
    }

    public function getPageViewStats(): array
    {
        return [
            'today'     => PageView::whereDate('created_at', today())->count(),
            'this_week' => PageView::where('created_at', '>=', now()->startOfWeek())->count(),
            'this_month' => PageView::where('created_at', '>=', now()->startOfMonth())->count(),
            'total'     => PageView::count(),
            'change'    => $this->getPercentChange(
                PageView::whereDate('created_at', today()->subDay())->count(),
                PageView::whereDate('created_at', today())->count()
            ),
        ];
    }

    public function getProjectStats(): array
    {
        return [
            'total'     => Project::count(),
            'published' => Project::where('status', 'published')->count(),
            'draft'     => Project::where('status', 'draft')->count(),
            'archived'  => Project::where('status', 'archived')->count(),
            'featured'  => Project::where('is_featured', true)->count(),
            'total_views' => Project::sum('view_count'),
        ];
    }

    public function getBlogStats(): array
    {
        return [
            'total'      => Blog::count(),
            'published'  => Blog::where('status', 'published')->count(),
            'draft'      => Blog::where('status', 'draft')->count(),
            'total_views' => Blog::sum('view_count'),
            'avg_views'  => round(Blog::where('status', 'published')->avg('view_count') ?? 0),
        ];
    }

    public function getContactStats(): array
    {
        return [
            'total'   => ContactQuery::count(),
            'unread'  => ContactQuery::where('status', 'unread')->count(),
            'replied' => ContactQuery::where('status', 'replied')->count(),
            'today'   => ContactQuery::whereDate('created_at', today())->count(),
        ];
    }

    public function getDeviceBreakdown(): array
    {
        return PageView::select('device_type', DB::raw('count(*) as count'))
            ->whereNotNull('device_type')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('device_type')
            ->pluck('count', 'device_type')
            ->toArray();
    }

    public function getBrowserBreakdown(): array
    {
        return PageView::select('browser', DB::raw('count(*) as count'))
            ->whereNotNull('browser')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('browser')
            ->orderByDesc('count')
            ->limit(5)
            ->pluck('count', 'browser')
            ->toArray();
    }

    public function getTopProjects(int $limit = 5): \Illuminate\Support\Collection
    {
        return Project::where('status', 'published')
            ->orderByDesc('view_count')
            ->limit($limit)
            ->get(['id', 'title', 'view_count', 'thumbnail', 'category']);
    }

    public function getTopBlogs(int $limit = 5): \Illuminate\Support\Collection
    {
        return Blog::where('status', 'published')
            ->orderByDesc('view_count')
            ->limit($limit)
            ->get(['id', 'title', 'view_count', 'featured_image', 'published_at']);
    }

    public function getRecentPageViews(int $limit = 10): \Illuminate\Support\Collection
    {
        return PageView::orderByDesc('created_at')
            ->limit($limit)
            ->get(['page', 'ip_address', 'device_type', 'browser', 'created_at']);
    }

    public function getViewsChartData(int $days = 30): array
    {
        $data = PageView::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count')
        )
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Fill missing days with 0
        $result = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = today()->subDays($i)->toDateString();
            $result[$date] = $data[$date] ?? 0;
        }

        return $result;
    }

    public function getLoginAttemptsChart(int $days = 14): array
    {
        $data = LoginAttempt::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(successful = 1) as success'),
            DB::raw('SUM(successful = 0) as failed')
        )
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $result = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = today()->subDays($i)->toDateString();
            $result[$date] = [
                'success' => $data[$date]->success ?? 0,
                'failed'  => $data[$date]->failed  ?? 0,
            ];
        }

        return $result;
    }

    private function getPercentChange(int $old, int $new): float
    {
        if ($old === 0) return $new > 0 ? 100 : 0;
        return round((($new - $old) / $old) * 100, 1);
    }
}
