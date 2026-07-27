<?php

namespace App\Providers;

use App\Models\Project;
use App\Observers\ProjectObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }
        Project::observe(ProjectObserver::class);
        
        Paginator::defaultView('vendor.pagination.tailwind');

        Model::shouldBeStrict(! app()->isProduction());

        Blade::directive('jpdecor', function () {
            return "<?php echo '<span class=\"jp-accent\">' . func_get_arg(0) . '</span>'; ?>";
        });

        Blade::directive('gradtext', function ($text) {
            return "<?php echo '<span class=\"grad-text\">' . {$text} . '</span>'; ?>";
        });
    }
}
