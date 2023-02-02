@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Penjualan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '26')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Penjualan</li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Penjualan</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        .modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }

        table {
            border-collapse: collapse;
            empty-cells: show;
        }

        td {
            position: relative;
        }

        .foo {
            border-radius: 50%;
            float: left;
            width: 10px;
            height: 10px;
            align-items: center !important;
        }

        tr.line-through td:not(:nth-last-child(-n+2)):before {
            content: " ";
            position: absolute;
            left: 0;
            top: 35%;
            border-bottom: 1px solid;
            width: 100%;
        }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .separator {
            border-top: 1px solid #bbb;
            width: 90%;
        }

        .wb {
            word-break: break-all;
            white-space: normal;
        }

        .nowraptxt {
            white-space: nowrap;
        }

        .filter {
            margin: 5px;
        }

        thead {
            text-align: center;
        }

        td {
            text-align: center;
            white-space: nowrap;
        }

        #urgent {
            color: #dc3545;
            font-weight: 600;
        }

        #warning {
            color: #FFC700;
            font-weight: 600;
        }

        #info {
            color: #3a7bb0;
            font-weight: 600;
        }

        .minimizechar {
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 13ch;
        }

        .hide {
            display: none;
        }

        .dropdown-toggle:hover {
            color: #4682B4;
        }

        .dropdown-toggle:active {
            color: #C0C0C0;
        }

        td.details-control {
            content: "\f055";
            font-family: FontAwesome;
            left: -5px;
            position: absolute;
            top: 0;
        }

        tr.details td.details-control {
            background: url('../resources/details_close.png') no-repeat center center;
        }

        #detailekat {
            background-color: #E9DDE5;

        }

        #detailspa {
            background-color: #FFE6C9;
        }

        #detailspb {
            background-color: #E1EBF2;
            /* color: #7D6378; */

        }

        .tabnum {
            font-variant-numeric: tabular-nums;
        }

        .removeshadow {
            box-shadow: none;
        }

        .align-center {
            text-align: center;
        }

        .bordertopnone {
            border-top: 0;
            border-left: 0;
            border-right: 0;
            border-bottom: 0;
            vertical-align: top;
        }

        .margin {
            margin-left: 10px;
            margin-right: 10px;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .card-detail {
            align-items: center;
            flex-direction: row;
            shadow: none;
            border: none;
        }

        .card-detail img {
            width: 25%;
            border-top-right-radius: 0;
            border-bottom-left-radius: calc(0.25rem - 1px);
        }

        /* .overflowcard {
                                                                                                                                                                                                                                                                                                                        max-height:
                                                                                                                                                                                                                                                                                                                        700px;
                                                                                                                                                                                                                                                                                                                    } */

        .bg-chart-light {
            background: rgba(192, 192, 192, 0.2);
        }

        .bg-chart-orange {
            background: rgb(236, 159, 5);
        }

        .bg-chart-yellow {
            background: rgb(255, 221, 0);
        }

        .bg-chart-green {
            background: rgb(11, 171, 100);
        }

        .bg-chart-blue {
            background: rgb(8, 126, 225);
        }

        @media screen and (min-width: 1440px) {

            body {
                font-size: 14px;
            }

            #detailmodal {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .overflowcard {
                max-height:
                    550px;
                width: auto;
                overflow-y: scroll;
                box-shadow: none;
            }

            .labelket {
                text-align: right;
            }
        }

        @media screen and (max-width: 1439px) {
            body {
                font-size: 12px;
            }

            h4 {
                font-size: 20px;
            }

            #detailmodal {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .overflowcard {
                max-height: 500px;
                width: auto;
                overflow-y: scroll;
                box-shadow: none;
            }

            .labelket {
                text-align: right;
            }
        }

        @media screen and (max-width: 991px) {
            .labelket {
                text-align: left;
            }

            .overflowcard {
                max-height: 150px;
                width: auto;
                overflow-y: scroll;
                box-shadow: none;
            }
        }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="col-12">
                <div class="row">
                    <div id="auth" class="hide">{{ Auth::user()->Karyawan->divisi_id }}</div>
                    <div class="col-12">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="ekatalog-tab" data-toggle="tab" href="#ekatalog"
                                    role="tab" aria-controls="ekatalog" aria-selected="false">E-Catalogue</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="spa-tab" data-toggle="tab" href="#spa" role="tab"
                                    aria-controls="spa" aria-selected="false">SPA</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="spb-tab" data-toggle="tab" href="#spb" role="tab"
                                    aria-controls="spb" aria-selected="false">SPB</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link " id="semua-penjualan-tab" data-toggle="tab" href="#semua-penjualan"
                                    role="tab" aria-controls="semua-penjualan" aria-selected="true">Penjualan</a>
                            </li>
                        </ul>
                        <div class="tab-content card" id="myTabContent">
                            <div class="tab-pane fade show active card-body" id="ekatalog" role="tabpanel"
                                aria-labelledby="ekatalog-tab">
                                <div class="row">
                                    {{-- <div class="col-4">
                                        <span class="float-left filter">
                                            <select class="form-control jenis_paket" name="jenis_paket" id="jenis_paket">
                                                <option value="AK1-" selected>AK1-
                                                </option>
                                                <option value="FKS-">FKS-</option>
                                            </select>
                                        </span>
                                    </div> --}}
                                    <div class="col-12">
                                        @if (Auth::user()->Karyawan->divisi_id == '26')
                                            <span class="float-right filter">
                                                <a href="{{ route('penjualan.penjualan.create') }}"><button
                                                        class="btn btn-outline-info">
                                                        <i class="fas fa-plus"></i> Tambah
                                                    </button>
                                                </a>
                                            </span>
                                        @endif
                                        <span class="float-right filter">
                                            <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-filter"></i> Filter
                                            </button>
                                            <form id="filter_ekat">
                                                <div class="dropdown-menu">
                                                    <div class="px-3 py-3">
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Database</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    value="2023" id="status2" name="data_tahun"
                                                                    checked />
                                                                <label class="form-check-label" for="status2">
                                                                    2023
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    value="2022" id="status2" name="data_tahun" />
                                                                <label class="form-check-label" for="status2">
                                                                    2022
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Status</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="sepakat" id="status1"
                                                                    name="status_ekatalog[]" />
                                                                <label class="form-check-label" for="status1">
                                                                    Sepakat
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="negosiasi" id="status2"
                                                                    name="status_ekatalog[]" />
                                                                <label class="form-check-label" for="status2">
                                                                    Negosiasi
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="batal" id="status3"
                                                                    name="status_ekatalog[]" />
                                                                <label class="form-check-label" for="status3">
                                                                    Batal
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="draft" id="status4"
                                                                    name="status_ekatalog[]" />
                                                                <label class="form-check-label" for="status4">
                                                                    Draft
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <span class="float-right">
                                                                <button class="btn btn-primary" type="submit"
                                                                    id="filter_ekatalog">
                                                                    Cari
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="ekatalogtable" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>No Urut</th>
                                                        <th>Nomor SO</th>
                                                        <th>Nomor AKN</th>
                                                        <th>Nomor PO</th>
                                                        <th>Tanggal Buat</th>
                                                        <th>Tanggal Edit</th>
                                                        <th>Tanggal Delivery</th>
                                                        <th>Customer</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade card-body" id="spa" role="tabpanel"
                                aria-labelledby="spa-tab">
                                <div class="row">
                                    <div class="col-12">
                                        @if (Auth::user()->Karyawan->divisi_id == '26')
                                            <span class="float-right filter">
                                                <a href="{{ route('penjualan.penjualan.create') }}"><button
                                                        class="btn btn-outline-info">
                                                        <i class="fas fa-plus"></i> Tambah
                                                    </button>
                                                </a>
                                            </span>
                                        @endif
                                        <span class="float-right filter">
                                            <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-filter"></i> Filter
                                            </button>
                                            <form id="filter_spa">
                                                <div class="dropdown-menu">
                                                    <div class="px-3 py-3">
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Database</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    value="2023" id="status2" name="data_tahun_spa"
                                                                    checked />
                                                                <label class="form-check-label" for="status2">
                                                                    2023
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    value="2022" id="status2"
                                                                    name="data_tahun_spa" />
                                                                <label class="form-check-label" for="status2">
                                                                    2022
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Status</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="7" id="status1" name="status_spa[]" />
                                                                <label class="form-check-label" for="status1">
                                                                    Penjualan
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="9" id="status4" name="status_spa[]" />
                                                                <label class="form-check-label" for="status4">
                                                                    PO
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="6" id="status5" name="status_spa[]" />
                                                                <label class="form-check-label" for="status5">
                                                                    Gudang
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="8" id="status6" name="status_spa[]" />
                                                                <label class="form-check-label" for="status6">
                                                                    QC
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="11" id="status8" name="status_spa[]" />
                                                                <label class="form-check-label" for="status8">
                                                                    Kirim
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <span class="float-right">
                                                                <button class="btn btn-primary" id="filter_spa"
                                                                    type="submit">
                                                                    Cari
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="spatable" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor SO</th>
                                                        <th>Nomor PO</th>
                                                        <th>Tanggal Order</th>
                                                        <th>Customer</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade card-body" id="spb" role="tabpanel"
                                aria-labelledby="spb-tab">
                                <div class="row">
                                    <div class="col-12">
                                        @if (Auth::user()->Karyawan->divisi_id == '26')
                                            <span class="float-right filter">
                                                <a href="{{ route('penjualan.penjualan.create') }}"><button
                                                        class="btn btn-outline-info">
                                                        <i class="fas fa-plus"></i> Tambah
                                                    </button>
                                                </a>
                                            </span>
                                        @endif
                                        <span class="float-right filter">
                                            <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-filter"></i> Filter
                                            </button>
                                            <form id="filter_spb">
                                                <div class="dropdown-menu">
                                                    <div class="px-3 py-3">
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Database</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    value="2023" id="status2" name="data_tahun_spb"
                                                                    checked />
                                                                <label class="form-check-label" for="status2">
                                                                    2023
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    value="2022" id="status2"
                                                                    name="data_tahun_spb" />
                                                                <label class="form-check-label" for="status2">
                                                                    2022
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Status</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="7" id="status_spb1"
                                                                    name="status_spb[]" />
                                                                <label class="form-check-label" for="status_spb1">
                                                                    Penjualan
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="9" id="status_spb2"
                                                                    name="status_spb[]" />
                                                                <label class="form-check-label" for="status_spb2">
                                                                    PO
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="6" id="status_spb3"
                                                                    name="status_spb[]" />
                                                                <label class="form-check-label" for="status_spb3">
                                                                    Gudang
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="8" id="status_spb4"
                                                                    name="status_spb[]" />
                                                                <label class="form-check-label" for="status_spb4">
                                                                    QC
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="11" id="status_spb6"
                                                                    name="status_spb[]" />
                                                                <label class="form-check-label" for="status_spb6">
                                                                    Kirim
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <span class="float-right">
                                                                <button class="btn btn-primary" id="filter_spb"
                                                                    type="submit">
                                                                    Cari
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">

                                        <div class="table-responsive">

                                            <table class="table table-hover" id="spbtable" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor SO</th>
                                                        <th>Nomor PO</th>
                                                        <th>Tanggal Order</th>
                                                        <th>Customer</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade  card-body" id="semua-penjualan" role="tabpanel"
                                aria-labelledby="semua-penjualan-tab">
                                <div class="row">
                                    <div class="col-12">
                                        @if (Auth::user()->Karyawan->divisi_id == '26')
                                            <span class="float-right filter">
                                                <a href="{{ route('penjualan.penjualan.create') }}"><button
                                                        class="btn btn-outline-info">
                                                        <i class="fas fa-plus"></i> Tambah
                                                    </button>
                                                </a>
                                            </span>
                                        @endif
                                        <span class="float-right filter">
                                            <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-filter"></i> Filter
                                            </button>
                                            <form id="filter_penjualan">
                                                <div class="dropdown-menu">
                                                    <div class="px-3 py-3">
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Database</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    value="2023" id="status2" name="data_tahun_pen"
                                                                    checked />
                                                                <label class="form-check-label" for="status2">
                                                                    2023
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    value="2022" id="status2"
                                                                    name="data_tahun_pen" />
                                                                <label class="form-check-label" for="status2">
                                                                    2022
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Jenis Penjualan</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="ekatalog" name="jenis_penjualan[]"
                                                                    id="jenis1" />
                                                                <label class="form-check-label" for="jenis1">
                                                                    E-Catalogue
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="spa" name="jenis_penjualan[]"
                                                                    id="jenis2" />
                                                                <label class="form-check-label" for="jenis2">
                                                                    SPA
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="spb" name="jenis_penjualan[]"
                                                                    id="jenis3" />
                                                                <label class="form-check-label" for="jenis3">
                                                                    SPB
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Status</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="7" name="status_penjualan[]"
                                                                    id="status3" />
                                                                <label class="form-check-label" for="status3">
                                                                    Penjualan
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="9" name="status_penjualan[]"
                                                                    id="status4" />
                                                                <label class="form-check-label" for="status4">
                                                                    PO
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="6" name="status_penjualan[]"
                                                                    id="status5" />
                                                                <label class="form-check-label" for="status5">
                                                                    Gudang
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="8" name="status_penjualan[]"
                                                                    id="status6" />
                                                                <label class="form-check-label" for="status6">
                                                                    QC
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="11" name="status_penjualan[]"
                                                                    id="status8" />
                                                                <label class="form-check-label" for="status8">
                                                                    Logistik
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <span class="float-right">
                                                                <button class="btn btn-primary" id="filter_penjualan"
                                                                    type="submit">
                                                                    Cari
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="penjualantable" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor SO</th>
                                                        <th>Nomor AKN</th>
                                                        <th>Nomor PO</th>
                                                        <th>Tanggal PO</th>
                                                        <th>Tanggal Delivery</th>
                                                        <th>Customer</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
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

            <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal"
                aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-warning">
                            <h4 id="modal-title">Edit Pesanan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="edit">

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="detailmodal" tabindex="-1" role="dialog" aria-labelledby="detailmodal"
                aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header">
                            <h4 id="modal-title">Detail</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="detail">

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="deletemodal"
                aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header">
                            <h4 id="modal-title">Hapus</h4>
                        </div>
                        <form method="post" action="" id="form-delete" data-target="">
                            @method('DELETE')
                            @csrf
                            <div class="modal-body" id="delete">
                                <div class="row">
                                    <div class="col-12">Apakah Anda yakin ingin menghapus data ini?
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <span class="float-left">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                </span>
                                <span class="float-right">
                                    <button type="submit" class="btn btn-danger " id="btnhapus">Hapus</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('adminlte_js')
    <script>
        $(function() {
            document.querySelector('#spa-tab').addEventListener('click', spa_show);
            document.querySelector('#spb-tab').addEventListener('click', spb_show);
            document.querySelector('#semua-penjualan-tab').addEventListener('click', p_show);

            ekat_show();

            function tbldetailpesanan() {
                $('#tabledetailpesan').DataTable({
                    "scrollX": false
                });
            }
            var divisi_id = "{{ Auth::user()->Karyawan->divisi_id }}";

            function p_show() {
                var penjualantable = $('#penjualantable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': "/api/penjualan/penjualan/data/semua/semua/" +
                            {{ \Carbon\Carbon::now()->year }},
                        "dataType": "json",
                        'type': 'POST',
                        "headers": {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
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
                    }, {
                        data: 'so',
                    }, {
                        data: 'no_paket',
                    }, {
                        data: 'nopo',
                    }, {
                        data: 'tgl_order',
                    }, {
                        data: 'tgl_kontrak',
                    }, {
                        data: 'nama_customer',
                    }, {
                        data: 'status',
                    }, {
                        data: 'button',
                        orderable: false,
                        searchable: false
                    }, ]
                });
            }

            function ekat_show() {
                var ekatalogtable = $('#ekatalogtable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/penjualan/penjualan/ekatalog/data/semua/' +
                            {{ \Carbon\Carbon::now()->year }},
                        "dataType": "json",
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                            data: 'no_urut',
                        },
                        {
                            data: 'so',
                        },
                        {
                            data: 'no_paket',
                        },
                        {
                            data: 'nopo',
                        },
                        {
                            data: 'tgl_buat',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'tgl_edit',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'tgl_kontrak',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_customer',
                        },
                        {
                            data: 'status',
                        },
                        {
                            data: 'button',
                            orderable: false,
                            searchable: false
                        },

                    ],


                });
            }

            function spa_show() {
                var spatable = $('#spatable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/penjualan/penjualan/spa/data/semua/' +
                            {{ \Carbon\Carbon::now()->year }},
                        "dataType": "json",
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                        },
                        {
                            data: 'nopo'
                        },
                        {
                            data: 'tglpo',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_customer'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: 'button',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            }

            function spb_show() {
                var spbtable = $('#spbtable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/penjualan/penjualan/spb/data/semua/' +
                            {{ \Carbon\Carbon::now()->year }},
                        "dataType": "json",
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                        },
                        {
                            data: 'nopo'
                        },
                        {
                            data: 'tglpo',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_customer'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: 'button',
                            orderable: false,
                            searchable: false
                        }
                    ],
                });
            }
        })
    </script>
    <script>
        $(function() {
            function validasi_batal() {
                if ($('#tanggal').val() != "" && $('#alasan').val() != "") {
                    $('#btnkirimbatal').attr('disabled', false);
                } else {
                    $('#btnkirimbatal').attr('disabled', true);
                }
            }

            $(document).on('keyup change', '#tanggal', function() {
                validasi_batal();
            });

            $(document).on('keyup change', '#alasan', function() {
                validasi_batal();
            });

            var optionpie = {
                type: 'pie',
                data: {
                    labels: [
                        '-',
                    ],
                    datasets: [{
                        label: 'STATUS PESANAN',
                        data: [1],
                        backgroundColor: [
                            'rgba(192, 192, 192, 0.2)',
                        ],
                        hoverOffset: 4
                    }]
                }
            }

            function update_chart(gudang, qc, log, ki) {
                const ctx = $('#myChart');
                const myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: [
                            'Gudang',
                            'QC',
                            'Logistik',
                            'Kirim',
                        ],
                        datasets: [{
                            label: 'STATUS PESANAN',
                            data: [gudang, qc, log, ki],
                            backgroundColor: [

                                'rgb(236, 159, 5)',
                                'rgb(255, 221, 0)',
                                'rgb(11, 171, 100)',
                                'rgb(8, 126, 225)'
                            ],
                            hoverOffset: 4
                        }]
                    }
                });
            }

            var optionpie = {
                type: 'pie',
                data: {
                    labels: [
                        '-',
                    ],
                    datasets: [{
                        label: 'STATUS PESANAN',
                        data: [0],
                        backgroundColor: [
                            'rgba(192, 192, 192, 0.2)',
                        ],
                        hoverOffset: 4
                    }]
                }
            }

            function update_chart(produk, gudang, qc, log, ki) {
                const ctx = $('#myChart');
                if (produk == 'part') {
                    const myChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: [
                                'QC',
                                'Logistik',
                                'Kirim',
                            ],
                            datasets: [{
                                label: 'STATUS PESANAN',
                                data: [qc, log, ki],
                                backgroundColor: [
                                    'rgb(255, 221, 0)',
                                    'rgb(11, 171, 100)',
                                    'rgb(8, 126, 225)'
                                ],
                                hoverOffset: 4
                            }]
                        }
                    });
                } else {
                    const myChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: [
                                'Gudang',
                                'QC',
                                'Logistik',
                                'Kirim',
                            ],
                            datasets: [{
                                label: 'STATUS PESANAN',
                                data: [gudang, qc, log, ki],
                                backgroundColor: [

                                    'rgb(236, 159, 5)',
                                    'rgb(255, 221, 0)',
                                    'rgb(11, 171, 100)',
                                    'rgb(8, 126, 225)'
                                ],
                                hoverOffset: 4
                            }]
                        }
                    });
                }

            }







            $(document).on('click', '.detailmodal', function(event) {
                event.preventDefault();
                var href = $(this).attr('data-attr');
                var id = $(this).data("id");
                var label = $(this).data("target");
                $.ajax({
                    url: href,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#detailmodal').modal("show");
                        $('#detail').html(result).show();

                        if (label == 'ekatalog') {
                            $('#detailmodal').find(".modal-header").removeClass(
                                'bg-orange bg-lightblue');
                            $('#detailmodal').find(".modal-header").addClass('bg-purple');
                            $('#detailmodal').find(".modal-header > h4").text('E-Catalogue');

                            detailtabel_ekatalog(id);
                        } else if (label == 'spa') {
                            $('#detailmodal').find(".modal-header").removeClass(
                                'bg-purple bg-lightblue');
                            $('#detailmodal').find(".modal-header").addClass('bg-orange');
                            $('#detailmodal').find(".modal-header > h4").text('SPA');
                            detailtabel_spa(id);
                        } else {
                            // const ctx = $('#myChart');
                            // const myChart = new Chart(ctx, optionpie);

                            // var chart = new ApexCharts(document.querySelector("#chartproduk"), options);
                            // chart.render();
                            // $('#detailmodal').find(".modal-header").attr('id', '');
                            // $('#detailmodal').find(".modal-header").attr('id', 'detailspb');
                            $('#detailmodal').find(".modal-header").removeClass(
                                'bg-orange bg-purple');
                            $('#detailmodal').find(".modal-header").addClass('bg-lightblue');
                            $('#detailmodal').find(".modal-header > h4").text('SPB');
                            detailtabel_spb(id);
                        }

                        $('#detailmodal').find('[data-toggle="tooltip"]').tooltip();
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page " + href + " cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

            $(document).on('click', '.editmodal', function(event) {
                event.preventDefault();
                var jenis = $(this).attr('data-jenis');
                var id = $(this).attr("data-id");
                $.ajax({
                    url: '/penjualan/pesanan/edit/' + id + '/' + jenis,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#editmodal').modal("show");
                        $('#edit').html(result).show();
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page " + '/penjualan/pesanan/edit/' + id + '/' + jenis +
                            " cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-danger margin',
                    cancelButton: 'btn btn-outline-secondary margin'
                },
                buttonsStyling: false
            })

            $(document).on('click', '.batalmodal', function(event) {
                event.preventDefault();
                var jenis = $(this).attr('data-jenis');
                var id = $(this).attr("data-id");
                console.log('/penjualan/penjualan/cancel/' + id + '/' + jenis);
                $.ajax({
                    url: '/penjualan/penjualan/cancel/' + id + '/' + jenis,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#detailmodal').modal("show");
                        $('#detail').html(result).show();
                        $('#detailmodal').find(".modal-header").removeClass(
                            'bg-purple bg-orange bg-lightblue');
                        $('#detailmodal').find(".modal-header").addClass('bg-dark');
                        $('#detailmodal').find(".modal-header > h4").text('Pesanan Batal');
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

            $(document).on('submit', '#btnkirimbatal', function(event) {
                event.preventDefault();
                var alasan = $(this).find('#alasan').val();
                var jenis = $(this).find('#jenis').val();
                var id = $(this).find('#id').val();

                swal({
                    title: "Batalkan Pesanan",
                    text: "Apakah anda yakin ingin membatalkan pesanan?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Batalkan',
                    cancelButtonText: 'Tutup',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: '/api/penjualan/penjualan/cancel/' + id + '/' + jenis,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response['data'] == "success") {
                                    swal.fire(
                                        'Batal',
                                        'Sukses Batalkan Pesanan',
                                        'success'
                                    );
                                    $('#penjualantable').DataTable().ajax.reload();
                                    if (jenis == 'spa') {
                                        $('#spatable').DataTable().ajax.reload();
                                    } else if (jenis == 'spb') {
                                        $('#spbtable').DataTable().ajax.reload();
                                    }
                                    $("#deletemodal").modal('hide');
                                } else if (response['data'] == "error") {
                                    swal.fire(
                                        'Gagal',
                                        'Gagal melakukan Hapus Data',
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                swal.fire(
                                    'Error',
                                    'Data telah digunakan dalam Transaksi Lain',
                                    'warning'
                                );
                                // console.log(action);
                            }
                        });
                        return false;
                        swalWithBootstrapButtons.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }
                })
            });

            $(document).on('submit', '#form-pesanan-update', function(e) {
                e.preventDefault();
                var action = $(this).attr('action');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: action,
                    data: $('#form-pesanan-update').serialize(),
                    beforeSend: function() {
                        swal.fire({
                            title: 'Sedang Proses',
                            html: 'Loading...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading()
                            }
                        })
                    },
                    success: function(response) {
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan Perubahan Pesanan',
                                'success'
                            );
                            $("#editmodal").modal('hide');
                            location.reload();
                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan Perubahan Pesanan',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("Page cannot open. Error:" + error);
                    }
                });
                return false;
            });

            $(document).on('submit', '#btnkirimbatal', function(event) {
                event.preventDefault();
                var alasan = $(this).find('#alasan').val();
                var jenis = $(this).find('#jenis').val();
                var id = $(this).find('#id').val();

                swal({
                        title: "Batalkan Pesanan",
                        text: "Apakah anda yakin ingin membatalkan pesanan?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Batalkan",
                        cancelButtonText: "Kembali",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: '/api/penjualan/penjualan/cancel',
                                type: 'POST',
                                data: {
                                    'id': id,
                                    'jenis': jenis,
                                    'alasan': alasan
                                },
                                beforeSend: function() {
                                    $('#loader').show();
                                },
                                success: function(result) {
                                    if (response['data'] == "success") {
                                        swal.fire(
                                            'Berhasil',
                                            'Berhasil melakukan Perubahan Pesanan',
                                            'success'
                                        );
                                        $("#editmodal").modal('hide');
                                        location.reload();
                                    } else if (response['data'] == "error") {
                                        swal.fire(
                                            'Gagal',
                                            'Gagal melakukan Perubahan Pesanan',
                                            'error'
                                        );
                                    }
                                },
                                complete: function() {
                                    $('#loader').hide();
                                },
                                error: function(jqXHR, testStatus, error) {
                                    console.log(error);
                                    alert("Page " + href + " cannot open. Error:" + error);
                                    $('#loader').hide();
                                },
                                timeout: 8000
                            })
                        } else {
                            swal("Cancelled", "Your imaginary file is safe :)", "error");
                        }
                    });
            });

            $(document).on('click', '.deletemodal', function(event) {
                event.preventDefault();
                var href = $(this).attr('data-attr');
                var id = $(this).data("id");
                var label = $(this).data("target");
                if (label == 'ekatalog') {
                    $('#deletemodal').find('form').attr('action', '/api/ekatalog/delete/' + id);
                    $('#deletemodal').find('form').attr('data-target', 'ekatalog');
                } else if (label == 'spa') {
                    $('#deletemodal').find('form').attr('action', '/api/spa/delete/' + id);
                    $('#deletemodal').find('form').attr('data-target', 'spa');
                } else {
                    $('#deletemodal').find('form').attr('action', '/api/spb/delete/' + id);
                    $('#deletemodal').find('form').attr('data-target', 'spb');
                }
                // $.ajax({
                //     url: href,
                //     beforeSend: function() {
                //         $('#loader').show();
                //     },
                //     // return the result
                //     success: function(result) {
                $('#deletemodal').modal("show");
                // $('#detail').html(result).show();

                //     },
                //     complete: function() {
                //         $('#loader').hide();
                //     },
                //     error: function(jqXHR, testStatus, error) {
                //         console.log(error);
                //         alert("Page " + href + " cannot open. Error:" + error);
                //         $('#loader').hide();
                //     },
                //     timeout: 8000
                // })
            });


            $(document).on('submit', '#form-delete', function(e) {
                e.preventDefault();
                var action = $(this).attr('action');
                var label = $(this).data("target");
                $.ajax({
                    url: action,
                    type: 'delete',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan Hapus Data',
                                'success'
                            );
                            $('#penjualantable').DataTable().ajax.reload();
                            if (label == 'ekatalog') {
                                $('#ekatalogtable').DataTable().ajax.reload();
                            } else if (label == 'spa') {
                                $('#spatable').DataTable().ajax.reload();
                            } else if (label == 'spb') {
                                $('#spbtable').DataTable().ajax.reload();
                            }
                            $("#deletemodal").modal('hide');
                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan Hapus Data',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        swal.fire(
                            'Error',
                            'Data telah digunakan dalam Transaksi Lain',
                            'warning'
                        );
                        // console.log(action);
                    }
                });
                return false;
            });

            function detailtabel_ekatalog(id) {
                var dt = $('#detailtabel').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/ekatalog/paket/detail/' + id,
                        "dataType": "json",
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                            "class": "details-control",
                            "orderable": false,
                            "data": null,
                            "defaultContent": ""
                        },
                        {
                            data: 'nama_produk',
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
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api(),
                            data;
                        // converting to interger to find total
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                        // computing column Total of the complete result
                        var jumlah_pesanan = api
                            .column(4)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        // computing column Total of the complete result
                        var total_pesanan = api
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        var num_for = $.fn.dataTable.render.number(',', '.', 2).display;
                        $(api.column(0).footer()).html('Total');
                        $(api.column(4).footer()).html('Total');
                        $(api.column(5).footer()).html(num_for(total_pesanan));
                    },
                });
            }

            function detailtabel_spa(id) {
                $('#detailtabel_spa').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/spa/paket/detail/' + id,
                        "dataType": "json",
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                            orderable: false,
                            searchable: false
                        },
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api(),
                            data;
                        // converting to interger to find total
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                        // computing column Total of the complete result
                        var jumlah_pesanan = api
                            .column(3)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        // computing column Total of the complete result
                        var total_pesanan = api
                            .column(4)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        var num_for = $.fn.dataTable.render.number(',', '.', 2).display;
                        $(api.column(0).footer()).html('Total');
                        $(api.column(3).footer()).html('Total');
                        $(api.column(4).footer()).html(num_for(total_pesanan));
                    },
                })
            }

            function detailtabel_spb(id) {
                $('#detailtabel_spb').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/spb/paket/detail/' + id,
                        "dataType": "json",
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                            orderable: false,
                            searchable: false
                        },
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api(),
                            data;
                        // converting to interger to find total
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                        // computing column Total of the complete result
                        var jumlah_pesanan = api
                            .column(3)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        // computing column Total of the complete result
                        var total_pesanan = api
                            .column(4)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        var num_for = $.fn.dataTable.render.number(',', '.', 2).display;
                        $(api.column(0).footer()).html('Total');
                        $(api.column(3).footer()).html('Total');
                        $(api.column(4).footer()).html(num_for(total_pesanan));
                    },
                })
            }

            $('#filter_penjualan').submit(function() {
                var jenis_penjualan = [];
                var status_penjualan = [];
                var data_tahun = $('input[name="data_tahun_pen"]:checked').val();

                $('input[name="jenis_penjualan[]"]:checked').each(function() {
                    jenis_penjualan.push($(this).val());
                });
                $('input[name="status_penjualan[]"]:checked').each(function() {
                    status_penjualan.push($(this).val());
                });

                if (jenis_penjualan != 0 && status_penjualan != 0) {
                    var x = jenis_penjualan;
                    var y = status_penjualan;
                } else if (jenis_penjualan != 0 && status_penjualan == 0) {
                    var x = jenis_penjualan;
                    var y = ['semua'];
                } else if (jenis_penjualan == 0 && status_penjualan != 0) {
                    var x = ['semua'];
                    var y = status_penjualan;
                } else {
                    var x = ['semua'];
                    var y = ['semua'];
                }
                $('#penjualantable').DataTable().ajax.url('/api/penjualan/penjualan/data/' + x + '/' + y +
                        '/' + data_tahun)
                    .load();
                return false;
            });

            $('#filter_ekat').submit(function() {
                var values_ekat = [];
                var data_tahun = $('input[name="data_tahun"]:checked').val();

                $('input[name="status_ekatalog[]"]:checked').each(function() {
                    values_ekat.push($(this).val());
                });
                if (values_ekat != 0) {
                    var x = values_ekat;

                } else {
                    var x = ['semua'];
                }

                $('#ekatalogtable').DataTable().ajax.url('/penjualan/penjualan/ekatalog/data/' + x + '/' +
                    data_tahun).load();
                return false;
            });

            $('#filter_spa').submit(function() {
                var values_spa = [];
                var values_ekat = [];
                var data_tahun = $('input[name="data_tahun_spa"]:checked').val();
                $('input[name="status_spa[]"]:checked').each(function() {
                    values_spa.push($(this).val());
                });
                if (values_spa != 0) {
                    var x = values_spa;

                } else {
                    var x = ['semua'];
                }
                $('#spatable').DataTable().ajax.url('/penjualan/penjualan/spa/data/' + x + '/' +
                    data_tahun).load();
                return false;

            });

            $('#filter_spb').submit(function() {
                var values_spb = [];
                var data_tahun = $('input[name="data_tahun_spb"]:checked').val();
                $('input[name="status_spb[]"]:checked').each(function() {
                    values_spb.push($(this).val());
                });
                // alert(values_spb);
                if (values_spb != 0) {
                    var x = values_spb;

                } else {
                    var x = ['semua'];
                }

                $('#spbtable').DataTable().ajax.url('/penjualan/penjualan/spb/data/' + x + '/' +
                    data_tahun).load();
                return false;
            });




            $(document).on('click', '#tabledetailpesan #lihatstok', function() {
                var id = $(this).attr('data-id');
                var produk = $(this).attr('data-produk');
                var update = 'update';
                var array = [];
                $.ajax({
                    url: '/api/get_stok_pesanan',
                    data: {
                        'id': id,
                        'jenis': produk
                    },
                    type: 'GET',
                    dataType: 'json',
                    success: function(result) {
                        if (produk == 'part') {
                            $("#part_status").addClass('d-none');
                        } else {
                            $("#part_status").removeClass('d-none');
                        }

                        var chartExist = Chart.getChart("myChart"); // <canvas> id
                        if (chartExist != undefined)
                            chartExist.destroy();
                        update_chart(produk, result.gudang, result.qc, result.log, result.kir);


                        $('#nama_prd').text(result.detail.penjualan_produk.nama);
                        $('#tot_gudang').text(" dari " + result.detail.count_jumlah);
                        $('#tot_qc').text(" dari " + result.detail.count_gudang);
                        $('#tot_log').text(" dari " + result.detail.count_qc_ok);
                        $('#tot_kirim').text(" dari " + result.kir);

                        $('#c_gudang').text(result.gudang);
                        $('#c_qc').text(result.qc);
                        $('#c_log').text(result.log);
                        $('#c_kirim').text(result.kir);

                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })

            });
        })
    </script>
@stop
