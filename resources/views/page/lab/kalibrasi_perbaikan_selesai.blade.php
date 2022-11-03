@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">{{ $jenis }} Alat Uji</h1>
@stop

@section('content')

<div class="container-fluid">

    <div class="container p-3 bg-white">

        <form action="{{route('alatuji.mt.external.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_mt" value="{{ $id }}">
        <input type="hidden" name="jenis" value="{{ $jenis }}">

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
                        <span><strong>{{ $data->nm_alatuji }}</strong></span>

                        <p class="mb-0 mt-3">Serial Number</p>
                        <span><strong>{{ $data->serial_number }}</strong></span>

                        <p class="mb-0 mt-3">Jenis {{ $jenis }}</p>
                        <span class="badge w-25 bc-warning">
                            <span class="text-warning">External</span>
                        </span>
                    </div>

                    <!-- col tanggal terakhir -->
                    <div class="col-4">
                        <p class="mb-0 mt-3">Tanggal Pengiriman</p>
                        <span><strong>{{ $data->tgl_kirim }}</strong></span>
                    </div>
                </div>

            </div>
        </div>

        <!-- card penerimaan -->
        <div class="card border-warning border-top-w3">
            <div class="card-body">

                <div class="row">
                    <h3 class="card-title">Penerimaan {{ $jenis }}</h3>
                </div>

                @error('tgl_terima')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Tanggal Penerimaan</span></div>
                    <div class="col">
                        <input class="form-control" type="date" name="tgl_terima" id="" value="{{ old('tgl_terima') }}">
                    </div>
                </div>

                @error('perusahaan')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Perusahaan</span></div>
                    <div class="col">
                        <select name="perusahaan" id="selectPerusahaan" class="form-control form-control-sm">
                        <option value="" disabled hidden {{ (old('perusahaan') == null ? 'SELECTED' : '') }}>Pilih Perusahaan</option>
                            <option value="0">Tidak di ketahui</option>
                            @foreach($perusahaan as $p)
                            <option value="{{ $p->id_merk }}" {{ (old('perusahaan') == $p->id_merk ? "SELECTED" : '') }}>{{ $p->nama_merk }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @error('biaya')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Biaya</span></div>
                    <div class="col">
                        <input class="form-control" type="number" name="biaya" id="" value="{{ old('biaya') }}">
                    </div>
                </div>

            </div>
        </div>

        <!-- card hasil -->
        <div class="card border-warning border-top-w3" >
            <div class="card-body">

                <div class="row">
                    <h3 class="card-title">Hasil {{ $jenis }}</h3>
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
                                    <input class="form-check-input" type="radio" name="cekFungsi" id="fungsiok" value="9" checked="" {{ old('cekFungsi') == null ? 'checked' : (old('cekFungsi') == 9 ? 'checked' : '') }}>
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

                @error('tindak_lanjut')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Tindak Lanjut</span></div>
                    <div class="col">
                        <textarea class="form-control" name="tindak_lanjut" id="" cols="30" rows="1" maxlength="100">{{ old('tindak_lanjut') }}</textarea>
                    </div>
                </div>

                @if($jenis == 'kalibrasi')
                @error('sertif_kalibrasi')
                    <div class="row mb-0">
                        <div class="col"></div>
                        <div class="col alert bc-danger text-danger border border-danger p-1">{{ $message }}</div>
                    </div>
                @enderror

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Sertifikat Kalibrasi</span></div>
                    <div class="col">
                        <div class="form-group">
                            <input type="file" name="sertif_kalibrasi" class="form-control-file">
                            <small class="text-muted">File Berupa Gambar, Maksimal 2MB</small>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>

        <div class="card-body">
            <div class="row float-right">
                <div class="col-auto">
                    <a href="{{ route('alatuji.detail', ['id' => $id, $jenis == 'kaibrasi' ? '4' : '5']) }}" class="btn btn-danger float-right">Batal</a>
                </div>
                <div class="col-auto">
                    <input type="submit" value="Simpan" class="btn btn-warning float-right">
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
        $('#selectPerusahaan').select2();
    });
</script>

@endsection
