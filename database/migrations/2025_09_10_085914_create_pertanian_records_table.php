<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pertanian_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->year('tahun');
            $table->unsignedInteger('luas_lahan'); // ha
            $table->unsignedBigInteger('produksi_padi'); // ton
            $table->unsignedBigInteger('produksi_jagung'); // ton
            $table->decimal('produktivitas_padi', 8, 2); // ton/ha
            $table->unsignedInteger('jumlah_petani');
            $table->unsignedInteger('irigasi_aktif'); // unit
            $table->unsignedInteger('harga_gabah'); // Rp/kg
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pertanian_records');
    }
};
