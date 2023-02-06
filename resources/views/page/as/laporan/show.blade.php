@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Laporan</h1>
@stop

@section('adminlte_css')
<style>
     td.dt-control {
        background: url("/assets/image/logo/plus.png") no-repeat center center;
        cursor: pointer;
        background-size: 15px 15px;
    }
    tr.shown td.dt-control {
        background: url("/assets/image/logo/minus.png") no-repeat center center;
        background-size: 15px 15px;
    }

    .filter {
        margin: 5px;
    }

    .hide {
        display: none !important;
    }

    .align-center {
        text-align: center;
    }

    .nowrap-text {
        white-space: nowrap;
    }

    .childrowbg{
        background-color: #E8E8E8;
    }

    @media screen and (min-width: 1440px) {

        section {
            font-size: 14px;
        }

        #detailmodal {
            font-size: 14px;
        }

        .btn {
            font-size: 12px;
        }
    }

    @media screen and (max-width: 1439px) {

        label,
        .row {
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
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <div class="card-title">Pencarian</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <!-- <form method="POST" action="/api/qc/so/laporan/create"> -->
                                <div class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="tgl_awal" class="col-form-label col-5" style="text-align: right">Tanggal Awal</label>
                                        <div class="col-2">
                                            <input type="date" class="form-control col-form-label @error('tgl_awal') is-invalid @enderror" id="tgl_awal" name="tgl_awal" />
                                            <div class="invalid-feedback" id="msgtgl_awal">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tgl_akhir" class="col-form-label col-5" style="text-align: right">Tanggal Akhir</label>
                                        <div class="col-2">
                                            <input type="date" class="form-control col-form-label @error('tgl_akhir') is-invalid @enderror" id="tgl_akhir" name="tgl_akhir"  readonly="true"/>
                                            <div class="invalid-feedback" id="msgtgl_akhir">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-5"></div>
                                        <div class="col-4">
                                            <span class="float-right filter"><button type="submit" class="btn btn-success" id="btncetak" disabled>Cetak</button></span>
                                            <span class="float-right filter"><button type="button" class="btn btn-outline-danger" id="btnbatal">Batal</button></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- </form> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row hide" id="lapform">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Laporan Keluhan</h5>
                        <div class="table-responsive">
                            <a id="exportbutton" href=""><button class="btn btn-info" id="btnexport"><i class="far fa-file-excel fa-fw"></i> Export</button></a>
                            <table class="table table-hover" id="showtable" style="width:100%">
                                <thead style="text-align: center;">
                                    <tr>
                                        <th>No</th>
                                        <th>No Retur</th>
                                        <th>PIC</th>
                                        <th>Customer</th>
                                        <th>Tgl Retur</th>
                                        <th>Keterangan</th>
                                        <th width="10%">Progress Perbaikan</th>
                                        <th width="10%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('adminlte_js')
<script src="{{ asset('assets/rowgroup/dataTables.rowGroup.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/rowgroup/rowGroup.bootstrap4.min.css') }}">

<script src="{{ asset('assets/button/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/button/jszip.min.js') }}"></script>
<script src="{{ asset('assets/button/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/button/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/button/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/button/buttons.print.min.js') }} "></script>
<link rel="stylesheet" href="{{ asset('assets/button/buttons.bootstrap4.min.css') }}">

<script>
    $(function() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        $("#tgl_awal").attr("max", today);
        $("#tgl_akhir").attr("max", today);

        $('#tgl_awal').on('keyup change', function() {
            $("#tgl_akhir").val("");
            $("#btncetak").removeAttr('disabled');
            if ($(this).val() != "") {
                $('#tgl_akhir').removeAttr('readonly');
                $("#tgl_akhir").attr("min", $(this).val())
                if ($('#tgl_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#tgl_akhir").val("");
                $("#btncetak").attr('disabled', true);
            }
        });

        $('#tgl_akhir').on('keyup change', function() {
            if ($(this).val() != "") {
                if ($('#tgl_awal').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $("#btnbatal").on('click', function() {
            $("#btncetak").attr('disabled', true);
            $('#tgl_awal').val('');
            $('#tgl_awal').attr('readonly', false);
            $('#tgl_akhir').val('');
            $('#tgl_akhir').attr('readonly', true);
            $('#lapform').addClass('hide');
        });

        $("#btncetak").on('click', function(e) {
            e.preventDefault();
            $("#btncetak").attr('disabled', true);
            $('#lapform').removeClass('hide');

            var tgl_awal = $('#tgl_awal').val();
            var tgl_akhir = $('#tgl_akhir').val();
            $('#exportbutton').attr('href', '/api/as/retur/laporan/'+tgl_awal+'/'+tgl_akhir);
            table(tgl_awal, tgl_akhir);
        });

        function no_kolom(table){
                table.on('order.dt search.dt', function() {
                    table.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }


        var showtable = "";
        function table(tgl_awal, tgl_akhir) {
            showtable = $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/as/retur/data/laporan/' + tgl_awal + '/' + tgl_akhir ,
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                columns: [{
                        data: null,
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'no_retur',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: null,
                        className: 'nowrap-text align-center',
                        render: function(data, type, row){
                            return data.pic+'<br><small>'+data.telp_pic+'</small>'
                        }
                    },
                    {
                        data: 'customer',
                        className: 'nowrap-text align-center',
                    },
                    {
                        data: 'tgl_retur',
                        className: 'nowrap-text align-center'
                    },
                    {
                        data: 'ket',
                        className: 'align-center',
                    },
                    {
                        data: null,
                        className: 'align-center',
                        render: function(data, type, row){
                            return `<div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="`+data.prog_perbaikan+`"  style="width:`+data.prog_perbaikan+`%" aria-valuemin="0" aria-valuemax="100">`+data.prog_perbaikan+`%</div>
                                    </div>
                                    <small class="text-muted">Selesai</small>`;
                        }
                    },
                    {
                        data: null,
                        className: 'align-center',
                        render: function(data, type, row){
                            return `<div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="`+data.prog_pengiriman+`"  style="width:`+data.prog_pengiriman+`%" aria-valuemin="0" aria-valuemax="100">`+data.prog_pengiriman+`%</div>
                                    </div>
                                    <small class="text-muted">Selesai</small>`;
                        }
                    }
                ],
            });

            no_kolom(showtable)
        }
    });
</script>
@endsection
