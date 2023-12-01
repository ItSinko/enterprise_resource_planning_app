<?php

use App\Http\Controllers\GudangController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\KualitasAirController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\inventory\AlatujiController;
use App\Http\Controllers\inventory\PerawatanController;
use App\Http\Controllers\inventory\VerifikasiController;
use App\Http\Controllers\inventory\KalibrasiPerbaikanController;
use App\Http\Controllers\LogistikController;

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
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', function () {
        return view('auth.login');
    });
});

// Route::get('/', function () {
//     if (auth()->user()->divisi_id == 24) return redirect('/ppic');
//     else if (auth()->user()->divisi_id == 3) return redirect('/manager-teknik');
//     else return view('home');
// })->middleware('auth');

Route::get('/home', function () {
    return redirect('/');
})->middleware('auth');

Route::get("/test", function () {
    return view('test');
});
Route::view('/modul_dashboard/show', 'auth.dashboard');
Route::group(['middleware' => 'auth', 'middleware' => ['auth', 'divisi:jual,kes,prd,dc,gbj,qc,log,gk,mtc,mgrgdg,dirut,it']], function () {
    Route::view('/edit_pwd', 'page.setting.edit_pwd');
    Route::post('/edit_pwd', [App\Http\Controllers\Auth\ResetPasswordController::class, 'update_pwd'])->name('penjualan.produk.store');
    Route::post('/edit_profile_photo', [App\Http\Controllers\Auth\ResetPasswordController::class, 'edit_profile_photo']);
});
Route::group(['prefix' => 'administrator', 'middleware' => 'auth'], function () {
    Route::view('/dashboard', 'page.administrator.dashboard');
    Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
        Route::view('/', 'page.administrator.user.show');
        Route::post('/data', [App\Http\Controllers\administrator\AdministratorController::class, 'get_data_user']);
        Route::post('/ubah_status', [App\Http\Controllers\administrator\AdministratorController::class, 'get_change_status_user'])->name('user.status');
        Route::get('/ubah_data/{id}', [App\Http\Controllers\administrator\AdministratorController::class, 'get_data_user_modal']);
        Route::get('/tambah', [App\Http\Controllers\administrator\AdministratorController::class, 'get_create_user_modal']);
        Route::post('/store', [App\Http\Controllers\administrator\AdministratorController::class, 'get_store_user']);
        Route::post('/update/{jenis}/{id}', [App\Http\Controllers\administrator\AdministratorController::class, 'get_update_user']);
        Route::post('/reset_pwd/{id}', [App\Http\Controllers\administrator\AdministratorController::class, 'reset_pwd_user'])->name('user.resetpwd');
    });
    Route::view('/{any?}', 'page.it.produk')->where('any', '.*');
    Route::group(['prefix' => 'part', 'middleware' => 'auth'], function () {
        Route::view('/', 'page.administrator.part.show');
    });
});
Route::group(['prefix' => 'ppic', 'middleware' => 'auth'], function () {
    Route::view('/{any?}', 'spa.ppic.spa')->middleware('divisi:ppic')->where('any', '.*');
    Route::group(['middleware' => ['divisi:jual,dirut,ppic']], function () {
        Route::view('/master_stok/show', 'spa.ppic.master_stok.show');
        Route::view('/master_pengiriman/show', 'spa.ppic.master_pengiriman.show');
        Route::get('/master_stok/detail/{id}', [App\Http\Controllers\PpicController::class, 'master_stok_detail_show'])->name('ppic.master_stok.detail');
        Route::get('/master_pengiriman/detail/{id}', [App\Http\Controllers\PpicController::class, 'master_pengiriman_detail_show'])->name('ppic.master_pengiriman.detail');
    });
});
// Route::middleware('auth')->prefix('/ppic')->group(function () {

// Route::get('/data/{status}', function ($status) {
//     return view('spa.ppic.data', ['status' => $status]);
// });
// Route::get('/jadwal/{status}', function ($status) {
//     return view('spa.ppic.jadwal', ['status' => $status]);
// });
// //test
// Route::view('/bppb/{any}', 'spa.ppic.bppb');
// Route::view('/test', 'spa.ppic');
//});
Route::middleware('auth')->prefix('/ppic_direksi')->group(function () {
    Route::view('/{any?}', 'page.direksi.perencanaan')->where('any', '.*');
});
Route::middleware('auth')->prefix('/manager-teknik')->group(function () {
    Route::view('/{any?}', 'spa.manager_teknik.spa')->middleware('divisi:dirtek')->where('any', '.*');
});

