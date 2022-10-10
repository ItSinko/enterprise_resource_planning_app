@extends('adminlte.page')
@section('title', 'Beta Version')
@section('content_header')
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
                <div class="card">
                    <div class="card-header bg-success">
                        <div class="card-title"><i class="fas fa-plus-circle"></i>&nbsp;Tambah</div>
                    </div>
                    <div class="card-body">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label for="no_pemeriksaan" class="col-sm-4 col-form-label" style="text-align:right;">Pilih Form</label>
                                            <div class="col-sm-8">
                                                <select type="text" class="form-control @error('form') is-invalid @enderror select2" name="form" style="width:45%;" id="form">
                                                    <option value="0">Pilih Data</option>
                                                    <option value="berat">Berat Badan</option>
                                                    <option value="gcu">GCU (Glucose, Cholesterol, Uric ACID)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12" id="berat" style="display:none">
            <div class="col-lg-12">
                <form action="/kesehatan_bulanan_berat/aksi_tambah" method="post">
                    {{ csrf_field() }}
                    <div class="card">
                        <div class="card-header bg-success">
                            <div class="card-title"><i class="fas fa-plus-circle"></i>&nbsp;Pengukuran Berat Badaan</div>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label for="no_pemeriksaan" class="col-sm-4 col-form-label" style="text-align:right;">Divisi</label>
                                                <div class="col-sm-8">
                                                    <select type="text" class="form-control @error('divisi') is-invalid @enderror select2" name="divisi" style="width:45%;">
                                                        <option value=""></option>
                                                        @foreach ($divisi as $d)
                                                        <option value="{{$d->id}}">{{$d->nama}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('divisi'))
                                                    <div class="text-danger">
                                                        {{ $errors->first('divisi')}}
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="no_pemeriksaan" class="col-sm-4 col-form-label" style="text-align:right;">Tanggal</label>
                                                <div class="col-sm-8">
                                                    <input type="date" class="form-control @error('tgl') is-invalid @enderror " name="tgl_cek" style="width:45%;" id="tgl">
                                                    @if($errors->has('tgl_cek'))
                                                    <div class="text-danger">
                                                        {{ $errors->first('tgl_cek')}}
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <table id="tabel_berat" class="table table-hover styled-table table-striped">
                                            <thead style="text-align: center;">
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th colspan="6">Komposisi Tubuh</th>
                                                    <th></th>
                                                </tr>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal</th>
                                                    <th>Nama</th>
                                                    <th>Tinggi</th>
                                                    <th>Berat</th>
                                                    <th>Lemak</th>
                                                    <th>Kandungan air</th>
                                                    <th>Otot</th>
                                                    <th>Tulang</th>
                                                    <th>Kalori</th>
                                                    <th>Catatan</th>
                                                </tr>
                                            </thead>
                                            <tbody style="text-align: center;">
                                                <tr>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="float-left"><a class="btn btn-danger rounded-pill" href="/kesehatan_mingguan"><i class="fas fa-times"></i>&nbsp;Batal</a></span>
                            <span class="float-right"><button class="btn btn-success rounded-pill" id="button_tambah"><i class="fas fa-plus"></i>&nbsp;Tambah Data</button></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-12" id="gcu" style="display:none">
            <div class="col-lg-12">
                <form action="/kesehatan_bulanan_gcu/aksi_tambah" method="post">
                    {{ csrf_field() }}
                    <div class="card">
                        <div class="card-header bg-success">
                            <div class="card-title"><i class="fas fa-plus-circle"></i>&nbsp;Pemeriksaan GCU (Glucose, Cholesterol, Uric ACID) </div>
                        </div>
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label for="no_pemeriksaan" class="col-sm-4 col-form-label" style="text-align:right;">Divisi</label>
                                                <div class="col-sm-8">
                                                    <select type="text" class="form-control @error('divisi') is-invalid @enderror select2" name="divisi" style="width:45%;">
                                                        <option value=""></option>
                                                        @foreach ($divisi as $d)
                                                        <option value="{{$d->id}}">{{$d->nama}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="no_pemeriksaan" class="col-sm-4 col-form-label" style="text-align:right;">Tanggal</label>
                                                <div class="col-sm-8">
                                                    <input type="date" class="form-control @error('tgl') is-invalid @enderror " name="tgl_cek" style="width:45%;" id="tgls">
                                                    @if($errors->has('tgl_cek'))
                                                    <div class="text-danger">
                                                        {{ $errors->first('tgl_cek')}}
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <table id="tabel_gcu" class="table table-hover styled-table table-striped">
                                            <thead style="text-align: center;">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Tanggal</th>
                                                    <th>Nama</th>
                                                    <th>Glucose</th>
                                                    <th>Cholesterol</th>
                                                    <th>Uric Acid</th>
                                                    <th>Catatan</th>
                                                </tr>
                                            </thead>
                                            <tbody style="text-align: center;">
                                                <tr>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="float-left"><a class="btn btn-danger rounded-pill" href="/kesehatan/bulanan"><i class="fas fa-times"></i>&nbsp;Batal</a></span>
                            <span class="float-right"><button class="btn btn-success rounded-pill" id="button_tambah" type="submit" disabled="true"><i class="fas fa-plus"></i>&nbsp;Tambah Data</button></span>
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
    $('#form').change(function() {
        var form = $(this).val();
        if (form == 'berat') {
            $("#gcu").hide('1000');
            $("#detail_gagal").hide('1000');
            $("#berat").show('1000');
        } else if (form == 'gcu') {
            $("#detail_gagal").hide('1000');
            $("#berat").hide('1000');
            $("#gcu").show('1000');
        } else {
            $("#berat").hide('1000');
            $("#detail_gagal").show('1000');
            $("#gcu").hide('1000');
        }
    });
</script>

<script>
    $(document).ready(function() {
        $('select[name="divisi"]').on('change', function() {
            var id = jQuery(this).val();
            console.log(id);
            if (id) {
                $.ajax({
                    url: '/kesehatan_harian/tambah/data/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        // console.log(data.length);
                        if (data.length > 0) {
                            jQuery('#tabel_berat > tbody >tr').empty();
                            var x = 0;
                            var no = 0;
                            $.each(data, function(key, value) {
                                no++;
                                //console.log(value['kesehatan_awal']['tinggi']);
                                $('input[name=tgl]').val('');
                                $('#tgl').change(function() {
                                    $('#date' + value['id'] + '').val($(this).val());
                                });
                                $('#tabel_berat').append(`<tr> <td>` + no + `</td>
                                <td><input id="date` + value[`id`] + `" type="date" class="form-control" readonly><input type="text" class="form-control d-none" name="kesehatan_awal_id[` + x + `]" value="` + value['kesehatan_awal']['id'] + `"></td>
                                                         <td>` + value[`nama`] + `</td>
                                                         <td>
                                                         <div class="input-group mb-3">
                                                         <input type="number" class="form-control d-none" name="karyawan_id[` + x + `]" value="` + value[`id`] + `">
                                                         <input type="text" class="form-control" name="tinggi[` + x + `]" readonly  value="` + value['kesehatan_awal']['tinggi'] + `" id="tinggi">
                                                         <div class="input-group-append">
                                                         <span class="input-group-text">Cm</span>
                                                         </div>
                                                         </div>
                                                         </td>
                                                         <td>
                                                         <div class="input-group mb-3">
                                                         <input type="text" class="form-control" name="berat[` + x + `]" id="berat">
                                                         <div class="input-group-append">
                                                            <span class="input-group-text">Kg</span>
                                                        </div>
                                                          </div>
                                                          <small id="status_bmi" class="form-text text-muted"></small>
                                                         </td>
                                                        <td>
                                                        <div class="input-group mb-3">
                                                         <input type="text" class="form-control" name="lemak[` + x + `]" >
                                                         <div class="input-group-append">
                                                            <span class="input-group-text">gram</span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                        <td>
                                                        <div class="input-group mb-3">
                                                         <input type="text" class="form-control" name="kandungan_air[` + x + `]" >
                                                         <div class="input-group-append">
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                        <td>
                                                       <div class="input-group mb-3">
                                                         <input type="text" class="form-control" name="otot[` + x + `]" >
                                                         <div class="input-group-append">
                                                            <span class="input-group-text">Kg</span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                        <td>
                                                        <div class="input-group mb-3">
                                                         <input type="text" class="form-control" name="tulang[` + x + `]" >
                                                         <div class="input-group-append">
                                                            <span class="input-group-text">Kg</span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                        <td>
                                                        <div class="input-group mb-3">
                                                         <input type="text" class="form-control" name="kalori[` + x + `]" >
                                                         <div class="input-group-append">
                                                            <span class="input-group-text">kkal</span>
                                                        </div>
                                                        </div>
                                                        </td>
                                                        <td>
                                                        <div class="input-group mb-3">
                                                         <textarea type="text" class="form-control" name="keterangan[` + x + `]" ></textarea>
                                                        </td>
                                                        </tr>`);
                                x++;
                            });
                        } else {
                            jQuery('#tabel_berat > tbody >tr').empty();
                            $('#tabel_berat').append('<tr><td colspan="8">Data yang anda pilih tidak ada</td></tr>');
                        }
                    },
                });
            } else {
                alert('Harap memuat ulang');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('select[name="divisi"]').on('change', function() {
            var id = jQuery(this).val();
            console.log(id);
            if (id) {
                $.ajax({
                    url: '/kesehatan_harian/tambah/data/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data.length);
                        if (data.length > 0) {
                            jQuery('#tabel_gcu > tbody >tr').empty();
                            var x = 0;
                            var no = 0;
                            $.each(data, function(key, value) {
                                no++;
                                $('input[name=tgl_cek]').val('');
                                $('#tgls').change(function() {
                                    $('#dates' + value['id'] + '').val($(this).val());
                                });
                                $('#tabel_gcu').append(`<tr> <td>` + no + `</td>
                                <td><input id="dates` + value[`id`] + `" type="date" class="form-control" readonly></td>
                                                         <td>` + value[`nama`] + `</td>
                                                         <td>
                                                         <div class="input-group mb-3">
                                                         <input type="number" class="form-control d-none" name="karyawan_id[` + x + `]" value="` + value[`id`] + `">
                                                         <input type="text" class="form-control" name="glukosa[` + x + `]" id="glukosa` + value[`id`] + `">
                                                         <div class="input-group-append">
                                                            <span class="input-group-text">mg/dl</span>
                                                        </div>
                                                          </div>
                                                          <small id="glukosa_status` + value[`id`] + `" class="form-text text-muted"></small>
                                                        </td>
                                                        <td>
                                                         <div class="input-group mb-3">
                                                         <input type="text" class="form-control" name="kolesterol[` + x + `]" id="kolesterol` + value[`id`] + `">
                                                         <div class="input-group-append">
                                                            <span class="input-group-text">mg/dl</span>
                                                        </div>
                                                          </div>
                                                          <small id="kolesterol_status` + value[`id`] + `" class="form-text text-muted"></small>
                                                        </td>
                                                        <td>
                                                         <div class="input-group mb-3">
                                                         <input type="text" class="form-control" name="asam_urat[` + x + `]" id="asam_urat` + value[`id`] + `">
                                                         <div class="input-group-append">
                                                            <span class="input-group-text">mg/dl</span>
                                                        </div>
                                                          </div>
                                                          <small id="asam_urat_status` + value[`id`] + `" class="form-text text-muted"></small>
                                                        </td>
                                                        <td>
                                                         <textarea type="text" class="form-control" name="keterangan[` + x + `]"></textarea>
                                                        </td>
                                                        </tr>`);
                                x++;
                                $('#glukosa' + value['id'] + '').keyup(function() {
                                    var value1 = parseFloat($('#glukosa' + value['id'] + '').val());
                                    if (value1 >= 200) {
                                        $('#glukosa_status' + value['id'] + '').text('Diabetes');
                                    } else if (value1 >= 140 && value1 <= 199) {
                                        $('#glukosa_status' + value['id'] + '').text('Pra Diabetes');
                                    } else {
                                        $('#glukosa_status' + value['id'] + '').text('Normal');
                                    }
                                });
                                $('#kolesterol' + value['id'] + '').keyup(function() {
                                    var value1 = parseFloat($('#kolesterol' + value['id'] + '').val());
                                    if (value1 >= 239) {
                                        $('#kolesterol_status' + value['id'] + '').text('Bahaya');
                                    } else if (value1 >= 200 && value1 <= 239) {
                                        $('#kolesterol_status' + value['id'] + '').text('Hati hati');
                                    } else {
                                        $('#kolesterol_status' + value['id'] + '').text('Normal');
                                    }
                                });
                                $('#asam_urat' + value['id'] + '').keyup(function() {
                                    var value1 = parseFloat($('#asam_urat' + value['id'] + '').val());
                                    if (value1 >= 2 && value1 <= 7.5) {
                                        $('#asam_urat_status' + value['id'] + '').text('Normal');
                                    } else {
                                        $('#asam_urat_status' + value['id'] + '').text('Asam Urat');
                                    }
                                });
                            });
                        } else {
                            jQuery('#tabel_gcu > tbody >tr').empty();
                            $('#tabel_gcu').append('<tr><td colspan="8">Data yang anda pilih tidak ada</td></tr>');
                        }
                    },
                });
            } else {
                alert('Harap memuat ulang');
            }
        });
    });
</script>
@endsection
