<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('two_factor_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token');
            $table->enum('type', ['login', 'password_reset']);
            $table->boolean('used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['user_id', 'type', 'used']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('two_factor_tokens');
    }
};