Route::group(['prefix' => '/produksiReworks' , 'middleware' => 'auth'], function () {
    Route::get('/export_excel/{id}', [ProduksiController::class, 'export_rework_excel']);
    Route::get('/cetak_seri_finish_goods/{seri}', [ProduksiController::class, 'cetak_seri_finish_goods']);
    Route::get('/cetak_seri_fg_medium', [ProduksiController::class, 'cetak_seri_finish_goods_medium']);
    Route::get('/cetak_seri_fg_small', [ProduksiController::class, 'cetak_seri_finish_goods_small']);;
    Route::get('/cetakseriReworkAll', [ProduksiController::class, 'cetak_seri_rework_all']);
    Route::get('/cetakseriReworkAllKardus', [ProduksiController::class, 'cetak_seri_rework_all_kardus']);
    Route::get('/viewpackinglist/{id}', [ProduksiController::class, 'view_packing_list']);
    Route::get('/cetakpackinglist', [ProduksiController::class, 'cetak_packing_list']);
    Route::get('/surat_permintaan/{id}', [ProduksiController::class, 'cetakSuratPermintaan']);
    Route::get('/surat_penyerahan/{id}/{divisi}', [ProduksiController::class, 'cetakSuratPenyerahan']);
    Route::get('/surat_pengiriman/{id}', [GudangController::class, 'cetakSuratPengantar']);
    Route::get('/cetakpeti/{id}', [LogistikController::class, 'cetak_peti']);
    Route::get('/viewpeti/{id}', [LogistikController::class, 'view_peti']);
});

Route::group(['prefix' => '/gbj', 'middleware' => 'auth'], function () {
    Route::view('/rework/{any?}', 'page.gbj.gbj_rework')->where('any', '.*');
    Route::view('/stok/{any?}', 'page.gbj.stok')->where('any', '.*');
    Route::view('/penjualan/{any?}', 'page.gbj.penjualan');
    Route::view('/produk/{any?}', 'page.gbj.produk')->where('any', '.*');
    Route::view('/so/{any?}', 'page.gbj.so')->where('any', '.*');
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
    Route::get('/export_nonso/{id}', [GudangController::class, 'exportNonso'])->name('gbj.nonso');
    Route::get('/export_spb/{id}', [GudangController::class, 'exportSpb'])->name('gbj.spb');
    Route::get('/export_noseri', [GudangController::class, 'export_noseri_gudang'])->name('gbj.noseri');
    // Route::view('/manager/produk', 'manager.gbj.produksi');
});

