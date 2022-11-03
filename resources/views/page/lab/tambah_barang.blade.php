@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Tambah Brang Uji</h1>
@stop

@section('content')
<style>
.bc-success{
    background-color:rgba(40, 167, 69, 0.2);
}
.ui-autocomplete-input {
z-index: 1511;
}
.ui-autocomplete {
z-index: 1510 !important;
}
.ui-menu-item > a.ui-corner-all {
    display: block;
    padding: 3px 15px;
    clear: both;
    font-weight: normal;
    line-height: 18px;
    color: #555555;
    white-space: nowrap;
    text-decoration: none;
}
.ui-state-hover, .ui-state-active {
    color: #ffffff;
    text-decoration: none;
    background-color: #0088cc;
    border-radius: 0px;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    background-image: none;
}
</style>
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<div class="container-fluid">

    <div class="container p-3 bg-white">

        <form action="{{route('alatuji.barang.store')}}" method="post" enctype="multipart/form-data">
        @csrf

        <!-- card informasi umum alat -->
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
                            <option value="" disabled hidden {{ (old('klasifikasi') == null ? 'SELECTED' : '') }}>Pilih Klasifikasi</option>
                            @foreach($klasifikasi as $k)
                            <option value="{{ $k->id_klasifikasi }}" {{ (old('klasifikasi') == $k->id_klasifikasi ? "SELECTED" : '') }}>{{ $k->nama_klasifikasi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @error('nama')
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
                        <select class="form-control form-control-sm" name="nama" id="selectNama">
                            <option value="" disabled hidden {{ (old('nama') == null ? 'SELECTED' : '') }}>Pilih Nama Alat</option>
                            @foreach($nama as $n)
                            <option value="{{ $n->id_alatuji }}" {{ (old('nama') == $n->id_alatuji ? "SELECTED" : '') }}>{{ $n->nm_alatuji }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <!-- card informasi umum alat end -->

        <!-- card informasi detail alat -->
        <div class="card border-primary border-top-w3 shadow">
            <div class="card-body">

                <div class="row">
                    <h3 class="card-title">Informasi Detail Alat Uji</h3>
                </div>

                @error('merk')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                @error('merkbaru')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Merk Alat Uji</span></div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="checkmerk" id="merkada" value="ada" {{ old('checkmerk') == null ? 'checked' : (old('checkmerk') == 'ada' ? 'checked' : '') }}>
                                    <label class="form-check-label" for="merkada">Merk Ada</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="checkmerk" id="merktidak" value="tidak" {{ old('checkmerk') == 'tidak' ? 'checked' : ''}}>
                                    <label class="form-check-label" for="merktidak">Merk Tidak Ada</label>
                                </div>
                            </div>
                        </div>

                        <div id="col_merkAda">
                        <select class="form-control form-control-sm" name="merk" id="selectMerk">
                            <option value="" disabled hidden {{ (old('merk') == null ? 'SELECTED' : '') }} >Pilih merk</option>
                            <option value="0">Tidak di ketahui</option>
                            @foreach($merk as $sp)
                            <option value="{{ $sp->id_merk }}" {{ (old('merk') == $sp->id_merk ? "SELECTED" : '') }}>{{ $sp->nama_merk }}</option>
                            @endforeach
                        </select>
                        </div>

                        <div id="col_merkTidak">
                        <input class="form-control mt-2" type="text" name="merkbaru" placeholder="Merk Baru" value="{{ old('merkbaru') }}" id="merkbaru">
                        <small><small class="text-muted">Nama Merk Case Sensitive</small></small>
                        </div>
                    </div>
                </div>

                @error('serial_number')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Serial Number</span></div>
                    <div class="col">
                        <input class="form-control" type="text" name="serial_number" value="{{ old('serial_number') }}" id="">
                    </div>
                </div>

                @error('tipe')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Tipe</span></div>
                    <div class="col">
                        <input class="form-control" type="text" name="tipe" id="tipe" value="{{ old('tipe') }}" id="">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col"><span class="float-right">Nomor Urut</span></div>
                    <div class="col">
                        <div class="row">
                            <div class="col-4">
                                <input class="form-control" type="number" name="nomor_urut" id="form_nourut" value="{{ old('nomor_urut') }}" id="" readonly>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input type="radio" name="cek_no_urut" id="cek_nourut_otomatis" class="form-check-input" value="otomatis" {{ old('cek_no_urut') == null ? 'checked' : (old('cek_no_urut') == 'otomatis' ? 'checked' : '') }}>
                                    <label class="form-check-label" for="cek_nourut_otomatis">Otomatis</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input type="radio" name="cek_no_urut" id="cek_notrut_manual" class="form-check-input" value="manual" {{ old('cek_no_urut') == 'manual' ? 'checked' : ''}}>
                                    <label class="form-check-label" for="cek_notrut_manual">Manual</label>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted">Nomor Urut akan di tampilkan dari nilai terbesar +1</small>
                    </div>
                </div>

                @error('tanggal_masuk')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Tanggal Masuk</span></div>
                    <div class="col">
                        <input class="form-control" type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" id="">
                    </div>
                </div>

                @error('kondisi')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Kondisi</span></div>
                    <div class="col">
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kondisi" id="ok" value="9" {{ old('kondisi') == null ? 'checked' : (old('kondisi') == 9 ? 'checked' : '') }}>
                                    <label class="form-check-label" for="ok">OK</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kondisi" id="not" value="10" {{ old('kondisi') == 10 ? 'checked' : ''}}>
                                    <label class="form-check-label" for="not">NOT OK</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @error('lokasi')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Lokasi</span></div>
                    <div class="col">
                        <select class="form-control form-control-sm" name="lokasi" id="selectLokasi">
                            <option value="" disabled hidden {{ (old('lokasi') == null ? 'SELECTED' : '') }} >Pilih Lokasi Penyimpanan</option>
                            @foreach($lokasi as $l)
                            <option value="{{ $l->id }}" {{ (old('lokasi') == $l->id ? "SELECTED" : '') }} >{{ $l->ruang }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <!-- card informasi detail alat end -->

        <!-- card dokumen penunjang -->
        <div class="card border-primary border-top-w3 shadow">
            <div class="card-body">

                <div class="row">
                    <h3 class="card-title">Dokumen Penunjang</h3>
                </div>

                @error('sert_kalibrasi')
                    <div class="row">
                        <div class="col"></div>
                        <div class="col">
                            <div class="alert bc-danger text-danger border border-danger py-0 mb-0 mt-1">{{ $message }}</div>
                        </div>
                    </div>
                @enderror
                <div class="row mb-2">
                    <div class="col"><span class="float-right">Sertifikat Kalibrasi</span></div>
                    <div class="col">
                        <div class="form-group">
                            <input type="file" name="sert_kalibrasi" class="form-control-file">
                            <small><small class="text-muted">Dokumen Berupa Gambar berukuran maksimal 2 MB</small></small>
                        </div>
                    </div>
                </div>

                <!-- <div class="row mb-2">
                    <div class="col"><span class="float-right">Barcode</span></div>
                    <div class="col">placeholder tempat barcode</div>
                </div> -->

            </div>
        </div>
        <!-- card dokumen penunjang end -->

        <div class="card-body">
            <div class="row float-right">
                <div class="col-auto">
                    <a href="/alatuji" class="btn btn-danger float-right">Batal</a>
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
        $('#selectNama').select2();
        $('#selectMerk').select2();

        // auto complete tipe
        $(function() {
            $.ajax({
                type:'GET',
                url:'{{ url("/api/inventory/get_data_tipe") }}',
                success:function(data) {
                    autoComp(data);
                }
            });
        });

        function autoComp(data){
            //hapus value jika ada yang null
            for(let i = 0; i<data.length;i++){
                if ( data[i] == null) { 
                    data.splice(i, 1); 
                }
            }
            console.log(data);
            $('#tipe').autocomplete({
                source: data,
                autofocus: true,
            });
        }
        // auto complete tipe end

        // get data nomor urut
        $('#selectNama').change(function(){
            var x = $('#selectNama').val();
            console.log(x);
            $.ajax({
                type:'GET',
                url:'{{ url("/api/inventory/get_data_no_urut") }}'+'/'+x,
                success:function(data) {
                    showNoUrut(data);
                }
            });
        })


        function showNoUrut(data){
            console.log(data);
            // bagian nomor urut manual & otomatis
            @if(old('cek_no_urut') == null OR old('cek_no_urut') == 'otomatis')
                $("#form_nourut").attr('readonly', true);
                $('#form_nourut').val(data);
            @endif
            @if(old('cek_no_urut') == 'manual')
                $("#form_nourut").prop( "readonly", false );
                $('#form_nourut').attr("placeholder", data);
            @endif

        }

        // get data nomor urut end
    });

    @if(old('checkmerk') == null OR old('checkmerk') == 'ada')
        $( "#merkbaru" ).prop( "disabled", true );
        $( "#selectMerk" ).prop( "disabled", false );
        $('#col_merkAda').show();
        $('#col_merkTidak').hide();
    @endif
    @if(old('checkmerk') == 'tidak')
        $( "#merkbaru" ).prop( "disabled", false );
        $( "#selectMerk" ).prop( "disabled", true );
        $('#col_merkAda').hide();
        $('#col_merkTidak').show();
    @endif

    $("input[name$='checkmerk']").click(function(){
        if($("#merkada").is(":checked"))
        {
            $( "#selectMerk" ).prop( "disabled", false );
            $( "#merkbaru" ).prop( "disabled", true );
            $('#col_merkTidak').hide();
            $('#col_merkAda').show();
        }
        if($("#merktidak").is(":checked"))
        {
            $( "#selectMerk" ).prop( "disabled", true );
            $( "#merkbaru" ).prop( "disabled", false );
            $('#col_merkAda').hide();
            $('#col_merkTidak').show();
        }
    });

    $("input[name$='cek_no_urut']").click(function(){
        if($("#cek_nourut_otomatis").is(":checked"))
        {
            $("#form_nourut").prop( "readonly", true );
        }
        if($("#cek_notrut_manual").is(":checked"))
        {
            $("#form_nourut").prop( "readonly", false );
        }
    });

    @if(session()->has('success'))
        Swal.fire({
            title: 'Berhasil',
            text: 'Data Berhasil di Masukkan',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif
</script>

@endsection
