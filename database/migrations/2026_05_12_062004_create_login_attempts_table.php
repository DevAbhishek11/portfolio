<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->boolean('successful')->default(false);
            $table->string('failure_reason')->nullable();
            $table->timestamps();

            $table->index(['email', 'ip_address']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};
