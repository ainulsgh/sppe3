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
        Schema::create('perhubungan_records', function (Blueprint $table) {
            $table->id();

            // user yang input
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // periode data
            $table->integer('tahun');
            $table->tinyInteger('bulan'); // 1-12

            // indikator retribusi
            $table->double('retribusi_truk')->default(0);
            $table->double('retribusi_pick_up')->default(0);
            $table->double('retribusi_parkir_motor')->default(0);
            $table->double('retribusi_parkir_angkot')->default(0);

            $table->timestamps();

            // kombinasi tahun+bulan unik
            $table->unique(['tahun', 'bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perhubungan_records');
    }
};
