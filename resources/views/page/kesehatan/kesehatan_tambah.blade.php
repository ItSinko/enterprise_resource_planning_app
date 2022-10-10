@extends('adminlte.page')
@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-lg-6 col-md-4 col-sm-4">
            <h1 class="m-0  text-dark">Tambah Kesehatan Awal</h1>
        </div><!-- /.col -->
        <div class="col-lg-6 col-md-8 col-sm-8">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('kesehatan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="\kesehatan">Kesehatan Awal</a></li>
                <li class="breadcrumb-item active">Tambah Kesehatan</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
<style>
    .hide{
        display: none !important
    }
    .removeboxshadow {
        box-shadow: none;
        border: 1px;
    }

    .bg-color{
        background-color: #e8fafc;
    }

    @media screen and (min-width: 993px) {
        .labelket {
            text-align: right;
        }

        section {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }
    }

    @media screen and (max-width: 992px) {
        .labelket {
            text-align: left;
        }

        section {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }
    }
    div.ui-tooltip {
    max-width: 400px;
}
</style>
@stop
@section('content')
<section class="content-header">
    <div class="container-fluid">
    </div>
</section>

<section class="content">
    <div class="row">
            <div class="col-lg-12">
                @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{session()->get('success')}}
                </div>
                @elseif(session()->has('error') || count($errors) > 0)
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    Data gagal ditambahkan
                </div>
                @endif
            </div>
            <div class="col-lg-12">
                <form action="/kesehatan/store" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h6 class="card-title">Kondisi Umum</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label for="no_pemeriksaan" class="col-sm-5 col-form-label" style="text-align:right;">Nama</label>
                                            <div class="col-sm-3">
                                                <select type="text" class="form-control col-form-label @error('karyawan_id') is-invalid @enderror select2" name="karyawan_id">

                                                    @foreach($karyawan as $k)
                                                    <option value="{{$k->id}}">{{$k->nama}}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('karyawan_id'))
                                                <div class="text-danger">
                                                    {{ $errors->first('karyawan_id')}}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="keterangan" class="col-sm-5 col-form-label" style="text-align:right;">Berat Badan</label>
                                            <div class="col-sm-2">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="berat" id="berat" value="{{ old('berat') }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Kg</span>
                                                    </div>
                                                </div>
                                                @if($errors->has('berat'))
                                                <div class="text-danger">
                                                    {{ $errors->first('berat')}}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="keterangan" class="col-sm-5 col-form-label" style="text-align:right;">Tinggi Badan</label>
                                            <div class="col-sm-2">
                                                <div class="input-group">
                                                    <input type="number" class="form-control" name="tinggi" id="tinggi" value="{{ old('tinggi') }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">Cm</span>
                                                    </div>
                                                </div>
                                                @if($errors->has('tinggi'))
                                                <div class="text-danger">
                                                    {{ $errors->first('tinggi')}}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label" style="text-align:right;">Body Mass Index</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control col-form-label @error('data') is-invalid @enderror " id="bmi" readonly>
                                                <small id="status_bmi" class="form-text text-muted"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h6 class="card-title">Kondisi Mata</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="keterangan" class="col-sm-5 col-form-label" style="text-align:right;">Nilai Tes Warna</label>
                                <div class="col-sm-2">
                                    <input type="number" class="form-control" name="buta_warna" id="buta_warna" value="{{ old('buta_warna') }}" placeholder="Jumlah yang dibaca">
                                    <input type="text" class="form-control d-none" name="status_mata" id="status_mata">
                                    @if($errors->has('buta_warna'))
                                    <div class="text-danger">
                                        {{ $errors->first('buta_warna')}}
                                    </div>
                                    @endif
                                    <small id="status_butawarna" class="form-text text-muted"></small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label" style="text-align:right;">Rabun Mata</label>
                                <div class="col-sm-3">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="mata_kiri" placeholder="Mata Kiri" name="mata_kiri">
                                            <small id="mata_kiri_status" class="form-text text-muted"></small>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="mata_kanan" placeholder="Mata Kanan" name="mata_kanan">
                                            <small id="mata_kanan_status" class="form-text text-muted"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h6 class="card-title">Kondisi Tubuh</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="keterangan" class="col-sm-5 col-form-label" style="text-align:right;">Suhu</label>
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="suhu" id="suhu" value="{{ old('suhu') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">°C</span>
                                        </div>
                                    </div>
                                    @if($errors->has('suhu'))
                                    <div class="text-danger">
                                        {{ $errors->first('suhu')}}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keterangan" class="col-sm-5 col-form-label" style="text-align:right;">Saturasi Oksigen Darah</label>
                                <div class="col-sm-3">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="spo2" id="spo2" value="{{ old('spo2') }}" placeholder="SPO2">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            @if($errors->has('spo2'))
                                            <div class="text-danger">
                                                {{ $errors->first('spo2')}}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-sm-6">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="pr" id="pr" value="{{ old('pr') }}" placeholder="PR">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">bpm</span>
                                                    </div>
                                                </div>
                                                @if($errors->has('pr'))
                                                <div class="text-danger">
                                                    {{ $errors->first('pr')}}
                                                </div>
                                                @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keterangan" class="col-sm-5 col-form-label" style="text-align:right;">Tekanan Darah</label>
                                <div class="col-sm-3">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="sistolik" id="sistolik" value="{{ old('sistolik') }}" placeholder="Systol">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">mmHg</span>
                                                </div>
                                            </div>
                                            @if($errors->has('sistolik'))
                                            <div class="text-danger">
                                                {{ $errors->first('sistolik')}}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="diastolik" id="diastolik" value="{{ old('diastolik') }}" placeholder="Dyastol">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">mmHg</span>
                                                </div>
                                            </div>
                                            @if($errors->has('diastolik'))
                                            <div class="text-danger">
                                                {{ $errors->first('diastolik')}}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kondisi" class="col-sm-5 col-form-label" style="text-align:right;">Komposisi tubuh</label>
                                <div class="col-sm-7 col-form-label">
                                    <div class="icheck-success d-inline col-sm-4">
                                        <input type="radio" name="status_komposisi_tubuh" value="0" checked="0">
                                        <label for="no">
                                            Tidak Ada
                                        </label>
                                    </div>
                                    <div class="icheck-warning d-inline col-sm-4">
                                        <input type="radio" name="status_komposisi_tubuh" value="1">
                                        <label for="sample">
                                            Ada
                                        </label>
                                    </div>
                                    <span class="invalid-feedback" role="alert" id="kondisi-msg"></span>
                                </div>
                            </div>

                            <div class="card" id="detail_komposisi" style="display:none">
                                <div class="card-header">
                                    <div class="card-title">&nbsp;Komposisi Tubuh</div>
                                </div>
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <table id="tabel" class="table table-hover styled-table table-striped">
                                                    <thead style="text-align: center;">
                                                        <tr>
                                                            <th>Fat</th>
                                                            <th>Tbw</th>
                                                            <th>Muscle</th>
                                                            <th>Bone</th>
                                                            <th>Kalori</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody style="text-align: center;">
                                                        <tr>
                                                            <td>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" class="form-control" name="lemak" id="lemak" value="{{ old('lemak') }}">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">gram</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" class="form-control" name="kandungan_air" id="kandungan_air" value="{{ old('kandungan_air') }}">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">%</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" class="form-control" name="otot" id="otot" value="{{ old('otot') }}">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Kg</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" class="form-control" name="tulang" id="tulang" value="{{ old('tulang') }}">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">Kg</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="input-group mb-3">
                                                                    <input type="text" class="form-control" name="kalori" id="kalori" value="{{ old('kalori') }}">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text">kkal</span>
                                                                    </div>
                                                                </div>
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
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h6 class="card-title">Penyakit yang diderita</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="tanggal" class="col-sm-5 col-form-label" style="text-align:right;">Hasil Medical Check Up</label>
                                <div class="col-sm-4">
                                    <input type="file" class="form-control @error('file_mcu') is-invalid @enderror" name="file_mcu" style="width:45%;">
                                    @if($errors->has('file_mcu'))
                                    <div class="text-danger">
                                        {{ $errors->first('file_mcu')}}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kondisi" class="col-sm-5 col-form-label" style="text-align:right;">Penyakit Bawaan</label>
                                <div class="col-sm-7 col-form-label">
                                    <div class="icheck-success d-inline col-sm-4">
                                        <input type="radio" name="riwayat_penyakit" value="Iya">
                                        <label for="no">
                                            Iya
                                        </label>
                                    </div>
                                    <div class="icheck-warning d-inline col-sm-4">
                                        <input type="radio" name="riwayat_penyakit" value="Tidak" checked="0">
                                        <label for="sample">
                                            Tidak
                                        </label>
                                    </div>
                                    <span class="invalid-feedback" role="alert" id="kondisi-msg"></span>
                                </div>
                            </div>
                            <div class="form-group" id="penyakit_ket" hidden="hidden">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-sm-10">
                                        <div class="card">
                                            <div class="card-body">
                                                <table class="table table-hover" style="text-align: center;" id="tabel_penyakit">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama Penyakit</th>
                                                            <th width="25%">Jenis Penyakit</th>
                                                            <th>Keterangan</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>
                                                                <input type="text" class="form-control d-none" name="fk_karyawan_id" id="id">
                                                                <textarea type="text" class="form-control nama" name="nama[]"></textarea>
                                                            </td>
                                                            <td>
                                                                <select class="form-control select2 jenis" style="width:100%" name="jenis[]">
                                                                    <option value="">Pilih jenis penyakit</option>
                                                                    <option value="Penyakit saat ini">Penyakit saat ini</option>
                                                                    <option value="Penyakit lama">Penyakit lama</option>
                                                                    <option value="Penyakit keluarga">Penyakit keluarga</option>
                                                                    <option value="Penyakit karena pekerjaan">Penyakit karena pekerjaan</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="kriteria[]" id="kriteria" value="1">
                                                                    <label class="form-check-label">Menular</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="kriteria[]" id="kriteria" value="0">
                                                                    <label class="form-check-label">Tidak menular</label>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <textarea type="text" class="form-control keterangan" name="keterangan[]" id="keterangan"></textarea>
                                                            </td>
                                                            <td>
                                                                <button name="add" type="button" id="tambahitem_penyakit" class="btn btn-success"><i class="nav-icon fas fa-plus-circle"></i></button>
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
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h6 class="card-title">COVID-19</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="kondisi" class="col-sm-5 col-form-label" style="text-align:right;">Vaksin</label>
                                        <div class="col-sm-7 col-form-label" >
                                            <div class="icheck-success d-inline col-sm-4">
                                                <input type="radio" name="status_vaksin" value="Belum" checked="0">
                                                <label for="no">
                                                    Belum
                                                </label>
                                            </div>
                                            <div class="icheck-warning d-inline col-sm-4">
                                                <input type="radio" name="status_vaksin" value="Sudah">
                                                <label for="sample">
                                                    Sudah
                                                </label>
                                            </div>
                                            <span class="invalid-feedback" role="alert" id="kondisi-msg"></span>
                                        </div>
                                    </div>
                                    <div hidden="hidden" class="form-group" id="vaksin_ket">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-sm-8">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <table id="tabel_vaksin" class="table table-hover styled-table"  style="text-align: center;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Tgl Vaksin</th>
                                                                    <th>Dosis</th>
                                                                    <th>Vaksin ke </th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>
                                                                        <input type="date" class="form-control date" name="date[]">
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control select2 dosis" name="dosis[]" placeholder="Pilih Dosis">
                                                                            <option value="Astrazeneca">Astrazeneca</option>
                                                                            <option value="Janssen">Janssen</option>
                                                                            <option value="Moderna">Moderna</option>
                                                                            <option value="Pfizer">Pfizer</option>
                                                                            <option value="Sinovac">Sinovac</option>
                                                                            <option value="Sinopharm">Sinopharm</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control select2 ket" name="ket[]"  placeholder="Pilih Vaksin">
                                                                            <option value="1">1</option>
                                                                            <option value="2">2</option>
                                                                            <option value="3">3</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <button name="add" type="button" id="tambahitem_vaksin" class="btn btn-success"><i class="nav-icon fas fa-plus-circle"></i></button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span role="alert" id="no_seri-msg"></span>
                                    </div>

                                    <div class="form-group row">
                                        <label for="kondisi" class="col-sm-5 col-form-label" style="text-align:right;">Pemeriksaan Covid</label>
                                        <div class="col-sm-7 col-form-label">
                                            <div class="icheck-success d-inline col-sm-6">
                                                <input type="radio" name="status_tes" value="Iya">
                                                <label for="no">
                                                    Iya
                                                </label>
                                            </div>
                                            <div class="icheck-warning d-inline col-sm-4">
                                                <input type="radio" name="status_tes" value="Tidak" checked="0">
                                                <label for="sample">
                                                    Tidak
                                                </label>
                                            </div>
                                            <span class="invalid-feedback" role="alert" id="kondisi-msg"></span>
                                        </div>
                                    </div>
                                    <div class="form-group" id="tes_ket" hidden="hidden">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-sm-10">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table id="tabel_tes" class="table table-hover styled-table table-striped"  style="text-align: center;">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="1%">No</th>
                                                                        <th width="20%">Jenis Tes</th>
                                                                        <th width="20%">Pemeriksa</th>
                                                                        <th width="20%">Tanggal</th>
                                                                        <th width="25%"></th>
                                                                        <th width="20%">Catatan</th>
                                                                        <th width="1%"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody style="text-align: center;">
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>
                                                                            <select class="form-control select2 jenis_tes" name="jenis_tes[]" id="0">
                                                                                <option value="">Pilih</option>
                                                                                <option value="Rapid">Rapid</option>
                                                                                <option value="Antigen">Antigen</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select type="text" class="form-control @error('pemeriksa_id') is-invalid @enderror pemeriksa_id select2 select2-info" name="pemeriksa_id[]" style="width:100%;" id="pemeriksa_id[]">

                                                                                @foreach ($pengecek as $p)
                                                                                <option value="{{$p->id}}">{{$p->nama}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td><input type="date" name="tgl_cek[]" class="form-control tgl_cek"></td>
                                                                        <td>
                                                                            <div id="rapids0" class="row rapids" hidden>
                                                                                <div class="icheck-success d-inline col-sm-6">
                                                                                    <input type="radio" name="hasil_covid[]" value="Non reaktif" class="hasil_covid">
                                                                                    <label for="no">
                                                                                        Non reaktif
                                                                                    </label>
                                                                                </div>
                                                                                <div class="icheck-success d-inline col-sm-6">
                                                                                    <input type="radio" name="hasil_covid[]" value="IgG" class="hasil_covid">
                                                                                    <label for="no">
                                                                                        IgG
                                                                                    </label>
                                                                                </div>
                                                                                <div class="icheck-warning d-inline col-sm-6">
                                                                                    <input type="radio" name="hasil_covid[]" value="IgM" class="hasil_covid">
                                                                                    <label for="sample">
                                                                                        IgM
                                                                                    </label>
                                                                                </div>
                                                                                <div class="icheck-warning d-inline col-sm-6">
                                                                                    <input type="radio" name="hasil_covid[]" value="IgG-IgM" class="hasil_covid">
                                                                                    <label for="sample">
                                                                                        IgG-IgM
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                            <div id="antigens0" class="row antigens" hidden>
                                                                                <div class="icheck-success d-inline col-sm-12">
                                                                                    <input type="radio" name="hasil_covid[]" value="C" class="hasil_covid">
                                                                                    <label for="no">
                                                                                        C
                                                                                    </label>
                                                                                </div>
                                                                                <div class="icheck-warning d-inline col-sm-12">
                                                                                    <input type="radio" name="hasil_covid[]" value="C/T" class="hasil_covid">
                                                                                    <label for="sample">
                                                                                        C/T
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <textarea class="form-control keterangan" name="keterangan[]"></textarea>
                                                                        </td>
                                                                        <td style="text-align: right;">
                                                                            <button name="add" type="button" id="tambahitem_tes" class="btn btn-success"><i class="nav-icon fas fa-plus-circle"></i></button>
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
                    </div>
                    <div class="row mb-3">
                        <div class="col-6"><a class="btn btn-danger float-left" href="/kesehatan">Batal</a></div>
                        <div class="col-6"><button class="btn btn-primary float-right" id="button_tambah">Simpan</button></div>
                    </div>
                </form>
            </div>

    </div>
</section>
@endsection
@section('adminlte_js')
<script>
    $(document).ready(function() {
        $('.select2').select2();
        function numberRow_vaksin($t) {
            var c = 0 - 1;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('.dosis').attr('name', 'dosis[' + j + ']');
                $(el).find('.date').attr('name', 'date[' + j + ']');
                $(el).find('.ket').attr('name', 'ket[' + j + ']');
                $('.dosis').select2();
                $('.ket').select2();
            });
        }

        function numberRow_tes($t) {
            var c = 0 - 1;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('.jenis_tes').attr('id', '' + j + '');
                $(el).find('.antigens').attr('id', 'antigens' + j + '');
                $(el).find('.rapids').attr('id', 'rapids' + j + '');
                $(el).find('.pemeriksa_id').attr('id', 'pemeriksa_id[' + j + ']');

                $(el).find('.jenis_tes').attr('name', 'jenis_tes[' + j + ']');
                $(el).find('.pemeriksa_id').attr('name', 'pemeriksa_id[' + j + ']');
                $(el).find('.tgl_cek').attr('name', 'tgl_cek[' + j + ']');
                $(el).find('.hasil_covid').attr('name', 'hasil_covid[' + j + ']');
                $(el).find('.keterangan').attr('name', 'keterangan[' + j + ']');
                $('.jenis_tes').select2();
                $('.pemeriksa_id').select2();
            });
        }
        $('input[type=radio][name=status_vaksin]').on('change', function() {
            if (this.value == 'Sudah') {
                $('#vaksin_ket').removeAttr('hidden');
            } else if (this.value == 'Belum') {
                $('#vaksin_ket').attr('hidden', 'hidden');
            }
        });
        $('input[type=radio][name=status_tes]').on('change', function() {
            if (this.value == 'Iya') {
                $('#tes_ket').removeAttr('hidden');
            } else if (this.value == 'Tidak') {
                $('#tes_ket').attr('hidden', 'hidden');
            }
        });

        $('input[type=radio][name=riwayat_penyakit]').on('change', function() {
            if (this.value == 'Iya') {
                $('#penyakit_ket').removeAttr('hidden');
            } else if (this.value == 'Tidak') {
                $('#penyakit_ket').attr('hidden', 'hidden');
            }
        });


        $('#tambahitem_vaksin').click(function(e) {
            var data = `  <tr>
            <td>1</td>
                                                                <td>
                                                                <input type="date" class="form-control date" name="date[]">
                                                                </td>
                                                                <td>
                                                                    <select class="form-control select2 dosis" name="dosis[]" placeholder="Pilih Dosis">
                                                                        <option value="Astrazeneca">Astrazeneca</option>
                                                                        <option value="Sinovac">Sinovac</option>
                                                                        <option value="Moderna">Moderna</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control select2 ket" name="ket[]" placeholder="Pilih Vaksin">
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                <button type="button" class="btn btn-danger karyawan-img-small" style="border-radius:50%;" id="closetable_vaksin"><i class="fas fa-times-circle"></i></button>
                                                   </td>
                                                </tr>`;
            $('#tabel_vaksin tr:last').after(data);
            numberRow_vaksin($("#tabel_vaksin"));
        });
        $('#tabel_vaksin').on('click', '#closetable_vaksin', function(e) {
            $(this).closest('tr').remove();
            numberRow_vaksin($("#tabel_vaksin"));
        });
        $('#tambahitem_tes').click(function(e) {
            var data = `  <tr>
            <td>1</td>
                                                            <td>
                                                                <select class="form-control select2 jenis_tes" name="jenis_tes[]">
                                                                    <option value="">Pilih</option>
                                                                    <option value="Rapid">Rapid</option>
                                                                    <option value="Antigen">Antigen</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select type="text" class="form-control @error('pemeriksa_id') is-invalid @enderror pemeriksa_id select2 select2-info" name="pemeriksa_id[]" style="width:100%;" id="pemeriksa_id[]">

                                                                    @foreach ($pengecek as $p)
                                                                    <option value="{{$p->id}}">{{$p->nama}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="date" name="tgl_cek[]" class="form-control tgl_cek"></td>
                                                            <td>
                                                            <div id="rapids" class="row rapids" hidden>
                                                            <div class="icheck-success d-inline col-sm-6">
                                                                <input type="radio" name="hasil_covid[]" value="Non reaktif" class="hasil_covid">
                                                                <label for="no">
                                                                    Non reaktif
                                                                </label>
                                                            </div>
                                                            <div class="icheck-success d-inline col-sm-6">
                                                                <input type="radio" name="hasil_covid[]" value="IgG" class="hasil_covid">
                                                                <label for="no">
                                                                    IgG
                                                                </label>
                                                            </div>
                                                            <div class="icheck-warning d-inline col-sm-6">
                                                                <input type="radio" name="hasil_covid[]" value="IgM" class="hasil_covid">
                                                                <label for="sample">
                                                                    IgM
                                                                </label>
                                                            </div>
                                                            <div class="icheck-warning d-inline col-sm-6">
                                                                <input type="radio" name="hasil_covid[]" value="IgG-IgM" class="hasil_covid">
                                                                <label for="sample">
                                                                    IgG-IgM
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div id="antigens" class="row antigens" hidden>
                                                            <div class="icheck-success d-inline col-sm-12">
                                                                <input type="radio" name="hasil_covid[]" value="C" class="hasil_covid">
                                                                <label for="no">
                                                                    C
                                                                </label>
                                                            </div>
                                                            <div class="icheck-warning d-inline col-sm-12">
                                                                <input type="radio" name="hasil_covid[]" value="C/T" class="hasil_covid" >
                                                                <label for="sample">
                                                                    C/T
                                                                </label>
                                                            </div>
                                                        </div>
                                                            </td>
                                                            <td>
                                                                <textarea class="form-control keterangan" name="keterangan[]"></textarea>
                                                            </td>
                                                                <td>
                                                                <button type="button" class="btn btn-danger karyawan-img-small" style="border-radius:50%;" id="closetable_tes"><i class="fas fa-times-circle"></i></button>
                                                   </td>
                                                </tr>`;
            $('#tabel_tes tr:last').after(data);
            numberRow_tes($("#tabel_tes"));
        });
        $('#tabel_tes').on('click', '#closetable_tes', function(e) {
            $(this).closest('tr').remove();
            numberRow_tes($("#tabel_tes"));
        });
        $('#tabel_tes').on("change", ".jenis_tes", function() {
            var x = $(this).closest('tr').find('.jenis_tes').val();
            var y = this.id;

            if (x == "Antigen") {
                $('#antigens' + y + '').removeAttr('hidden');
                $('#rapids' + y + '').attr('hidden', 'hidden');
            } else if (x == "Rapid") {
                $('#rapids' + y + '').removeAttr('hidden');
                $('#antigens' + y + '').attr('hidden', 'hidden');
            }
            console.log(y);
        });


        function numberRow_penyakit($t) {
            var c = 0 - 1;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('.nama').attr('name', 'nama[' + j + ']');
                $(el).find('.jenis').attr('name', 'jenis[' + j + ']');
                $(el).find('.keterangan').attr('name', 'keterangan[' + j + ']');
                $(el).find('input[type="radio"]').attr('name', 'kriteria[' + j + ']');
                $('.jenis').select2();
            });
        }

        $('#tambahitem_penyakit').click(function(e) {
            var data = `  <tr>
      <td>1</td>
                                    <td>
                                      <input type="text" class="form-control d-none" name="id" id="id">
                                      <textarea type="text" class="form-control nama" name="nama[]"></textarea>
                                    </td>
                                    <td>
                                      <select class="form-control select2 jenis" style="width:100%" name="jenis[]">
                                        <option value="">Pilih jenis penyakit</option>
                                        <option value="Penyakit saat ini">Penyakit saat ini</option>
                                        <option value="Penyakit lama">Penyakit lama</option>
                                        <option value="Penyakit keluarga">Penyakit keluarga</option>
                                        <option value="Penyakit karena pekerjaan">Penyakit karena pekerjaan</option>
                                      </select>
                                    </td>
                                    <td>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="kriteria[]" id="kriteria" value="1">
                                        <label class="form-check-label">Menular</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="kriteria[]" id="kriteroa" value="0">
                                        <label class="form-check-label">Tidak menular</label>
                                      </div>
                                    </td>
                                    <td>
                                      <textarea type="text" class="form-control keterangan" name="keterangan[]" id="keterangan"></textarea>
                                    </td>
                                    <td>
                                    <button type="button" class="btn btn-danger karyawan-img-small" style="border-radius:50%;" id="closetable_penyakit"><i class="fas fa-times-circle"></i></button>
                                                  </td>
                                                </tr>`;
            $('#tabel_penyakit tr:last').after(data);
            numberRow_penyakit($("#tabel_penyakit"));
        });
        $('#tabel_penyakit').on('click', '#closetable_penyakit', function(e) {
            $(this).closest('tr').remove();
            numberRow_vaksin($("#tabel_penyakit"));
        });

        $('#cek_form').hide();
        $('#tipe_1').show();
        $('#tipe_2').hide();
        $('#tipe_3').hide();

        $(function() {
            $('#buta_warna').keyup(function() {
                var value1 = parseFloat($('#buta_warna').val());
                if (value1 >= 1 && value1 <= 7) {
                    $('#status_butawarna').text('Defisensi');
                    $('input[name=status_mata]').val('Defisensi');
                } else if (value1 >= 8 && value1 <= 9) {
                    $('#status_butawarna').text('Abnormal');
                    $('input[name=status_mata]').val('Abnormal');
                } else if (value1 >= 10 && value1 <= 14) {
                    $('#status_butawarna').text('Normal');
                    $('input[name=status_mata]').val('Normal');
                }
            });
        });

        $('input[type=radio][name=tes_covid]').on('change', function() {
            if (this.value == 'Antibody') {
                $('#tipe_1').show();
                $('#tipe_2').hide();
                $('#tipe_3').hide();
                $('input[name=hasil_covid]').prop("required", true);
                $('input[name=hasil_covid]').prop("checked", false);
            } else if (this.value == 'Antigen') {
                $('#tipe_2').show();
                $('#tipe_1').hide();
                $('#tipe_3').hide();
                $('input[name=hasil_covid]').prop("required", true);
                $('input[name=hasil_covid]').prop("checked", false);
            } else if (this.value == 'Saliva') {
                $('#tipe_2').show();
                $('#tipe_1').hide();
                $('#tipe_3').hide();
                $('input[name=hasil_covid]').prop("required", true);
                $('input[name=hasil_covid]').prop("checked", false);
            } else if (this.value == 'Genose / PCR') {
                $('#tipe_3').show();
                $('#tipe_1').hide();
                $('#tipe_2').hide();
                $('input[name=hasil_covid]').prop("required", true);
                $('input[name=hasil_covid]').prop("checked", false);
            } else {

            }
        });


        $('input[type=radio][name=status_komposisi_tubuh]').on('change', function() {
            if (this.value == 1) {
                $('#lemak').val('');
                $('#kandungan_air').val('');
                $('#otot').val('');
                $('#tulang').val('');
                $('#kalori').val('');
                $('input[id=lemak]').prop("required", true);
                $('input[id=kandungan_air]').prop("required", true);
                $('input[id=otot]').prop("required", true);
                $('input[id=tulang]').prop("required", true);
                $('input[id=kalori]').prop("required", true);
                $("#detail_komposisi").removeAttr("style");
            } else {
                $('#lemak').val('');
                $('#kandungan_air').val('');
                $('#otot').val('');
                $('#tulang').val('');
                $('#kalori').val('');
                $('input[id=lemak]').prop("required", false);
                $('input[id=kandungan_air]').prop("required", false);
                $('input[id=otot]').prop("required", false);
                $('input[id=tulang]').prop("required", false);
                $('input[id=kalori]').prop("required", false);
                $("#detail_komposisi").css('display', 'none');
            }
        });

        $(function() {
            $('#berat, #tinggi').keyup(function() {
                var value1 = parseFloat($('#berat').val()) || 0;
                var value2 = parseFloat($('#tinggi').val()) || 0;
                var sum = value1 / ((value2 / 100) * (value2 / 100))
                $('#bmi').val(sum.toFixed(2));
                if (sum >= 30) {
                    $('#status_bmi').text('Kegemukan (Obesitas)');
                } else if (sum >= 25 || sum >= 29.9) {
                    $('#status_bmi').text('Kelebihan Berat Badan');
                } else if (sum >= 18.5 || sum >= 24.9) {
                    $('#status_bmi').text('Normal (Ideal)');
                } else {
                    $('#status_bmi').text('Kekurangan Berat Badan');
                }
            });
        });

        $(function() {
            $('#mata_kiri').keyup(function() {
                var value1 = parseFloat($('#mata_kiri').val());
                if (value1 <= 6) {
                    $('#mata_kiri_status').text('Tidak Normal');
                } else {
                    $('#mata_kiri_status').text('Normal');
                }
            });
        });
        $(function() {
            $('#mata_kanan').keyup(function() {
                var value1 = parseFloat($('#mata_kanan').val());
                if (value1 <= 6) {
                    $('#mata_kanan_status').text('Tidak Normal');
                } else {
                    $('#mata_kanan_status').text('Normal');
                }
            });
        });
    });
</script>
@stop
