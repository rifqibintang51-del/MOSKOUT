<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('titik_risikos', function (Blueprint $table) {
            $table->string('provinsi', 100)->nullable()->after('rt_rw');
            $table->string('kabupaten', 100)->nullable()->after('provinsi');
            $table->string('kecamatan', 100)->nullable()->after('kabupaten');
            $table->string('kelurahan', 100)->nullable()->after('kecamatan');
        });
    }

    public function down(): void
    {
        Schema::table('titik_risikos', function (Blueprint $table) {
            $table->dropColumn(['provinsi', 'kabupaten', 'kecamatan', 'kelurahan']);
        });
    }
};
