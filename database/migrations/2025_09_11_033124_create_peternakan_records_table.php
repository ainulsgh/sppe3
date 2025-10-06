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
        Schema::create('peternakan_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->unsignedSmallInteger('tahun');
            $table->unsignedTinyInteger('bulan');

            // Kolom indikator hasil peternakan (decimal)
            $table->decimal('daging_sapi', 15, 2)->default(0);
            $table->decimal('daging_kambing', 15, 2)->default(0);
            $table->decimal('daging_kuda', 15, 2)->default(0);
            $table->decimal('daging_ayam_buras', 15, 2)->default(0);
            $table->decimal('daging_ayam_ras_pedaging', 15, 2)->default(0);
            $table->decimal('daging_itik', 15, 2)->default(0);

            $table->decimal('telur_ayam_petelur', 15, 2)->default(0);
            $table->decimal('telur_ayam_buras', 15, 2)->default(0);
            $table->decimal('telur_itik', 15, 2)->default(0);
            $table->decimal('telur_ayam_ras_petelur', 15, 2)->default(0);

            $table->timestamps();

            // unik: hanya boleh ada 1 data per tahun+bulan
            $table->unique(['tahun', 'bulan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peternakan_records');
    }
};
