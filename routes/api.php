<?php

use App\Http\Controllers\GudangController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\PpicController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\SparepartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/master')->group(function () {
    Route::post('/produk/no_akd', [App\Http\Controllers\MasterController::class, 'check_no_akd']);
    Route::put('/produk/update_coo/{id}', [App\Http\Controllers\MasterController::class, 'update_coo_master_produk'])->name('master.produk.update_coo');
});

Route::prefix('/ppic')->group(function () {
    Route::post('/master_stok/data', [App\Http\Controllers\PpicController::class, 'get_master_stok_data']);
    Route::post('/master_stok/detail/{id}', [App\Http\Controllers\PpicController::class, 'get_detail_master_stok']);
    Route::post('/master_pengiriman/data', [App\Http\Controllers\PpicController::class, 'get_master_pengiriman_data']);
    Route::post('/master_pengiriman/detail/{id}', [App\Http\Controllers\PpicController::class, 'get_detail_master_pengiriman']);
    Route::get('/data/perakitan/{status?}', [App\Http\Controllers\PpicController::class, 'get_data_perakitan']);
    Route::get('/datatables/perakitan', [App\Http\Controllers\PpicController::class, 'get_datatables_data_perakitan']);
    Route::get('/data/rencana_perakitan', [App\Http\Controllers\PpicController::class, 'get_data_perakitan_rencana']);
    Route::get('/data/gbj', [App\Http\Controllers\PpicController::class, 'get_data_barang_jadi']);
    Route::get('/data/so', [App\Http\Controllers\PpicController::class, 'get_data_so']);
    Route::get('/data/so/detail/{id}', [App\Http\Controllers\PpicController::class, 'get_data_so_detail']);
    Route::get('/data/master_pengiriman_for_ppic/detail/{id}', [App\Http\Controllers\PpicController::class, 'get_detail_pengiriman_for_ppic']);
    Route::get('/data/gk/sparepart', [App\Http\Controllers\PpicController::class, 'get_data_sparepart_gk']);
    Route::get('/data/gk/unit', [App\Http\Controllers\PpicController::class, 'get_data_unit_gk']);
    Route::get('/data/komentar', [App\Http\Controllers\PpicController::class, 'get_komentar_jadwal_perakitan']);
    Route::post('/create/perakitan', [App\Http\Controllers\PpicController::class, 'create_data_perakitan']);
    Route::post('/update/perakitan/{id}', [App\Http\Controllers\PpicController::class, 'update_data_perakitan']);
    Route::post('/update/perakitans/{status}', [App\Http\Controllers\PpicController::class, 'update_many_data_perakitan']);
    Route::post('/delete/perakitan/{id}', [App\Http\Controllers\PpicController::class, 'delete_data_perakitan']);
    Route::get('/counting/status/perakitan', [App\Http\Controllers\PpicController::class, 'counting_status_data_perakitan']);
    Route::get('/counting/komentar', [App\Http\Controllers\PpicController::class, 'count_proses_jadwal']);
    Route::post('/create/komentar', [App\Http\Controllers\PpicController::class, 'create_komentar_jadwal_perakitan']);
    Route::post('/update/komentar', [App\Http\Controllers\PpicController::class, 'update_komentar_jadwal_perakitan']);
    Route::post('/send_notification', [App\Http\Controllers\PpicController::class, 'send_notification']);
    Route::get('/data/produk_so/{id}/{value}', [PpicController::class, 'get_data_pesanan_produk']);
    Route::get('/test/query', [App\Http\Controllers\PpicController::class, 'test_query']);
});

Route::prefix('/provinsi')->group(function () {
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_provinsi']);
    Route::get('select_edit', [App\Http\Controllers\MasterController::class, 'select_provinsi_edit']);
});
Route::prefix('/kota_kabupaten')->group(function () {
    Route::get('select', [App\Http\Controllers\ProvincesController::class, 'kota_kabupaten']);
});
Route::prefix('/customer')->group(function () {
    Route::post('data/{divisi_id}/{filter}', [App\Http\Controllers\MasterController::class, 'get_data_customer']);
    Route::get('nama/{id}/{val}', [App\Http\Controllers\MasterController::class, 'get_nama_customer']);
    Route::post('detail/{id}', [App\Http\Controllers\MasterController::class, 'get_data_pesanan']);
    // Route::post('create', [App\Http\Controllers\MasterController::class, 'create_customer']);
    Route::get('update_modal/{id}', [App\Http\Controllers\MasterController::class, 'update_customer_modal']);
    //Route::put('update/{id}', [App\Http\Controllers\MasterController::class, 'update_customer']);
    Route::delete('delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_customer']);
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_customer']);;
    Route::get('select_rencana', [App\Http\Controllers\MasterController::class, 'select_customer_rencana']);;

    Route::get('select/{id}', [App\Http\Controllers\MasterController::class, 'select_customer_id']);;
    Route::get('check/{id}', [App\Http\Controllers\MasterController::class, 'check_customer']);
});

