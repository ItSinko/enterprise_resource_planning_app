@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-lg-6 col-md-4 col-sm-4">
            <h1 class="m-0  text-dark">Tambah Karyawan</h1>
        </div><!-- /.col -->
        <div class="col-lg-6 col-md-8 col-sm-8">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('kesehatan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="\karyawan">Karyawan</a></li>
                <li class="breadcrumb-item active">Tambah Karyawan</li>
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
<section class="content">
    <div class="container-fluid">
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
                <form action="/karyawan/store" method="post" >
                {{csrf_field()}}
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h5 class="card-title">Biodata Karyawan</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-horizontal">
                            <div class="form-group row">
                                <label for="no_pemeriksaan" class="col-sm-5 col-form-label" style="text-align:right;">Nama Karyawan</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" id="nama" style="width:45%;" placeholder="Masukkan Nama Karyawan" value="{{ old('nama') }}">
                                    @if($errors->has('nama'))
                                    <div class="text-danger">
                                        {{ $errors->first('nama')}}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_pemeriksaan" class="col-sm-5 col-form-label" style="text-align:right;">Tanggal Lahir</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control @error('nama') is-invalid @enderror" name="tgllahir" style="width:30%;" placeholder="Masukkan Nama Karyawan" value="{{ old('tgllahir') }}">
                                    @if($errors->has('tgllahir'))
                                    <div class="text-danger">
                                        {{ $errors->first('tgllahir')}}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kondisi" class="col-sm-5 col-form-label" style="text-align:right;">Jenis Kelamin</label>
                                <div class="col-sm-7" style="margin-top:7px;">
                                    <div class="icheck-success d-inline col-sm-5">
                                        <input type="radio" name="jenis" value="L" checked="0">
                                        <label for="no">
                                            Laki laki
                                        </label>
                                    </div>
                                    <div class="icheck-warning d-inline col-sm-5">
                                        <input type="radio" name="jenis" value="P">
                                        <label for="sample">
                                            Perempuan
                                        </label>
                                    </div>
                                    <span class="invalid-feedback" role="alert" id="kondisi-msg"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h5 class="card-title">Informasi Pekerjaan</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-horizontal">
                            <div class="form-group row">
                                <label for="no_pemeriksaan" class="col-sm-5 col-form-label" style="text-align:right;">Tgl Masuk</label>
                                <div class="col-sm-7">
                                    <input type="date" class="form-control @error('tgl_kerja') is-invalid @enderror" name="tgl_kerja" style="width:30%;" placeholder="Masukkan Nama Karyawan" value="{{ old('tgl_kerja') }}">
                                    @if($errors->has('tgl_kerja'))
                                    <div class="text-danger">
                                        {{ $errors->first('tgl_kerja')}}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_pemeriksaan" class="col-sm-5 col-form-label" style="text-align:right;">Divisi</label>
                                <div class="col-sm-7">
                                    <select type="text" class="form-control @error('divisi_id') is-invalid @enderror select2" name="divisi_id" style="width:45%;" placeholder="Masukkan Nama Karyawan" value="{{ old('divisi_id') }}">
                                        <option value="">Pilih Divisi</option>
                                        @foreach($divisi as $d)
                                        <option value="{{$d->id}}">{{$d->nama}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('divisi_id'))
                                    <div class="text-danger">
                                        {{ $errors->first('divisi_id')}}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="no_pemeriksaan" class="col-sm-5 col-form-label" style="text-align:right;">Jabatan</label>
                                <div class="col-sm-7">
                                    <select type="text" class="form-control @error('jabatan') is-invalid @enderror select2" name="jabatan" style="width:45%;" placeholder="Masukkan Nama Karyawan" value="{{ old('jabatan') }}">
                                        <option value="">Pilih Jabatan</option>
                                        <option value="direktur">Direktur</option>
                                        <option value="manager">Manager</option>
                                        <option value="assisten manager">Ass Manager</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="staff">Staff</option>
                                        <option value="operator">Operator</option>
                                        <option value="harian">Harian</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    @if($errors->has('jabatan'))
                                    <div class="text-danger">
                                        {{ $errors->first('jabatan')}}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <span class="float-left"><a class="btn btn-danger" href="/karyawan">Batal</a></span>
                        <span class="float-right"><button class="btn btn-primary" id="button_tambah">Simpan</button></span>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('adminlte_js')
<script>
    $(document).ready(function() {
        $('#nama').keyup(function() {
            var nama = $(this).val();
            $.ajax({
                url: '/karyawan/cekdata/' + nama,
                method: "GET",
                dataType: "json",
                success: function(data) {
                    if (data != 0) {
                        $('#nama').html('<span class="text-danger">Nama karyawan pernah di input</span>');
                        $('#button_tambah').attr("disabled", true);
                    } else {
                        $('#button_tambah').attr("disabled", false);
                    }
                }
            })
        });

        $('.select2').select2();
    });
</script>
@stop
