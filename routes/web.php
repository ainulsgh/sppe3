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
<<<<<<< HEAD
use App\Http\Controllers\PertanianRecordController;
=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
<<<<<<< HEAD
=======
    // Dashboard umum (boleh redirect by role di controller ini)
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
    Route::get('/dashboard', DashboardController::class)->name('dashboard');


    //admin
    Route::prefix('admin')->middleware('role:admin')->group(function () {
<<<<<<< HEAD
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
=======
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        Route::get('/data',      [AdminController::class, 'data'])->name('admin.data');
        Route::get('/export', [AdminController::class, 'export'])->name('admin.export');
    });

    //dinas perikanan
    Route::prefix('perikanan')->middleware('role:dinas perikanan')->group(function () {
        // Route::get('/dashboard', fn() => Inertia::render('DinasPerikanan/Dashboard'))->name('perikanan.dashboard');
<<<<<<< HEAD
        Route::get('/dashboard', [PerikananRecordController::class, 'dashboard'])->name('perikanan.dashboard');
        Route::get('/inputdata', [PerikananRecordController::class, 'index'])->name('perikanan.index');
=======
        Route::get('/dashboard', [App\Http\Controllers\PerikananRecordController::class, 'dashboard'])->name('perikanan.dashboard');
        Route::get('/', [PerikananRecordController::class, 'index'])->name('perikanan.index');
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        Route::post('/input', [PerikananRecordController::class, 'store'])->name('perikanan.store');
        Route::get('/data', [PerikananRecordController::class, 'data'])->name('perikanan.data');
        Route::get('/edit', [PerikananRecordController::class, 'edit'])->name('perikanan.edit');
        Route::post('/edit', [PerikananRecordController::class, 'upsert'])->name('perikanan.update');
        Route::post('/upsert',    [PerikananRecordController::class, 'upsert'])->name('perikanan.upsert');
        Route::get('/export', [PerikananRecordController::class, 'export'])->name('perikanan.export');
    });

    //dinas peternakan
    Route::prefix('peternakan')->middleware('role:dinas peternakan')->group(function () {
<<<<<<< HEAD
        Route::get('/dashboard', [PeternakanRecordController::class, 'dashboard'])->name('peternakan.dashboard');
        Route::get('/inputdata', [PeternakanRecordController::class, 'index'])->name('peternakan.index');
        Route::post('/input', [PeternakanRecordController::class, 'store'])->name('peternakan.store');
        Route::get('/data', [PeternakanRecordController::class, 'data'])->name('peternakan.data');
        Route::get('/edit', [PeternakanRecordController::class, 'edit'])->name('peternakan.edit');
        Route::post('/edit', [PeternakanRecordController::class, 'upsert'])->name('peternakan.update');
        Route::post('/upsert', [PeternakanRecordController::class, 'upsert'])->name('peternakan.upsert');
=======
        Route::get('/dashboard', [App\Http\Controllers\PeternakanRecordController::class, 'dashboard'])->name('peternakan.dashboard');
        Route::get('/', [PeternakanRecordController::class, 'index'])->name('peternakan.index');
        Route::post('/', [PeternakanRecordController::class, 'store'])->name('peternakan.store');
        Route::get('/data', [PeternakanRecordController::class, 'data'])->name('peternakan.data');
        Route::get('/edit', [PeternakanRecordController::class, 'edit'])->name('peternakan.edit');
        Route::post('/edit', [PeternakanRecordController::class, 'upsert'])->name('peternakan.update');
        Route::post('/upsert', [PeternakanRecordController::class, 'upsert'])->name('Peternakan.upsert');
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        Route::get('/export', [PeternakanRecordController::class, 'export'])->name('peternakan.export');
    });

    //dinas perhubungan
    Route::prefix('perhubungan')->middleware('role:dinas perhubungan')->group(function () {
<<<<<<< HEAD
        Route::get('/dashboard', [PerhubunganRecordController::class, 'dashboard'])->name('perhubungan.dashboard');
        Route::get('/inputdata', [PerhubunganRecordController::class, 'index'])->name('perhubungan.index');
        Route::post('/input', [PerhubunganRecordController::class, 'store'])->name('perhubungan.store');
        Route::get('/data', [PerhubunganRecordController::class, 'data'])->name('perhubungan.data');
        Route::get('/edit', [PerhubunganRecordController::class, 'edit'])->name('Perhubungan.edit');
        Route::post('/edit', [PerhubunganRecordController::class, 'upsert'])->name('perhubungan.update');
        Route::post('/upsert', [PerhubunganRecordController::class, 'upsert'])->name('perhubungan.upsert');
        Route::get('/export', [PerhubunganRecordController::class, 'export'])->name('perhubungan.export');
=======
        Route::get('/dashboard', [App\Http\Controllers\PerhubunganRecordController::class, 'dashboard'])->name('perhubungan.dashboard');
        Route::get('/', [PerhubunganRecordController::class, 'index'])->name('perhubungan.index');
        Route::post('/', [PerhubunganRecordController::class, 'store'])->name('perhubungan.store');
        Route::get('/data', [PerhubunganRecordController::class, 'data'])->name('Perhubungan.data');
        Route::get('/edit', [PerhubunganRecordController::class, 'edit'])->name('Perhubungan.edit');
        Route::post('/edit', [PerhubunganRecordController::class, 'upsert'])->name('Perhubungan.update');
        Route::post('/upsert', [PerhubunganRecordController::class, 'upsert'])->name('Perhubungan.upsert');
        Route::get('/export', [PerhubunganRecordController::class, 'export'])->name('Perhubungan.export');
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
    });

    //dpmptsp
    Route::prefix('dpmptsp')->middleware('role:dpmptsp')->group(function () {
        Route::get('/dashboard', [DpmptspRecordController::class, 'dashboard'])->name('dpmptsp.dashboard');
<<<<<<< HEAD
        Route::get('/inputdata', [DpmptspRecordController::class, 'index'])->name('dpmptsp.index');
        Route::post('/input', [DpmptspRecordController::class, 'store'])->name('dpmptsp.store');
=======
        Route::get('/', [DpmptspRecordController::class, 'index'])->name('dpmptsp.index');
        Route::post('/', [DpmptspRecordController::class, 'store'])->name('dpmptsp.store');
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
        Route::get('/data', [DpmptspRecordController::class, 'data'])->name('dpmptsp.data');
        Route::get('/edit', [DpmptspRecordController::class, 'edit'])->name('dpmptsp.edit');
        Route::post('/edit', [DpmptspRecordController::class, 'upsert'])->name('dpmptsp.update');
        Route::post('/upsert', [DpmptspRecordController::class, 'upsert'])->name('dpmptsp.upsert');
        Route::get('/export', [DpmptspRecordController::class, 'export'])->name('dpmptsp.export');
        Route::delete('/{DpmptspRecord}', [DpmptspRecordController::class, 'destroy'])->name('dpmptsp.destroy');
    });
<<<<<<< HEAD

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

=======
>>>>>>> 6e0ca59fbea2962653e41a069bd3ed95bf98a112
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__ . '/auth.php';
