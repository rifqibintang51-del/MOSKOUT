<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Cari user pertama dengan role petugas
        $petugasId = DB::table('users')->where('role', 'petugas')->value('id');

        if ($petugasId) {
            // Update semua pemeriksaan yang merujuk ke user admin (ID 1)
            // agar merujuk ke user petugas yang benar
            DB::table('pemeriksaan_risikos')
                ->where('petugas_id', 1)
                ->update(['petugas_id' => $petugasId]);
        }
    }

    public function down(): void
    {
        // Tidak bisa di-reverse karena data sudah berubah
    }
};