Route::group(['prefix' => '/produksi', 'middleware' => 'auth'], function () {
    Route::view('/dashboard', 'page.produksi.dashboard');
    Route::view('/so', 'page.produksi.so');
    Route::view('/perencanaan_perakitan', 'page.produksi.perencanaan_perakitan');
    Route::get('/riwayat_perakitan', [ProduksiController::class, 'his_rakit']);
    Route::get('/export_noseri', [ProduksiController::class, 'export_noseri_produksi'])->name('export.rakitseri');
    Route::view('/pengiriman', 'page.produksi.pengiriman');
    Route::view('/riwayat_transfer', 'page.produksi.riwayat_transfer');
    Route::view('/jadwal_perakitan', 'page.produksi.jadwal_perakitan');
    Route::view('/{any?}', 'page.produksi.new_produksi')->where('any', '.*');
});
// Route::middleware('auth')->prefix('/penjualan')->group(function () {
//     Route::view('/produk/{any?}', 'page.penjualan.produk');
//     Route::view('/customer/{any?}', 'page.penjualan.customer');
//     Route::view('/penjualan/{any?}', 'page.penjualan.penjualan');
//     Route::view('/po/{any?}', 'page.penjualan.po');
// });
Route::group(['prefix' => 'master', 'middleware' => 'auth'], function () {
    Route::group(['prefix' => '/produk'], function () {
        Route::view('/show', 'page.master.produk.show')->name('master.produk.show');
        Route::post('/data', [App\Http\Controllers\MasterController::class, 'get_data_master_produk']);
        Route::get('/edit_coo/{id}', [App\Http\Controllers\MasterController::class, 'edit_coo_data_produk'])->name('master.produk.edit_coo');

        Route::get('/export', [App\Http\Controllers\MasterController::class, 'export_produk'])->name('master.produk.export');
        Route::get('/cancel_po', [App\Http\Controllers\MasterController::class, 'cancel_po'])->name('master.cancel_po');
    });
});
// 'middleware' => 'auth'
Route::group(['prefix' => 'penjualan' , 'middleware' => 'auth'], function () {
    Route::group(['middleware' => ['divisi:jual,asp']], function () {
        Route::get('/dashboard', [App\Http\Controllers\PenjualanController::class, 'dashboard'])->name('penjualan.dashboard');
    });


    Route::group(['prefix' => '/produk'], function () {
        Route::group(['middleware' => ['divisi:jual']], function () {
            Route::view('/show', 'page.penjualan.produk.show')->name('penjualan.produk.show');
        });
        Route::group(['middleware' => ['divisi:jual']], function () {
            Route::view('/create', 'page.penjualan.produk.create')->name('penjualan.produk.create');
            Route::post('/store', [App\Http\Controllers\MasterController::class, 'create_penjualan_produk'])->name('penjualan.produk.store');
            Route::view('/edit', 'page.penjualan.produk.edit')->name('penjualan.produk.edit');
            Route::put('/update/{id}', [App\Http\Controllers\MasterController::class, 'update_penjualan_produk'])->name('penjualan.produk.update');
        });
    });

    Route::group(['prefix' => '/rencana', 'middleware' => ['divisi:jual']], function () {
        Route::view('/show', 'page.penjualan.rencana.show')->name('penjualan.rencana.show');
        Route::get('/result', [App\Http\Controllers\RencanaPenjualanController::class, 'show'])->name('penjualan.rencana.result');
        Route::get('/real/show/{id}', [App\Http\Controllers\RencanaPenjualanController::class, 'get_show_real'])->name('penjualan.rencana.real');
        Route::view('/create', 'page.penjualan.rencana.create')->name('penjualan.rencana.create');
        Route::put('/store',  [App\Http\Controllers\RencanaPenjualanController::class, 'create_rencana'])->name('penjualan.rencana.store');
        Route::post('/update',  [App\Http\Controllers\RencanaPenjualanController::class, 'update_rencana'])->name('penjualan.rencana.update');
        Route::get('/laporan/{customer}/{tahun}',  [App\Http\Controllers\RencanaPenjualanController::class, 'show_laporan'])->name('penjualan.rencana.laporan');
        Route::get('/laporan_detail/{customer}/{tahun}',  [App\Http\Controllers\RencanaPenjualanController::class, 'show_laporan_detail'])->name('penjualan.rencana.laporan_detail');
    });

    Route::group(['prefix' => '/pesanan'], function () {
        Route::group(['middleware' => ['divisi:jual']], function () {
            Route::get('/edit/{id}/{jenis}', [App\Http\Controllers\PenjualanController::class, 'edit_penjualan_pesanan'])->name('penjualan.pesanan.edit');
        });
    });

    Route::group(['prefix' => '/customer'], function () {
        Route::group(['middleware' => ['divisi:qc,jual,log,asp,dirut']], function () {
            Route::view('/show', 'page.penjualan.customer.show')->name('penjualan.customer.show');
            //Export
            Route::get('/export', [App\Http\Controllers\MasterController::class, 'export_customer'])->name('penjualan.customer.export');
            Route::get('/detail/{id}', [App\Http\Controllers\MasterController::class, 'detail_customer'])->name('penjualan.customer.detail');
        });

        Route::group(['middleware' => ['divisi:jual']], function () {
            // Route::get('/data/{filter}', [App\Http\Controllers\MasterController::class, 'get_data_customer']);
            Route::view('/create', 'page.penjualan.customer.create')->name('penjualan.customer.create');
            Route::post('/store', [App\Http\Controllers\MasterController::class, 'create_customer'])->name('penjualan.customer.store');
            Route::put('/update/{id}', [App\Http\Controllers\MasterController::class, 'update_customer'])->name('penjualan.customer.update');
        });
    });

    Route::group(['prefix' => '/penjualan'], function () {
        Route::group(['middleware' => ['divisi:dc,jual,asp,dirut,qc,log,ppic']], function () {
            Route::get('/detail/ekatalog/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_ekatalog'])->name('penjualan.penjualan.detail.ekatalog');
            Route::get('/detail/ekatalog_ppic/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_ekatalog_ppic']);
            Route::get('/detail/spa/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spa'])->name('penjualan.penjualan.detail.spa');
            Route::get('/detail/spb/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spb'])->name('penjualan.penjualan.detail.spb');
            Route::get('/cancel_po/{id}', [App\Http\Controllers\PenjualanController::class, 'cancel_po']);
        });

        Route::group(['middleware' => ['divisi:jual,dirut,ppic']], function () {
            Route::view('/show', 'page.penjualan.penjualan.show')->name('penjualan.penjualan.show');
            Route::post('/ekatalog/data/{value}/{tahun}', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog']);
            Route::post('/spa/data/{value}/{tahun}', [App\Http\Controllers\PenjualanController::class, 'get_data_spa']);
            Route::post('/spb/data/{value}/{tahun}', [App\Http\Controllers\PenjualanController::class, 'get_data_spb']);
        });
        Route::group(['middleware' => ['divisi:jual,ppic']], function () {
            Route::get('/export/{jenis}/{customer_id}/{tgl_awal}/{tgl_akhir}/{seri}/{tampilan}', [App\Http\Controllers\PenjualanController::class, 'export_laporan'])->name('penjualan.penjualan.export');
        });
        // Route::group(['middleware' => ['divisi:jual']], function () {
        Route::get('/cetak_surat_perintah/{id}',  [App\Http\Controllers\PenjualanController::class, 'cetak_surat_perintah'])->name('penjualan.penjualan.cetak_surat_perintah');
        Route::view('/create', 'page.penjualan.penjualan.create')->name('penjualan.penjualan.create');
        Route::view('/create_new', 'page.penjualan.penjualan.create_new')->name('penjualan.penjualan.create_new');
        // Route::get('/penjualan/data/{jenis}/{status}', [App\Http\Controllers\PenjualanController::class, 'penjualan_data'])->name('penjualan.penjualan.penjualan.data');
        Route::post('/store', [App\Http\Controllers\PenjualanController::class, 'create_penjualan'])->name('penjualan.penjualan.store');
        Route::post('/store_emindo', [App\Http\Controllers\PenjualanController::class, 'store_ekat_emindo'])->name('penjualan.penjualan.store_emindo');
        Route::post('/store_emindo_po', [App\Http\Controllers\PenjualanController::class, 'store_ekat_emindo_po'])->name('penjualan.penjualan.store_emindo_po');
        Route::post('/store_emindo_spa', [App\Http\Controllers\PenjualanController::class, 'store_spa_emindo'])->name('penjualan.penjualan.store_emindo_spa');
        Route::post('/store_do', [App\Http\Controllers\PenjualanController::class, 'update_do'])->name('penjualan.penjualan.store_emindo_spa');
        Route::get('/edit_ekatalog/{id}/{jenis}', [App\Http\Controllers\PenjualanController::class, 'update_penjualan'])->name('penjualan.penjualan.edit_ekatalog');
        Route::put('/update/ekatalog/{id}', [App\Http\Controllers\PenjualanController::class, 'update_ekatalog'])->name('penjualan.penjualan.update_ekatalog');
        Route::put('/update/spa/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spa'])->name('penjualan.penjualan.update_spa');
        Route::put('/update/spb/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spb'])->name('penjualan.penjualan.update_spb');
        Route::view('/edit_spa', 'page.penjualan.penjualan.edit_spa')->name('penjualan.penjualan.edit_spa');
        Route::view('/edit_spa', 'page.penjualan.penjualan.edit_spa')->name('penjualan.penjualan.edit_spa');
        //Export Laporan
    });
    // });

    Route::group(['prefix' => '/so', 'middleware' => ['divisi:jual']], function () {
        Route::view('/show', 'page.penjualan.so.show')->name('penjualan.so.show');
        Route::get('/create/{id}', [App\Http\Controllers\PenjualanController::class, 'view_so_ekatalog'])->name('penjualan.so.create');
        Route::put('/store/{id}', [App\Http\Controllers\PenjualanController::class, 'create_so_ekatalog'])->name('penjualan.so.ekatalog.create');
        Route::view('/edit', 'page.penjualan.so.edit')->name('penjualan.so.edit');
    });

    Route::group(['prefix' => '/lacak', 'middleware' => ['divisi:jual,gbj,qc,log,dc,asp,dirut']], function () {
        Route::view('/show', 'page.penjualan.lacak.show')->name('penjualan.lacak.show');
    });

    Route::group(['prefix' => '/laporan', 'middleware' => ['divisi:jual']], function () {
        Route::view('/show', 'page.penjualan.laporan.show')->name('penjualan.laporan.show');
    });
    // Route::get('/dashboard', 'digidocu\DocumentsController@dashboard')->name('dc.dashboard');
    // Route::get('/dep_doc/{id?}', 'digidocu\DocumentsController@dep_doc')->name('dc.dep_doc');
});

