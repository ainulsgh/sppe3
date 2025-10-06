<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
//ganti classnya
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
class PeternakanRecord extends Model
{
    use HasFactory;
    protected $fillable = [
<<<<<<< HEAD
        //sesuaikan dengan database
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
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
