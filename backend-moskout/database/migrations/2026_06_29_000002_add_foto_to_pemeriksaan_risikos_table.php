<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemeriksaan_risikos', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('revisi_ke');
        });
    }

    public function down(): void
    {
        Schema::table('pemeriksaan_risikos', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
