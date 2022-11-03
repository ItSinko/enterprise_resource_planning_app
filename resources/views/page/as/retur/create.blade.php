@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Tambah Retur</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('as.retur.show') }}">Memo Retur</a></li>
                        <li class="breadcrumb-item active">Tambah Memo Retur</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        table>tbody>tr>td>.select2>.selection>.select2-selection--single {
            height: 100% !important;
        }

        table>tbody>tr>td>.select2>.selection>.select2-selection>.select2-selection__rendered {
            word-wrap: break-word !important;
            text-overflow: inherit !important;
            white-space: normal !important;
        }

        .hide {
            display: none !important;
        }

        .margin {
            margin: 5px;
        }

        .align-center {
            text-align: center;
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

            .overflowy {
                max-height: 330px;
                overflow-y: scroll;
                box-shadow: none;
            }

            .cust {
                max-width: 40%;
            }

        }

        @media screen and (max-width: 1219px) {
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: right;
            }

            .overflowy {
                max-height: 330px;
                overflow-y: scroll;
                box-shadow: none;
            }

            .cust {
                max-width: 40%;
            }
        }

        @media screen and (max-width: 991px) {
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: left;
            }

            .margin-md {
                margin-top: 10px;
            }

            .align-md {
                text-align: center;
            }

            .overflowy {
                max-height: 455px;
                overflow-y: scroll;
                box-shadow: none;
            }
        }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="box-shadow:none;">
                        {{-- <div class="card-header bg-info">
                        <h5 class="card-title"><i class="fas fa-plus"></i> Tambah Retur</h5>
                    </div> --}}
                        <form method="POST" action="{{ route('as.retur.store') }}" id="formtambahretur">
                            @csrf
                            <div class="card-body">
                                <div class="form-horizontal">
                                    <div id="informasi_pelanggan" class="card card-outline card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Informasi Transaksi</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row" id="no_retur_input">
                                                <label for="no_retur" class="col-lg-5 col-md-12 col-form-label labelket">No
                                                    Retur</label>
                                                <div class="col-lg-2 col-md-8">
                                                    <input name="no_retur" id="no_retur"
                                                        class="form-control col-form-label no_retur  @error('no_retur') is-invalid @enderror">
                                                    <div class="invalid-feedback" id="msgno_retur"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="tgl_retur_input">
                                                <label for="tgl_retur"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Tanggal Retur</label>
                                                <div class="col-lg-2 col-md-6">
                                                    <input type="date" name="tgl_retur" id="tgl_retur"
                                                        class="form-control col-form-label tgl_retur  @error('tgl_retur') is-invalid @enderror">
                                                    <div class="invalid-feedback" id="msgtgl_retur"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="pilih_jenis_retur_input">
                                                <label for="pilih_jenis_retur"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Jenis Retur</label>
                                                <div class="col-lg-3 col-md-8 d-flex justify-content-between">
                                                    <div class="form-check form-check-inline col-form-label">
                                                        <input class="form-check-input" type="radio"
                                                            name="pilih_jenis_retur" id="pilih_jenis_retur1"
                                                            value="peminjaman" />
                                                        <label class="form-check-label"
                                                            for="pilih_jenis_retur1">Peminjaman</label>
                                                    </div>
                                                    <div class="form-check form-check-inline col-form-label">
                                                        <input class="form-check-input" type="radio"
                                                            name="pilih_jenis_retur" id="pilih_jenis_retur2"
                                                            value="komplain" />
                                                        <label class="form-check-label"
                                                            for="pilih_jenis_retur2">Komplain</label>
                                                    </div>
                                                    <div class="form-check form-check-inline col-form-label">
                                                        <input class="form-check-input" type="radio"
                                                            name="pilih_jenis_retur" id="pilih_jenis_retur3"
                                                            value="service" />
                                                        <label class="form-check-label"
                                                            for="pilih_jenis_retur3">Service</label>
                                                    </div>
                                                    <div class="invalid-feedback" id="msgpilih_jenis_retur"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="keterangan_input">
                                                <label for="keterangan"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Keterangan
                                                    Retur</label>
                                                <div class="col-lg-4 col-md-12">
                                                    <textarea name="keterangan" id="keterangan"
                                                        class="form-control col-form-label keterangan  @error('keterangan') is-invalid @enderror"></textarea>
                                                    <div class="invalid-feedback" id="msgketerangan"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="informasi_pelanggan" class="card card-outline card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title" id="title_info_cust">Informasi Pelanggan</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            {{-- <hr class="my-4"/> --}}
                                            <div class="form-group row" id="no_transaksi_input">
                                                <label for="no_transaksi"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">No Transaksi
                                                    Penjualan</label>
                                                <div class="col-lg-3 col-md-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <select
                                                                class="form-control custom-select select2 no_transaksi_ref"
                                                                id="no_transaksi_ref" name="no_transaksi_ref">
                                                                <option value="po">No PO</option>
                                                                <option value="so">No SO</option>
                                                                <option value="no_akn">No AKN</option>
                                                                <option value="no_sj">No SJ</option>
                                                            </select>
                                                        </div>
                                                        <input type="text" name="no_transaksi" id="no_transaksi"
                                                            class="form-control col-form-label no_transaksi  @error('no_transaksi') is-invalid @enderror" />
                                                    </div>
                                                    <small class="text-success mt-1" id="infono_transaksi">* Pilih Nomor
                                                        Referensi Penjualan yang akan dipakai</small>
                                                    <div class="invalid-feedback mt-1" id="msgno_transaksi"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row hide" id="pic_peminjaman_input">
                                                <label for="pic_peminjaman"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Penanggung
                                                    Jawab</label>
                                                <div class="col-lg-4 col-md-8">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend" width="40%">
                                                            <select class="form-control custom-select select2 divisi_id"
                                                                id="divisi_id" name="divisi_id">

                                                            </select>
                                                        </div>
                                                        <input type="text" name="pic_peminjaman" id="pic_peminjaman"
                                                            class="form-control col-form-label pic_peminjaman  @error('pic_peminjaman') is-invalid @enderror"
                                                            width="60%" />
                                                    </div>
                                                    <small class="text-success mt-1" id="infono_transaksi">* Pilih Divisi
                                                        Penanggung Jawab</small>
                                                    <div class="invalid-feedback" id="msgpic_peminjaman"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="customer_id_input">
                                                <label for="customer_id"
                                                    class="col-lg-5 col-md-12 col-form-label labelket"
                                                    id="label_cust">Nama Customer</label>
                                                <div class="col-lg-4 col-md-8">
                                                    <input name="customer_id" id="customer_id"
                                                        class="form-control col-form-label customer_id  @error('customer_id') is-invalid @enderror" />
                                                    <div class="invalid-feedback" id="msgcustomer_id"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="alamat_input">
                                                <label for="alamat"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Alamat</label>
                                                <div class="col-lg-4 col-md-8">
                                                    <textarea name="alamat" id="alamat"
                                                        class="form-control col-form-label alamat  @error('alamat') is-invalid @enderror"></textarea>
                                                    <div class="invalid-feedback" id="msgalamat"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="tgl_retur_input">
                                                <label for="tgl_retur"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Telepon</label>
                                                <div class="col-lg-2 col-md-6">
                                                    <input name="telepon" id="telepon"
                                                        class="form-control col-form-label telepon  @error('telepon') is-invalid @enderror" />
                                                    <div class="invalid-feedback" id="msgtelepon"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div id="ref_trans" class="hide">
                                    <hr class="my-4"/>
                                    <h5>Referensi Transaksi</h5>
                                    <div class="form-group row">
                                        <label for="ref_transaksi" class="col-lg-5 col-md-12 col-form-label labelket">Transaksi</label>
                                        <div class="col-lg-2 col-md-6 d-flex justify-content-between">
                                            <div class="form-check form-check-inline col-form-label">
                                                <input class="form-check-input" type="radio" name="ref_transaksi" id="ref_transaksi1" value="tersedia" />
                                                <label class="form-check-label" for="ref_transaksi1">Tersedia</label>
                                            </div>
                                            <div class="form-check form-check-inline col-form-label">
                                                <input class="form-check-input" type="radio" name="ref_transaksi" id="ref_transaksi2" value="tidak_tersedia" />
                                                <label class="form-check-label" for="ref_transaksi2">Tidak Tersedia</label>
                                            </div>
                                            <div class="invalid-feedback" id="msgref_transaksi"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row hide" id="no_ref_tidak_tersedia_input">
                                        <label for="no_ref_tidak_tersedia" class="col-lg-5 col-md-12 col-form-label labelket">No Referensi</label>
                                        <div class="col-lg-3 col-md-12">
                                            <input type="text" class="form-control  col-form-label" id="no_ref_tidak_tersedia" name="no_ref_tidak_tersedia">
                                            <div class="invalid-feedback" id="msgno_ref_tidak_tersedia"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row hide" id="customer_tidak_tersedia_input">
                                        <label for="customer_tidak_tersedia" class="col-lg-5 col-md-12 col-form-label labelket">Nama Customer</label>
                                        <div class="col-lg-3 col-md-12">
                                            <input type="text" class="form-control  col-form-label" id="customer_tidak_tersedia" name="customer_tidak_tersedia">
                                            <div class="invalid-feedback" id="msgcustomer_tidak_tersedia"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row hide" id="alamat_tidak_tersedia_input">
                                        <label for="alamat_tidak_tersedia" class="col-lg-5 col-md-12 col-form-label labelket">Alamat Customer</label>
                                        <div class="col-lg-3 col-md-12">
                                            <textarea class="form-control col-form-label" id="alamat_tidak_tersedia" name="alamat_tidak_tersedia"></textarea>
                                            <div class="invalid-feedback" id="msgalamat_tidak_tersedia"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row hide" id="pilih_ref_penjualan_input">
                                        <label for="pilih_ref_penjualan" class="col-lg-5 col-md-12 col-form-label labelket">Cari Berdasarkan</label>
                                        <div class="col-lg-3 col-md-8 d-flex justify-content-between">
                                            <div class="form-check form-check-inline col-form-label">
                                                <input class="form-check-input" type="radio" name="pilih_ref_penjualan" id="pilih_ref_penjualan1" value="so" />
                                                <label class="form-check-label" for="pilih_ref_penjualan1">No SO</label>
                                            </div>
                                            <div class="form-check form-check-inline col-form-label">
                                                <input class="form-check-input" type="radio" name="pilih_ref_penjualan" id="pilih_ref_penjualan2" value="po" />
                                                <label class="form-check-label" for="pilih_ref_penjualan2">No PO</label>
                                            </div>
                                            <div class="form-check form-check-inline col-form-label">
                                                <input class="form-check-input" type="radio" name="pilih_ref_penjualan" id="pilih_ref_penjualan3" value="no_akn" />
                                                <label class="form-check-label" for="pilih_ref_penjualan3">No Paket (Ekatalog)</label>
                                            </div>
                                            <div class="invalid-feedback" id="msgpilih_ref_penjualan"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row hide" id="no_ref_penjualan_input">
                                        <label for="no_ref_penjualan" class="col-lg-5 col-md-12 col-form-label labelket">No Ref Penjualan</label>
                                        <div class="col-lg-3 col-md-12">
                                            <select name="no_ref_penjualan" id="no_ref_penjualan" class="form-control custom-select no_ref_penjualan  @error('no_ref_penjualan') is-invalid @enderror">
                                            </select>
                                            <div class="invalid-feedback" id="msgno_ref_penjualan"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="informasi_transaksi" class="hide">
                                    <hr class="my-4"/>
                                    <h5>Info Penjualan</h5>
                                    <div class="row row-cols-1 row-cols-md-2 g-4">
                                        <div class="col" id="info_customer">
                                            <div class="card removeshadow bg-light h-100">
                                                <div class="card-body">
                                                    <h6><b>Customer</b></h6>
                                                    <div class="row">
                                                        <div class="p-2" style="min-width:60%; max-width: 70%">
                                                            <div class="margin">
                                                                <div><small class="text-muted">Nama Customer</small></div>
                                                                <div><b id="nama_customer">-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Alamat</small></div>
                                                                <div><b id="alamat_customer">-</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No Telp</small></div>
                                                                <div><b id="telp_customer">-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Provinsi</small></div>
                                                                <div><b id="provinsi_customer">-</b></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col" id="info_penjualan">
                                            <div class="card removeshadow bg-light h-100">
                                                <div class="card-body">
                                                    <h6><b>Transaksi Penjualan</b></h6>
                                                    <div class="row d-flex justify-content-between">
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No SO</small></div>
                                                                <div><b id="no_so">-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">No AKN</small></div>
                                                                <div><b id="no_paket">-</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No PO</small></div>
                                                                <div><b id="no_po">-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Tanggal PO</small></div>
                                                                <div><b id="tgl_po">-</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No DO</small></div>
                                                                <div><b id="no_do">-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Tanggal DO</small></div>
                                                                <div><b id="tgl_do">-</b></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col hide" id="info_retur">
                                            <div class="card removeshadow bg-light h-100">
                                                <div class="card-body">
                                                    <h6><b>Transaksi Retur</b></h6>
                                                    <div class="row d-flex justify-content-between">
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No Retur</small></div>
                                                                <div><b>-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Tanggal Retur</small></div>
                                                                <div><b>-</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No SO</small></div>
                                                                <div><b>-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">No AKN</small></div>
                                                                <div><b>-</b></div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2">
                                                            <div class="margin">
                                                                <div><small class="text-muted">No PO</small></div>
                                                                <div><b>-</b></div>
                                                            </div>
                                                            <div class="margin">
                                                                <div><small class="text-muted">Tanggal PO</small></div>
                                                                <div><b>-</b></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                    <div id="barang_penjualan" class="card card-outline card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Permintaan Barang</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            {{-- <hr class="my-4">
                                        <h5></h5>
                                        <div class="form-group row" id="pilih_jenis_barang_input">
                                            <label for="pilih_jenis_barang" class="col-lg-5 col-md-12 col-form-label labelket">Jenis Barang</label>
                                            <div class="col-lg-2 col-md-8 d-flex justify-content-between">
                                                <div class="form-check form-check-inline col-form-label">
                                                    <input class="form-check-input" type="checkbox" name="pilih_jenis_barang[]" id="pilih_jenis_barang1" value="produk" />
                                                    <label class="form-check-label" for="pilih_jenis_barang1">Produk</label>
                                                </div>
                                                <div class="form-check form-check-inline col-form-label">
                                                    <input class="form-check-input" type="checkbox" name="pilih_jenis_barang[]" id="pilih_jenis_barang2" value="komplain" />
                                                    <label class="form-check-label" for="pilih_jenis_barang2">Part</label>
                                                </div>
                                                <div class="invalid-feedback" id="msgpilih_jenis_barang"></div>
                                            </div>
                                        </div> --}}
                                            <div class="form-group row align-items-start">
                                                <div id="card_produk"
                                                    class="card card-outline card-success col mx-2 collapsed-card">
                                                    <div class="card-header">
                                                        <div
                                                            class="form-check form-check-inline col-form-label card-title">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="pilih_jenis_barang[]" id="pilih_jenis_barang1"
                                                                value="produk" />
                                                            <h6 class="form-check-label" for="pilih_jenis_barang1">Produk
                                                            </h6>
                                                        </div>
                                                        <div class="card-tools col-form-label">
                                                            <button type="button" class="btn btn-tool" disabled="true"
                                                                data-card-widget="collapse" id="collapse-produk">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover table-bordered table-striped"
                                                                id="produktable" style="text-align:center;">
                                                                <thead>
                                                                    <tr>
                                                                        {{-- <th width="7%">No</th>
                                                                    <th width="30%">Nama Paket</th>
                                                                    <th width="25%">Nama Produk</th>
                                                                    <th width="30%">No Seri</th>
                                                                    <th width="8%">Aksi</th> --}}
                                                                        <th width="7%">No</th>
                                                                        <th width="62%">Nama Produk</th>
                                                                        <th width="24%">Jumlah</th>
                                                                        <th width="7%">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        {{-- <td><select name="paket_produk_id[0]" id="paket_produk_id0" class="form-control custom-select paket_produk_id  @error('paket_produk_id') is-invalid @enderror"></select></td> --}}
                                                                        <td><select name="produk_id[0]" id="produk_id0"
                                                                                class="form-control custom-select produk_id  @error('produk_id') is-invalid @enderror"></select>
                                                                        </td>
                                                                        <td><input type="number"
                                                                                class="form-control produk_jumlah"
                                                                                name="produk_jumlah[0]"
                                                                                id="produk_jumlah0" /></td>
                                                                        {{-- <td><select name="no_seri_select[0]" id="no_seri_select0" class="form-control custom-select no_seri @error('no_seri') is-invalid @enderror" multiple="true"></select>
                                                                        <input type="text" class="form-control no_seri_input hide" id="no_seri_input0" name="no_seri_input[0]"/>
                                                                    </td> --}}
                                                                        <td><a href="#" id="tambah_paket_produk"><i
                                                                                    class="fas fa-plus text-success"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="card_part"
                                                    class="card card-outline card-success col mx-2 collapsed-card">
                                                    <div class="card-header">
                                                        <div
                                                            class="form-check form-check-inline col-form-label card-title">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="pilih_jenis_barang[]" id="pilih_jenis_barang2"
                                                                value="part" />
                                                            <h6 class="form-check-label" for="pilih_jenis_barang2">Part
                                                            </h6>
                                                        </div>
                                                        <div class="card-tools col-form-label">
                                                            <button type="button" class="btn btn-tool" disabled="true"
                                                                data-card-widget="collapse" id="collapse-part">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive" id="part_table_input">
                                                            <table class="table table-hover table-bordered table-striped"
                                                                id="parttable" style="text-align:center;">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="7%">No</th>
                                                                        <th width="62%">Nama Part</th>
                                                                        <th width="24%">Jumlah</th>
                                                                        <th width="7%">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td><select name="part_id[0]" id="part_id0"
                                                                                class="form-control custom-select part_id  @error('part_id') is-invalid @enderror"></select>
                                                                        </td>
                                                                        <td><input type="number"
                                                                                class="form-control part_jumlah"
                                                                                name="part_jumlah[0]" id="part_jumlah0" />
                                                                        </td>
                                                                        <td><a href="#" id="tambah_part"><i
                                                                                    class="fas fa-plus text-success"></i></a>
                                                                        </td>
                                                                    </tr>
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
                            <div class="card-footer">
                                <a href="{{ route('as.retur.show') }}" type="button" class="btn btn-danger">Batal</a>
                                <button type="submit" class="btn btn-info float-right" id="btnsubmit"
                                    disabled="true">Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('adminlte_js')
    <script>
        $(function() {
            var inputjumpart = false;
            var inputjumproduk = false;

            var inputpart = false;
            var inputproduk = false;

            $('#no_transaksi_ref').select2();

            function validasi() {
                var jenis_array = [];
                $('input[name="pilih_jenis_barang[]"]:checked').each(function() {
                    jenis_array.push($(this).val());
                });
                if ($.inArray("produk", jenis_array) !== -1) {
                    $('#produktable').find('.produk_id').each(function() {
                        if ($(this).val() != null) {
                            inputproduk = true;
                        } else {
                            inputproduk = false;
                            return false;
                        }
                    });

                    $('#produktable').find('.produk_jumlah').each(function() {
                        if ($(this).val() != "") {
                            inputjumproduk = true;
                        } else {
                            inputjumproduk = false;
                            return false;
                        }
                    });
                } else if ($.inArray("produk", jenis_array) === -1) {
                    inputproduk = true;
                    inputjumproduk = true;
                }

                if ($.inArray("part", jenis_array) !== -1) {

                    $('#parttable').find('.part_id').each(function() {
                        if ($(this).val() != null) {
                            inputpart = true;
                        } else {
                            inputpart = false;
                            return false;
                        }
                    });

                    $('#parttable').find('.part_jumlah').each(function() {
                        if ($(this).val() != "") {
                            inputjumpart = true;
                        } else {
                            inputjumpart = false;
                            return false;
                        }
                    });

                } else if ($.inArray("part", jenis_array) === -1) {
                    inputpart = true;
                    inputjumpart = true;
                }

                console.log(inputjumproduk + ", " + inputproduk + ", " + inputjumpart + ", " + inputpart);

                if ($('#no_retur').val() != "" && $('#tgl_retur').val() != "" && $(
                        'input[name="pilih_jenis_retur"]:checked').length > 0 && $('#customer_id').val() != "" && $(
                        '#alamat').val() && $('input[name="pilih_jenis_barang[]"]:checked').length > 0 &&
                    inputproduk == true && inputjumproduk == true && inputpart == true && inputjumpart == true) {
                    if ($('input[name="pilih_jenis_retur"]:checked').val() != "peminjaman" && $(
                            'input[name="no_transaksi"]').val() != "") {
                        $('#btnsubmit').attr('disabled', false);
                    } else if ($('input[name="pilih_jenis_retur"]:checked').val() == "peminjaman" && $(
                            'input[name="pic_peminjaman"]').val() != "") {
                        $('#btnsubmit').attr('disabled', false);
                    } else {
                        $('#btnsubmit').attr('disabled', true);
                    }
                } else {
                    $('#btnsubmit').attr('disabled', true);
                }
            }

            $(document).on('change keyup', '#no_retur', function() {
                validasi();
            })

            $(document).on('change keyup', '#tgl_retur', function() {
                validasi();
            })

            $(document).on('change', 'input[name="pilih_jenis_retur"]', function() {
                $('#no_transaksi').val("");
                $('#customer_id').val("");
                $('#alamat').val("");
                $('#telepon').val("");
                var value = $('input[name="pilih_jenis_retur"]:checked').val();
                if (value == "peminjaman") {
                    $('#pic_peminjaman_input').removeClass('hide');
                    $('#label_cust').text('Nama Peminjam');
                    $('#title_info_cust').text('Info Peminjaman');
                    $('#no_transaksi_input').addClass('hide');
                } else {
                    $('#pic_peminjaman_input').addClass('hide');
                    $('#label_cust').text('Nama Customer');
                    $('#title_info_cust').text('Info Penjualan');
                    $('#no_transaksi_input').removeClass('hide');
                }
                validasi();
            })

            $(document).on('change keyup', '#customer_id', function() {
                validasi();
            })

            $(document).on('change keyup', '#alamat', function() {
                validasi();
            })

            // $(document).on('change keyup', 'input[name="pilih_jenis_barang[]"]', function(){
            //     validasi();
            // })

            $(document).on('change', '#no_transaksi_ref', function() {
                $('#no_transaksi').val("");
                $('#customer_id').val("");
                $('#alamat').val("");
                $('#telepon').val("");
            })

            function trproduktable() {
                var produktr = $('#produktable > tbody > tr').length;
                var data = `<tr>
                    <td>1</td>
                    {{-- <td><select name="paket_produk_id[0]" id="paket_produk_id0" class="form-control custom-select paket_produk_id  @error('paket_produk_id') is-invalid @enderror"></select></td> --}}
                    <td><select name="produk_id[0]" id="produk_id0" class="form-control custom-select produk_id  @error('produk_id') is-invalid @enderror"></select></td>
                    <td><input type="number" class="form-control produk_jumlah" name="produk_jumlah[0]" id="produk_jumlah0"/></td>
                    {{-- <td><select name="no_seri_select[0]" id="no_seri_select0" class="form-control custom-select no_seri @error('no_seri') is-invalid @enderror" multiple="true"></select>
                        <input type="text" class="form-control no_seri_input hide" id="no_seri_input0" name="no_seri_input[0]"/></td> --}}
                        <td>`;
                if (produktr > 0) {
                    data += `<a id="remove_paket_produk"><i class="fas fa-minus" style="color: red"></i></a>`;
                } else {
                    data += `<a href="#" id="tambah_paket_produk"><i class="fas fa-plus text-success"></i></a>`;
                }
                data += `</td>
                </tr>`;

                return data;
            }

            function trparttable() {
                var parttr = $('#parttable > tbody > tr').length;
                var data = `<tr>
                    <td>1</td>
                    <td><select name="part_id[0]" id="part_id0" class="form-control custom-select part_id  @error('part_id') is-invalid @enderror"></select></td>
                    <td><input type="number" class="form-control part_jumlah" name="part_jumlah[0]" id="part_jumlah0"/></td>
                    <td>`;
                if (parttr > 0) {
                    data += `<a id="remove_part"><i class="fas fa-minus" style="color: red"></i></a>`;
                } else {
                    data += `<a href="#" id="tambah_part"><i class="fas fa-plus text-success"></i></a>`;
                }
                data += `</td>
                </tr>`;

                return data;
            }

            $('.no_ref_penjualan').select2();
            $('.paket_produk_id').select2();

            function produk() {
                $('.produk_id').select2({
                    placeholder: "Pilih Produk",
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/produk/select/',
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
                })
            }

            $('.divisi_id').select2({
                placeholder: "Pilih Divisi",
                ajax: {
                    minimumResultsForSearch: 20,

                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/gbj/sel-divisi',
                    // data: function(params) {
                    //     return {
                    //         term: params.term
                    //     }
                    // },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.nama,
                                };
                            })
                        };
                    },
                }
            }).change(function(e) {
                e.preventDefault();

            })

            function part() {
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
                })
            }

            $(document).on('change', '#produktable .produk_id', function() {
                validasi();
            });

            $(document).on('change keyup', '#produktable .produk_jumlah', function() {

                validasi();
            });

            $(document).on('change', '#parttable .part_id', function() {
                validasi();
            });

            $(document).on('change keyup', '#parttable .part_jumlah', function() {
                validasi();
            });

            $('.no_seri').select2();
            produk();
            part();
            $('input[type="radio"][name="ref_transaksi"]').on('change', function() {
                format_informasi_ref_penjualan();
                var value = $(this).val();
                $('input[name="pilih_ref_penjualan"]').prop('checked', false);
                $('.no_ref_penjualan').empty();

                $('.paket_produk_id').empty();
                $('.produk_id').empty();
                $('.no_seri').empty();

                if (value == "tidak_tersedia") {
                    $('#informasi_transaksi').addClass('hide');

                    $('#no_ref_tidak_tersedia_input').removeClass('hide');
                    $('#customer_tidak_tersedia_input').removeClass('hide');
                    $('#alamat_tidak_tersedia_input').removeClass('hide');

                    $('#pilih_ref_penjualan_input').addClass('hide');
                    $('#no_ref_penjualan_input').addClass('hide');

                    $('#produktable tr').find('.no_seri_input').removeClass('hide');
                    $('#produktable tr').find('.no_seri').next(".select2-container").hide();
                    produk_penjualan_tidak_tersedia();
                } else if (value == "tersedia") {
                    $('#informasi_transaksi').removeClass('hide');

                    $('#no_ref_tidak_tersedia_input').addClass('hide');
                    $('#customer_tidak_tersedia_input').addClass('hide');
                    $('#alamat_tidak_tersedia_input').addClass('hide');

                    $('#pilih_ref_penjualan_input').removeClass('hide');
                    $('#no_ref_penjualan_input').removeClass('hide');

                    $('#produktable tr').find('.no_seri_input').addClass('hide');
                    $('#produktable tr').find('.no_seri').next(".select2-container").show();
                }
            });

            $('input[name="pilih_ref_penjualan"]').on('change', function() {
                $('.no_ref_penjualan').empty();
                format_informasi_ref_penjualan();
                no_ref_penjualan($(this).val());
            });

            $(document).on('change', 'input[name="pilih_jenis_barang[]"]', function() {
                var jenis_arry = [];
                var x = $(this).val();

                $('input[name="pilih_jenis_barang[]"]:checked').each(function() {
                    jenis_arry.push($(this).val());
                });

                console.log(x);

                if ($('input[name="pilih_jenis_barang[]"]:checkbox:checked').length == 0) {
                    jenis_arry.push(x);
                    $('input[name="pilih_jenis_barang[]"][value="' + x + '"]').prop("checked", true);
                }
                filter_jenis(jenis_arry);
                validasi();
            });

            function filter_jenis(x) {
                if ($.inArray("produk", x) !== -1) {
                    $("#card_produk").removeClass("collapsed-card");
                    $("#collapse-produk").attr('disabled', false);
                } else {
                    $('#produktable tbody').empty();
                    $('#produktable tbody').append(trproduktable());
                    numberRowsProduk($("#produktable"));
                    $("#card_produk").addClass("collapsed-card");
                    $("#collapse-produk").attr('disabled', true);
                }

                if ($.inArray("part", x) !== -1) {
                    $("#card_part").removeClass("collapsed-card");
                    $("#collapse-part").attr('disabled', false);
                } else {
                    $('#parttable tbody').empty();
                    $('#parttable tbody').append(trparttable());
                    numberRowsPart($('#parttable'));
                    $("#card_part").addClass("collapsed-card");
                    $("#collapse-part").attr('disabled', true);
                }
            }

            function numberRowsProduk($t) {
                var c = 0 - 1;
                var referensi = $('input[name="ref_transaksi"]:checked').val();
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;
                    // $(el).find('.paket_produk_id').attr('name', 'paket_produk_id[' + j + ']');
                    // $(el).find('.paket_produk_id').attr('id', 'paket_produk_id' + j);

                    $(el).find('.produk_id').attr('name', 'produk_id[' + j + ']');
                    $(el).find('.produk_id').attr('id', 'produk_id' + j);

                    $(el).find('.produk_jumlah').attr('name', 'produk_jumlah[' + j + ']');
                    $(el).find('.produk_jumlah').attr('id', 'produk_jumlah' + j);

                    // $(el).find('.no_seri').attr('name', 'no_seri_select[' + j + ']');
                    // $(el).find('.no_seri').attr('id', 'no_seri_select' + j);

                    // $(el).find('.no_seri_input').attr('name', 'no_seri_input[' + j + ']');
                    // $(el).find('.no_seri_input').attr('id', 'no_seri_input' + j);

                    // $('.paket_produk_id').select2();
                    produk();
                    // $('.no_seri').select2();

                    // if(referensi == "tersedia"){
                    //     produk_penjualan_tersedia($(".no_ref_penjualan").val());
                    //     $('.no_seri_input').addClass('hide');
                    //     $('.no_seri').next(".select2-container").show();

                    // } else if(referensi == "tidak_tersedia"){
                    //     produk_penjualan_tidak_tersedia();
                    //     $('.no_seri_input').removeClass('hide');
                    //     $('.no_seri').next(".select2-container").hide();
                    // }
                });
            }

            $(document).on('click', '#produktable #tambah_paket_produk', function(e) {
                e.preventDefault();
                $('#produktable tr:last').after(trproduktable());
                numberRowsProduk($("#produktable"));
                validasi();
            });

            $('#produktable').on('click', '#remove_paket_produk', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
                numberRowsProduk($("#produktable"));
                validasi();
            });

            function numberRowsPart($t) {
                var c = 0 - 1;
                var referensi = $('input[name="ref_transaksi"]:checked').val();
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;
                    $(el).find('.part_id').attr('name', 'part_id[' + j + ']');
                    $(el).find('.part_id').attr('id', 'part_id' + j);

                    $(el).find('.part_jumlah').attr('name', 'part_jumlah[' + j + ']');
                    $(el).find('.part_jumlah').attr('id', 'part_jumlah' + j);
                    part();
                });
            }

            $(document).on('click', '#parttable #tambah_part', function(e) {
                e.preventDefault();
                $('#parttable tr:last').after(trparttable());
                numberRowsPart($("#parttable"));
                validasi();
            });

            $('#parttable').on('click', '#remove_part', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
                numberRowsPart($("#parttable"));
                validasi();
            });

            function no_ref_penjualan(jenis) {
                $('.no_ref_penjualan').select2({
                    ajax: {
                        minimumResultsForSearch: 20,
                        placeholder: "Pilih Produk",
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/as/list/so_selesai/' + jenis,
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
            }

            function produk_penjualan(id) {
                $('.paket_produk_id').select2({
                    ajax: {
                        minimumResultsForSearch: 20,
                        placeholder: "Pilih Paket Produk",
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/as/list/so_selesai_paket/' + id,
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
                                        text: obj.penjualan_produk.nama
                                    };
                                })
                            };
                        },
                    }
                })
            }

            function format_informasi_ref_penjualan() {
                $('#nama_customer').text("-");
                $('#alamat_customer').text("-");
                $('#telp_customer').text("-");
                $('#provinsi_customer').text("-");

                $('#no_so').text("-");
                $('#no_po').text("-");
                $('#tgl_po').text("-");
                $('#no_paket').text("-");
                $('#no_do').text("-");
                $('#tgl_do').text("-");
            }

            function informasi_ref_penjualan(id) {
                $.ajax({
                    type: "GET",
                    url: '/api/as/detail/so_retur/' + id,
                    dataType: 'json',
                    success: function(data) {
                        $('#nama_customer').text(data.customer.nama);
                        $('#alamat_customer').text(data.customer.alamat);
                        $('#provinsi_customer').text(data.customer.provinsi.nama);

                        $('#no_so').text(data.pesanan.so);

                        $('#no_po').text(data.pesanan.no_po);
                        $('#tgl_po').text(data.pesanan.tgl_po);
                        if (data.no_paket != undefined) {
                            $('#no_paket').text(data.no_paket);
                        } else {
                            $('#no_paket').text("-");
                        }

                        if (data.customer.telp != null) {
                            $('#telp_customer').text(data.customer.telp);
                        } else {
                            $('#telp_customer').text("-");
                        }

                        if (data.no_do != null) {
                            $('#no_do').text(data.no_do);
                        } else {
                            $('#no_do').text("-");
                        }

                        if (data.tgl_do != null) {
                            $('#tgl_do').text(data.tgl_do);
                        } else {
                            $('#tgl_do').text("-");
                        }
                    },
                    error: function(data) {
                        alert('Error occured');
                    }
                });
            }

            function produk_penjualan_tersedia(id) {
                $('.paket_produk_id').empty();
                $('.paket_produk_id').select2({
                    ajax: {
                        minimumResultsForSearch: 20,
                        placeholder: "Pilih Paket Produk",
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/as/list/so_selesai_paket/' + id,
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
                                        text: obj.penjualan_produk.nama
                                    };
                                })
                            };
                        },
                    }
                })
            }

            function produk_penjualan_tidak_tersedia() {
                var prm;
                // $('.paket_produk_id').empty();
                $('.paket_produk_id').select2({
                    ajax: {
                        minimumResultsForSearch: 20,
                        placeholder: "Pilih Paket Produk",
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
                })
            }

            function produk_gudang_tersedia(column, id) {
                $('#' + column).empty();
                $('#' + column).select2({
                    ajax: {
                        minimumResultsForSearch: 20,
                        placeholder: "Pilih Produk",
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/as/list/so_selesai_paket_produk/' + id,
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
                                        text: obj.gudang_barang_jadi.produk.nama + " " + obj
                                            .gudang_barang_jadi.nama
                                    };
                                })
                            };
                        },
                    }
                })
            }

            function produk_gudang_tidak_tersedia(column, id) {
                $('#' + column).select2({
                    ajax: {
                        minimumResultsForSearch: 20,
                        placeholder: "Pilih Produk",
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/penjualan_produk/select/' + id,
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data[0].produk, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nama
                                    };
                                })
                            };
                        },
                    }
                })
            }

            $(document).on('keyup change', '.no_ref_penjualan', function() {
                var val = $(this).val();
                if (val != "") {
                    informasi_ref_penjualan(val);
                    produk_penjualan_tersedia(val);
                }
            });

            $(document).on('keyup change', '#produktable  .paket_produk_id', function() {
                var val = $(this).val();
                var column = $(this).closest('tr').find('.produk_id').attr('id');
                if (val != "") {
                    $(column).empty();
                    if ($('input[name="ref_transaksi"]:checked').val() == "tidak_tersedia") {
                        produk_gudang_tidak_tersedia(column, val);
                    } else if ($('input[name="ref_transaksi"]:checked').val() == "tersedia") {
                        produk_gudang_tersedia(column, val);
                    }
                }
            });

            $("#customer_id").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        dataType: 'json',
                        url: '/api/customer/select',
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.nama,
                                    value: el.id
                                };
                            });
                            response(transformed.slice(0, 10));
                        },
                        error: function() {
                            response([]);
                        }
                    });
                },
                focus: function(event, ui) {
                    $(this).val(ui.item.label);
                    return false;
                },
                select: function(event, ui) {
                    var id = ui.item.value;
                    $(this).val(ui.item.label);

                    if (id != "") {
                        $.ajax({
                            url: '/api/customer/select/' + id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('#alamat').val(data[0].alamat);
                                $('#telepon').val(data[0].telepon);
                            }
                        });
                    } else {
                        $('#alamat').val("");
                        $('#telepon').val("");
                    }
                    validasi();
                    return false;
                }
            });

            $("#customer_id").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        dataType: 'json',
                        url: '/api/customer/select',
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.nama,
                                    value: el.id
                                };
                            });
                            response(transformed.slice(0, 10));
                        },
                        error: function() {
                            response([]);
                        }
                    });
                },
                focus: function(event, ui) {
                    $(this).val(ui.item.label);
                    return false;
                },
                select: function(event, ui) {
                    var id = ui.item.value;
                    $(this).val(ui.item.label);

                    if (id != "") {
                        $.ajax({
                            url: '/api/customer/select/' + id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('#alamat').val(data[0].alamat);
                                $('#telepon').val(data[0].telepon);
                            }
                        });
                    } else {
                        $('#alamat').val("");
                        $('#telepon').val("");
                    }
                    validasi();
                    return false;
                }
            });

            $("#no_transaksi").autocomplete({
                source: function(request, response) {
                    var jenis = $('#no_transaksi_ref').val();
                    $.ajax({
                        dataType: 'json',
                        type: 'GET',
                        url: '/api/as/list/so_selesai/' + jenis,
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.nama,
                                    value: el.id
                                };
                            });
                            response(transformed.slice(0, 10));
                        },
                        error: function() {
                            response([]);
                        }
                    });
                },
                focus: function(event, ui) {
                    $(this).val(ui.item.label);
                    return false;
                },
                select: function(event, ui) {
                    var id = ui.item.value;

                    $(this).val(ui.item.label);
                    if (id != "") {
                        $.ajax({
                            url: '/api/as/detail/so_retur/' + id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('#customer_id').val(data.customer.nama);
                                $('#alamat').val(data.customer.alamat);
                                $('#telepon').val(data.customer.telepon);
                            }
                        });
                    } else {
                        $('#customer_id').val("");
                        $('#alamat').val("");
                        $('#telepon').val("");
                    }
                    validasi();
                    return false;
                },
                keyup: function(event, ui) {
                    $('#customer_id').val("");
                    $('#alamat').val("");
                    $('#telepon').val("");
                }
            });
        })
    </script>
@stop
