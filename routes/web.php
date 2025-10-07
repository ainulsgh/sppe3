<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PerikananRecordController;
use App\Http\Controllers\PerhubunganRecordController;
use App\Http\Controllers\PeternakanRecordController;
use App\Http\Controllers\DpmptspRecordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PertanianRecordController;

Route::get('/', function () {
        return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        //admin
        Route::prefix('admin')->middleware('role:admin')->group(function () {
                Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
                Route::get('/data',      [AdminController::class, 'data'])->name('admin.data');
                Route::get('/export', [AdminController::class, 'export'])->name('admin.export');
        });

        //dinas perikanan
        Route::prefix('perikanan')->middleware('role:dinas perikanan')->group(function () {
                Route::get('/dashboard', [PerikananRecordController::class, 'dashboard'])->name('perikanan.dashboard');
                Route::get('/inputdata', [PerikananRecordController::class, 'index'])->name('perikanan.index');
                Route::post('/input', [PerikananRecordController::class, 'store'])->name('perikanan.store');
                Route::get('/data', [PerikananRecordController::class, 'data'])->name('perikanan.data');
                Route::get('/edit', [PerikananRecordController::class, 'edit'])->name('perikanan.edit');
                Route::post('/edit', [PerikananRecordController::class, 'upsert'])->name('perikanan.update');
                Route::post('/upsert',    [PerikananRecordController::class, 'upsert'])->name('perikanan.upsert');
                Route::get('/export', [PerikananRecordController::class, 'export'])->name('perikanan.export');
        });

        //dinas peternakan
        Route::prefix('peternakan')->middleware('role:dinas peternakan')->group(function () {
                Route::get('/dashboard', [PeternakanRecordController::class, 'dashboard'])->name('peternakan.dashboard');
                Route::get('/inputdata', [PeternakanRecordController::class, 'index'])->name('peternakan.index');
                Route::post('/input', [PeternakanRecordController::class, 'store'])->name('peternakan.store');
                Route::get('/data', [PeternakanRecordController::class, 'data'])->name('peternakan.data');
                Route::get('/edit', [PeternakanRecordController::class, 'edit'])->name('peternakan.edit');
                Route::post('/edit', [PeternakanRecordController::class, 'upsert'])->name('peternakan.update');
                Route::post('/upsert', [PeternakanRecordController::class, 'upsert'])->name('peternakan.upsert');
                Route::get('/export', [PeternakanRecordController::class, 'export'])->name('peternakan.export');
        });

        //dinas perhubungan
        Route::prefix('perhubungan')->middleware('role:dinas perhubungan')->group(function () {
                Route::get('/dashboard', [PerhubunganRecordController::class, 'dashboard'])->name('perhubungan.dashboard');
                Route::get('/inputdata', [PerhubunganRecordController::class, 'index'])->name('perhubungan.index');
                Route::post('/input', [PerhubunganRecordController::class, 'store'])->name('perhubungan.store');
                Route::get('/data', [PerhubunganRecordController::class, 'data'])->name('perhubungan.data');
                Route::get('/edit', [PerhubunganRecordController::class, 'edit'])->name('Perhubungan.edit');
                Route::post('/edit', [PerhubunganRecordController::class, 'upsert'])->name('perhubungan.update');
                Route::post('/upsert', [PerhubunganRecordController::class, 'upsert'])->name('perhubungan.upsert');
                Route::get('/export', [PerhubunganRecordController::class, 'export'])->name('perhubungan.export');
        });

        //dpmptsp
        Route::prefix('dpmptsp')->middleware('role:dpmptsp')->group(function () {
                Route::get('/dashboard', [DpmptspRecordController::class, 'dashboard'])->name('dpmptsp.dashboard');
                Route::get('/inputdata', [DpmptspRecordController::class, 'index'])->name('dpmptsp.index');
                Route::post('/input', [DpmptspRecordController::class, 'store'])->name('dpmptsp.store');
                Route::get('/data', [DpmptspRecordController::class, 'data'])->name('dpmptsp.data');
                Route::get('/edit', [DpmptspRecordController::class, 'edit'])->name('dpmptsp.edit');
                Route::post('/edit', [DpmptspRecordController::class, 'upsert'])->name('dpmptsp.update');
                Route::post('/upsert', [DpmptspRecordController::class, 'upsert'])->name('dpmptsp.upsert');
                Route::get('/export', [DpmptspRecordController::class, 'export'])->name('dpmptsp.export');
                Route::delete('/{DpmptspRecord}', [DpmptspRecordController::class, 'destroy'])->name('dpmptsp.destroy');
        });

        //dinas pertanian
        Route::prefix('pertanian')->middleware('role:dinas pertanian')->group(function () {
                Route::get('/dashboard', [PertanianRecordController::class, 'dashboard'])->name('pertanian.dashboard');
                Route::get('/inputdata', [PertanianRecordController::class, 'index'])->name('pertanian.index');
                Route::post('/input', [PertanianRecordController::class, 'store'])->name('pertanian.store');
                Route::get('/data', [PertanianRecordController::class, 'data'])->name('pertanian.data');
                Route::get('/edit', [PertanianRecordController::class, 'edit'])->name('pertanian.edit');
                Route::post('/edit', [PertanianRecordController::class, 'upsert'])->name('pertanian.update');
                Route::post('/upsert', [PertanianRecordController::class, 'upsert'])->name('pertanian.upsert');
                Route::get('/export', [PertanianRecordController::class, 'export'])->name('pertanian.export');
        });

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__ . '/auth.php';