Route::prefix('/produk')->group(function () {
    Route::get('data', [App\Http\Controllers\MasterController::class, 'get_data_produk']);
    Route::post('create', [App\Http\Controllers\MasterController::class, 'create_produk']);
    Route::post('update', [App\Http\Controllers\MasterController::class, 'update_produk']);
    Route::delete('delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_produk']);
    Route::get('check/{id}', [App\Http\Controllers\MasterController::class, 'check_produk']);
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_produk']);
    Route::get('select/{id}', [App\Http\Controllers\MasterController::class, 'select_produk_id']);
});
Route::prefix('/penjualan_produk')->group(function () {
    Route::post('data/{value}/{min}/{max}', [App\Http\Controllers\MasterController::class, 'get_data_penjualan_produk']);
    // Route::post('create', [App\Http\Controllers\MasterController::class, 'create_penjualan_produk']);
    // Route::post('update/{id}', [App\Http\Controllers\MasterController::class, 'update_penjualan_produk']);
    Route::delete('delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_penjualan_produk']);
    Route::post('detail/{id}', [App\Http\Controllers\MasterController::class, 'get_data_detail_penjualan_produk']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\MasterController::class, 'delete_detail_penjualan_produk']);
    Route::get('update_modal/{id}', [App\Http\Controllers\MasterController::class, 'update_penjualan_produk_modal']);
    Route::get('check/{id}/{val}', [App\Http\Controllers\MasterController::class, 'check_penjualan_produk']);
    Route::get('select', [App\Http\Controllers\MasterController::class, 'select_penjualan_produk']);
    Route::get('select/{id}', [App\Http\Controllers\MasterController::class, 'select_penjualan_produk_id']);
});

Route::prefix('/penjualan')->group(function () {
    // Route::post('create', [App\Http\Controllers\PenjualanController::class, 'create_penjualan']);
    Route::post('/penjualan/data/{jenis}/{status}', [App\Http\Controllers\PenjualanController::class, 'penjualan_data']);
    Route::get('chart', [App\Http\Controllers\PenjualanController::class, 'chart_penjualan']);
    // Route::post('data', [App\Http\Controllers\PenjualanController::class, 'penjualan_data']);
    Route::post('check_no_paket/{id}/{val}', [App\Http\Controllers\PenjualanController::class, 'check_no_paket']);
    //   Route::get('customer/data/{filter}', [App\Http\Controllers\MasterController::class, 'get_data_customer']);
    Route::prefix('/pesanan')->group(function () {
        Route::put('update/{id}/{jenis}', [App\Http\Controllers\PenjualanController::class, 'update_penjualan_pesanan']);
        Route::post('produk/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_paket_pesanan_ekat']);
    });
    Route::prefix('/lacak')->group(function () {
        Route::post('data/{parameter}/{value}', [App\Http\Controllers\PenjualanController::class, 'get_lacak_penjualan']);
    });

    Route::group(['prefix' => '/rencana'], function () {
        Route::get('select_tahun', [App\Http\Controllers\RencanaPenjualanController::class, 'select_tahun_rencana']);
        Route::post('show/{customer}/{tahun}', [App\Http\Controllers\RencanaPenjualanController::class, 'get_data_rencana']);
    });
});

Route::prefix('/so')->group(function () {
    Route::post('data', [App\Http\Controllers\PenjualanController::class, 'get_data_so']);
    Route::post('/cek', [App\Http\Controllers\GudangController::class, 'storeCekSO']);
});
Route::prefix('/laporan')->group(function () {
    Route::post('/create', [App\Http\Controllers\PenjualanController::class, 'laporan']);
    Route::post('/penjualan/{penjualan}/{distributor}/{tanggal_awal}/{tanggal_akhir}', [App\Http\Controllers\PenjualanController::class, 'get_data_laporan_penjualan']);
    Route::post('/qc/{jenis}/{produk}/{no_so}/{hasil}/{tgl_awal}/{tgl_akhir}', [App\Http\Controllers\QcController::class, 'get_data_laporan_qc']);
    Route::post('/logistik/{pengiriman}/{ekspedisi}/{tgl_awal}/{tgl_akhir}', [App\Http\Controllers\LogistikController::class, 'get_data_laporan_logistik']);
});

