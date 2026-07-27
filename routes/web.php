<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ProjectController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ContactQueryController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SkillController;
use Illuminate\Support\Facades\Artisan;

// ─── Frontend ────────────────────────────────────────────────────────────────
Route::middleware(['track.pageview'])->group(function () {
    Route::get('/',               [HomeController::class,    'index'])->name('home');
    Route::get('/about',          [AboutController::class,   'index'])->name('about');

    // ── Projects (specific routes before slug catch-all) ───────────────────
    Route::get('/projects',                         [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/ajax/list',               [ProjectController::class, 'ajaxList'])->name('projects.ajax');
    Route::get('/projects/category/{category}',     [ProjectController::class, 'index'])->name('projects.category');
    Route::get('/projects/{slug}',                  [ProjectController::class, 'show'])->name('projects.show');

    Route::get('/services',       [ServiceController::class, 'index'])->name('services');

    // ── Blogs (specific routes before slug catch-all) ──────────────────────
    Route::get('/blogs',                          [BlogController::class, 'index'])->name('blogs.index');
    Route::get('/blogs/ajax/list',                [BlogController::class, 'ajaxList'])->name('blogs.ajax');
    Route::get('/blogs/tag/{tag}',                [BlogController::class, 'index'])->name('blogs.tag');
    Route::get('/blogs/category/{category}',      [BlogController::class, 'index'])->name('blogs.category');
    Route::get('/blogs/{slug}',                   [BlogController::class, 'show'])->name('blogs.show');
    Route::post('/blogs/{slug}/comment',          [BlogController::class, 'comment'])->name('blogs.comment');

    Route::get('/contact',        [ContactController::class, 'index'])->name('contact');
    Route::post('/contact',       [ContactController::class, 'store'])->name('contact.store');
});

// ─── Admin Auth ───────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest only
    Route::middleware('guest')->group(function () {
        Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
        Route::get('/forgot-password',  [ForgotPasswordController::class, 'show'])->name('forgot-password');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot-password.send');
        Route::get('/reset-password/{token}', [PasswordResetController::class, 'show'])->name('reset-password');
        Route::post('/reset-password',        [PasswordResetController::class, 'reset'])->name('reset-password.submit');
    });

    // Authenticated but awaiting 2FA
    Route::middleware('admin.auth')->group(function () {
        Route::get('/two-factor',        [TwoFactorController::class, 'show'])->name('two-factor');
        Route::post('/two-factor',       [TwoFactorController::class, 'verify'])->name('two-factor.verify');
        Route::post('/two-factor/resend', [TwoFactorController::class, 'resend'])->name('two-factor.resend');
    });

    // Fully authenticated
    Route::middleware(['admin.auth', 'two-factor.verified'])->group(function () {
        Route::get('/dashboard',       [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

        // Projects
        Route::get('/projects/ajax/list', [AdminProjectController::class, 'ajaxList'])->name('projects.ajax');
        Route::resource('projects', AdminProjectController::class);
        Route::post('/projects/{id}/toggle-featured', [AdminProjectController::class, 'toggleFeatured'])->name('projects.toggle-featured');
        Route::post('/projects/{id}/toggle-status',   [AdminProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
        Route::delete('/projects/{id}/image/{imageId}', [AdminProjectController::class, 'deleteImage'])->name('projects.delete-image');

        // Blogs
        Route::get('/blogs/ajax/list', [AdminBlogController::class, 'ajaxList'])->name('blogs.ajax');
        Route::resource('blogs', AdminBlogController::class);
        Route::post('/blogs/{id}/toggle-featured', [AdminBlogController::class, 'toggleFeatured'])->name('blogs.toggle-featured');
        Route::post('/blogs/{id}/toggle-status',   [AdminBlogController::class, 'toggleStatus'])->name('blogs.toggle-status');

        // Blog Comments (review pages under blogs)
        Route::get('/blogs/{id}/comments',                       [AdminBlogController::class, 'comments'])->name('blogs.comments');
        Route::get('/blogs/{id}/comments/ajax',                  [AdminBlogController::class, 'commentsAjax'])->name('blogs.comments.ajax');
        Route::post('/blogs/{id}/comments/{commentId}/approve',  [AdminBlogController::class, 'approveComment'])->name('blogs.approve-comment');
        Route::post('/blogs/{id}/comments/{commentId}/toggle',   [AdminBlogController::class, 'toggleComment'])->name('blogs.toggle-comment');
        Route::delete('/blogs/{id}/comments/{commentId}',        [AdminBlogController::class, 'deleteComment'])->name('blogs.delete-comment');

        // Contacts
        Route::get('/contacts/ajax/list',    [ContactQueryController::class, 'ajaxList'])->name('contacts.ajax');
        Route::get('/contacts',              [ContactQueryController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{id}',         [ContactQueryController::class, 'show'])->name('contacts.show');
        Route::post('/contacts/{id}/reply',  [ContactQueryController::class, 'reply'])->name('contacts.reply');
        Route::post('/contacts/{id}/status', [ContactQueryController::class, 'updateStatus'])->name('contacts.update-status');
        Route::delete('/contacts/{id}',      [ContactQueryController::class, 'destroy'])->name('contacts.destroy');
        Route::post('/contacts/bulk-action', [ContactQueryController::class, 'bulkAction'])->name('contacts.bulk-action');

        // Inside the admin authenticated group
        Route::resource('skills', SkillController::class)->except(['show']);
        Route::post('/skills/reorder', [SkillController::class, 'reorder'])->name('skills.reorder');

        // Public API for radar (no auth needed — data is public)
        Route::get('/api/skills/radar', [SkillController::class, 'radarApi'])->name('api.skills.radar');

        Route::get('/profile',                         [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit',                    [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile',                         [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/security',                [ProfileController::class, 'security'])->name('profile.security');
        Route::put('/profile/password',                [ProfileController::class, 'updatePassword'])->name('profile.update-password');
        Route::post('/profile/two-factor/enable',      [ProfileController::class, 'enableTwoFactor'])->name('profile.enable-2fa');
        Route::post('/profile/two-factor/disable',     [ProfileController::class, 'disableTwoFactor'])->name('profile.disable-2fa');
        Route::post('/profile/two-factor/verify-setup', [ProfileController::class, 'verifyTwoFactorSetup'])->name('profile.verify-2fa-setup');

        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    });
});

// ── SEO Routes ────────────────────────────────────────────────────────────
Route::get('/sitemap.xml', function () {

    $projects = \App\Models\Project::published()
        ->select('slug', 'updated_at')
        ->get();

    $blogs = \App\Models\Blog::published()
        ->select('slug', 'updated_at')
        ->get();

    $static = [
        ['url' => route('home'), 'priority' => '1.0', 'freq' => 'weekly'],
        ['url' => route('about'), 'priority' => '0.8', 'freq' => 'monthly'],
        ['url' => route('projects.index'), 'priority' => '0.9', 'freq' => 'weekly'],
        ['url' => route('services'), 'priority' => '0.8', 'freq' => 'monthly'],
        ['url' => route('blogs.index'), 'priority' => '0.85', 'freq' => 'weekly'],
        ['url' => route('contact'), 'priority' => '0.7', 'freq' => 'monthly'],
    ];

    return response()
        ->view('seo.sitemap', compact('static', 'projects', 'blogs'))
        ->header('Content-Type', 'application/xml');
});

Route::get('/robots.txt', function () {
    return response(
        view('seo.robots'),
        200,
        ['Content-Type' => 'text/plain']
    );
})->name('robots');

Route::prefix('server')->group(function () {
    $token = 'admin@1120Abhi';
    Route::get('/storage-link/{key}', function ($key) use ($token) {
        abort_if($key !== $token, 403);
        Artisan::call('storage:link');
        return nl2br(Artisan::output() . "\nStorage linked successfully.");
    });

    Route::get('/migrate/{key}', function ($key) use ($token) {
        abort_if($key !== $token, 403);
        Artisan::call('migrate', [
            '--force' => true
        ]);
        return nl2br(Artisan::output() . "\nMigration completed.");
    });

    Route::get('/clear/{key}', function ($key) use ($token) {
        abort_if($key !== $token, 403);
        Artisan::call('optimize:clear');
        return nl2br(Artisan::output() . "\nAll caches cleared.");
    });

    Route::get('/optimize/{key}', function ($key) use ($token) {
        abort_if($key !== $token, 403);
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        return nl2br("Application optimized successfully.");
    });

    Route::get('/queue-restart/{key}', function ($key) use ($token) {
        abort_if($key !== $token, 403);
        Artisan::call('queue:restart');
        return 'Queue restarted successfully.';
    });

    Route::get('/down/{key}', function ($key) use ($token) {
        abort_if($key !== $token, 403);
        Artisan::call('down');
        return 'Application is now in maintenance mode.';
    });

    Route::get('/up/{key}', function ($key) use ($token) {
        abort_if($key !== $token, 403);
        Artisan::call('up');
        return 'Application is live again.';
    });

});