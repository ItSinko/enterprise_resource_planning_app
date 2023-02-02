<?php

use App\Http\Controllers\GudangController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\SparepartController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v2')->group(function () {
    // produksi
    Route::prefix('/prd')->group(function () {
        Route::get('/produk-so', [ProduksiController::class, 'getCountProdukBySO']);
        Route::get('/data-so/{id}', [ProduksiController::class, 'detailCountProdukBySO']);
        Route::post('/telat_rakit', [ProduksiController::class, 'storeTelatRakit']);
    });

    Route::prefix('/gbj')->group(function () {
        Route::get('show_nonso', [GudangController::class, 'getNonSODone']);
        Route::get('show_nonso_new', [GudangController::class, 'getNonSODone_new']);
        Route::get('template_noseri', [GudangController::class, 'download_template_noseri']);
        Route::post('import-noseri', [GudangController::class, 'import_noseri']);
        Route::post('store-importseri', [GudangController::class, 'import_noseri_to_db']);
        Route::post('delete-noseri', [GudangController::class, 'delete_noseri']);
        Route::post('edit-noseri', [GudangController::class, 'edit_noseri']);
        Route::post('list-waiting-noseri', [GudangController::class, 'get_data_waiting_approve']);
        Route::post('list-approve-noseri', [GudangController::class, 'list_approve_noseri'])->middleware('jwt.verify');
        Route::post('list-update-noseri', [GudangController::class, 'list_update_noseri'])->middleware('jwt.verify');
        Route::post('detail-update-noseri', [GudangController::class, 'detail_list_update_noseri'])->middleware('jwt.verify');
        Route::post('detail-delete-noseri', [GudangController::class, 'detail_list_delete_noseri'])->middleware('jwt.verify');
        Route::post('proses-delete-noseri', [GudangController::class, 'proses_delete_noseri']);
        Route::post('proses-update-noseri', [GudangController::class, 'proses_update_noseri']);
        Route::post('tets', [GudangController::class, 'updateStokGudang']);
        Route::post('riwayat_perubahan_noseri', [GudangController::class, 'getNoseriHistoryPerubahan'])->middleware('jwt.verify');
        Route::post('alasan_edit_noseri_staff', [GudangController::class, 'get_alasan_from_staff'])->middleware('jwt.verify');
        Route::post('detail_riwayat_perubahan_noseri', [GudangController::class, 'detailNoseriHistoryPerubahan'])->middleware('jwt.verify');
        Route::get('header_count_noseri_status/{a}', [GudangController::class, 'headerCountNoseri'])->middleware('jwt.verify');
        Route::get('get_rekap_so_produk', [GudangController::class, 'get_rekap_so_produk']);
        Route::get('get_detail_rekap_so_produk/{id}', [GudangController::class, 'get_detail_rekap_so_produk']);

        Route::post('delete_paket_so', [GudangController::class, 'deleteCekSO']);
        Route::get('template_so/{id}', [GudangController::class, 'download_template_so']);
        Route::post('preview-so', [GudangController::class, 'preview_so']);
        Route::post('store-sodb', [GudangController::class, 'store_so_to_db']);

        Route::get('template_nonso', [GudangController::class, 'template_tanpa_so']);
        Route::post('preview-nonso', [GudangController::class, 'preview_tanpa_so']);
        Route::post('store-nonsodb', [GudangController::class, 'store_nonso_to_db']);

        Route::post('so_batal', [GudangController::class, 'get_so_batal']);
        Route::post('proses_so_batal', [GudangController::class, 'proses_so_batal']);
    });

    Route::prefix('gk')->group(function () {
        Route::get('getNoseriGudang', [SparepartController::class, 'getNoseriGudang'])->name('autocom');
        Route::post('/checkSeriNew', [SparepartController::class, 'checkNoseriNew']);
    });
});