Route::prefix('/gbj')->group(function () {
    Route::post('data', [App\Http\Controllers\GudangController::class, 'get_data_barang_jadi']);
    Route::post('/create', [App\Http\Controllers\GudangController::class, 'StoreBarangJadi']);
    Route::post('/edit/{id}', [App\Http\Controllers\GudangController::class, 'UpdateBarangJadi']);
    Route::delete('/delete/{id}', [App\Http\Controllers\GudangController::class, 'DestroyBarangJadi']);
    Route::post('/get', [App\Http\Controllers\GudangController::class, 'GetBarangJadiByID']);
    Route::post('data-so', [GudangController::class, 'getSODone']);

    Route::get('/test', [App\Http\Controllers\GudangController::class, 'test']);

    // select
    Route::get('sel-product', [GudangController::class, 'select_product']);
    Route::get('sel-product/{id}', [GudangController::class, 'select_product_by_id']);
    Route::get('sel-satuan', [GudangController::class, 'select_satuan']);
    Route::get('sel-layout', [GudangController::class, 'select_layout']);
    Route::get('sel-divisi', [GudangController::class, 'select_divisi']);
    Route::get('sel-gbj', [GudangController::class, 'select_gbj']);

    // so
    Route::post('/createNon', [App\Http\Controllers\GudangController::class, 'tanpaSo']);

    // noseri
    Route::get('noseri/{id}', [GudangController::class, 'getNoseri']);
    Route::get('history/{id}', [GudangController::class, 'getHistory']);
    Route::post('noseri/{id}', [GudangController::class, 'storeNoseri']);
    Route::post('ceknoseri', [GudangController::class, 'ceknoseri']);
    Route::post('ubahseri', [GudangController::class, 'updateSeriLayout']);
    Route::post('addSeri', [GudangController::class, 'addSeri']);
    Route::post('readyseri', [GudangController::class, 'cekReadySeri']);
});

Route::prefix('/draft')->group(function () {
    Route::post('/rancang', [GudangController::class, 'storeDraftRancang']);
    Route::post('/final', [GudangController::class, 'storeFinalRancang']);

    // get
    Route::post('/data', [GudangController::class, 'getDraftPerakitan']);
    Route::post('/data-seri', [GudangController::class, 'getNoseriDraftRakit']);
});

Route::prefix('/transaksi')->group(function () {
    Route::get('/all', [GudangController::class, 'getAllTransaksi']);
    Route::get('/history', [GudangController::class, 'getHistorybyProduk']);
    Route::get('/history-detail/{id}', [GudangController::class, 'getDetailHistory']);
    Route::get('/history-detail-seri/{id}', [GudangController::class, 'getDetailHistorySeri']);
    Route::get('/all-detail/{id}', [GudangController::class, 'getDetailAll']);
});

Route::prefix('/dashboard-gbj')->group(function () {
    Route::get('/noseri/{id}', [GudangController::class, 'getNoseriTerima']);
    // produk
    Route::get('/stok/1020/h', [GudangController::class, 'h1']);
    Route::get('/stok/59/h', [GudangController::class, 'h2']);
    Route::get('/stok/14/h', [GudangController::class, 'h3']);
    Route::get('/stok/1020', [GudangController::class, 'getProdukstok1020']);
    Route::get('/stok/59', [GudangController::class, 'getProdukstok59']);
    Route::get('/stok/14', [GudangController::class, 'getProdukstok14']);

    Route::post('/in/36/h', [GudangController::class, 'h4']);
    Route::post('/in/612/h', [GudangController::class, 'h5']);
    Route::post('/in/1236/h', [GudangController::class, 'h6']);
    Route::post('/in/36plus/h', [GudangController::class, 'h7']);
    Route::post('/in/36', [GudangController::class, 'getProdukIn36']);
    Route::post('/in/612', [GudangController::class, 'getProdukIn612']);
    Route::post('/in/1236', [GudangController::class, 'getProduk1236']);
    Route::post('/in/36plus', [GudangController::class, 'getProduk36Plus']);
    Route::get('/byproduct', [GudangController::class, 'getProdukByLayout']);
    Route::get('/test', [GudangController::class, 'test']);

    // terima
    Route::post('/terimaproduk1/h', [GudangController::class, 'hh1']);
    Route::post('/terimaproduk2/h', [GudangController::class, 'hh2']);
    Route::post('/terimaproduk3/h', [GudangController::class, 'hh3']);
    Route::post('/terimaproduk1', [GudangController::class, 'getPenerimaanProduk1']);
    Route::post('/terimaproduk2', [GudangController::class, 'getPenerimaanProduk2']);
    Route::post('/terimaproduk3', [GudangController::class, 'getPenerimaanProduk3']);
    Route::post('/terimaall', [GudangController::class, 'getPenerimaanAll']);

    // penjualan
    Route::post('/list1/h', [GudangController::class, 'he1']);
    Route::post('/list2/h', [GudangController::class, 'he2']);
    Route::post('/list/h', [GudangController::class, 'he3']);
    Route::post('/list1', [GudangController::class, 'list_tf1']);
    Route::post('/list2', [GudangController::class, 'list_tf2']);
    Route::post('/list', [GudangController::class, 'list_tf3']);
    Route::post('/list-all', [GudangController::class, 'outSO']);
    Route::get('/list-detail/{id}/{value}', [GudangController::class, 'detailsale']);
});

