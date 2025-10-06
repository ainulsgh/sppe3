<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//ganti classnya
class PerhubunganRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        //sesuaikan dengan database
        'user_id',
        'tahun',
        'bulan',
        'retribusi_truk',
        'retribusi_pick_up',
        'retribusi_parkir_motor',
        'retribusi_parkir_angkot',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'bulan' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
