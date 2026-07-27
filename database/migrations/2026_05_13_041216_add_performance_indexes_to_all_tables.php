<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Projects
        Schema::table('projects', function (Blueprint $table) {
            if (! $this->indexExists('projects', 'projects_slug_index'))
                $table->index('slug');
            if (! $this->indexExists('projects', 'projects_sort_order_index'))
                $table->index('sort_order');
            if (! $this->indexExists('projects', 'projects_view_count_index'))
                $table->index('view_count');
        });

        // Blogs
        Schema::table('blogs', function (Blueprint $table) {
            if (! $this->indexExists('blogs', 'blogs_slug_index'))
                $table->index('slug');
            if (! $this->indexExists('blogs', 'blogs_view_count_index'))
                $table->index('view_count');
            if (! $this->indexExists('blogs', 'blogs_category_index'))
                $table->index('category');
        });

        // Page views
        Schema::table('page_views', function (Blueprint $table) {
            if (! $this->indexExists('page_views', 'page_views_session_id_page_index'))
                $table->index(['session_id', 'page']);
        });

        // Contact queries
        Schema::table('contact_queries', function (Blueprint $table) {
            if (! $this->indexExists('contact_queries', 'contact_queries_created_at_index'))
                $table->index('created_at');
        });
    }

    public function down(): void {}

    private function indexExists(string $table, string $index): bool
    {
        try {
            $sm      = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes($table);
            return isset($indexes[$index]);
        } catch (\Throwable) {
            return false;
        }
    }
};