Route::prefix('/tfp')->group(function () {
    Route::post('/create', [ProduksiController::class, 'CreateTFItem']);
    Route::post('/byso', [ProduksiController::class, 'TfbySO']);
    Route::post('/byso-final', [ProduksiController::class, 'TfbySOFinal']);
    Route::post('/create-noseri', [GudangController::class, 'storeNoseri']);
    Route::post('/create-final', [GudangController::class, 'finalDraftRakit']);

    Route::post('/updateRancangSO', [ProduksiController::class, 'updateRancangSO']);
    Route::post('/updateFinalSO', [ProduksiController::class, 'updateFinalSO']);

    // get
    Route::get('data', [ProduksiController::class, 'getTFnon']);
    Route::get('noseri/{id}', [ProduksiController::class, 'getNoseri']);
    Route::get('data-so', [ProduksiController::class, 'getOutSO']);
    Route::get('cek-so', [ProduksiController::class, 'getSOCek']);
    Route::get('detail-so/{id}/{value}', [ProduksiController::class, 'getDetailSO']);
    Route::get('edit-so/{id}/{value}', [ProduksiController::class, 'getEditSO']);
    Route::get('header-so/{id}/{value}', [ProduksiController::class, 'headerSo']);
    Route::get('rakit', [GudangController::class, 'getRakit']);
    Route::get('rakit-noseri/{id}/{value}', [GudangController::class, 'getRakitNoseri']);
    Route::get('rakit-terima/{id}/{value}', [GudangController::class, 'getTerimaRakit']);
    Route::post('/seri-so', [ProduksiController::class, 'getNoseriSO']);
    Route::post('/seri-edit-so', [ProduksiController::class, 'getNoseriSOEdit']);
    Route::post('/closeRakit', [ProduksiController::class, 'closeRakit']);
    Route::post('/closeTransfer', [ProduksiController::class, 'closeTransfer']);

    // check
    Route::post('/cekStok', [ProduksiController::class, 'checkStok']);
    Route::post('/updateCheck', [ProduksiController::class, 'UncheckedNoseri']);
    Route::post('/updateChecked', [ProduksiController::class, 'checkedNoseri']);
});

