<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan_risikos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('titik_risiko_id')
                  ->constrained('titik_risikos')
                  ->onDelete('cascade');
            $table->integer('petugas_id');
            $table->date('tanggal_pemeriksaan');
            $table->enum('ditemukan_jentik', ['ya', 'tidak']);
            $table->text('kondisi_lingkungan');
            $table->text('tindakan_dilakukan');
            $table->enum('status_akhir', ['aman', 'perlu pemantauan', 'perlu tindakan']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_risikos');
    }
};
