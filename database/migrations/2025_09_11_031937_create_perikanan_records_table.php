<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perikanan_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->unsignedSmallInteger('tahun');
            $table->unsignedTinyInteger('bulan');

            // indikator (decimal biar bisa simpan desimal)
            $table->decimal('penangkapan_di_laut', 15, 2)->default(0);
            $table->decimal('penangkapan_di_perairan_umum', 15, 2)->default(0);
            $table->decimal('budidaya_laut_rumput_laut', 15, 2)->default(0);
            $table->decimal('budidaya_tambak_rumput_laut', 15, 2)->default(0);
            $table->decimal('budidaya_tambak_udang', 15, 2)->default(0);
            $table->decimal('budidaya_tambak_bandeng', 15, 2)->default(0);
            $table->decimal('budidaya_tambak_lainnya', 15, 2)->default(0);
            $table->decimal('budidaya_kolam', 15, 2)->default(0);
            $table->decimal('budidaya_sawah', 15, 2)->default(0);

            $table->timestamps();

            // kombinasi tahun+bulan harus unik
            $table->unique(['tahun', 'bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perikanan_records');
    }
};