Route::prefix('/prd')->group(function () {
    Route::get('/dashboard', [ProduksiController::class, 'dashboard']);
    Route::get('/allproduk', [ProduksiController::class, 'getAllProduk']);
    Route::post('/grafikproduk', [ProduksiController::class, 'getGrafikProduk']);

    Route::post('/minus10/h', [ProduksiController::class, 'h_minus10']);
    Route::post('/minus5/h', [ProduksiController::class, 'h_minus5']);
    Route::post('/exp/h', [ProduksiController::class, 'h_exp']);

    Route::post('/minus5', [ProduksiController::class, 'minus5']);
    Route::post('/minus10', [ProduksiController::class, 'minus10']);
    Route::post('/exp', [ProduksiController::class, 'expired']);

    Route::post('/exp_rakit/h', [ProduksiController::class, 'exp_rakit_h']);
    Route::post('/exp_rakit', [ProduksiController::class, 'exp_rakit']);

    Route::post('/exp_jadwal/h', [ProduksiController::class, 'exp_jadwal_h']);
    Route::post('/exp_jadwal', [ProduksiController::class, 'exp_jadwal']);

    // so
    Route::post('/so', [ProduksiController::class, 'getSOProduksi']);

    // jadwal
    // plan
    Route::post('/plan', [ProduksiController::class, 'plan_rakit']);
    Route::post('/plan-cal', [ProduksiController::class, 'calender_plan']);

    // on
    Route::post('/ongoing', [ProduksiController::class, 'on_rakit']);
    Route::post('/ongoing-cal', [ProduksiController::class, 'calender_current']);
    Route::get('/ongoing/h/{id}', [ProduksiController::class, 'detailRakitHeader']);
    Route::get('/ajax_his_rakit', [ProduksiController::class, 'ajax_history_rakit']);
    Route::get('/ajax_perproduk', [ProduksiController::class, 'ajax_perproduk']);
    Route::get('/detail_perproduk/{id}', [ProduksiController::class, 'detail_perproduk']);
    Route::get('/product_his_rakit', [ProduksiController::class, 'product_his_rakit']);
    Route::post('/rakit-seri', [ProduksiController::class, 'storeRakitNoseri']);
    Route::post('cek-noseri', [ProduksiController::class, 'cekDuplicateNoseri']);
    Route::post('/ajax_sisa', [ProduksiController::class, 'ajax_sisa_transfer']);
    Route::post('/detail_sisa_kirim', [ProduksiController::class, 'detail_sisa_kirim']);

    Route::get('/testing', [ProduksiController::class, 'change_jadwal']);

    // kirim
    Route::get('/kirim', [ProduksiController::class, 'getSelesaiRakit']);
    Route::get('/headerSeri/{id}', [ProduksiController::class, 'getHeaderSeri']);
    Route::get('/historySeri/{id}/{value}/{value2}', [ProduksiController::class, 'historySeri']);
    Route::get('/detailSeri1/{id}/{value}', [ProduksiController::class, 'detailSeri1']);
    Route::post('/send', [ProduksiController::class, 'kirimseri']);
    Route::post('/terimaseri', [ProduksiController::class, 'terimaseri']);
    Route::post('/delete', [ProduksiController::class, 'deleteNoseri']);
    Route::post('/deleteAll', [ProduksiController::class, 'deleteAllSeri']);
    Route::post('/updateRakitseri', [ProduksiController::class, 'updateNoseri']);
    Route::post('/cekUpdateNoseri', [ProduksiController::class, 'cekUbahNoseri']);

    // riwayat
    Route::prefix('/history')->group(function () {
        Route::post('/rakit/h', [ProduksiController::class, 'h_rakit']);
        Route::post('/unit/h', [ProduksiController::class, 'h_unit']);
        Route::get('/header/{id}', [ProduksiController::class, 'header_his_rakit']);
    });
});

