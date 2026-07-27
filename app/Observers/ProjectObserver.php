<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class ProjectObserver
{
    public function saved(Project $project): void
    {
        $this->bust();
    }
    public function deleted(Project $project): void
    {
        $this->bust();
    }
    public function restored(Project $project): void
    {
        $this->bust();
    }

    private function bust(): void
    {
        Cache::forget('home.featured_projects');
        Cache::forget('home.stats');
    }
}
