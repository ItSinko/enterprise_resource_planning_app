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
                <li class="breadcrumb-item active">Edit SPA</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->

@stop

@section('adminlte_css')
<style>
    table > tbody > tr > td > .form-group > .select2 > .selection > .select2-selection--single {
    height: 100% !important;
    }
    table > tbody > tr > td > .form-group > .select2 > .selection > .select2-selection > .select2-selection__rendered {
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
        background-color: #ffeab8;
    }

    #produktable {
        width: 1371px !important;
    }

    #parttable {
        width: 1371px !important;
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

        .labelket {
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

        .labelket {
            text-align: left;
        }
    }

    .select_item .select2-selection--single {
  height: 100% !important;
}
.select_item .select2-selection__rendered{
  word-wrap: break-word !important;
  text-overflow: inherit !important;
  white-space: normal !important;
}
</style>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        @foreach($spa as $e)
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-11 col-md-12">
                                <h5 class="margin">Info Penjualan SPA</h5>
                                <div class="row d-flex justify-content-between">
                                    <div class="p-2 cust">
                                        <div class="margin">
                                            <small>Info Customer</small>
                                        </div>
                                        <div id="nama_customer" class="margin"><b>{{$e->customer->nama}}</b></div>
                                        <div id="alamat" class="margin"><b>{{$e->customer->alamat}}</b></div>
                                        <div id="provinsi" class="margin"><b>{{$e->customer->provinsi->nama}}</b></div>
                                        <div id="telepon" class="margin"><b>{{$e->customer->telp}}</b></div>
                                    </div>
                                    <div class="p-2">
                                        <div class="margin">
                                            <div><small class="text-muted">No PO</small></div>
                                            <div id="no_po">
                                                <b>
                                                    @if($e->Pesanan)
                                                    {{$e->Pesanan->no_po}}
                                                    @endif
                                                </b>
                                            </div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal PO</small></div>
                                            <b>
                                                <div id="tgl_po">
                                                    @if($e->Pesanan)
                                                    @if(empty($e->Pesanan->tgl_po) || $e->Pesanan->tgl_po == "0000-00-00")
                                                    -
                                                    @else
                                                    {{$e->Pesanan->tgl_po}}
                                                    @endif
                                                    @endif
                                                </div>
                                            </b>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <div class="margin">
                                            <div><small class="text-muted">No DO</small></div>
                                            <div id="no_po">
                                                <b>
                                                    @if($e->Pesanan != "")
                                                    @if(!empty($e->Pesanan->no_do))
                                                    {{$e->Pesanan->no_do}}
                                                    @else
                                                    -
                                                    @endif
                                                    @endif
                                                </b>
                                            </div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Tanggal DO</small></div>
                                            <div id="no_po">
                                                <b>
                                                    @if($e->Pesanan != "")
                                                    @if(!empty($e->Pesanan->tanggal_do))
                                                    {{$e->Pesanan->tanggal_do}}
                                                    @else
                                                    -
                                                    @endif
                                                    @endif
                                                </b>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2">
                                        <div class="margin">
                                            <div><small class="text-muted">No SO</small></div>
                                            <div id="no_so">
                                                <b>
                                                    @if($e->Pesanan != "")
                                                    @if(!empty($e->Pesanan->so))
                                                    {{$e->Pesanan->so}}
                                                    @else
                                                    -
                                                    @endif
                                                    @endif
                                                </b>
                                            </div>
                                        </div>
                                        <div class="margin">
                                            <div><small class="text-muted">Status</small></div>

                                            @if($e->log == "penjualan")
                                            <div id="status" class="badge red-text ">Penjualan</div>
                                            @elseif($e->log == "po")
                                            <div id="status" class="badge purple-text ">PO</div>
                                            @elseif($e->log == "gudang")
                                            <div id="status" class="badge orange-text ">Gudang</div>
                                            @elseif($e->log == "qc")
                                            <div id="status" class="badge yellow-text ">QC</div>
                                            @elseif($e->log == "logistik")
                                            <div id="status" class="badge blue-text ">Logistik</div>
                                            @elseif($e->log == "pengiriman")
                                            <div id="status" class="badge green-text ">Pengiriman</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
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
                            <form method="post" action="{{route('penjualan.penjualan.update_spa', ['id' => $e->id])}}">
                                {{csrf_field()}}
                                {{method_field('PUT')}}
                                <div class="row d-flex justify-content-center">
                                    <div class="col-lg-10 col-md-12">
                                        <h4>Info Customer</h4>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-horizontal">
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Nama Customer</label>
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
                                                        <div class="col-7">
                                                            <input type="text" class="form-control col-form-label" name="alamat" id="alamat_customer" readonly value="{{$e->customer->alamat}}" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Telepon</label>
                                                        <div class="col-lg-5 col-md-12">
                                                            <input type="text" class="form-control col-form-label" name="telepon" id="telepon_customer" readonly value="{{$e->customer->telp}}" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="penjualan" class="col-form-label col-lg-5 col-md-12 labelket">Barang</label>
                                                        <div class="col-5 col-form-label">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="jenis_pen" value="produk" name="jenis_pen[]" @if(count($e->pesanan->detailpesanan) > 0 )checked  @endif>
                                                                <label class="form-check-label" for="inlineCheckbox1">Produk</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="jenis_pen" value="sparepart" name="jenis_pen[]" @if($e->pesanan->getJumlahPesananNonJasa())checked  @endif>
                                                                <label class="form-check-label" for="inlineCheckbox1">Sparepart</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="jasacheck"  name="jasacheck[]" @if($e->pesanan->getJumlahPesananJasa())  checked    @endif  disabled  >
                                                                <label class="form-check-label" for="inlineCheckbox1">Jasa </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-7">
                                                            <input type="text" class="d-none" id="c_produk"value="{{count($e->pesanan->detailpesanan)}}" />
                                                            <input type="text" class="d-none" id="c_part"value="{{$e->pesanan->getJumlahPesananNonJasa()}}" />
                                                        </div>
                                                    </div>
                                                    {{-- <div class="form-group row">
                                                        <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Barang</label>
                                                        <div class="col-lg-5 col-md-12">
                                                            <div class="form-check form-check-inline col-form-label" id="penj_prd">
                                                                <input class="form-check-input" type="radio" name="jenis_penj" id="jenis_penj1" value="produk" @if(count($e->pesanan->detailpesanan) > 0 && count($e->pesanan->detailpesananpart)) checked @endif/>
                                                                <label class="form-check-label" for="jenis_penj1">Produk</label>
                                                            </div>
                                                            <div class="form-check form-check-inline col-form-label" id="penj_spr">
                                                                <input class=" form-check-input" type="radio" name="jenis_penj" id="jenis_penj2" value="sparepart" @if(count($e->pesanan->detailpesanan) <= 0 && count($e->pesanan->detailpesananpart) > 0) checked @endif/>
                                                                    <label class="form-check-label" for="jenis_penj2">Sparepart</label>
                                                            </div>
                                                            <div class="form-check form-check-inline col-form-label" id="penj_sem">
                                                                <input class="form-check-input" type="radio" name="jenis_penj" id="jenis_penj3" value="semua" @if(count($e->pesanan->detailpesanan) > 0 && count($e->pesanan->detailpesananpart) > 0) checked @endif/>
                                                                <label class="form-check-label" for="jenis_penj3">Produk + Sparepart</label>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-flex justify-content-center">
                                    <div class="col-lg-10 col-md-12">
                                        <h4>Info Penjualan</h4>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Delivery Order</label>
                                                    <div class="col-lg-5 col-md-12 col-form-label">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="do" id="yes" value="yes" {{ empty($e->Pesanan->no_do)? "" : "checked" }} />
                                                            <label class="form-check-label" for="yes">Tersedia</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="do" id="no" value="no" {{ empty($e->Pesanan->no_do)? "checked" : "" }} />
                                                            <label class="form-check-label" for="no">Tidak tersedia</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row    @if(empty($e->Pesanan->no_do)) hide @endif " id="do_detail_no">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Nomor DO</label>
                                                    <div class="col-4">
                                                        <input type="text" class="form-control col-form-label @error('no_do') is-invalid @enderror" id="no_do" name="no_do" value="{{$e->Pesanan->no_do}}" />
                                                        <div class="invalid-feedback" id="msgno_do">
                                                            @if($errors->has('no_do'))
                                                            {{ $errors->first('no_do')}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row  @if(empty($e->Pesanan->tgl_do)) hide @endif " id="do_detail_tgl">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Tanggal DO</label>
                                                    <div class="col-4">
                                                        <input type="date" class="form-control col-form-label @error('tanggal_do') is-invalid @enderror" id="tanggal_do" name="tanggal_do" value="{{$e->Pesanan->tgl_do}}" />
                                                        <div class="invalid-feedback" id="msgtanggal_po">
                                                            @if($errors->has('tanggal_do'))
                                                            {{ $errors->first('tanggal_do')}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label for="keterangan" class="col-form-label col-lg-5 col-md-12 labelket">Keterangan</label>
                                                    <div class="col-lg-5 col-md-12">
                                                        <textarea class="form-control col-form-label" id="nonketerangan" name="keterangan">{{$e->Pesanan->ket}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                @if(isset($e->pesanan))
                                <div class="row d-flex justify-content-center @if(count($e->pesanan->detailpesanan) <= 0) hide @endif" id="dataproduk">
                                    <div class="col-lg-10 col-md-12">
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
                                                                        <th width="2%">No</th>
                                                                        <th width="35%">Nama Paket</th>
                                                                        <th width="15%">Jumlah</th>
                                                                        <th width="6%">Stok Distributor</th>
                                                                        <th width="20%">Harga</th>
                                                                        <th width="20%">Subtotal</th>
                                                                        <th width="2%">Aksi</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>

                                                                    <?php $produkpenjualan = 0; ?>
                                                                    @if(isset($e->pesanan->detailpesanan))
                                                                    @foreach($e->pesanan->detailpesanan as $f)
                                                                    <tr>
                                                                        <td>{{$loop->iteration}}</td>
                                                                        <td>
                                                                            <div class="form-group select_item">
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
                                                                                                <option value="{{$g->GudangBarangJadi->id}}" selected>
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
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text" id="produk_satuan">pcs</span>
                                                                                    </div>
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
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text" id="prdhrg">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control produk_harga" name="produk_harga[{{$produkpenjualan}}]" id="produk_harga{{$produkpenjualan}}" placeholder="Masukkan Harga" style="width:100%;" aria-describedby="prdhrg" value="{{number_format($f->harga,0,',','.')}}" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text" id="prdsub">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control produk_subtotal" name="produk_subtotal[{{$produkpenjualan}}]" id="produk_subtotal{{$produkpenjualan}}" placeholder="Masukkan Subtotal" style="width:100%;" value="{{number_format($f->harga*$f->jumlah,0,',','.')}}" aria-describedby="prdsub" readonly />
                                                                            </div>
                                                                        </td>
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
                                                                            <div class="form-group select_item">
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
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text" id="produk_satuan">pcs</span>
                                                                                    </div>
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
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text" id="prdhrg">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control produk_harga" name="produk_harga[0]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;" aria-describedby="prdhrg" value="" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text" id="prdsub">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control produk_subtotal" name="produk_subtotal[0]" id="produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" value="" aria-describedby="prdsub" readonly />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <a id="removerowproduk"><i class="fas fa-minus" style="color: red"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    @endif

                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="5" style="text-align:right;">Total Harga</th>
                                                                        <th colspan="2" id="totalhargaprd" class="align-right">Rp.
                                                                            @if(isset($e->pesanan->detailpesanan))
                                                                            <?php $x = 0;
                                                                            foreach ($e->pesanan->detailpesanan as $f) {
                                                                                $x += $f->harga * $f->jumlah;
                                                                            }
                                                                            ?>
                                                                            {{number_format($x,0,',','.')}}
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


                                <div class="row d-flex justify-content-center @if($e->pesanan->getJumlahPesananNonJasa() <= 0) hide @endif" id="datapart">
                                    <div class="col-lg-10 col-md-12">
                                        <h4>Data Part</h4>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="table-responsive">
                                                            <table class="table" style="text-align: center;" id="parttable" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th colspan="7">
                                                                            <button type="button" class="btn btn-primary float-right" id="addrowpart">
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
                                                                    @if(isset($e->pesanan->detailpesananpart))
                                                                    @foreach($e->pesanan->DetailPesananPartNonJasa() as $f)
                                                                    <tr>
                                                                        <td>{{$loop->iteration}}</td>
                                                                        <td>
                                                                            <div class="form-group select_item">
                                                                                <select class="select2 form-control select-info custom-select part_id" name="part_id[]" id="part_id{{$loop->iteration-1}}" width="100%">
                                                                                    <option value="{{$f->sparepart->id}}" selected>{{$f->sparepart->nama}}</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group">
                                                                                    <input type="number" class="form-control part_jumlah" aria-label="produk_satuan" name="part_jumlah[]" id="part_jumlah{{$loop->iteration-1}}" style="width:100%;" value="{{$f->jumlah}}">
                                                                                    <div class="input-group-append">
                                                                                        <span class="input-group-text" id="part_satuan">pcs</span>
                                                                                    </div>
                                                                                </div>
                                                                                <small id="part_ketersediaan"></small>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control part_harga" name="part_harga[]" id="part_harga{{$loop->iteration-1}}" placeholder="Masukkan Harga" style="width:100%;" value="{{number_format($f->harga,0,',','.')}}" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control part_subtotal" name="part_subtotal[]" id="part_subtotal{{$loop->iteration-1}}" placeholder="Masukkan Subtotal" style="width:100%;" value="{{number_format($f->jumlah * $f->harga,0,',','.')}}" readonly />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <a id="removerowpart"><i class="fas fa-minus" style="color: red"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                    @endif
                                                                </tbody>
                                                                <tfoot>
                                                                    <th colspan="4" style="text-align:right;">Total Harga</th>
                                                                    <th id="totalhargapart" class="align-right">Rp.
                                                                        @if(isset($e->pesanan->detailpesananpart))
                                                                        <?php $x = 0;
                                                                        foreach ($e->pesanan->detailpesananpart as $f) {
                                                                            $x += $f->harga * $f->jumlah;
                                                                        }
                                                                        ?>
                                                                        {{number_format($x,0,',','.')}}
                                                                        @endif
                                                                    </th>
                                                                </tfoot>

                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif


                                <div class="row d-flex justify-content-center  @if($e->pesanan->getJumlahPesananJasa() <= 0) hide @endif" id="datajasa">
                                    <div class="col-lg-10 col-md-12">
                                        <h4>Jasa</h4>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="table-responsive justify-content-center">
                                                            <table class="table" style="text-align: center;" id="jasatable">
                                                                <thead>

                                                                    <tr>
                                                                        <th width="5%">No</th>
                                                                        <th width="35%">Nama Jasa</th>
                                                                        <th width="20%">Harga</th>
                                                                        <th width="20%">Subtotal</th>

                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                    @foreach($e->pesanan->DetailPesananPartJasa() as $f)
                                                                    <tr>
                                                                        <td>{{$loop->iteration}}</td>
                                                                        <td>
                                                                            <div class="form-group">
                                                                                    <input  width="100%" class="form-control "  style="text-align: center" value="{{$f->sparepart->nama}}" readonly>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control " style="width:100%;" value="{{number_format($f->harga,0,',','.')}}" readonly />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-group d-flex justify-content-center">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">Rp</span>
                                                                                </div>
                                                                                <input type="text" class="form-control "  style="width:100%;" value="{{number_format($f->jumlah * $f->harga,0,',','.')}}" readonly />
                                                                            </div>
                                                                        </td>

                                                                    </tr>
                                                                    @endforeach

                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="3" style="text-align:right;">Total Harga</th>
                                                                        <th id="totalhargajasa" class="align-right">Rp.
                                                                            @if($e->pesanan->getJumlahPesananJasa() > 0)
                                                                        <?php $x = 0;
                                                                        foreach ($e->pesanan->DetailPesananPartJasa() as $f) {
                                                                            $x += $f->harga * $f->jumlah;
                                                                        }
                                                                        ?>
                                                                        {{number_format($x,0,',','.')}}
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
                                    <div class="col-lg-10 col-md-12">
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
        @endforeach
    </div>
</section>
@stop

@section('adminlte_js')
<script>
    $(function() {
        var penjualan_produk_id = false;
        var variasi = false;
        var produk_jumlah = false;
        var produk_harga = false;

        var part_id = false;
        var part_jumlah = false;
        var part_harga = false;

        var jasa_id = false;
        var jasa_harga = false;
        function checkvalidasi() {
            var jenis_array = [];
            $("input[id=jenis_pen]:checked").each(function() {
                jenis_array.push($(this).val());
            });

            if($.inArray("produk", jenis_array) !== -1 ) {
                $('#produktable').find('.penjualan_produk_id').each(function() {
                    if ($(this).val() != "") {
                        penjualan_produk_id = true;
                    }
                    else {
                        penjualan_produk_id = false;
                        return false;
                    }
                });

                $('#produktable').find('.variasi').each(function() {
                    if ($(this).val() != "") {
                        variasi = true;
                    }
                    else {
                        variasi = false;
                        return false;
                    }
                });

                $('#produktable').find('.produk_jumlah').each(function() {
                    if ($(this).val() != "") {
                        produk_jumlah = true;
                    }
                    else {
                        produk_jumlah = false;
                        return false;
                    }
                });

                $('#produktable').find('.produk_harga').each(function() {
                    if ($(this).val() != "") {
                        produk_harga = true;
                    }
                    else {
                        produk_harga = false;
                        return false;
                    }
                });
            }else if($.inArray("produk", jenis_array) === -1 ){
                penjualan_produk_id = true;
                variasi = true;
                produk_jumlah = true;
                produk_harga = true;
            }

            if($.inArray("sparepart", jenis_array) !== -1 ) {
                $('#parttable').find('.part_id').each(function() {
                    if ($(this).val() != null) {
                        part_id = true;
                        console.log("part_id: "+$(this).val());
                    }
                    else {
                        part_id = false;
                        return false;
                    }
                });

                $('#parttable').find('.part_jumlah').each(function() {
                    if ($(this).val() != "") {
                        part_jumlah = true;
                        console.log("part_jumlah: "+$(this).val());
                    }
                    else {
                        part_jumlah = false;
                        return false;
                    }
                });

                $('#parttable').find('.part_harga').each(function() {
                    if ($(this).val() != "") {
                        part_harga = true;
                        console.log("part_harga: "+$(this).val());
                    }
                    else {
                        part_harga = false;
                        return false;
                    }
                });
            }else if($.inArray("sparepart", jenis_array) === -1 ){
                part_id = true;
                part_jumlah = true;
                part_harga = true;
            }

            if($.inArray("jasa", jenis_array) !== -1 ) {
                $('#jasatable').find('.jasa_id').each(function() {
                    if ($(this).val() != null) {
                        jasa_id = true;
                    }
                    else {
                        jasa_id = false;
                        return false;
                    }
                });

                $('#jasatable').find('.jasa_harga').each(function() {
                    if ($(this).val() != "") {
                        jasa_harga = true;
                    }
                    else {
                        jasa_harga = false;
                        return false;
                    }
                });
            }else if($.inArray("jasa", jenis_array) === -1 ){
                jasa_id = true;
                jasa_harga = true;
            }

            if ($('input[type="radio"][name="do"]:checked').val() == "yes") {
                if ($('#customer_id').val() != "" && $("#no_do").val() != "" && $("#tanggal_do").val() != "" && penjualan_produk_id == true && variasi == true && produk_jumlah == true && produk_harga == true && part_id == true && part_jumlah == true && part_harga == true && jasa_id == true && jasa_harga == true) {
                    $('#btnsimpan').removeAttr("disabled");
                } else {
                    $('#btnsimpan').attr("disabled", true);
                }
            } else if ($('input[type="radio"][name="do"]:checked').val() == "no") {
                if ($('#customer_id').val() != "" && penjualan_produk_id == true && variasi == true && produk_jumlah == true && produk_harga == true && part_id == true && part_jumlah == true && part_harga == true && jasa_id == true && jasa_harga == true) {
                    $('#btnsimpan').removeAttr("disabled");
                } else {
                    $('#btnsimpan').attr("disabled", true);
                }
            } else {
                $('#btnsimpan').attr("disabled", true);
            }
        }

        $(document).on('keyup change', '#no_do', function(){
            checkvalidasi();
        })

        $(document).on('keyup change', '#tanggal_do', function(){
            checkvalidasi();
        })

        $(".os-content-arrange").remove();
        loop();
        load_variasi();

        function loop() {
            for (i = 0; i < 20; i++) {
                select_data(i);
                load_part(i);
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

        $('#customer_id').on('keyup change', function() {
            if ($(this).val() != "") {
                $('#msgcustomer_id').text("");
                $('#customer_id').removeClass('is-invalid');
                //var value = getCustomer($(this).val());
                // $('#alamat').val(value.alamat);
                // $('#telepon').val(value.telepon);
            } else if ($(this).val() == "") {
                $('#msgcustomer_id').text("Silahkan Pilih Customer");
                $('#customer_id').addClass('is-invalid');
            }
            checkvalidasi();
        });

        // $('input[type="radio"][name="jenis_penj"]').on('change', function() {
        //     var x = $(this).val();
        //     if ($(this).val() == "produk") {
        //         $("#datapart").addClass("hide");
        //         $("#dataproduk").removeClass("hide");

        //         $('#produktable tbody').empty();
        //         $('#produktable tbody').append(trproduktable());
        //         numberRowsProduk($("#produktable"));

        //         $('#parttable tbody').empty();
        //     } else if ($(this).val() == "sparepart") {
        //         $("#datapart").removeClass("hide");
        //         $("#dataproduk").addClass("hide");

        //         $('#produktable tbody').empty();

        //         $('#parttable tbody').empty();
        //         $('#parttable tbody').append(trparttable());
        //         numberRowsPart($("#parttable"));
        //     } else if ($(this).val() == "semua") {
        //         $("#datapart").removeClass("hide");
        //         $("#dataproduk").removeClass("hide");

        //         $('#produktable tbody').empty();
        //         $('#produktable tbody').append(trproduktable());
        //         numberRowsProduk($("#produktable"));

        //         $('#parttable tbody').empty();
        //         $('#parttable tbody').append(trparttable());
        //         numberRowsPart($("#parttable"));
        //     }
        // });

        $('input[type="checkbox"][name="jenis_pen[]"]').on('change', function() {
            var jenis_arry = [];
            var x = $(this).val();


            $("input[id=jenis_pen]:checked").each(function() {
                    jenis_arry.push($(this).val());
            });
            $("input[id=jasacheck]:checked").each(function() {
                    jenis_arry.push('jasa');
            });

            if ($("input[name='jenis_pen[]']:checked").length == 0) {
                jenis_arry.push(x);
                $("input[id=jenis_pen][value="+x+"]").prop("checked", true);
                }
            filter_jenis(jenis_arry);
            checkvalidasi();
        });


            function filter_jenis(x){
              if( $.inArray("produk", x) !== -1 ) {
                $("#dataproduk").removeClass("hide");

                if ($('#c_produk').val() <= 0){
                    ($("#c_produk").val(1))
                    $('#produktable tbody').append(trproduktable());
                 numberRowsProduk($("#produktable"));
                }

                }else{
                $('#totalhargaprd').text("Rp. 0");
                ($("#c_produk").val(0))
                $('#produktable tbody').empty();
                $("#dataproduk").addClass("hide");
                }

              if( $.inArray("jasa", x) !== -1 ) {
                $("#datajasa").removeClass("hide");
                }else{
                $("#datajasa").addClass("hide");
                }

              if( $.inArray("sparepart", x) !== -1 ) {
                $("#datapart").removeClass("hide");

                if ($('#c_part').val() <= 0){
                ($("#c_part").val(1))

                $('#parttable tbody').append(trparttable());
                numberRowsPart($("#parttable"));
                }
                }else{
                    $('#totalhargapart').text("Rp. 0");
                ($("#c_part").val(0))
                $('#parttable tbody').empty();
                $("#datapart").addClass("hide");
                }
            }

        $('input[type="radio"][name="do"]').on('change', function() {
            if ($(this).val() == "yes") {
                $("#do_detail_no").removeClass("hide");
                $("#do_detail_tgl").removeClass("hide");
            } else if ($(this).val() == "no") {
                $("#do_detail_no").addClass("hide");
                $("#do_detail_tgl").addClass("hide");
            }
            checkvalidasi();
        });

        $('#no_po').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgno_po").text("");
                $("#no_po").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgno_po").text("Nomor PO Harus diisi");
                $("#no_po").addClass('is-invalid');
            }
        });

        $('#tanggal_po').on('keyup', function() {
            if ($(this).val() != "") {
                $("#msgtanggal_po").text("");
                $("#tanggal_po").removeClass('is-invalid');
            } else if ($(this).val() == "") {
                $("#msgtanggal_po").text("Tanggal PO Harus diisi");
                $("#tanggal_po").addClass('is-invalid');
            }
        });

        $('#customer_id').select2({
            placeholder: "Pilih Customer",
            ajax: {
                minimumResultsForSearch: 20,
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
                    console.log(data);
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
                    console.log(data);
                    $('#alamat_customer').val(data[0].alamat);
                    $('#telepon_customer').val(data[0].telp);
                }
            });
            checkvalidasi();
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
                    console.log(data);
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


        //PRODUK
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
                    $(el).find('select[data-attr="variasi' + k + '"]').attr('name', 'variasi[' + j + '][' + k + ']');
                    $(el).find('select[data-attr="variasi' + k + '"]').attr('id', 'variasi' + j + '' + k);
                    $(el).find('span[data-attr="ketstok' + k + '"]').attr('name', 'ketstok[' + j + '][' + k + ']');
                    $(el).find('span[data-attr="ketstok' + k + '"]').attr('id', 'ketstok' + j + '' + k);
                }
                $(el).find('.detail_produk').attr('id', 'detail_produk' + j);
                $(el).find('.produk_harga').attr('id', 'produk_harga' + j);
                $(el).find('.produk_harga').attr('name', 'produk_harga[' + j + ']');
                $(el).find('.produk_jumlah').attr('id', 'produk_jumlah' + j);
                $(el).find('.produk_jumlah').attr('name', 'produk_jumlah[' + j + ']');
                $(el).find('.produk_subtotal').attr('id', 'produk_subtotal' + j);
                $(el).find('.produk_subtotal').attr('name', 'produk_subtotal[' + j + ']');
                $(el).find('.detail_jual').attr('id', 'detail_jual' + j);
                select_data($(el).find('.penjualan_produk_id').attr('id'));
            });
        }

        $("#produktable").on('keyup change', '.penjualan_produk_id', function() {
            var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
            var harga = $(this).closest('tr').find('.produk_harga').val();
            var subtotal = $(this).closest('tr').find('.produk_subtotal');

            if (jumlah != "" && harga != "") {
                var hargacvrt = replaceAll(harga, '.', '');
                subtotal.val(formatmoney(jumlah * parseInt(hargacvrt)));
                totalhargaprd();
            } else {
                subtotal.val(formatmoney("0"));
                totalhargaprd();
            }
            checkvalidasi();
        });

        $("#produktable").on('keyup change', '.produk_jumlah', function() {
            var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
            var harga = $(this).closest('tr').find('.produk_harga').val();
            var subtotal = $(this).closest('tr').find('.produk_subtotal');
            var ketstok = $(this).closest('tr').find('.ketstok');
            var variasi = $(this).closest('tr').find('.variasi');
            var ppid = $(this).closest('tr').find('.penjualan_produk_id').attr('id');
            if (jumlah != "" && harga != "") {
                var hargacvrt = replaceAll(harga, '.', '');
                subtotal.val(formatmoney(jumlah * parseInt(hargacvrt)));
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
            checkvalidasi();
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
            checkvalidasi();
        })

        $("#produktable").on('keyup change', '.produk_harga', function() {
            var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $(this).val(result);
            var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
            var harga = $(this).closest('tr').find('.produk_harga').val();
            var subtotal = $(this).closest('tr').find('.produk_subtotal');
            if (jumlah != "" && harga != "") {
                var hargacvrt = replaceAll(harga, '.', '');
                subtotal.val(formatmoney(jumlah * parseInt(hargacvrt)));
                totalhargaprd();
            } else {
                subtotal.val(formatmoney("0"));
                totalhargaprd();
            }
            checkvalidasi();
        });

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
                            <div class="input-group-append">
                                <span class="input-group-text" id="produk_satuan">pcs</span>
                            </div>
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
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;"/>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control produk_subtotal" name="produk_subtotal[]" id="produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" readonly/>
                    </div>
                </td>
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

            checkvalidasi();
        });

        $('#produktable').on('click', '#removerowproduk', function(e) {
            $(this).closest('tr').remove();
            numberRowsProduk($("#produktable"));
            totalhargaprd();
            if ($('#produktable > tbody > tr').length <= 0) {
                $('#produktable tbody').append(trproduktable());
                numberRowsProduk($("#produktable"));
                $("#totalhargaprd").text("Rp. 0");
            }
            checkvalidasi();
        });

        function select_data(i) {
            $('#' + i).select2({
                placeholder: "Pilih Produk",
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
                                    for (var y = 0; y < res[0].produk[x].gudang_barang_jadi.length; y++) {
                                        var nama_var = "";
                                        if(res[0].produk[x].gudang_barang_jadi[y].nama != ""){
                                            nama_var = res[0].produk[x].gudang_barang_jadi[y].nama;
                                        }
                                        else {
                                            nama_var = res[0].produk[x].nama;
                                        }
                                        data.push({
                                            id: res[0].produk[x].gudang_barang_jadi[y].id,
                                            text: nama_var,
                                            jumlah: res[0].produk[x].pivot.jumlah,
                                            qt: cek_stok(res[0].produk[x].gudang_barang_jadi[y].id)
                                        });
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
                checkvalidasi();
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
                                for (var y = 0; y < res[0].produk[x].gudang_barang_jadi.length; y++) {
                                    var nama_var = "";
                                    if(res[0].produk[x].gudang_barang_jadi[y].nama != ""){
                                        nama_var = res[0].produk[x].gudang_barang_jadi[y].nama;
                                    }
                                    else {
                                        nama_var = res[0].produk[x].nama;
                                    }
                                    data.push({
                                        id: res[0].produk[x].gudang_barang_jadi[y].id,
                                        text: nama_var,
                                        jumlah: res[0].produk[x].pivot.jumlah,
                                        qt: cek_stok(res[0].produk[x].gudang_barang_jadi[y].id)
                                    });
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

        //PART
        function load_part(i) {
            $('#part_id' + i).select2({
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

        function totalhargapart() {
            var totalharga = 0;
            $('#parttable').find('tr .part_subtotal').each(function() {
                var subtotal = replaceAll($(this).val(), '.', '');
                totalharga = parseInt(totalharga) + parseInt(subtotal);
                $("#totalhargapart").text("Rp. " + totalharga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
            })
        }

        $("#parttable").on('keyup change', '.part_id', function() {
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

            checkvalidasi();
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
            checkvalidasi();
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
                load_part(j);
            });
        }

        function trparttable() {
            var data = `<tr>
                <td>1</td>
                <td>
                    <div class="form-group select_item">
                        <select class="select2 form-control select-info custom-select part_id" name="part_id[]" id="part_id0" width="100%">
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <div class="input-group">
                            <input type="number" class="form-control part_jumlah" aria-label="produk_satuan" name="part_jumlah[]" id="part_jumlah0" style="width:100%;">
                            <div class="input-group-append">
                                <span class="input-group-text" id="part_satuan">pcs</span>
                            </div>
                        </div>
                        <small id="part_ketersediaan"></small>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control part_harga" name="part_harga[]" id="part_harga0" placeholder="Masukkan Harga" style="width:100%;" />
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="text" class="form-control part_subtotal" name="part_subtotal[]" id="part_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" readonly />
                    </div>
                </td>
                <td>
                    <a id="removerowpart"><i class="fas fa-minus" style="color: red"></i></a>
                </td>
            </tr>`;
            return data;
        }

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
            checkvalidasi();
        });

        $('#addrowpart').on('click', function() {
            if ($('#parttable > tbody > tr').length <= 0) {
                $('#parttable tbody').append(trparttable());
                numberRowsPart($("#parttable"));
            } else {
                $('#parttable tbody tr:last').after(trparttable());
                numberRowsPart($("#parttable"));
            }
            checkvalidasi();
        });

        $('#parttable').on('click', '#removerowpart', function(e) {
            $(this).closest('tr').remove();
            numberRowsPart($("#parttable"));
            totalhargapart();
            if ($('#parttable > tbody > tr').length <= 0) {
                // $('#totalhargapart').text("Rp. 0");
                // ($("#c_part").val(0))
                // $('#parttable tbody').empty();
                // $("#datapart").addClass("hide");
                // $("input[id=jenis_pen][value='sparepart']").prop("checked", false);

                $('#parttable tbody').append(trparttable());
                numberRowsPart($("#parttable"));
                $("#totalhargapart").text("Rp. 0");
            }
            checkvalidasi();
        });
    });
</script>
@stop
