<?php

use App\Http\Controllers\GudangController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\SparepartController;
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
    if (auth()->user()->divisi->id == 24) return redirect('/ppic');
    else if (auth()->user()->divisi->id == 3) return redirect('/manager-teknik');
    else return view('home');
})->middleware('auth');

Route::get('/home', function () {
    return redirect('/');
})->middleware('auth');

Route::get("/test", function () {
    return view('test');
});

Route::middleware('auth')->prefix('/ppic')->group(function () {
    Route::view('/{any?}', 'spa.ppic.spa');
    // Route::get('/data/{status}', function ($status) {
    //     return view('spa.ppic.data', ['status' => $status]);
    // });
    // Route::get('/jadwal/{status}', function ($status) {
    //     return view('spa.ppic.jadwal', ['status' => $status]);
    // });

    // //test
    // Route::view('/bppb/{any}', 'spa.ppic.bppb');
    // Route::view('/test', 'spa.ppic');
    Route::view('/master_stok/show', 'spa.ppic.master_stok.show');
    Route::get('/master_stok/detail/{id}', [App\Http\Controllers\PpicController::class, 'master_stok_detail_show'])->name('ppic.master_stok.detail');
    Route::view('/master_pengiriman/show', 'spa.ppic.master_pengiriman.show');
    Route::get('/master_pengiriman/detail/{id}', [App\Http\Controllers\PpicController::class, 'master_pengiriman_detail_show'])->name('ppic.master_pengiriman.detail');
});
Route::middleware('auth')->prefix('/ppic_direksi')->group(function () {
    Route::view('/{any?}', 'page.direksi.perencanaan');
});
Route::middleware('auth')->prefix('/manager-teknik')->group(function () {
    Route::view('/{any?}', 'spa.manager_teknik.spa');
});

Route::middleware('auth')->prefix('/gbj')->group(function () {
    Route::view('/stok/{any?}', 'page.gbj.stok');
    Route::view('/penjualan/{any?}', 'page.gbj.penjualan');
    Route::view('/produk/{any?}', 'page.gbj.produk');
    Route::view('/so/{any?}', 'page.gbj.so');
    Route::view('/transferproduk', 'page.gbj.transferproduk');
    Route::view('/transferproduk', 'page.gbj.transferproduk');
    Route::view('/bso', 'page.gbj.bso');
    Route::view('/tso', 'page.gbj.tso');
    Route::get('/dp', [GudangController::class, 'terimaRakit']);
    Route::view('/lp', 'page.gbj.lp');
    Route::view('/dashboard', 'page.gbj.dashboard');
    Route::group(['prefix' => '/tp'], function () {
        Route::get('/', [GudangController::class, 'allTp']);
        Route::get('/{id}', [GudangController::class, 'getDetailHistory1']);
    });
    Route::get('/data', [GudangController::class, 'get_data_barang_jadi']);
    Route::get('/export_spb/{id}', [GudangController::class, 'exportSpb'])->name('gbj.spb');
});

Route::middleware('auth')->prefix('/produksi')->group(function () {
    Route::view('/dashboard', 'page.produksi.dashboard');
    Route::view('/so', 'page.produksi.so');
    Route::view('/jadwal_perakitan', 'page.produksi.jadwal_perakitan');
    Route::view('/perencanaan_perakitan', 'page.produksi.perencanaan_perakitan');
    Route::get('/riwayat_perakitan', [ProduksiController::class, 'his_rakit']);
    Route::view('/pengiriman', 'page.produksi.pengiriman');
});
// Route::middleware('auth')->prefix('/penjualan')->group(function () {
//     Route::view('/produk/{any?}', 'page.penjualan.produk');
//     Route::view('/customer/{any?}', 'page.penjualan.customer');
//     Route::view('/penjualan/{any?}', 'page.penjualan.penjualan');
//     Route::view('/po/{any?}', 'page.penjualan.po');
// });