Route::prefix('/spr')->group(function () {
    Route::get('/data', [App\Http\Controllers\SparepartController::class, 'get']);
    Route::post('/create', [App\Http\Controllers\SparepartController::class, 'store']);
    Route::post('/edit/{id}', [App\Http\Controllers\SparepartController::class, 'update']);
    Route::delete('/delete/{id}', [App\Http\Controllers\SparepartController::class, 'delete']);
    Route::get('/data/{id}', [App\Http\Controllers\SparepartController::class, 'getId']);
});
Route::prefix('/gk')->group(function () {
    Route::get('/his-spr/{id}', [SparepartController::class, 'history_spr']);
    Route::get('/unit', [SparepartController::class, 'get_unit']);
    Route::get('/his-unit/{id}', [SparepartController::class, 'history_unit']);

    Route::post('/draft-tf', [SparepartController::class, 'get_draft_tf']);
    Route::post('/draft-terima', [SparepartController::class, 'get_draft_terima']);
    Route::get('/noseri/{id}', [SparepartController::class, 'headerSeri']);
    Route::get('/coba', [SparepartController::class, 'testing']);
    Route::post('/detailseri', [SparepartController::class, 'get_detail_id1']);

    // cek
    Route::post('/cekseri', [SparepartController::class, 'cekNoseriTerima']);
    Route::post('/getseri/spr', [SparepartController::class, 'getSeriDoneSpr']);
    Route::post('/getseri/unit', [SparepartController::class, 'getSeriDoneUnit']);
    Route::post('/header/spr', [SparepartController::class, 'headerNoseriSpr']);

    // select
    Route::post('/sel-m-jasa', [MasterController::class, 'select_m_jasa']);
    Route::post('/sel-m-spare', [MasterController::class, 'select_m_sparepart']);
    Route::post('/sel-spare', [MasterController::class, 'select_sparepart']);
    Route::post('/gkspr', [MasterController::class, 'select_gk_spr']);
    Route::post('/gkunit', [MasterController::class, 'select_gk_unit']);
    Route::post('/gklayout', [MasterController::class, 'select_gk_layout']);
    Route::get('/sel-tahun', [SparepartController::class, 'get_trx_tahun']);

    // store
    Route::post('/out-draft', [SparepartController::class, 'transfer_by_draft']);
    Route::post('/out-final', [SparepartController::class, 'transfer_by_final']);

    Route::post('/in-draft', [SparepartController::class, 'terima_by_draft']);
    Route::post('/in-final', [SparepartController::class, 'terima_by_final']);

    Route::post('/ubahunit', [SparepartController::class, 'updateUnit']);
    Route::post('/edit-in', [SparepartController::class, 'edit_draft_terima']);
    Route::post('/edit-out', [SparepartController::class, 'edit_draft_transfer']);

    Route::post('/updateDraft', [SparepartController::class, 'updateTerima']);
    Route::post('/updateFinal', [SparepartController::class, 'updateTerimaFinal']);
    Route::post('/deleteDraftTerima', [SparepartController::class, 'deleteDraftTerima']);
    Route::post('/deleteDraftTransfer', [SparepartController::class, 'deleteDraftTransfer']);
    Route::post('/editseri', [SparepartController::class, 'getNoseriEdit']);
    Route::post('/editseriunit', [SparepartController::class, 'getNoseriEditUnit']);
    Route::post('/editseri-out', [SparepartController::class, 'getOutSeriEdit']);
    Route::post('/editseriunit-out', [SparepartController::class, 'getOutSeriEditUnit']);

    Route::post('/uncheck', [SparepartController::class, 'uncheckNoseri']);
    Route::post('/check', [SparepartController::class, 'checkNoseri']);

    // edit transfer
    Route::post('/updateTransfer', [SparepartController::class, 'updateTransfer']);
    Route::post('/updateTransferDraft', [SparepartController::class, 'updateTransferDraft']);

    Route::prefix('/terima')->group(function () {
        Route::get('/all', [SparepartController::class, 'getProdukgudang']);
        Route::get('/detail-seri/{id}', [SparepartController::class, 'getSeriProduk']);
        Route::get('/terima-seri/{id}', [SparepartController::class, 'getSeriRakit']);
        Route::post('/submit', [SparepartController::class, 'terimaSeri']);
    });

    // history trx
    Route::prefix('/transaksi')->group(function () {
        Route::post('/by-product', [SparepartController::class, 'history_by_produk']);
        Route::post('/all', [SparepartController::class, 'historyAll']);
        Route::get('/noseri/{id}', [SparepartController::class, 'get_noseri_history']);
        Route::get('/header/{id}', [App\Http\Controllers\SparepartController::class, 'get_detail_id']);
        Route::get('/history/{id}', [App\Http\Controllers\SparepartController::class, 'get_trx']);
        Route::post('/grafik-trf', [SparepartController::class, 'grafik_trf']);
    });

    Route::prefix('/dashboard')->group(function () {
        Route::post('/tingkat', [SparepartController::class, 'byTingkat']);
        Route::post('/layout', [SparepartController::class, 'byLayout']);

        // stok
        Route::post('/stok/34', [SparepartController::class, 'stok34']);
        Route::post('/stok/510', [SparepartController::class, 'stok510']);
        Route::post('/stok/10', [SparepartController::class, 'stok10plus']);

        Route::post('/stok/34/h', [SparepartController::class, 'h_stok34']);
        Route::post('/stok/510/h', [SparepartController::class, 'h_stok510']);
        Route::post('/stok/10/h', [SparepartController::class, 'h_stok10plus']);

        // in
        Route::post('/in/36', [SparepartController::class, 'in36']);
        Route::post('/in/612', [SparepartController::class, 'in612']);
        Route::post('/in/1236', [SparepartController::class, 'in1236']);
        Route::post('/in/36plus', [SparepartController::class, 'in36plus']);

        Route::post('/in/36/h', [SparepartController::class, 'h_in36']);
        Route::post('/in/612/h', [SparepartController::class, 'h_in612']);
        Route::post('/in/1236/h', [SparepartController::class, 'h_in1236']);
        Route::post('/in/36plus/h', [SparepartController::class, 'h_in36plus']);
    });
});

Route::prefix('/noseri')->group(function () {
    Route::post('/edit/{id}', [App\Http\Controllers\NoseriController::class, 'UpdateNoSeri']);
    Route::delete('/delete/{id}', [App\Http\Controllers\NoseriController::class, 'DestroyNoSeri']);
});
Route::prefix('/produk')->group(function () {
    Route::post('/variasi_stok/{id}', [App\Http\Controllers\PenjualanController::class, 'check_variasi_jumlah']);
});

