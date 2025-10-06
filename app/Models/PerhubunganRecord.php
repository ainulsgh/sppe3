<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
//ganti classnya
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
class PerhubunganRecord extends Model
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
