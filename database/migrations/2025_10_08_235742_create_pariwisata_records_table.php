<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pariwisata_records', function (Blueprint $table) { //sesuaikan nama tabel
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->integer('tahun');
            $table->tinyInteger('bulan'); 

            //isikan indikator dinas
            $table->integer('jumlah_kunjungan_wisata')->default(0);
            $table->integer('pad_objek_wisata')->default(0);

            $table->timestamps();
            $table->unique(['tahun', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pariwisata_records'); //sesuaikan nama tabel
    }
};
