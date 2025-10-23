<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// =============================================================================
// MIGRATION 6: 2025_03_31_001431_create_activity_log_table.php
// =============================================================================
return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_actions_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->string('action');
            $table->string('target_type');
            $table->unsignedBigInteger('target_id');
            $table->json('details')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['admin_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['target_type', 'target_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_actions_log');
    }
};