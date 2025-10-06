<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('perikanan_records', function (Blueprint $table) {
            // simpan sebagai angka 1-12 untuk sorting yang mudah
            $table->unsignedTinyInteger('bulan')->default(1)->after('tahun');
        });
    }

    public function down(): void
    {
        Schema::table('perikanan_records', function (Blueprint $table) {
            $table->dropColumn('bulan');
        });
    }
};
