<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerikananRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'tahun',
        'bulan',
        'penangkapan_di_laut',
        'penangkapan_di_perairan_umum',
        'budidaya_laut_rumput_laut',
        'budidaya_tambak_rumput_laut',
        'budidaya_tambak_udang',
        'budidaya_tambak_bandeng',
        'budidaya_tambak_lainnya',
        'budidaya_kolam',
        'budidaya_sawah'
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
