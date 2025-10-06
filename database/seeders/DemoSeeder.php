<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PerikananRecord;
use App\Models\PertanianRecord;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);
        $ikan = User::factory()->create([
            'name' => 'Petugas Perikanan',
            'email' => 'perikanan@example.com',
            'role' => 'dinas perikanan',
            'password' => Hash::make('password'),
        ]);
        $tani = User::factory()->create([
            'name' => 'Petugas Pertanian',
            'email' => 'pertanian@example.com',
            'role' => 'dinas pertanian',
            'password' => Hash::make('password'),
        ]);

        PerikananRecord::create([
            'user_id' => $ikan->id,
            'tahun' => date('Y'),
            'produksi_ikan' => 12000,
            'jumlah_kapal' => 85,
            'nelayan_aktif' => 560,
            'luas_tambak' => 430,
            'nilai_ekspor' => 9500000000,
        ]);

        PertanianRecord::create([
            'user_id' => $tani->id,
            'tahun' => date('Y'),
            'luas_lahan' => 25000,
            'produksi_padi' => 180000,
            'produksi_jagung' => 95000,
            'produktivitas_padi' => 5.40,
            'jumlah_petani' => 8200,
            'irigasi_aktif' => 120,
            'harga_gabah' => 6500,
        ]);
    }
}
