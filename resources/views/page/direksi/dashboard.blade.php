@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Dashboard</h1>

@stop

@section('adminlte_css')
<style lang="scss">

    .fc-event-time {
        display: none;
    }

    section {
        font-size: 14px;
    }

    .hidden {
        display: none;
    }

    #pengirimantable thead {
        text-align: center;
    }

    #pengirimantable td:nth-child(5) {
        text-align: right;
        white-space: nowrap;
    }

    #pengirimantable td:nth-child(1),
    td:nth-child(4),
    td:nth-child(6) {
        text-align: center;
        white-space: nowrap;
    }

    .nowrap {
        white-space: nowrap;
    }

    .align-center {
        text-align: center;
    }

    #urgent {
        color: red;
    }

    #warning {
        color: #FFC700;
    }

    #info {
        color: #3a7bb0;
    }

    .fa-search:hover {
        color: #4682B4;
    }

    .fa-search:active {
        color: #C0C0C0;
    }

    .removeshadow {
        box-shadow: none;
    }

    .orange {
        background-color: #ff6600;
        color: #FFFFFF;
    }

    .yellow {
        background-color: #ffb31a;
        color: #FFFFFF;
    }

    .blue {
        background-color: #00bfff;
        color: #FFFFFF;
    }

    .red {
        background-color: #b30000;
        color: #FFFFFF;
    }

    .purple {
        background-color: #7D6378;
        color: #FFFFFF;
    }

    .green {
        background-color: #456600;
        color: #FFFFFF;
    }

    .midnightblue {
        background-color: #191970;
        color: #FFFFFF;
    }

    .link {
        color: #FFFFFF;
        text-decoration: none;
        background-color: none;
    }

    .blue-bg {
        background-color: #5F7A90;
    }

    @media screen and (max-width: 1440px) {
        #pengirimantable {
            font-size: 12px;
        }

        h4 {
            font-size: 20px;
        }

        #detailmodal {
            font-size: 12px;
        }

        .so-title {
            font-size: 14px;
        }
    }

    @media screen and (min-width: 1440px) {
        #pengirimantable {
            font-size: 12px;
        }

        h4 {
            font-size: 20px;
        }

        #detailmodal {
            font-size: 12px;
        }
    }

    #tableKerusakan_filter {
        display: none;
    }

    .active {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

</style>
@stop

@section('content')
<input type="hidden" id="auth" name="" value="{{ Auth::user()->divisi_id }}">
<link rel="stylesheet" href="{{ asset('vendor/fullcalendar/main.css') }}">
<script src="{{ asset('vendor/fullcalendar/main.js') }}"></script>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card removeshadow">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-search-dollar"></i> Penjualan</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="card-deck">
                                <div class="card">
                                    <div class="card-body">
                                        {{-- <div class="row"> --}}
                                            {{-- <div class="col-12"> --}}
                                                <h4><b>Grafik</b></h4>
                                                <div class="chart">
                                                    <canvas id="myChart"
                                                        style="position: relative; height:95vh; width:95vw;"></canvas>
                                                </div>
                                            {{-- </div> --}}
                                        {{-- </div> --}}
                                    </div>
                                </div>
                            {{-- </div>
                            <div class="col-lg-6 col-md-12 align-center"> --}}
                                <div class="card">
                                    <div class="card-body">
                                        <h4><b>Sales Order</b></h4>
                                        <div class="row">
                                            <div class="col-xl-8 col-lg-12 align-center">
                                                <div class="row">
                                                    <div class="col-lg-12 col-xl-6 py-2">
                                                        <div class="card h-100 purple">
                                                            <div class="card-body">
                                                                <h3 id="so_gudang">{{$gudang}}</h3>
                                                                <p class="so-title">SO Belum Diproses Gudang</p>
                                                            </div>
                                                            <div class="card-footer align-center"><a href="#"
                                                                    id="belumdikirim" class="link">Lihat Laporan <i
                                                                        class="fas fa-arrow-circle-right"></i></a></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-xl-6 py-2">
                                                        <div class="card h-100 yellow">
                                                            <div class="card-body ">
                                                                <h3 id="so_qc">{{$qc}}</h3>
                                                                <p class="so-title">SO Belum Diproses QC</p>
                                                            </div>
                                                            <div class="card-footer align-center"><a href="/qc/so/show"
                                                                    id="belumdikirim" class="link">Lihat Laporan <i
                                                                        class="fas fa-arrow-circle-right"></i></a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-xl-6 py-2">
                                                        <div class="card h-100 green">
                                                            <div class="card-body">
                                                                <h3 id="so_logistik">{{$log}}</h3>
                                                                <p class="so-title">SO Belum Diproses Logistik</p>
                                                            </div>
                                                            <div class="card-footer align-center">
                                                                <a href="/logistik/so/show" id="belumdikirim" class="link">Lihat Laporan <i class="fas fa-arrow-circle-right"></i></a></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-xl-6 py-2">
                                                        <div class="card h-100 midnightblue">
                                                            <div class="card-body">
                                                                <h3 id="so_dc">{{$dc}}</h3>
                                                                <p class="so-title">SO Belum Diproses DC</p>
                                                            </div>
                                                            <div class="card-footer align-center"><a href="/dc/so/show"
                                                                    id="belumdikirim" class="link">Lihat Laporan <i
                                                                        class="fas fa-arrow-circle-right"></i></a></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-xl-4 py-2">
                                                {{-- <div class="row">
                                                    <div class="col-lg-12 col-12 py-2"> --}}
                                                        <div class="card h-100 red">
                                                            <div class="card-body text-center">
                                                                <h3 id="so_dc">{{$penj}}</h3>
                                                                <p class="so-title">AKN Belum Memiliki SO</p>
                                                            </div>
                                                            <div class="card-footer align-center">
                                                                <a href="/penjualan/penjualan/show" id="belumdikirim" class="link">Lihat Laporan <i class="fas fa-arrow-circle-right"></i></a>
                                                            </div>
                                                        </div>
                                                    {{-- </div>
                                                </div> --}}
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-industry mr-1"></i>
                            Gudang
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <i class="fas fa-boxes mr-1"></i>
                                            Gudang Barang Jadi
                                        </h5>
                                        <div class="card-tools">
                                            <select name="" id="gbj" class="form-control">
                                                <option value="penjualan" selected>Penjualan</option>
                                                <option value="produk">Produk</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="card-body" style="font-size: 12px">
                                        <div class="row penjualangbj">
                                            <div class="col-sm-4">
                                                <div class="card" style="background-color: #E6EFFA">
                                                    <div class="card-body text-center">
                                                        <h4 id="he1">10</h4>
                                                        <p class="card-text">Produk Melewati Batas Transfer Lebih Dari 1
                                                            Hari</p>
                                                    </div>
                                                </div>
                                                <div class="card" style="background-color: #FEF7EA">
                                                    <div class="card-body text-center">
                                                        <h4 id="he2">50</h4>
                                                        <p class="card-text">Produk Melewati Batas Transfer Lebih Dari 2
                                                            Hari</p>
                                                    </div>
                                                </div>
                                                <div class="card" style="background-color: #FCEDE9">
                                                    <div class="card-body text-center">
                                                        <h4 id="he3">60</h4>
                                                        <p class="card-text">Produk Melewati Batas Transfer Lebih Dari 3
                                                            Hari</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <h5 class="text-center text-bold" style="font-size: 14px">Produk yang
                                                    tidak tersedia sesuai SO</h5>
                                                <table class="table table-striped table-jml-stok">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Produk</th>
                                                            <th>Permintaan</th>
                                                            <th>Stok Saat Ini</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row produkgbj hidden">
                                            <div class="col-sm-4">
                                                <div class="card">
                                                    <div class="card-body text-center"
                                                        style="background-color: #FEF7EA">
                                                        <h4 id="prd1">10</h4>
                                                        <p class="card-text">Produk dengan jumlah stok 10 sampai 20</p>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body text-center"
                                                        style="background-color: #FFBD67">
                                                        <h4 id="prd3">10</h4>
                                                        <p class="card-text">Produk dengan jumlah stok 1 sampai 4</p>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body text-center"
                                                        style="background-color: #FA8282">
                                                        <h4 id="prd4">10</h4>
                                                        <p class="card-text font-weight">Produk masuk 3 bulan sampai 6
                                                            bulan</p>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body text-center"
                                                        style="background-color: #FF6464">
                                                        <h4 id="prd7">10</h4>
                                                        <p class="card-text font-weight">Produk masuk lebih dari 3 tahun
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h6 class="text-center text-bold">Daftar Stok Layout</h6>
                                                        <hr>
                                                        <div class="row mb-3">
                                                            <div class="col-sm">
                                                                <h5><b>Layout 1</b></h5>
                                                            </div>
                                                            <div class="col-sm text-right">Layout :</div>
                                                            <div class="col-sm">
                                                                <select class="select2 form-control layout"
                                                                    multiple="multiple">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <table class="table tableStokLayout">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Nama Produk</th>
                                                                    <th>Jumlah</th>
                                                                    <th>Layout</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Gudang Karantina --}}
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title pb-3">
                                            Gudang Karantina <i class="fas fa-toolbox"></i>
                                        </h5>
                                    </div>
                                    <div class="card-body" style="font-size: 12px">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card" style="background-color: #D3E785">
                                                    <div class="card-body text-center">
                                                        <h4 id="h1">10</h4>
                                                        <p class="card-text">Produk dengan jumlah stok 3 sampai 4</p>
                                                    </div>
                                                </div>
                                                <div class="card" style="background-color: #FCFFCC">
                                                    <div class="card-body text-center">
                                                        <h4 id="h3">10</h4>
                                                        <p class="card-text">Produk dengan jumlah lebih dari 10</p>
                                                    </div>
                                                </div>
                                                <div class="card" id="produk-masuk-3-bulan"
                                                    style="background-color: #FFE1A1;">
                                                    <div class="card-body text-center">
                                                        <h4 id="h4">10</h4>
                                                        <p class="card-text font-weight">Produk masuk 3 bulan sampai 6
                                                            bulan</p>
                                                    </div>
                                                </div>
                                                <div class="card" id="produk-masuk-3-tahun"
                                                    style="background-color: #F95959;">
                                                    <div class="card-body text-center">
                                                        <h4 id="h7">10</h4>
                                                        <p class="card-text font-weight">Produk masuk lebih dari 3 tahun
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="col-xl-12 d-flex justify-content-end">
                                                    <span class="float-right filter">
                                                        <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false"
                                                            style="font-size: 12px">
                                                            <i class="fas fa-filter"></i> Filter Kerusakan
                                                        </button>
                                                        <div class="dropdown-menu" style="font-size: 12px">
                                                            <div class="px-3 py-3">
                                                                <div class="form-group">
                                                                    <label for="jenis_penjualan">Kerusakan</label>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            value="Level 1" id="level1" />
                                                                        <label class="form-check-label" for="jenis1">
                                                                            Level 1
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            value="Level 2" id="level2" />
                                                                        <label class="form-check-label" for="jenis2">
                                                                            Level 2
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            value="Level 3" id="level3" />
                                                                        <label class="form-check-label" for="jenis2">
                                                                            Level 3
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </span>&nbsp;
                                                    <span class="float-right filter">
                                                        <button class="btn btn-outline-primary" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false"
                                                            style="font-size: 12px">
                                                            <i class="fas fa-filter"></i> Filter Jenis
                                                        </button>
                                                        <div class="dropdown-menu" style="font-size: 12px">
                                                            <div class="px-3 py-3">
                                                                <div class="form-group">
                                                                    <label for="jenis_penjualan">Jenis</label>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            value="Sparepart" id="sparepart" />
                                                                        <label class="form-check-label" for="jenis1">
                                                                            Sparepart
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            value="Unit" id="unit" />
                                                                        <label class="form-check-label" for="jenis2">
                                                                            Unit
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </span>
                                                </div>
                                                <table class="table" id="tableKerusakan">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Produk</th>
                                                            <th>Jumlah</th>
                                                            <th>Tingkat Kerusakan</th>
                                                            <th>Jenis</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-cogs mr-1"></i> Produksi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Jadwal Perakitan</h5>
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <i class="fas fa-cogs mr-1"></i>
                                            Perakitan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6 col-md-4">
                                                <div id="bataswaktupenyerahan" class="card active otg"
                                                    style="background-color: #E6EFFA">
                                                    <div class="card-body text-center">
                                                        <h4 id="m4">10</h4>
                                                        <p class="card-text">Produk Mendekati Batas Waktu Penyerahan ke
                                                            GBJ</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div id="bataswaktuperakitan" class="card otg"
                                                    style="background-color: #FEF7EA">
                                                    <div class="card-body text-center">
                                                        <h4 id="m5">50</h4>
                                                        <p class="card-text">Produk Mendekati Batas Waktu Perakitan</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div id="perubahanperakitan" class="card otg"
                                                    style="background-color: #FCEDE9">
                                                    <div class="card-body text-center">
                                                        <h4 id="m6">60</h4>
                                                        <p class="card-text">Produk Mengalami Perubahan Jadwal Perakitan
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="produkGbj">
                                            <table class="table table-produk-gbj">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal Mulai</th>
                                                        <th>Tanggal Selesai</th>
                                                        <th>Nomor BPPB</th>
                                                        <th>Produk</th>
                                                        <th>Jumlah</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="produkPerakitan hidden">
                                            <table class="table table-waktu-perakitan">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal Mulai</th>
                                                        <th>Tanggal Selesai</th>
                                                        <th>Nomor BPPB</th>
                                                        <th>Produk</th>
                                                        <th>Jumlah</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <div class="perubahanPerakitan hidden">
                                            <table class="table table-perubahan-perakitan">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal Mulai</th>
                                                        <th>Tanggal Selesai</th>
                                                        <th>Nomor BPPB</th>
                                                        <th>Produk</th>
                                                        <th>Jumlah</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@section('adminlte_js')
