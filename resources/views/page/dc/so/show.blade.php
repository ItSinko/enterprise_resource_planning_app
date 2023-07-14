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
                    @if (Auth::user()->divisi_id == '9')
                        <li class="breadcrumb-item"><a href="{{ route('dc.dashboard') }}">Beranda</a></li>
                    @elseif(Auth::user()->divisi_id == '9')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                    @endif
                    <li class="breadcrumb-item active">Sales Order DC</li>
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

        .warning {
            color: #FFC700;
            font-weight: 600;
        }

        .info {
            color: #3a7bb0;
            font-weight: 600;
        }

        .filter {
            margin: 5px;
        }

        .nowraptext {
            white-space: nowrap;
        }

        .minimizechar {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 25ch;
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

            .dropdown-menu {
                font-size: 12px;
            }
        }
    </style>
@stop

@section('content')
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-proses_coo-tab" data-toggle="pill"
                                        href="#pills-proses_coo" role="tab" aria-controls="pills-proses_coo"
                                        aria-selected="true">COO Dalam Proses</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-selesai_coo-tab" data-toggle="pill"
                                        href="#pills-selesai_coo" role="tab" aria-controls="pills-selesai_coo"
                                        aria-selected="false">COO Selesai</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-proses_coo" role="tabpanel"
                                    aria-labelledby="pills-proses_coo-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="float-right filter">
                                                <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                                <form id="filter">
                                                    <div class="dropdown-menu">
                                                        <div class="px-3 py-3">
                                                            <div class="form-group">
                                                                <label for="status">Status</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        value="semua" id="status3" name="status" />
                                                                    <label class="form-check-label" for="status3">
                                                                        Semua
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        value="belum_diproses" id="status2"
                                                                        name="status" />
                                                                    <label class="form-check-label" for="status2">
                                                                        Belum Diproses
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        value="sebagian_diproses" id="status1"
                                                                        name="status" />
                                                                    <label class="form-check-label" for="status1">
                                                                        Sebagian Diproses
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <span class="float-right">
                                                                    <button class="btn btn-primary">
                                                                        Cari
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table nowraptext" style="text-align:center;" id="showtable">
                                                    <thead>
                                                        <th>No</th>
                                                        <th>No PO</th>
                                                        <th>No AKN</th>
                                                        <th>Batas Kontrak</th>
                                                        <th>Customer</th>
                                                        <th>Instansi</th>
                                                        <th>Status</th>
                                                        <th>Keterangan</th>
                                                        <th>Aksi</th>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade show" id="pills-selesai_coo" role="tabpanel"
                                    aria-labelledby="pills-selesai_coo-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table nowraptext" style="text-align:center;"
                                                    id="selesaitable">
                                                    <thead>
                                                        <th>No</th>
                                                        <th>No PO</th>
                                                        <th>No AKN</th>
                                                        <th>Customer</th>
                                                        <th>Instansi</th>
                                                        <th>Status</th>
                                                        <th>Keterangan</th>
                                                        <th>Aksi</th>
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
            </div>
        </div>
        </div>
        <div class="modal fade" id="batalmodal" data-backdrop="static"  tabindex="-1" role="dialog" aria-labelledby="batalmodal"
        aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header">
                        <h4 id="modal-title">Konfirmasi Batal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="batal">
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('adminlte_js')
    <script>
    $(document).on('submit', '#batal_so_dc', function(e) {
                e.preventDefault();
                var action = $(this).attr('action');
                console.log(action);
                $.ajax({
                    url: action,
                    type: 'POST',
                    data: $('#batal_so_dc').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                            if (response['data'] == "alasan_salah") {
                            swal.fire(
                                'Gagal',
                                response['message'],
                                'error'
                            );
                        }
                            if (response['data'] == "berhasil") {
                            swal.fire(
                                'Berhasil',
                                response['message'],
                                'success'
                            ).then(function() {
                                $('.modal').modal('hide')
                                $('#showtable').DataTable().ajax.url('/api/dc/so/data/semua').load();
                                selesaitable()

                         });
                        }

                    },
                    error: function(xhr, status, error) {
                    console.log(error)
                        swal.fire(
                            'Error',
                            'Ada kesalahan, batal transaksi gagal',
                            'warning'
                        );

                    }
                });
                return false;
            });

             $("#showtable").on('click', '.batalmodal', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                swal.fire({
                    title: 'Batalkan Transaksi',
                    text: "Data transaksi ini akan terhapus semua",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'Tidak',
                    confirmButtonText: 'Iya',
                    }).then(function(isConfirm) {
                        if (isConfirm.value === true) {
                            console.log(id)
                           $.ajax({
                                url: '/dc/so/cancel/' + id,
                                success: function(result) {
                                    $('#batalmodal').modal("show");
                                    $('#batal').html(result).show();
                                },
                                error: function(jqXHR, testStatus, error) {
                                    alert("Page cannot open. Error:" + error);
                                    $('#loader').hide();
                                },
                                timeout: 8000
                              })
                        }
                    })
            });
             $("#selesaitable").on('click', '.batalmodal', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                swal.fire({
                    title: 'Batalkan Transaksi',
                    text: "Data transaksi ini akan terhapus semua",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'Tidak',
                    confirmButtonText: 'Iya',
                    }).then(function(isConfirm) {
                        if (isConfirm.value === true) {
                            console.log(id)
                           $.ajax({
                                url: '/dc/so/cancel/' + id,
                                success: function(result) {
                                    $('#batalmodal').modal("show");
                                    $('#batal').html(result).show();
                                },
                                error: function(jqXHR, testStatus, error) {
                                    alert("Page cannot open. Error:" + error);
                                    $('#loader').hide();
                                },
                                timeout: 8000
                              })
                        }
                    })
            });


            $(document).on('click', '#pills-selesai_coo-tab', function() {
                selesaitable();
            });

            var showtable = $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/dc/so/data/semua',
                    'type': 'POST',
                    'datatype': 'JSON',
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
                    data: 'no_po',
                }, {
                    data: 'no_paket',
                }, {
                    data: 'batas_paket',
                }, {
                    data: 'nama_customer',
                }, {
                    data: 'instansi',
                }, {
                    data: 'status',
                }, {
                    data: 'ket',
                    class: 'minimizechar'
                }, {
                    data: 'button',
                }]
            })

            function selesaitables() {
            alert('ok')
            }
            function selesaitable() {
                var showtable = $('#selesaitable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/dc/so/selesai/semua',
                        'type': 'POST',
                        'datatype': 'JSON',
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
                        data: 'no_po',
                    }, {
                        data: 'no_paket',
                    }, {
                        data: 'nama_customer',
                    }, {
                        data: 'instansi',
                    }, {
                        data: 'status',
                    }, {
                        data: 'ket',
                        class: 'minimizechar'
                    }, {
                        data: 'button',
                    }]
                });
            }

            $('#filter').submit(function() {
                var values = [];
                $("input:checked").each(function() {
                    values.push($(this).val());
                });
                if (values != 0) {
                    var x = values;

                } else {
                    var x = ['semua']
                }
                console.log(x);
                $('#showtable').DataTable().ajax.url('/api/dc/so/data/' + x).load();
                return false;
            });


        </script>
        @stop
