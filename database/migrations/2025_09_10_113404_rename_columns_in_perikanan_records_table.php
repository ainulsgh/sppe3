<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('perikanan_records', function (Blueprint $table) {
            $table->renameColumn('produksi_ikan', 'penangkapan_di_laut');
            $table->renameColumn('jumlah_kapal', 'penangkapan_di_perairan_umum');
            $table->renameColumn('luas_tambak', 'Budidaya_laut_rumput_laut');
            $table->renameColumn('nelayan_aktif', 'Budidaya_tambak_rumput_laut');
            $table->renameColumn('nilai_ekspor', 'Budidaya_tambak_udang');
        });
    }

    public function down(): void
    {
        Schema::table('perikanan_records', function (Blueprint $table) {
            $table->renameColumn('penangkapan_di_laut', 'produksi_ikan');
            $table->renameColumn('penangkapan_di_perairan_umum', 'jumlah_kapal');
            $table->renameColumn('Budidaya_laut_rumput_laut', 'luas_tambak');
            $table->renameColumn('Budidaya_tambak_rumput_laut', 'nelayan_aktif');
            $table->renameColumn('Budidaya_tambak_udang', 'nilai_ekspor');
        });
    }
};