Route::group(['prefix' => 'qc', 'middleware' => 'auth'], function () {
    Route::group(['middleware' => ['divisi:qc']], function () {
        Route::get('/dashboard', [App\Http\Controllers\QcController::class, 'dashboard'])->name('qc.dashboard');
    });
    Route::group(['prefix' => '/so'], function () {
        Route::group(['middleware' => ['divisi:qc,dirut']], function () {
            Route::view('/show', 'page.qc.so.show')->name('qc.so.show');
            Route::get('/detail/{id}/{value}', [App\Http\Controllers\QcController::class, 'detail_so'])->name('qc.so.detail');
            Route::view('/detail_ekatalog/{id}', 'page.qc.so.detail_ekatalog')->name('qc.so.detail_ekatalog');
            Route::view('/detail_spa/{id}', 'page.qc.so.detail_spa')->name('qc.so.detail_spa');
            Route::view('/detail_spb/{id}', 'page.qc.so.detail_spb')->name('qc.so.detail_spb');
            Route::get('/export/{id}', [App\Http\Controllers\QcController::class, 'get_cetak_noseri_qc'])->name('qc.so.export');
            Route::group(['prefix' => '/riwayat'], function () {
                Route::view('/show', 'page.qc.so.riwayat.show')->name('qc.so.riwayat.show');
            });
        });
        Route::group(['middleware' => ['divisi:qc']], function () {
            Route::view('/create', 'page.qc.so.create')->name('qc.so.create');
            Route::get('/edit/{jenis}/{produk_id}/{pesanan_id}', [App\Http\Controllers\QcController::class, 'get_data_seri_detail_ekatalog'])->name('qc.so.edit');
            Route::group(['prefix' => '/laporan'], function () {
                Route::view('/show', 'page.qc.laporan.show')->name('qc.so.laporan.show');
                Route::get('/export/{jenis}/{produk}/{no_so}/{hasil}/{tgl_awal}/{tgl_akhir}', [App\Http\Controllers\QcController::class, 'get_cetak_laporan_qc']);
            });
            Route::get('/cancel/{id}', [App\Http\Controllers\QcController::class, 'cancel_so'])->name('qc.so.cancel_po');
        });
    });
});


