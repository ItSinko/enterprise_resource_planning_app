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
                <li class="breadcrumb-item"><a href="{{route('penjualan.penjualan.show')}}">Penjualan</a></li>
                <li class="breadcrumb-item active">Edit Ekatalog</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->

@stop

@section('adminlte_css')
<style>
    .hide {
        display: none !important;
    }

    .margin {
        margin: 5px;
    }

    .margin-top {
        margin-top: 5px;
    }

    .align-center {
        text-align: center;
    }

    legend {
        font-size: 14px;
    }

    filter {
        margin: 5px;
    }

    .blue-bg {
        background-color: #c8daea;
    }

    #produktable{
        width: 1371px !important;
    }

    .removeshadow{
        box-shadow: none;
    }

    @media screen and (min-width: 1220px) {

        body {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }

        .labelket{
            text-align: right;
        }

        .cust{
            max-width:40%;
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

        .labelket{
            text-align: right;
        }

        .cust{
            max-width:40%;
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
        body {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }

        .labelket{
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
        padding: 5px;
        cursor: pointer;
        background-color: #fff;
        border-bottom: 1px solid #d4d4d4;
    }

        /*when hovering an item:*/
    .autocomplete-items div:hover {
        background-color: #ffdb4d;
    }

        /*when navigating through the items using the arrow keys:*/
    .autocomplete-active {
        background-color: #e6c300 !important;
    }

</style>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card" id="ekatalog">
                    <div class="card-body">
                        <h4 class="margin">Data Penjualan </h4>
                        <div class="row">
                            <div class="col-lg-11 col-md-12">
                                <div class="row d-flex justify-content-between">
                                    <div class="p-2 cust">
                                        <div>
                                            <div class="margin">
                                                <small class="text-muted">Info Instansi</small>
                                            </div>
                                            <div class="margin"><b>{{$e->instansi}}</b></div>
                                            <div class="margin"><b>{{$e->satuan}}</b></div>
                                            <div class="margin"><b>
                                                    @if(!empty($e->Provinsi))
                                                    {{$e->Provinsi->nama}}
                                                    @endif
                                                </b></div>
                                        </div>
                                        <div class="margin-top">
                                            <div class="margin">
                                                <small class="text-muted">Info Customer</small>
                                            </div>
                                            <div class="margin"><b>{{$e->customer->nama}}</b></div>
                                            <div class="margin"><b>{{$e->customer->alamat}}</b></div>
                                            <div class="margin"><b>
                                                    @if(!empty($e->Customer->Provinsi))
                                                    @if($e->Customer->nama != 'Belum diketahui')
                                                    {{$e->Customer->Provinsi->nama}}
                                                    @endif
                                                    @endif

                                                </b></div>
                                            <div class="margin"><b>{{$e->Customer->telp}}</b></div>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <div class="margin">
                                            <div><small class="text-muted">No SO</small></div>
                                            <div>@if($e->Pesanan)
                                                @if(!empty($e->Pesanan->so))
                                                <b>{{$e->Pesanan->so}}</b>
                                                @else
                                                <b>-</b>
                                                @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">No AKN</small></div>
                                            <div><b>{{$e->no_paket}}</b></div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal Order</small>
                                            </div>
                                            <div><b>{{date('d-m-Y', strtotime($e->tgl_buat))}}</b></div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal Batas Kontrak</small>
                                            </div>
                                            <div><b>
                                                    @if(!empty($e->tgl_kontrak))
                                                    {{date('d-m-Y', strtotime($e->tgl_kontrak))}}
                                                    @else
                                                    -
                                                    @endif
                                                </b></div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Status</small></div>
                                            @if($e->status == 'batal')
                                            <div class="badge red-text">Batal</div>
                                            @elseif($e->status == 'negosiasi')
                                            <div class="badge yellow-text">Negosiasi</div>
                                            @elseif($e->status == 'sepakat')
                                            <div class="margin-top"><b><span class="green-text">Sepakat</span></b></div>
                                            @elseif($e->status == 'draft')
                                            <div class="badge blue-text">Draft</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <div class="margin">
                                            <div><small class="text-muted">No PO</small></div>
                                            <div>@if($e->Pesanan)
                                                @if(!empty($e->Pesanan->no_po))
                                                <b>{{$e->Pesanan->no_po}}</b>
                                                @else
                                                <b>-</b>
                                                @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal PO</small>
                                            </div>
                                            <div><b>
                                                    @if($e->Pesanan)
                                                    @if(!empty($e->Pesanan->tgl_po))
                                                    {{date('d-m-Y', strtotime($e->Pesanan->tgl_po))}}
                                                    @else
                                                    -
                                                    @endif
                                                    @endif</b></div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">No DO</small></div>
                                            <div><b>@if($e->Pesanan)
                                                    @if(!empty($e->Pesanan->no_do))
                                                    {{$e->Pesanan->no_do}}
                                                    @else
                                                    -
                                                    @endif
                                                    @endif</b></div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal DO</small>
                                            </div>
                                            <div><b>@if($e->Pesanan)
                                                    @if(!empty($e->Pesanan->tgl_do))
                                                    {{date('d-m-Y', strtotime($e->Pesanan->tgl_do))}}
                                                    @else
                                                    -
                                                    @endif
                                                    @endif</b></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-warning">
                        <div class="card-title">Form Ubah Data</div>
                    </div>
                    <div class="card-body">
                        @if(session()->has('error') || count($errors) > 0 )
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Gagal mengubah data!</strong> Periksa
                            kembali data yang diinput
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @elseif(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Berhasil mengubah data</strong>,
                            Terima kasih
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        <div class="content">
                            <form method="post" autocomplete="off" action="{{route('penjualan.penjualan.update_ekatalog', ['id' => $e->id])}}">
                                {{csrf_field()}}
                                {{method_field('PUT')}}
                                <div class="row d-flex justify-content-center">
                                    <div class="col-lg-11 col-md-12">
                                        <h4>Info Customer</h4>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-horizontal">
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Nama Customer / Distributor</label>
                                                        <div class="col-lg-5 col-md-12 col-form-label">
                                                            <div class="form-check form-check-inline " id="sudah_dsb">
                                                                <input class="form-check-input" type="radio" name="namadistributor" id="namadistributor1" value="sudah" />
                                                                <label class="form-check-label" for="namadistributor1">Sudah Diketahui</label>
                                                            </div>
                                                            <div class="form-check form-check-inline " id="belum_dsb">
                                                                <input class="form-check-input" type="radio" name="namadistributor" id="namadistributor2" value="belum" />
                                                                <label class="form-check-label" for="namadistributor2">Belum Diketahui</label>
                                                            </div>
                                                            <div class="invalid-feedback" id="msgnamadistributor">
                                                                @if($errors->has('namadistributor'))
                                                                {{ $errors->first('namadistributor')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket"></label>
                                                        <div class="col-lg-5 col-md-12">
                                                            <select name="customer_id" id="customer_id" class="form-control customer_id custom-select @error('customer_id') is-invalid @enderror">
                                                                <option value="{{$e->customer_id}}" selected>{{$e->customer->nama}}</option>
                                                            </select>
                                                            <div class="invalid-feedback" id="msgcustomer_id">
                                                                @if($errors->has('customer_id'))
                                                                {{ $errors->first('customer_id')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Alamat</label>
                                                        <div class="col-lg-7 col-md-12">
                                                            <textarea class="form-control col-form-label @error('alamat') is-invalid @enderror" name="alamat" id="alamat_customer" readonly>{{$e->customer->alamat}}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Telepon</label>
                                                        <div class="col-lg-5 col-md-12">
                                                            <input type="text" class="form-control col-form-label" name="telepon" id="telepon_customer" readonly value="{{$e->customer->telp}}" />
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center" id="akn">
                                    <div class="col-lg-11 col-md-12">
                                        <h4>Info AKN</h4>
                                        <div class="card">
                                            <div class="card-body">
                                                <ul class="nav nav-pills mb-3 nav-justified" id="pills-tab" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="pills-penjualan-tab" data-toggle="pill" href="#pills-penjualan" role="tab" aria-controls="pills-penjualan" aria-selected="true">Deskripsi Ekatalog</a>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="pills-instansi-tab" data-toggle="pill" href="#pills-instansi" role="tab" aria-controls="pills-instansi" aria-selected="false">Instansi</a>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <a class="nav-link" id="pills-produk-tab" data-toggle="pill" href="#pills-produk" role="tab" aria-controls="pills-produk" aria-selected="false">Rencana Penjualan</a>
                                                    </li>
                                                </ul>

                                                <div class="form-horizontal">
                                                    <div class="tab-content" id="pills-tabContent">
                                                        <div class="tab-pane fade show active" id="pills-penjualan" role="tabpanel" aria-labelledby="pills-penjualan-tab">
                                                            <div class="card removeshadow">
                                                                <div class="card-body">
                                                                    <div class="form-group row">
                                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">No Paket</label>
                                                                        <div class="col-lg-5 col-md-12 input-group">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text" id="ket_no_paket">AK1-</span>
                                                                            </div>
                                                                            <input type="text" class="form-control col-form-label @error('no_paket') is-invalid @enderror" name="no_paket" id="no_paket" aria-label="ket_no_paket" @if($e->status == "draft") readonly="true" @endif/>
                                                                            <div class="input-group-append  @if($e->status != "draft") hide @endif" id="checkbox_nopaket">
                                                                                <span class="input-group-text" >
                                                                                    <div class="form-check form-check-inline">
                                                                                        <input class="form-check-input" type="checkbox" name="isi_nopaket" id="isi_nopaket" value="true" />
                                                                                        <label class="form-check-label" for="isi_nopaket">Isi</label>
                                                                                    </div>
                                                                                </span>
                                                                            </div>
                                                                            <div class="invalid-feedback" id="msgno_paket">
                                                                                @if($errors->has('no_paket'))
                                                                                {{ $errors->first('no_paket')}}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">No Urut</label>
                                                                        <div class="col-lg-2 col-md-4">
                                                                            <input type="number" class="form-control col-form-label @error('no_urut') is-invalid @enderror" name="no_urut" id="no_urut" value="{{$e->no_urut}}" />
                                                                            <div class="invalid-feedback" id="msgno_urut">
                                                                                @if($errors->has('no_urut'))
                                                                                {{ $errors->first('no_urut')}}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Status</label>
                                                                        <div class="col-lg-5 col-md-12 col-form-label">
                                                                            <!-- <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio" name="status_akn" id="status_akn4" value="draft" />
                                                                                <label class="form-check-label" for="status_akn4">Draft</label>
                                                                            </div> -->
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio" name="status_akn" id="status_akn1" value="sepakat" />
                                                                                <label class="form-check-label" for="status_akn1">Sepakat</label>
                                                                            </div>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio" name="status_akn" id="status_akn2" value="negosiasi" />
                                                                                <label class="form-check-label" for="status_akn2">Negosiasi</label>
                                                                            </div>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio" name="status_akn" id="status_akn3" value="batal" />
                                                                                <label class="form-check-label" for="status_akn3">Batal</label>
                                                                            </div>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input" type="radio" name="status_akn" id="status_akn3" value="draft" />
                                                                                <label class="form-check-label" for="status_akn3">Draft</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Tanggal Buat</label>
                                                                        <div class="col-lg-4 col-md-4">
                                                                            <input type="date" class="form-control col-form-label @error('tgl_buat') is-invalid @enderror" name="tgl_buat" id="tgl_buat" value="{{$e->tgl_buat}}" />
                                                                            <div class="invalid-feedback" id="msgtgl_buat">
                                                                                @if($errors->has('tgl_buat'))
                                                                                {{ $errors->first('tgl_buat')}}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Tanggal Edit</label>
                                                                        <div class="col-lg-4 col-md-4">
                                                                            <input type="date" class="form-control col-form-label @error('tgl_edit') is-invalid @enderror" name="tgl_edit" id="tgl_edit" value="{{$e->tgl_edit}}" />
                                                                            <div class="invalid-feedback" id="msgtgl_edit">
                                                                                @if($errors->has('tgl_edit'))
                                                                                {{ $errors->first('tgl_edit')}}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Tanggal Delivery</label>
                                                                        <div class="col-lg-4 col-md-4">
                                                                            <input type="date" class="form-control col-form-label @error('batas_kontrak') is-invalid @enderror" name="batas_kontrak" id="batas_kontrak" value="{{$e->tgl_kontrak}}" />
                                                                            <div class="invalid-feedback" id="msgbatas_kontrak">
                                                                                @if($errors->has('batas_kontrak'))
                                                                                {{ $errors->first('batas_kontrak')}}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade show" id="pills-instansi" role="tabpanel" aria-labelledby="pills-instansi-tab">
                                                            <div class="card removeshadow">
                                                                <div class="card-header"><h6>Rencana Penjualan</h6></div>
                                                                <div class="card-body">
                                                                    <div class="form-group row">
                                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Instansi</label>
                                                                        <div class="col-lg-7 col-md-12 autocomplete">
                                                                            <input type="text" name="instansi" id="instansi" class="form-control col-form-label @error('instansi') is-invalid @enderror" value="{{$e->instansi}}"/>
                                                                            <div class="invalid-feedback" id="msginstansi">
                                                                                @if($errors->has('instansi'))
                                                                                {{ $errors->first('instansi')}}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Satuan Kerja</label>
                                                                        <div class="col-lg-7 col-md-12">
                                                                            <input type="text" class="form-control col-form-label @error('satuan_kerja') is-invalid @enderror" name="satuan_kerja" id="satuan_kerja" value="{{$e->satuan}}" />
                                                                            <div class="invalid-feedback" id="msgsatuan_kerja">
                                                                                @if($errors->has('satuan_kerja'))
                                                                                {{ $errors->first('satuan_kerja')}}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Alamat Instansi</label>
                                                                        <div class="col-lg-7 col-md-12">
                                                                            <textarea class="form-control col-form-label @error('alamatinstansi') is-invalid @enderror" name="alamatinstansi" id="alamatinstansi">{{$e->alamat}}</textarea>
                                                                            <div class="invalid-feedback" id="msgalamatinstansi">
                                                                                @if($errors->has('alamatinstansi'))
                                                                                {{ $errors->first('alamatinstansi')}}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Provinsi</label>
                                                                        <div class="col-lg-7 col-md-12">
                                                                            <select name="provinsi" id="provinsi" class="form-control custom-select provinsi @error('provinsi') is-invalid @enderror" style="width: 100%;">
                                                                                @if(!empty($e->provinsi_id))
                                                                                <option value="{{$e->provinsi_id}}" selected>{{$e->provinsi->nama}}</option>
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Deskripsi</label>
                                                                        <div class="col-lg-5 col-md-12">
                                                                            <textarea class="form-control col-form-label @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi">{{$e->deskripsi}}</textarea>
                                                                            <div class="invalid-feedback" id="msgdeskripsi">
                                                                                @if($errors->has('deskripsi'))
                                                                                {{ $errors->first('deskripsi')}}
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row">
                                                                        <label for="keterangan" class="col-form-label col-lg-5 col-md-12 labelket">Keterangan</label>
                                                                        <div class="col-lg-5 col-md-12">
                                                                            <textarea class="form-control col-form-label" id="keterangan" name="keterangan">{{$e->ket}}</textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade show" id="pills-produk" role="tabpanel" aria-labelledby="pills-produk-tab">
                                                            <div class="card removeshadow">
                                                                <div class="card-header"><h6>Rencana Penjualan</h6></div>
                                                                <div class="card-body">
                                                                    <div class="col-lg-12 col-md-12 perencanaan">
                                                                        <div class="table-responsive">
                                                                            <table class="table" style="text-align: center; width: 100%;" id="perencanaantable">
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
                                </div>
                                <div class="row d-flex justify-content-center" id="dataproduk">
                                    <div class="col-lg-11 col-md-12">
                                        <h4>Data Produk</h4>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="table-responsive">
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
                                                                        <th width="5%">No</th>
                                                                        <th width="35%">Nama Paket</th>
                                                                        <th width="10%">Jumlah</th>
                                                                        <th width="15%">Harga</th>
                                                                        <th width="15%">Ongkir</th>
                                                                        <th width="15%">Subtotal</th>
                                                                        <th width="5%">Aksi</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>

                                                                    <?php $produkpenjualan = 0; ?>
                                                                    @if(isset($e->pesanan))
                                                                    @if(isset($e->pesanan->detailpesanan))
                                                                    @foreach($e->pesanan->detailpesanan as $f)
                                                                    <tr>
                                                                        <td>{{$loop->iteration}}</td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <select name="penjualan_produk_id[]" id="{{$loop->iteration-1}}" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                                                                    <option value="{{$f->penjualan_produk_id}}" selected>{{$f->penjualanproduk->nama}}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="detail_produk" id="detail_produk{{ $loop->iteration - 1 }}">
                                                                                <fieldset>
                                                                                    <legend><b>Detail Produk</b></legend>
                                                                                    <?php $variasi = 0; ?>
                                                                                    @foreach($f->DetailPesananProduk as $g)
                                                                                    <div>
                                                                                        <div class="card-body blue-bg">
                                                                                            <h6>{{$g->GudangBarangJadi->Produk->nama}}</h6>
                                                                                            <select class="form-control variasi" name="variasi[{{$produkpenjualan}}][{{$variasi}}]" id="variasi{{$produkpenjualan}}{{$variasi}}" style="width:100%;" data-attr="variasi{{$variasi}}" data-id="{{$variasi}}">
                                                                                                <option value="{{$g->GudangBarangJadi->id}}">
                                                                                                    @if(!empty($g->GudangBarangJadi->nama))
                                                                                                    {{$g->GudangBarangJadi->nama}}
                                                                                                    @else
                                                                                                    {{$g->GudangBarangJadi->Produk->nama}}
                                                                                                    @endif
                                                                                                </option>
                                                                                            </select>
                                                                                            <span class=" invalid-feedback d-block ketstok" name="ketstok[{{$produkpenjualan}}][{{$variasi}}]" id="ketstok{{$produkpenjualan}}{{$variasi}}" data-attr="ketstok{{$variasi}}" data-id="{{$variasi}}"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php $variasi = $variasi + 1; ?>
                                                                                    @endforeach
                                                                                </fieldset>
                                                                            </div>
                                                                            <div class="detailjual" id="tes0">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group">
                                                                                    <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[{{$produkpenjualan}}]" id="produk_jumlah{{$produkpenjualan}}" style="width:100%;" value="{{$f->jumlah}}">

                                                                                </div>
                                                                                <small id="produk_ketersediaan"></small>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">

                                                                                <input type="text" class="form-control produk_harga" name="produk_harga[{{$produkpenjualan}}]" id="produk_harga{{$produkpenjualan}}" placeholder="Masukkan Harga" style="width:100%;" aria-describedby="prdhrg" value="{{number_format($f->harga,0,',','.')}}" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">

                                                                                <input type="text" class="form-control produk_ongkir" name="produk_ongkir[{{$produkpenjualan}}]" id="produk_ongkir{{$produkpenjualan}}" placeholder="Masukkan Harga" style="width:100%;" aria-describedby="prdong" value="{{number_format($f->ongkir,0,',','.')}}" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">

                                                                                <input type="text" class="form-control produk_subtotal" name="produk_subtotal[{{$produkpenjualan}}]" id="produk_subtotal{{$produkpenjualan}}" placeholder="Masukkan Subtotal" style="width:100%;" value="{{number_format(($f->harga*$f->jumlah)+$f->ongkir,0,',','.')}}" aria-describedby="prdsub" readonly />
                                                                            </div>
                                                                        </td>
                                                                        <td hidden><input type="hidden" class="rencana_id" name="rencana_id[{{$produkpenjualan}}]" id="rencana_id{{$produkpenjualan}}" readonly value="{{$f->detail_rencana_penjualan_id}}"></td>
                                                                        <td>
                                                                            <a id="removerowproduk"><i class="fas fa-minus" style="color: red"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    <?php $produkpenjualan = $produkpenjualan + 1; ?>
                                                                    @endforeach
                                                                    @else
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <select name="penjualan_produk_id[0]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                                                                </select>
                                                                            </div>
                                                                            <div class="detail_produk" id="detail_produk0">
                                                                            </div>
                                                                            <div class="detailjual" id="tes0">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group">
                                                                                    <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[]" id="produk_jumlah" style="width:100%;" value="">

                                                                                </div>
                                                                                <small id="produk_ketersediaan"></small>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">

                                                                                <input type="text" class="form-control produk_harga" name="produk_harga[0]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;" aria-describedby="prdhrg" value="" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">

                                                                                <input type="text" class="form-control produk_ongkir" name="produk_ongkir[0]" id="produk_ongkir0" placeholder="Masukkan Ongkir" style="width:100%;" aria-describedby="prdhrg" value="" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">

                                                                                <input type="text" class="form-control produk_subtotal" name="produk_subtotal[0]" id="produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" value="" aria-describedby="prdsub" readonly />
                                                                            </div>
                                                                        </td>
                                                                        <td hidden><input type="hidden" class="rencana_id" name="rencana_id[]" id="rencana_id0" readonly></td>
                                                                        <td>
                                                                            <a id="removerowproduk"><i class="fas fa-minus" style="color: red"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                    @else
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                <select name="penjualan_produk_id[0]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                                                                </select>
                                                                            </div>
                                                                            <div class="detail_produk" id="detail_produk0">
                                                                            </div>
                                                                            <div class="detailjual" id="tes0">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group">
                                                                                    <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[0]" id="produk_jumlah0" style="width:100%;" value="">

                                                                                </div>
                                                                                <small id="produk_ketersediaan"></small>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">

                                                                                <input type="text" class="form-control produk_harga" name="produk_harga[0]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;" aria-describedby="prdhrg" value="" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">

                                                                                <input type="text" class="form-control produk_ongkir" name="produk_ongkir[0]" id="produk_ongkir0" placeholder="Masukkan Harga" style="width:100%;" aria-describedby="prdhrg" value="" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">

                                                                                <input type="text" class="form-control produk_subtotal" name="produk_subtotal[0]" id="produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" value="" aria-describedby="prdsub" readonly />
                                                                            </div>
                                                                        </td>
                                                                        <td hidden><input type="hidden" class="rencana_id" name="rencana_id[]" id="rencana_id0" readonly></td>
                                                                        <td>
                                                                            <a id="removerowproduk"><i class="fas fa-minus" style="color: red"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    @endif
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="5" style="text-align:right;">Total Harga</th>
                                                                        <th id="totalhargaprd" class="align-right">Rp.
                                                                            @if(isset($e->pesanan))
                                                                            @if(isset($e->pesanan->detailpesanan))
                                                                            <?php $x = 0;
                                                                            foreach ($e->pesanan->detailpesanan as $f) {
                                                                                $x += ($f->harga * $f->jumlah) + $f->ongkir;
                                                                            }
                                                                            ?>
                                                                            {{number_format($x,0,',','.')}}
                                                                            @endif
                                                                            @endif
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
                                    <div class="col-lg-11 col-md-12">
                                        <span>
                                            <a href="{{route('penjualan.penjualan.show')}}" type="button" class="btn btn-danger">
                                                Batal
                                            </a>
                                        </span>
                                        <span class="float-right">
                                            <button type="submit" class="btn btn-warning" id="btnsimpan">
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
    </div>
    </div>
    </div>
</section>
@stop

@section('adminlte_js')
<script>
    $(function() {
        $(".os-content-arrange").remove();
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        // var instansi_array = [];

        $("#tgl_edit").attr("max", today);
        $("#batas_kontrak").attr("min", today);

        loop();
        load_variasi();

        // function getinstansi(id){
        //     instansi_array = [];
        //     $.ajax({
        //         url: '/api/customer/get_instansi/' + id+'/'+yyyy,
        //         type: 'POST',
        //         dataType: 'json',
        //         async: false,
        //         success: function(data) {
        //             $.each(data, function( i, val ) {
        //                 instansi_array.push(val);
        //             });
        //         }
        //     });
        // }

        function perencanaan(customer_id, instansi){
            $('#perencanaantable').DataTable({
                searching: false,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/penjualan/rencana/produk/'+customer_id+'/'+instansi+'/'+yyyy,
                    'dataType': 'json',
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
                        render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' ),
                        className: 'align-right nowrap-text'
                    },
                    {
                        data: 'aksi',
                        className: 'align-center nowrap-text'
                    },
                ]
            });
        }

        // getinstansi("{{$e->customer_id}}");
        perencanaan("{{$e->customer_id}}", "{{$e->instansi}}");

        function loop() {
            for (i = 0; i < 20; i++) {
                select_data(i);
            }
        }

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

        $('#customer_id').select2({
            ajax: {
                minimumResultsForSearch: 20,
                placeholder: "Pilih Customer",
                dataType: 'json',
                theme: "bootstrap",
                delay: 250,
                type: 'GET',
                url: '/api/customer/select',
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
            $.ajax({
                url: '/api/customer/select/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#alamat_customer').val(data[0].alamat);
                    $('#telepon_customer').val(data[0].telp);
                }
            });

            // getinstansi(id);
            perencanaan(id, document.getElementById("instansi").value);
        });

        $('.provinsi').select2({
            ajax: {
                minimumResultsForSearch: 20,
                placeholder: "Pilih Produk",
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
        })

        if ('{{$e->customer_id}}' == 484) {
            var cust_id = 'belum';
            $("#customer_id").attr('disabled', true);
            $("#customer_id").empty().trigger('change');
            $("#provinsi").attr('disabled', true);
            $("#provinsi").empty().trigger('change');
            $("#alamat").val("");
            $("#telepon").val("");
        } else {
            var cust_id = 'sudah';
        }
        if ('{{$e->status}}' != 'sepakat') {
            if ('{{$e->status}}' == 'draft') {
                $("#dataproduk").addClass("hide");
            }
            $("#provinsi").attr('disabled', true);
            $("#provinsi").empty().trigger('change')
            $("#batas_kontrak").attr('disabled', true);
        }

        $('input[type="radio"][name="namadistributor"]').on('change', function() {
            if ($(this).val() != "") {
                if ($(this).val() == "sudah") {
                    $("#customer_id").attr('disabled', false);
                } else {
                    $("#customer_id").attr('disabled', true);
                    $("#customer_id").empty().trigger('change')
                    $("#alamat").val("");
                    $("#telepon").val("");
                }
                checkvalidasi();
            } else {
                $("#msgstatus").text("Status Harus dipilih");
                $("#status").addClass('is-invalid');
                $('#btntambah').attr("disabled", true);
            }
        });

        $('input[name="status_akn"][value={{$e->status}}]').attr('checked', 'checked');
        $('input[name="namadistributor"][value=' + cust_id + ']').attr('checked', 'checked');
        $('#customer_id').on('keyup change', function() {
            if ($(this).val() != "") {
                $('#msgcustomer_id').text("");
                $('#customer_id').removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $('#msgcustomer_id').text("Silahkan Pilih Customer");
                $('#customer_id').addClass('is-invalid');
            }
        });

        $('input[type="radio"][name="status_akn"]').on('change', function() {
            if ($(this).val() != "") {
                if ($(this).val() == "sepakat") {
                    $('#checkbox_nopaket').addClass('hide');
                    $('#isi_nopaket').prop("checked", false);
                    $('#no_paket').attr('readonly', false);
                    $("#dataproduk").removeClass("hide");
                    $("#batas_kontrak").attr('disabled', false);
                    $("#provinsi").attr('disabled', false);
                    // $("#produktable tbody").empty();
                    // $('#produktable tbody').append(trproduktable());
                    numberRowsProduk($("#produktable"));
                } else if ($(this).val() == "draft") {
                    $('#checkbox_nopaket').removeClass('hide');
                    $('#isi_nopaket').prop("checked", false);
                    $('#no_paket').attr('readonly', true);
                    $("#produktable tbody").empty();
                    $("#totalhargaprd").text("Rp. 0");
                    $("#dataproduk").addClass("hide");
                    $("#batas_kontrak").attr('disabled', true);
                    $("#provinsi").attr('disabled', true);
                    $("#provinsi").empty().trigger('change')
                } else {
                    //$("#produktable tbody").empty();
                    //$('#produktable tbody').append(trproduktable());
                    $('#checkbox_nopaket').addClass('hide');
                    $('#isi_nopaket').prop("checked", false);
                    $('#no_paket').attr('readonly', false);
                    numberRowsProduk($("#produktable"));
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
                $("#status").addClass('is-invalid');
            }
        });

        $(document).on('change', '#isi_nopaket', function(){
            if($('#isi_nopaket:checked').length > 0){
                $('#no_paket').attr('readonly', false);
            }else{
                $('#no_paket').attr('readonly', true);
            }
        })


        $('#tanggal_pemesanan').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msgtanggal_pemesanan").text("");
                $("#tanggal_pemesanan").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgtanggal_pemesanan").text("Isi Tanggal Pemesanan");
                $("#tanggal_pemesanan").addClass('is-invalid');
            }
        });

        $('input[type="radio"][name="do_akn"]').on('change', function() {
            if ($(this).val() == "yes") {
                $("#do_detail_no_akn").removeClass("hide");
                $("#do_detail_tgl_akn").removeClass("hide");
            } else if ($(this).val() == "no") {
                $("#do_detail_no_akn").addClass("hide");
                $("#do_detail_tgl_akn").addClass("hide");
            }
        });

        $('#batas_kontrak').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msgbatas_kontrak").text("");
                $("#batas_kontrak").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgbatas_kontrak").text("Batas Kontrak Harus diisi");
                $("#batas_kontrak").addClass('is-invalid');
            }
        });

        $('#tgl_edit').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msgtgl_edit").text("");
                $("#tgl_edit").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgtgl_edit").text("Tanggal Edit Harus diisi");
                $("#tgl_edit").addClass('is-invalid');
            }
        });

        $('#pills-produk-tab').on('click', function(){
            var cust = $('#customer_id').val();
            var instansi = $('#instansi').val();
            perencanaan(cust, instansi);
        })

        $('input[name="instansi"]').on('keyup change', function() {
            if ($(this).val() != "") {
                var cust = $('.customer_id').val();
                $("#msginstansi").text("");
                $("#instansi").removeClass('is-invalid');
                perencanaan(cust, $(this).val());
            } else if ($(this).val() == "") {
                $("#msginstansi").text("Instansi Harus diisi");
                $("#instansi").addClass('is-invalid');
            }
        });
        $('#deskripsi').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msgdeskripsi").text("");
                $("#deskripsi").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgdeskripsi").text("Deskripsi harus diisi");
                $("#deskripsi").addClass('is-invalid');
            }
        });

        $('#no_urut').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msgno_urut").text("");
                $("#no_urut").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgno_urut").text("No Urut harus diisi");
                $("#no_urut").addClass('is-invalid');
            }
        });

        $('#no_po_akn').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#msgno_po_akn").text("");
                $("#no_po_akn").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgno_po_akn").text("Nomor PO Harus diisi");
                $("#no_po_akn").addClass('is-invalid');
            }
        });

        $('#tanggal_po_akn').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgtanggal_po_akn").text("");
                $("#tanggal_po_akn").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgtanggal_po_akn").text("Tanggal PO Harus diisi");
                $("#tanggal_po_akn").addClass('is-invalid');
            }
        });

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


        function totalhargaprd() {
            var totalharga = 0;
            $('#produktable').find('tr .produk_subtotal').each(function() {
                var subtotal = replaceAll($(this).val(), '.', '');
                totalharga = parseInt(totalharga) + parseInt(subtotal);
                $("#totalhargaprd").text("Rp. " + totalharga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
            })
        }

        function numberRowsProduk($t) {
            var c = 0 - 2;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('.penjualan_produk_id').attr('name', 'penjualan_produk_id[' + j + ']');
                $(el).find('.penjualan_produk_id').attr('id', j);
                var variasi = $(el).find('.variasi');
                for (var k = 0; k < variasi.length; k++) {
                    $(el).find('select[name="variasi[' + j + '][' + k + ']"').select2();
                    $(el).find('select[data-attr="variasi' + k + '"]').attr('name', 'variasi[' + j + '][' + k + ']');
                    $(el).find('select[data-attr="variasi' + k + '"]').attr('id', 'variasi' + j + '' + k);
                    $(el).find('span[data-attr="ketstok' + k + '"]').attr('name', 'ketstok[' + j + '][' + k + ']');
                    $(el).find('span[data-attr="ketstok' + k + '"]').attr('id', 'ketstok' + j + '' + k);
                }
                $(el).find('.detail_produk').attr('id', 'detail_produk' + j);
                $(el).find('.produk_harga').attr('id', 'produk_harga' + j);
                $(el).find('.produk_harga').attr('name', 'produk_harga[' + j + ']');
                $(el).find('.produk_ongkir').attr('id', 'produk_ongkir' + j);
                $(el).find('.produk_ongkir').attr('name', 'produk_ongkir[' + j + ']');
                $(el).find('.produk_jumlah').attr('id', 'produk_jumlah' + j);
                $(el).find('.produk_jumlah').attr('name', 'produk_jumlah[' + j + ']');
                $(el).find('.produk_subtotal').attr('id', 'produk_subtotal' + j);
                $(el).find('.produk_subtotal').attr('name', 'produk_subtotal[' + j + ']');
                $(el).find('.rencana_id').attr('id', 'rencana_id' + j);
                $(el).find('.rencana_id').attr('name', 'rencana_id[' + j + ']');
                $(el).find('.detail_jual').attr('id', 'detail_jual' + j);
                select_data($(el).find('.penjualan_produk_id').attr('id'));
            });
        }

        $("#produktable").on('keyup change', '.penjualan_produk_id', function() {
            var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
            var harga = $(this).closest('tr').find('.produk_harga').val();
            var ongkir = $(this).closest('tr').find('.produk_ongkir').val();
            var subtotal = $(this).closest('tr').find('.produk_subtotal');
            $(this).closest('tr').find('.rencana_id').val("");
            if (jumlah != "" && harga != "") {
                var hargacvrt = replaceAll(harga, '.', '');
                var ongkircvrt = replaceAll(ongkir, '.', '');
                subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                totalhargaprd();
            } else {
                subtotal.val(formatmoney("0"));
                totalhargaprd();
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
                subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                totalhargaprd();
                for (var i = 0; i < variasi.length; i++) {
                    var variasires = $('select[name="variasi[' + ppid + '][' + i + ']"]').select2('data')[0];
                    var kebutuhan = jumlah * variasires.jumlah;
                    if (cek_stok(variasires.id) < kebutuhan) {
                        var jumlah_kekurangan = 0;
                        if (cek_stok(variasires.id) < 0) {
                            jumlah_kekurangan = kebutuhan;
                        } else {
                            jumlah_kekurangan = Math.abs(cek_stok(variasires.id) - kebutuhan);
                        }
                        $('select[name="variasi[' + ppid + '][' + i + ']"]').addClass('is-invalid');
                        $('span[name="ketstok[' + ppid + '][' + i + ']"]').text('Jumlah Kurang ' + jumlah_kekurangan + ' dari Permintaan');
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
        });

        $('#produktable').on('keyup change', '.variasi', function() {
            $(this).val();
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
                $('span[name="ketstok[' + ppid + '][' + id + ']"]').text('Jumlah Kurang ' + jumlah_kekurangan + ' dari Permintaan');
            } else if (cek_stok(vals.id) >= kebutuhan) {
                $('select[name="variasi[' + ppid + '][' + id + ']"]').removeClass('is-invalid');
                $('span[name="ketstok[' + ppid + '][' + id + ']"]').text('');
            }
        })


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
                subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                totalhargaprd();
            } else {
                subtotal.val(formatmoney("0"));
                totalhargaprd();
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
        });

        function trproduktable() {
            var data = `<tr>
                <td></td>
                <td>
                    <div class="form-group">
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
                    <div class="form-group d-flex justify-content-center">
                        <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;"/>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <input type="text" class="form-control produk_ongkir" name="produk_ongkir[]" id="produk_ongkir0" placeholder="Masukkan Harga" style="width:100%;"/>
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
        });

        $('#produktable').on('click', '#removerowproduk', function(e) {
            $(this).closest('tr').remove();
            numberRowsProduk($("#produktable"));
            totalhargaprd();
            if ($('#produktable > tbody > tr').length <= 0) {
                $("#totalhargaprd").text("0");
            }
        });

        function select_data(i) {
            $('#' + i).select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    dataType: 'json',
                    delay: 250,
                    type: 'GET',
                    url: '/api/penjualan_produk/select/',
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
            }).change(function(i) {
                var index = $(this).attr('id');
                var id = $(this).val();
                $.ajax({
                    url: '/api/penjualan_produk/select/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        $('#produk_harga' + index).val(formatmoney(res[0].harga));
                        $('#produk_subtotal' + index).val(formatmoney(res[0].harga * $('#produk_jumlah' + index).val()));
                        $('#rencana_id' + index).val("");
                        totalhargaprd();
                        var tes = $('#detail_produk' + index);
                        tes.empty();
                        var datas = "";
                        tes.append(`<fieldset><legend><b>Detail Produk</b></legend>`);
                        for (var x = 0; x < res[0].produk.length; x++) {
                            var data = [];
                            tes.append(`<div>`);
                            tes.append(`<div class="card-body blue-bg">
                                        <h6>` + res[0].produk[x].nama + `</h6>
                                        <select class="form-control variasi" name="variasi[` + index + `][` + x + `]" id="variasi` + index + `` + x + `" style="width:100%;" data-attr="variasi` + x + `" data-id="` + x + `"></select>
                                        <span class="invalid-feedback d-block ketstok" name="ketstok[` + index + `][` + x + `]" id="ketstok` + index + `` + x + `" data-attr="ketstok` + x + `" data-id="` + x + `"></span>
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
                                    var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                                    return $span;
                                },
                                templateSelection: function(data) {
                                    var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                                    return $span;
                                }
                            });

                            $(`select[name="variasi[` + index + `][` + x + `]"]`).trigger("change");
                            tes.append(`</div>`)
                        }
                        tes.append(`</fieldset>`);
                    }
                });
            });
        }


        function load_variasi() {
            produk = [];
            produk = <?php
                        $prd = array();
                        if (isset($e->Pesanan)) {
                            $p = array();
                            if (isset($e->Pesanan->DetailPesanan)) {
                                echo json_encode($e->Pesanan->DetailPesanan);
                            } else {
                                echo json_encode($prd);
                            }
                        } else {
                            echo json_encode($prd);
                        } ?>;
            if (produk.length > 0) {
                for (var w = 0; w < produk.length; w++) {
                    $.ajax({
                        url: '/api/penjualan_produk/select/' + produk[w]['penjualan_produk_id'],
                        type: 'GET',
                        dataType: 'json',
                        async: false,
                        success: function(res) {
                            for (var x = 0; x < res[0].produk.length; x++) {
                                var data = [];
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
                                $('select[name="variasi[' + w + '][' + x + ']"]').select2({
                                    placeholder: 'Pilih Variasi',
                                    data: data,
                                    templateResult: function(data) {
                                        var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                                        return $span;
                                    },
                                    templateSelection: function(data) {
                                        var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                                        return $span;
                                    }
                                });

                                $('select[name="variasi[' + w + '][' + x + ']"]').trigger("change");
                            }
                        }
                    });
                }
            }

        }

        function autocomplete(inp, arr) {
            /*the autocomplete function takes two arguments,
            the text field element and an array of possible autocompleted values:*/
            var currentFocus;
            /*execute a function when someone writes in the text field:*/
            inp.addEventListener("input", function(e) {
                var a, b, i, val = this.value;
                /*close any already open lists of autocompleted values*/
                closeAllLists();
                if (!val) { return false;}
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
                        (or any other open lists of autocompleted values:*/
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
            document.addEventListener("click", function (e) {
                closeAllLists(e.target);
            });
        }

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
                        url: '/api/customer/get_instansi/' + $('#customer_id').val() + '/' + yyyy,
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

        // autocomplete(document.getElementById("instansi"), instansi_array);

        $('#perencanaantable').on('click', '#btntransfer', function() {
            var id = $(this).closest('tr').find('#btntransfer').attr('data-id');
            var nama_produk = $(this).closest('tr').find('#btntransfer').attr('data-nama_produk');
            var produk_id = $(this).closest('tr').find('#btntransfer').attr('data-produk');
            var jumlah = $(this).closest('tr').find('#btntransfer').attr('data-jumlah');
            var harga = $(this).closest('tr').find('#btntransfer').attr('data-harga');
            transferproduk(id, nama_produk, produk_id, jumlah, harga);
        });

        function transferproduk(id, nama_produk, produk_id, jumlah, harga){
            var data = `<tr>
                    <td></td>
                    <td>
                        <div class="form-group">
                            <select name="penjualan_produk_id[]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                <option value="`+produk_id+`">`+nama_produk+`</option>
                            </select>
                            <div class="detailjual" id="tes0">
                            </div>
                        </div>
                        <div id="detail_produk" class="detail_produk"></div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">
                            <div class="input-group">
                                <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[]" id="produk_jumlah0" value="`+jumlah+`" style="width:100%;">

                            </div>
                            <small id="produk_ketersediaan"></small>
                        </div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">

                            <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" value="`+formatmoney(harga)+`" placeholder="Masukkan Harga" style="width:100%;"/>
                        </div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">

                            <input type="text" class="form-control produk_ongkir" name="produk_ongkir[]" id="produk_ongkir0" placeholder="Masukkan Ongkir" style="width:100%;"/>
                        </div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">
                            <input type="text" class="form-control produk_subtotal" name="produk_subtotal[]" id="produk_subtotal0" placeholder="Masukkan Subtotal" value="`+ formatmoney(jumlah * parseInt(harga)) + `" style="width:100%;" readonly/>
                        </div>
                    </td>
                    <td hidden><input type="hidden" class="rencana_id" name="rencana_id[]" id="rencana_id0" readonly value="`+id+`"></td>
                    <td>
                        <a id="removerowproduk"><i class="fas fa-minus" style="color: red;"></i></a>
                    </td>
                </tr>`;

            if ($('#produktable > tbody > tr').length <= 0) {
                $('#produktable tbody').append(data);
                select_data();
                numberRowsProduk($("#produktable"));
                totalhargaprd();
            } else {
                $('#produktable tbody tr:last').after(data);
                select_data();
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
                                        <select class="form-control variasi" name="variasi[` + index + `][` + x + `]" style="width:100%;" id="variasi` + index + `` + x + `" data-attr="variasi` + x + `" data-id="` + x + `"></select>
                                        <span class="invalid-feedback d-block ketstok" name="ketstok[` + index + `][` + x + `]" id="ketstok` + index + `` + x + `" data-attr="ketstok` + x + `" data-id="` + x + `"></span>
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
                                var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                                return $span;
                            },
                            templateSelection: function(data) {
                                var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
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
    });
</script>
@stop
