@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">GCU Tambah</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kesehatan.dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active"><a href="/kesehatan/bulanan/">Kesehatan Bulanan</a></li>
                    <li class="breadcrumb-item ">GCU Tambah</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop
@section('adminlte_css')
    <style>
        table {
            border-collapse: collapse;
            empty-cells: show;
        }

        td {
            position: relative;
        }

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

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
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
            @if (session()->has('success'))
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
                <div class="card-header">
                    <h6 class="card-title"></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">Informasi Umum</h5>
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label for="tgl_cek_form" class="col-lg-4 col-form-label align-right">Tanggal</label>
                                    <div class="col-lg-3">
                                        <input type="date" class="form-control tgl_cek_form col-form-label"
                                            name="tgl_cek_form" id="tgl_cek_form">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tgl_cek_form" class="col-lg-4 col-form-label align-right">Nama
                                        Karyawan</label>
                                    <div class="col-lg-4">
                                        <select class="form-control select2" style="width:100%" name="karyawan_id_form"
                                            id="karyawan_id_form">
                                            @foreach ($karyawan as $k)
                                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label for="glukosa_form" class="col-lg-4 col-md-12 col-form-label align-right">Hasil
                                        Cek GCU</label>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="row">
                                            <div class="input-group col-lg-4">
                                                <input type="text" class="form-control glukosa_form" name="glukosa_form"
                                                    id="glukosa_form" placeholder="Glucose">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">mg/dl</span>
                                                </div>
                                            </div>
                                            <div class="input-group col-lg-4">
                                                <input type="text" class="form-control kolestrol_form"
                                                    name="kolestrol_form" id="kolestrol_form" placeholder="Cholestrol">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">mg/dl</span>
                                                </div>
                                            </div>
                                            <div class="input-group col-lg-4">
                                                <input type="text" class="form-control asam_urat_form"
                                                    name="asam_urat_form" id="asam_urat_form" placeholder="Uric ACID">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">mg/dl</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="keterangan_form" class="col-lg-4 col-form-label align-right">Catatan</label>
                                    <div class="col-lg-4">
                                        <textarea type="text" class="form-control keterangan_form" name="keterangan_form" id="keterangan_form"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-warning btn-sm" id="btnreset">Reset Form</button>
                    <button type="button" class="btn btn-secondary btn-sm float-right" id="tambahitem">Tambahkan Ke
                        Draf</button>
                </div>
            </div>
            <form action="/kesehatan/bulanan/gcu/aksi_tambah" method="post">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Draf Pemeriksaan GCU (Glucose, Cholesterol, Uric ACID)</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-center" id="tabel_gcu">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama</th>
                                        <th>Glucose</th>
                                        <th>Cholesterol</th>
                                        <th>Uric Acid</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="8">Data Belum Tersedia</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="float-left"><a class="btn btn-danger rounded-pill" href="/kesehatan/bulanan"><i
                                    class="fas fa-times"></i>&nbsp;Batal</a></span>
                        <span class="float-right"><button class="btn btn-success rounded-pill" id="button_tambah" type="submit" disabled="true"><i
                                    class="fas fa-plus"></i>&nbsp;Tambah Data</button></span>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
@section('adminlte_js')
    <script>
        // $(document).ready(function() {
        //     $('#tabel_gcu').DataTable({
        //         "scrollX": true,
        //         "searching": false,
        //         "paging": false,
        //         "lengthChange": false,
        //         "info": false
        //     });
        // });
    </script>
    <script>
        $(function() {
            function numberRows($t) {
                var c = 0 - 1;
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;
                    $(el).find('.tgl_cek').attr('name', 'tgl_cek[' + j + ']');
                    $(el).find('.karyawan_id').attr('name', 'karyawan_id[' + j + ']');
                    $(el).find('.glukosa').attr('name', 'glukosa[' + j + ']');
                    $(el).find('.kolesterol').attr('name', 'kolesterol[' + j + ']');
                    $(el).find('.asam_urat').attr('name', 'asam_urat[' + j + ']');
                    $(el).find('.keterangan').attr('name', 'keterangan[' + j + ']');



                });
            }

            $('#tambahitem').click(function(e) {
                var tgl_cek_form = $('#tgl_cek_form').val();
                var karyawan_id_form = $('#karyawan_id_form').val();
                var karyawan_id_text = $('#karyawan_id_form option:selected').text();
                var glukosa_form = $('#glukosa_form').val();
                var kolestrol_form = $('#kolestrol_form').val();
                var asam_urat_form = $('#asam_urat_form').val();
                var keterangan_form = $('#keterangan_form').val();

                if (tgl_cek_form != '' && glukosa_form != '' && kolestrol_form != '' && asam_urat_form != '' && karyawan_id_form != "") {
                    $('#tgl_cek_form').val('');
                    $('#karyawan_id_form').val('');
                    $('#glukosa_form').val('');
                    $('#kolestrol_form').val('');
                    $('#asam_urat_form').val('');
                    $('#keterangan_form').val('');

                    const day = (new Date(tgl_cek_form).getDate() + 1).toString().padStart(2,"0");
                    const month = (new Date(tgl_cek_form).getMonth() + 1).toString().padStart(2,"0");
                    const year = new Date(tgl_cek_form).getFullYear();

                    const date_format = day+"-"+month+"-"+year;

                    var data = `<tr>
                        <td>1</td>
                        <td><input type="date" class="form-control d-none tgl_cek" name="tgl_cek[]" value="` + tgl_cek_form +
                            `">` + date_format + `</td>
                        <td><input type="date" class="form-control d-none karyawan_id" name="karyawan_id[]" value="` +
                            karyawan_id_form + `">` + karyawan_id_text + `</td>
                        <td><input type="text" class="form-control d-none glukosa" name="glukosa[]" value="` + glukosa_form +
                            `">` + glukosa_form + `</td>
                        <td><input type="text" class="form-control d-none kolesterol" name="kolesterol[]" value="` +
                            kolestrol_form + `">` + kolestrol_form + `</td>
                        <td><input type="text" class="form-control d-none asam_urat" name="asam_urat[]" value="` +
                            asam_urat_form + `">` + asam_urat_form + `</td>
                        <td><textarea type="text" class="form-control d-none keterangan" name="keterangan[]" value="` +
                            keterangan_form + `"></textarea>` + keterangan_form + `</td>
                        <td><i class="fas fa-times text-danger" id="closetable"></i></td>
                    </tr>`;
                    if ($('#tabel_gcu > tbody > tr > td > .tgl_cek').length <= 0) {
                        $('#tabel_gcu > tbody > tr').remove();
                        $('#tabel_gcu tbody').append(data);
                    } else {
                        $('#tabel_gcu tbody tr:last').after(data);
                    }
                    numberRows($("#tabel_gcu"));
                    $('#button_tambah').attr('disabled', false);
                } else {
                    swal.fire(
                        'Gagal',
                        'Mohon Lengkapi form untuk menambah Draft Data',
                        'warning'
                    );
                }

            });
            $('#tabel_gcu').on('click', '#closetable', function(e) {
                $(this).closest('tr').remove();
                console.log($('#tabel_gcu > tbody > tr').length);
                numberRows($("#tabel_gcu"));
                if ($('#tabel_gcu > tbody > tr').length <= 0) {
                    $('#button_tambah').attr('disabled', true);
                    $('#tabel_gcu tbody').append('<tr><td colspan="16">Data Belum Tersedia</td></tr>');
                }

            });

        })
        $('.select2').select2({
            allowClear: true,
            placeholder: "Pilih Karyawan"
        });
    </script>
@endsection
