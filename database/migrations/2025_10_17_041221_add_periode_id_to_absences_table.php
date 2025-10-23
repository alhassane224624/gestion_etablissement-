<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('absences', function (Blueprint $table) {
            $table->foreignId('periode_id')->nullable()->constrained()->onDelete('cascade')->after('stagiaire_id');
        });
    }

    public function down()
    {
        Schema::table('absences', function (Blueprint $table) {
            $table->dropForeign(['periode_id']);
            $table->dropColumn('periode_id');
        });
    }
};
