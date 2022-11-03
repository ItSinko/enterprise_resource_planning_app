@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">{{ $jenis }} Alat Uji</h1>
@stop

@section('content')

<div class="container-fluid">

    <div class="container p-3 bg-white">

        <!-- card info -->
        <div class="card border-secondary border-bottom-w3">
            <div class="card-body">

                <div class="row">
                    <!-- gambar -->
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

                    <!-- col tanggal terakhir -->
                    <div class="col-4">
                        <p class="mb-0 mt-3">{{ $jenis }} Terakhir</p>
                        <span><strong>{{ $data->tgl_kirim }}</strong></span>
                    </div>
                </div>

            </div>
        </div>

        <form action="{{route('alatuji.mt.store')}}" method="post" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="serial_number_id" value="{{ $id }}">
        <input type="hidden" name="jenis_mt" value="{{ $jenis }}">

        <!-- card info -->
        <div class="card border-primary border-top-w3">
            <div class="card-body">

                <div class="row">
                    <h3 class="card-title">Informasi Umum {{ $jenis }}</h3>
                </div>

                @error('tanggal_pengajuan')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Tanggal Pengajuan</span></div>
                    <div class="col">
                        <input class="form-control" type="date" name="tanggal_pengajuan" id="" value="{{ old('tanggal_pengajuan') }}">
                    </div>
                </div>

                @error('masalah')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Masalah</span></div>
                    <div class="col">
                        <textarea class="form-control" name="masalah" id="" cols="30" rows="1" maxlength="255">{{ old('masalah') }}</textarea>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Jenis {{ $jenis }}</span></div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dilakukan" id="internal" value="Internal" {{ old('dilakukan') == null ? 'checked' : (old('dilakukan') == 'Internal' ? 'checked' : '') }}>
                                    <label class="form-check-label" for="internal">Internal</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dilakukan" id="external" value="External" {{ old('dilakukan') == 'External' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="external">External</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- card hasil -->
        <div class="card border-primary border-top-w3" >
            <div class="card-body">

                <!-- bagian internal -->
                <div id="internalContainer">
                <div class="row">
                    <h3 class="card-title">Hasil {{ $jenis }} Internal</h3>
                </div>

                @error('operator')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Operator Pelaksana</span></div>
                    <div class="col">
                        <select name="operator" id="selectOperator" class="form-control form-control-sm">
                            <option></option>
                            @foreach($user as $u)
                            <option value="{{ $u->id }}" {{ old('operator') == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @error('cekFungsi')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Cek Fungsi</span></div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cekFungsi" id="fungsiOK" value="9" {{ old('cekFungsi') == 9 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fungsiOK">OK</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cekFungsi" id="fungsiNOT" value="10" {{ old('cekFungsi') == 10 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fungsiNOT">NOT OK</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @error('cekFisik')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Cek Fisik</span></div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cekFisik" id="fisikOK" value="9" {{ old('cekFisik') == 9 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fisikOK">OK</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cekFisik" id="fisikNOT" value="10" {{ old('cekFisik') == 10 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fisikNOT">NOT OK</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @error('tindak_lanjut')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Tindak Lanjut</span></div>
                    <div class="col">
                        <textarea class="form-control" name="tindak_lanjut" id="" cols="30" rows="1" maxlength="100" >{{ old('tindak_lanjut') }}</textarea>
                    </div>
                </div>
                </div>
                <!-- bagian internal end -->

                <!-- bagian external -->
                <div id="externalContainer">
                <div class="row">
                    <h3 class="card-title">Dokumen {{ $jenis }} External</h3>
                </div>

                @error('surat_jalan')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Surat Jalan</span></div>
                    <div class="col">
                        <div class="form-group">
                            <input type="file" name="surat_jalan" class="form-control-file">
                            <small class="text-muted">File Berupa Gambar, Maksimal 2MB</small>
                        </div>
                    </div>
                </div>

                @error('memo')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Memo</span></div>
                    <div class="col">
                        <div class="form-group">
                            <input type="file" name="memo" class="form-control-file">
                            <small class="text-muted">File Berupa Gambar, Maksimal 2MB</small>
                        </div>
                    </div>
                </div>
                </div>
                <!-- bagian external end -->

            </div>
        </div>

        <div class="card-body">
            <div class="row float-right">
                <div class="col-auto">
                    <a href="{{ route('alatuji.detail', ['id' => $id, $jenis == 'Kalibrasi' ? '4' : '5']) }}" class="btn btn-danger float-right">Batal</a>
                </div>
                <div class="col-auto">
                    <input type="submit" id="btnSubmit" value="Simpan" class="btn btn-primary float-right">
                </div>
            </div>
        </div>

        </form>

    </div>

</div>
@stop
@section('adminlte_js')
<script>
    //hide dokumen internal external
    $(document).ready(function(){
        @if(old('dilakukan') == 'External')
            $('#internalContainer').hide();
        @elseif(old('dilakukan') == 'Internal')
            $('#externalContainer').hide();
        @else
            $('#externalContainer').hide();
        @endif

        $("input[name$='dilakukan']").click(function(){
            if($("#external").is(":checked"))
            {
                $('#internalContainer').hide();
                $('#externalContainer').show();
            }
            if($("#internal").is(":checked"))
            {
                $('#internalContainer').show();
                $('#externalContainer').hide();
            }
        })

        $('#selectOperator').select2({
            placeholder: "Pilih Penanggung Jawab"
        });
    });

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