Route::group(['prefix' => 'penjualan', 'middleware' => 'auth'], function () {
    Route::get('/dashboard', [App\Http\Controllers\PenjualanController::class, 'dashboard'])->name('penjualan.dashboard');

    Route::group(['prefix' => '/produk'], function () {
        Route::view('/show', 'page.penjualan.produk.show')->name('penjualan.produk.show');
        Route::view('/create', 'page.penjualan.produk.create')->name('penjualan.produk.create');
        Route::post('/store', [App\Http\Controllers\MasterController::class, 'create_penjualan_produk'])->name('penjualan.produk.store');
        Route::view('/edit', 'page.penjualan.produk.edit')->name('penjualan.produk.edit');
        Route::put('/update/{id}', [App\Http\Controllers\MasterController::class, 'update_penjualan_produk'])->name('penjualan.produk.update');
    });

    Route::group(['prefix' => '/rencana'], function () {
        Route::view('/show', 'page.penjualan.rencana.show')->name('penjualan.rencana.show');
        Route::view('/create', 'page.penjualan.rencana.create')->name('penjualan.rencana.create');
    });

    Route::group(['prefix' => '/customer'], function () {
        Route::view('/show', 'page.penjualan.customer.show')->name('penjualan.customer.show');
        // Route::get('/data/{filter}', [App\Http\Controllers\MasterController::class, 'get_data_customer']);
        Route::view('/create', 'page.penjualan.customer.create')->name('penjualan.customer.create');
        Route::post('/store', [App\Http\Controllers\MasterController::class, 'create_customer'])->name('penjualan.customer.store');
        Route::put('/update/{id}', [App\Http\Controllers\MasterController::class, 'update_customer'])->name('penjualan.customer.update');
        Route::get('/detail/{id}', [App\Http\Controllers\MasterController::class, 'detail_customer'])->name('penjualan.customer.detail');
    });

    Route::group(['prefix' => '/penjualan'], function () {
        Route::view('/show', 'page.penjualan.penjualan.show')->name('penjualan.penjualan.show');
        Route::view('/create', 'page.penjualan.penjualan.create')->name('penjualan.penjualan.create');
        Route::view('/create_new', 'page.penjualan.penjualan.create_new')->name('penjualan.penjualan.create_new');

        // Route::get('/penjualan/data/{jenis}/{status}', [App\Http\Controllers\PenjualanController::class, 'penjualan_data'])->name('penjualan.penjualan.penjualan.data');
        Route::post('/ekatalog/data/{value}', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog']);
        Route::post('/spa/data/{value}', [App\Http\Controllers\PenjualanController::class, 'get_data_spa']);
        Route::post('/spb/data/{value}', [App\Http\Controllers\PenjualanController::class, 'get_data_spb']);

        Route::post('/store', [App\Http\Controllers\PenjualanController::class, 'create_penjualan'])->name('penjualan.penjualan.store');
        Route::get('/detail/ekatalog/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_ekatalog'])->name('penjualan.penjualan.detail.ekatalog');
        Route::get('/detail/spa/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spa'])->name('penjualan.penjualan.detail.spa');
        Route::get('/detail/spb/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spb'])->name('penjualan.penjualan.detail.spb');
        Route::get('/edit_ekatalog/{id}/{jenis}', [App\Http\Controllers\PenjualanController::class, 'update_penjualan'])->name('penjualan.penjualan.edit_ekatalog');
        Route::put('/update/ekatalog/{id}', [App\Http\Controllers\PenjualanController::class, 'update_ekatalog'])->name('penjualan.penjualan.update_ekatalog');
        Route::put('/update/spa/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spa'])->name('penjualan.penjualan.update_spa');
        Route::put('/update/spb/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spb'])->name('penjualan.penjualan.update_spb');
        Route::view('/edit_spa', 'page.penjualan.penjualan.edit_spa')->name('penjualan.penjualan.edit_spa');
        Route::view('/edit_spa', 'page.penjualan.penjualan.edit_spa')->name('penjualan.penjualan.edit_spa');

        //Export Laporan
        Route::get('/export/{jenis}/{customer_id}/{tgl_awal}/{tgl_akhir}', [App\Http\Controllers\PenjualanController::class, 'export_laporan'])->name('penjualan.penjualan.export');
    });

    Route::group(['prefix' => '/so'], function () {
        Route::view('/show', 'page.penjualan.so.show')->name('penjualan.so.show');
        Route::get('/create/{id}', [App\Http\Controllers\PenjualanController::class, 'view_so_ekatalog'])->name('penjualan.so.create');
        Route::put('/store/{id}', [App\Http\Controllers\PenjualanController::class, 'create_so_ekatalog'])->name('penjualan.so.ekatalog.create');
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
    Route::get('/dashboard', [App\Http\Controllers\QcController::class, 'dashboard'])->name('qc.dashboard');
    Route::group(['prefix' => '/so'], function () {
        Route::view('/show', 'page.qc.so.show')->name('qc.so.show');
        Route::get('/detail/{id}/{value}', [App\Http\Controllers\QcController::class, 'detail_so'])->name('qc.so.detail');
        Route::view('/detail_ekatalog/{id}', 'page.qc.so.detail_ekatalog')->name('qc.so.detail_ekatalog');
        Route::view('/detail_spa/{id}', 'page.qc.so.detail_spa')->name('qc.so.detail_spa');
        Route::view('/detail_spb/{id}', 'page.qc.so.detail_spb')->name('qc.so.detail_spb');
        Route::view('/create', 'page.qc.so.create')->name('qc.so.create');
        Route::get('/edit/{jenis}/{produk_id}/{pesanan_id}', [App\Http\Controllers\QcController::class, 'get_data_seri_detail_ekatalog'])->name('qc.so.edit');
        Route::group(['prefix' => '/riwayat'], function () {
            Route::view('/show', 'page.qc.so.riwayat.show')->name('qc.so.riwayat.show');
        });
        Route::group(['prefix' => '/laporan'], function () {
            Route::view('/show', 'page.qc.laporan.show')->name('qc.so.laporan.show');
        });
    });
});