Route::group(['prefix' => 'logistik', 'middleware' => 'auth'], function () {
    Route::group(['middleware' => ['divisi:log']], function () {
        Route::get('/dashboard', [App\Http\Controllers\LogistikController::class, 'dashboard'])->name('logistik.dashboard');
    });
    Route::group(['prefix' => '/so'], function () {
        // Route::group(['middleware' => ['divisi:log,dirut']], function () {
            Route::view('/show', 'page.logistik.so.show')->name('logistik.so.show');
            Route::post('/data/{value}/{years}', [App\Http\Controllers\LogistikController::class, 'get_data_so']);
            Route::get('/detail/{status}/{id}/{value}', [App\Http\Controllers\LogistikController::class, 'update_so'])->name('logistik.so.detail');
        // });
        Route::group(['middleware' => ['divisi:log']], function () {
            Route::get('/create/{jenis}', [App\Http\Controllers\LogistikController::class, 'create_logistik_view'])->name('logistik.so.create');
            Route::view('/edit', 'page.logistik.so.edit')->name('logistik.so.edit');
            Route::group(['prefix' => '/riwayat'], function () {
                Route::view('/show', 'page.logistik.so.riwayat.show')->name('logistik.so.riwayat.show');
            });
            Route::group(['prefix' => '/laporan'], function () {
                Route::view('/show', 'page.logistik.laporan.show')->name('logistik.so.laporan.show');
            });
            Route::get('/cancel/{id}', [App\Http\Controllers\LogistikController::class, 'cancel_so'])->name('logistik.so.cancel_po');
        });
    });

    Route::group(['prefix' => '/ekspedisi'], function () {
        Route::group(['middleware' => ['divisi:log,dirut']], function () {
            Route::view('/show', 'page.logistik.ekspedisi.show')->name('logistik.ekspedisi.show');
            Route::post('/data/{value_1}/{value_2}', [App\Http\Controllers\MasterController::class, 'get_data_ekspedisi']);
            Route::post('/data', [App\Http\Controllers\MasterController::class, 'get_data_ekspedisi']);
            Route::get('/detail/{id}', [App\Http\Controllers\MasterController::class, 'detail_ekspedisi'])->name('logistik.ekspedisi.detail');
        });
        Route::group(['middleware' => ['divisi:log']], function () {
            Route::view('/create', 'page.logistik.ekspedisi.create')->name('logistik.ekspedisi.create');
            Route::post('/store', [App\Http\Controllers\MasterController::class, 'create_ekspedisi'])->name('logistik.ekspedisi.store');
            Route::get('/edit/{id}', [App\Http\Controllers\MasterController::class, 'update_ekspedisi_modal'])->name('logistik.ekspedisi.edit');
            Route::put('/update/{id}', [App\Http\Controllers\MasterController::class, 'update_ekspedisi'])->name('logistik.ekspedisi.update');
        });
        //Export
        Route::get('/export', [App\Http\Controllers\MasterController::class, 'export_ekspedisi'])->name('logistik.ekspedisi.export');
    });

    Route::group(['prefix' => '/pengiriman'], function () {
        // Route::group(['middleware' => ['divisi:jual,asp,dirut,log']], function () {
        Route::view('/show', 'page.logistik.pengiriman.show')->name('logistik.pengiriman.show');
        Route::post('/data/{pengiriman}/{provinsi}/{jenis_penjualan}', [App\Http\Controllers\LogistikController::class, 'get_data_pengiriman']);
        Route::get('/detail/{id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'get_pengiriman_detail_data'])->name('logistik.pengiriman.detail');
        Route::view('/noseri/{id}', 'page.logistik.pengiriman.noseri')->name('logistik.pengiriman.noseri');
        Route::view('/create', 'page.logistik.pengiriman.create')->name('logistik.pengiriman.create');
        // Route::view('/edit/{id}', 'page.logistik.pengiriman.edit')->name('logistik.pengiriman.edit');
        Route::get('/edit/{id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'update_modal_surat_jalan'])->name('logistik.pengiriman.edit');
        Route::get('/print/{id}', [App\Http\Controllers\LogistikController::class, 'pdf_surat_jalan'])->name('logistik.pengiriman.print');
        Route::get('/prints/{id}', [App\Http\Controllers\LogistikController::class, 'cetak_surat_jalan']);
        Route::get('/edit_sj_draft/{id}', [App\Http\Controllers\LogistikController::class, 'edit_sj']);
        Route::group(['prefix' => '/riwayat'], function () {
            Route::view('/show', 'page.logistik.pengiriman.riwayat.show')->name('logistik.riwayat.show');
        });
        // });

        Route::view('/{any?}', 'page.logistik.logistik')->where('any', '.*');
    });

    Route::group(['prefix' => '/pengiriman_retur'], function () {
        Route::view('/show', 'page.logistik.pengiriman_retur.show')->name('logistik.pengiriman_retur.show');
        Route::view('/edit', 'page.logistik.pengiriman_retur.edit')->name('logistik.pengiriman_retur.edit');
        Route::post('/update', [App\Http\Controllers\LogistikController::class, 'update_pengiriman_retur'])->name('logistik.pengiriman_retur.update');
    });

    Route::group(['prefix' => '/laporan', 'middleware' => ['divisi:log']], function () {
        Route::view('/show', 'page.logistik.laporan.show')->name('logistik.laporan.show');
        Route::get('/export/{jenis}/{ekspedisi}/{tgl_awal}/{tgl_akhir}', [App\Http\Controllers\LogistikController::class, 'export_laporan'])->name('logistik.laporan.export');
    });
});

Route::middleware('auth')->prefix('/teknik')->group(function () {
    Route::group(['prefix' => '/bom'], function () {
        Route::view('/show', 'page.teknik.bom.show')->name('teknik.bom.show');
        Route::get('/detail/{id}',  [App\Http\Controllers\TeknikController::class, 'bom_detail'])->name('teknik.bom.detail');
        Route::get('/data/produk/{id}',  [App\Http\Controllers\TeknikController::class, 'bom_data_produk'])->name('teknik.bom.data.produk');
    });
});

Route::group(['prefix' => 'direksi', 'middleware' => 'auth'], function () {
    Route::get('/dashboard', [App\Http\Controllers\DireksiController::class, 'dashboard'])->name('direksi.dashboard');
});

Route::group(['prefix' => 'dc', 'middleware' => 'auth'], function () {
    Route::group(['middleware' => ['divisi:dc']], function () {
        Route::get('/dashboard',   [App\Http\Controllers\DcController::class, 'dashboard'])->name('dc.dashboard');

        Route::group(['prefix' => '/so_in_process'], function () {
            Route::view('/show', 'page.dc.so.in_proses.show')->name('dc.so.in_proses.show');
        });

        Route::group(['prefix' => '/so'], function () {
            Route::get('/cancel/{id}',  [App\Http\Controllers\DcController::class, 'cancel_so_view'])->name('dc.so.cancel_view');
            Route::view('/show', 'page.dc.so.show')->name('dc.so.show');
            Route::get('/detail/{id}/{value}',  [App\Http\Controllers\DcController::class, 'detail_coo'])->name('dc.so.detail');
            Route::view('/create/{id}', 'page.dc.so.create')->name('dc.so.create');
            Route::group(['prefix' => '/laporan'], function () {
                Route::view('/show', 'page.dc.laporan.show')->name('dc.so.laporan.show');
            });
        });
    });

    Route::group(['prefix' => '/coo'], function () {
        Route::group(['middleware' => ['divisi:dc,dirut']], function () {
            Route::view('/show', 'page.dc.coo.show')->name('dc.coo.show');
            Route::get('/pdf/so/{id}/{value}/{jenis}/{stamp}', [App\Http\Controllers\DcController::class, 'pdf_semua_so_coo'])->name('dc.coo.semua.so.pdf');
            Route::get('/pdf/semua/{id}/{value}/{jenis}/{stamp}', [App\Http\Controllers\DcController::class, 'pdf_semua_coo'])->name('dc.coo.semua.pdf');
            Route::get('/pdf/{id}/{value}/{jenis}/{stamp}', [App\Http\Controllers\DcController::class, 'pdf_seri_coo'])->name('dc.seri.coo.pdf');
        });
        Route::group(['middleware' => ['divisi:dc']], function () {
            Route::view('/detail/{id}', 'page.dc.coo.detail')->name('dc.coo.detail');
            Route::view('/create/{id}', 'page.dc.coo.create')->name('dc.coo.create');
            Route::get('/create/{id}/{Value}', [App\Http\Controllers\DcController::class, 'create_coo'])->name('dc.coo.create');
            Route::get('/edit/{id}/{Value}', [App\Http\Controllers\DcController::class, 'edit_coo'])->name('dc.coo.edit');
            Route::get('/edit_tglkirim/{Value}', [App\Http\Controllers\DcController::class, 'edit_tglkirim_coo'])->name('dc.coo.tglkirim_edit');
            Route::group(['prefix' => '/laporan'], function () {
                Route::view('/show', 'page.dc.laporan.show')->name('dc.coo.laporan.show');
            });
        });
    });
});

Route::group(['prefix' => 'as', 'middleware' => ['auth', 'divisi:asp,log']], function () {


    Route::view('/dashboard', 'page.as.dashboard')->name('as.dashboard');

    Route::group(['prefix' => '/penjualan'], function () {
        Route::view('/show', 'page.as.penjualan.show')->name('as.penjualan.show');
    });

    Route::group(['prefix' => '/laporan'], function () {
        Route::view('/show', 'page.as.laporan.show')->name('as.penjualan.show');
    });

    Route::group(['prefix' => '/retur'], function () {
        Route::view('/show', 'page.as.retur.show')->name('as.retur.show');
        Route::get('/create', [App\Http\Controllers\AfterSalesController::class, 'create_retur'])->name('as.retur.create');
        Route::post('/store', [App\Http\Controllers\AfterSalesController::class, 'store_retur'])->name('as.retur.store');
        Route::get('/edit/{id}', [App\Http\Controllers\AfterSalesController::class, 'edit_retur'])->name('as.retur.edit');
        Route::put('/update/{id}', [App\Http\Controllers\AfterSalesController::class, 'update_retur'])->name('as.retur.update');
        Route::delete('/delete', [App\Http\Controllers\AfterSalesController::class, 'delete_retur'])->name('as.retur.delete');
    });


    Route::group(['prefix' => '/perbaikan'], function () {
        Route::view('/show', 'page.as.perbaikan.show')->name('as.perbaikan.show');
        Route::post('/data', [App\Http\Controllers\AfterSalesController::class, 'data_perbaikan'])->name('as.perbaikan.data');
        Route::view('/create', 'page.as.perbaikan.create')->name('as.perbaikan.create');
        Route::post('/store', [App\Http\Controllers\AfterSalesController::class, 'store_perbaikan'])->name('as.perbaikan.store');
        Route::post('/switch', [App\Http\Controllers\AfterSalesController::class, 'save_switch_noseri'])->name('as.perbaikan.switch');
        Route::post('/done_karantina', [App\Http\Controllers\AfterSalesController::class, 'done_karantina_noseri'])->name('as.perbaikan.done_karantina');
        Route::view('/detail', 'page.as.perbaikan.detail')->name('as.perbaikan.detail');
        Route::view('/noseri', 'page.as.perbaikan.switch')->name('as.perbaikan.noseri');
        Route::get('/edit/{id}', [App\Http\Controllers\AfterSalesController::class, 'edit_perbaikan'])->name('as.perbaikan.edit');
        Route::put('/update/{id}', [App\Http\Controllers\AfterSalesController::class, 'update_perbaikan'])->name('as.perbaikan.update');
    });

    Route::group(['prefix' => '/pengiriman'], function () {
        Route::view('/show', 'page.as.pengiriman.show')->name('as.pengiriman.show');
        Route::view('/create', 'page.as.pengiriman.create')->name('as.pengiriman.create');
        Route::post('/store', [App\Http\Controllers\AfterSalesController::class, 'store_pengiriman'])->name('as.pengiriman.store');
        Route::view('/detail', 'page.as.pengiriman.detail')->name('as.pengiriman.detail');
        Route::get('/edit/{id}', [App\Http\Controllers\AfterSalesController::class, 'edit_pengiriman'])->name('as.pengiriman.edit');
        Route::put('/update/{id}', [App\Http\Controllers\AfterSalesController::class, 'update_pengiriman'])->name('as.pengiriman.update');
    });

    Route::group(['prefix' => '/so'], function () {
        Route::get('/data', [App\Http\Controllers\AfterSalesController::class, 'get_data_so']);
        Route::get('/detail/{id}/{jenis}', [App\Http\Controllers\AfterSalesController::class, 'get_detail_so'])->name('as.so.detail');
        Route::view('/show', 'page.as.so.show')->name('as.so.show');
        Route::view('/list/{id}', 'page.as.so.list')->name('as.so.list');
    });

    // Route::group(['prefix' => '/coo'], function () {
    //     Route::view('/show', 'page.dc.coo.show')->name('dc.coo.show');
    //     Route::view('/detail/{id}', 'page.dc.coo.detail')->name('dc.coo.detail');
    //     Route::view('/create/{id}', 'page.dc.coo.create')->name('dc.coo.create');
    //     Route::view('/edit/{id}', 'page.dc.coo.edit')->name('dc.coo.edit');
    //     Route::get('/pdf', [App\Http\Controllers\DcController::class, 'pdf_coo'])->name('dc.coo.pdf');
    //     Route::group(['prefix' => '/laporan'], function () {
    //         Route::view('/show', 'page.dc.laporan.show')->name('dc.coo.laporan.show');
    //     });
    // });
});

Route::group(['prefix' => 'mtc', 'middleware' => ['auth', 'divisi:mtc,eng']], function () {
    Route::group(['prefix' => '/air'], function () {
        Route::view('/masuk', 'page.maintenance.air.masuk');
        // Route::get('/masuk', [App\Http\Controllers\MaintenanceController::class, 'show_air_masuk'],)->name('mtc.air.masuk');
        // Route::get('/keluar', [App\Http\Controllers\MaintenanceController::class, 'show_air_keluar'])->name('mtc.air.keluar');
    });

    Route::group(['prefix' => '/listrik'], function () {
        Route::group(['prefix' => '/monitoring'], function () {
            Route::get('/table', [App\Http\Controllers\MaintenanceController::class, 'show_listrik_monitoring_table'])->name('mtc.listrik.monitoring_table');
            Route::get('/grafik', [App\Http\Controllers\MaintenanceController::class, 'show_listrik_monitoring_grafik'])->name('mtc.listrik.monitoring_grafik');
        });
        Route::get('/panel', [App\Http\Controllers\MaintenanceController::class, 'show_listrik_panel'])->name('mtc.listrik.panel');
    });
});

Route::group(['prefix' => '/gk', 'middleware' => ['auth', 'divisi:gk,dirut']], function () {
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

Route::get('/testing/pbj', [ProduksiController::class, 'cetakTest']);

Route::view('/uit', 'page.login_page.index');

Route::view('/hr/{any?}', 'page.hr.index')->where('any', '.*');
Route::view('/meeting/{any?}', 'page.meeting.index')->where('any', '.*');

Route::namespace('v2')->group(__DIR__ . '/kesehatan/kesehatan.php');
Route::namespace('lab')->group(__DIR__ . '/inventory/web.php');
