<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



Route::group(['prefix' => '/kesehatan', 'middleware' => 'auth'], function () {


    Route::group(['prefix' => '/klinik'], function () {
        Route::get('/obat_detail', [App\Http\Controllers\kesehatan\KesehatanController::class, 'klinik_obat_detail']);
        Route::get('/diagnosa_detail', [App\Http\Controllers\kesehatan\KesehatanController::class, 'klinik_diagnosa_detail']);
        Route::get('/sakit_detail', [App\Http\Controllers\kesehatan\KesehatanController::class, 'klinik_sakit_detail']);
    });
    //  Route::group(['middleware' => ['divisi:kes']], function () {
    Route::view('/dashboard', 'page.kesehatan.dashboard')->name('kesehatan.dashboard');
    Route::get('/', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan']);
    Route::get('/tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_tambah']);
    Route::get('/detail', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_detail']);
    Route::post('/store', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_aksi_tambah']);
    Route::post('/data', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_data']);

    Route::post('/penyakit/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_riwayat_penyakit']);
    Route::get('/data/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_data_detail']);
    //});

    Route::group(['prefix' => '/vaksin'], function () {
        Route::post('/aksi_tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_vaksin_aksi_tambah']);
        Route::get('/chart/data', [App\Http\Controllers\kesehatan\KesehatanController::class, 'chart_vaksin']);
        Route::post('/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_vaksin']);
        Route::delete('/delete/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_vaksin_aksi_hapus']);
    });

    Route::group(['prefix' => '/riwayat_penyakit'], function () {
        Route::post('/aksi_tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_riwayat_penyakit_aksi_tambah']);
        Route::delete('/delete/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_riwayat_penyakit_aksi_hapus']);
        Route::get('/data', [App\Http\Controllers\kesehatan\KesehatanController::class, 'riwayat_penyakit_data']);
    });

    Route::group(['prefix' => '/riwayat_analisa'], function () {
        Route::get('/data', [App\Http\Controllers\kesehatan\KesehatanController::class, 'riwayat_analisa_data']);
    });



    Route::group(['prefix' => '/laporan'], function () {
        Route::get('/harian', [App\Http\Controllers\kesehatan\KesehatanController::class, 'laporan_harian']);
        Route::get('/mingguan', [App\Http\Controllers\kesehatan\KesehatanController::class, 'laporan_mingguan']);
        Route::get('/bulanan', [App\Http\Controllers\kesehatan\KesehatanController::class, 'laporan_bulanan']);
        Route::get('/tahunan', [App\Http\Controllers\kesehatan\KesehatanController::class, 'laporan_tahunan']);
        Route::get('/harian/data/{filter}/{id}/{start}/{end}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'laporan_harian_data']);
        Route::get('/mingguan/data/{filter_mingguan}/{filter}/{id}/{start}/{end}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'laporan_mingguan_data']);
        Route::get('/bulanan/data/{filter_bulanan}/{filter}/{id}/{start}/{end}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'laporan_bulanan_data']);
        Route::get('/tahunan/data/{filter}/{id}/{start}/{end}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'laporan_tahunan_data']);
    });

    Route::group(['prefix' => '/bulanan'], function () {
        Route::get('/',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan']);
        Route::get('/gcu/tambah',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_gcu_tambah']);
        Route::get('/berat/tambah',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_berat_tambah']);
        Route::get('/tambah/data',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_tambah_data']);
        Route::post('/gcu/aksi_tambah',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_gcu_aksi_tambah']);
        Route::post('/berat/aksi_tambah',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_berat_aksi_tambah']);
        Route::put('/berat/aksi_ubah',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_berat_aksi_ubah']);
        Route::put('/gcu/aksi_ubah',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_gcu_aksi_ubah']);
        Route::post('/gcu/data',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_gcu_data']);
        Route::post('/berat/data',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_berat_data']);
        Route::delete('/berat/delete/{id}',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_berat_delete']);
        Route::delete('/gcu/delete/{id}',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_gcu_delete']);
        Route::get('/detail',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_gcu_detail']);
        Route::post('/berat/detail/{karyawan_id}',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_berat_detail_data']);
        Route::post('/gcu/detail/{karyawan_id}',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_gcu_detail_data']);
        Route::get('/detail/data/{karyawan_id}',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_bulanan_detail_data_karyawan']);
    });

    Route::group(['prefix' => '/mingguan'], function () {
        //Kesehatan Mingguan
        Route::get('/', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan']);
        // /* Tambah */
        // Route::get('/tambah', 'KesehatanController@kesehatan_mingguan_tambah');
        Route::get('/rapid/tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_rapid_tambah']);
        Route::get('/tensi/tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_tensi_tambah']);
        Route::post('/tensi/aksi_tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_tensi_aksi_tambah']);
        Route::put('/tensi/aksi_ubah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_tensi_aksi_ubah']);
        Route::put('/rapid/aksi_ubah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_rapid_aksi_ubah']);
        Route::post('/rapid/aksi_tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_rapid_aksi_tambah']);
        // /* Get Data */
        Route::post('/tensi/data', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_tensi_data']);
        Route::post('/rapid/data', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_rapid_data']);
        Route::delete('/tensi/delete/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_tensi_delete']);
        Route::delete('/rapid/delete/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_rapid_delete']);
        // /* Get Detail */
        Route::get('/detail', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_detail']);
        Route::get('/tensi/detail/{karyawan_id}',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_tensi_detail_data']);
        Route::get('/rapid/detail/{karyawan_id}',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_rapid_detail_data']);
        Route::get('/tensi/detail/data/{karyawan_id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'kesehatan_mingguan_tensi_detail_data_karyawan']);
    });
});

Route::group(['prefix' => '/karyawan', 'middleware' => 'auth'], function () {
    // Route::group(['middleware' => ['divisi:kes']], function () {
    Route::get('/', [App\Http\Controllers\kesehatan\KaryawanController::class, 'karyawan_show']);
    Route::post('/data', [App\Http\Controllers\kesehatan\KaryawanController::class, 'karyawan_data']);
    Route::get('/create', [App\Http\Controllers\kesehatan\KaryawanController::class, 'karyawan_tambah']);
    Route::get('/cekdata/{value}', [App\Http\Controllers\kesehatan\KaryawanController::class, 'karyawan_cekdata']);
    Route::post('/store', [App\Http\Controllers\kesehatan\KaryawanController::class, 'karyawan_aksi_tambah']);
    Route::put('/update', [App\Http\Controllers\kesehatan\KaryawanController::class, 'karyawan_aksi_ubah']);
    Route::delete('/delete/{id}', [App\Http\Controllers\kesehatan\KaryawanController::class, 'karyawan_aksi_hapus']);
    //  });

    Route::group(['prefix' => '/sakit'], function () {
        Route::get('/', [App\Http\Controllers\kesehatan\KesehatanController::class, 'karyawan_sakit']);
        Route::get('/cetak/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'karyawan_sakit_cetak']);
        Route::post('/data/{value}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'karyawan_sakit_data']);
        Route::get('/tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'karyawan_sakit_tambah']);
        Route::get('/obat/data/', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_data']);
        Route::get('/obat/detail/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_data_detail']);
        Route::post('/aksi_tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'karyawan_sakit_aksi_tambah']);
        Route::delete('/delete/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'karyawan_sakit_aksi_delete']);
        Route::post('/penyakit/top/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'penyakit_top']);
        Route::post('/obat/top/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_top']);
        Route::get('/penyakit/top/detail/{month}/{year}/{sakit}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'penyakit_top_detail']);
        Route::post('/person/top/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'person_top']);
        Route::get('/obat/top/detail/{month}/{year}/{sakit}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_top_detail']);
        Route::get('/person/top/detail/{month}/{year}/{sakit}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'person_top_detail']);
    });
    Route::group(['prefix' => '/masuk'], function () {
        // //Karyawan Masuk
        Route::get('/', [App\Http\Controllers\kesehatan\KesehatanController::class, 'karyawan_masuk']);
        Route::post('/data', [App\Http\Controllers\kesehatan\KesehatanController::class, 'karyawan_masuk_data']);
        Route::get('/tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'karyawan_masuk_tambah']);
        Route::post('/aksi_tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'karyawan_masuk_aksi_tambah']);
        Route::get('/detail/data/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'karyawan_masuk_detail_data']);
        Route::delete('/delete/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'karyawan_masuk_aksi_delete']);
        Route::get('/chart_absen', [App\Http\Controllers\kesehatan\KesehatanController::class, 'chart_absen']);
    });
});

Route::group(['prefix' => '/obat', 'middleware' => 'auth'], function () {
    // Route::group(['middleware' => ['divisi:kes']], function () {
    Route::get('/', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat']);
    Route::post('/data', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_data']);
    Route::get('/data/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_data_id']);
    Route::get('/data/select/{where}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_data_select']);
    Route::post('/data/detail/{id}',  [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_detail_data_karyawan']);
    Route::get('/cekdata/{nama}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_cekdata']);
    Route::get('/detail/data/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_detail_data']);
    Route::get('/tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_tambah']);
    Route::post('/aksi_tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_aksi_tambah']);
    Route::post('/stok/aksi_tambah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_stok_aksi_tambah']);
    Route::get('/stok/data/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_stok_data']);
    Route::put('/aksi_ubah', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_aksi_ubah']);
    Route::delete('/delete/{id}', [App\Http\Controllers\kesehatan\KesehatanController::class, 'obat_aksi_delete']);
    //  });
});
