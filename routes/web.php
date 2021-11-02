<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    if (auth()->user()->divisi->id == 24) return redirect('/ppic/dashboard');
    else return redirect('/home');
})->middleware('auth');

Route::get('/home', function () {
    return view('home');
})->middleware('auth');

// Route::get('/ppic/{any}', function () {
//     return view('test');
// })->where('any', '.*');

Route::middleware('auth')->prefix('/ppic')->group(function () {
    Route::view('/dashboard', 'spa.ppic.dashboard');
    Route::view('/gudang/{any}', 'spa.ppic.gudang');
    Route::view('/schedule/{any}', 'spa.ppic.jadwal');
    Route::view('/bppb/{any}', 'spa.ppic.bppb');
});

Route::middleware('auth')->prefix('/gbj')->group(function () {
    Route::view('/stok/{any?}', 'page.gbj.stok');
    Route::view('/penjualan/{any?}', 'page.gbj.penjualan');
});

// Route::middleware('auth')->prefix('/penjualan')->group(function () {
//     Route::view('/produk/{any?}', 'page.penjualan.produk');
//     Route::view('/customer/{any?}', 'page.penjualan.customer');
//     Route::view('/penjualan/{any?}', 'page.penjualan.penjualan');
//     Route::view('/po/{any?}', 'page.penjualan.po');
// });

Route::group(['prefix' => 'penjualan', 'middleware' => 'auth'], function () {
    Route::view('/dashboard', 'page.penjualan.dashboard')->name('penjualan.dashboard');

    Route::group(['prefix' => '/produk'], function () {
        Route::view('/show', 'page.penjualan.produk.show')->name('penjualan.produk.show');
        Route::view('/create', 'page.penjualan.produk.create')->name('penjualan.produk.create');
        Route::view('/edit', 'page.penjualan.produk.edit')->name('penjualan.produk.edit');
    });

    Route::group(['prefix' => '/customer'], function () {
        Route::view('/show', 'page.penjualan.customer.show')->name('penjualan.customer.show');
        Route::view('/create', 'page.penjualan.customer.create')->name('penjualan.customer.create');
        Route::get('/detail/{id}', [App\Http\Controllers\MasterController::class, 'detail_customer'])->name('penjualan.customer.detail');
    });

    Route::group(['prefix' => '/penjualan'], function () {
        Route::view('/show', 'page.penjualan.penjualan.show')->name('penjualan.penjualan.show');
        Route::view('/create', 'page.penjualan.penjualan.create')->name('penjualan.penjualan.create');
        Route::view('/detail/ekatalog/{id}', 'page.penjualan.penjualan.detail_ekatalog')->name('penjualan.penjualan.detail.ekatalog');
        Route::view('/detail/spa/{id}', 'page.penjualan.penjualan.detail_spa')->name('penjualan.penjualan.detail.spa');
        Route::view('/detail/spb/{id}', 'page.penjualan.penjualan.detail_spb')->name('penjualan.penjualan.detail.spb');
        Route::view('/edit/{id}/{jenis}', 'page.penjualan.penjualan.edit')->name('penjualan.penjualan.edit');
    });

    Route::group(['prefix' => '/so'], function () {
        Route::view('/show', 'page.penjualan.so.show')->name('penjualan.so.show');
        Route::view('/create', 'page.penjualan.so.create')->name('penjualan.so.create');
        Route::view('/edit', 'page.penjualan.so.edit')->name('penjualan.so.edit');
    });

    Route::group(['prefix' => '/lacak'], function () {
        Route::view('/show', 'page.penjualan.lacak.show')->name('penjualan.lacak.show');
    });

    Route::group(['prefix' => '/laporan'], function () {
        Route::view('/show', 'page.penjualan.laporan.show')->name('penjualan.laporan.show');
    });
    // Route::get('/dashboard', 'digidocu\DocumentsController@dashboard')->name('dc.dashboard');
    // Route::get('/dep_doc/{id?}', 'digidocu\DocumentsController@dep_doc')->name('dc.dep_doc');
});

Route::group(['prefix' => 'qc', 'middleware' => 'auth'], function () {
    Route::view('/dashboard', 'page.qc.dashboard')->name('qc.dashboard');

    Route::group(['prefix' => '/so'], function () {
        Route::view('/show', 'page.qc.so.show')->name('qc.so.show');
        Route::view('/detail/{id}', 'page.qc.so.detail')->name('qc.so.detail');
        Route::view('/create', 'page.qc.so.create')->name('qc.so.create');
        Route::view('/edit', 'page.qc.so.edit')->name('qc.so.edit');
    });
});
Route::get('/provinsi', [ProvincesController::class, 'provinsi'])->name('provinsi');

Route::get('/test/{name?}', function ($name = null) {
    return $name;
});

// Route::group(['prefix' => '/gbj', 'middleware' => 'auth'], function () {
//     Route::view('/stok', 'page.gbj.stok_show');
// });