Route::prefix('/ekatalog')->group(function () {
    // Route::get('data/{value}', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog']);
    Route::post('pengiriman/data', [App\Http\Controllers\PenjualanController::class, 'get_data_ekatalog_pengiriman']);

    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_ekatalog']);
    Route::get('detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_ekatalog']);
    Route::post('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_ekatalog']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_ekatalog']);
    Route::delete('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_ekatalog']);
});

Route::prefix('/spa')->group(function () {
    // Route::get('data', [App\Http\Controllers\PenjualanController::class, 'get_data_spa']);
    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spa']);
    Route::get('detail/{$id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spa']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_spa']);
    Route::delete('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_spa']);
    Route::post('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_spa']);
});

Route::prefix('/spb')->group(function () {
    // Route::get('data', [App\Http\Controllers\PenjualanController::class, 'get_data_spb']);

    Route::get('detail/{$id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_spb']);
    Route::get('detail/delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_detail_spb']);
    Route::delete('delete/{id}', [App\Http\Controllers\PenjualanController::class, 'delete_spb']);
    Route::post('paket/detail/{id}', [App\Http\Controllers\PenjualanController::class, 'get_data_detail_paket_spb']);
    // Route::post('update/{id}', [App\Http\Controllers\PenjualanController::class, 'update_spb']);
});

Route::prefix('/qc')->group(function () {
    Route::post('dashboard/data/{value}', [App\Http\Controllers\QcController::class, 'dashboard_data']);
    Route::post('dashboard/so', [App\Http\Controllers\QcController::class, 'dashboard_so']);
    Route::prefix('/so')->group(function () {
        Route::put('create/{jenis}/{pesanan_id}/{produk_id}', [App\Http\Controllers\QcController::class, 'create_data_qc']);
        Route::post('data/{value}', [App\Http\Controllers\QcController::class, 'get_data_so']);
        Route::post('data/selesai/{value}', [App\Http\Controllers\QcController::class, 'get_data_selesai_so']);
        Route::post('seri/{status}/{value}/{idpesanan}', [App\Http\Controllers\QcController::class, 'get_data_seri_ekatalog']);
        Route::post('part/{value}', [App\Http\Controllers\QcController::class, 'get_data_part_cek']);
        Route::post('seri/select/{seri_id}/{produk_id}/{pesanan_id}', [App\Http\Controllers\QcController::class, 'get_data_select_seri']);
        Route::get('data_test', [App\Http\Controllers\QcController::class, 'get_data_so_qc']);
        Route::post('detail/{id}', [App\Http\Controllers\QcController::class, 'get_data_detail_so']);
        Route::get('update_modal', [App\Http\Controllers\QcController::class, 'update_modal_so']);
        Route::post('cek/part/{id}', [App\Http\Controllers\QcController::class, 'get_jumlah_cek_part']);
        Route::prefix('/riwayat')->group(function () {
            Route::get('detail_modal/{id}/{jenis}', [App\Http\Controllers\QcController::class, 'detail_modal_riwayat_so']);
            Route::post('data', [App\Http\Controllers\QcController::class, 'get_data_riwayat_pengujian']);
            Route::get('select/{id}', [App\Http\Controllers\QcController::class, 'getProdukPesananSelect']);
            Route::post('detail/{id}/{jenis}', [App\Http\Controllers\QcController::class, 'get_data_detail_riwayat_pengujian']);
        });
        Route::prefix('/laporan')->group(function () {
            Route::post('/create', [App\Http\Controllers\QcController::class, 'laporan_outgoing']);
        });
    });
});

