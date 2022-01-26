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
                @if(Auth::user()->divisi_id == "26")
                <li class="breadcrumb-item"><a href="{{route('penjualan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Penjualan</li>
                @elseif(Auth::user()->divisi_id == "2")
                <li class="breadcrumb-item"><a href="{{route('direksi.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Penjualan</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
<style>
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

    @media screen and (min-width: 1440px) {

        section {
            font-size: 14px;
        }

        #detailmodal {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }

        .overflowy {
            max-height: 550px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
        }
    }

    @media screen and (max-width: 1439px) {

        label,
        .row {
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

        .overflowy {
            max-height: 450px;
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
                <div id="auth" class="hide">{{Auth::user()->divisi->id}}</div>
                <div class="col-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="semua-penjualan-tab" data-toggle="tab" href="#semua-penjualan" role="tab" aria-controls="semua-penjualan" aria-selected="true">Penjualan</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="ekatalog-tab" data-toggle="tab" href="#ekatalog" role="tab" aria-controls="ekatalog" aria-selected="false">E-Catalogue</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="spa-tab" data-toggle="tab" href="#spa" role="tab" aria-controls="spa" aria-selected="false">SPA</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="spb-tab" data-toggle="tab" href="#spb" role="tab" aria-controls="spb" aria-selected="false">SPB</a>
                        </li>
                    </ul>
                    <div class="tab-content card" id="myTabContent">
                        <div class="tab-pane fade show active card-body" id="semua-penjualan" role="tabpanel" aria-labelledby="semua-penjualan-tab">
                            <div class="row">
                                <div class="col-12">
                                    @if(Auth::user()->divisi->id == "26")
                                    <span class="float-right filter">
                                        <a href="{{route('penjualan.penjualan.create')}}"><button class="btn btn-outline-info">
                                                <i class="fas fa-plus"></i> Tambah
                                            </button>
                                        </a>
                                    </span>
                                    @endif
                                    <span class="float-right filter">
                                        <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <form id="filter_penjualan">
                                            <div class="dropdown-menu">
                                                <div class="px-3 py-3">
                                                    <div class="form-group">
                                                        <label for="jenis_penjualan">Jenis Penjualan</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="ekatalog" name="jenis_penjualan[]" id="jenis1" />
                                                            <label class="form-check-label" for="jenis1">
                                                                E-Catalogue
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="spa" name="jenis_penjualan[]" id="jenis2" />
                                                            <label class="form-check-label" for="jenis2">
                                                                SPA
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="spb" name="jenis_penjualan[]" id="jenis3" />
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
                                                            <input class="form-check-input" type="checkbox" value="7" name="status_penjualan[]" id="status3" />
                                                            <label class="form-check-label" for="status3">
                                                                Penjualan
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="9" name="status_penjualan[]" id="status4" />
                                                            <label class="form-check-label" for="status4">
                                                                PO
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="6" name="status_penjualan[]" id="status5" />
                                                            <label class="form-check-label" for="status5">
                                                                Gudang
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="8" name="status_penjualan[]" id="status6" />
                                                            <label class="form-check-label" for="status6">
                                                                QC
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="13" name="status_penjualan[]" id="status7" />
                                                            <label class="form-check-label" for="status7">
                                                                Terkirim Sebagian
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="11" name="status_penjualan[]" id="status8" />
                                                            <label class="form-check-label" for="status8">
                                                                Kirim
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <span class="float-right">
                                                            <button class="btn btn-primary" id="filter_penjualan" type="submit">
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
                                                    <th>No SO</th>
                                                    <th>Nomor AKN</th>
                                                    <th>Nomor PO</th>
                                                    <th>Tanggal Order</th>
                                                    <th>Tanggal Delivery</th>
                                                    <th>Customer</th>
                                                    <th>Jenis</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
                                                <td>1</td>
                                                <td>SOEKAT090202101</td>
                                                <td>AKN1-79479274207</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>05-10-2021</td>
                                                <td>
                                                    <span class="urgent">19-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">RS Soeryadi Kendal</span></td>
                                                <td>E-Catalogue</td>
                                                <td>
                                                    <span class="red-text badge">Batal</span>
                                                </td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('penjualan.penjualan.edit_ekatalog', ['id' => 1, 'jenis' => 'ekatalog'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-pencil-alt"></i>
                                                                Edit
                                                            </button>
                                                        </a>
                                                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="{{route('penjualan.penjualan.detail.ekatalog', ['id' => 2])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SOSPB090202101</td>
                                                <td>-</td>
                                                <td>PO/ON/45/10/21</td>
                                                <td>14-10-2021</td>
                                                <td>
                                                    <span class="warning">28-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">Pak Amin Pasuruan</span></td>
                                                <td>SPB</td>
                                                <td>
                                                    <span class="green-text badge">Pengiriman</span>
                                                </td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('penjualan.penjualan.edit_ekatalog', ['id' => 1, 'jenis' => 'spb'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-pencil-alt"></i>
                                                                Edit
                                                            </button>
                                                        </a>
                                                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="{{route('penjualan.penjualan.detail.spb', ['id' => 2])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SOSPA090202101</td>
                                                <td>-</td>
                                                <td>PO/ON/37/10/21</td>
                                                <td>15-10-2021</td>
                                                <td>29-10-2021</td>
                                                <td><span class="minimizechar">PT Emiindo Jakarta</span></td>
                                                <td>SPA</td>

                                                <td>
                                                    <span class="yellow-text badge">Gudang</span>
                                                </td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('penjualan.penjualan.edit_ekatalog', ['id' => 1, 'jenis' => 'spa'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-pencil-alt"></i>
                                                                Edit
                                                            </button>
                                                        </a>
                                                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="{{route('penjualan.penjualan.detail.spa', ['id' => 2])}}">
                                                            <button class=" dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade card-body" id="ekatalog" role="tabpanel" aria-labelledby="ekatalog-tab">
                            <div class="row">
                                <div class="col-12">
                                    <span class="float-right filter">
                                        <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <form id="filter_ekat">
                                            <div class="dropdown-menu">
                                                <div class="px-3 py-3">
                                                    <div class="form-group">
                                                        <label for="jenis_penjualan">Status</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="sepakat" id="status1" name="status_ekatalog[]" />
                                                            <label class="form-check-label" for="status1">
                                                                Sepakat
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="negosiasi" id="status2" name="status_ekatalog[]" />
                                                            <label class="form-check-label" for="status2">
                                                                Negosiasi
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="batal" id="status3" name="status_ekatalog[]" />
                                                            <label class="form-check-label" for="status3">
                                                                Batal
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="po" id="status4" />
                                                        <label class="form-check-label" for="status4">
                                                            PO
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="gudang" id="status5" />
                                                        <label class="form-check-label" for="status5">
                                                            Gudang
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="qc" id="status6" />
                                                        <label class="form-check-label" for="status6">
                                                            QC
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="logistik" id="status7" />
                                                        <label class="form-check-label" for="status7">
                                                            Logistik
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="pengiriman" id="status8" />
                                                        <label class="form-check-label" for="status8">
                                                            Pengiriman
                                                        </label>
                                                    </div>
                                                </div> -->
                                                    <div class="form-group">
                                                        <span class="float-right">
                                                            <button class="btn btn-primary" type="submit" id="filter_ekatalog">
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
                                                    <th>No Urut</th>
                                                    <th>Nomor SO</th>
                                                    <th>Nomor AKN</th>
                                                    <th>Nomor PO</th>
                                                    <th>Tanggal Order</th>
                                                    <th>Tanggal Delivery</th>
                                                    <th>Customer</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
                                                <td>1</td>
                                                <td>SOEKAT090202101</td>
                                                <td>AKN1-79479274207</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>05-10-2021</td>
                                                <td>
                                                    <span class="urgent">19-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">RS Soeryadi Kendal</span></td>
                                                <td>
                                                    <span class="red-text badge">Batal</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SOEKAT090202101</td>
                                                <td>AKN1-79479274207</td>
                                                <td>PO/ON/45/10/21</td>
                                                <td>14-10-2021</td>
                                                <td>
                                                    <span class="warning">28-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">PT Cipta Medika Pasuruan</span></td>

                                                <td>
                                                    <span class="green-text badge">Sepakat</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SOEKAT090202101</td>
                                                <td>AKN1-79479274207</td>
                                                <td>PO/ON/45/10/21</td>
                                                <td>15-10-2021</td>
                                                <td>29-10-2021</td>
                                                <td><span class="minimizechar">PT Emiindo Jakarta</span></td>
                                                <td>
                                                    <span class="yellow-text badge">Negosiasi</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade card-body" id="spa" role="tabpanel" aria-labelledby="spa-tab">
                            <div class="row">
                                <div class="col-12">
                                    <span class="float-right filter">
                                        <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <form id="filter_spa">
                                            <div class="dropdown-menu">
                                                <div class="px-3 py-3">
                                                    <div class="form-group">
                                                        <label for="jenis_penjualan">Status</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="7" id="status1" name="status_spa[]" />
                                                            <label class="form-check-label" for="status1">
                                                                Penjualan
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="9" id="status4" name="status_spa[]" />
                                                            <label class="form-check-label" for="status4">
                                                                PO
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="6" id="status5" name="status_spa[]" />
                                                            <label class="form-check-label" for="status5">
                                                                Gudang
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="8" id="status6" name="status_spa[]" />
                                                            <label class="form-check-label" for="status6">
                                                                QC
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="13" id="status7" name="status_spa[]" />
                                                            <label class="form-check-label" for="status7">
                                                                Terkirim Sebagian
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="11" id="status8" name="status_spa[]" />
                                                            <label class="form-check-label" for="status8">
                                                                Kirim
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <span class="float-right">
                                                            <button class="btn btn-primary" id="filter_spa" type="submit">
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
                                                <!-- <tr>
                                                <td>1</td>
                                                <td>SOSPA0902012910</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>05-10-2021</td>
                                                <td>
                                                    <span class="urgent">19-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">RS Soeryadi Kendal</span></td>
                                                <td>
                                                    <span class="red-text badge">PO</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SOSPA0902012910</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>14-10-2021</td>
                                                <td>
                                                    <span class="warning">28-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">PT Cipta Medika Pasuruan</span></td>
                                                <td>
                                                    <span class="yellow-text badge">Gudang</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SOSPA0902012910</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>15-10-2021</td>
                                                <td>29-10-2021</td>
                                                <td><span class="minimizechar">PT Emiindo Jakarta</span></td>
                                                <td>
                                                    <span class="green-text badge">Pengiriman</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade card-body" id="spb" role="tabpanel" aria-labelledby="spb-tab">
                            <div class="row">
                                <div class="col-12">
                                    <span class="float-right filter">
                                        <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <form id="filter_spb">
                                            <div class="dropdown-menu">
                                                <div class="px-3 py-3">
                                                    <div class="form-group">
                                                        <label for="jenis_penjualan">Status</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="7" id="status_spb1" name="status_spb[]" />
                                                            <label class="form-check-label" for="status_spb1">
                                                                Penjualan
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="9" id="status_spb2" name="status_spb[]" />
                                                            <label class="form-check-label" for="status_spb2">
                                                                PO
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="6" id="status_spb3" name="status_spb[]" />
                                                            <label class="form-check-label" for="status_spb3">
                                                                Gudang
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="8" id="status_spb4" name="status_spb[]" />
                                                            <label class="form-check-label" for="status_spb4">
                                                                QC
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="13" id="status_spb5" name="status_spb[]" />
                                                            <label class="form-check-label" for="status_spb5">
                                                                Terkirim Sebagian
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="11" id="status_spb6" name="status_spb[]" />
                                                            <label class="form-check-label" for="status_spb6">
                                                                Kirim
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <span class="float-right">
                                                            <button class="btn btn-primary" id="filter_spb" type="submit">
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
                                                <!-- <tr>
                                                <td>1</td>
                                                <td>SOSPB0902012910</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>05-10-2021</td>
                                                <td>
                                                    <span class="urgent">19-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">RS Soeryadi Kendal</span></td>
                                                <td>
                                                    <span class="yellow-text badge">Gudang</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SOSPB0902012910</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>14-10-2021</td>
                                                <td>
                                                    <span class="warning">28-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">PT Cipta Medika Pasuruan</span></td>
                                                <td>
                                                    <span class="yellow-text badge">Gudang</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SOSPB0902012910</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>15-10-2021</td>
                                                <td>29-10-2021</td>
                                                <td><span class="minimizechar">PT Emiindo Jakarta</span></td>
                                                <td>
                                                    <span class="green-text badge">Pengiriman</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr> -->
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
        <div class="modal fade" id="detailmodal" tabindex="-1" role="dialog" aria-labelledby="detailmodal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header">
                        <h4 id="modal-title">Detail</h4>
                    </div>
                    <div class="modal-body" id="detail">

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="deletemodal" aria-hidden="true">
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
        function tbldetailpesanan() {
            $('#tabledetailpesan').DataTable({
                "scrollX": false
            });
        }
        var divisi_id = "{{Auth::user()->divisi->id}}";
        var penjualantable = $('#penjualantable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                'url': "/api/penjualan/penjualan/data/semua/semua",
                "dataType": "json",
                'type': 'POST',
                "headers": {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                }
                // 'headers': {
                //     'X-CSRF-TOKEN': '{{csrf_token()}}'
                // },
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
                data: 'jenis',
            }, {
                data: 'status',
            }, {
                data: 'button',
                orderable: false,
                searchable: false
            }, ]
        });


        var ekatalogtable = $('#ekatalogtable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/penjualan/penjualan/ekatalog/data/semua',
                "dataType": "json",
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                    data: 'id'
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
                },
                {
                    data: 'tgl_kontrak',
                },
                {
                    data: 'nama_customer',
                },
                {
                    data: 'status',
                },
                {
                    data: 'button',
                },
            ]
        });
        var spatable = $('#spatable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/penjualan/penjualan/spa/data/semua',
                "dataType": "json",
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
                },
                {
                    data: 'nopo'
                },
                {
                    data: 'tglpo'
                },
                {
                    data: 'nama_customer'
                },
                {
                    data: 'status'
                },
                {
                    data: 'button'
                }
            ]
        })
        var spbtable = $('#spbtable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/penjualan/penjualan/spb/data/semua',
                "dataType": "json",
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
                },
                {
                    data: 'nopo'
                },
                {
                    data: 'tglpo'
                },
                {
                    data: 'nama_customer'
                },
                {
                    data: 'status'
                },
                {
                    data: 'button'
                }
            ]
        })
    })