<script>
    var access_token = localStorage.getItem('lokal_token');
    if (access_token == null) {
            Swal.fire({
                title: 'Session Expired',
                text: 'Silahkan login kembali',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.preventDefault();
                    document.getElementById('logout-form').submit();
                }
            })
        }
    $(function () {
        $('#divisitable').DataTable({});
        var pengirimantable = $('#pengirimantable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/ekatalog/pengiriman/data/',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'so',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'no_po',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'batas_kontrak',
                    className: 'nowrap-text align-center',
                },
                {
                    data: 'button',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
            ]
        })
    });
    $(function () {
        $(document).on('click', '.detailmodal', function (event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data("id");
            var label = $(this).data("target");
            $.ajax({
                url: href,
                beforeSend: function () {
                    $('#loader').show();
                },
                // return the result
                success: function (result) {
                    $('#detailmodal').modal("show");
                    $('#detail').html(result).show();
                    if (label == 'ekatalog') {
                        detailtabel_ekatalog(id);
                    } else if (label == 'spa') {
                        detailtabel_spa(id);
                    } else {
                        detailtabel_spb(id);
                    }
                },
                complete: function () {
                    $('#loader').hide();
                },
                error: function (jqXHR, testStatus, error) {
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

        function detailtabel_ekatalog(id) {
            $('#detailtabel').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/ekatalog/paket/detail/' + id,
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_produk',
                    },
                    {
                        data: 'harga',
                        render: $.fn.dataTable.render.number(',', '.', 2),
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'jumlah',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total',
                        render: $.fn.dataTable.render.number(',', '.', 2),
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'button',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                ],
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    // converting to interger to find total
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };
                    // computing column Total of the complete result
                    var jumlah_pesanan = api
                        .column(3)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    // computing column Total of the complete result
                    var total_pesanan = api
                        .column(4)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    var num_for = $.fn.dataTable.render.number(',', '.', 2).display;
                    $(api.column(0).footer()).html('Total');
                    $(api.column(3).footer()).html('Total');
                    $(api.column(4).footer()).html(num_for(total_pesanan));
                },
            })
        }

        // Produksi
        var date = new Date()
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear()

        var Calendar = FullCalendar.Calendar;
        var calendarEl = document.getElementById('calendar');

        var calendar = new Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            weekends: false,
            locale: 'id',
            events: function (fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: "/api/prd/ongoing-cal",
                    type: "post",
                    dataType: "json",
                    beforeSend : function(xhr){
                        xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                    },
                    success: function (res) {
                        var events = [];
                        if (res != null) {
                            $.each(res, function (i, item) {
                                events.push({
                                    start: item.tanggal_mulai,
                                    end: item.tanggal_selesai +
                                        'T23:59:59',
                                    title: item.produk.produk.nama +
                                        ' ' + item.produk.nama,
                                    backgroundColor: item.warna,
                                    borderColor: item.warna,
                                })
                            })
                        }
                        // console.log('events', events);
                        successCallback(events);
                    }
                })
            }
        });

        calendar.render();
    });
    $(document).ready(function () {
        $.ajax({
            url: "/api/penjualan/chart",
            method: "GET",
            success: function (data) {
                var ctx = document.getElementById("myChart");
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                            "Juli", "Agustus", "September", "Oktober", "November",
                            "Desember"
                        ],
                        datasets: [{
                                label: "E-Catalogue",
                                backgroundColor: "#7D6378",
                                data: [data.ekatalog_graph[1].count, data
                                    .ekatalog_graph[2].count, data.ekatalog_graph[3]
                                    .count, data.ekatalog_graph[4].count, data
                                    .ekatalog_graph[5].count, data.ekatalog_graph[6]
                                    .count, data.ekatalog_graph[7].count, data
                                    .ekatalog_graph[8].count, data.ekatalog_graph[9]
                                    .count, data.ekatalog_graph[10].count, data
                                    .ekatalog_graph[11].count, data.ekatalog_graph[
                                        12].count
                                ],
                                borderColor: '#7D6378',
                            },
                            {
                                label: "SPA",
                                backgroundColor: "#EA8B1B",
                                data: [data.spa_graph[1].count, data.spa_graph[2].count,
                                    data.spa_graph[3].count, data.spa_graph[4]
                                    .count, data.spa_graph[5].count, data.spa_graph[
                                        6].count, data.spa_graph[7].count, data
                                    .spa_graph[8].count, data.spa_graph[9].count,
                                    data.spa_graph[10].count, data.spa_graph[11]
                                    .count, data.spa_graph[12].count
                                ],
                                borderColor: '#EA8B1B',
                            },
                            {
                                label: "SPB",
                                backgroundColor: "#5F7A90",
                                data: [data.spb_graph[1].count, data.spb_graph[2].count,
                                    data.spb_graph[3].count, data.spb_graph[4]
                                    .count, data.spb_graph[5].count, data.spb_graph[
                                        6].count, data.spb_graph[7].count, data
                                    .spb_graph[8].count, data.spb_graph[9].count,
                                    data.spb_graph[10].count, data.spb_graph[11]
                                    .count, data.spb_graph[12].count
                                ],
                                borderColor: '#5F7A90',
                            }
                        ]
                    },
                    options: {
                        // animations: {
                        //     tension: {
                        //         duration: 4000,
                        //         easing: 'linear',
                        //         from: 1,
                        //         to: 0,
                        //         loop: true
                        //     }
                        // },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Grafik Penjualan Tahunan'
                            }
                        },
                        scales: {
                            // y: { // defining min and max so hiding the dataset does not change scale range
                            //     min: 0,
                            //     max: 2,
                            //     stepSize: 1,
                            // }
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        });

        // GBJ
        // Penjualan
        $('.penjualangbj').removeClass('hidden');
        $('.produkgbj').addClass('hidden');
        $.ajax({
            url: "/api/dashboard-gbj/list1/h",
            type: "post",
            success: function (res) {
                $('h4#he1').text(res);
            }
        })

        $.ajax({
            url: "/api/dashboard-gbj/list2/h",
            type: "post",
            success: function (res) {
                $('h4#he2').text(res);
            }
        });
        $.ajax({
            url: "/api/dashboard-gbj/list/h",
            type: "post",
            success: function (res) {
                $('h4#he3').text(res);
            }
        });
        $('.table-jml-stok').DataTable({
            dom: 'frtip',
            processing: true,
            serverSide: true,
            autoWidth: false,
            "lengthChange": false,
            pageLength: 7,
            ajax: {
                url: '/api/dashboard-gbj/list-all',
                type: "post",
                beforeSend : function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                }
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'produk'
                },
                {
                    data: 'permintaan'
                },
                {
                    data: 'current_stok'
                },
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        // Produk
        $('.tableStokLayout').DataTable({
            dom: 'frtip',

            processing: true,
            serverSide: true,
            autoWidth: false,
            searching: false,
            "lengthChange": false,
            pageLength: 5,
            ajax: {
                url: '/api/dashboard-gbj/byproduct',
                type: "get",
                beforeSend : function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                }
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'prd'
                },
                {
                    data: 'jml'
                },
                {
                    data: 'layout'
                },
                // {data: 'action'},
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $.ajax({
            url: '/api/gbj/sel-layout',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res) {
                    $(".layout").empty();
                    $(".layout").append(
                        '<option value="" selected>All Layout</option>');
                    $.each(res, function (key, value) {
                        $(".layout").append('<option value="' + value
                            .ruang + '">' + value.ruang + '</option');
                    });
                } else {
                    $(".layout").empty();
                }
            }
        });
        $.ajax({
            url: "/api/dashboard-gbj/stok/1020/h",
            success: function (res) {
                $('h4#prd1').text(res);
            }
        })

        $.ajax({
            url: "/api/dashboard-gbj/stok/14/h",
            success: function (res) {
                $('h4#prd3').text(res);
            }
        })

        $.ajax({
            url: "/api/dashboard-gbj/in/36/h",
            type: "post",
            success: function (res) {
                $('h4#prd4').text(res);
            }
        })

        $.ajax({
            url: "/api/dashboard-gbj/in/36plus/h",
            type: "post",
            success: function (res) {
                $('h4#prd7').text(res);
            }
        })
        $('#gbj').change(function (e) {
            if ($(this).val() == 'penjualan') {
                $('.penjualangbj').removeClass('hidden');
                $('.produkgbj').addClass('hidden');
            } else if ($(this).val() == 'produk') {
                $('.penjualangbj').addClass('hidden');
                $('.produkgbj').removeClass('hidden');
                $('.select2').select2();
            }
        });

        // GK
        $.ajax({
            url: "/api/gk/dashboard/stok/34/h",
            type: "post",
            success: function (res) {
                $('h4#h1').text(res);
            }
        })
        $.ajax({
            url: "/api/gk/dashboard/stok/10/h",
            type: "post",
            success: function (res) {
                $('h4#h3').text(res);
            }
        })
        $.ajax({
            url: "/api/gk/dashboard/in/36/h",
            type: "post",
            success: function (res) {
                $('h4#h4').text(res);
            }
        })
        $.ajax({
            url: "/api/gk/dashboard/in/36plus/h",
            type: "post",
            success: function (res) {
                $('h4#h7').text(res);
            }
        })

        $('#sparepart').click(function () {
            if ($(this).prop('checked') == true) {
                table.column(3).search($(this).val()).draw();
            } else {
                table.column(3).search('').draw();
            }
        })

        $('#unit').click(function () {
            if ($(this).prop('checked') == true) {
                table.column(3).search($(this).val()).draw();
            } else {
                table.column(3).search('').draw();
            }
        })

        $('#level1').click(function () {
            if ($(this).prop('checked') == true) {
                table.column(2).search($(this).val()).draw();
            } else {
                table.column(2).search('').draw();
            }
        })
        $('#level2').click(function () {
            if ($(this).prop('checked') == true) {
                table.column(2).search($(this).val()).draw();
            } else {
                table.column(2).search('').draw();
            }
        })
        $('#level3').click(function () {
            if ($(this).prop('checked') == true) {
                table.column(2).search($(this).val()).draw();
            } else {
                table.column(2).search('').draw();
            }
        })

        var table = $('#tableKerusakan').DataTable({
            dom: 'frtip',
            processing: true,
            serverSide: true,
            autoWidth: false,
            pageLength: 5,
            ajax: {
                url: "/api/gk/dashboard/tingkat",
                type: "post",
                beforeSend : function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                }
            },
            columns: [{
                    data: 'produk'
                },
                {
                    data: 'jumlah'
                },
                {
                    data: 'tingkat'
                },
                {
                    data: 'jenis'
                },
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
    });
    // rakit
    $.ajax({
        url: "/api/prd/exp_rakit/h",
        type: "post",
        success: function (res) {
            console.log(res);
            $('h4#m4').text(res);
        }
    })

    $('.table-produk-gbj').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        dom: "Bfrtip",
        ajax: {
            url: "/api/prd/exp_rakit",
            type: "post",
            beforeSend : function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
            }
        },
        columns: [{
                data: 'start'
            },
            {
                data: 'end'
            },
            {
                data: 'no_bppb'
            },
            {
                data: 'produk'
            },
            {
                data: 'jml'
            },
            {
                data: 'button1'
            }
        ],
        columnDefs: [{
            targets: [5],
            "visible": document.getElementById('auth').value == '2' ? false : true
        }, {
            targets: [3],
            className: 'text-center'
        }],
        "ordering": false,
        "autoWidth": false,
        "lengthChange": false,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
    });

    $.ajax({
        url: "/api/prd/exp_rakit/h",
        type: "post",
        success: function (res) {
            console.log(res);
            $('h4#m5').text(res);
        }
    })

    $('.table-waktu-perakitan').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        dom: "Bfrtip",
        ajax: {
            url: "/api/prd/exp_rakit",
            type: "post",
            beforeSend : function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
            }
        },
        columns: [{
                data: 'start'
            },
            {
                data: 'end'
            },
            {
                data: 'no_bppb'
            },
            {
                data: 'produk'
            },
            {
                data: 'jml'
            },
            {
                data: 'button'
            }
        ],
        columnDefs: [{
            targets: [5],
            "visible": document.getElementById('auth').value == '2' ? false : true
        }, {
            targets: [3],
            className: 'text-center'
        }],
        "ordering": false,
        "autoWidth": false,
        "lengthChange": false,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
    });

    $.ajax({
        url: "/api/prd/exp_jadwal/h",
        type: "post",
        success: function (res) {
            console.log(res);
            $('h4#m6').text(res);
        }
    })

    $('.table-perubahan-perakitan').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        dom: "Bfrtip",
        ajax: {
            url: "/api/prd/exp_jadwal",
            type: "post",
            beforeSend : function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
            }
        },
        columns: [{
                data: 'start'
            },
            {
                data: 'end'
            },
            {
                data: 'no_bppb'
            },
            {
                data: 'produk'
            },
            {
                data: 'jml'
            },
            {
                data: 'button'
            }
        ],
        columnDefs: [{
            targets: [5],
            "visible": document.getElementById('auth').value == '2' ? false : true
        }, {
            targets: [3],
            className: 'text-center'
        }],
        "ordering": false,
        "autoWidth": false,
        "lengthChange": false,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
    });
    $(document).on('click', '#bataswaktupenyerahan', function () {
        $('#bataswaktupenyerahan').addClass('active');
        $('#bataswaktuperakitan').removeClass('active');
        $('#perubahanperakitan').removeClass('active');
        $('.produkGbj').removeClass('hidden');
        $('.produkPerakitan').addClass('hidden');
        $('.perubahanPerakitan').addClass('hidden');
    });

    $(document).on('click', '#bataswaktuperakitan', function () {
        $('#bataswaktuperakitan').addClass('active');
        $('#bataswaktupenyerahan').removeClass('active');
        $('#perubahanperakitan').removeClass('active');
        $('.produkPerakitan').removeClass('hidden');
        $('.produkGbj').addClass('hidden');
        $('.perubahanPerakitan').addClass('hidden');
    });

    $(document).on('click', '#perubahanperakitan', function () {
        $('#perubahanperakitan').addClass('active');
        $('#bataswaktupenyerahan').removeClass('active');
        $('#bataswaktuperakitan').removeClass('active');
        $('.perubahanPerakitan').removeClass('hidden');
        $('.produkGbj').addClass('hidden');
        $('.produkPerakitan').addClass('hidden');
    });

</script>
@stop
