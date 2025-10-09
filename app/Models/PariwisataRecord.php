<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//ganti classnya
class PariwisataRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        //sesuaikan dengan database
        'user_id',
        'tahun',
        'bulan',
        'jumlah_objek_wisata',
        'pad_objek_wisata'
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