</script>

<script>
    $(function() {
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

                        $('#detailmodal').find(".modal-header").attr('id', '');
                        $('#detailmodal').find(".modal-header").attr('id', 'detailekat');
                        $('#detailmodal').find(".modal-header > h4").text('E-Catalogue');
                        detailtabel_ekatalog(id);
                    } else if (label == 'spa') {
                        $('#detailmodal').find(".modal-header").attr('id', '');
                        $('#detailmodal').find(".modal-header").attr('id', 'detailspa');
                        $('#detailmodal').find(".modal-header > h4").text('SPA');
                        detailtabel_spa(id);
                    } else {
                        $('#detailmodal').find(".modal-header").attr('id', '');
                        $('#detailmodal').find(".modal-header").attr('id', 'detailspb');
                        $('#detailmodal').find(".modal-header > h4").text('SPB');
                        detailtabel_spb(id);
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

        // var detailRows = [];

        // function format() {
        //     return 'Full name: <br>' +
        //         'Salary: <br>' +
        //         'The child row can contain any data you wish, including links, images, inner tables etc.';
        // }

        // $('#detailtabel tbody').on('click', 'tr td.details-control', function() {
        //     var tr = $(this).closest('tr');
        //     var row = dt.row(tr);
        //     var idx = $.inArray(tr.attr('id'), detailRows);

        //     if (row.child.isShown()) {
        //         tr.removeClass('details');
        //         row.child.hide();

        //         // Remove from the 'open' array
        //         detailRows.splice(idx, 1);
        //     } else {
        //         tr.addClass('details');
        //         row.child(format(row.data())).show();

        //         // Add to the 'open' array
        //         if (idx === -1) {
        //             detailRows.push(tr.attr('id'));
        //         }
        //     }
        // });

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
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
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

            // dt.on('draw', function() {
            //     $.each(detailRows, function(i, id) {
            //         console.log(id);
            //         $('#' + id + ' td.details-control').trigger('click');
            //     });
            // });
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
            $('#penjualantable').DataTable().ajax.url('/api/penjualan/penjualan/data/' + x + '/' + y).load();
            return false;
        });
        $('#filter_ekat').submit(function() {
            var values_ekat = [];
            $('input[name="status_ekatalog[]"]:checked').each(function() {
                values_ekat.push($(this).val());
            });
            if (values_ekat != 0) {
                var x = values_ekat;

            } else {
                var x = ['semua'];
            }
            console.log(x);
            $('#ekatalogtable').DataTable().ajax.url('/penjualan/penjualan/ekatalog/data/' + x).load();
            return false;
        });
        $('#filter_spa').submit(function() {
            var values_spa = [];
            $('input[name="status_spa[]"]:checked').each(function() {
                values_spa.push($(this).val());
            });
            if (values_spa != 0) {
                var x = values_spa;

            } else {
                var x = ['semua'];
            }
            console.log(x);
            $('#spatable').DataTable().ajax.url('/penjualan/penjualan/spa/data/' + x).load();
            return false;

        });

        $('#filter_spb').submit(function() {
            var values_spb = [];
            $('input[name="status_spb[]"]:checked').each(function() {
                values_spb.push($(this).val());
            });
            // alert(values_spb);
            if (values_spb != 0) {
                var x = values_spb;

            } else {
                var x = ['semua'];
            }
            console.log(x);
            $('#spbtable').DataTable().ajax.url('/penjualan/penjualan/spb/data/' + x).load();
            return false;
        });
    })
</script>
@stop
