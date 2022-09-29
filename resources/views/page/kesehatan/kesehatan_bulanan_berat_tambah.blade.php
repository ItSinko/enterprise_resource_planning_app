@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Berat Tambah</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kesehatan.dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active"><a href="/kesehatan/bulanan/">Kesehatan Bulanan</a></li>
                    <li class="breadcrumb-item ">Berat Tambah</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('adminlte_css')
<style>
    table { border-collapse: collapse; empty-cells: show; }

    td { position: relative; }

    .foo {
        border-radius: 50%;
        float: left;
        width: 10px;
        height: 10px;
        align-items: center !important;
    }

    tr.line-through td:not(:nth-last-child(-n+2)):before {
        content: " ";
        position: absolute;
        left: 0;
        top: 35%;
        border-bottom: 1px solid;
        width: 100%;
    }

    .align-right {
        text-align:right;
    }

    .align-center {
        text-align:center;
    }

    @media screen and (min-width: 1440px) {

        body {
            font-size: 14px;
        }

        #detailmodal {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }


    }

    @media screen and (max-width: 1439px) {
        body {
            font-size: 12px;
        }

        h4 {
            font-size: 20px;
        }

        #detailmodal {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }
    }



</style>
@stop
@section('content')
<div class="row">
    <div class="col-12">
        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            Data berhasil ditambahkan
        </div>
        @elseif(session()->has('error') || count($errors) > 0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            Data gagal ditambahkan
        </div>
        @endif
        <div class="card">
            <div class="card-header card-outline card-primary">
                <h6 class="card-title">Form Tambah Hasil Cek Bulanan</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">Informasi Umum</h5>
                        <div class="form-horizontal">
                            <div class="form-group row">
                                <label for="tgl_cek_form" class="col-lg-5 col-form-label align-right">Tanggal</label>
                                <div class="col-lg-2">
                                    <input type="date" class="form-control tgl_cek_form col-form-label" name="tgl_cek_form" id="tgl_cek_form" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_cek_form" class="col-lg-5 col-form-label align-right">Nama Karyawan</label>
                                <div class="col-lg-3">
                                    <select class="form-control select2" style="width:100%" name="karyawan_id_form" id="karyawan_id_form">
                                        <option value=""></option>
                                        @foreach($karyawan as $k)
                                        <option value="{{$k->id}}">{{$k->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mt-3">
                    <div class="col-6">
                        <h5 class="mb-3">Hasil Ukur Timbangan</h5>
                        <div class="form-horizontal">
                            <div class="form-group row">
                                <label for="tgl_cek_form" class="col-lg-3 col-form-label align-right">Berat & Lemak</label>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="input-group col-6">
                                            <input type="text" class="form-control berat_form" name="berat_form" id="berat_form" placeholder="Berat">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Kg</span>
                                            </div>
                                        </div>
                                        <div class="input-group col-6">
                                            <input type="text" class="form-control lemak_form" name="lemak_form" id="lemak_form" placeholder="Lemak">
                                            <div class="input-group-append">
                                                <span class="input-group-text">gram</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_cek_form" class="col-lg-3 col-form-label align-right">Kandungan Air</label>
                                <div class="input-group col-lg-4">
                                    <input type="text" class="form-control kandungan_air_form" name="kandungan_air_form" id="kandungan_air_form" placeholder="Air">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_cek_form" class="col-lg-3 col-form-label align-right">Otot & Tulang</label>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="input-group col-6">
                                            <input type="text" class="form-control otot_form" name="otot_form" id="otot_form" placeholder="Otot">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Kg</span>
                                            </div>
                                        </div>
                                        <div class="input-group col-6">
                                            <input type="text" class="form-control tulang_form" name="tulang_form" id="tulang_form" placeholder="Tulang">
                                            <div class="input-group-append">
                                                <span class="input-group-text">Kg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_cek_form" class="col-lg-3 col-form-label align-right">Kalori</label>
                                <div class="input-group col-4">
                                    <input type="text" class="form-control kalori_form" name="kalori_form" id="kalori_form" placeholder="Kalori">
                                    <div class="input-group-append">
                                        <span class="input-group-text">kkal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="mb-3">Hasil Pengukuran Lain</h5>
                        <div class="form-horizontal">
                            <div class="form-group row">
                                <label for="keterangan" class="col-sm-3 col-form-label" style="text-align:right;">Suhu</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="suhu_form" id="suhu_form">
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
                                <label for="keterangan" class="col-sm-3 col-form-label" style="text-align:right;">Saturasi Oksigen Darah</label>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="spo2_form" id="spo2_form" value="{{ old('spo2_form') }}" placeholder="SPO2">
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
                                                    <input type="text" class="form-control" name="pr_form" id="pr_form" value="{{ old('pr_form') }}" placeholder="PR">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">bpm</span>
                                                    </div>
                                                </div>
                                                @if($errors->has('pr_form'))
                                                <div class="text-danger">
                                                    {{ $errors->first('pr_form')}}
                                                </div>
                                                @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keterangan" class="col-sm-3 col-form-label" style="text-align:right;">Tekanan Darah</label>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="sistolik_form" id="sistolik_form" value="{{ old('sistolik_form') }}" placeholder="Systol">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">mmHg</span>
                                                </div>
                                            </div>
                                            @if($errors->has('sistolik_form'))
                                            <div class="text-danger">
                                                {{ $errors->first('sistolik_form')}}
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="diastolik_form" id="diastolik_form" value="{{ old('diastolik_form') }}" placeholder="Dyastol">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">mmHg</span>
                                                </div>
                                            </div>
                                            @if($errors->has('diastolik_form'))
                                            <div class="text-danger">
                                                {{ $errors->first('diastolik_form')}}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="form-horizontal">
                            <div class="form-group row">
                                <label for="keterangan_form" class="col-lg-5 col-form-label align-right">Catatan</label>
                                <div class="col-lg-3">
                                    <textarea class="form-control keterangan_form col-form-label" name="keterangan_form" id="keterangan_form"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-warning btn-sm" id="btnreset">Reset Form</button>
                <button type="button" class="btn btn-secondary btn-sm float-right" id="tambahitem">Tambahkan Ke Draf</button>
            </div>
        </div>
        <form action="/kesehatan/bulanan/berat/aksi_tambah" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h5 class="card-title">Draf Data Kesehatan Bulanan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table-hover align-center" id="tabel_berat">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Tanggal</th>
                                        <th rowspan="2">Nama</th>
                                        <th colspan="6">Timbangan</th>
                                        <th rowspan="2">Suhu</th>
                                        <th colspan="2">Saturasi Oksigen</th>
                                        <th colspan="2">Tekanan Darah</th>
                                        <th rowspan="2">Catatan</th>
                                        <th rowspan="2">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th>Berat</th>
                                        <th>Lemak</th>
                                        <th>Kandungan air</th>
                                        <th>Otot</th>
                                        <th>Tulang</th>
                                        <th>Kalori</th>

                                        <th>SPO2</th>
                                        <th>PR</th>
                                        <th>Systol</th>
                                        <th>Dyastol</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="16">Data Belum Tersedia</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="/kesehatan/bulanan"><button type="button" class="btn btn-danger btn-sm" id="btnreset">Batal</button></a>
                    <button type="submit" class="btn btn-info btn-sm float-right" id="tambahitem">Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('adminlte_js')
<script>
    $(document).ready(function() {
        $('#tabel_berat').DataTable({
            "scrollX": true,
            "searching": false,
            "paging": false,
            "lengthChange": false,
            "info": false
        });
    });
</script>
<script>
    $(function() {
        function numberRows($t) {
            var c = 0 - 2;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('.tgl_cek').attr('name', 'tgl_cek[' + j + ']');
                $(el).find('.karyawan_id').attr('name', 'karyawan_id[' + j + ']');
                $(el).find('.berat').attr('name', 'berat[' + j + ']');
                $(el).find('.lemak').attr('name', 'lemak[' + j + ']');
                $(el).find('.kandungan_air').attr('name', 'kandungan_air[' + j + ']');
                $(el).find('.otot').attr('name', 'otot[' + j + ']');
                $(el).find('.tulang').attr('name', 'tulang[' + j + ']');
                $(el).find('.kalori').attr('name', 'kalori[' + j + ']');
                $(el).find('.suhu').attr('name', 'suhu[' + j + ']');
                $(el).find('.spo2').attr('name', 'spo2[' + j + ']');
                $(el).find('.pr').attr('name', 'pr[' + j + ']');
                $(el).find('.sistolik').attr('name', 'sistolik[' + j + ']');
                $(el).find('.diastolik').attr('name', 'diastolik[' + j + ']');
                $(el).find('.keterangan').attr('name', 'keterangan[' + j + ']');

                $('.select2').select2({allowClear: true, placeholder: "Pilih Karyawan"});
            });
        }

        $('#btnreset').click(function(){
            $('#tgl_cek_form').val('');
            $('#karyawan_id_form').val('');
            $('#berat_form').val('');
            $('#lemak_form').val('');
            $('#kandungan_air_form').val('');
            $('#otot_form').val('');
            $('#tulang_form').val('');
            $('#kalori_form').val('');
            $('#suhu_form').val('');
            $('#spo2_form').val('');
            $('#pr_form').val('');
            $('#sistolik_form').val('');
            $('#diastolik_form').val('');
            $('#keterangan_form').val('');
            $('.select2').select2({allowClear: true, placeholder: "Pilih Karyawan"});
        })

        $('#tambahitem').click(function(e) {
            var tgl_cek_form = $('#tgl_cek_form').val();
            var karyawan_id_form = $('#karyawan_id_form').val();
            var karyawan_text_form = $('#karyawan_id_form option:selected').text();
            var berat_form = $('#berat_form').val();
            var lemak_form = $('#lemak_form').val();
            var kandungan_air_form = $('#kandungan_air_form').val();
            var otot_form = $('#otot_form').val();
            var tulang_form = $('#tulang_form').val();
            var kalori_form = $('#kalori_form').val();
            var suhu_form = $('#suhu_form').val();
            var spo2_form = $('#spo2_form').val();
            var pr_form = $('#pr_form').val();
            var sistolik_form = $('#sistolik_form').val();
            var diastolik_form = $('#diastolik_form').val();
            var keterangan_form = $('#keterangan_form').val();

            $('#tgl_cek_form').val('');
            $('#karyawan_id_form').val('');
            $('#berat_form').val('');
            $('#lemak_form').val('');
            $('#kandungan_air_form').val('');
            $('#otot_form').val('');
            $('#tulang_form').val('');
            $('#kalori_form').val('');
            $('#suhu_form').val('');
            $('#spo2_form').val('');
            $('#pr_form').val('');
            $('#sistolik_form').val('');
            $('#diastolik_form').val('');
            $('#keterangan_form').val('');
            const day = new Date(tgl_cek_form).getDate();
            const month = (new Date(tgl_cek_form).getMonth() + 1).toString().padStart(2,"0");
            const year = new Date(tgl_cek_form).getFullYear();

            const date_format = day+"-"+month+"-"+year;
            var data = `<tr>
                <td></td>
                <td><input type="date" class="form-control d-none tgl_cek" name="tgl_cek[]" value="`+tgl_cek_form+`">`+date_format+`</td>
                <td><input type="text" class="form-control d-none karyawan_id" name="karyawan_id[]" value="`+karyawan_id_form+`">`+karyawan_text_form+`</td>
                <td><input type="text" class="form-control d-none berat" name="berat[]" value="`+berat_form+`">`+berat_form+` Kg</td>
                <td><input type="text" class="form-control d-none lemak" name="lemak[]" value="`+lemak_form+`">`+lemak_form+` gram</td>
                <td><input type="text" class="form-control d-none kandungan_air" name="kandungan_air[]" value="`+kandungan_air_form+`">`+kandungan_air_form+`%</td>
                <td><input type="text" class="form-control d-none otot" name="otot[]" value="`+otot_form+`">`+otot_form+` Kg</td>
                <td><input type="text" class="form-control d-none tulang" name="tulang[]" value="`+tulang_form+`">`+tulang_form+` Kg</td>
                <td><input type="text" class="form-control d-none kalori" name="kalori[]" value="`+kalori_form+`">`+kalori_form+` kkal</td>
                <td><input type="text" class="form-control d-none suhu" name="suhu[]" id="suhu"  value="`+suhu_form+`">`+suhu_form+` °C</td>
                <td><input type="text" class="form-control d-none spo2" name="spo2[]" id="spo2" value="`+spo2_form+`">`+spo2_form+`%</td>
                <td><input type="text" class="form-control d-none pr" name="pr[]" id="pr" value="`+pr_form+`">`+pr_form+` bpm</td>
                <td><input type="text" class="form-control d-none sistolik" name="sistolik[]" id="sistolik" value="`+sistolik_form+`">`+sistolik_form+` mmHg</td>
                <td><input type="text" class="form-control d-none diastolik" name="diastolik[]" id="diastolik" value="`+diastolik_form+`">`+diastolik_form+` mmHg</td>
                <td><textarea type="text" class="form-control d-none keterangan" name="keterangan[]" value="`+keterangan_form+`"></textarea>`+keterangan_form+`</td>
                <td><i class="fas fa-times text-danger" id="closetable"></i></td>
            </tr>`;
            if($('#tabel_berat > tbody > tr > td > .tgl_cek').length <= 0){
                $('#tabel_berat > tbody > tr').remove();
                $('#tabel_berat tbody').append(data);
            }else{
                $('#tabel_berat tbody tr:last').after(data);
            }
            numberRows($("#tabel_berat"));
        });
        $('#tabel_berat').on('click', '#closetable', function(e) {
            $(this).closest('tr').remove();
            numberRows($("#tabel_berat"));
            if($('#tabel_berat > tbody > tr').length <= 0){
                $('#tabel_berat tbody').append('<tr><td colspan="16">Data Belum Tersedia</td></tr>');
            }

        });

    })
    $('.select2').select2({allowClear: true, placeholder: "Pilih Karyawan"});
</script>
@endsection
