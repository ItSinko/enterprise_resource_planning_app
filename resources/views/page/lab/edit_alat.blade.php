@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Ubah Data Alat Uji</h1>
@stop

@section('content')

<div class="container-flu">

    <div class="container p-3 bg-white">

        <form action="{{route('alatuji.update')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_alatuji" value="{{$data->alatuji_id}}">
        <input type="hidden" name="id_serial_number" value="{{$data->id_serial_number}}">

        <div class="card border-warning" style="border-top-width:3px!important;">
            <div class="card-body">

                <div class="row">
                    <h3 class="card-title">Informasi Umum Alat Uji</h3>
                </div>

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Klasifikasi</span></div>
                    <div class="col">
                        <select class="form-control form-control-sm" name="klasifikasi" id="selectKlasifikasi">
                            @foreach($klasifikasi as $k)
                            <option value="{{ $k->id_klasifikasi }}" {{ $k->id_klasifikasi == $data->klasifikasi_id ? 'SELECTED' : ''}}>{{ $k->nama_klasifikasi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Nama Alat Uji</span></div>
                    <div class="col">
                        <select class="form-control form-control-sm" name="namaalat" id="selectNama">
                            @foreach($nama as $n)
                            <option value="{{ $n->nm_alatuji }}" {{ $n->id_alatuji == $data->alatuji_id ? 'SELECTED' : ''}}>{{ $n->nm_alatuji }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Fungsi</span></div>
                    <div class="col">
                        <textarea class="form-control" name="fungsi" id="" cols="30" rows="1">{{ $data->desk_alatuji }}</textarea>
                    </div>
                </div>

            </div>
        </div>

        <div class="card border-warning" style="border-top-width:3px!important;">
            <div class="card-body">

                <div class="row">
                    <h3 class="card-title">Informasi Alat Uji</h3>
                </div>

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Merk Alat Uji</span></div>
                    <div class="col">
                        <select class="form-control form-control-sm" name="merk" id="selectMerk">
                            <option value="" disabled selected hidden>Pilih merk</option>
                            <option value="">belum</option>
                            @foreach($merk as $sp)
                            <option value="{{ $sp->id_merk }}" {{ $sp->id_merk == $data->merk_id ? 'SELECTED' : '' }}>{{ $sp->nama_merk }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @error('serialNM')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Serial Number</span></div>
                    <div class="col">
                        <input class="form-control" type="text" name="serialNM" id="" value="{{$sn->serial_number}}">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Nomor urut</span></div>
                    <div class="col">
                        <input class="form-control" type="number" name="noUrut" id="" value="{{ (int)$data->no_urut }}">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Tanggal Masuk</span></div>
                    <div class="col">
                        <input type="date" name="tgl_masuk" id="" value="{{$sn->tgl_masuk}}">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Kondisi</span></div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                <input class="form-check-input" type="radio" name="kondisi" id="ok" value="9" checked>
                                <label class="form-check-label" for="ok">
                                    OK
                                </label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                <input class="form-check-input" type="radio" name="kondisi" id="not" value="10">
                                <label class="form-check-label" for="not">
                                    NOT OK
                                </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Lokasi</span></div>
                    <div class="col">
                        <select class="form-control form-control-sm" name="lokasi" id="selectLokasi">
                            @foreach($lokasi as $l)
                            <option value="{{ $l->id }}" {{ $l->id == 8 ? 'SELECTED' : '' }}>{{ $l->ruang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <div class="card border-warning" style="border-top-width:3px!important;">
            <div class="card-body">
                <div class="row">
                    <h3 class="card-title">Dokumen Penjunjung</h3>
                </div>

                @error('sop')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">SOP Alat Uji</span></div>
                    <div class="col">
                        <div class="form-group">
                            <input type="file" name="sop" class="form-control-file">
                        </div>
                    </div>
                </div>

                @error('manual')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Manual Book</span></div>
                    <div class="col">
                        <div class="form-group">
                            <input type="file" name="manual" class="form-control-file">
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><span class="float-right">QR Code</span></div>
                    <div class="col">
                        display QR
                    </div>
                </div>

                @error('gambar')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Gambar</span></div>
                    <div class="col">
                        <div class="form-group">
                            <input type="file" name="gambar" class="form-control-file">
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"></div>
                    <div class="col">
                        gambar privew
                    </div>
                </div>

            </div>
        </div>

        <div class="card-body">
            <div class="row float-right">
                <div class="col-auto">
                    <a href="{{ route('alatuji.detail', ['id' => $data->id_serial_number]) }}" class="btn btn-danger float-right">Batal</a>
                </div>
                <div class="col-auto">
                    <input type="submit" value="Submit" class="btn btn-primary float-right">
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
        $('#selectNama').select2();
        $('#selectMerk').select2();
        $('#selectlokasi').select2();

        // tampilkan alert input data berhasil
        @if(session()->has('editSuccess'))
            Swal.fire({
                title: 'Berhasil',
                text: 'Data Berhasil di Perbarui',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif
        // modal alert end
    });
</script>
@endsection
