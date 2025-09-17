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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard umum (boleh redirect by role di controller ini)
    Route::get('/dashboard', DashboardController::class)->name('dashboard');


    //admin
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/data',      [AdminController::class, 'data'])->name('admin.data');
        Route::get('/export', [AdminController::class, 'export'])->name('admin.export');
    });

    //dinas perikanan
    Route::prefix('perikanan')->middleware('role:dinas perikanan')->group(function () {
        // Route::get('/dashboard', fn() => Inertia::render('DinasPerikanan/Dashboard'))->name('perikanan.dashboard');
        Route::get('/dashboard', [App\Http\Controllers\PerikananRecordController::class, 'dashboard'])->name('perikanan.dashboard');
        Route::get('/', [PerikananRecordController::class, 'index'])->name('perikanan.index');
        Route::post('/input', [PerikananRecordController::class, 'store'])->name('perikanan.store');
        Route::get('/data', [PerikananRecordController::class, 'data'])->name('perikanan.data');
        Route::get('/edit', [PerikananRecordController::class, 'edit'])->name('perikanan.edit');
        Route::post('/edit', [PerikananRecordController::class, 'upsert'])->name('perikanan.update');
        Route::post('/upsert',    [PerikananRecordController::class, 'upsert'])->name('perikanan.upsert');
        Route::get('/export', [PerikananRecordController::class, 'export'])->name('perikanan.export');
    });

    //dinas peternakan
    Route::prefix('peternakan')->middleware('role:dinas peternakan')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\PeternakanRecordController::class, 'dashboard'])->name('peternakan.dashboard');
        Route::get('/', [PeternakanRecordController::class, 'index'])->name('peternakan.index');
        Route::post('/', [PeternakanRecordController::class, 'store'])->name('peternakan.store');
        Route::get('/data', [PeternakanRecordController::class, 'data'])->name('peternakan.data');
        Route::get('/edit', [PeternakanRecordController::class, 'edit'])->name('peternakan.edit');
        Route::post('/edit', [PeternakanRecordController::class, 'upsert'])->name('peternakan.update');
        Route::post('/upsert', [PeternakanRecordController::class, 'upsert'])->name('Peternakan.upsert');
        Route::get('/export', [PeternakanRecordController::class, 'export'])->name('peternakan.export');
    });

    //dinas perhubungan
    Route::prefix('perhubungan')->middleware('role:dinas perhubungan')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\PerhubunganRecordController::class, 'dashboard'])->name('perhubungan.dashboard');
        Route::get('/', [PerhubunganRecordController::class, 'index'])->name('perhubungan.index');
        Route::post('/', [PerhubunganRecordController::class, 'store'])->name('perhubungan.store');
        Route::get('/data', [PerhubunganRecordController::class, 'data'])->name('Perhubungan.data');
        Route::get('/edit', [PerhubunganRecordController::class, 'edit'])->name('Perhubungan.edit');
        Route::post('/edit', [PerhubunganRecordController::class, 'upsert'])->name('Perhubungan.update');
        Route::post('/upsert', [PerhubunganRecordController::class, 'upsert'])->name('Perhubungan.upsert');
        Route::get('/export', [PerhubunganRecordController::class, 'export'])->name('Perhubungan.export');
    });

    //dpmptsp
    Route::prefix('dpmptsp')->middleware('role:dpmptsp')->group(function () {
        Route::get('/dashboard', [DpmptspRecordController::class, 'dashboard'])->name('dpmptsp.dashboard');
        Route::get('/', [DpmptspRecordController::class, 'index'])->name('dpmptsp.index');
        Route::post('/', [DpmptspRecordController::class, 'store'])->name('dpmptsp.store');
        Route::get('/data', [DpmptspRecordController::class, 'data'])->name('dpmptsp.data');
        Route::get('/edit', [DpmptspRecordController::class, 'edit'])->name('dpmptsp.edit');
        Route::post('/edit', [DpmptspRecordController::class, 'upsert'])->name('dpmptsp.update');
        Route::post('/upsert', [DpmptspRecordController::class, 'upsert'])->name('dpmptsp.upsert');
        Route::get('/export', [DpmptspRecordController::class, 'export'])->name('dpmptsp.export');
        Route::delete('/{DpmptspRecord}', [DpmptspRecordController::class, 'destroy'])->name('dpmptsp.destroy');
    });
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__ . '/auth.php';
