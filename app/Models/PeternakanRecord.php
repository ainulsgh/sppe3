<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeternakanRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'tahun',
        'bulan',
        'daging_sapi',
        'daging_kambing',
        'daging_kuda',
        'daging_ayam_buras',
        'daging_ayam_ras_pedaging',
        'daging_itik',
        'telur_ayam_petelur',
        'telur_ayam_buras',
        'telur_itik',
        'telur_ayam_ras_petelur_rak',
        'telur_ayam_buras_rak',
        'telur_itik_rak'
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
