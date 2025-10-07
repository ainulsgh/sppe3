<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $raw = (string) (Auth::user()->role ?? '');
        $role = strtolower(trim($raw));
        $map = [
<<<<<<< HEAD
            //tambahkan dinas baru
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
            'admin'         => 'admin',
            'administrator' => 'admin',
            'dinas perikanan'  => 'perikanan',
            'dinas pertanian'  => 'pertanian',
            'dinas peternakan'  => 'peternakan',
            'dinas perhubungan'  => 'perhubungan',
            'dpmptsp'  => 'dpmptsp',
<<<<<<< HEAD
            'dinas pertanian' => 'pertanian'
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        ];
        $key = $map[$role] ?? null;

        return match ($key) {
<<<<<<< HEAD
            //tambahkan dinas baru
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
            'admin'      => redirect()->route('admin.dashboard'),
            'perikanan'  => redirect()->route('perikanan.dashboard'),
            'pertanian'  => redirect()->route('pertanian.dashboard'),
            'peternakan'  => redirect()->route('peternakan.dashboard'),
            'perhubungan'  => redirect()->route('perhubungan.dashboard'),
            'dpmptsp'  => redirect()->route('dpmptsp.dashboard'),
<<<<<<< HEAD
            'pertanian'  => redirect()->route('pertanian.dashboard'),
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
            default      => Inertia::render('login'),
        };
    }
}
