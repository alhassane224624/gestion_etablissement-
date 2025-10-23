<?php
// =============================================================================
// MIGRATION 1: 2014_10_12_000000_create_users_table.php
// =============================================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('role', ['administrateur', 'professeur', 'stagiaire'])->default('stagiaire');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamp('activated_at')->nullable();
            $table->foreignId('activated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('specialite')->nullable();
            $table->text('bio')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->index(['role', 'is_active']);
            $table->index(['created_by', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};