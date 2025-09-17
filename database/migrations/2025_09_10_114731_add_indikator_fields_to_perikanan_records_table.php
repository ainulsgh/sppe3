<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('perikanan_records', function (Blueprint $table) {
            // indikator tambahan
            $table->integer('budidaya_tambak_bandeng')->default(0)->after('Budidaya_tambak_udang');
            $table->integer('budidaya_tambak_lainnya')->default(0)->after('budidaya_tambak_bandeng');
            $table->integer('budidaya_kolam')->default(0)->after('budidaya_tambak_lainnya');
            $table->integer('budidaya_sawah')->default(0)->after('budidaya_kolam');
        });
    }

    public function down(): void
    {
        Schema::table('perikanan_records', function (Blueprint $table) {
            $table->dropColumn([
                'budidaya_tambak_bandeng',
                'budidaya_tambak_lainnya',
                'budidaya_kolam',
                'budidaya_sawah',
            ]);
        });
    }
};
