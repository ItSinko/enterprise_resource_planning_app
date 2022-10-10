@extends('adminlte.page')
@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-lg-6 col-md-4 col-sm-4">
            <h1 class="m-0  text-dark">Tambah Karyawan Sakit</h1>
        </div><!-- /.col -->
        <div class="col-lg-6 col-md-8 col-sm-8">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('kesehatan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="\karyawan\sakit">Karyawan Sakit</a></li>
                <li class="breadcrumb-item active">Tambah Karyawan Sakit</li>
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
            <div class="col-lg-12">
                <form action="/karyawan/sakit/aksi_tambah" method="post" enctype="multipart/form-data" id="formkaryawansakit">
                 @csrf
                 <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h6 class="card-title">Detail Umum</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-horizontal">
                            <div class="form-group row">
                                <label for="tanggal" class="col-sm-5 col-form-label" style="text-align:right;">Tgl Pemeriksaan </label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control @error('tgl_cek') is-invalid @enderror" name="tgl" value="{{old('tgl_cek')}}" max="{{ date('Y-m-d') }}" placeholder="Analisa pemeriksaan" style="width:45%;">
                                </div>
                                <span role="alert" id="no_seri-msg"></span>
                            </div>
                            <div class="form-group row">
                                <label for="no_pemeriksaan" class="col-sm-5 col-form-label" style="text-align:right;">Karyawan Sakit</label>
                                <div class="col-sm-3">
                                    <select type="text" class="form-control @error('karyawan_id') is-invalid @enderror select2 karyawan_id" name="karyawan_id" id="karyawan_id">

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
                                <label for="no_pemeriksaan" class="col-sm-5 col-form-label" style="text-align:right;">Pemeriksa</label>
                                <div class="col-sm-3">
                                    <select type="text" class="form-control @error('pemeriksa_id') is-invalid @enderror select2 pemeriksa_id" name="pemeriksa_id" id="pemeriksa_id">

                                        @foreach($pengecek as $p)
                                        <option value="{{$p->id}}">{{$p->nama}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('karyawan_id'))
                                    <div class="text-danger">
                                        {{ $errors->first('karyawan_id')}}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                 <div class="card">
                    <div class="card-header card-primary card-outline">
                        <h6 class="card-title">Hasil Analisa</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-horizontal">

                            <div class="form-group row">
                                <label for="tanggal" class="col-sm-5 col-form-label" style="text-align:right;">Analisa</label>
                                <div class="col-sm-7">
                                    <textarea type="text" class="form-control @error('analisa') is-invalid @enderror" name="analisa" id="analisa" value="{{old('analisa')}}" placeholder="Analisa pemeriksaan" style="width:45%;"></textarea>
                                </div>
                                <span role="alert" id="no_seri-msg"></span>
                            </div>
                            <div class="form-group row">
                                <label for="tanggal" class="col-sm-5 col-form-label" style="text-align:right;">Diagnosa</label>
                                <div class="col-sm-7">
                                    <textarea type="text" class="form-control @error('diagnosa') is-invalid @enderror" name="diagnosa" id="diagnosa" value="{{old('diagnosa')}}" placeholder="Diagnosa pemeriksaan" style="width:45%;"></textarea>
                                </div>
                                <span role="alert" id="no_seri-msg"></span>
                            </div>
                            <div class="form-group row">
                                <label for="kondisi" class="col-sm-5 col-form-label" style="text-align:right;">Penanganan</label>
                                <div class="col-sm-7 col-form-label">
                                    <div class="icheck-success d-inline col-sm-4">
                                        <input type="radio" name="hasil_1" value="Terapi">
                                        <label for="no">
                                            Terapi
                                        </label>
                                    </div>
                                    <div class="icheck-warning d-inline col-sm-4">
                                        <input type="radio" name="hasil_1" value="Pengobatan">
                                        <label for="sample">
                                            Pengobatan
                                        </label>
                                    </div>
                                    <span class="invalid-feedback" role="alert" id="kondisi-msg"></span>
                                </div>
                            </div>
                            <div id="tipe_1" style="display:none">
                                <div class="form-group row">
                                    <label for="tanggal" class="col-sm-5 col-form-label" style="text-align:right;">Terapi</label>
                                    <div class="col-sm-4">
                                        <textarea type="text" class="form-control @error('terapi') is-invalid @enderror" id="terapi" value="{{old('terapi')}}" placeholder="Terapi yang digunakan" name="terapi"></textarea>
                                    </div>
                                    <span role="alert" id="no_seri-msg"></span>
                                </div>
                            </div>
                            <div id="tipe_2" style="display:none">
                                <div class="form-group row">
                                    <table class="table table-hover styled-table table-striped col-sm-12" id="obat" style="text-align: center;">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="25%">Obat</th>
                                                {{-- <th width="20%">Aturan</th> --}}
                                                <th width="50%">Aturan</th>
                                                <th width="15%">Jumlah</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <select class="form-control  obat_data " name="obat[]" id="0">
                                                    </select>
                                                </td>
                                                {{-- <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input aturan_obat" type="radio" name="aturan_obat[]" value="Sebelum Makan">
                                                        <label class="form-check-label">Sebelum Makan</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input aturan_obat" type="radio" name="aturan_obat[]" value="Sesudah Makan">
                                                        <label class="form-check-label">Sesudah Makan</label>
                                                    </div>
                                                </td> --}}
                                                <td>
                                                    <div class="form-check form-check-inline" style="width:20%">
                                                        <input class="form-check-input dosis_obat" type="radio" name="dosis_obat[]" value="1x1">
                                                        <label class="form-check-label" for="dosis_obat">
                                                            1x1 Hari
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline" style="width:20%">
                                                        <input class="form-check-input dosis_obat" type="radio" name="dosis_obat[]" value="2x1">
                                                        <label class="form-check-label" for="dosis_obat">
                                                            2x1 Hari
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline" style="width:50%">
                                                        <input class="form-check-input dosis_obat" type="radio" name="dosis_obat[]" value="2x1">
                                                        <div class="form-row align-items-center">
                                                            <div class="col-4">
                                                                <input type="text" class="form-control dosis_obat_custom_obat" name="dosis_obat_custom_obat[]" id="dosis_obat_custom_obat" placeholder="Obat" width="5%">
                                                            </div>
                                                            <div class="col-auto"><label for="">x</label></div>
                                                            <div class="col-4">
                                                                <input type="text" class="form-control dosis_obat_custom_hari" name="dosis_obat_custom_hari[]" id="dosis_obat_custom_" placeholder="Hari" width="5%">
                                                            </div>
                                                            <div class="col-auto"><label for="">Hari</label></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="bottom">
                                                    <div class="input-group">
                                                        <input type="number" class="form-control jumlah" name="jumlah[]" id="jumlah0" placeholder="Jumlah obat">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Pcs</span>
                                                        </div>
                                                    </div>
                                                    {{-- <small id="stok0" class="stok text-muted">Stok : - </small> --}}

                                                </td>

                                                <td style="text-align: right;">
                                                    <button name="add" type="button" id="tambahitem" class="btn btn-success"><i class="nav-icon fas fa-plus-circle"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kondisi" class="col-sm-5 col-form-label" style="text-align:right;">Tindak lanjut</label>
                                <div class="col-sm-7 col-form-label">
                                    <div class="icheck-success d-inline col-sm-4">
                                        <input type="radio" name="hasil_2" id="tindak_lanjut1" value="Lanjut bekerja">
                                        <label for="tindak_lanjut1">
                                            Lanjut bekerja
                                        </label>
                                    </div>
                                    <div class="icheck-warning d-inline col-sm-4">
                                        <input type="radio" name="hasil_2" id="tindak_lanjut2" value="Dipulangkan">
                                        <label for="tindak_lanjut2">
                                            Dipulangkan
                                        </label>
                                    </div>
                                    <div class="icheck-warning d-inline col-sm-4">
                                        <input type="radio" name="hasil_2" id="tindak_lanjut3" value="Istirahat">
                                        <label for="tindak_lanjut3">
                                            Istirahat
                                        </label>
                                    </div>
                                    <span class="invalid-feedback" role="alert" id="kondisi-msg"></span>
                                </div>
                            </div>
                            <div id="tindak_lanjut_istirahat" style="display:none">
                                <div class="form-group row">
                                    <label for="tanggal" class="col-sm-5 col-form-label" style="text-align:right;">Tanda Vital</label>
                                    <div class="col-sm-4">
                                        <textarea type="text" class="form-control @error('tanda_vital') is-invalid @enderror" id="tanda_vital" value="{{old('tanda_vital')}}" placeholder="Tanda Vital" name="tanda_vital"></textarea>
                                    </div>
                                    <span role="alert" id="no_seri-msg"></span>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="kondisi" class="col-sm-4 col-form-label" style="text-align:right;"></label>
                                <div class="col-sm-8" style="margin-top:7px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                        <label class="" for=" flexCheckDefault">
                                            Surat Keterangan Istirahat
                                        </label>
                                    </div>
                                    <span class="invalid-feedback" role="alert" id="kondisi-msg"></span>
                                </div>
                            </div> -->
                        </div>
                    </div>
                 </div>
                 <div class="row mb-3">
                    <div class="col-6"><a class="btn btn-danger float-left" href="/karyawan/sakit">Batal</a></div>
                    <div class="col-6"><button class="btn btn-primary float-right" type="submit" id="button_tambah" disabled="true">Simpan</button></div>
                 </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
@section('adminlte_js')
<script>
    var where = [''];
    $(document).ready(function() {
        $('#formkaryawansakit').on('keyup change', function(){
            if($('input[name="tgl"]').val() != "" && $('#karyawan_id').val() != "" && $('#pemeriksa_id').val() != "" && $('#analisa').val() != "" && $('#diagnosa').val() != "" && $('input[name="hasil_1"][type="radio"]:checked').val() != "" && $('input[name="hasil_2"][type="radio"]:checked').val() != ""){
                if(($('input[type="radio"][name="hasil_1"]:checked').val() == "Terapi" && $('#terapi').val() != "") || $('input[name="hasil_1"]:checked').val() == "Pengobatan"){
                    $('#button_tambah').attr('disabled', false);
                }
                else{
                    $('#button_tambah').attr('disabled', true);
                }
            }else{
                $('#button_tambah').attr('disabled', true);
            }
        });
        $('.select2').select2();
        select_data();

        function numberRows($t) {
            var c = 0 - 1;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('input.aturan_obat:radio').attr('name', 'aturan_obat[' + j + ']');
                $(el).find('input.dosis_obat:radio').attr('name', 'dosis_obat[' + j + ']');
                $(el).find('.jumlah').attr('name', 'jumlah[' + j + ']');
                $(el).find('.dosis_obat_custom_obat').attr('name', 'dosis_obat_custom_obat[' + j + ']');
                $(el).find('.dosis_obat_custom_hari').attr('name', 'dosis_obat_custom_hari[' + j + ']');
                $(el).find('.jumlah').attr('id', 'jumlah' + j + '');
                $(el).find('.stok').attr('id', 'stok' + j + '');
                $(el).find('.obat').attr('name', 'obat[' + j + ']');
                $(el).find('.obat_data').attr('id', j);
                $(el).find('.obat_data').attr('name', 'obat['+j+']');
                select_data();
            });
        }
        $('#obat').on("change", ".obat_data", function(i) {
            var id = jQuery(this).val();
            var index = $(this).attr('id');
            //  console.log(where);
            where.splice(index, index, id);
            if (index == 0) {
                where.splice(1, 1);
            }
            console.log(where);
            $.ajax({
                url: '/obat/data/' + id,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $("#stok" + index).text('Stok : ' + data[0].stok);
                    $("#jumlah" + index).prop('max', data[0].stok);
                    $("#jumlah" + index).prop('min', 1);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        })
        $('#tambahitem').click(function(e) {
            var data = `<tr>
                                                                <td>1</td>
                                                                <td>
                                                                    <select class="form-control  obat_data " id="0" name="obat[]">
                                                                    </select>
                                                                </td>
                                                                {{-- <td>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input aturan_obat" type="radio" name="aturan_obat[]" value="Sebelum Makan">
                                                                        <label class="form-check-label">Sebelum Makan</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input aturan_obat" type="radio" name="aturan_obat[]" value="Sesudah Makan">
                                                                        <label class="form-check-label">Sesudah Makan</label>
                                                                    </div>
                                                                </td> --}}
                                                                <td>
                                                                    <div class="form-check form-check-inline" style="width:20%">
                                                                        <input class="form-check-input dosis_obat" type="radio" name="dosis_obat[]" value="1x1">
                                                                        <label class="form-check-label" for="dosis_obat">
                                                                            1x1 Hari
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline" style="width:20%">
                                                                        <input class="form-check-input dosis_obat" type="radio" name="dosis_obat[]" value="2x1">
                                                                        <label class="form-check-label" for="dosis_obat">
                                                                            2x1 Hari
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline" style="width:50%">
                                                                        <input class="form-check-input dosis_obat" type="radio" name="dosis_obat[]" value="2x1">
                                                                        <div class="form-row align-items-center">
                                                                            <div class="col-4">
                                                                                <input type="text" class="form-control dosis_obat_custom_obat" name="dosis_obat_custom_obat[]" id="dosis_obat_custom_obat" placeholder="Obat" width="5%">
                                                                            </div>
                                                                            <div class="col-auto"><label for="">x</label></div>
                                                                            <div class="col-4">
                                                                                <input type="text" class="form-control dosis_obat_custom_hari" name="dosis_obat_custom_hari[]" id="dosis_obat_custom_" placeholder="Hari" width="5%">
                                                                            </div>
                                                                            <div class="col-auto"><label for="">Hari</label></div>
                                                                        </div>
                                                                    </div>
                                                                    {{-- <div class="form-check form-check-inline">
                                                                        <input class="form-check-input dosis_obat" type="radio" name="dosis_obat[]" id="custom_radio">
                                                                        <label class="form-check-label" for="sample">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control dosis_obat_custom" name="dosis_obat_custom[]" id="dosis_obat_custom" placeholder="Jumlah obat x hari">
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text">Hari</span>
                                                                                </div>
                                                                            </div>
                                                                        </label>
                                                                    </div> --}}
                                                                </td>
                                                                <td class="bottom">
                                                                    <div class="input-group">
                                                                        <input type="number" class="form-control jumlah" name="jumlah[]" id="jumlah0" placeholder="Jumlah obat">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">Pcs</span>
                                                                        </div>
                                                                    </div>
                                                                    {{-- <small id="stok0" class="stok text-muted">Stok : - </small> ---}}
                                                                </td>
                                                                <td style="text-align: right;">
                                                                <button type="button" class="btn btn-danger karyawan-img-small" style="border-radius:50%;" id="closetable"><i class="fas fa-times-circle"></i></button>
                                                                </td>
                        </tr>`;
            $('#obat tr:last').after(data);
            numberRows($("#obat"));
        });


        $('#obat').on('click', '#closetable', function(e) {
            var id = $(this).closest('tr').find('.obat_data').attr('id');
            $(this).closest('tr').remove();
            where.splice(id, id);
            numberRows($("#obat"));
        });


        function select_data() {
            if (where == '') {
                where = ['99999999999'];
            }
            //console.log(where);
            $('.obat_data').select2({
                placeholder: "Pilih Produk",
                ajax: {
                    dataType: 'json',
                    delay: 250,
                    type: 'GET',
                    url: '/obat/data/select/' + where,
                    data: function(params) {
                        return {
                            term: params.term
                        }
                    },
                    processResults: function(data) {
                        console.log(data);
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.nama,
                                    stok: obj.stok
                                };
                            })
                        };
                    },

                },
                templateResult: function(data) {
                        var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.stok + `">` + data.stok + `</span></div>`);
                        return $span;
                    }
                    // ,
                    // templateSelection: function(data) {
                    //     var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.stok + `">` + data.stok + `</span></div>`);
                    //     return $span;
                    // }
            });
        }

        $('input[name=dosis_obat]').on("click", function() {
            check = $("#custom_radio").is(":checked");
            if (check) {
                $('input[id=dosis_obat_custom]').prop("required", true);
            } else {
                $('input[id=dosis_obat_custom]').prop("required", false);
                $('#dosis_obat_custom').val('');
            }
        });
        $('input[name=hasil_1]').prop("required", true);
        $('input[name=hasil_2]').prop("required", true);
        $('input[name=hasil_2]').on('change', function(){
            if($('input[name=hasil_2]:checked').val() == 'Istirahat'){
                $('#tindak_lanjut_istirahat').removeAttr('style');
            }else{
                $('#tindak_lanjut_istirahat').css('display', 'none');
            }
        })

        $('input[type=radio][name=hasil_1]').on('change', function() {
            if (this.value == 'Terapi') {
                $('#obat').val(null).trigger('change');
                $("#tipe_1").removeAttr("style");
                $("#tipe_2").css('display', 'none');
                $('#dosis_obat_custom').val('');
                $('#jumlah').val('');
                $('select[name=obat_id]').prop("required", false);
                $('input[name=jumlah]').prop("required", false);
                $('input[name=aturan_obat]').prop("required", false);
                $('input[name=dosis_obat]').prop("required", false);
                $('input[name=aturan_obat]').prop("checked", false);
                $('input[name=dosis_obat]').prop("checked", false);
                $('textarea[id=terapi]').prop("required", true);
                $('#obat').val(null).trigger('change');
                $('#stok').text('');
            } else {
                $('input[name=jumlah]').prop("required", true);
                $('select[name=obat_id]').prop("required", true);
                $('input[name=dosis_obat]').prop("required", true);
                $('input[name=aturan_obat]').prop("required", true);
                $('#obat').val(null).trigger('change');
                $("#tipe_1").css('display', 'none')
                $("#tipe_2").removeAttr("style");
                $('textarea[id=terapi]').prop("required", false);
                select_data();
            }
        });
            $("#diagnosa").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        dataType: 'json',
                        url: "/kesehatan/riwayat_penyakit/data",
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.nama,
                                    id: el.id
                                };
                            });
                            response(transformed.slice(0, 10));
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });


            $("#analisa").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        dataType: 'json',
                        url: "/kesehatan/riwayat_analisa/data",
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.analisa,
                                    id: el.id
                                };
                            });
                            response(transformed.slice(0, 10));
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });
    });

</script>
@stop