Route::prefix('/logistik')->group(function () {
    Route::post('dashboard/data/{value}', [App\Http\Controllers\LogistikController::class, 'dashboard_data']);
    Route::post('dashboard/so', [App\Http\Controllers\LogistikController::class, 'dashboard_so']);
    Route::group(['prefix' => '/so'], function () {
        Route::put('create/{detail_pesanan_id}/{part_array}/{jenis}', [App\Http\Controllers\LogistikController::class, 'create_logistik']);
        // Route::get('data', [App\Http\Controllers\LogistikController::class, 'get_data_so']);
        Route::post('noseri/detail/{id}', [App\Http\Controllers\LogistikController::class, 'get_noseri_so']);
        Route::post('noseri/detail/belum_kirim/{id}', [App\Http\Controllers\LogistikController::class, 'get_noseri_so_belum_kirim']);
        Route::get('noseri/detail/selesai_kirim/{id}', [App\Http\Controllers\LogistikController::class, 'get_noseri_so_selesai_kirim']);
        Route::post('noseri/detail/selesai_kirim/data/{id}', [App\Http\Controllers\LogistikController::class, 'get_noseri_so_selesai_kirim_data']);
        // Route::get('data/detail/{id}', [App\Http\Controllers\LogistikController::class, 'get_data_detail_so']);
        Route::post('data/detail/belum_kirim/{id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'get_data_detail_belum_kirim_so']);
        Route::post('data/detail/selesai_kirim/{id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'get_data_detail_selesai_kirim_so']);
        Route::get('detail/select/{produk_id}/{part_id}/{pesanan_id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'get_data_select_produk']);
        Route::get('data/selesai', [App\Http\Controllers\LogistikController::class, 'get_data_selesai_so']);

        Route::post('data/sj/{id}', [App\Http\Controllers\LogistikController::class, 'get_data_pesanan_sj']);
    });
    Route::group(['prefix' => '/ekspedisi'], function () {
        // Route::get('/data', [App\Http\Controllers\MasterController::class, 'get_data_ekspedisi']);
        Route::get('select/{provinsi}', [App\Http\Controllers\MasterController::class, 'select_ekspedisi']);
        Route::post('detail/{id}', [App\Http\Controllers\MasterController::class, 'get_data_detail_ekspedisi']);
        // Route::post('create', [App\Http\Controllers\MasterController::class, 'create_ekspedisi']);
    });

    Route::group(['prefix' => '/pengiriman'], function () {
        // Route::get('/data', [App\Http\Controllers\LogistikController::class, 'get_data_pengiriman']);
        Route::post('/data/{id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'get_produk_detail_pengiriman']);
        Route::put('/update/{id}', [App\Http\Controllers\LogistikController::class, 'update_pengiriman']);
        Route::get('/status/update/{id}/{status}', [App\Http\Controllers\LogistikController::class, 'update_status_pengiriman']);
        // Route::get('/edit/{id}', [App\Http\Controllers\LogistikController::class, 'update_modal_surat_jalan'])->name('logistik.pengiriman.edit');
        // Route::get('/detail/{id}/{jenis}', [App\Http\Controllers\LogistikController::class, 'get_pengiriman_detail_datas']);
        // Route::get('data', [App\Http\Controllers\MasterController::class, 'get_data_ekspedisi']);
        // Route::post('create', [App\Http\Controllers\MasterController::class, 'create_ekspedisi']);
        Route::group(['prefix' => '/riwayat'], function () {
            Route::post('/data/{pengiriman}/{provinsi}/{jenis_penjualan}', [App\Http\Controllers\LogistikController::class, 'get_data_riwayat_pengiriman']);
        });
    });

    Route::group(['prefix' => '/cek'], function () {
        Route::post('/no_sj/{id}/{val}/{jenis}', [App\Http\Controllers\LogistikController::class, 'check_no_sj']);
        Route::post('/no_resi/{val}', [App\Http\Controllers\LogistikController::class, 'check_no_resi']);
    });
});

Route::prefix('/dc')->group(function () {
    Route::post('data', [App\Http\Controllers\DcController::class, 'get_data_coo']);
    Route::post('dashboard/data/{value}', [App\Http\Controllers\DcController::class, 'dashboard_data']);
    Route::post('dashboard/so', [App\Http\Controllers\DcController::class, 'dashboard_so']);
    Route::prefix('/so')->group(function () {
        Route::post('create/{value}', [App\Http\Controllers\DcController::class, 'create_coo']);
        Route::put('update/{value}', [App\Http\Controllers\DcController::class, 'update_coo']);
        Route::post('data/{value}', [App\Http\Controllers\DcController::class, 'get_data_so']);
        Route::post('detail/{id}', [App\Http\Controllers\DcController::class, 'get_data_detail_so']);
        Route::post('detail/seri/{id}', [App\Http\Controllers\DcController::class, 'get_data_detail_seri_so']);
        Route::post('detail/seri/select/{id}/{value}', [App\Http\Controllers\DcController::class, 'get_data_detail_select_seri_so']);
    });
});

Route::prefix('/as')->group(function () {
    Route::post('/so/data', [App\Http\Controllers\AfterSalesController::class, 'get_data_so']);
    Route::post('/so/detail/{id}/{jenis}', [App\Http\Controllers\AfterSalesController::class, 'get_detail_pengiriman']);
});

Route::group(['prefix' => 'direksi', 'middleware' => 'auth'], function () {
    Route::get('dashboard', [App\Http\Controllers\DireksiController::class, 'dashboard']);
});

Route::get('testingJson', [GudangController::class, 'dataTesting']);

Route::namespace('v2')->group(__DIR__ . '/yogi/api.php');
