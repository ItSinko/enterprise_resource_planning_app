<?php
use App\Http\Controllers\inventory\AlatujiController;
use App\Http\Controllers\inventory\PerawatanController;
use App\Http\Controllers\inventory\VerifikasiController;
use App\Http\Controllers\inventory\KalibrasiPerbaikanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::group(['prefix' => 'lab'], function () {
    Route::group(['middleware' => ['divisi:lab']], function () {
        Route::get('/dashboard', [AlatujiController::class, 'dashboard'])->name('lab.dashboard');
    });
    Route::group(['prefix' => 'alatuji'], function () {
        Route::view('/show', 'page.lab.alatuji')->name('alatuji.show');
        Route::get('/detail/{id}/{x?}', [AlatujiController::class, 'detail'])->name('alatuji.detail');
        Route::get('/doc/{jenis}/{id}', [AlatujiController::class, 'show_document'])->name('alatuji.doc');

        Route::group(['prefix' => 'pinjam'], function () {
            Route::post('/store', [AlatujiController::class, 'store_pinjam'])->name('alatuji.pinjam.store');
        });

        Route::group(['middleware' => ['divisi:lab']], function () {
            Route::get('/create', [AlatujiController::class, 'tambahalat'])->name('alatuji.create');
            Route::post('/store', [AlatujiController::class, 'store_jenisalat'])->name('alatuji.store');
            Route::get('/edit/{id}', [AlatujiController::class, 'edit_alat'])->name('alatuji.edit');
            Route::post('/update', [AlatujiController::class, 'store_editalat'])->name('alatuji.update');
            Route::group(['prefix' => 'pinjam'], function () {
                Route::post('/konfirmasi', [AlatujiController::class, 'store_konfirmasi'])->name('alatuji.pinjam.konfirmasi');
                Route::post('/kembali', [AlatujiController::class, 'store_kembali'])->name('alatuji.pinjam.kembali');
            });
            Route::group(['prefix' => 'barang'], function () {
                Route::get('/create', [AlatujiController::class, 'tambahbarang'])->name('alatuji.barang.create');
                Route::post('/store', [AlatujiController::class, 'store_tambahbarang'])->name('alatuji.barang.store');
            });
            Route::group(['prefix' => 'perawatan'], function () {
                Route::get('/detail/{id}', [PerawatanController::class, 'index'])->name('alatuji.perawatan.detail');
                Route::post('/store', [PerawatanController::class, 'store'])->name('alatuji.perawatan.store');
            });
            Route::group(['prefix' => 'target'], function () {
                Route::post('/store', [AlatujiController::class, 'store_target'])->name('alatuji.target.store');
            });

            Route::group(['prefix' => 'verifikasi'], function () {
                Route::get('/detail/{id}', [VerifikasiController::class, 'index'])->name('alatuji.verifikasi.detail');
                Route::post('/store', [VerifikasiController::class, 'store_verifikasi'])->name('alatuji.verifikasi.store');
            });
            Route::group(['prefix' => 'mt'], function () {
                Route::get('/create/{jenis}/{id}', [KalibrasiPerbaikanController::class, 'index'])->name('alatuji.mt.create');
                Route::post('/store', [KalibrasiPerbaikanController::class, 'store_mt'])->name('alatuji.mt.store');
                Route::get('/konfirmasi/{jenis}/{id}', [KalibrasiPerbaikanController::class, 'confirm'])->name('alatuji.mt.konfirmasi');
                Route::post('/external/store', [KalibrasiPerbaikanController::class, 'store_external'])->name('alatuji.mt.external.store');
                // Route::get('/create/{jenis}/{id}', [KalibrasiPerbaikanController::class, 'index'])->name('alatuji.mt.create');
            });
        });

    });



    // Route::get('/detail/{id}/{x?}', [AlatujiController::class, 'detail'])->name('detail');
    // Route::get('/doc/{jenis}/{id}', [AlatujiController::class, 'show_document']);

    // Route::post('/store_pinjam', [AlatujiController::class, 'store_pinjam']);


});

// Alat Uji End
