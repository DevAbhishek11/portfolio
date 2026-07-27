<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('short_description', 255);
            $table->longText('description');
            $table->string('thumbnail');
            $table->string('github_url')->nullable();
            $table->string('live_url')->nullable();
            $table->enum('category', ['frontend', 'backend', 'fullstack']);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('sort_order')->default(0);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_featured']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
