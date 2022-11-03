@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Verifikasi Alat Uji</h1>
@stop

@section('content')

<div class="container-fluid">

    <div class="container p-3 bg-white">

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
                        <span><strong>{{ $data->desk_alatuji }}</strong></span>

                        <p class="mb-0 mt-3">Serial Number</p>
                        <span><strong>{{ $data->serial_number }}</strong></span>

                        <p class="mb-0 mt-3">Status</p>
                        {!! $data->status_pinjam_id !!}
                    </div>

                    <!-- col perawatan terakhir -->
                    <div class="col-4">
                        <p class="mb-0 mt-3">Verifikasi Terakhir</p>
                        <span><strong>{{ $data->tgl_perawatan }}</strong></span>
                    </div>
                </div>

            </div>
        </div>

        <!-- card verifikasi -->
        <form action="{{route('alatuji.verifikasi.store')}}" method="post">
        @csrf

        <div class="card border-primary border-top-w3">
            <div class="card-body">

                <div class="row">
                    <h3 class="card-title">Pengerjaan</h3>
                </div>

                <input type="hidden" name="serial_number" value="{{ $id }}">

                @error('operator')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Operator</span></div>
                    <div class="col">
                        <select name="operator" id="selectOperator" class="form-control form-control-sm">
                            <option value="" disabled selected hidden>Pilih Operator Verifikasi</option>
                            @foreach($user as $u)
                            <option value="{{ $u->id }}" {{ old('operator') == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @error('tgl_verifikasi')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Tanggal Verifikasi</span></div>
                    <div class="col">
                        <input class="form-control" type="date" name="tgl_verifikasi" id=""  value="{{ old('tgl_verifikasi') }}">
                    </div>
                </div>

                @error('pelaksanaan_kendali')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Pelaksanaan Pengendalian</span></div>
                    <div class="col">
                        <textarea class="form-control" name="pelaksanaan_kendali" id="" cols="30" rows="1">{{ old('pelaksanaan_kendali') }}</textarea>
                    </div>
                </div>

            </div>
        </div>

        <!-- card hasil Verifikasi -->
        <div class="card border-primary border-top-w3" >
            <div class="card-body">
                <div class="row">
                    <h3 class="card-title">Hasil Verifikasi</h3>
                </div>

                <div id="check_group">
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Cek Fungsi</span></div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cekFungsi" id="fungsiok" value="9" {{ old('cekFungsi') == null ? 'checked' : (old('cekFungsi') == 9 ? 'checked' : '') }}>
                                    <label class="form-check-label" for="fungsiok">OK</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cekFungsi" id="fungsinot" value="10" {{ old('cekFungsi') == 10 ? 'checked' : ''}}>
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
                                    <input class="form-check-input" type="radio" name="cekFisik" id="fisikok" value="9" {{ old('cekFisik') == null ? 'checked' : (old('cekFisik') == 9 ? 'checked' : '') }}>
                                    <label class="form-check-label" for="fisikok">OK</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cekFisik" id="fisiknot" value="10" {{ old('cekFisik') == 10 ? 'checked' : ''}}>
                                    <label class="form-check-label" for="fisiknot">NOT OK</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                @error('keputusan')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Keputusan</span></div>
                    <div class="col">
                        <textarea class="form-control" name="keputusan" id="" cols="30" rows="1">{{ old('keputusan') }}</textarea>
                    </div>
                </div>

                @error('keterangan')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Keterangan</span></div>
                    <div class="col">
                        <textarea class="form-control" name="keterangan" id="" cols="30" rows="1">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                @error('tindak_lanjut')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div id="tindak_lanjut_container">
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Tindak Lanjut</span></div>
                    <div class="col">
                        <textarea class="form-control" name="tindak_lanjut" id="" cols="30" rows="1">{{ old('tindak_lanjut') }}</textarea>
                    </div>
                </div>
                </div>

            </div>
        </div>

        <div class="card-body">
            <div class="row float-right">
                <div class="col-auto">
                    <a href="{{ route('alatuji.detail', ['id' => $id, 'x' => 3]) }}" class="btn btn-danger float-right">Batal</a>
                </div>
                <div class="col-auto">
                    <input type="submit" id="btnSubmit" class="btn btn-primary float-right" value="simpan">
                </div>
            </div>
        </div>

        </form>

    </div>

</div>
@stop
@section('adminlte_js')
<script>
$(document).ready(function(){
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

    // cek tindak lanjut
    @if(old('cekFungsi') == '10' or old('cekFisik') == '10')
        $('#tindak_lanjut_container').show();
    @else
        $('#tindak_lanjut_container').hide();
    @endif

    var x = 0;
    var y = 0;
    function cek(x,y){
        if(x == 1 && y == 1){
            $('#tindak_lanjut_container').hide();
        }else{
            $('#tindak_lanjut_container').show();
        }
    }
    $("input[name$='cekFungsi']").click(function(){
        if($("#fungsiok").is(":checked")){
            x = 1;
            cek(x,y);
        }
        if($("#fungsinot").is(":checked")){
            x = 0;
            cek(x,y);
        }
    });
    $("input[name$='cekFisik']").click(function(){
        if($("#fisikok").is(":checked")){
            y = 1;
            cek(x,y);
        }
        if($("#fisiknot").is(":checked")){
            y = 0;
            cek(x,y);
        }
    });
    // cek tindak lanjut end
});
</script>

@endsection
