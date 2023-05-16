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
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.penjualan.show') }}">Penjualan</a></li>
                        <li class="breadcrumb-item active">Tambah Penjualan</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->

@stop

@section('adminlte_css')
    <style>
        table>tbody>tr>td>.form-group>.select2>.selection>.select2-selection--single {
            height: 100% !important;
        }

        table>tbody>tr>td>.form-group>.select2>.selection>.select2-selection>.select2-selection__rendered {
            word-wrap: break-word !important;
            text-overflow: inherit !important;
            white-space: normal !important;
        }

        .hide {
            display: none !important
        }

        .align-right {
            text-align: right;
        }

        .select2 {
            width: 100% !important;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-search--dropdown .select2-search__field {
            width: 98%;
        }

        .select_item .select2-selection--single {
            height: 100% !important;
        }

        .select_item .select2-selection__rendered {
            word-wrap: break-word !important;
            text-overflow: inherit !important;
            white-space: normal !important;
        }

        legend {
            font-size: 14px;
        }

        filter {
            margin: 5px;
        }

        .blue-bg {
            background-color: #e0eff3;
        }

        #penjualanform {
            top: 0;

        }

        #produktable {
            width: 1250px !important;
            margin-left: auto;
            margin-right: auto;
        }

        #parttable {
            width: 1250px !important;
            margin-left: auto;
            margin-right: auto;
        }

        #jasatable {
            width: 1250px !important;
            margin-left: auto;
            margin-right: auto;
        }

        .removeshadow {
            box-shadow: none;
        }


        @media screen and (min-width: 1220px) {

            body {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .labelket {
                text-align: right;
            }


        }

        @media screen and (max-width: 1219px) {

            /* label,
                                                            .row {
                                                                font-size: 12px;
                                                            }

                                                            h4 {
                                                                font-size: 20px;
                                                            } */
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: right;
            }
        }

        @media screen and (max-width: 991px) {

            /* label,
                                                            .row {
                                                                font-size: 12px;
                                                            }

                                                            h4 {
                                                                font-size: 20px;
                                                            } */
            section {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: left;
            }
        }

        .autocomplete {
            position: relative;
            display: inline-block;

        }

        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 10px;
            right: 10px;
        }

        .autocomplete-items div {
            padding: 7px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        /*when hovering an item:*/
        .autocomplete-items div:hover {
            background-color: steelblue;
            color: #ffffff;
        }

        /*when navigating through the items using the arrow keys:*/
        .autocomplete-active {
            background-color: midnightblue !important;
            color: #ffffff;
        }


        .ui-widget-content.ui-autocomplete {
            width: 350px;
            max-height: 200px;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        .ui-menu-item .ui-menu-item-wrapper.ui-state-active {
            background: #366aca !important;
            font-weight: bold !important;
            color: #ffffff !important;
        }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center" id="penjualanform">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-info">
                            <div class="card-title">Form Tambah Data</div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('error') || count($errors) > 0)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>{{ Session::get('error') }}</strong> Periksa
                                    kembali data yang diinput
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @elseif(Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>{{ Session::get('success') }}</strong>,
                                    Terima kasih
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <form method="post" autocomplete="off" action="{{ route('penjualan.penjualan.store') }}">
                                {{ csrf_field() }}
                                <div class="row d-flex justify-content-center">
                                    <div class="col-lg-11 col-md-12">
                                        <h4>Info Customer</h4>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-horizontal">
                                                    <div class="form-group row">
                                                        <label for=""
                                                            class="col-form-label col-lg-5 col-md-12 labelket">Jenis
                                                            Penjualan</label>
                                                        <div class="col-lg-5 col-md-12 col-form-label">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="jenis_penjualan" id="jenis_penjualan1"
                                                                    value="ekatalog" />
                                                                <label class="form-check-label"
                                                                    for="jenis_penjualan1">E-Catalogue</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="jenis_penjualan" id="jenis_penjualan2"
                                                                    value="spa" />
                                                                <label class="form-check-label"
                                                                    for="jenis_penjualan2">SPA</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="jenis_penjualan" id="jenis_penjualan3"
                                                                    value="spb" />
                                                                <label class="form-check-label"
                                                                    for="jenis_penjualan3">SPB</label>
                                                            </div>
                                                            <div class="invalid-feedback" id="msgjenis_penjualan">
                                                                @if ($errors->has('jenis_penjualan'))
                                                                    {{ $errors->first('jenis_penjualan') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="penjualan"
                                                            class="col-form-label col-lg-5 col-md-12 labelket">Barang</label>
                                                        <div class="col-5 col-form-label">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="jenis_pen" value="produk" name="jenis_pen[]"
                                                                    disabled>
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox1">Produk</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="jenis_pen" value="sparepart" name="jenis_pen[]"
                                                                    disabled>
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox1">Sparepart</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="jenis_pen" value="jasa" name="jenis_pen[]"
                                                                    disabled>
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox1">Jasa</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="form-group row">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Pilih Barang</label>
                                                    <div class="col-lg-5 col-md-12 col-form-label">
                                                        <div class="form-check form-check-inline hide" id="penj_prd">
                                                            <input class="form-check-input" type="radio" name="jenis_penj" id="jenis_penj1" value="produk" />
                                                            <label class="form-check-label" for="jenis_penj1">Produk</label>
                                                        </div>
                                                        <div class="form-check form-check-inline hide" id="penj_spr">
                                                            <input class=" form-check-input" type="radio" name="jenis_penj" id="jenis_penj2" value="sparepart" />
                                                            <label class="form-check-label" for="jenis_penj2">Sparepart</label>
                                                        </div>
                                                        <div class="form-check form-check-inline hide" id="penj_sem">
                                                            <input class="form-check-input" type="radio" name="jenis_penj" id="jenis_penj3" value="semua" />
                                                            <label class="form-check-label" for="jenis_penj3">Produk + Sparepart</label>
                                                        </div>
                                                        <div class="form-check form-check-inline hide" id="penj_jas">
                                                            <input class="form-check-input" type="radio" name="jenis_penj" id="jenis_penj4" value="jasa" />
                                                            <label class="form-check-label" for="jenis_penj3">Jasa</label>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                    <div class="form-group row">
                                                        <label for=""
                                                            class="col-form-label col-lg-5 col-md-12 labelket">Nama
                                                            Customer / Distributor</label>
                                                        <div class="col-lg-5 col-md-12 col-form-label">
                                                            <div class="form-check form-check-inline " id="sudah_dsb">
                                                                <input class="form-check-input" type="radio"
                                                                    name="namadistributor" id="namadistributor1"
                                                                    value="sudah" />
                                                                <label class="form-check-label"
                                                                    for="namadistributor1">Sudah Diketahui</label>
                                                            </div>
                                                            <div class="form-check form-check-inline " id="belum_dsb">
                                                                <input class="form-check-input" type="radio"
                                                                    name="namadistributor" id="namadistributor2"
                                                                    value="belum" />
                                                                <label class="form-check-label"
                                                                    for="namadistributor2">Belum Diketahui</label>
                                                            </div>
                                                            <div class="invalid-feedback" id="msgnamadistributor">
                                                                @if ($errors->has('namadistributor'))
                                                                    {{ $errors->first('namadistributor') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for=""
                                                            class="col-form-label col-lg-5 col-md-12 labelket"></label>
                                                        <div class="col-lg-5 col-md-12">
                                                            <select name="customer_id" id="customer_id"
                                                                class="form-control custom-select customer_id   @error('customer_id') is-invalid @enderror">
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for=""
                                                            class="col-form-label col-lg-5 col-md-12 labelket">Alamat</label>
                                                        <div class="col-lg-7 col-md-12">
                                                            <textarea class="form-control col-form-label @error('alamat') is-invalid @enderror" name="alamat" id="alamat"
                                                                readonly></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for=""
                                                            class="col-form-label col-lg-5 col-md-12 labelket">Telepon</label>
                                                        <div class="col-lg-5 col-md-12">
                                                            <input type="text" class="form-control col-form-label"
                                                                name="telepon" id="telepon" readonly />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-horizontal">
                                    <div class="row d-flex justify-content-center hide" id="akn">
                                        <div class="col-lg-11 col-md-12">
                                            <h4>Info AKN</h4>
                                            <div class="card">
                                                <div class="card-body">
                                                    <ul class="nav nav-pills mb-3 nav-justified" id="pills-tab"
                                                        role="tablist">
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link active" id="pills-penjualan-tab"
                                                                data-toggle="pill" href="#pills-penjualan" role="tab"
                                                                aria-controls="pills-penjualan"
                                                                aria-selected="true">Deskripsi Ekatalog</a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link" id="pills-po-ekat-tab"
                                                                data-toggle="pill" href="#pills-po-ekat" role="tab"
                                                                aria-controls="pills-po-ekat"
                                                                aria-selected="false">Purchase Order</a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link disabled" id="pills-instansi-tab"
                                                                data-toggle="pill" href="#pills-instansi" role="tab"
                                                                aria-controls="pills-instansi"
                                                                aria-selected="false">Instansi</a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link disabled" id="pills-produk-tab"
                                                                data-toggle="pill" href="#pills-produk" role="tab"
                                                                aria-controls="pills-produk" aria-selected="false">Rencana
                                                                Penjualan</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content" id="pills-tabContent">
                                                        <div class="tab-pane fade show active" id="pills-penjualan" role="tabpanel" aria-labelledby="pills-penjualan-tab">
                                                            <div class="card removeshadow">
                                                                <div class="card-header">
                                                                    <h6>Deskripsi Ekatalog</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-group row">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">No
                                                                            Urut</label>
                                                                        <div class="col-lg-2 col-md-6">
                                                                            <input type="number"
                                                                                class="form-control col-form-label @error('no_urut') is-invalid @enderror"
                                                                                name="no_urut" id="no_urut" />
                                                                            <div class="invalid-feedback" id="msgno_urut">
                                                                                @if ($errors->has('no_urut'))
                                                                                    {{ $errors->first('no_urut') }}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">No
                                                                            Paket</label>
                                                                        <div class="col-lg-5 col-md-12 input-group">
                                                                            <div class="input-group-prepend">
                                                                                <select class="form-control jenis_paket"
                                                                                    name="jenis_paket" id="jenis_paket">
                                                                                    <option value="AK1-" selected>AK1-
                                                                                    </option>
                                                                                    <option value="FKS-">FKS-</option>
                                                                                </select>
                                                                                {{-- <span class="input-group-text" id="ket_no_paket">AK1-</span> --}}
                                                                            </div>
                                                                            <input type="text"
                                                                                class="form-control col-form-label @error('no_paket') is-invalid @enderror"
                                                                                name="no_paket" id="no_paket"
                                                                                aria-label="ket_no_paket"
                                                                                height="100%" />
                                                                            <div class="input-group-append hide"
                                                                                id="checkbox_nopaket">
                                                                                <span class="input-group-text">
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="checkbox"
                                                                                            name="isi_nopaket"
                                                                                            id="isi_nopaket"
                                                                                            value="true" />
                                                                                        <label class="form-check-label"
                                                                                            for="isi_nopaket"></label>
                                                                                    </div>
                                                                                </span>
                                                                            </div>
                                                                            <div class="invalid-feedback"
                                                                                id="msgno_paket">
                                                                                @if ($errors->has('no_paket'))
                                                                                    {{ $errors->first('no_paket') }}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">Status</label>
                                                                        <div class="col-lg-6 col-md-12 col-form-label">
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input"
                                                                                    type="radio" name="status"
                                                                                    id="status1" value="sepakat" />
                                                                                <label class="form-check-label"
                                                                                    for="status1">Sepakat</label>
                                                                            </div>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input"
                                                                                    type="radio" name="status"
                                                                                    id="status2" value="negosiasi" />
                                                                                <label class="form-check-label"
                                                                                    for="status2">Negosiasi</label>
                                                                            </div>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input"
                                                                                    type="radio" name="status"
                                                                                    id="status3" value="batal" />
                                                                                <label class="form-check-label"
                                                                                    for="status3">Batal</label>
                                                                            </div>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input"
                                                                                    type="radio" name="status"
                                                                                    id="status4" value="draft" />
                                                                                <label class="form-check-label"
                                                                                    for="status4">Draft</label>
                                                                            </div>
                                                                            <div class="invalid-feedback" id="msgstatus">
                                                                                @if ($errors->has('status'))
                                                                                    {{ $errors->first('status') }}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row hide"
                                                                        id="isi_produk_input">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket"></label>
                                                                        <div class="col-lg-6 col-md-12 col-form-label">
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox" name="isi_produk"
                                                                                    id="isi_produk" value="isi" />
                                                                                <label class="form-check-label"
                                                                                    for="isi_produk">Isi Produk</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                                            Buat</label>
                                                                        <div class="col-lg-4">
                                                                            <input type="date"
                                                                                class="form-control col-form-label @error('tanggal_pemesanan') is-invalid @enderror"
                                                                                name="tanggal_pemesanan"
                                                                                id="tanggal_pemesanan" />
                                                                            <div class="invalid-feedback"
                                                                                id="msgtanggal_pemesanan">
                                                                                @if ($errors->has('tanggal_pemesanan'))
                                                                                    {{ $errors->first('tanggal_pemesanan') }}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                                            Edit</label>
                                                                        <div class="col-lg-4">
                                                                            <input type="date"
                                                                                class="form-control col-form-label @error('tanggal_edit') is-invalid @enderror"
                                                                                name="tanggal_edit" id="tanggal_edit" />
                                                                            <div class="invalid-feedback"
                                                                                id="msgtanggal_edit">
                                                                                @if ($errors->has('tanggal_edit'))
                                                                                    {{ $errors->first('tanggal_edit') }}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                                            Delivery</label>
                                                                        <div class="col-lg-4 col-md-12">
                                                                            <input type="date"
                                                                                class="form-control col-form-label @error('batas_kontrak') is-invalid @enderror"
                                                                                name="batas_kontrak" id="batas_kontrak" />
                                                                            <div class="invalid-feedback"
                                                                                id="msgbatas_kontrak">
                                                                                @if ($errors->has('batas_kontrak'))
                                                                                    {{ $errors->first('batas_kontrak') }}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {{-- <div class="card-footer">
                                                                <button type="button" class="btn btn-info float-right" id="pills-instansi-tab" data-toggle="pill" href="#pills-instansi" role="tab" aria-controls="pills-instansi" aria-selected="false">Selanjutnya</button>
                                                            </div> --}}
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade show" id="pills-po-ekat" role="tabpanel" aria-labelledby="pills-po-ekat-tab">
                                                            <div class="card removeshadow">
                                                                <div class="card-header">
                                                                    <h6>Purchase Order</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-group row">
                                                                        <label for="no_po_ekat"
                                                                            class="col-lg-5 col-md-12 col-form-label labelket">No
                                                                            PO</label>
                                                                        <div class="col-lg-5 col-md-12">
                                                                            <input type="text"
                                                                                class="form-control @error('no_po_ekat') is-invalid @enderror"
                                                                                value=""
                                                                                placeholder="Masukkan Nomor Purchase Order"
                                                                                id="no_po_ekat" name="no_po_ekat" />
                                                                            <div class="invalid-feedback" id="msgno_po_ekat">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="tanggal_po_ekat"
                                                                            class="col-lg-5 col-md-12 col-form-label labelket">Tanggal
                                                                            PO</label>
                                                                        <div class="col-lg-5 col-md-12">
                                                                            <input type="date"
                                                                                class="form-control @error('tanggal_po_ekat') is-invalid @enderror"
                                                                                value=""
                                                                                placeholder="Masukkan Tanggal Purchase Order"
                                                                                id="tanggal_po_ekat" name="tanggal_po_ekat" />
                                                                            <div class="invalid-feedback" id="msgtanggal_po_ekat">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">Delivery
                                                                            Order</label>
                                                                        <div class="col-lg-5 col-md-12 col-form-label">
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio"
                                                                                    name="do_ekat" id="yes" value="yes" />
                                                                                <label class="form-check-label"
                                                                                    for="yes">Tersedia</label>
                                                                            </div>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio"
                                                                                    name="do_ekat" id="no" value="no" />
                                                                                <label class="form-check-label" for="no">Tidak
                                                                                    tersedia</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row hide" id="do_detail_no_ekat">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">Nomor
                                                                            DO</label>
                                                                        <div class="col-lg-5 col-md-12">
                                                                            <input type="text"
                                                                                class="form-control col-form-label @error('no_do_ekat') is-invalid @enderror"
                                                                                id="no_do_ekat" name="no_do_ekat" />
                                                                            <div class="invalid-feedback" id="msgno_do_ekat">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row hide" id="do_detail_tgl_ekat">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                                            DO</label>
                                                                        <div class="col-lg-5 col-md-12">
                                                                            <input type="date"
                                                                                class="form-control col-form-label @error('tanggal_do_ekat') is-invalid @enderror"
                                                                                id="tanggal_do_ekat" name="tanggal_do_ekat" />
                                                                            <div class="invalid-feedback" id="msgtanggal_do_ekat">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="keterangan_ekat"
                                                                            class="col-lg-5 col-md-12 col-form-label labelket">Keterangan</label>
                                                                        <div class="col-lg-5 col-md-12">
                                                                            <textarea class="form-control" placeholder="Masukkan Keterangan" id="keterangan_ekat" name="keterangan_ekat"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="pills-instansi" role="tabpanel" aria-labelledby="pills-instansi-tab">
                                                            <div class="card removeshadow">
                                                                <div class="card-header">
                                                                    <h6>Instansi</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-group row">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">Instansi</label>
                                                                        <div class="col-lg-7 col-md-12 autocomplete">
                                                                            <input type="text"
                                                                                class="form-control col-form-label @error('instansi') is-invalid @enderror"
                                                                                name="instansi" id="instansi"
                                                                                autocomplete="off" />
                                                                            <div class="invalid-feedback"
                                                                                id="msginstansi">
                                                                                @if ($errors->has('instansi'))
                                                                                    {{ $errors->first('instansi') }}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">Satuan
                                                                            Kerja</label>
                                                                        <div class="col-lg-6 col-md-12">
                                                                            <input type="text"
                                                                                class="form-control col-form-label @error('satuan_kerja') is-invalid @enderror"
                                                                                name="satuan_kerja" id="satuan_kerja" />
                                                                            <div class="invalid-feedback"
                                                                                id="msgsatuan_kerja">
                                                                                @if ($errors->has('satuan_kerja'))
                                                                                    {{ $errors->first('satuan_kerja') }}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">Alamat
                                                                            Instansi</label>
                                                                        <div class="col-lg-7 col-md-12">
                                                                            <textarea class="form-control col-form-label @error('alamatinstansi') is-invalid @enderror" name="alamatinstansi"
                                                                                id="alamatinstansi"></textarea>
                                                                            <div class="invalid-feedback"
                                                                                id="msgalamatinstansi">
                                                                                @if ($errors->has('alamatinstansi'))
                                                                                    {{ $errors->first('alamatinstansi') }}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">Provinsi</label>
                                                                        <div class="col-lg-7 col-md-12">
                                                                            <select name="provinsi" id="provinsi"
                                                                                class="form-control custom-select provinsi @error('provinsi') is-invalid @enderror"
                                                                                style="width: 100%;">
                                                                            </select>
                                                                            <div class="invalid-feedback"
                                                                                id="msgprovinsi">
                                                                                @if ($errors->has('provinsi'))
                                                                                    {{ $errors->first('provinsi') }}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for=""
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">Deskripsi</label>
                                                                        <div class="col-lg-5 col-md-12">
                                                                            <textarea class="form-control col-form-label @error('deskripsi') is-invalid @enderror" name="deskripsi"
                                                                                id="deskripsi"></textarea>
                                                                            <div class="invalid-feedback"
                                                                                id="msgdeskripsi">
                                                                                @if ($errors->has('deskripsi'))
                                                                                    {{ $errors->first('deskripsi') }}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="keterangan"
                                                                            class="col-form-label col-lg-5 col-md-12 labelket">Keterangan</label>
                                                                        <div class="col-lg-5 col-md-12">
                                                                            <textarea class="form-control col-form-label" name="keterangan"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {{-- <div class="card-footer">
                                                                <button type="button" class="btn btn-danger float-left" id="pills-penjualan-tab" data-toggle="pill" href="#pills-penjualan" role="tab" aria-controls="pills-penjualan" aria-selected="false">Kembali</button>
                                                                <button type="button" class="btn btn-info float-right" id="pills-keterangan-tab" data-toggle="pill" href="#pills-keterangan" role="tab" aria-controls="pills-keterangan" aria-selected="false">Selanjutnya</button>
                                                            </div> --}}
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="pills-produk" role="tabpanel" aria-labelledby="pills-produk-tab">
                                                            <div class="card removeshadow">
                                                                <div class="card-header">
                                                                    <h6>Rencana Penjualan</h6>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="col-lg-12 col-md-12 perencanaan">
                                                                        <div class="table-responsive">
                                                                            <table class="table"
                                                                                style="text-align: center; width: 100%;"
                                                                                id="perencanaantable">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>No</th>
                                                                                        <th>Nama Produk</th>
                                                                                        <th>Qty</th>
                                                                                        <th>Realisasi</th>
                                                                                        <th>Harga</th>
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
                                        </div>
                                    </div>
                                    <div class="row d-flex justify-content-center hide" id="nonakn">
                                        <div class="col-lg-11 col-md-12">
                                            <h4>Info Penjualan</h4>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label for=""
                                                            class="col-form-label col-lg-5 col-md-12 labelket">Nomor
                                                            PO</label>
                                                        <div class="col-lg-4 col-md-12">
                                                            <input type="text"
                                                                class="form-control col-form-label @error('no_po') is-invalid @enderror"
                                                                id="no_po" name="no_po" />
                                                            <div class="invalid-feedback" id="msgno_po">
                                                                @if ($errors->has('no_po'))
                                                                    {{ $errors->first('no_po') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for=""
                                                            class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                            PO</label>
                                                        <div class="col-lg-4 col-md-12">
                                                            <input type="date"
                                                                class="form-control col-form-label @error('tanggal_po') is-invalid @enderror"
                                                                id="tanggal_po" name="tanggal_po" />
                                                            <div class="invalid-feedback" id="msgtanggal_po">
                                                                @if ($errors->has('tanggal_po'))
                                                                    {{ $errors->first('tanggal_po') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for=""
                                                            class="col-form-label col-lg-5 col-md-12 labelket">Delivery
                                                            Order</label>
                                                        <div class="col-lg-5 col-md-12 col-form-label">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="do" id="yes" value="yes" />
                                                                <label class="form-check-label"
                                                                    for="yes">Tersedia</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                    name="do" id="no" value="no" />
                                                                <label class="form-check-label" for="no">Tidak
                                                                    tersedia</label>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="form-group row hide" id="do_detail_no">
                                                        <label for=""
                                                            class="col-form-label col-lg-5 col-md-12 labelket">Nomor
                                                            DO</label>
                                                        <div class="col-lg-4 col-md-12">
                                                            <input type="text"
                                                                class="form-control col-form-label @error('no_do') is-invalid @enderror"
                                                                id="no_do" name="no_do" />
                                                            <div class="invalid-feedback" id="msgno_do">
                                                                @if ($errors->has('no_do'))
                                                                    {{ $errors->first('no_do') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row hide" id="do_detail_tgl">
                                                        <label for=""
                                                            class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                            DO</label>
                                                        <div class="col-lg-4 col-md-12">
                                                            <input type="date"
                                                                class="form-control col-form-label @error('tanggal_do') is-invalid @enderror"
                                                                id="tanggal_do" name="tanggal_do" />
                                                            <div class="invalid-feedback" id="msgtanggal_do">
                                                                @if ($errors->has('tanggal_do'))
                                                                    {{ $errors->first('tanggal_do') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="keterangan"
                                                            class="col-form-label col-lg-5 col-md-12 labelket">Keterangan</label>
                                                        <div class="col-lg-5 col-md-12">
                                                            <textarea class="form-control col-form-label" id="nonketerangan" name="keterangan"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
<<<<<<< HEAD
                                <div class="row justify-content-center hide" id="dataproduk">
                                    <div class="col-lg-11 col-md-12">
                                        <h4>Data Produk</h4>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="table-responsive justify-content-center">
                                                            <table class="table" style="text-align: center;"
                                                                id="produktable">
                                                                <thead>
                                                                    <tr>
                                                                        <th colspan="7">
                                                                            <button type="button"
                                                                                class="btn btn-primary float-right"
                                                                                id="addrowproduk">
                                                                                <i class="fas fa-plus"></i>
                                                                                Produk
                                                                            </button>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th width="5%">No</th>
                                                                        <th width="35%">Nama Paket</th>
                                                                        <th width="10%">Jumlah</th>
                                                                        <th width="15%">Harga</th>
                                                                        <th width="15%">Ongkir</th>
                                                                        <th width="15%">Subtotal</th>
                                                                        <th hidden>ID_Rencana</th>
                                                                        <th width="5%">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <select name="penjualan_produk_id[]"
                                                                                    id="0"
                                                                                    class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror"
                                                                                    style="width:100%;">
                                                                                </select>
                                                                            </div>
                                                                            <div class="detail_produk"
                                                                                id="detail_produk0">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div
                                                                                class="form-group d-flex justify-content-center">
                                                                                <div class="input-group">
                                                                                    <input type="number"
                                                                                        class="form-control produk_jumlah"
                                                                                        aria-label="produk_satuan"
                                                                                        name="produk_jumlah[]"
                                                                                        id="produk_jumlah0"
                                                                                        style="width:100%;">
                                                                                </div>
                                                                                <small id="produk_ketersediaan"></small>
                                                                            </div>
                                                                        </td>

                                                                        <td>
                                                                            <div
                                                                                class="form-group d-flex justify-content-center">
                                                                                <input type="text"
                                                                                    class="form-control produk_harga"
                                                                                    name="produk_harga[]"
                                                                                    id="produk_harga0"
                                                                                    placeholder="Masukkan Harga"
                                                                                    style="width:100%;" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div
                                                                                class="form-group d-flex justify-content-center">
                                                                                <input type="text"
                                                                                    class="form-control produk_ongkir"
                                                                                    name="produk_ongkir[]"
                                                                                    id="produk_ongkir0"
                                                                                    placeholder="Masukkan Ongkir"
                                                                                    style="width:100%;" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div
                                                                                class="form-group d-flex justify-content-center">
                                                                                <input type="text"
                                                                                    class="form-control produk_subtotal"
                                                                                    name="produk_subtotal[]"
                                                                                    id="produk_subtotal0"
                                                                                    placeholder="Masukkan Subtotal"
                                                                                    style="width:100%;" readonly />
                                                                            </div>
                                                                        </td>
                                                                        <td hidden><input type="hidden"
                                                                                class="rencana_id" name="rencana_id[]"
                                                                                id="rencana_id0" readonly></td>
                                                                        <td>
                                                                            <a id="removerowproduk"><i
                                                                                    class="fas fa-minus"
                                                                                    style="color: red"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="5" style="text-align:right;">Total
                                                                            Harga</th>
                                                                        <th id="totalhargaprd" class="align-right">Rp. 0
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
=======
                            </div>
                            <div class="row justify-content-center hide" id="dataproduk">
                                <div class="col-lg-11 col-md-12">
                                    <h4>Data Produk</h4>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive justify-content-center">
                                                        <table class="table" style="text-align: center;" id="produktable">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="7">
                                                                        <button type="button" class="btn btn-primary float-right" id="addrowproduk">
                                                                            <i class="fas fa-plus"></i>
                                                                            Produk
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th width="2%">No</th>
                                                                    <th width="35%">Nama Paket</th>
                                                                    <th width="10%">Jumlah</th>
                                                                    <th width="6%">Stok Distributor</th>
                                                                    <th width="15%">Harga</th>
                                                                    <th width="15%">Ongkir</th>
                                                                    <th width="15%">Subtotal</th>
                                                                    <th hidden>ID_Rencana</th>
                                                                    <th width="2%">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <select name="penjualan_produk_id[]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                                                            </select>
                                                                        </div>
                                                                        <div class="detail_produk" id="detail_produk0">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group d-flex justify-content-center">
                                                                            <div class="input-group">
                                                                                <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[]" id="produk_jumlah0" style="width:100%;">
                                                                            </div>
                                                                            <small id="produk_ketersediaan"></small>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-check col-form-label">
                                                                            <input type="checkbox" class="form-check-input stok_dist" id="stok_dist" name="stok_dist[]" value="1">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group d-flex justify-content-center">
                                                                            <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;" />
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group d-flex justify-content-center">
                                                                            <input type="text" class="form-control produk_ongkir" name="produk_ongkir[]" id="produk_ongkir0" placeholder="Masukkan Ongkir" style="width:100%;" />
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group d-flex justify-content-center">
                                                                            <input type="text" class="form-control produk_subtotal" name="produk_subtotal[]" id="produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" readonly />
                                                                        </div>
                                                                    </td>
                                                                    <td hidden><input type="hidden" class="rencana_id" name="rencana_id[]" id="rencana_id0" readonly></td>
                                                                    <td>
                                                                        <a id="removerowproduk"><i class="fas fa-minus" style="color: red"></i></a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="5" style="text-align:right;">Total Harga</th>
                                                                    <th id="totalhargaprd" class="align-right">Rp. 0</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
>>>>>>> della_v
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center hide" id="datapart">
                                    <div class="col-lg-11 col-md-12">
                                        <h4>Data Part</h4>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="table-responsive justify-content-center">
                                                            <table class="table" style="text-align: center;"
                                                                id="parttable">
                                                                <thead>
                                                                    <tr>
                                                                        <th colspan="7">
                                                                            <button type="button"
                                                                                class="btn btn-primary float-right"
                                                                                id="addrowpart">
                                                                                <i class="fas fa-plus"></i>
                                                                                Part
                                                                            </button>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th width="5%">No</th>
                                                                        <th width="35%">Nama Part</th>
                                                                        <th width="15%">Jumlah</th>
                                                                        <th width="20%">Harga</th>
                                                                        <th width="20%">Subtotal</th>
                                                                        <th width="5%">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <select
                                                                                    class="select form-control custom-select part_id"
                                                                                    name="part_id[]" id="part_id0"
                                                                                    width="100%">
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div
                                                                                class="form-group d-flex justify-content-center">
                                                                                <input type="number"
                                                                                    class="form-control part_jumlah"
                                                                                    name="part_jumlah[]" id="part_jumlah0"
                                                                                    style="width:100%;">
                                                                                <small id="part_ketersediaan"></small>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div
                                                                                class="form-group d-flex justify-content-center">

                                                                                <input type="text"
                                                                                    class="form-control part_harga"
                                                                                    name="part_harga[]" id="part_harga0"
                                                                                    placeholder="Masukkan Harga"
                                                                                    style="width:100%;" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div
                                                                                class="form-group d-flex justify-content-center">

                                                                                <input type="text"
                                                                                    class="form-control part_subtotal"
                                                                                    name="part_subtotal[]"
                                                                                    id="part_subtotal0"
                                                                                    placeholder="Masukkan Subtotal"
                                                                                    style="width:100%;" readonly />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <a id="removerowpart"><i class="fas fa-minus"
                                                                                    style="color: red"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="4" style="text-align:right;">Total
                                                                            Harga</th>
                                                                        <th id="totalhargapart" class="align-right">Rp. 0
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center hide" id="datajasa">
                                    <div class="col-lg-11 col-md-12">
                                        <h4>Jasa</h4>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="table-responsive justify-content-center">
                                                            <table class="table" style="text-align: center;"
                                                                id="jasatable">
                                                                <thead>
                                                                    <tr>
                                                                        <th colspan="7">
                                                                            <button type="button"
                                                                                class="btn btn-primary float-right"
                                                                                id="addrowjasa">
                                                                                <i class="fas fa-plus"></i>
                                                                                Jasa
                                                                            </button>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th width="5%">No</th>
                                                                        <th width="35%">Nama Jasa</th>
                                                                        <th width="20%">Harga</th>
                                                                        <th width="20%">Subtotal</th>
                                                                        <th width="5%">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>
                                                                            <div class="form-group select_item">
                                                                                <select
                                                                                    class="select2 form-control select-info custom-select jasa_id"
                                                                                    name="jasa_id[]" id="jasa_id0"
                                                                                    width="100%">
                                                                                </select>
                                                                            </div>
                                                                        </td>

                                                                        <td>
                                                                            <div
                                                                                class="form-group d-flex justify-content-center">
                                                                                <input type="text"
                                                                                    class="form-control jasa_harga"
                                                                                    name="jasa_harga[]" id="jasa_harga0"
                                                                                    placeholder="Masukkan Harga"
                                                                                    style="width:100%;" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div
                                                                                class="form-group d-flex justify-content-center">

                                                                                <input type="text"
                                                                                    class="form-control jasa_subtotal"
                                                                                    name="jasa_subtotal[]"
                                                                                    id="jasa_subtotal0"
                                                                                    placeholder="Masukkan Subtotal"
                                                                                    style="width:100%;" readonly />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <a id="removerowjasa"><i class="fas fa-minus"
                                                                                    style="color: red"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="3" style="text-align:right;">Total
                                                                            Harga</th>
                                                                        <th id="totalhargajasa" class="align-right">Rp. 0
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <div class="col-11">
                                        <span>
                                            <a href="{{ route('penjualan.penjualan.show') }}" type="button"
                                                class="btn btn-danger">
                                                Batal
                                            </a>
                                        </span>
                                        <span class="float-right">
                                            <button type="submit" class="btn btn-info" id="btntambah" disabled="true">
                                                Simpan
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('adminlte_js')
    <script>
        $(function() {
            var produk_obj = {};
            var part_obj = {};
            var jasa_obj = {};

            $('#jenis_paket').select2({
                placeholder: "Pilih Paket"
            });

            function perencanaan(customer_id, instansi) {
                $('#perencanaantable').DataTable({
                    searching: false,
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/penjualan/rencana/produk/' + customer_id + '/' + instansi + '/' + yyyy,
                        'dataType': 'json',
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
                            className: 'align-center nowrap-text',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_produk',
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'qty',
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'realisasi',
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'harga',
                            render: $.fn.dataTable.render.number('.', ',', 0, 'Rp. '),
                            className: 'align-right nowrap-text'
                        },
                        {
                            data: 'aksi',
                            className: 'align-center nowrap-text'
                        },
                    ]
                });
            }

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            // var instansi_array = [];
            var alamat_instansi_array = [];
            today = yyyy + '-' + mm + '-' + dd;
            $("#tanggal_pemesanan").attr("max", today);
            $("#batas_kontrak").attr("min", today);
            $("#tanggal_po").attr("max", today);
            $("#tanggal_do").attr("min", today);
            select_data(prm);
            load_part();
            load_jasa();
            // $('#customer_id').on('keyup change', function() {
            //     if ($(this).val() != "") {
            //         $('#msgcustomer_id').text("");
            //         $('#customer_id').removeClass('is-invalid');
            //         // var value = getCustomer($(this).val());
            //         $('#alamat').val(value.alamat);
            //         $('#telepon').val(value.telepon);
            //     } else if ($(this).val() == "") {
            //         $('#msgcustomer_id').text("Silahkan Pilih Customer");
            //         $('#customer_id').addClass('is-invalid');
            //     }
            // });
            function reset_akn() {
                $("#tanggal_pemesanan").val("");
                $("#instansi").val("");
                $("#satuan_kerja").val("");
                $("#no_paket").val("");
                $('input[type="radio"][name="status"]').prop("checked", false);
                $("#batas_kontrak").val("");
                $("#deskripsi").val("");
                $('#btntambah').attr("disabled", true);
            }

            function reset_penjualan() {
                $("#no_po").val("");
                $("#tanggal_po").val("");
                $('input[type="radio"][name="do"]').prop("checked", false);
                $("#do_detail_no").addClass("hide");
                $("#do_detail_tgl").addClass("hide");
                $("#no_do").val("");
                $("#tanggal_do").val("");
                $('#btntambah').attr("disabled", true);
            }

            var penjualan_produk_id = false;
            var variasi = false;
            var produk_jumlah = false;
            var produk_harga = false;

            var part_id = false;
            var part_jumlah = false;
            var part_harga = false;

            var jasa_id = false;
            var jasa_harga = false;

            function checkpenjualanform() {
                if ($('input[type="radio"][name="status"]:checked').val() == "sepakat") {
                    if ((!$("#no_urut").hasClass('is-invalid')) && ($("#no_paket")
                            .val() != "" && !$("#no_paket").hasClass('is-invalid')) && $(
                            "input[name='status']:checked")
                        .val() != "" && $('#tanggal_pemesanan').val() != "" && $("#batas_kontrak").val() != "") {
                        $('#pills-instansi-tab').removeClass('disabled');
                        if ($("#instansi").val() !== "" && $("#alamatinstansi").val() !== "" && $(".provinsi")
                            .val() !== "" && $("#satuan_kerja").val() != "" && $("#deskripsi").val() != "") {
                            $('#pills-produk-tab').removeClass('disabled');
                        } else {
                            $('#pills-produk-tab').addClass('disabled');
                        }
                    } else {
                        $('#pills-instansi-tab').addClass('disabled');
                        $('#pills-produk-tab').addClass('disabled');
                    }
                } else if (($('input[type="radio"][name="status"]:checked').val() == "draft") || ($(
                        'input[type="radio"][name="status"]:checked').val() == "batal")) {
                    if ((!$("#no_urut").hasClass('is-invalid')) && $(
                            "input[name='status']:checked").val() != "" && $('#tanggal_pemesanan').val() != "") {

                        if (($('#no_paket').val() != "" && $('input[type="checkbox"][name="isi_nopaket"]:checked')
                                .length > 0) || ($('#no_paket').val() == "" && $(
                                'input[type="checkbox"][name="isi_nopaket"]:checked').length <= 0) && !$(
                                "#no_paket").hasClass('is-invalid')) {
                            $('#pills-instansi-tab').removeClass('disabled');
                        } else {
                            $('#pills-instansi-tab').addClass('disabled');
                        }
                    } else {
                        $('#pills-instansi-tab').addClass('disabled');
                    }
                } else {
                    if ((!$("#no_urut").hasClass('is-invalid')) && ($("#no_paket")
                            .val() != "" && !$("#no_paket").hasClass('is-invalid')) && $(
                            "input[name='status']:checked")
                        .val() != "" && $('#tanggal_pemesanan').val() != "") {
                        $('#pills-instansi-tab').removeClass('disabled');
                        if ($("#instansi").val() !== "" && $("#alamatinstansi").val() !== "" && $("#satuan_kerja")
                            .val() != "" && $("#deskripsi").val() != "") {
                            $('#pills-produk-tab').removeClass('disabled');
                        } else {
                            $('#pills-produk-tab').addClass('disabled');
                        }
                    } else {
                        $('#pills-instansi-tab').addClass('disabled');
                        $('#pills-produk-tab').addClass('disabled');
                    }
                }
            }


            function checkvalidasi() {
                var jenis_array = [];
                $("input[id=jenis_pen]:checked").each(function() {
                    jenis_array.push($(this).val());
                });

                if ($.inArray("produk", jenis_array) !== -1) {
                    $('#produktable').find('.penjualan_produk_id').each(function() {
                        if ($(this).val() != "") {
                            penjualan_produk_id = true;
                        } else {
                            penjualan_produk_id = false;
                            return false;
                        }
                    });

                    $('#produktable').find('.variasi').each(function() {
                        if ($(this).val() != "") {
                            variasi = true;
                        } else {
                            variasi = false;
                            return false;
                        }
                    });

                    $('#produktable').find('.produk_jumlah').each(function() {
                        if ($(this).val() != "") {
                            produk_jumlah = true;
                        } else {
                            produk_jumlah = false;
                            return false;
                        }
                    });

                    $('#produktable').find('.produk_harga').each(function() {
                        if ($(this).val() != "") {
                            produk_harga = true;
                        } else {
                            produk_harga = false;
                            return false;
                        }
                    });
                } else if ($.inArray("produk", jenis_array) === -1) {
                    penjualan_produk_id = true;
                    variasi = true;
                    produk_jumlah = true;
                    produk_harga = true;
                }

                if ($.inArray("sparepart", jenis_array) !== -1) {
                    $('#parttable').find('.part_id').each(function() {
                        if ($(this).val() != null) {
                            part_id = true;
                        } else {
                            part_id = false;
                            return false;
                        }
                    });

                    $('#parttable').find('.part_jumlah').each(function() {
                        if ($(this).val() != "") {
                            part_jumlah = true;
                        } else {
                            part_jumlah = false;
                            return false;
                        }
                    });

                    $('#parttable').find('.part_harga').each(function() {
                        if ($(this).val() != "") {
                            part_harga = true;
                        } else {
                            part_harga = false;
                            return false;
                        }
                    });
                } else if ($.inArray("sparepart", jenis_array) === -1) {
                    part_id = true;
                    part_jumlah = true;
                    part_harga = true;
                }

                if ($.inArray("jasa", jenis_array) !== -1) {
                    $('#jasatable').find('.jasa_id').each(function() {
                        if ($(this).val() != null) {
                            jasa_id = true;
                        } else {
                            jasa_id = false;
                            return false;
                        }
                    });

                    $('#jasatable').find('.jasa_harga').each(function() {
                        if ($(this).val() != "") {
                            jasa_harga = true;
                        } else {
                            jasa_harga = false;
                            return false;
                        }
                    });
                } else if ($.inArray("jasa", jenis_array) === -1) {
                    jasa_id = true;
                    jasa_harga = true;
                }
                if ($('input[type="radio"][name="status"]:checked').val() == "sepakat") {
                    if ($('#customer_id').val() != "" && $('#tanggal_pemesanan').val() != "" && $("#instansi")
                        .val() !== "" && $("#alamatinstansi").val() !== "" && $(".provinsi").val() !== "" && $(
                            "#satuan_kerja").val() != "" && ($("#no_paket").val() != "" && !$("#no_paket").hasClass(
                            'is-invalid')) && $("input[name='status']:checked").val() != "" && $("#batas_kontrak")
                        .val() != "" && $("#deskripsi").val() != "" && ((!$("#no_urut")
                            .hasClass('is-invalid')) && !$("#no_paket").hasClass('is-invalid')) &&
                        penjualan_produk_id == true && variasi == true && produk_jumlah == true && produk_harga ==
                        true && part_id == true && part_jumlah == true && part_harga == true && jasa_id == true &&
                        jasa_harga == true) {
                        $('#btntambah').removeAttr("disabled");
                    } else {
                        $('#btntambah').attr("disabled", true);
                    }
                } else if (($('input[type="radio"][name="status"]:checked').val() == "draft") || ($(
                        'input[type="radio"][name="status"]:checked').val() == "batal")) {
                    if ($('#tanggal_pemesanan').val() != "" && $("#instansi").val() !== "" && $("#alamatinstansi")
                        .val() !== "" && $("#satuan_kerja").val() != "" && $("#deskripsi").val() != "" && ((!$("#no_urut").hasClass('is-invalid')) && !$("#no_paket")
                            .hasClass('is-invalid'))) {
                        if (($('#no_paket').val() != "" && $('input[type="checkbox"][name="isi_nopaket"]:checked')
                                .length > 0) || ($('#no_paket').val() == "" && $(
                                'input[type="checkbox"][name="isi_nopaket"]:checked').length <= 0) && !$(
                                "#no_paket").hasClass('is-invalid')) {
                            if ($('input[type="checkbox"][name="isi_produk"]:checked').length > 0) {
                                if (penjualan_produk_id == true && variasi == true && produk_jumlah == true &&
                                    produk_harga == true) {
                                    $('#btntambah').removeAttr("disabled");
                                } else {
                                    $('#btntambah').attr("disabled", true);
                                }
                            } else {
                                $('#btntambah').removeAttr("disabled");
                            }
                        } else {
                            $('#btntambah').attr("disabled", true);
                        }

                    } else {
                        $('#btntambah').attr("disabled", true);
                    }
                }
                // else if ($('input[type="radio"][name="status"]:checked').val() == "batal") {
                //     if ($('#tanggal_pemesanan').val() != "" && $("#instansi").val() !== "" && $("#alamatinstansi")
                //         .val() !== "" && $("#satuan_kerja").val() != "" && ($("#no_paket").val() != "" && !$(
                //             "#no_paket").hasClass('is-invalid')) && $("input[name='status']:checked").val() != "" &&
                //         $("#deskripsi").val() != "" && (($('#no_urut').val() != "" && !$("#no_urut").hasClass(
                //             'is-invalid')) && !$("#no_paket").hasClass('is-invalid'))) {
                //         if ($('input[type="checkbox"][name="isi_produk"]:checked').length > 0) {
                //             if (penjualan_produk_id == true && variasi == true && produk_jumlah == true &&
                //                 produk_harga == true) {
                //                 $('#btntambah').removeAttr("disabled");
                //             } else {
                //                 $('#btntambah').attr("disabled", true);
                //             }
                //         } else {
                //             $('#btntambah').removeAttr("disabled");
                //         }
                //     } else {
                //         $('#btntambah').attr("disabled", true);
                //     }
                // }
                else {
                    if ($('#tanggal_pemesanan').val() != "" && $("#instansi").val() !== "" && $("#alamatinstansi")
                        .val() !== "" && $("#satuan_kerja").val() != "" && ($("#no_paket").val() != "" && !$(
                            "#no_paket").hasClass('is-invalid')) && $("input[name='status']:checked").val() != "" &&
                        $("#deskripsi").val() != "" && ((!$("#no_urut").hasClass(
                            'is-invalid')) && !$("#no_paket").hasClass('is-invalid')) && penjualan_produk_id ==
                        true && variasi == true && produk_jumlah == true && produk_harga == true && part_id ==
                        true && part_jumlah == true && part_harga == true && jasa_id == true && jasa_harga == true
                    ) {

                        $('#btntambah').removeAttr("disabled");
                    } else {
                        $('#btntambah').attr("disabled", true);
                    }
                }
                checkpenjualanform();
            }

            function checkvalidasinonakn() {
                var jenis_array = [];
                $("input[id=jenis_pen]:checked").each(function() {
                    jenis_array.push($(this).val());
                });

                if ($.inArray("produk", jenis_array) !== -1) {
                    $('#produktable').find('.penjualan_produk_id').each(function() {
                        if ($(this).val() != "") {
                            penjualan_produk_id = true;
                        } else {
                            penjualan_produk_id = false;
                            return false;
                        }
                    });

                    $('#produktable').find('.variasi').each(function() {
                        if ($(this).val() != "") {
                            variasi = true;
                        } else {
                            variasi = false;
                            return false;
                        }
                    });

                    $('#produktable').find('.produk_jumlah').each(function() {
                        if ($(this).val() != "") {
                            produk_jumlah = true;
                        } else {
                            produk_jumlah = false;
                            return false;
                        }
                    });

                    $('#produktable').find('.produk_harga').each(function() {
                        if ($(this).val() != "") {
                            produk_harga = true;
                        } else {
                            produk_harga = false;
                            return false;
                        }
                    });
                } else if ($.inArray("produk", jenis_array) === -1) {
                    penjualan_produk_id = true;
                    variasi = true;
                    produk_jumlah = true;
                    produk_harga = true;
                }

                if ($.inArray("sparepart", jenis_array) !== -1) {
                    $('#parttable').find('.part_id').each(function() {
                        if ($(this).val() != null) {
                            part_id = true;
                        } else {
                            part_id = false;
                            return false;
                        }
                    });

                    $('#parttable').find('.part_jumlah').each(function() {
                        if ($(this).val() != "") {
                            part_jumlah = true;
                        } else {
                            part_jumlah = false;
                            return false;
                        }
                    });

                    $('#parttable').find('.part_harga').each(function() {
                        if ($(this).val() != "") {
                            part_harga = true;
                        } else {
                            part_harga = false;
                            return false;
                        }
                    });
                } else if ($.inArray("sparepart", jenis_array) === -1) {
                    part_id = true;
                    part_jumlah = true;
                    part_harga = true;
                }

                if ($.inArray("jasa", jenis_array) !== -1) {
                    $('#jasatable').find('.jasa_id').each(function() {
                        if ($(this).val() != null) {
                            jasa_id = true;
                        } else {
                            jasa_id = false;
                            return false;
                        }
                    });

                    $('#jasatable').find('.jasa_harga').each(function() {
                        if ($(this).val() != "") {
                            jasa_harga = true;
                        } else {
                            jasa_harga = false;
                            return false;
                        }
                    });
                } else if ($.inArray("jasa", jenis_array) === -1) {
                    jasa_id = true;
                    jasa_harga = true;
                }

                if ($('input[type="radio"][name="do"]:checked').val() == "yes") {
                    if ($('#customer_id').val() != "" && $("#no_po").val() != "" && $("#tanggal_po").val() != "" &&
                        $("#no_do").val() != "" && $("#tanggal_do").val() != "" && penjualan_produk_id == true &&
                        variasi == true && produk_jumlah == true && produk_harga == true && part_id == true &&
                        part_jumlah == true && part_harga == true && jasa_id == true && jasa_harga == true) {
                        $('#btntambah').removeAttr("disabled");
                    } else {
                        $('#btntambah').attr("disabled", true);
                    }
                } else if ($('input[type="radio"][name="do"]:checked').val() == "no") {
                    if ($('#customer_id').val() != "" && $("#no_po").val() != "" && $("#tanggal_po").val() != "" &&
                        penjualan_produk_id == true && variasi == true && produk_jumlah == true && produk_harga ==
                        true && part_id == true && part_jumlah == true && part_harga == true && jasa_id == true &&
                        jasa_harga == true) {
                        $('#btntambah').removeAttr("disabled");
                    } else {
                        $('#btntambah').attr("disabled", true);
                    }
                } else {
                    $('#btntambah').attr("disabled", true);
                }
            }
            $('#tanggal_pemesanan').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#batas_kontrak").attr("min", $(this).val());
                    $("#msgtanggal_pemesanan").text("");
                    $("#tanggal_pemesanan").removeClass('is-invalid');
                    checkvalidasi();
                } else if ($(this).val() == "") {
                    $("#msgtanggal_pemesanan").text("Isi Tanggal Pemesanan");
                    $("#tanggal_pemesanan").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                }
            });


            var prm;

            $('input[type="radio"][name="jenis_penjualan"]').on('change', function() {
                reset_akn();
                reset_penjualan();
                select_data($(this).val());
                if ($(this).val() == "ekatalog") {
                    $("#datajasa").addClass("hide");
                    $("#datapart").addClass("hide");
                    $("#dataproduk").removeClass("hide");
                    $("#nonakn").addClass("hide");
                    $("#akn").removeClass("hide");
                    $(".os-content-arrange").remove();
                    //cek
                    $("#belum_dsb").removeClass("hide");
                    $("#penj_prd").removeClass("hide");
                    $("#penj_spr").addClass("hide");
                    $("#penj_sem").addClass("hide");
                    $("#penj_jas").addClass("hide");
                    //
                    $("#alamat").val("");
                    $("#telepon").val("");

                    //
                    $("#customer_id").attr('disabled', true);
                    $("#customer_id").empty().trigger('change')
                    $("input[name=namadistributor][value='belum']").prop("checked", true);
                    $("input[name=jenis_penj][value='produk']").prop("checked", true);
                    $("input[name=jenis_penj][value='sparepart']").prop("checked", false);

                    //++
                    $("input[id=jenis_pen]").prop("checked", false);
                    $("input[id=jenis_pen][value='produk']").prop("checked", true);
                    $("input[id=jenis_pen][value='produk']").attr("disabled", false);
                    $("input[id=jenis_pen][value='sparepart']").attr("disabled", true);
                    $("input[id=jenis_pen][value='jasa']").attr("disabled", true);

                } else if ($(this).val() == "spa") {
                    $("#datajasa").addClass("hide");
                    $("#datapart").addClass("hide");
                    $("#dataproduk").removeClass("hide");
                    $("#nonakn").removeClass("hide");
                    $("#akn").addClass("hide");
                    $(".os-content-arrange").remove();
                    $("#customer_id").attr('disabled', false);
                    //cek
                    $("#belum_dsb").addClass("hide");
                    $("#penj_prd").removeClass("hide");
                    $("#penj_spr").removeClass("hide");
                    $("#penj_sem").removeClass("hide");
                    $("#penj_jas").removeClass("hide");
                    $("input[name=namadistributor][value='sudah']").prop("checked", true);
                    $("input[name=jenis_penj][value='produk']").prop("checked", true);
                    $("input[name=jenis_penj][value='sparepart']").prop("checked", false);


                    //Reset
                    $("input[id=jenis_pen][value='produk']").prop("checked", false);
                    $("input[id=jenis_pen][value='sparepart']").prop("checked", false);
                    $("input[id=jenis_pen][value='jasa']").prop("checked", false);


                    //++
                    $("input[id=jenis_pen][value='produk']").prop("checked", true);
                    $("input[id=jenis_pen][value='produk']").attr("disabled", false);
                    $("input[id=jenis_pen][value='sparepart']").attr("disabled", false);
                    $("input[id=jenis_pen][value='jasa']").attr("disabled", false);


                } else if ($(this).val() == "spb") {
                    $("#datapart").removeClass("hide");
                    $("#dataproduk").addClass("hide");
                    $("#datajasa").addClass("hide");
                    $("#nonakn").removeClass("hide");
                    $("#akn").addClass("hide");
                    $(".os-content-arrange").remove();
                    $("#customer_id").attr('disabled', false);
                    //cek
                    $("#belum_dsb").addClass("hide");
                    $("#penj_prd").removeClass("hide");
                    $("#penj_spr").removeClass("hide");
                    $("#penj_sem").removeClass("hide");
                    $("#penj_jas").removeClass("hide");
                    $("input[name=namadistributor][value='sudah']").prop("checked", true);
                    $("input[name=jenis_penj][value='produk']").prop("checked", false);
                    $("input[name=jenis_penj][value='sparepart']").prop("checked", true);

                    //Reset
                    $("input[id=jenis_pen][value='produk']").prop("checked", false);
                    $("input[id=jenis_pen][value='sparepart']").prop("checked", false);
                    $("input[id=jenis_pen][value='jasa']").prop("checked", false);

                    //++
                    $("input[id=jenis_pen][value='sparepart']").prop("checked", true);
                    $("input[id=jenis_pen][value='produk']").attr("disabled", false);
                    $("input[id=jenis_pen][value='sparepart']").attr("disabled", false);
                    $("input[id=jenis_pen][value='jasa']").attr("disabled", false);
                }
            });



            $('input[type="checkbox"][name="jenis_pen[]"]').on('change', function() {
                var jenis_arry = [];
                var x = $(this).val();

                $("input[id=jenis_pen]:checked").each(function() {
                    jenis_arry.push($(this).val());
                });

                if ($(":checkbox:checked").length == 0) {
                    jenis_arry.push(x);
                    $("input[id=jenis_pen][value=" + x + "]").prop("checked", true);
                }
                filter_jenis(jenis_arry);

                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });

            function filter_jenis(x) {
                if ($.inArray("produk", x) !== -1) {
                    $("#dataproduk").removeClass("hide");
                } else {
                    $("#dataproduk").addClass("hide");
                }
                if ($.inArray("jasa", x) !== -1) {
                    $("#datajasa").removeClass("hide");
                } else {
                    $("#datajasa").addClass("hide");
                }
                if ($.inArray("sparepart", x) !== -1) {
                    $("#datapart").removeClass("hide");
                } else {
                    $("#datapart").addClass("hide");
                }
            }

            $('input[type="radio"][name="do"]').on('change', function() {
                $('#btntambah').attr("disabled", true);
                $("#no_do").val("");
                $("#tanggal_do").val("");
                if ($(this).val() == "yes") {
                    $("#do_detail_no").removeClass("hide");
                    $("#do_detail_tgl").removeClass("hide");
                    checkvalidasinonakn();
                } else if ($(this).val() == "no") {
                    $("#do_detail_no").addClass("hide");
                    $("#do_detail_tgl").addClass("hide");
                    checkvalidasinonakn();
                }
            });

            $('#batas_kontrak').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgbatas_kontrak").text("");
                    $("#batas_kontrak").removeClass('is-invalid');

                } else if ($(this).val() == "") {
                    $("#msgbatas_kontrak").text("Tanggal Delivery Harus diisi");
                    $("#batas_kontrak").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                }
                checkvalidasi();
            });
            $('#instansi').on('keyup change select', function() {
                if ($(this).val() != "") {
                    var cust = $('#customer_id').val();
                    $("#msginstansi").text("");
                    $("#instansi").removeClass('is-invalid');
                    perencanaan(cust, $(this).val());

                } else if ($(this).val() == "") {
                    $("#msginstansi").text("Instansi Harus diisi");
                    $("#instansi").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                }
                checkvalidasi();
            });

            $('#pills-produk-tab').on('click', function() {
                var cust = $('#customer_id').val();
                var instansi = $('#instansi').val();
                perencanaan(cust, instansi);
            })

            $('#alamatinstansi').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgalamatinstansi").text("");
                    $("#alamatinstansi").removeClass('is-invalid');

                } else if ($(this).val() == "") {
                    $("#msgalamatinstansi").text("Alamat Instansi Harus diisi");
                    $("#alamatinstansi").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                }
                checkvalidasi();
            });
            $('input[type="radio"][name="namadistributor"]').on('change', function() {
                if ($(this).val() != "") {
                    if ($(this).val() == "sudah") {
                        $("#customer_id").attr('disabled', false);
                        var $newOption = $("<option selected='selected'></option>").val("213").text(
                            "PT. EMIINDO Jaya Bersama")
                        $(".customer_id").append($newOption).trigger('change');
                    } else {
                        $("#customer_id").attr('disabled', true);
                        $("#customer_id").empty().trigger('change')
                        $("#alamat").val("");
                        $("#telepon").val("");
                    }

                } else {
                    $("#msgstatus").text("Status Harus dipilih");
                    $("input[name='status']:checked").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                }

                checkvalidasi();
            });

            $('input[type="radio"][name="status"]').on('change', function() {
                $('#isi_produk_input').addClass('hide');
                if ($(this).val() != "") {

                    if ($(this).val() == "sepakat") {
                        $('#checkbox_nopaket').addClass('hide');
                        $('#isi_nopaket').prop("checked", false);
                        $('#isi_nopaket').val("true");
                        $('#no_paket').attr('readonly', false);
                        $("#dataproduk").removeClass("hide");
                        $("#batas_kontrak").attr('disabled', false);
                        $("#provinsi").attr('disabled', false);
                        var $newOption = $("<option selected='selected'></option>").val("11").text(
                            "Jawa Timur")
                        $(".provinsi").append($newOption).trigger('change');
                    } else if (($(this).val() == "draft") || ($(this).val() == "batal")) {
                        $('#checkbox_nopaket').removeClass('hide');
                        $('#isi_nopaket').prop("checked", false);
                        $('#isi_nopaket').val("");
                        $("#no_paket").removeClass('is-invalid');
                        $('#no_paket').attr('readonly', true);
                        $("#msgno_paket").text("");
                        $("#no_paket").removeClass('is-invalid');
                        $("#produktable tbody").empty();
                        $('#produktable tbody').append(trproduktable());
                        numberRowsProduk($("#produktable"));
                        $("#totalhargaprd").text("Rp. 0");
                        $("#dataproduk").addClass("hide");
                        $("#batas_kontrak").attr('disabled', true);
                        $("#provinsi").attr('disabled', true);
                        $("#provinsi").empty().trigger('change');
                        $('#isi_produk_input').removeClass('hide');
                    }
                    //  else if ($(this).val() == "batal") {
                    //     $('#checkbox_nopaket').addClass('hide');
                    //     $('#isi_nopaket').prop("checked", false);
                    //     $('#isi_nopaket').val("true");
                    //     $('#no_paket').attr('readonly', false);
                    //     $("#produktable tbody").empty();
                    //     $('#produktable tbody').append(trproduktable());
                    //     numberRowsProduk($("#produktable"));
                    //     $("#totalhargaprd").text("Rp. 0");
                    //     $("#dataproduk").addClass("hide");
                    //     $("#batas_kontrak").attr('disabled', true);
                    //     $("#provinsi").attr('disabled', true);
                    //     $("#provinsi").empty().trigger('change')
                    //     $('#isi_produk_input').removeClass('hide');
                    // }
                    else {
                        $('#checkbox_nopaket').addClass('hide');
                        $('#isi_nopaket').prop("checked", false);
                        $('#isi_nopaket').val("true");
                        $('#no_paket').attr('readonly', false);
                        $("#batas_kontrak").val("");
                        $("#batas_kontrak").attr('disabled', true);
                        $("#dataproduk").removeClass("hide");
                        $("#provinsi").attr('disabled', true);
                        $("#provinsi").empty().trigger('change')
                    }

                } else {
                    $('#checkbox_nopaket').addClass('hide');
                    $('#isi_nopaket').prop("checked", false);
                    $('#no_paket').attr('readonly', false);
                    $("#msgstatus").text("Status Harus dipilih");
                    $("input[name='status']:checked").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                }
                checkvalidasi();
            });

            $(document).ready(function() {
                $('input[type="checkbox"][name="isi_nopaket"]').change(function() {
                    if ($('input[type="checkbox"][name="isi_nopaket"]:checked').length > 0) {
                        $('#no_paket').attr('readonly', false);

                    } else {
                        $('#no_paket').attr('readonly', true);
                        $("#no_paket").removeClass('is-invalid');
                    }

                });
                checkvalidasi();
            });
            $('input[type="checkbox"][name="isi_produk"]').change(function() {
                $("#produktable tbody").empty();
                $('#produktable tbody').append(trproduktable());
                numberRowsProduk($("#produktable"));
                $("#totalhargaprd").text("Rp. 0");

                if ($('input[type="checkbox"][name="isi_produk"]:checked').length > 0) {
                    $("#dataproduk").removeClass("hide");
                } else {
                    $("#dataproduk").addClass("hide");
                }
                checkvalidasi();
            });

            $('input[type="checkbox"][name="isi_produk"]').change(function() {
                $("#produktable tbody").empty();
                $('#produktable tbody').append(trproduktable());
                numberRowsProduk($("#produktable"));
                $("#totalhargaprd").text("Rp. 0");

                if ($('input[type="checkbox"][name="isi_produk"]:checked').length > 0) {
                    $("#dataproduk").removeClass("hide");
                } else {
                    $("#dataproduk").addClass("hide");
                }
                checkvalidasi();
            });

            $('#satuan_kerja').on('keyup', function() {
                if ($(this).val() != "") {
                    $("#msgsatuan_kerja").text("");
                    $("#satuan_kerja").removeClass('is-invalid');

                } else if ($(this).val() == "") {
                    $("#msgsatuan_kerja").text("Satuan Kerja Harus diisi");
                    $("#satuan_kerja").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                }

                checkvalidasi();
            });

<<<<<<< HEAD
            $('#deskripsi').on('keyup', function() {
                if ($(this).val() != "") {
                    $("#msgdeskripsi").text("");
                    $("#deskripsi").removeClass('is-invalid');
                    checkvalidasi();
                } else if ($(this).val() == "") {
                    $("#msgdeskripsi").text("Deskripsi harus diisi");
                    $("#deskripsi").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
=======
        $('#tanggal_do').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msgtanggal_do").text("");
                $("#tanggal_do").removeClass('is-invalid');
                checkvalidasinonakn();
                // if ($("#no_po").val() != "" && $("#no_do").val() != "") {
                //     $('#btntambah').removeAttr("disabled");
                // } else {
                //     $('#btntambah').attr("disabled", true);
                // }
            } else if ($(this).val() == "") {
                $('#btntambah').attr("disabled", true);
                $("#msgtanggal_do").text("Tanggal DO Harus diisi");
                $("#tanggal_do").addClass('is-invalid');
            }
        });
        $("#customer_id").attr('disabled', true);
        $('.customer_id').select2({
            placeholder: "Pilih Customer",
            ajax: {
                minimumResultsForSearch: 20,
                dataType: 'json',
                theme: "bootstrap",
                delay: 250,
                type: 'GET',
                url: '/api/customer/select/',
                data: function(params) {
                    return {
                        term: params.term
                    }
                },

                processResults: function(data) {
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.nama
                            };
                        })
                    };
                },
            }
        }).change(function() {
            var id = $(this).val();
            // instansi_array.length = 0
            $.ajax({
                url: '/api/customer/select/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if(data[0] != undefined){
                        $('#alamat').val(data[0].alamat);
                        $('#telepon').val(data[0].telp);
                    }
>>>>>>> della_v
                }
            });



            $('#no_paket').on('keyup change', function() {
                if ($(this).val() != "") {
                    var values = $(this).val();
                    $.ajax({
                        type: 'POST',
                        dataType: 'JSON',
                        url: '/api/penjualan/check_no_paket/' + '0/' + values,
                        success: function(data) {
                            if (data > 0) {
                                $("#msgno_paket").text("No Paket tidak boleh sama");
                                $("#no_paket").addClass('is-invalid');
                                $('#btntambah').attr("disabled", true);
                            } else {
                                $("#msgno_paket").text("");
                                $("#no_paket").removeClass('is-invalid');
                                checkvalidasi();
                            }
                        },
                        error: function(data) {
                            $("#msgno_paket").text("No Paket tidak boleh sama");
                            $("#no_paket").addClass('is-invalid');
                            $('#btntambah').attr("disabled", true);
                        }
                    });
                } else if ($(this).val() == "") {
                    $("#msgno_paket").text("No Paket harus diisi");
                    $("#no_paket").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                }
            });

            $('#no_urut').on('keyup change', function() {
                if ($(this).val() != "") {
                    var values = $(this).val();
                    $.ajax({
                        type: 'POST',
                        dataType: 'JSON',
                        url: '/api/penjualan/check_no_urut/' + '0/' + values,
                        success: function(data) {
                            if (data > 0) {
                                $("#msgno_urut").text("No Urut tidak boleh sama");
                                $("#no_urut").addClass('is-invalid');
                                $('#btntambah').attr("disabled", true);
                            } else {
                                $("#msgno_urut").text("");
                                $("#no_urut").removeClass('is-invalid');
                            }
                        },
                        error: function(data) {
                            $("#msgno_urut").text("No Urut tidak boleh sama");
                            $("#no_urut").addClass('is-invalid');
                            $('#btntambah').attr("disabled", true);
                        }
                    });
                } else if ($(this).val() == "") {
                    $("#msgno_urut").text("");
                    $("#no_urut").removeClass('is-invalid');
                }
                checkvalidasi();
            });

            $('#no_po').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgno_po").text("");
                    $("#no_po").removeClass('is-invalid');
                    checkvalidasinonakn();
                    // if ($('input[type="radio"][name="do"]:checked').val() == "yes") {
                    //     if ($("#tanggal_po").val() != "" && $("#no_do").val() != "" && $("#tanggal_do").val() != "") {
                    //         $('#btntambah').removeAttr("disabled");
                    //     } else {
                    //         $('#btntambah').attr("disabled", true);
                    //     }
                    // } else {

                    //     if ($("#tanggal_po").val() != "") {
                    //         $('#btntambah').removeAttr("disabled");
                    //     } else {

                    //         $('#btntambah').attr("disabled", true);
                    //     }
                    // }
                } else if ($(this).val() == "") {
                    $('#btntambah').attr("disabled", true);
                    $("#msgno_po").text("Nomor PO Harus diisi");
                    $("#no_po").addClass('is-invalid');
                }
            });

            $('#tanggal_po').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgtanggal_po").text("");
                    $("#tanggal_po").removeClass('is-invalid');
                    $("#tanggal_do").attr("min", $(this).val());
                    checkvalidasinonakn();
                    // if ($('input[type="radio"][name="do"]:checked').val() == "yes") {
                    //     if ($("#no_po").val() != "" && $("#no_do").val() != "" && $("#tanggal_do").val() != "") {
                    //         $('#btntambah').removeAttr("disabled");
                    //     } else {
                    //         $('#btntambah').attr("disabled", true);
                    //     }
                    // } else {
                    //     if ($("#no_po").val() != "") {

                    //         $('#btntambah').removeAttr("disabled");
                    //     } else {

                    //         $('#btntambah').attr("disabled", true);
                    //     }
                    // }
                } else if ($(this).val() == "") {
                    $('#btntambah').attr("disabled", true);
                    $("#msgtanggal_po").text("Tanggal PO Harus diisi");
                    $("#tanggal_po").addClass('is-invalid');
                }
            });

            $('#no_do').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgno_do").text("");
                    $("#no_do").removeClass('is-invalid');
                    checkvalidasinonakn();
                    // if ($("#tanggal_po").val() != "" && $("#tanggal_do").val() != "") {
                    //     $('#btntambah').removeAttr("disabled");
                    // } else {
                    //     $('#btntambah').attr("disabled", true);
                    // }

                } else if ($(this).val() == "") {
                    $('#btntambah').attr("disabled", true);
                    $("#msgno_do").text("Nomor Do Harus diisi");
                    $("#no_do").addClass('is-invalid');
                }
            });

            $('#tanggal_do').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgtanggal_do").text("");
                    $("#tanggal_do").removeClass('is-invalid');
                    checkvalidasinonakn();
                    // if ($("#no_po").val() != "" && $("#no_do").val() != "") {
                    //     $('#btntambah').removeAttr("disabled");
                    // } else {
                    //     $('#btntambah').attr("disabled", true);
                    // }
                } else if ($(this).val() == "") {
                    $('#btntambah').attr("disabled", true);
                    $("#msgtanggal_do").text("Tanggal DO Harus diisi");
                    $("#tanggal_do").addClass('is-invalid');
                }
            });
            $("#customer_id").attr('disabled', true);
            $('.customer_id').select2({
                placeholder: "Pilih Customer",
                ajax: {
                    minimumResultsForSearch: 20,
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/customer/select/',
                    data: function(params) {
                        return {
                            term: params.term
                        }
                    },

                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.nama
                                };
                            })
                        };
                    },
                }
            }).change(function() {
                var id = $(this).val();
                // instansi_array.length = 0
                $.ajax({
                    url: '/api/customer/select/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data[0] != undefined) {
                            $('#alamat').val(data[0].alamat);
                            $('#telepon').val(data[0].telp);
                        }
                    }
                });

                // $.ajax({
                //     url: '/api/customer/get_instansi/' + id + '/' + yyyy,
                //     type: 'POST',
                //     dataType: 'json',
                //     success: function(data) {
                //         $.each(data, function(i, val) {
                //             instansi_array.push(val);
                //         });
                //     }
                // });

                if (id != "484") {
                    if ($('input[type="radio"][name="jenis_penjualan"]:checked').val() == "ekatalog") {
                        var ins_cust = $('#instansi').val();
                        if (ins_cust != "") {
                            perencanaan(customer_id, $('#instansi').val());
                        } else {
                            perencanaan(customer_id, "0");
                        }
                    }
                }
            });

            $('#produktable').on('keyup change', '.variasi', function() {
                var name = $(this).attr('name');
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var ppid = $(this).closest('tr').find('.penjualan_produk_id').attr('id');
                val = $('select[name="' + name + '"]').val();
                id = $('select[name="' + name + '"]').attr('data-id');
                vals = $('select[name="' + name + '"]').select2('data')[0];
                var kebutuhan = jumlah * vals.jumlah;
                if (cek_stok(vals.id) < kebutuhan) {
                    var jumlah_kekurangan = 0;
                    if (cek_stok(vals.id) < 0) {
                        jumlah_kekurangan = kebutuhan;
                    } else {
                        jumlah_kekurangan = Math.abs(cek_stok(vals.id) - kebutuhan);
                    }
                    $('select[name="variasi[' + ppid + '][' + id + ']"]').addClass('is-invalid');
                    $('span[name="ketstok[' + ppid + '][' + id + ']"]').text('Jumlah Kurang ' +
                        jumlah_kekurangan + ' dari Permintaan');
                } else if (cek_stok(vals.id) >= kebutuhan) {
                    $('select[name="variasi[' + ppid + '][' + id + ']"]').removeClass('is-invalid');
                    $('span[name="ketstok[' + ppid + '][' + id + ']"]').text('');
                }
            })

            function cek_stok(id) {
                var jumlah = 0;
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    async: false,
                    url: '/api/produk/variasi_stok/' + id,
                    success: function(data) {
                        jumlah = data;
                    },
                    error: function(data) {
                        jumlah = data;
                    }
                });
                return jumlah;
            }

            // function check_no_paket(values) {
            //     var hasil = "";
            //     $.ajax({
            //         type: 'POST',
            //         dataType: 'JSON',
            //         async: false,
            //         url: '/api/penjualan/check_no_paket/' + '0/' + values,
            //         success: function(data) {
            //             hasil = data;
            //         },
            //         error: function(data) {
            //             hasil = data;
            //         }
            //     });
            //     return hasil;
            // }

            function formatmoney(bilangan) {
                var number_string = bilangan.toString(),
                    sisa = number_string.length % 3,
                    rupiah = number_string.substr(0, sisa),
                    ribuan = number_string.substr(sisa).match(/\d{3}/g);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return rupiah;
            }

            function replaceAll(string, search, replace) {
                return string.split(search).join(replace);
            }

            function select_data(prm) {
                // $('.penjualan_produk_id').on('change', function() {
                //     for (i = 0; i < 3; ++i) {
                //         $("#produktable ").append('<tr><td>Detail Paket</td></tr>');
                //     }
                // });


                $('.penjualan_produk_id').select2({
                    placeholder: "Pilih Produk",
                    width: 'resolve',
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/penjualan_produk/select_param/' + prm,
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {

                            return {
                                results: $.map(data, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nama
                                    };
                                })
                            };
                        },
                    }
                });
                // .change(function() {
                //     var index = $(this).attr('id');
                //     var id = $(this).val();
                //     $.ajax({
                //         url: '/api/penjualan_produk/select/' + id,
                //         type: 'GET',
                //         dataType: 'json',
                //         success: function(res) {
                //             $('#produk_harga' + index).val(formatmoney(res[0].harga));
                //             var jumlah_pesan = $('#produk_jumlah' + index).val();
                //             if (jumlah_pesan == "") {
                //                 jumlah_pesan = 0;
                //             }
                //             console.log('subtotal' + formatmoney((res[0].harga) * jumlah_pesan));
                //             $('#produk_subtotal' + index).val(formatmoney((res[0].harga) * jumlah_pesan));
                //             var tes = $('#detail_produk' + index);
                //             tes.empty();
                //             var datas = "";
                //             tes.append(`<fieldset><legend><b>Detail Produk</b></legend>`);
                //             for (var x = 0; x < res[0].produk.length; x++) {
                //                 var data = [];
                //                 tes.append(`<div>`);
                //                 tes.append(`<div class="card-body blue-bg">
            //                             <h6>` + res[0].produk[x].nama + `</h6>
            //                             <select class="form-control variasi" name="variasi[` + index + `][` + x + `]" style="width:100%;" id="variasi` + index + `` + x + `" data-attr="variasi` + x + `" data-id="` + x + `"></select>
            //                             <span class="invalid-feedback d-block ketstok" name="ketstok[` + index + `][` + x + `]" id="ketstok` + index + `` + x + `" data-attr="ketstok` + x + `" data-id="` + x + `"></span>
            //                           </div>`);
                //                 if (res[0].produk[x].gudang_barang_jadi.length <= 1) {
                //                     data.push({
                //                         id: res[0].produk[x].gudang_barang_jadi[0].id,
                //                         text: res[0].produk[x].nama,
                //                         jumlah: res[0].produk[x].pivot.jumlah,
                //                         qt: cek_stok(res[0].produk[x].gudang_barang_jadi[0].id)
                //                     });
                //                 } else {
                //                     for (var y = 0; y < res[0].produk[x].gudang_barang_jadi.length; y++) {
                //                         data.push({
                //                             id: res[0].produk[x].gudang_barang_jadi[y].id,
                //                             text: res[0].produk[x].gudang_barang_jadi[y].nama,
                //                             jumlah: res[0].produk[x].pivot.jumlah,
                //                             qt: cek_stok(res[0].produk[x].gudang_barang_jadi[y].id)
                //                         });
                //                     }
                //                 }
                //                 $(`select[name="variasi[` + index + `][` + x + `]"]`).select2({
                //                     placeholder: 'Pilih Variasi',
                //                     data: data,
                //                     templateResult: function(data) {
                //                         var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                //                         return $span;
                //                     },
                //                     templateSelection: function(data) {
                //                         var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                //                         return $span;
                //                     }
                //                 });

                //                 $(`select[name="variasi[` + index + `][` + x + `]"]`).trigger("change");
                //                 tes.append(`</div>`)
                //             }
                //             tes.append(`</fieldset>`);
                //             // tes.html(datas);
                //         }
                //     });
                // });
            }

            function load_part() {
                $('.part_id').select2({
                    placeholder: "Pilih Part",
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'POST',
                        url: '/api/gk/sel-m-spare',
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {

                            //console.log(data);
                            return {
                                results: $.map(data, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nama
                                    };
                                })
                            };
                        },
                    }
                });
            }

            function load_jasa() {
                $('.jasa_id').select2({
                    placeholder: "Pilih Jasa",
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'POST',
                        url: '/api/gk/sel-m-jasa',
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {

                            //console.log(data);
                            return {
                                results: $.map(data, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nama
                                    };
                                })
                            };
                        },
                    }
                });
            }

            function totalhargaprd() {
                var totalharga = 0;
                $('#produktable').find('tr .produk_subtotal').each(function() {
                    var subtotal = replaceAll($(this).val(), '.', '');
                    totalharga = parseInt(totalharga) + parseInt(subtotal);
                    $("#totalhargaprd").text("Rp. " + totalharga.toString().replace(
                        /(\d)(?=(\d{3})+(?!\d))/g, "$1."));
                })
            }

            function totalhargapart() {
                var totalharga = 0;
                $('#parttable').find('tr .part_subtotal').each(function() {
                    var subtotal = replaceAll($(this).val(), '.', '');
                    totalharga = parseInt(totalharga) + parseInt(subtotal);
                    $("#totalhargapart").text("Rp. " + totalharga.toString().replace(
                        /(\d)(?=(\d{3})+(?!\d))/g, "$1."));
                })
            }

            function totalhargajasa() {
                var totalharga = 0;
                $('#jasatable').find('tr .jasa_subtotal').each(function() {
                    var subtotal = replaceAll($(this).val(), '.', '');
                    totalharga = parseInt(totalharga) + parseInt(subtotal);
                    $("#totalhargajasa").text("Rp. " + totalharga.toString().replace(
                        /(\d)(?=(\d{3})+(?!\d))/g, "$1."));
                })
            }

            $("#produktable").on('keyup change', '.penjualan_produk_id', function() {
                var index = $(this).attr('id');
                var id = $(this).val();
                $.ajax({
                    url: '/api/penjualan_produk/select/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        $('#rencana_id' + index).val("");
                        $('#produk_harga' + index).val(formatmoney(res[0].harga));
                        var jumlah_pesan = $('#produk_jumlah' + index).val();
                        if (jumlah_pesan == "") {
                            jumlah_pesan = 0;
                        }
                        $('#produk_subtotal' + index).val(formatmoney((res[0].harga) *
                            jumlah_pesan));
                        var tes = $('#detail_produk' + index);
                        tes.empty();
                        var datas = "";
                        tes.append(`<fieldset><legend><b>Detail Produk</b></legend>`);
                        for (var x = 0; x < res[0].produk.length; x++) {
                            var data = [];
                            tes.append(`<div>`);
                            tes.append(`<div class="card-body blue-bg">
                                        <h6>` + res[0].produk[x].nama + `</h6>
                                        <select class="form-control variasi" name="variasi[` + index + `][` + x +
                                `]" style="width:100%;" id="variasi` + index + `` + x +
                                `" data-attr="variasi` + x + `" data-id="` + x + `"></select>
                                        <span class="invalid-feedback d-block ketstok" name="ketstok[` + index + `][` +
                                x + `]" id="ketstok` + index + `` + x +
                                `" data-attr="ketstok` + x + `" data-id="` + x + `"></span>
                                      </div>`);
                            if (res[0].produk[x].gudang_barang_jadi.length <= 1) {
                                data.push({
                                    id: res[0].produk[x].gudang_barang_jadi[0].id,
                                    text: res[0].produk[x].nama,
                                    jumlah: res[0].produk[x].pivot.jumlah,
                                    qt: cek_stok(res[0].produk[x].gudang_barang_jadi[0]
                                        .id)
                                });
                            } else {
                                for (var y = 0; y < res[0].produk[x].gudang_barang_jadi
                                    .length; y++) {

                                    var nama_var = "";
                                    if (res[0].produk[x].gudang_barang_jadi[y].nama != "") {
                                        nama_var = res[0].produk[x].gudang_barang_jadi[y].nama;
                                    } else {
                                        nama_var = res[0].produk[x].nama;
                                    }

                                    data.push({
                                        id: res[0].produk[x].gudang_barang_jadi[y].id,
                                        text: nama_var,
                                        jumlah: res[0].produk[x].pivot.jumlah,
                                        qt: cek_stok(res[0].produk[x]
                                            .gudang_barang_jadi[y].id)
                                    });
                                }
                            }
                            $(`select[name="variasi[` + index + `][` + x + `]"]`).select2({
                                placeholder: 'Pilih Variasi',
                                data: data,
                                templateResult: function(data) {
                                    var $span = $(
                                        `<div><span class="col-form-label">` +
                                        data.text +
                                        `</span><span class="badge blue-text float-right col-form-label stok" data-id="` +
                                        data.qt + `">` + data.qt +
                                        `</span></div>`);
                                    return $span;
                                },
                                templateSelection: function(data) {
                                    var $span = $(
                                        `<div><span class="col-form-label">` +
                                        data.text +
                                        `</span><span class="badge blue-text float-right col-form-label stok" data-id="` +
                                        data.qt + `">` + data.qt +
                                        `</span></div>`);
                                    return $span;
                                }
                            });

                            $(`select[name="variasi[` + index + `][` + x + `]"]`).trigger(
                                "change");
                            tes.append(`</div>`)
                        }
                        tes.append(`</fieldset>`);
                        // tes.html(datas);
                    }
                });
                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }

            });
            $("#produktable").on('keyup change', '.produk_jumlah', function() {
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var harga = $(this).closest('tr').find('.produk_harga').val();
                var ongkir = $(this).closest('tr').find('.produk_ongkir').val();
                var subtotal = $(this).closest('tr').find('.produk_subtotal');
                var ketstok = $(this).closest('tr').find('.ketstok');
                var variasi = $(this).closest('tr').find('.variasi');
                var ppid = $(this).closest('tr').find('.penjualan_produk_id').attr('id');
                if (jumlah != "" && harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    var ongkircvrt = replaceAll(ongkir, '.', '');
                    if (ongkircvrt == "") {
                        ongkircvrt = "0";
                        $(this).closest('tr').find('.produk_ongkir').val("0");
                    }
                    subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                    totalhargaprd();
                    for (var i = 0; i < variasi.length; i++) {
                        var variasires = $('select[name="variasi[' + ppid + '][' + i + ']"]').select2(
                            'data')[0];
                        var kebutuhan = jumlah * variasires.jumlah;

                        if (cek_stok(variasires.id) < kebutuhan) {
                            var jumlah_kekurangan = 0;
                            if (cek_stok(variasires.id) < 0) {
                                jumlah_kekurangan = kebutuhan;
                            } else {
                                jumlah_kekurangan = Math.abs(cek_stok(variasires.id) - kebutuhan);
                            }
                            $('select[name="variasi[' + ppid + '][' + i + ']"]').addClass('is-invalid');
                            $('span[name="ketstok[' + ppid + '][' + i + ']"]').text('Jumlah Kurang ' +
                                jumlah_kekurangan + ' dari Permintaan');
                        } else if (cek_stok(variasires.id) >= kebutuhan) {
                            $('select[name="variasi[' + ppid + '][' + i + ']"]').removeClass('is-invalid');
                            $('span[name="ketstok[' + ppid + '][' + i + ']"]').text('');
                        }
                    }
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargaprd();
                    variasi.removeClass('is-invalid');
                    ketstok.text('');
                }
                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });
            $("#produktable").on('keyup change', '.produk_harga', function() {
                var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(result);
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var harga = $(this).closest('tr').find('.produk_harga').val();
                var ongkir = $(this).closest('tr').find('.produk_ongkir').val();
                var subtotal = $(this).closest('tr').find('.produk_subtotal');
                if (jumlah != "" && harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    var ongkircvrt = replaceAll(ongkir, '.', '');
                    if (ongkircvrt == "") {
                        ongkircvrt = "0";
                        $(this).closest('tr').find('.produk_ongkir').val("0");
                    }
                    subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                    totalhargaprd();
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargaprd();
                }
                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });
            $("#produktable").on('keyup change', '.produk_ongkir', function() {
                var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(result);
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var harga = $(this).closest('tr').find('.produk_harga').val();
                var ongkir = $(this).closest('tr').find('.produk_ongkir').val();
                var subtotal = $(this).closest('tr').find('.produk_subtotal');
                if (jumlah != "" && harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    var ongkircvrt = replaceAll(ongkir, '.', '');
                    subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                    totalhargaprd();
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargaprd();
                }
                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });
            $('#perencanaantable').on('click', '#btntransfer', function() {
                var id = $(this).closest('tr').find('#btntransfer').attr('data-id');
                var nama_produk = $(this).closest('tr').find('#btntransfer').attr('data-nama_produk');
                var produk_id = $(this).closest('tr').find('#btntransfer').attr('data-produk');
                var jumlah = $(this).closest('tr').find('#btntransfer').attr('data-jumlah');
                var harga = $(this).closest('tr').find('#btntransfer').attr('data-harga');
                transferproduk(id, nama_produk, produk_id, jumlah, harga);
            });

            function transferproduk(id, nama_produk, produk_id, jumlah, harga) {
                var data = `<tr>
                    <td></td>
                    <td>
                        <div class="form-group">
                            <select name="penjualan_produk_id[]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                <option value="` + produk_id + `">` + nama_produk +
                    `</option>
                            </select>
                            <div class="detailjual" id="tes0">
                            </div>
                        </div>
                        <div id="detail_produk" class="detail_produk"></div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">
                            <div class="input-group">
                                <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[]" id="produk_jumlah0" value="` +
                    jumlah +
                    `" style="width:100%;">

                            </div>
                            <small id="produk_ketersediaan"></small>
                        </div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">

                            <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" value="` +
                    formatmoney(harga) +
                    `" placeholder="Masukkan Harga" style="width:100%;"/>
                        </div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">

                            <input type="text" class="form-control produk_ongkir" name="produk_ongkir[]" id="produk_ongkir0" placeholder="Masukkan Ongkir" value="0" style="width:100%;"/>
                        </div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">
                            <input type="text" class="form-control produk_subtotal" name="produk_subtotal[]" id="produk_subtotal0" placeholder="Masukkan Subtotal" value="` +
                    formatmoney(jumlah * parseInt(harga)) +
                    `" style="width:100%;" readonly/>
                        </div>
                    </td>
                    <td hidden><input type="hidden" class="rencana_id" name="rencana_id[]" id="rencana_id0" readonly value="` +
                    id + `"></td>
                    <td>
                        <a id="removerowproduk"><i class="fas fa-minus" style="color: red;"></i></a>
                    </td>
                </tr>`;

                if ($('#produktable > tbody > tr').length <= 0) {
                    $('#produktable tbody').append(data);
                    select_data(prm);
                    numberRowsProduk($("#produktable"));
                    totalhargaprd();
                } else {

                    $('#produktable tbody tr:last').after(data);
                    select_data(prm);
                    numberRowsProduk($("#produktable"));
                    totalhargaprd();
                }
                var index = $('#produktable > tbody > tr').length - 1;
                $.ajax({
                    url: '/api/penjualan_produk/select/' + produk_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        var tes = $('#detail_produk' + index);
                        tes.empty();
                        var datas = "";
                        tes.append(`<fieldset><legend><b>Detail Produk</b></legend>`);
                        for (var x = 0; x < res[0].produk.length; x++) {
                            var data = [];
                            tes.append(`<div>`);
                            tes.append(`<div class="card-body blue-bg">
                                        <h6>` + res[0].produk[x].nama + `</h6>
                                        <select class="form-control variasi" name="variasi[` + index + `][` + x +
                                `]" style="width:100%;" id="variasi` + index + `` + x +
                                `" data-attr="variasi` + x + `" data-id="` + x + `"></select>
                                        <span class="invalid-feedback d-block ketstok" name="ketstok[` + index + `][` +
                                x + `]" id="ketstok` + index + `` + x + `" data-attr="ketstok` + x +
                                `" data-id="` + x + `"></span>
                                      </div>`);
                            if (res[0].produk[x].gudang_barang_jadi.length <= 1) {
                                data.push({
                                    id: res[0].produk[x].gudang_barang_jadi[0].id,
                                    text: res[0].produk[x].nama,
                                    jumlah: res[0].produk[x].pivot.jumlah,
                                    qt: cek_stok(res[0].produk[x].gudang_barang_jadi[0].id)
                                });
                            } else {
                                for (var y = 0; y < res[0].produk[x].gudang_barang_jadi.length; y++) {
                                    data.push({
                                        id: res[0].produk[x].gudang_barang_jadi[y].id,
                                        text: res[0].produk[x].gudang_barang_jadi[y].nama,
                                        jumlah: res[0].produk[x].pivot.jumlah,
                                        qt: cek_stok(res[0].produk[x].gudang_barang_jadi[y].id)
                                    });
                                }
                            }
                            $(`select[name="variasi[` + index + `][` + x + `]"]`).select2({
                                placeholder: 'Pilih Variasi',
                                data: data,
                                templateResult: function(data) {
                                    var $span = $(`<div><span class="col-form-label">` +
                                        data.text +
                                        `</span><span class="badge blue-text float-right col-form-label stok" data-id="` +
                                        data.qt + `">` + data.qt + `</span></div>`);
                                    return $span;
                                },
                                templateSelection: function(data) {
                                    var $span = $(`<div><span class="col-form-label">` +
                                        data.text +
                                        `</span><span class="badge blue-text float-right col-form-label stok" data-id="` +
                                        data.qt + `">` + data.qt + `</span></div>`);
                                    return $span;
                                }
                            });

                            $(`select[name="variasi[` + index + `][` + x + `]"]`).trigger("change");
                            tes.append(`</div>`)
                        }
                        tes.append(`</fieldset>`);
                        // tes.html(datas);
                    }
                });
            }

            //PRODUK TABLE
            function numberRowsProduk($t) {
                var c = 0 - 2;
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;
                    $(el).find('.penjualan_produk_id').attr('name', 'penjualan_produk_id[' + j + ']');
                    $(el).find('.penjualan_produk_id').attr('id', j);
                    var variasi = $(el).find('.variasi');
                    for (var k = 0; k < variasi.length; k++) {
                        $(el).find('select[name="variasi[' + j + '][' + k + ']]"').select2();
                        $(el).find('select[data-attr="variasi' + k + '"]').attr('name', 'variasi[' + j +
                            '][' + k + ']');
                        $(el).find('select[data-attr="variasi' + k + '"]').attr('id', 'variasi' + j + '' +
                            k);
                        $(el).find('span[data-attr="ketstok' + k + '"]').attr('name', 'ketstok[' + j +
                            '][' + k + ']');
                        $(el).find('span[data-attr="ketstok' + k + '"]').attr('id', 'ketstok' + j + '' + k)

                    }
                    $(el).find('.detail_produk').attr('id', 'detail_produk' + j);
                    $(el).find('.produk_ongkir').attr('id', 'produk_ongkir' + j);
                    $(el).find('.produk_ongkir').attr('id', 'produk_ongkir' + j);
                    $(el).find('.produk_harga').attr('id', 'produk_harga' + j);
                    $(el).find('.produk_harga').attr('name', 'produk_harga[' + j + ']');
                    $(el).find('.produk_jumlah').attr('id', 'produk_jumlah' + j);
                    $(el).find('.produk_jumlah').attr('name', 'produk_jumlah[' + j + ']');
                    $(el).find('.produk_subtotal').attr('id', 'produk_subtotal' + j);
                    $(el).find('.produk_subtotal').attr('name', 'produk_subtotal[' + j + ']');
                    $(el).find('.rencana_id').attr('id', 'rencana_id' + j);
                    $(el).find('.rencana_id').attr('name', 'rencana_id[' + j + ']');
                    $(el).find('.detail_jual').attr('id', 'detail_jual' + j);
                    select_data(prm);
                });
            }

            function trproduktable() {
                var data = `<tr>
                <td></td>
                <td>
                    <div class="form-group select_item">
                        <select name="penjualan_produk_id[]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                            <option value=""></option>
                        </select>
                        <div class="detailjual" id="tes0">
                        </div>
                    </div>
                    <div id="detail_produk" class="detail_produk"></div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <div class="input-group">
                            <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[]" id="produk_jumlah" style="width:100%;">

                        </div>
                        <small id="produk_ketersediaan"></small>
                    </div>
                </td>
                <td>
                <div class="form-check col-form-label">
                    <input type="checkbox" class="form-check-input stok_dist" id="stok_dist" name="stok_dist[]" value="1">
                </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">

                        <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;"/>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">

                        <input type="text" class="form-control produk_ongkir" name="produk_ongkir[]" id="produk_ongkir0" placeholder="Masukkan Ongkir" style="width:100%;"/>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">

                        <input type="text" class="form-control produk_subtotal" name="produk_subtotal[]" id="produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" readonly/>
                    </div>
                </td>
                <td hidden><input type="hidden" class="rencana_id" name="rencana_id[]" id="rencana_id0" readonly></td>
                <td>
                    <a id="removerowproduk"><i class="fas fa-minus" style="color: red;"></i></a>
                </td>
            </tr>`;
                return data;
            }

            $('#addrowproduk').on('click', function() {
                if ($('#produktable > tbody > tr').length <= 0) {
                    $('#produktable tbody').append(trproduktable());
                    numberRowsProduk($("#produktable"));
                } else {
                    $('#produktable tbody tr:last').after(trproduktable());
                    numberRowsProduk($("#produktable"));
                }
                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });

            $('#produktable').on('click', '#removerowproduk', function(e) {
                $(this).closest('tr').remove();
                numberRowsProduk($("#produktable"));
                totalhargaprd();
                if ($('#produktable > tbody > tr').length <= 0) {
                    $('#produktable tbody').append(trproduktable());
                    numberRowsProduk($("#produktable"));
                    $("#totalhargaprd").text("0");
                }
                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });

            function numberRowsPart($t) {
                var c = 0 - 2;
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;
                    $(el).find('.part_id').attr('name', 'part_id[' + j + ']');
                    $(el).find('.part_id').attr('id', 'part_id' + j);
                    $(el).find('.part_jumlah').attr('name', 'part_jumlah[' + j + ']');
                    $(el).find('.part_jumlah').attr('id', 'part_jumlah' + j);
                    $(el).find('.part_harga').attr('name', 'part_harga[' + j + ']');
                    $(el).find('.part_harga').attr('id', 'part_harga' + j);
                    $(el).find('.part_subtotal').attr('name', 'part_subtotal[' + j + ']');
                    $(el).find('.part_subtotal').attr('id', 'part_subtotal' + j);
                    load_part();
                });
            }

            function trparttable() {
                var data = `
            <tr>
                <td>1</td>
                <td>
                    <div class="form-group select_item">
                        <select class="select2 form-control select-info custom-select part_id" name="part_id[]" id="part_id0" width="100%">
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                            <input type="number" class="form-control part_jumlah" name="part_jumlah[]" id="part_jumlah0" style="width:100%;">

                        <small id="part_ketersediaan"></small>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">

                        <input type="text" class="form-control part_harga" name="part_harga[]" id="part_harga0" placeholder="Masukkan Harga" style="width:100%;" />
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">

                        <input type="text" class="form-control part_subtotal" name="part_subtotal[]" id="part_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" readonly />
                    </div>
                </td>
                <td>
                    <a id="removerowpart"><i class="fas fa-minus" style="color: red"></i></a>
                </td>
            </tr>`;
                return data;
            }

            $('#addrowpart').on('click', function() {
                if ($('#parttable > tbody > tr').length <= 0) {
                    $('#parttable tbody').append(trparttable());
                    numberRowsPart($("#parttable"));
                } else {
                    $('#parttable tbody tr:last').after(trparttable());
                    numberRowsPart($("#parttable"));
                }

                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });

            function numberRowsJasa($t) {
                var c = 0 - 2;
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;
                    $(el).find('.jasa_id').attr('name', 'jasa_id[' + j + ']');
                    $(el).find('.jasa_id').attr('id', 'jasa_id' + j);
                    $(el).find('.jasa_jumlah').attr('name', 'jasa_jumlah[' + j + ']');
                    $(el).find('.jasa_jumlah').attr('id', 'jasa_jumlah' + j);
                    $(el).find('.jasa_harga').attr('name', 'jasa_harga[' + j + ']');
                    $(el).find('.jasa_harga').attr('id', 'jasa_harga' + j);
                    $(el).find('.jasa_subtotal').attr('name', 'jasa_subtotal[' + j + ']');
                    $(el).find('.jasa_subtotal').attr('id', 'jasa_subtotal' + j);
                    load_jasa();
                });
            }

            function trjasatable() {
                var data = `
            <tr>
                <td>1</td>
                <td>
                    <div class="form-group select_item">
                        <select class="select2 form-control select-info custom-select jasa_id" name="jasa_id[]" id="jasa_id0" width="100%">
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">

                        <input type="text" class="form-control jasa_harga" name="jasa_harga[]" id="jasa_harga0" placeholder="Masukkan Harga" style="width:100%;" />
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">

                        <input type="text" class="form-control jasa_subtotal" name="jasa_subtotal[]" id="jasa_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" readonly />
                    </div>
                </td>
                <td>
                    <a id="removerowjasa"><i class="fas fa-minus" style="color: red"></i></a>
                </td>
            </tr>`;
                return data;
            }

            $('#addrowjasa').on('click', function() {
                if ($('#jasatable > tbody > tr').length <= 0) {
                    $('#jasatable tbody').append(trjasatable());
                    numberRowsPart($("#jasatable"));
                } else {
                    $('#jasatable tbody tr:last').after(trjasatable());
                    numberRowsJasa($("#jasatable"));
                }

                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }



                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });

            $("#parttable").on('keyup change', '.part_id', function() {
                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });

            $("#parttable").on('keyup change', '.part_jumlah', function() {
                var jumlah = $(this).closest('tr').find('.part_jumlah').val();
                var harga = $(this).closest('tr').find('.part_harga').val();
                var subtotal = $(this).closest('tr').find('.part_subtotal');
                if (jumlah != "" && harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    subtotal.val(formatmoney(jumlah * parseInt(hargacvrt)));
                    totalhargapart();
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargapart();
                }

                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });

            $("#parttable").on('keyup change', '.part_harga', function() {
                var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(result);
                var jumlah = $(this).closest('tr').find('.part_jumlah').val();
                var harga = $(this).closest('tr').find('.part_harga').val();
                var subtotal = $(this).closest('tr').find('.part_subtotal');

                if (jumlah != "" && harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    subtotal.val(formatmoney(jumlah * parseInt(hargacvrt)));
                    totalhargapart();
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargapart();
                }

                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });

            $("#jasatable").on('keyup change', '.jasa_id', function() {
                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });

            $("#jasatable").on('keyup change', '.jasa_harga', function() {
                var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(result);
                var harga = $(this).closest('tr').find('.jasa_harga').val();
                var subtotal = $(this).closest('tr').find('.jasa_subtotal');

                if (harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    subtotal.val(formatmoney(1 * parseInt(hargacvrt)));
                    totalhargajasa();
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargajasa();
                }
                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });

            $('#parttable').on('click', '#removerowpart', function(e) {
                $(this).closest('tr').remove();
                numberRowsPart($("#parttable"));
                totalhargapart();
                if ($('#parttable > tbody > tr').length <= 0) {
                    $('#parttable tbody').append(trparttable());
                    numberRowsPart($("#parttable"));
                    $("#totalhargapart").text("Rp. 0");
                }

                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });

            $('#jasatable').on('click', '#removerowjasa', function(e) {
                $(this).closest('tr').remove();
                numberRowsJasa($("#jasatable"));
                totalhargajasa();
                if ($('#jasatable > tbody > tr').length <= 0) {
                    $('#jasatable tbody').append(trjasatable());
                    numberRowsJasa($("#jasatable"));
                    $("#totalhargajasa").text("Rp. 0");
                }
                if ($('input[name="jenis_penjualan"]:checked').val() == "ekatalog") {
                    checkvalidasi();
                } else {
                    checkvalidasinonakn();
                }
            });

            $('.provinsi').select2({
                placeholder: "Pilih Provinsi",
                ajax: {
                    minimumResultsForSearch: 20,
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/provinsi/select',
                    data: function(params) {
                        return {
                            term: params.term
                        }
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.nama
                                };
                            })
                        };
                    },
                }
            }).change(function() {
                if ($(this).val() != "") {
                    $("#msgprovinsi").text("");
                    $("#provinsi").removeClass('is-invalid');
                    // if ($("#tanggal_pemesanan").val() != "" && $("#instansi").val() != "" && $("#satuan_kerja").val() != "" && $("#no_paket").val() != "" && $("input[name='status']:checked").val() != "" && $("#batas_kontrak").val() != "") {
                    //     $('#btntambah').removeAttr("disabled");
                    // } else {
                    //     $('#btntambah').attr("disabled", true);
                    // }

                    checkvalidasi();
                } else if ($(this).val() == "") {
                    $("#msgprovinsi").text("Provinsi harus diisi");
                    $("#provinsi").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                }
            });



            function autocomplete(inp, arr) {
                /*the autocomplete function takes two arguments,
                the text field element and an array of possible autocompleted values:*/
                var currentFocus;
                /*execute a function when someone writes in the text field:*/
                inp.addEventListener("input", function(e) {
                    var a, b, i, val = this.value;
                    /*close any already open lists of autocompleted values*/
                    closeAllLists();
                    if (!val) {
                        return false;
                    }
                    currentFocus = -1;
                    /*create a DIV element that will contain the items (values):*/
                    a = document.createElement("DIV");
                    a.setAttribute("id", this.id + "autocomplete-list");
                    a.setAttribute("class", "autocomplete-items");
                    /*append the DIV element as a child of the autocomplete container:*/
                    this.parentNode.appendChild(a);
                    /*for each item in the array...*/
                    for (i = 0; i < arr.length; i++) {
                        /*check if the item starts with the same letters as the text field value:*/
                        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                            /*create a DIV element for each matching element:*/
                            b = document.createElement("DIV");
                            /*make the matching letters bold:*/
                            b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                            b.innerHTML += arr[i].substr(val.length);
                            /*insert a input field that will hold the current array item's value:*/
                            b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                            /*execute a function when someone clicks on the item value (DIV element):*/
                            b.addEventListener("click", function(e) {
                                /*insert the value for the autocomplete text field:*/
                                inp.value = this.getElementsByTagName("input")[0].value;
                                /*close the list of autocompleted values,
                                or any other open lists of autocompleted values:*/
                                closeAllLists();
                            });
                            a.appendChild(b);
                        }
                    }
                });
                /*execute a function presses a key on the keyboard:*/
                inp.addEventListener("keydown", function(e) {
                    var x = document.getElementById(this.id + "autocomplete-list");
                    if (x) x = x.getElementsByTagName("div");
                    if (e.keyCode == 40) {
                        /*If the arrow DOWN key is pressed,
                        increase the currentFocus variable:*/
                        currentFocus++;
                        /*and and make the current item more visible:*/
                        addActive(x);
                    } else if (e.keyCode == 38) { //up
                        /*If the arrow UP key is pressed,
                        decrease the currentFocus variable:*/
                        currentFocus--;
                        /*and and make the current item more visible:*/
                        addActive(x);
                    } else if (e.keyCode == 13) {
                        /*If the ENTER key is pressed, prevent the form from being submitted,*/
                        e.preventDefault();
                        if (currentFocus > -1) {
                            /*and simulate a click on the "active" item:*/
                            if (x) x[currentFocus].click();
                        }
                    }
                });

                function addActive(x) {
                    /*a function to classify an item as "active":*/
                    if (!x) return false;
                    /*start by removing the "active" class on all items:*/
                    removeActive(x);
                    if (currentFocus >= x.length) currentFocus = 0;
                    if (currentFocus < 0) currentFocus = (x.length - 1);
                    /*add class "autocomplete-active":*/
                    x[currentFocus].classList.add("autocomplete-active");
                }

                function removeActive(x) {
                    /*a function to remove the "active" class from all autocomplete items:*/
                    for (var i = 0; i < x.length; i++) {
                        x[i].classList.remove("autocomplete-active");
                    }
                }

                function closeAllLists(elmnt) {
                    /*close all autocomplete lists in the document,
                    except the one passed as an argument:*/
                    var x = document.getElementsByClassName("autocomplete-items");
                    for (var i = 0; i < x.length; i++) {
                        if (elmnt != x[i] && elmnt != inp) {
                            x[i].parentNode.removeChild(x[i]);
                        }
                    }
                }
                /*execute a function when someone clicks in the document:*/
                document.addEventListener("click", function(e) {
                    closeAllLists(e.target);
                });
            }

            /*An array containing all the country names in the world:*/



            $("#alamatinstansi").autocomplete({
                source: function(request, response) {

                    $.ajax({
                        dataType: 'json',
                        url: "/api/penjualan/check_alamat",
                        data: {
                            term: request.term
                        },

                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.alamat,
                                    id: el.id
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });

            $("#instansi").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        dataType: 'json',
                        url: '/api/customer/get_instansi/' + $('#customer_id').val() + '/' +
                            yyyy,
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el,
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });

            $("#satuan_kerja").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        dataType: 'json',
                        url: "/api/ekatalog/all_satuan",
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.satuan,
                                    id: el.id
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });

            $("#deskripsi").autocomplete({
                source: function(request, response) {

                    $.ajax({
                        dataType: 'json',
                        url: "/api/ekatalog/all_deskripsi",
                        data: {
                            term: request.term
                        },

                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.deskripsi,
                                    id: el.id
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });

            $('input[type="radio"][name="do_ekat"]').on('change', function() {
                $('#btntambah').attr("disabled", true);
                $("#no_do_ekat").val("");
                $("#tanggal_do_ekat").val("");
                if ($(this).val() == "yes") {
                    $("#do_detail_no_ekat").removeClass("hide");
                    $("#do_detail_tgl_ekat").removeClass("hide");
                } else if ($(this).val() == "no") {
                    $("#do_detail_no_ekat").addClass("hide");
                    $("#do_detail_tgl_ekat").addClass("hide");
                }
            });
            /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
            // autocomplete(document.getElementById("instansi"), instansi_array);

        });
    </script>
@stop
