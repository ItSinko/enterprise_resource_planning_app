@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Laporan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '15')
                        <li class="breadcrumb-item"><a href="{{ route('logistik.dashboard') }}">Beranda</a></li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                    @endif
                    <li class="breadcrumb-item active">Laporan</li>

                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
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

        td.dt-child-control {
            background: url("/assets/image/logo/arrow_down.png") no-repeat center center;
            cursor: pointer;
            background-size: 15px 15px;
        }

        tr.shown-child td.dt-child-control {
            background: url("/assets/image/logo/arrow_up.png") no-repeat center center;
            background-size: 15px 15px;
        }

        .filter {
            margin: 5px;
        }

        .childrowbg {
            background-color: #E8E8E8;
        }

        .hide {
            display: none !important;
        }

        .nowrap-text {
            white-space: nowrap;
        }

        .minimizechar {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 30ch;
        }

        .align-center {
            text-align: center;
        }

        .bgcolor {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }


        @media screen and (min-width: 1440px) {
            section {
                font-size: 14px;
            }

            .dropdown-item {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 1439px) {
            section {
                font-size: 12px;
            }

            .dropdown-item {
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
                                    <!-- <form method="POST" action="/api/laporan/create"> -->
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label for="pengiriman" class="col-form-label col-5"
                                                style="text-align: right">Pengiriman</label>
                                            <div class="col-5 col-form-label">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="pengiriman"
                                                        id="jasa_pengiriman1" value="ekspedisi" />
                                                    <label class="form-check-label" for="jasa_pengiriman1">Ekspedisi</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="pengiriman"
                                                        id="jasa_pengiriman2" value="nonekspedisi" />
                                                    <label class="form-check-label" for="jasa_pengiriman2">Non
                                                        Ekspedisi</label>
                                                </div>
                                                <div class="feedback" id="msgjasa_pengiriman">
                                                    <small class="text-muted">Jasa Pengiriman boleh dikosongi</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row hide" id="ekspedisi">
                                            <label for="" class="col-form-label col-5"
                                                style="text-align: right">Ekspedisi</label>
                                            <div class="col-5">
                                                <select class="select2 select-info form-control ekspedisi_id"
                                                    name="ekspedisi_id" id="ekspedisi_id" style="width:100%;">
                                                    <option value=""></option>
                                                </select>
                                                <div class="feedback" id="msgjasa_pengiriman">
                                                    <small class="text-muted">Nama Ekspedisi boleh dikosongi</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tanggal_mulai" class="col-form-label col-5"
                                                style="text-align: right">Tanggal Mulai</label>
                                            <div class="col-2">
                                                <input type="date"
                                                    class="form-control col-form-label @error('tanggal_mulai') is-invalid @enderror"
                                                    id="tanggal_mulai" name="tanggal_mulai" />
                                                <div class="invalid-feedback" id="msgtanggal_mulai">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tanggal_akhir" class="col-form-label col-5"
                                                style="text-align: right">Tanggal Akhir</label>
                                            <div class="col-2">
                                                <input type="date"
                                                    class="form-control col-form-label @error('tanggal_akhir') is-invalid @enderror"
                                                    id="tanggal_akhir" name="tanggal_akhir" readonly />
                                                <div class="invalid-feedback" id="msgtanggal_akhir">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-5"></div>
                                            <div class="col-4">
                                                <span class="float-right filter"><button type="button"
                                                        class="btn btn-success" id="btncetak"
                                                        disabled>Cetak</button></span>
                                                <span class="float-right filter"><button type="button"
                                                        class="btn btn-outline-danger" id="btnbatal">Batal</button></span>
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
            <div class="row hide" id="showform">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Laporan Pengiriman</h5>
                            <div class="table-responsive">
                                <a id="exportbutton"
                                    href="{{ route('logistik.laporan.export', ['jenis' => 'semua', 'ekspedisi' => 0, 'tgl_awal' => '0', 'tgl_akhir' => '0']) }}"><button
                                        class="btn btn-success">
                                        <i class="far fa-file-excel"></i> Export
                                    </button>
                                </a>
                                <table class="table table-hover" id="showtable">
                                    <thead style="text-align: center;">
                                        <tr>
                                            {{-- <th>No</th>
                                        <th>No SO</th>
                                        <th>No PO</th>
                                        <th>No SJ</th>
                                        <th>Tanggal Kirim</th>
                                        <th>No Resi</th>
                                        <th>Customer</th>
                                        <th>Alamat</th>
                                        <th>Provinsi</th>
                                        <th>Jasa Ekspedisi</th>
                                        <th>Nama Produk</th>
                                        <th>Jumlah</th>
                                        <th>Status</th> --}}
                                            <th></th>
                                            <th>No SO</th>
                                            <th>No AKN</th>
                                            <th>No PO</th>
                                            <th>Tanggal PO</th>
                                            <th>Customer</th>
                                            <th>Alamat</th>
                                            <th>Provinsi</th>
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

    <script>
        $(function() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            //  console.log(today);
            $("#tanggal_mulai").attr("max", today);
            $("#tanggal_akhir").attr("max", today);

            ekspedisi_select();
            var showtable = "";
            var sjtable = "";

            function table(pengiriman, ekspedisi, tgl_awal, tgl_akhir) {
                console.log('/api/laporan/logistik/' + pengiriman + '/' + ekspedisi + '/' + tgl_awal + '/' +
                    tgl_akhir);
                // showtable = $('#showtable').DataTable({
                //     destroy: true,
                //     processing: true,
                //     dom: 'Bfrtip',
                //     serverSide: false,
                //     language: {
                //         processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                //     },
                //     ajax: {
                //         'url': '/api/laporan/logistik/' + pengiriman + '/' + ekspedisi + '/' + tgl_awal + '/' + tgl_akhir,
                //         'dataType': 'json',
                //         'type': 'POST',
                //         'headers': {
                //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
                //         }
                //     },
                //     columns: [{
                //             data: 'DT_RowIndex',
                //             className: 'nowrap-text align-center'
                //         },
                //         {
                //             data: 'so',
                //             className: 'nowrap-text align-center'
                //         },
                //         {
                //             data: 'po',
                //             className: 'nowrap-text align-center'
                //         },
                //         {
                //             data: 'sj'
                //         },
                //         {
                //             data: 'tgl_kirim',
                //             className: 'nowrap-text align-center'
                //         },
                //         {
                //             data: 'no_resi'
                //         },
                //         {
                //             data: 'customer'
                //         },
                //         {
                //             data: 'alamat'
                //         },
                //         {
                //             data: 'provinsi',
                //             className: 'nowrap-text align-center'
                //         },
                //         {
                //             data: 'ekspedisi',
                //             className: 'nowrap-text align-center'
                //         },

                //         {
                //             data: 'produk',
                //             className: 'nowrap-text align-center'
                //         },
                //         {
                //             data: 'jumlah',
                //             className: 'nowrap-text align-center'
                //         },
                //         {
                //             data: 'status',
                //             className: 'nowrap-text align-center'
                //         },
                //     ],
                // });

                showtable = $('#showtable').DataTable({
                    destroy: true,
                    processing: true,
                    dom: 'Bfrtip',
                    serverSide: false,
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    ajax: {
                        'url': '/api/laporan/logistik/' + pengiriman + '/' + ekspedisi + '/' + tgl_awal +
                            '/' + tgl_akhir,
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    columns: [{
                            "className": 'dt-control',
                            "orderable": false,
                            "data": null,
                            "defaultContent": ''
                        },
                        {
                            data: 'so',
                            className: 'nowrap-text align-center'
                        },
                        {
                            data: 'no_paket',
                            className: 'nowrap-text align-center'
                        },
                        {
                            data: 'po',
                            className: 'nowrap-text align-center'
                        },
                        {
                            data: 'tgl_po',
                            className: 'nowrap-text align-center'
                        },
                        {
                            data: 'customer'
                        },
                        {
                            data: 'alamat'
                        },
                        {
                            data: 'provinsi',
                            className: 'nowrap-text align-center'
                        },
                    ],
                });
            }

            function format(data) {
                return `
            <div class="row childrowbg">
                <div class="col-12">
                    <div class="row">
                        <div class="col-7">
                            <div class="card shadow-none">
                                <!-- <div class="card-header"><h6 class="card-title">Daftar Produk</h6></div> -->

                                <div class="card-body">
                                    <h5>Surat Jalan</h5>
                                    <div class="table-responsive">
                                    <table class="table table-hover sjtable" id="sjtable` + data + `" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No SJ</th>
                                                <th>Tgl Kirim</th>
                                                <th>No Resi</th>
                                                <th>Ekspedisi / Pengirim</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="card shadow-none det_prd hide">
                                <div class="card-body">
                                <h5>Detail Produk</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="detailsjtable` + data + `">
                                        <thead>
                                            <tr>
                                                <th>Nama produk</th>
                                                <th>Jumlah</th>
                                                <th>No Seri</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            }

            function sjtabledata(id, pengiriman, ekspedisi, tgl_awal, tgl_akhir) {
                sjtable = $('#sjtable' + id).DataTable({
                    destroy: true,
                    processing: true,
                    dom: 'Bfrtip',
                    serverSide: false,
                    searching: false,
                    paging: false,
                    info: false,
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    ajax: {
                        'url': '/api/logistik/so/data/sj_filter/' + id,
                        'data': {
                            'pengiriman': pengiriman,
                            'ekspedisi': ekspedisi,
                            'tgl_awal': tgl_awal,
                            'tgl_akhir': tgl_akhir
                        },
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            className: 'nowrap-text align-center',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nosurat',
                            className: 'nowrap-text align-center'
                        },
                        {
                            data: 'tgl_kirim',
                            className: 'nowrap-text align-center'
                        },
                        {
                            data: 'noresi',
                            className: 'nowrap-text align-center'
                        },
                        {
                            data: 'ekspedisi_id',
                            className: 'nowrap-text align-center'
                        },
                        {
                            data: 'status_id',
                            className: 'nowrap-text align-center'
                        },
                        {
                            data: 'btn',
                            className: 'nowrap-text align-center',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });
            }


            $('#showtable tbody').on('click', 'td.dt-control', function() {
                var tr = $(this).closest('tr');
                var row = showtable.row(tr);

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(format(row.data().id), ['childrowbg', "childrow" + row.data().id]).show();
                    tr.addClass('shown');
                    var ekspedisi = "0";
                    var pengiriman = "0";
                    if ($('input[type="radio"][name="pengiriman"]:checked').length > 0) {
                        pengiriman = $('input[type="radio"][name="pengiriman"]:checked').val();
                        if (pengiriman == "ekspedisi") {
                            if ($(".ekspedisi_id").val() != "") {
                                ekspedisi = $(".ekspedisi_id").val();
                            } else {
                                ekspedisi = "0";
                            }
                        }
                    } else {
                        pengiriman = "0";
                    }

                    var tgl_awal = $('#tanggal_mulai').val();
                    var tgl_akhir = $('#tanggal_akhir').val();
                    sjtabledata(row.data().id, pengiriman, ekspedisi, tgl_awal, tgl_akhir);
                }
            });

            function formatchild(data) {
                return `
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-none">
                        <div class="card-header"><h6 class="card-title">Daftar Produk</h6></div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table class="table table-hover" id="detailsjtable` + data + `">
                                <thead>
                                    <tr>
                                        <th>Nama produk</th>
                                        <th>Jumlah</th>
                                        <th>No Seri</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            }

            function detailsjtabledata(id, id_sj) {
                $('#detailsjtable' + id).DataTable({
                    destroy: true,
                    processing: true,
                    dom: 'Bfrtip',
                    serverSide: false,
                    searching: false,
                    info: false,
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    ajax: {
                        'url': '/api/logistik/pengiriman/data/' + id_sj + '/0',
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    columns: [{
                            data: 'nama_produk',
                            className: 'nowrap-text align-center'
                        },
                        {
                            data: 'jumlah',
                            className: 'nowrap-text align-center'
                        },
                        {
                            data: 'no_seri',
                            className: 'align-center minimizechar'
                        },
                    ],
                });
            }


            $(document).on('click', '.sjtable tbody .detail', function() {

                var tr = $(this).closest('tr');
                var dataid = tr.find('#detail').attr('data-id');
                var dataparent = tr.find('#detail').attr('data-parent');

                console.log(dataparent);
                if (dataid) {
                    $('#showtable .childrow' + dataparent).find('.det_prd').removeClass('hide');
                    $('#sjtable' + dataparent).find('tr').removeClass('bgcolor');
                    tr.addClass('bgcolor');
                    detailsjtabledata(dataparent, dataid);
                } else {
                    $('#showtable .childrow' + dataparent).find('.det_prd').addClass('hide');
                    $('#sjtable' + dataparent).find('tr').removeClass('bgcolor');
                }

                // if ( row.child.isShown() ) {
                //     // This row is already open - close it
                //     row.child.hide();
                //     tr.removeClass('shown-child');
                // }
                // else {
                //     // Open this row
                //     row.child( formatchild(row.data().id) ).show();
                //     tr.addClass('shown-child');
                //     detailsjtabledata(row.data().id);
                // }
            });


            function ekspedisi_select() {
                $('.ekspedisi_id').select2({
                    placeholder: "Pilih Ekspedisi",
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        delay: 250,
                        type: 'GET',
                        url: '/api/logistik/ekspedisi/select/0',
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
                            //   console.log(data);
                            return {
                                results: $.map(data, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nama
                                    };
                                })
                            };
                        },
                    }
                }).change(function() {
                    var value = $(this).val();
                    //  console.log(value);
                });
            }

            $('.ekspedisi_id').on('keyup change', function() {
                if ($(this).val() != "") {
                    $('input[type="radio"][name="penjualan"]').removeAttr('disabled');
                    if ($('input[type="radio"][name="penjualan"]').val() != undefined && $('#tanggal_mulai')
                        .val() != "" && $('#tanggal_akhir').val() != "") {
                        $("#btncetak").removeAttr('disabled');
                    } else {
                        $("#btncetak").attr('disabled', true);
                    }
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            });

            $('input[type="radio"][name="pengiriman"]').on('change', function() {
                if ($(this).val() == "ekspedisi") {
                    $('#ekspedisi').removeClass('hide');
                } else {
                    $('#ekspedisi').addClass('hide');
                }
            });

            $('#tanggal_mulai').on('keyup change', function() {
                $("#tanggal_akhir").val("");
                $("#btncetak").removeAttr('disabled');
                if ($(this).val() != "") {
                    $('#tanggal_akhir').removeAttr('readonly');
                    $("#tanggal_akhir").attr("min", $(this).val())
                    if ($('#tanggal_akhir').val() != "") {
                        $("#btncetak").removeAttr('disabled');
                    } else {

                        $("#btncetak").attr('disabled', true);
                    }
                } else {
                    $("#tanggal_akhir").val("");
                    $("#btncetak").attr('disabled', true);
                }
            });

            $('#tanggal_akhir').on('keyup change', function() {
                if ($(this).val() != "") {
                    if ($('#tanggal_mulai').val() != "") {
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
                $(".ekspedisi_id").val(null).trigger("change");
                $('#ekspedisi').addClass('hide');
                $('input[type="radio"][name="pengiriman"]').prop('checked', false);
                $('#tanggal_mulai').val('');
                $('#tanggal_akhir').val('');
                $('#tanggal_akhir').attr('readonly', true);
                $('#showform').addClass('hide');
            });

            $('#btncetak').on('click', function() {
                $('#showform').removeClass('hide');

                var ekspedisi = "0";
                // console.log($(".ekspedisi_id").val());
                var pengiriman = "0";
                if ($('input[type="radio"][name="pengiriman"]:checked').length > 0) {
                    pengiriman = $('input[type="radio"][name="pengiriman"]:checked').val();
                    if (pengiriman == "ekspedisi") {
                        if ($(".ekspedisi_id").val() != "") {
                            ekspedisi = $(".ekspedisi_id").val();
                        } else {
                            ekspedisi = "0";
                        }
                    }
                } else {
                    pengiriman = "0";
                }

                var tgl_awal = $('#tanggal_mulai').val();
                var tgl_akhir = $('#tanggal_akhir').val();

                // console.log(pengiriman);
                // console.log(ekspedisi);
                // console.log(tgl_awal);
                // console.log(tgl_akhir);

                table(pengiriman, ekspedisi, tgl_awal, tgl_akhir);

                var link = '/logistik/laporan/export/' + pengiriman + '/' + ekspedisi + '/' + tgl_awal +
                    '/' + tgl_akhir;

                $('#exportbutton').attr({
                    href: link
                });

                return false

            })
        });
    </script>
@endsection
