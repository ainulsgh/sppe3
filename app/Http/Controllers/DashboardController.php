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
            //tambahkan dinas baru
            'admin'         => 'admin',
            'administrator' => 'admin',
            'dinas perikanan'  => 'perikanan',
            'dinas pertanian'  => 'pertanian',
            'dinas peternakan'  => 'peternakan',
            'dinas perhubungan'  => 'perhubungan',
            'dpmptsp'  => 'dpmptsp',
            'dinas pertanian' => 'pertanian'
        ];
        $key = $map[$role] ?? null;

        return match ($key) {
            //tambahkan dinas baru
            'admin'      => redirect()->route('admin.dashboard'),
            'perikanan'  => redirect()->route('perikanan.dashboard'),
            'pertanian'  => redirect()->route('pertanian.dashboard'),
            'peternakan'  => redirect()->route('peternakan.dashboard'),
            'perhubungan'  => redirect()->route('perhubungan.dashboard'),
            'dpmptsp'  => redirect()->route('dpmptsp.dashboard'),
            'pertanian'  => redirect()->route('pertanian.dashboard'),
            default      => Inertia::render('login'),
        };
    }
}
