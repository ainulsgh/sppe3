<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('perikanan_records', function (Blueprint $table) {
            // bulan angka 1–12
            if (!Schema::hasColumn('perikanan_records', 'bulan')) {
                $table->unsignedTinyInteger('bulan')->default(1)->after('tahun');
            }

            $addInt = function (string $col) use ($table) {
                if (!Schema::hasColumn('perikanan_records', $col)) {
                    $table->integer($col)->default(0);
                }
            };

            $addInt('penangkapan_di_laut');
            $addInt('penangkapan_di_perairan_umum');
            $addInt('budidaya_laut_rumput_laut');
            $addInt('budidaya_tambak_rumput_laut');
            $addInt('budidaya_tambak_udang');
            $addInt('budidaya_tambak_bandeng');
            $addInt('budidaya_tambak_lainnya');
            $addInt('budidaya_kolam');
            $addInt('budidaya_sawah');
        });
    }

    public function down(): void
    {
        // tidak perlu drop supaya aman
    }
};
