@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Sales Order</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '9')
                        <li class="breadcrumb-item"><a href="{{ route('dc.dashboard') }}">Beranda</a></li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '9')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ route('dc.so.show') }}">Sales Order DC</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        .urgent {
            color: #dc3545;
            font-weight: 600;
        }

        .info {
            color: #FFC700;
            font-weight: 600;
        }

        .ok {
            color: green;
            font-weight: 600;
        }

        .nok {
            color: #dc3545;
            font-weight: 600;
        }

        .warning {
            color: #FFC700;
            font-weight: 600;
        }

        .list-group-item {
            border: 0 none;
        }

        .align-right {
            float: right;
        }

        .align-center {
            text-align: center;
        }

        .margin {
            margin-bottom: 5px;
        }

        .filter {
            margin: 5px;
        }

        .hide {
            display: none !important;
        }

        .bgcolor {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .fa-search:hover {
            color: #ADD8E6;
        }

        .fa-search:active {
            color: #808080;
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



        @media screen and (min-width: 1440px) {
            section {
                font-size: 14px;
            }

            .dropdown-item {
                font-size: 14px;
            }

            /* .cust{
                max-width: 40%;
            } */
        }

        @media screen and (max-width: 1439px) {
            section {
                font-size: 12px;
            }

            .dropdown-item {
                font-size: 12px;
            }

            /* .cust{
                max-width: 40%;
            } */
        }

        @media screen and (max-width: 992px) {
            .collapsable {
                display: none;
            }

            .align-md {
                text-align: center;
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
                        <div class="card-body">
                            <h5>Info</h5>
                            <div class="row">
                                <div class="col-lg-11 col-md-12">
                                    <div class="row d-flex justify-content-between">
                                        <div class="p-2">
                                            <div class="filter">
                                                <div><small class="text-muted">Customer</small></div>
                                                <div><b>{{ $data->spa->customer->nama }}</b></div>
                                                <div><b>{{ $data->spa->customer->alamat }}</b></div>
                                                <div><b>{{ $data->spa->customer->Provinsi->nama }}</b></div>
                                            </div>
                                        </div>
                                        <div class="p-2">
                                            <div class="filter">
                                                <div><small class="text-muted">No SO</small></div>
                                                <div><b>{{ $data->so }}</b></div>
                                            </div>
                                            <div class="filter">
                                                <div><small class="text-muted">No PO</small></div>
                                                <div><b>{{ $data->no_po }}</b></div>
                                            </div>
                                        </div>
                                        <div class="p-2">
                                            <div class="filter">
                                                <div><small class="text-muted">Tanggal PO</small></div>
                                                <div><b>{{ date('d-m-Y', strtotime($data->tgl_po)) }}</b></div>
                                            </div>
                                            <div class="filter">
                                                <div><small class="text-muted">Status</small></div>
                                                <div><b>{!! $status !!}</b></div>
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
        <div class="row">
            <div class="col-lg-7 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Daftar Barang</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" style="text-align: center; width:100%;" id="showtable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th class="nowrap-text">Tgl Surat Jalan</th>
                                        <th>Nama</th>
                                        <th>No AKD</th>
                                        <th>Bulan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-6 hide" id="noseri">
                <div class="card">
                    <div class="card-body">


                        <div class="row">
                            <div class="col-12">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-belum-proses-tab" data-toggle="pill"
                                            href="#pills-belum-proses" role="tab" aria-controls="pills-belum-proses"
                                            aria-selected="true">Belum Tersedia</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-selesai-proses-tab" data-toggle="pill"
                                            href="#pills-selesai-proses" role="tab" aria-controls="pills-selesai-proses"
                                            aria-selected="false">Sudah Tersedia</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-belum-proses" role="tabpanel"
                                        aria-labelledby="pills-belum-proses-tab">
                                        @if (Auth::user()->divisi->kode == 'dc')
                                            <span class="float-right filter">
                                                <a data-toggle="modal" data-target="#createmodal" class="createmodal"
                                                    data-attr="" data-id="">
                                                    <button class="btn btn-info btn-sm" id="cekbrg" disabled="true">
                                                        <i class="fas fa-plus"></i> Tambah COO
                                                    </button>
                                                </a>
                                            </span>
                                        @endif
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped"
                                                style="text-align: center; width:100%;" id="noseri_belum_proses_table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="check_all" id="check_all" name="check_all" />
                                                                <label class="form-check-label" for="check_all">
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <th>No Seri</th>
                                                        <th>Tgl Kirim</th>
                                                        <th>Ttd Terima</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show" id="pills-selesai-proses" role="tabpanel"
                                        aria-labelledby="pills-selesai-proses-tab">
                                        @if (Auth::user()->divisi->kode == 'dc')
                                            <span class="float-right filter">
                                                <a data-toggle="modal" data-target="#editmodal" class="editmodal"
                                                    data-attr="" data-id="">
                                                    <button class="btn btn-warning btn-sm" id="cekbrgedit"
                                                        disabled="true">
                                                        <i class="fas fa-pencil-alt"></i> Edit COO
                                                    </button>
                                                </a>
                                            </span>
                                        @endif
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped"
                                                style="text-align: center; width:100%;" id="noseri_selesai_proses_table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="check_all" id="check_all" name="check_all" />
                                                                <label class="form-check-label" for="check_all">
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <th>No Seri</th>
                                                        <th>Tgl Kirim</th>
                                                        <th>Ttd Terima</th>
                                                        <th></th>
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
                </div>
            </div>
            <div class="modal fade" id="createmodal" role="dialog" aria-labelledby="createmodal" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-info">
                            <h4 class="modal-title">Tambah COO</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="create">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-warning">
                            <h4 class="modal-title">COO</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="edit">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="tglkirim_modal" role="dialog" aria-labelledby="tglkirim_modal"
                aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-warning">
                            <h4 class="modal-title">Tgl Kirim COO</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="tglkirim_edit">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('adminlte_js')
    <script>
        $(function() {
            var divisi = '{{ Auth::user()->divisi->kode }}';
            $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/dc/so/detail/{{ $data->id }}',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'tgl_surat',
                        className: 'nowrap-text align-center collapsable',
                    },
                    {
                        data: 'nama_paket',
                    },
                    {
                        data: 'no_akd',
                        className: 'nowrap-text align-center collapsable',
                    }, {
                        data: 'bulan',
                    }, {
                        data: 'status',
                        className: 'nowrap-text align-center collapsable',
                    }, {
                        data: 'button',
                        orderable: false,
                        searchable: false
                    }
                ]
            });


            $('#noseri_belum_proses_table').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/dc/so/detail/seri/' + 0 + '/belum',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                        data: 'checkbox',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false,
                        visible: divisi == "dc" ? true : false
                    },
                    {
                        data: 'noseri',

                    },
                    {
                        data: 'tgl',
                        className: 'collapsable',
                    },
                    {
                        data: 'ket',
                        className: 'collapsable',
                    },
                    {
                        data: 'laporan',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#noseri_selesai_proses_table').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/dc/so/detail/seri/' + 0 + '/belum',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                        data: 'checkbox',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false,
                        visible: divisi == "dc" ? true : false
                    },
                    {
                        data: 'noseri',

                    },
                    {
                        data: 'tgl',
                        className: 'collapsable',
                    },
                    {
                        data: 'ket',
                        className: 'collapsable',
                    },
                    {
                        data: 'laporan',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            function listnoseri(seri_id, data, table) {
                $('#' + table).DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    searching: false,
                    info: false,
                    ajax: {
                        'type': 'POST',
                        'datatype': 'JSON',
                        'url': '/api/dc/so/detail/seri/select/' + seri_id + '/' + data,
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                        data: 'DT_RowIndex',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'noseri'
                    }]
                });
            }

            var checkedAryBelumProses = [];
            $('#noseri_belum_proses_table').on('click', 'input[name="check_all"]', function() {
                var rows = $('#noseri_belum_proses_table').DataTable().rows().nodes();
                if ($('#noseri_belum_proses_table').find('input[name="check_all"]:checked').length > 0) {
                    $('#cekbrg').prop('disabled', false);
                    // $('#cekbrgedit').prop('disabled', false);
                    $('.nosericheck', rows).prop('checked', true);
                    checkedAryBelumProses = [];
                    $.each($(".nosericheck:checked", rows), function() {
                        checkedAryBelumProses.push($(this).closest('tr').find('.nosericheck').attr(
                            'data-id'));
                    });
                } else if ($('#noseri_belum_proses_table').find('input[name="check_all"]:checked').length <=
                    0) {
                    $('.nosericheck', rows).prop('checked', false);
                    $('#cekbrg').prop('disabled', true);
                    // $('#cekbrgedit').prop('disabled', true);
                }
            });

            $('#noseri_belum_proses_table').on('click', '.nosericheck', function() {
                var rows = $('#noseri_belum_proses_table').DataTable().rows().nodes();
                $('#noseri_belum_proses_table').find('#check_all').prop('checked', false);
                if ($('.nosericheck:checked', rows).length > 0) {
                    $('#cekbrg').prop('disabled', false);
                    // $('#cekbrgedit').prop('disabled', false);
                    checkedAryBelumProses = [];
                    $.each($(".nosericheck:checked", rows), function() {
                        checkedAryBelumProses.push($(this).closest('tr').find('.nosericheck').attr(
                            'data-id'));
                    });
                } else if ($('.nosericheck:checked', rows).length <= 0) {
                    $('#cekbrg').prop('disabled', true);
                    // $('#cekbrgedit').prop('disabled', true);
                }
            });

            checkedArySelesaiProses = [];
            $('#noseri_selesai_proses_table').on('click', 'input[name="check_all"]', function() {
                var rows = $('#noseri_selesai_proses_table').DataTable().rows().nodes();
                if ($('#noseri_selesai_proses_table').find('input[name="check_all"]:checked').length > 0) {
                    // $('#cekbrg').prop('disabled', false);
                    $('#cekbrgedit').prop('disabled', false);
                    $('.nosericheck', rows).prop('checked', true);
                    checkedArySelesaiProses = [];
                    $.each($(".nosericheck:checked", rows), function() {
                        checkedArySelesaiProses.push($(this).closest('tr').find('.nosericheck')
                            .attr('data-id'));
                    });
                } else if ($('#noseri_selesai_proses_table').find('input[name="check_all"]:checked')
                    .length <= 0) {
                    $('.nosericheck', rows).prop('checked', false);
                    // $('#cekbrg').prop('disabled', true);
                    $('#cekbrgedit').prop('disabled', true);
                }
            });

            $('#noseri_selesai_proses_table').on('click', '.nosericheck', function() {
                var rows = $('#noseri_selesai_proses_table').DataTable().rows().nodes();
                $('#check_all').prop('checked', false);
                if ($('.nosericheck:checked', rows).length > 0) {
                    // $('#cekbrg').prop('disabled', false);
                    $('#cekbrgedit').prop('disabled', false);
                    checkedArySelesaiProses = [];
                    $.each($(".nosericheck:checked", rows), function() {
                        checkedArySelesaiProses.push($(this).closest('tr').find('.nosericheck')
                            .attr('data-id'));
                    });
                } else if ($('.nosericheck:checked', rows).length <= 0) {
                    // $('#cekbrg').prop('disabled', true);
                    $('#cekbrgedit').prop('disabled', true);
                }
            });

            $('#showtable').on('click', '.noserishow', function() {
                var data = $(this).attr('data-id');
                var datacount = $(this).attr('data-count');
                if (datacount == 0) {
                    // $('.sericheckbox').addClass("hide");
                    // $('.createmodal').addClass("hide");
                    // $('.editmodal').removeClass("hide");
                    // $('#noseri_belum_proses_table').DataTable().column(0).visible(false);
                } else {
                    // $('.createmodal').removeClass("hide");
                    // $('.editmodal').addClass("hide");
                    // $('#noseri_belum_proses_table').DataTable().column(0).visible(true);
                }
                $('#showtable').find('tr').removeClass('bgcolor');
                $(this).closest('tr').addClass('bgcolor');
                $('#noseri').removeClass('hide');
                $('#noseri_belum_proses_table').DataTable().ajax.url('/api/dc/so/detail/seri/' + data +
                    '/belum').load();
                $('#noseri_selesai_proses_table').DataTable().ajax.url('/api/dc/so/detail/seri/' + data +
                    '/selesai').load();
                //  console.log(data);
            });

            $(document).on('submit', '#form-create-coo', function(e) {
                e.preventDefault();
                var action = $(this).attr('action');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: action,
                    data: $('#form-create-coo').serialize(),
                    beforeSend: function() {
                        $('#btnsimpan').attr('disabled', true);
                        $('#loader').show();
                    },
                    success: function(response) {
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan Penambahan Data COO',
                                'success'
                            ).then(function() {
                                location.reload();
                            });
                            $("#editmodal").modal('hide');
                            $('#cekbrg').attr("disabled", true);
                            $('#noseritable').DataTable().ajax.reload();

                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan Penambahan Data COO',
                                'error'
                            );
                        }
                    },
                    complete: function() {
                        $('#btnsimpan').attr('disabled', false);
                        $('#loader').hide();
                    },
                    error: function(xhr, status, error) {
                        alert($('#form-create-coo').serialize());
                    }
                });
                return false;
            });
            $(document).on('submit', '#form-update-coo', function(e) {
                e.preventDefault();
                var action = $(this).attr('action');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: action,
                    data: $('#form-update-coo').serialize(),
                    beforeSend: function() {
                        $('#btnsimpan').attr('disabled', true);
                        $('#loader').show();
                    },
                    success: function(response) {
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan Perubahan Data COO',
                                'success'
                            ).then(function() {
                                location.reload();
                            });
                            $("#editmodal").modal('hide');
                            $('#cekbrg').attr("disabled", true);
                            $('#noseritable').DataTable().ajax.reload();

                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan Perubahan Data COO',
                                'error'
                            );
                        }
                    },
                    complete: function() {
                        $('#btnsimpan').attr('disabled', false);
                        $('#loader').hide();
                    },
                    error: function(xhr, status, error) {
                        alert($('#form-update-coo').serialize());
                    }
                });
                return false;
            });

            // $(document).on('click', '.createmodal', function(event) {
            //     event.preventDefault();
            //     var href = $(this).attr('data-attr');
            //     var id = $(this).data('id');
            //     $.ajax({
            //         url: "/dc/coo/create/" + id,
            //         beforeSend: function() {
            //             $('#loader').show();
            //         },
            //         // return the result
            //         success: function(result) {
            //             $('#createmodal').modal("show");
            //             $('#create').html(result).show();
            //             $('.bulan').select2({
            //                 placeholder: 'Pilih Bulan',
            //                 allowClear: true
            //             });
            //             // $("#editform").attr("action", href);
            //         },
            //         complete: function() {
            //             $('#loader').hide();
            //         },
            //         error: function(jqXHR, testStatus, error) {
            //             console.log(error);
            //             alert("Page " + href + " cannot open. Error:" + error);
            //             $('#loader').hide();
            //         },
            //         timeout: 8000
            //     })
            // });

            $(document).on('click', '.createmodal', function(event) {
                event.preventDefault();
                data = $(".nosericheck").data().value;
                var id = $(this).data('id');
                $.ajax({
                    url: "/dc/coo/create/" + checkedAryBelumProses + "/" + data,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#createmodal').modal("show");
                        $('#create').html(result).show();
                        $('.bulan_edit').select2({
                            placeholder: 'Pilih Bulan',
                            allowClear: true
                        });
                        listnoseri(checkedAryBelumProses, data, 'listnoseribelum');
                        // $("#editform").attr("action", href);
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page " + href + " cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

            $(document).on('click', '.editmodal', function(event) {
                event.preventDefault();
                data = $(".nosericheck").data().value;
                var id = $(this).data('id');
                $.ajax({
                    url: "/dc/coo/edit/" + checkedArySelesaiProses + "/" + data,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#editmodal').modal("show");
                        $('#edit').html(result).show();
                        $('.bulan_edit').select2({
                            placeholder: 'Pilih Bulan',
                            allowClear: true
                        });
                        listnoseri(checkedArySelesaiProses, data, 'listnoseriselesai');
                        // $("#editform").attr("action", href);
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page " + href + " cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

            $(document).on('click', '.tglkirim_modal', function(event) {
                var id = $(this).data('id');
                console.log(id);

                $.ajax({
                    url: "/dc/coo/edit_tglkirim/" + id,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#tglkirim_modal').modal("show");
                        $('#tglkirim_edit').html(result).show();
                        //  alert('response);

                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page " + href + " cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

            $(document).on('keyup change', 'select[name="bulan"]', function() {
                if ($(this).val() != "" && (($('input[type="radio"][name="diketahui"]:checked').val() ==
                        "spa") || ($('#jabatan').val() != "" && $('#nama').val() != ""))) {
                    $('#btnsimpan').removeClass('disabled');
                } else {
                    $('#btnsimpan').addClass('disabled');
                }
            });

            $(document).on('change', 'input[type="radio"][name="diketahui"]', function() {
                $('#jabatan').val("");
                $('#nama').val("");
                console.log($(this).val());
                if ($(this).val() != "custom") {
                    $('#jabatan_label').addClass('hide');
                    $('#nama_label').addClass('hide');
                    if ($('select[name="bulan"]').val() != "") {
                        $('#btnsimpan').removeClass('disabled');
                    } else {
                        $('#btnsimpan').addClass('disabled');
                    }
                } else {
                    $('#jabatan_label').removeClass('hide');
                    $('#nama_label').removeClass('hide');
                    if ($('select[name="bulan"]').val() != "" && $('#jabatan').val() != "" && $('#nama')
                        .val() != "") {
                        $('#btnsimpan').removeClass('disabled');
                    } else {
                        $('#btnsimpan').addClass('disabled');
                    }
                }
            });

            $(document).on('keyup change', 'input[name="nama"]', function() {
                if ($(this).val() != "" && $('#jabatan').val() != "" && $('select[name="bulan"]').val() !=
                    "") {
                    $('#btnsimpan').removeClass('disabled');
                } else {
                    $('#btnsimpan').addClass('disabled');
                }
            });

            $(document).on('keyup change', 'input[name="jabatan"]', function() {
                if ($(this).val() != "" && $('#nama').val() != "" && $('select[name="bulan"]').val() !=
                    "") {
                    $('#btnsimpan').removeClass('disabled');
                } else {
                    $('#btnsimpan').addClass('disabled');
                }
            });

        })
    </script>

@endsection
