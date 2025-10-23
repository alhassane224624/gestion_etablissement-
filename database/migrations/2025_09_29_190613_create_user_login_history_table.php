<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 5: 2025_01_01_000003_create_user_login_history_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('user_login_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address');
            $table->string('user_agent');
            $table->timestamp('login_at');
            $table->timestamp('logout_at')->nullable();
            $table->string('session_id');
            $table->timestamps();

            $table->index(['user_id', 'login_at']);
            $table->index('login_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_login_history');
    }
};