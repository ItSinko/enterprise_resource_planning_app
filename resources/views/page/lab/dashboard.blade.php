@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
<style>
.hoverwidget{
    width:100%;
    float:left;
    overflow:hidden;
    position:relative;
    text-align:center;
    cursor:default;
}

.hoverwidget .overlay{
    width:100%;
    height:100%;
    position:absolute;
    overflow:hidden;
    top:0;
    left:0;
    opacity:0;
    background-color:rgba(0,0,0,0.5);
    -webkit-transition:all .2s ease-in-out;
    transition:all .2s ease-in-out
}

.hoverwidget:hover .overlay {
    opacity:1;
    filter:alpha(opacity=100);
}

.small-box .icon i {
    font-size: 85px;
    right: -15px;
}

.icon {
    width: 100%;
}

table tbody tr td i{
    vertical-align: middle;
    text-align:center;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js" integrity="sha256-+8RZJua0aEWg+QVVKg4LEzEEm/8RFez5Tb4JBNiV5xA=" crossorigin="anonymous"></script>
<div class="container-fluid">

    {{-- <div class="container p-3"> --}}

        <!-- card Alat uji & peminjaman -->
        <div class="row">
            <div class="col-4 p-1">
                <div class="card mb-1">
                    <div class="mx-2 mt-2"><strong>Alat Uji</strong></div>
                    <div class="card-body py-0 m-0">
                        <div class="row">
                            <!-- col kiri alat uji -->
                            <!-- info total -->
                            <div class="col p-1">
                                <div class="small-box bc-primary text-primary flex-fill d-flex flex-column hoverwidget">
                                    <div class="inner">
                                        <div class="card-title fs-4"><strong>{{ json_decode($data)->total }}</strong></div>
                                        <div class="card-text">Total</div>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-list" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col p-1">
                                <div class="small-box bc-primary text-primary flex-fill d-flex flex-column hoverwidget">
                                    <div class="inner">
                                        <div class="card-title fs-4"><strong class="text-end text-end">{{ json_decode($data)->tersedia }}</strong></div>
                                        <div class="card-text text-end">Tersedia</div>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- info total end -->

                            <!-- info not ok -->
                            <div class="col p-1">
                                <div class="small-box bc-danger text-danger flex-fill d-flex flex-column hoverwidget">
                                    <div class="inner">
                                        <div class="card-title fs-2"><strong>{{ json_decode($data)->not }}</strong></div>
                                        <div class="card-text">Not OK</div>
                                    </div>
                                    <div class="icon">
                                        <i class="fa-regular fa-circle-xmark" aria-hidden="true"></i>
                                    </div>
                                    <div class="overlay">
                                        <div class="btn btn-primary" data-toggle="modal" data-target="#notOkModal">Detail</div>
                                    </div>
                                </div>
                            </div>
                            <!-- info not ok end -->
                            <!-- col kiri alat uji end -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-8 p-1">
                <div class="card mb-1">
                <div class="mt-2 mx-2"><strong>Peminjaman</strong></div>
                    <div class="card-body py-0 m-0">
                        <div class="row">
                        <!-- info permintaan pinjam -->
                        <div class="col p-1">
                            <div class="small-box bc-warning text-warning flex-fill d-flex flex-column hoverwidget">
                                <div class="inner">
                                    <div class="card-title fs-2"><strong>{{ json_decode($data)->permintaan }}</strong></div>
                                    <div class="card-text text-left">Perminataan</div>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-hand-holding" style="top:-15px;right:-5px;" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <!-- info permintaan pinjam end -->

                        <!-- info Dipinjam Pengembalian -->
                        <div class="col p-1">
                            <div class="small-box bc-success text-success flex-fill d-flex flex-column hoverwidget">
                                <div class="inner">
                                    <div class="card-title fs-2"><strong>{{ json_decode($data)->dipinjam }}</strong></div>
                                    <div class="card-text text-left">Dipinjam</div>
                                    <div class="icon">
                                        <i class="fa-regular fa-clock" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- info Dipinjam Pengembalian end -->

                        <!-- info batas pengembalian -->
                        <div class="col p-1">
                            <div class="small-box bc-danger text-danger flex-fill d-flex flex-column hoverwidget">
                                <div class="inner">
                                    <div class="card-title fs-2"><strong>{{ json_decode($data)->batasPinjam }}</strong></div>
                                    <div class="card-text text-left">Melebihi Batas</div>
                                </div>
                                <div class="icon">
                                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <!-- info batas pengembalian end -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- card Alat uji & peminjaman end -->

        <div class="row">
        <!-- card External -->
            <div class="col-3 p-1">
                <div class="card mb-1 h-100">
                    <div class="mx-2 mt-2"><strong>Perbaikan & Kalibrasi</strong></div>
                    <div class="card-body py-0 m-0">
                        <!-- card sedang maintenence diluar -->
                        <div class="col p-1">
                            <div class="small-box bc-primary flex-fill d-flex flex-column hoverwidget">
                                <div class="inner text-primary">
                                    <div class="card-title fs-2"><strong>{{ json_decode($data)->external }}</strong></div>
                                    <div class="card-text text-left">External</div>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-external-link-alt fa-flip-horizontal" style="right:0px;"></i>
                                </div>
                            </div>
                        </div>
                        <!-- card sedang maintenence diluar end -->
                    </div>
                </div>
            </div>
        <!-- card External end -->

        <!-- card Verifikasi Perawatan -->
            <div class="col p-1">
                <div class="card mb-1">
                    <div class="mx-2 mt-2"><strong>Perawatan & Verifikasi</strong></div>
                        <div class="card-body py-0 m-0">

                        <div class="row">
                            <!-- card jadwal maintenence yang akan datang -->
                            <div class="col p-1">
                                <div class="small-box flex-fill d-flex flex-column justify-content-start bc-secondary text-secondary hoverwidget">
                                    <div class="inner">
                                        <div class="text-left"><strong>Reminder</strong></div>
                                        <div class="d-flex justify-content-start">
                                            <div class="w-50 text-left"><span>Verifikasi</span></div>
                                            <div><strong>{{ json_decode($data)->verifikasiNext }}</strong></div>
                                        </div>
                                        <div class="d-flex justify-content-start">
                                            <div class="w-50 text-left"><span>Perawatan</span></div>
                                            <div><strong>{{ json_decode($data)->perawatanNext }}</strong></div>
                                        </div>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-calendar-plus" aria-hidden="true"></i>
                                    </div>
                                    <div class="overlay">
                                        <div class="btn btn-primary" data-toggle="modal" data-target="#model_mt_berikutnya">Detail</div>
                                    </div>
                                </div>
                            </div>
                            <!-- card jadwal maintenence yang akan datang end -->

                            <!-- card jadwal maintenence bulan ini -->
                            <div class="col p-1">
                                <div class="small-box flex-fill d-flex flex-column justify-content-start bc-warning text-warning hoverwidget">
                                    <div class="inner">
                                        <div class="text-left"><strong>Bulan Ini</strong></div>
                                        <div class="d-flex justify-content-start">
                                            <div class="w-50 text-left">Verifikasi</div>
                                            <div class=""><strong>{{ json_decode($data)->verifikasiNow }}</strong></div>
                                        </div>
                                        <div class="d-flex justify-content-start">
                                            <div class="w-50 text-left">Perawatan</div>
                                            <div class=""><strong>{{ json_decode($data)->perawatanNow }}</strong></div>
                                        </div>
                                    </div>
                                    <div class="icon">
                                        <i class="fa-solid fa-calendar" aria-hidden="true"></i>
                                    </div>
                                    <div class="overlay">
                                        <div class="btn btn-primary" data-toggle="modal" data-target="#model_mt_sekarng">Detail</div>
                                    </div>
                                </div>
                            </div>
                            <!-- card jadwal maintenence bulan ini end -->

                            <!-- card jadwal maintenence terlewati -->
                            <div class="col p-1">
                                <div class="small-box flex-fill d-flex flex-column justify-content-start bc-danger text-danger hoverwidget">
                                    <div class="inner">
                                        <div class="text-left"><strong>Terlewati</strong></div>
                                        <div class="d-flex justify-content-start">
                                            <div class="w-50 text-left">Verifikasi</div>
                                            <div class=""><strong>{{ json_decode($data)->verifikasiOld }}</strong></div>
                                        </div>
                                        <div class="d-flex justify-content-start">
                                            <div class="w-50 text-left">Perawatan</div>
                                            <div class=""><strong>{{ json_decode($data)->perawatanOld }}</strong></div>
                                        </div>
                                    </div>
                                    <div class="icon">
                                        <i class="fa-solid fa-calendar-xmark" aria-hidden="true"></i>
                                    </div>
                                    <div class="overlay">
                                        <div class="btn btn-primary" data-toggle="modal" data-target="#model_mt_terlewati">Detail</div>
                                    </div>
                                </div>
                            </div>
                            <!-- card jadwal maintenence terlewati end -->

                            <!-- card alat uji yang belum memiliki data perawatan verifikasi -->
                            <!-- <div class="col-3 p-1">
                                <div class="card shadow-none border-0 m-0 bc-secondary text-secondary">
                                    <div class="card-body py-2">
                                        <div class="row">
                                            <div class="col">
                                                <div class="card-title fs-2 m-0"><strong>10</strong></div>
                                            </div>
                                            <div class="col d-flex justify-content-center">
                                                <i class="fa-solid fa-circle-question fa-3x pt-3"></i>
                                            </div>
                                        </div>
                                        <div class="card-text">Belum Terdata</div>
                                    </div>
                                </div>
                            </div> -->
                            <!-- card alat uji yang belum memiliki data perawatan verifikasi end -->
                        </div>
                    </div>
                </div>
            </div>
        <!-- card maintenance end -->
        </div>

        <!-- bagian tabel peminjaman alat uji -->
        <div class="row">
            <!-- col pemijaman alat uji -->
            <div class="col-6 p-1">
                <div class="card mb-1">
                    <div class="mt-2 mx-2"><strong>Peminjaman Alat Uji</strong></div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-striped table-sm" id="tablePengajuan">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Peminjam</th>
                                    <th>Alat</th>
                                    <th>SN</th>
                                    <th>Kondisi</th>
                                    <th>Tgl Batas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col peminjaman alat uji end -->

            <!-- col pengembalian alatuji -->
            <div class="col-6 p-1">
                <div class="card">
                    <div class="mt-2 mx-2"><strong>Pengembalian Alat Uji</strong></div>
                    <div class="card-body">

                        <div class="table-responsive">
                        <table class="table table-striped table-sm" id="tableDipinjam">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">Peminjam</th>
                                    <th scope="col">Alat</th>
                                    <th scope="col">SN</th>
                                    <th scope="col">Kondisi</th>
                                    <th scope="col">Batas</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        </div>

                    </div>
                </div>
            </div>
            <!-- col pengembalian alatuji end -->
        </div>
        <!-- bagian peminjaman alat uji end -->

        <!-- bagian grafik -->
        <div class="row">
            <!-- card grafik peminjaman alat uji -->
            <div class="col p-1">
                <div class="card mb-1">
                    <div class="card-body">
                        <canvas id="myChart" width="400" height="100"></canvas>
                    </div>
                </div>
            </div>
            <!-- card grafik peminjaman alat uji end -->

            <!-- card gafik target maintenance alat uji -->
            <!-- <div class="col p-1">
                <div class="card mb-1">
                    <div class="card-body">

                    </div>
                </div>
            </div> -->
            <!-- card gafik target maintenance alat uji end -->
        </div>
        <!-- bagian grafik end -->

    {{-- </div> --}}
</div>

<!-- modal section -->

<!-- modal mt bulan ini -->
<div class="modal fade" id="model_mt_sekarng" tabindex="-1" aria-labelledby="model_mt_sekarng_Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">

            <h4 class="modal-title" id="pinjamModalLabel">
                <strong>
                    Bulan Ini
                </strong>
            </h4>

            <!-- tab nav -->
            <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-toggle="tab" href="#mt_sekarang_tab_perawatan">Perawatan</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-toggle="tab" href="#mt_sekarang_tab_verifikasi">Verifikasi</a>
                </li>
            </ul>
            <!-- tab nav end -->

            <!-- tab content -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="mt_sekarang_tab_perawatan" role="tabpanel" >
                    <div class="table-responsive">
                        <table class="table table-sm border text-center w-100" id="tabel_mt_sekarang_p">
                            <thead class="bc-grey">
                                <tr>
                                    <th class="border-top-0 border-bottom-0">No</th>
                                    <th class="border-top-0 border-bottom-0">Alat Uji</th>
                                    <th class="border-top-0 border-bottom-0">Serial Num</th>
                                    <th class="border-top-0 border-bottom-0">Terakhir</th>
                                    <th class="border-top-0 border-bottom-0">Berikutnya</th>
                                    <th class="border-top-0 border-bottom-0">-</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="mt_sekarang_tab_verifikasi" role="tabpanel" >
                    <div class="table-responsive">
                        <table class="table table-sm border text-center w-100" id="tabel_mt_sekarang_v">
                            <thead class="bc-grey">
                                <tr>
                                    <th class="border-top-0 border-bottom-0">No</th>
                                    <th class="border-top-0 border-bottom-0">Alat Uji</th>
                                    <th class="border-top-0 border-bottom-0">Serial Num</th>
                                    <th class="border-top-0 border-bottom-0">Terakhir</th>
                                    <th class="border-top-0 border-bottom-0">Berikutnya</th>
                                    <th class="border-top-0 border-bottom-0">-</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- tab content end -->

            <!-- content -->
        </div>
        </div>
    </div>
</div>
<!-- modal mt bulan ini end -->

<!-- modal mt jadwal terlewati -->
<div class="modal fade" id="model_mt_terlewati" tabindex="-1" aria-labelledby="model_mt_lewat_Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">

            <h4 class="modal-title" id="pinjamModalLabel">
                <strong>
                    Jadwal Terlewati
                </strong>
            </h4>

            <!-- tab nav -->
            <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-toggle="tab" href="#mt_lewat_tab_perawatan">Perawatan</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-toggle="tab" href="#mt_lewat_tab_verifikasi">Verifikasi</a>
                </li>
            </ul>
            <!-- tab nav end -->

            <!-- tab content -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="mt_lewat_tab_perawatan" role="tabpanel" >
                    <div class="table-responsive">
                        <table class="table table-sm border text-center w-100" id="tabel_mt_terlewati_p">
                            <thead class="bc-grey">
                                <tr>
                                    <th class="border-top-0 border-bottom-0">No</th>
                                    <th class="border-top-0 border-bottom-0">Alat Uji</th>
                                    <th class="border-top-0 border-bottom-0">Serial Num</th>
                                    <th class="border-top-0 border-bottom-0">Terakhir</th>
                                    <th class="border-top-0 border-bottom-0">Berikutnya</th>
                                    <th class="border-top-0 border-bottom-0">-</th>
                                </tr>
                            </thead>
                            <tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="mt_lewat_tab_verifikasi" role="tabpanel" >
                    <div class="table-responsive">
                        <table class="table table-sm border text-center w-100" id="tabel_mt_terlewati_v">
                            <thead class="bc-grey">
                                <tr>
                                    <th class="border-top-0 border-bottom-0">No</th>
                                    <th class="border-top-0 border-bottom-0">Alat Uji</th>
                                    <th class="border-top-0 border-bottom-0">Serial Num</th>
                                    <th class="border-top-0 border-bottom-0">Terakhir</th>
                                    <th class="border-top-0 border-bottom-0">Berikutnya</th>
                                    <th class="border-top-0 border-bottom-0">-</th>
                                </tr>
                            </thead>
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- tab content end -->

            <!-- content -->
        </div>
        </div>
    </div>
</div>
<!-- modal mt jadwal terlewati -->

<!-- modal mt 1 bulan berikutnya -->
<div class="modal fade" id="model_mt_berikutnya" tabindex="-1" aria-labelledby="model_mt_berikutnya_Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-body">

            <h4 class="modal-title" id="pinjamModalLabel">
                <strong>
                    Reminder
                </strong>
            </h4>

            <!-- tab nav -->
            <ul class="nav nav-tabs nav-justified mb-3" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-toggle="tab" href="#mt_berikutnya_tab_perawatan">Perawatan</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-toggle="tab" href="#mt_berikutnya_tab_verifikasi">Verifikasi</a>
                </li>
            </ul>
            <!-- tab nav end -->

            <!-- tab content -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="mt_berikutnya_tab_perawatan" role="tabpanel" >
                    <div class="table-responsive">
                        <table class="table table-sm border text-center w-100" id="tabel_mt_berikutnya_p">
                            <thead class="bc-grey">
                                <tr>
                                    <th class="border-top-0 border-bottom-0">No</th>
                                    <th class="border-top-0 border-bottom-0">Alat Uji</th>
                                    <th class="border-top-0 border-bottom-0">Serial Num</th>
                                    <th class="border-top-0 border-bottom-0">Terakhir</th>
                                    <th class="border-top-0 border-bottom-0">Berikutnya</th>
                                    <th class="border-top-0 border-bottom-0">-</th>
                                </tr>
                            </thead>
                            <tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="mt_berikutnya_tab_verifikasi" role="tabpanel" >
                    <div class="table-responsive">
                        <table class="table table-sm border text-center w-100" id="tabel_mt_berikutnya_v">
                            <thead class="bc-grey">
                                <tr>
                                    <th class="border-top-0 border-bottom-0">No</th>
                                    <th class="border-top-0 border-bottom-0">Alat Uji</th>
                                    <th class="border-top-0 border-bottom-0">Serial Num</th>
                                    <th class="border-top-0 border-bottom-0">Terakhir</th>
                                    <th class="border-top-0 border-bottom-0">Berikutnya</th>
                                    <th class="border-top-0 border-bottom-0">-</th>
                                </tr>
                            </thead>
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- tab content end -->

            <!-- content -->
        </div>
        </div>
    </div>
</div>
<!-- modal mt 1 bulan berikutnya end -->

<!-- modal konfirmasi peminjaman -->
<div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="konfirmasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-body">

            <h4 class="modal-title">
                <strong>
                    Konfirmasi Peminjaman
                </strong>
            </h4>

            <div class="row mt-3">

                <!-- kolom kiri -->
                <div class="col-4">
                    <div class="card border-warning border-top-w3">
                    <div class="card-body">
                        <h5><strong>Info Peminjman</strong></h5>

                        <p class="mb-0 mt-3">Nama Peminjam</p>
                        <span><strong id="modal_display_terima_peminjam"></strong></span>

                        <p class="mb-0 mt-3">Tanggal Pengajuan</p>
                        <span><strong id="modal_display_terima_tgl1">01 September 2022</strong></span>

                        <p class="mb-0 mt-3">Tanggal Peminjaman</p>
                        <span><strong id="modal_display_terima_tgl2">01 September 2022</strong></span>

                        <p class="mb-0 mt-3">Tanggal Pengembalian</p>
                        <span><strong id="modal_display_terima_tgl3">01 September 2022</strong></span>
                    </div>
                    </div>
                </div>
                <!-- kolom kiri end -->

                <!-- kolom kanan -->
                <div class="col-8">

                    <div class="alert bc-warning" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-info-circle-fill text-warning" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                        </svg>

                        <strong>
                            Peminjaman Untuk Alat Uji
                        </strong>
                    </div>

                    <form action="{{route('alatuji.pinjam.konfirmasi')}}" method="POST">
                    @csrf
                    <input type="hidden" name="peminjaman_konfirm_id" id="peminjaman_konfirm_id" value="">
                    <input type="hidden" name="alatuji_konfirm_id" id="alatuji_konfirm_id" value="">

                    <!-- card kanan info pinjam -->
                    <div class="card border-warning border-top-w3">
                    <div class="card-body">

                        <div class="row mb-2">
                            <div class="col"><span class="float-right">Status Peminjaman</span></div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_peminjaman" id="konfirmasi_terima" value="15" checked="">
                                            <label class="form-check-label" for="konfirmasi_terima">Terima</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_peminjaman" id="konfirmasi_tolak" value="18">
                                            <label class="form-check-label" for="konfirmasi_tolak">Tolak</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col"><span class="float-right">Kondisi Barang</span></div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_peminjaman" id="konfirmasi_ok" value="9" {{ old('kondisi_peminjaman') == null ? 'checked' : (old('kondisi_peminjaman') == 9 ? 'checked' : '') }}>
                                            <label class="form-check-label" for="konfirmasi_ok">OK</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_peminjaman" id="konformasi_not" value="10" {{ old('kondisi_peminjaman') == 10 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="konformasi_not">NOT OK</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('keterangan_konfirmasi')
                            <div class="col mb-0">
                                <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                            </div>
                        @enderror

                        <div id="keterangan_konfirmasi_container" style="display:none">
                        <div class="row mb-2">
                            <div class="col"><span class="float-right">Keterangan</span></div>
                            <div class="col">
                                <textarea class="form-control" name="keterangan_konfirmasi" id="" cols="30" rows="1"></textarea>
                            </div>
                        </div>
                        </div>

                    </div>
                    </div>
                    <!-- card kanan info pinjam end -->

                    <div class="row float-right">
                        <div class="col-auto">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                <strong>Batal</strong>
                            </button>
                        </div>
                        <div class="col-auto">
                            <input type="submit" value="Pinjam" class="btn btn-warning" style="font-weight:bold">
                        </div>
                    </div>
                    </form>

                </div>
                <!-- kolom kanan end -->

            </div>

        </div>
        </div>
    </div>
</div>
<!-- modal konfirmasi pinjam end -->

<!-- modal terima barang peminjaman -->
<div class="modal fade" id="terimaModal" tabindex="-1" aria-labelledby="terimaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-body">

            <h4 class="modal-title">
            <div class="alert bc-success" role="alert">
                <strong>
                    Terima Barang Peminjaman
                </strong>
            </div>
            </h4>

            <div class="row mt-3">

                <!-- kolom kiri -->
                <div class="col-4">
                    <div class="card border-success border-top-w3">
                    <div class="card-body">
                        <h5><strong>Info Peminjman</strong></h5>

                        <p class="mb-0 mt-3">Nama Peminjam</p>
                        <span><strong id="modal_display_kembali_nama"></strong></span>

                        <p class="mb-0 mt-3">Tanggal Peminjaman</p>
                        <span><strong id="modal_display_kembali_tgl1"></strong></span>

                        <p class="mb-0 mt-3">Batas Pengembalian</p>
                        <span><strong id="modal_display_kembali_tgl2"></strong></span>
                    </div>
                    </div>
                </div>
                <!-- kolom kiri end -->

                <!-- kolom kanan -->
                <div class="col-8">

                    <form action="{{route('alatuji.pinjam.kembali')}}" method="post">
                    @csrf
                    <input type="hidden" name="peminjaman_kembali_id" id="peminjaman_kembali_id" value="">
                    <input type="hidden" name="id_alat_uji" id="id_alat_uji" value="">

                    <!-- card kanan info kembali -->
                    <div class="card border-success border-top-w3">
                    <div class="card-body">

                        @error('tgl_kembali')
                        <div class="row">
                            <div class="col"></div>
                            <div class="col mb-0">
                                <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                            </div>
                        </div>
                        @enderror

                        <div class="row mb-2">
                            <div class="col"><span class="float-right">Tanggal Pengembalian</span></div>
                            <div class="col">
                                <input class="form-control" type="date" name="tgl_kembali" id="" value="{{ old('tgl_kembali') }}">
                            </div>
                        </div>

                        @error('jumlah_penggunaan')
                        <div class="row">
                            <div class="col"></div>
                            <div class="col mb-0">
                                <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                            </div>
                        </div>
                        @enderror

                        <div class="row mb-2">
                            <div class="col"><span class="float-right">Jumlah Penggunaan</span></div>
                            <div class="col">
                                <input class="form-control" type="number" name="jumlah_penggunaan" id="" value="{{ old('jumlah_penggunaan') }}">
                            </div>
                        </div>

                        @error('waktu_penggunaan')
                        <div class="row">
                            <div class="col"></div>
                            <div class="col mb-0">
                                <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                            </div>
                        </div>
                        @enderror

                        <div class="row mb-2">
                            <div class="col"><span class="float-right">Waktu Penggunaan</span></div>
                            <div class="col">
                                <div class="input-group">
                                    <input class="form-control" type="number" name="waktu_penggunaan" id="" value="{{ old('waktu_penggunaan') }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Hari</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col"><span class="float-right">Kondisi Barang</span></div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_kembali" id="kondisiOK" value="9" {{ old('kondisi_kembali') == null ? 'checked' : (old('kondisi_kembali') == 9 ? 'checked' : '') }}>
                                            <label class="form-check-label" for="kondisiOK">OK</label>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kondisi_kembali" id="kondisiNOT" value="10" {{ old('kondisi_kembali') == 10 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="kondisiNOT">NOT OK</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('keterangan_kembali')
                        <div class="row">
                            <div class="col"></div>
                            <div class="col mb-0">
                                <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                            </div>
                        </div>
                        @enderror

                        <div class="row mb-2">
                            <div class="col"><span class="float-right">Keterangan</span></div>
                            <div class="col">
                                <textarea class="form-control" name="keterangan_kembali" id="" cols="30" rows="1"></textarea>
                            </div>
                        </div>

                    </div>
                    </div>
                    <!-- card kanan info kembali end -->

                    <div class="row float-right">
                        <div class="col-auto">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                <strong>Batal</strong>
                            </button>
                        </div>
                        <div class="col-auto">
                            <input type="submit" value="Terima" class="btn btn-success" style="font-weight:bold">
                        </div>
                    </div>
                    </form>

                </div>
                <!-- kolom kanan end -->

            </div>

        </div>
        </div>
    </div>
</div>
<!-- modal terima barang end -->

<!-- modal alat uji not ok -->
<div class="modal fade" id="notOkModal" tabindex="-1" aria-labelledby="alatNotOkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-body">

            <h4 class="modal-title">
                <strong>
                    Not OK
                </strong>
            </h4>

            <table class="table table-stripped table-sm border text-center w-100" id="tabel_data_alat_not_ok">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Alat uji</th>
                        <th>SN</th>
                        <th>Kondisi</th>
                        <th>Halaman</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

        </div>
        </div>
    </div>
</div>
<!-- modal alat uji not ok end -->

<!-- modal section end -->
@stop
@section('adminlte_js')
<script>
$(document).ready(function () {

    // tampilkan alert input data berhasil
    @if(session()->has('success'))
        Swal.fire({
            title: 'Berhasil',
            text: 'Data Berhasil di Perbarui',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif
    // modal alert end

    var tablePeminjaman = $('#tablePengajuan').DataTable({
        processing: true,
        serverSide: false,
        destroy: false,
        ajax: "{{ url('/api/inventory/data_dashboard_permintaan') }}",
        language: {
            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nama', name: 'nama'},
            {data: 'nm_alatuji', name: 'nama_alat'},
            {data: 'serial_number', name: 'serial_number'},
            {data: 'kondisi_id', name: 'kondisi_awal'},
            {data: 'tgl_batas', name: 'tgl_batas'},
            {data: 'aksi', name: 'aksi'}
        ],
        order:[[5, 'desc']]
    });

    var tableDipinjam = $('#tableDipinjam').DataTable({
        processing: true,
        serverSide: false,
        destroy: false,
        ajax: "{{ url('/api/inventory/data_dashboard_pengembalian') }}",
        language: {
            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nama', name: 'nama'},
            {data: 'nm_alatuji', name: 'nama_alat'},
            {data: 'serial_number', name: 'serial_number'},
            {data: 'kondisi_awal', name: 'kondisi_awal'},
            {data: 'tgl_batas', name: 'tgl_batas'},
            {data: 'aksi', name: 'aksi'}
        ],
        //responsive: true
    });

    var pinjamBulananChart = new Chart(document.getElementById('myChart').getContext('2d'),{
        type: 'line',
        data: {
            labels: ['jan', 'feb', 'mar', 'april', 'mei', 'juni', 'juli', 'agust', 'sep', 'okt', 'nov', 'des'],
            datasets: [{
                data: [
                    @for($i=0;$i<=11;$i++)
                        {{ json_decode($data)->total_peminjaman[$i] != null ? json_decode($data)->total_peminjaman[$i].',' : '0,' }}
                    @endfor
                ],
                label: 'Dipinjam',
                borderColor: "#3e95cd",
                fill: false
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Jumlah Peminjaman Perbulan'
                },
                legend: {
                    display: false
                },
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var tabelMtSekarang_p = $('#tabel_mt_sekarang_p').DataTable({
        processing: true,
        serverSide: true,
        destroy: false,
        ajax: "{{url('/api/inventory/data_dashboard_mt_sekarang/')}}"+"/"+"p",
        language: {
            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nm_alatuji', name: 'Nama'},
            {data: 'serial_number', name: 'SN'},
            {data: 'tgl_perawatan', name: 'tgl sebelumnya'},
            {data: 'jadwal_perawatan', name: 'tgl berikutnya'},
            {data: 'detail', name: 'detail'},
        ],
        order:[[5, 'asc']]
    });

    var tabelMtSekarang_v = $('#tabel_mt_sekarang_v').DataTable({
        processing: true,
        serverSide: true,
        destroy: false,
        ajax: "{{url('/api/inventory/data_dashboard_mt_sekarang/')}}"+"/"+"v",
        language: {
            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nm_alatuji', name: 'Nama'},
            {data: 'serial_number', name: 'SN'},
            {data: 'tgl_perawatan', name: 'tgl sebelumnya'},
            {data: 'jadwal_perawatan', name: 'tgl berikutnya'},
            {data: 'detail', name: 'detail'},
        ],
        order:[[5, 'asc']]
    });

    var tabelMtTerlewati_p = $('#tabel_mt_terlewati_p').DataTable({
        processing: true,
        serverSide: true,
        destroy: false,
        ajax: "{{url('/api/inventory/data_dashboard_mt_terlewati/')}}"+"/"+"p",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nm_alatuji', name: 'Nama'},
            {data: 'serial_number', name: 'SN'},
            {data: 'tgl_perawatan', name: 'tgl sebelumnya'},
            {data: 'jadwal_perawatan', name: 'tgl berikutnya'},
            {data: 'detail', name: 'detail'},
        ],
        order:[[5, 'asc']]
    });

    var tabelMtTerlewati_v = $('#tabel_mt_terlewati_v').DataTable({
        processing: true,
        serverSide: true,
        destroy: false,
        ajax: "{{url('/api/inventory/data_dashboard_mt_terlewati/')}}"+"/"+"v",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nm_alatuji', name: 'Nama'},
            {data: 'serial_number', name: 'SN'},
            {data: 'tgl_perawatan', name: 'tgl sebelumnya'},
            {data: 'jadwal_perawatan', name: 'tgl berikutnya'},
            {data: 'detail', name: 'detail'},
        ],
        order:[[5, 'asc']]
    });

    var tableMtBerikutnyaP = $('#tabel_mt_berikutnya_p').DataTable({
        processing: true,
        serverSide: true,
        destroy: false,
        ajax: "{{url('/api/inventory/data_dashboard_mt_reminder/')}}"+"/"+"p",
        language: {
            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nm_alatuji', name: 'Nama'},
            {data: 'serial_number', name: 'SN'},
            {data: 'tgl_perawatan', name: 'tgl sebelumnya'},
            {data: 'jadwal_perawatan', name: 'tgl berikutnya'},
            {data: 'detail', name: 'detail'},
        ],
        order:[[5, 'asc']]
    });

    var tableMtBerikutnyaV = $('#tabel_mt_berikutnya_v').DataTable({
        processing: true,
        serverSide: true,
        destroy: false,
        ajax: "{{url('/api/inventory/data_dashboard_mt_reminder/')}}"+"/"+"v",
        language: {
            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nm_alatuji', name: 'Nama'},
            {data: 'serial_number', name: 'SN'},
            {data: 'tgl_perawatan', name: 'tgl sebelumnya'},
            {data: 'jadwal_perawatan', name: 'tgl berikutnya'},
            {data: 'detail', name: 'detail'},
        ],
        order:[[5, 'asc']]
    });

    var tabelAlatNotOk = $('#tabel_data_alat_not_ok').DataTable({
        processing: true,
        serverSide: true,
        destroy: false,
        // ajax: "{{url('/api/inventory/data_dashboard_mt_reminder/')}}",
        ajax: "{{url('/api/inventory/get_data_not_ok')}}",
        language: {
            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'nm_alatuji', name: 'Nama'},
            {data: 'serial_number', name: 'SN'},
            {data: 'kondisi_id', name: 'kondisi'},
            {data: 'detail', name: 'detail'},
        ]
    });

    // modal konfirmasi peminjaman hide field keterangan
    @if(old('status_peminjaman') == '15')
        $('#keterangan_konfirmasi_container').show();
    @else
        $('#keterangan_konfirmasi_container').hide();
    @endif

    var kembali_x = 0;
    var kembali_y = 0;
    function cek(x,y){
        if(x == 1 && y == 1){
            $('#keterangan_konfirmasi_container').hide();
        }else{
            $('#keterangan_konfirmasi_container').show();
        }
    }
    $("input[name$='status_peminjaman']").click(function(){
        if($("#konfirmasi_terima").is(":checked")){
            kembali_x = 1; cek(kembali_x,kembali_y);
        }
        if($("#konfirmasi_tolak").is(":checked")){
            kembali_x = 0; cek(kembali_x,kembali_y);
        }
    });
    $("input[name$='kondisi_peminjaman']").click(function(){
        if($("#konfirmasi_ok").is(":checked")){
            kembali_y = 1; cek(kembali_x,kembali_y);
        }
        if($("#konformasi_not").is(":checked")){
            kembali_y = 0; cek(kembali_x,kembali_y);
        }
    });
    // modal konfirmasi hide keterangan end

});

// ambil data peminjaman, untuk modal konfirmasi peminjaman
function pinjamData(id){
    $.ajax({
        type:'GET',
        url:"{{url('/api/inventory/peminjaman_terima_data/')}}"+'/'+id,
        success:function(data) {
            $('#konfirmasiModal').modal('show');
            $('#peminjaman_konfirm_id').val(data.id_peminjaman);
            $('#alatuji_konfirm_id').val(data.serial_number_id);
            $('#modal_display_terima_peminjam').text(data.nama);
            $('#modal_display_terima_tgl1').text(data.created_at);
            $('#modal_display_terima_tgl2').text(data.tgl_pinjam);
            $('#modal_display_terima_tgl3').text(data.tgl_batas);
        }
    })
}
// ambil data untuk modal konfirmasi end

// ambil data peminjaman, untuk modal terima barang peminjaman
function dikembalikanData(id){
    $.ajax({
        type:'GET',
        url:"{{url('/api/inventory/peminjaman_terima_data/')}}"+'/'+id,
        success:function(data) {
            $('#terimaModal').modal('show');
            $('#peminjaman_kembali_id').val(data.id_peminjaman);
            $('#id_alat_uji').val(data.serial_number_id);
            $('#modal_display_kembali_nama').text(data.nama);
            $('#modal_display_kembali_tgl1').text(data.tgl_pinjam);
            $('#modal_display_kembali_tgl2').text(data.tgl_batas);
        }
    })
}
// ambil data untuk modal terima barang peminjaman end
</script>

@endsection
