<?php

// database/migrations/xxxx_xx_xx_xxxxxx_alter_perikanan_records_make_decimal.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('perikanan_records', function (Blueprint $table) {
            $table->decimal('penangkapan_di_laut',           15, 2)->default(0)->change();
            $table->decimal('penangkapan_di_perairan_umum',  15, 2)->default(0)->change();
            $table->decimal('budidaya_laut_rumput_laut',     15, 2)->default(0)->change();
            $table->decimal('budidaya_tambak_rumput_laut',   15, 2)->default(0)->change();
            $table->decimal('budidaya_tambak_udang',         15, 2)->default(0)->change();
            $table->decimal('budidaya_tambak_bandeng',       15, 2)->default(0)->change();
            $table->decimal('budidaya_tambak_lainnya',       15, 2)->default(0)->change();
            $table->decimal('budidaya_kolam',                15, 2)->default(0)->change();
            $table->decimal('budidaya_sawah',                15, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('perikanan_records', function (Blueprint $table) {
            $table->integer('penangkapan_di_laut')->default(0)->change();
            $table->integer('penangkapan_di_perairan_umum')->default(0)->change();
            $table->integer('budidaya_laut_rumput_laut')->default(0)->change();
            $table->integer('budidaya_tambak_rumput_laut')->default(0)->change();
            $table->integer('budidaya_tambak_udang')->default(0)->change();
            $table->integer('budidaya_tambak_bandeng')->default(0)->change();
            $table->integer('budidaya_tambak_lainnya')->default(0)->change();
            $table->integer('budidaya_kolam')->default(0)->change();
            $table->integer('budidaya_sawah')->default(0)->change();
        });
    }
};
