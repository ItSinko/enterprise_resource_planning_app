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
                    @if (Auth::user()->Karyawan->divisi_id == '23')
                        <li class="breadcrumb-item"><a href="{{ route('qc.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Sales Order QC</li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Sales Order QC</li>
                    @endif
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

        .filter {
            margin: 5px;
        }

        .info {
            color: #4682B4
        }

        .minimizechar {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 25ch;
        }

        @media screen and (min-width: 1440px) {

            body {
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
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-proses_uji-tab" data-toggle="pill"
                                        href="#pills-proses_uji" role="tab" aria-controls="pills-proses_uji"
                                        aria-selected="true">Dalam Proses</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-selesai_uji-tab" data-toggle="pill"
                                        href="#pills-selesai_uji" role="tab" aria-controls="pills-selesai_uji"
                                        aria-selected="false">Selesai Proses</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-proses_uji" role="tabpanel"
                                    aria-labelledby="pills-proses_uji-tab">
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
                                                                <label for="jenis_penjualan">Jenis Penjualan</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="ekatalog" id="defaultCheck1"
                                                                        name="jenis_penj[]" />
                                                                    <label class="form-check-label" for="defaultCheck1">
                                                                        E-Catalogue
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="spa" id="defaultCheck2"
                                                                        name="jenis_penj[]" />
                                                                    <label class="form-check-label" for="defaultCheck2">
                                                                        SPA
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="spb" id="defaultCheck3"
                                                                        name="jenis_penj[]" />
                                                                    <label class="form-check-label" for="defaultCheck3">
                                                                        SPB
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <span class="float-right">
                                                                    <button class="btn btn-primary" type="submit">
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
                                                <table class="table" style="text-align:center;width:100%;" id="showtable">
                                                    <thead>
                                                        <th>No</th>
                                                        <th>No SO</th>
                                                        <th>No PO</th>
                                                        <th>Batas Pengujian</th>
                                                        <th>Customer</th>
                                                        <th>Keterangan</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade show" id="pills-selesai_uji" role="tabpanel"
                                    aria-labelledby="pills-selesai_uji-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="float-right filter">
                                                <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                                <form id="filter_selesai">
                                                    <div class="dropdown-menu">
                                                        <div class="px-3 py-3">
                                                            <div class="form-group">
                                                                <label for="jenis_penjualan">Jenis Penjualan</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="ekatalog" id="defaultCheck1"
                                                                        name="jenis_penj2[]" />
                                                                    <label class="form-check-label" for="defaultCheck1">
                                                                        E-Catalogue
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="spa" id="defaultCheck2"
                                                                        name="jenis_penj2[]" />
                                                                    <label class="form-check-label" for="defaultCheck2">
                                                                        SPA
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="spb" id="defaultCheck3"
                                                                        name="jenis_penj2[]" />
                                                                    <label class="form-check-label" for="defaultCheck3">
                                                                        SPB
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <span class="float-right">
                                                                    <button class="btn btn-primary" type="submit">
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
                                                <table class="table" style="text-align:center;width:100%;"
                                                    id="selesaitable">
                                                    <thead>
                                                        <th>No</th>
                                                        <th>No SO</th>
                                                        <th>No PO</th>
                                                        {{-- <th>Batas Pengujian</th> --}}
                                                        <th>Customer</th>
                                                        <th>Keterangan</th>
                                                        {{-- <th>Status</th> --}}
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
        <div class="modal fade" id="batalmodal" tabindex="-1" role="dialog" aria-labelledby="batalmodal"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header bg-navy">
                        <h4 id="modal-title">Pesanan Batal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="batal">

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="noserimodal" tabindex="-1" role="dialog" aria-labelledby="noserimodal"
            aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header bg-light">
                        <h4 id="modal-title">Noseri</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="noseri">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table" style="text-align:center;width:100%;" id="noseritable">
                                        <thead>
                                            <th>No</th>
                                            <th>No Seri</th>
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
    </section>
@stop
@section('adminlte_js')
    <script>
        $(function() {
            var showtable = $('#showtable').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    'url': '/api/qc/so/data/semua',
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
                        orderable: true,
                        searchable: false
                    }, {
                        data: 'so',

                    }, {
                        data: 'no_po',

                    }, {
                        data: 'batas_uji',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false,
                    }, {
                        data: 'nama_customer',

                    }, {
                        data: 'keterangan',
                        className: 'minimizechar',
                    },
                    {
                        data: 'status',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'button',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            })
            $('#filter').submit(function() {
                var values = [];
                $("input[name='jenis_penj[]']:checked").each(function() {
                    values.push($(this).val());
                });
                if (values != 0) {
                    var x = values;

                } else {
                    var x = ['semua']
                }
                console.log(x);
                $('#showtable').DataTable().ajax.url('/api/qc/so/data/' + x).load();
                return false;
            });

            $('#pills-selesai_uji-tab').on('click', function() {
                selesai_data();
            })

            $("#showtable").on('click', '.batalmodal', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    url: '/qc/so/cancel/' + id,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#batalmodal').modal("show");
                        $('#batal').html(result).show();
                        produktable(id);
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

            $(document).on('click', '#produktable .noseri', function(event) {
                event.preventDefault();
                var id = $(this).attr('data-id');
                var pesan = $(this).attr('data-pesan');
                console.log(id + " " + pesan);
                $('#noserimodal').modal("show");
                noseritable(id, pesan);
            });

            function produktable(id) {
                $('#produktable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/qc/so/detail/' + id,
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'nowrap-text align-center',
                        },
                        {
                            data: 'nama_produk',
                            className: 'align-center',
                        },
                        {
                            data: 'jumlah',
                            orderable: false,
                            searchable: false,
                            className: 'nowrap-text align-center',
                        },
                        {
                            data: 'aksi',
                            orderable: false,
                            searchable: false,
                            className: 'nowrap-text align-center',
                        },
                    ],
                });
            }

            function noseritable(data_id, pesanan_id) {
                $('#noseritable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    autowidth: true,
                    ajax: {
                        'type': 'POST',
                        'datatype': 'JSON',
                        'url': '/api/qc/so/seri/belum/' + data_id + '/' + pesanan_id,
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'seri',
                            className: 'nowrap-text align-center',
                            orderable: true,
                            searchable: true
                        }
                    ]
                });
            }

            function selesai_data() {
                var showtable = $('#selesaitable').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        'url': '/api/qc/so/data/selesai/semua',
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
                            orderable: true,
                            searchable: false
                        }, {
                            data: 'so',
                        }, {
                            data: 'no_po',
                        },
                        // {
                        //     data: 'batas_uji',
                        //     className: 'nowrap-text align-center',
                        //     orderable: false,
                        //     searchable: false,
                        // },
                        {
                            data: 'nama_customer',

                        }, {
                            data: 'keterangan',
                            className: 'minimizechar',
                        },
                        // {
                        //     data: 'status',
                        //     className: 'nowrap-text align-center',
                        //     orderable: false,
                        //     searchable: false
                        // },
                        {
                            data: 'button',
                            className: 'nowrap-text align-center',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                $('#filter_selesai').submit(function() {
                    var values2 = [];
                    var x2 = [];
                    $("input[name='jenis_penj2[]']:checked").each(function() {
                        values2.push($(this).val());
                    });
                    if (values2 != 0) {
                        x2 = values2;
                    } else {
                        x2 = ['semua']
                    }
                    console.log(x2);
                    $('#selesaitable').DataTable().ajax.url('/api/qc/so/data/selesai/' + x2).load();
                    return false;
                });
            }
        })
    </script>
@stop
