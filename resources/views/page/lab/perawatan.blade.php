@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Perawatan Alat Uji</h1>
@stop

@section('content')

<div class="container-fluid">

    <div class="container p-3 bg-white">

        <form action="{{route('alatuji.perawatan.store')}}" method="post">
        @csrf

        <!-- card info -->
        <div class="card border-secondary border-bottom-w3">
            <div class="card-body">

                <div class="row">
                    <!-- col gambar -->
                    <div class="col-4">
                        <object data="{{ asset('/storage/gambar/'.$data->gbr_alatuji) }}" type="image/png" class="img-fluid text-center">
                            <img src="{{ asset('/storage/gambar/default.png') }}" class="img-fluid text-center" alt="gambar alat uji">
                        </object>
                    </div>

                    <!-- col info -->
                    <div class="col-4">
                        <h4><strong>{{ $data->kode_alat }}</strong></h4>
                        <span><strong>{{ $data->nm_alatuji }}</strong></span>

                        <p class="mb-0 mt-3">Serial Number</p>
                        <span><strong>{{ $data->serial_number }}</strong></span>

                        <p class="mb-0 mt-3">Status</p>
                        {!! $data->status_pinjam_id !!}
                    </div>

                    <!-- col perawatan terakhir -->
                    <div class="col-4">
                        <p class="mb-0 mt-3">Perawatan Terakhir</p>
                        <span><strong>{{ $data->tgl_perawatan }}</strong></span>
                    </div>
                </div>

            </div>
        </div>

        <!-- card pengerjaan -->
        <div class="card border-primary border-top-w3">
            <div class="card-body">

                <div class="row">
                    <h3 class="card-title">Pengerjaan</h3>
                </div>

                <input type="hidden" name="serial_number" value="{{ $id }}">

                @error('operator')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col mb-0">
                            <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Operator</span></div>
                    <div class="col">
                        <select name="operator" id="selectOperator" class="form-control form-control-sm">
                            <option value="" disabled selected hidden>Pilih Operator Perawatan</option>
                            @foreach($user as $u)
                            <option value="{{ $u->id }}" {{ old('operator') == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @error('tgl_perawatan')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col mb-0">
                            <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Tanggal Perawatan</span></div>
                    <div class="col">
                        <input class="form-control" type="date" name="tgl_perawatan" id=""  value="{{ old('tgl_perawatan') }}">
                    </div>
                </div>

            </div>
        </div>

        <!-- card hasil perawatan -->
        <div class="card border-primary border-top-w3" >
            <div class="card-body">
                <div class="row">
                    <h3 class="card-title">Hasil perawatan</h3>
                </div>

                @error('cek_fungsi_txt')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col mb-0">
                            <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Cek Fungsi</span></div>
                    <div class="col">
                        <div class="form-group">
                            <input type="text" name="cek_fungsi_txt" class="form-control" value="{{ old('cek_fungsi_txt') }}">
                        </div>
                    </div>
                </div>

                @error('cek_kelengkapan')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col mb-0">
                            <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Cek Kelengkapan</span></div>
                    <div class="col">
                        <div class="form-group">
                            <input type="text" name="cek_kelengkapan" id="" class="form-control" value="{{ old('cek_kelengkapan') }}">
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Cek Fungsi</span></div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cekFungsi" id="fungsiok" value="9" checked="">
                                    <label class="form-check-label" for="fungsiok">OK</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cekFungsi" id="fungsinot" value="10">
                                    <label class="form-check-label" for="fungsinot">NOT OK</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Cek Fisik</span></div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cekFisik" id="fisikok" value="9" checked="">
                                    <label class="form-check-label" for="fisikok">OK</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cekFisik" id="fisiknot" value="10">
                                    <label class="form-check-label" for="fisiknot">NOT OK</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @error('tindak_lanjut')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col mb-0">
                            <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Tindak Lanjut</span></div>
                    <div class="col">
                        <textarea class="form-control" name="tindak_lanjut" id="" cols="30" rows="1">{{ old('tindak_lanjut') }}</textarea>
                    </div>
                </div>

                @error('keterangan')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col mb-0">
                            <div class="alert bc-danger text-danger border border-danger p-1 mb-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Keterangan</span></div>
                    <div class="col">
                        <textarea class="form-control" name="keterangan" id="" cols="30" rows="1">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-body">
            <div class="row float-right">
                <div class="col-auto">
                    <a href="{{ route('alatuji.detail', ['id' => $id, 'x' => 2]) }}" class="btn btn-danger float-right">Batal</a>
                </div>
                <div class="col-auto">
                    <input type="submit" id="btnSubmit" value="Submit" class="btn btn-primary float-right">
                </div>
            </div>
        </div>

        </form>

    </div>

</div>
@stop
@section('adminlte_js')
<script>
$('#selectOperator').select2();
// tampilkan modal konfirmasi
$("#btnSubmit").on('click', function(e){
    e.preventDefault();
    var form = $(this).parents('form');

    Swal.fire({
        title: 'Data Akan Di Catat',
        text: 'Pastikan data yang anda masukan sudah benar',
        icon: 'question',
        confirmButtonText: 'OK',
    }).then((a) => {
        if(a){
            form.submit();
        }
    });
});
</script>

@endsection
