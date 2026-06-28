<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titik_risikos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_titik', 150);
            $table->text('alamat');
            $table->string('rt_rw', 20)->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->enum('jenis_risiko', ['genangan', 'barang bekas', 'saluran air', 'tempat sampah']);
            $table->enum('level_risiko_awal', ['rendah', 'sedang', 'tinggi']);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titik_risikos');
    }
};
