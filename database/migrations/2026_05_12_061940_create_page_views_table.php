<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('page');
            $table->string('url');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->string('country')->nullable();
            $table->enum('device_type', ['desktop', 'mobile', 'tablet'])->nullable();
            $table->string('browser')->nullable();
            $table->string('session_id')->nullable();
            $table->nullableMorphs('viewable');
            $table->timestamps();

            $table->index('page');
            $table->index('created_at');
            $table->index('device_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
