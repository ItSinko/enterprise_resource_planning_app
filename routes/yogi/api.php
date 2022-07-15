<?php

use App\Http\Controllers\GudangController;
use App\Http\Controllers\ProduksiController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v2')->group(function() {
    // produksi
    Route::prefix('/prd')->group(function() {
        Route::get('/produk-so', [ProduksiController::class, 'getCountProdukBySO']);
        Route::get('/data-so/{id}', [ProduksiController::class, 'detailCountProdukBySO']);
        Route::post('/telat_rakit', [ProduksiController::class, 'storeTelatRakit']);
    });

    Route::prefix('/gbj')->group(function() {
        Route::get('template_noseri', [GudangController::class, 'download_template_noseri']);
        Route::post('import-noseri', [GudangController::class, 'import_noseri']);
        Route::post('store-importseri', [GudangController::class, 'import_noseri_to_db']);
        Route::post('delete-noseri', [GudangController::class, 'delete_noseri']);
        Route::post('edit-noseri', [GudangController::class, 'edit_noseri']);
        Route::post('list-waiting-noseri', [GudangController::class, 'get_data_waiting_approve']);
        Route::post('list-approve-noseri', [GudangController::class, 'list_approve_noseri']);
        Route::post('list-update-noseri', [GudangController::class, 'list_update_noseri']);
        Route::post('detail-update-noseri', [GudangController::class, 'detail_list_update_noseri']);
        Route::post('detail-delete-noseri', [GudangController::class, 'detail_list_delete_noseri']);
        Route::post('proses-delete-noseri', [GudangController::class, 'proses_delete_noseri']);
        Route::post('proses-update-noseri', [GudangController::class, 'proses_update_noseri']);
        Route::post('tets',[GudangController::class, 'updateStokGudang']);
        Route::post('detail-noseri-history-produk', [GudangController::class, 'detailHistoriNoseri']);
        Route::post('detaildone-noseri-history-produk', [GudangController::class, 'detailHistoriNoseriDone']);
        Route::post('riwayat_perubahan_noseri', [GudangController::class, 'getNoseriHistoryPerubahan']);
        Route::post('detail_riwayat_perubahan_noseri', [GudangController::class, 'detailNoseriHistoryPerubahan']);

        Route::get('template_so/{id}', [GudangController::class, 'download_template_so']);
        Route::post('preview-so', [GudangController::class, 'preview_so']);
        Route::post('store-sodb', [GudangController::class, 'store_so_to_db']);

        Route::post('so_batal', [GudangController::class, 'get_so_batal']);
        Route::post('proses_so_batal', [GudangController::class, 'proses_so_batal']);
    });

    Route::prefix('/gk')->group(function() {
        Route::post('/list_terima_dari_luar', [GudangController::class, 'get_terima_perbaikan']);
        Route::post('/detail_terima_dari_luar', [GudangController::class, 'get_detail_terima_perbaikan']);
    });

});
