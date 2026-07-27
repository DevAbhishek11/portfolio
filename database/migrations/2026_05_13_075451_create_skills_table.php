<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('category'); // Frontend, Backend, Mobile, Database, Tools
            $table->unsignedTinyInteger('level')->default(80); // 0-100
            $table->string('color')->default('#8b5cf6'); // hex colour for radar
            $table->string('icon')->nullable(); // SVG path or emoji
            $table->integer('sort_order')->default(0);
            $table->boolean('is_featured')->default(true); // show on radar
            $table->timestamps();

            $table->index(['user_id', 'category']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};