Route::group(['prefix' => 'logistik', 'middleware' => 'auth'], function () {
    Route::get('/dashboard', [App\Http\Controllers\LogistikController::class, 'dashboard'])->name('logistik.dashboard');
    Route::group(['prefix' => '/so'], function () {
        Route::view('/show', 'page.logistik.so.show')->name('logistik.so.show');
        Route::post('/data/{value}', [App\Http\Controllers\LogistikController::class, 'get_data_so']);
        Route::get('/detail/{status}/{id}/{value}', [App\Http\Controllers\logistikController::class, 'update_so'])->name('logistik.so.detail');
        Route::get('/create/{detail_pesanan_id}/{part}/{pesanan_id}/{jenis}', [App\Http\Controllers\logistikController::class, 'create_logistik_view'])->name('logistik.so.create');
        Route::view('/edit', 'page.logistik.so.edit')->name('logistik.so.edit');
        Route::group(['prefix' => '/riwayat'], function () {
            Route::view('/show', 'page.logistik.so.riwayat.show')->name('logistik.so.riwayat.show');
        });
        Route::group(['prefix' => '/laporan'], function () {
            Route::view('/show', 'page.logistik.laporan.show')->name('logistik.so.laporan.show');
        });
    });

    Route::group(['prefix' => '/ekspedisi'], function () {
        Route::post('/data/{value_1}/{value_2}', [App\Http\Controllers\MasterController::class, 'get_data_ekspedisi']);
        Route::view('/show', 'page.logistik.ekspedisi.show')->name('logistik.ekspedisi.show');
        Route::post('/data', [App\Http\Controllers\MasterController::class, 'get_data_ekspedisi']);
        Route::get('/detail/{id}', [App\Http\Controllers\MasterController::class, 'detail_ekspedisi'])->name('logistik.ekspedisi.detail');
        Route::view('/create', 'page.logistik.ekspedisi.create')->name('logistik.ekspedisi.create');
        Route::post('/store', [App\Http\Controllers\MasterController::class, 'create_ekspedisi'])->name('logistik.ekspedisi.store');
        Route::get('/edit/{id}', [App\Http\Controllers\MasterController::class, 'update_ekspedisi_modal'])->name('logistik.ekspedisi.edit');
        Route::put('/update/{id}', [App\Http\Controllers\MasterController::class, 'update_ekspedisi'])->name('logistik.ekspedisi.update');
    });

    Route::group(['prefix' => '/pengiriman'], function () {
        Route::view('/show', 'page.logistik.pengiriman.show')->name('logistik.pengiriman.show');
        Route::post('/data/{pengiriman}/{provinsi}/{jenis_penjualan}', [App\Http\Controllers\LogistikController::class, 'get_data_pengiriman']);
        Route::get('/detail/{id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'get_pengiriman_detail_data'])->name('logistik.pengiriman.detail');
        Route::view('/noseri/{id}', 'page.logistik.pengiriman.noseri')->name('logistik.pengiriman.noseri');
        Route::view('/create', 'page.logistik.pengiriman.create')->name('logistik.pengiriman.create');
        // Route::view('/edit/{id}', 'page.logistik.pengiriman.edit')->name('logistik.pengiriman.edit');
        Route::get('/edit/{id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'update_modal_surat_jalan'])->name('logistik.pengiriman.edit');
        Route::get('/print/{id}', [App\Http\Controllers\LogistikController::class, 'pdf_surat_jalan'])->name('logistik.pengiriman.print');
        Route::group(['prefix' => '/riwayat'], function () {
            Route::view('/show', 'page.logistik.pengiriman.riwayat.show')->name('logistik.riwayat.show');
        });
    });
    Route::group(['prefix' => '/laporan'], function () {
        Route::view('/show', 'page.logistik.laporan.show')->name('logistik.laporan.show');
    });
});

Route::group(['prefix' => 'direksi', 'middleware' => 'auth'], function () {
    Route::get('/dashboard', [App\Http\Controllers\DireksiController::class, 'dashboard'])->name('direksi.dashboard');
});

Route::group(['prefix' => 'dc', 'middleware' => 'auth'], function () {
    Route::get('/dashboard',   [App\Http\Controllers\DcController::class, 'dashboard'])->name('dc.dashboard');

    Route::group(['prefix' => '/so'], function () {
        Route::view('/show', 'page.dc.so.show')->name('dc.so.show');
        Route::get('/detail/{id}/{value}',  [App\Http\Controllers\DcController::class, 'detail_coo'])->name('dc.so.detail');
        Route::view('/create/{id}', 'page.dc.so.create')->name('dc.so.create');
        Route::group(['prefix' => '/laporan'], function () {
            Route::view('/show', 'page.dc.laporan.show')->name('dc.so.laporan.show');
        });
    });

    Route::group(['prefix' => '/coo'], function () {
        Route::view('/show', 'page.dc.coo.show')->name('dc.coo.show');
        Route::view('/detail/{id}', 'page.dc.coo.detail')->name('dc.coo.detail');
        Route::view('/create/{id}', 'page.dc.coo.create')->name('dc.coo.create');
        Route::get('/edit/{id}/{Value}', [App\Http\Controllers\DcController::class, 'edit_coo'])->name('dc.coo.edit');
        Route::get('/pdf/so/{id}/{value}/{jenis}', [App\Http\Controllers\DcController::class, 'pdf_semua_so_coo'])->name('dc.coo.semua.so.pdf');
        Route::get('/pdf/semua/{id}/{value}/{jenis}', [App\Http\Controllers\DcController::class, 'pdf_semua_coo'])->name('dc.coo.semua.pdf');
        Route::get('/pdf/{id}/{value}/{jenis}', [App\Http\Controllers\DcController::class, 'pdf_seri_coo'])->name('dc.seri.coo.pdf');
        Route::group(['prefix' => '/laporan'], function () {
            Route::view('/show', 'page.dc.laporan.show')->name('dc.coo.laporan.show');
        });
    });
});

Route::group(['prefix' => 'as', 'middleware' => 'auth'], function () {
    Route::view('/dashboard', 'page.as.dashboard')->name('as.dashboard');

    Route::group(['prefix' => '/penjualan'], function () {
        Route::view('/show', 'page.as.penjualan.show')->name('as.penjualan.show');
    });

    Route::group(['prefix' => '/so'], function () {
        Route::get('/data', [App\Http\Controllers\AfterSalesController::class, 'get_data_so'])->name('as.so.show');
        Route::get('/detail/{id}/{jenis}', [App\Http\Controllers\AfterSalesController::class, 'get_detail_so'])->name('as.so.detail');
        Route::view('/show', 'page.as.so.show')->name('as.so.show');
        Route::view('/list/{id}', 'page.as.so.list')->name('as.so.list');
    });

    Route::group(['prefix' => '/coo'], function () {
        Route::view('/show', 'page.dc.coo.show')->name('dc.coo.show');
        Route::view('/detail/{id}', 'page.dc.coo.detail')->name('dc.coo.detail');
        Route::view('/create/{id}', 'page.dc.coo.create')->name('dc.coo.create');
        Route::view('/edit/{id}', 'page.dc.coo.edit')->name('dc.coo.edit');
        Route::get('/pdf', [App\Http\Controllers\DcController::class, 'pdf_coo'])->name('dc.coo.pdf');
        Route::group(['prefix' => '/laporan'], function () {
            Route::view('/show', 'page.dc.laporan.show')->name('dc.coo.laporan.show');
        });
    });
});

Route::group(['prefix' => '/gk', 'middleware' => 'auth'], function () {
    Route::view('/dashboard', 'page.gk.dashboard');
    Route::view('/gudang', 'page.gk.gudang.index');
    Route::get('/gudang/sparepart/{id}', [SparepartController::class, 'detail_spr']);
    Route::get('/gudang/unit/{id}', [SparepartController::class, 'detail_unit']);
    Route::view('/terimaProduk', 'page.gk.terima.index');
    Route::get('/terimaProduk/{id}', [SparepartController::class, 'edit_terima']);
    Route::view('/transfer', 'page.gk.transfer.index');
    Route::get('/transfer/{id}', [SparepartController::class, 'edit_tf']);
    Route::view('/transaksi', 'page.gk.transaksi.index');
    Route::get('/transaksi/{id}', [SparepartController::class, 'detail_trx']);
    Route::get('/export', [SparepartController::class, 'exportTransaksi'])->name('gk.export');
    Route::get('/export-produk', [SparepartController::class, 'exportProduk'])->name('gk.export-produk');
    Route::get('/export-unit', [SparepartController::class, 'exportUnit'])->name('gk.export-unit');
});

Route::view('/uit', 'page.login_page.index');

// Route::group(['prefix' => '/gbj', 'middleware' => 'auth'], function () {
//     Route::view('/stok', 'page.gbj.stok_show');
// });
