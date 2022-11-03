@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Tambah Jenis Alat Uji</h1>
@stop

@section('content')
<style>
.bc-success{
    background-color:rgba(40, 167, 69, 0.2);
}
.border-top-w3{
    border-top-width:3px!important;
}
.border-bottom-w3{
    border-bottom-width:3px!important;
}
</style>

<div class="container-fluid">

    <div class="container p-3 bg-white">

        <form action="{{route('alatuji.store')}}" method="post" enctype="multipart/form-data">
        @csrf

        <!-- card informasi umum -->
        <div class="card border-primary border-top-w3 shadow">
            <div class="card-body">

                <div class="row">
                    <h3 class="card-title">Informasi Umum Alat Uji</h3>
                </div>

                @error('klasifikasi')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Klasifikasi</span></div>
                    <div class="col">
                        <select class="form-control form-control-sm" name="klasifikasi" id="selectKlasifikasi">
                            <option value="" disabled selected hidden>Pilih Klasifikasi</option>
                            @foreach($klasifikasi as $k)
                            <option value="{{ $k->id_klasifikasi }}" {{ old('klasifikasi') == $k->id_klasifikasi ? 'selected' : '' }}>{{ $k->nama_klasifikasi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @error('satuan')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Satuan Pengukuran</span></div>
                    <div class="col">
                        <select class="form-control form-control-sm" name="satuan" id="selectSatuan">
                            <option value="" disabled selected hidden>Pilih Satuan Pengukuran</option>
                            @foreach($satuan as $s)
                            <option value="{{ $s->id }}" {{ old('satuan') == $s->id ? 'selected' : '' }}>{{ $s->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @error('nama_alat')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Nama Alat Uji</span></div>
                    <div class="col">
                        <input class="form-control" type="text" name="nama_alat" value="{{ old('nama_alat') }}" id="">
                    </div>
                </div>

                @error('kode_alat')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Kode Alat Uji</span></div>
                    <div class="col">
                        <input class="form-control" type="text" name="kode_alat" value="{{ old('kode_alat') }}" id="">
                    </div>
                </div>

                @error('fungsi_alat')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Fungsi</span></div>
                    <div class="col">
                        <textarea class="form-control" name="fungsi_alat" value="{{ old('fungsi_alat') }}" id="" cols="30" rows="1">{{ old('fungsi_alat') }}</textarea>
                    </div>
                </div>

            </div>
        </div>

        <!-- card dokumen penunjang -->
        <div class="card border-primary border-top-w3 shadow">
            <div class="card-body">

                <div class="row">
                    <h3 class="card-title">Dokumen Penunjang</h3>
                </div>

                @error('gambar')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Gambar</span></div>
                    <div class="col">
                        <div class="form-group">
                            <input type="file" name="gambar" class="form-control-file">
                            <small class="text-muted">Gambar berupa jpg/jpeg/png maksimal 2mb</small>
                        </div>
                    </div>
                </div>

                @error('manual_book')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Manual Book</span></div>
                    <div class="col">
                        <div class="form-group">
                            <input type="file" name="manual_book" class="form-control-file">
                            <small class="text-muted">Dokumen berupa pdf maksimal 10mb</small>
                        </div>
                    </div>
                </div>

                @error('sop')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">SOP</span></div>
                    <div class="col">
                        <div class="form-group">
                            <input type="file" name="sop" class="form-control-file">
                            <small class="text-muted">Gambar berupa jpg/jpeg/png maksimal 2mb</small>
                        </div>
                    </div>
                </div>

                <!-- <div class="row mb-2">
                    <div class="col"><span class="float-right">Barcode</span></div>
                    <div class="col">barcode</div>
                </div> -->

            </div>
        </div>

        <div class="card-body">
            <div class="row float-right">
                <div class="col-auto">
                    <a href="" class="btn btn-danger float-right">Batal</a>
                </div>
                <div class="col-auto">
                    <input type="submit" value="Simpan" class="btn btn-primary float-right">
                </div>
            </div>
        </div>

        </form>

    </div>

</div>
@stop
@section('adminlte_js')
<script>
$(document).ready(function() {
    $('#selectKlasifikasi').select2();
    $('#selectSatuan').select2();

    // tampilkan alert input data berhasil
    @if(session()->has('success'))
        Swal.fire({
            title: 'Berhasil',
            text: 'Data Berhasil di Masukkan',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif
    // modal alert end
});
</script>
@endsection
