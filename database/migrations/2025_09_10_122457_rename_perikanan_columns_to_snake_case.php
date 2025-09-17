<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Ambil daftar kolom sebenarnya (nama persis yang disimpan MySQL)
        $columns = Schema::getColumnListing('perikanan_records');

        // Peta kolom lama -> baru (masukkan semua kemungkinan ejaan yang pernah ada)
        $map = [
            // kapital → snake_case
            'Penangkapan_di_laut'            => 'penangkapan_di_laut',
            'Penangkapan_di_perairan_umum'   => 'penangkapan_di_perairan_umum',
            'Budidaya_laut_rumput_laut'      => 'budidaya_laut_rumput_laut',
            'Budidaya_tambak_rumput_laut'    => 'budidaya_tambak_rumput_laut',
            'Budidaya_tambak_udang'          => 'budidaya_tambak_udang',
            'Budidaya_tambak_bandeng'        => 'budidaya_tambak_bandeng',
            'Budidaya_tambak_lainnya'        => 'budidaya_tambak_lainnya',
            'Budidaya_kolam'                 => 'budidaya_kolam',
            'Budidaya_sawah'                 => 'budidaya_sawah',

            // typo yang sempat muncul
            'budidata_tambak_bandeng'        => 'budidaya_tambak_bandeng',
            'budidata_tambak_lainnya'        => 'budidaya_tambak_lainnya',
        ];

        foreach ($map as $from => $to) {
            if (in_array($from, $columns, true) && $from !== $to) {
                Schema::table('perikanan_records', function (Blueprint $table) use ($from, $to) {
                    $table->renameColumn($from, $to);
                });
            }
        }
    }

    public function down(): void
    {
        $columns = Schema::getColumnListing('perikanan_records');

        $reverse = [
            'penangkapan_di_laut'           => 'Penangkapan_di_laut',
            'penangkapan_di_perairan_umum'  => 'Penangkapan_di_perairan_umum',
            'budidaya_laut_rumput_laut'     => 'budidaya_laut_rumput_laut',
            'budidaya_tambak_rumput_laut'   => 'budidaya_tambak_rumput_laut',
            'budidaya_tambak_udang'         => 'budidaya_tambak_udang',
            'budidaya_tambak_bandeng'       => 'budidaya_tambak_bandeng',
            'budidaya_tambak_lainnya'       => 'budidaya_tambak_lainnya',
            'budidaya_kolam'                => 'budidaya_kolam',
            'budidaya_sawah'                => 'budidaya_sawah',
        ];

        foreach ($reverse as $from => $to) {
            if (in_array($from, $columns, true) && $from !== $to) {
                Schema::table('perikanan_records', function (Blueprint $table) use ($from, $to) {
                    $table->renameColumn($from, $to);
                });
            }
        }
    }
};
