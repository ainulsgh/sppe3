<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
//ubah classnya
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
class PerikananRecord extends Model
{
    use HasFactory;
    protected $fillable = [
<<<<<<< HEAD
        //sesuiakn dengan yang ada di database
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
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
