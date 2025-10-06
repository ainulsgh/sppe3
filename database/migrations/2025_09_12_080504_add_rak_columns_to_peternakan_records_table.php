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
        Schema::table('peternakan_records', function (Blueprint $table) {
            $table->decimal('telur_ayam_ras_petelur_rak', 15, 2)->default(0)->after('telur_itik');
            $table->decimal('telur_ayam_buras_rak', 15, 2)->default(0)->after('telur_ayam_ras_petelur_rak');
            $table->decimal('telur_itik_rak', 15, 2)->default(0)->after('telur_ayam_buras_rak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peternakan_records', function (Blueprint $table) {
            $table->dropColumn([
                'telur_ayam_ras_petelur_rak',
                'telur_ayam_buras_rak',
                'telur_itik_rak',
            ]);
        });
    }
};
