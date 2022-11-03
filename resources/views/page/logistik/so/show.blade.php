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
                    @if (Auth::user()->Karyawan->divisi_id == '15')
                        <li class="breadcrumb-item"><a href="{{ route('logistik.dashboard') }}">Beranda</a></li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                    @endif
                    <li class="breadcrumb-item active">Sales Order</li>

                </ol>
            </div>
        </div>
    </div>
@stop

@section('adminlte_css')
    <style>
        .hide {
            display: none !important;
        }

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
        }

        .filter {
            margin: 5px;
        }

        .minimizechar {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 25ch;
        }

        .align-center {
            text-align: center;
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
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-proses_kirim-tab" data-toggle="pill"
                                        href="#pills-proses_kirim" role="tab" aria-controls="pills-proses_kirim"
                                        aria-selected="true">Dalam Proses</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-selesai_kirim-tab" data-toggle="pill"
                                        href="#pills-selesai_kirim" role="tab" aria-controls="pills-selesai_kirim"
                                        aria-selected="false">Selesai Proses</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-proses_kirim" role="tabpanel"
                                    aria-labelledby="pills-proses_kirim-tab">
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
                                                                <label for="jenis_penjualan">Pengiriman</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="belum_kirim" id="defaultCheck1" />
                                                                    <label class="form-check-label" for="defaultCheck1">
                                                                        Belum Dikirim
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="sebagian_kirim" id="defaultCheck2" />
                                                                    <label class="form-check-label" for="defaultCheck2">
                                                                        Sebagian Dikirim
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
                                                <table class="table" style="text-align:center;" id="showtable">
                                                    <thead>
                                                        <th>No</th>
                                                        <th>No SO</th>
                                                        <th>No PO</th>
                                                        <th>Batas Pengiriman</th>
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
                                <div class="tab-pane fade show" id="pills-selesai_kirim" role="tabpanel"
                                    aria-labelledby="pills-selesai_kirim-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table" style="text-align:center; width:100%;"
                                                    id="selesaitable">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2">No</th>
                                                            <th rowspan="2">No SO</th>
                                                            <th rowspan="2">No PO</th>
                                                            <th colspan="2">Pengiriman</th>
                                                            <th rowspan="2">Customer</th>
                                                            {{-- <th rowspan="2">Alamat</th> --}}
                                                            <th rowspan="2">Keterangan</th>
                                                            <th rowspan="2">Aksi</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Awal</th>
                                                            <th>Akhir</th>
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
                        <div id="modal-overlay" class="overlay"></div>
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

        </div>
    </section>
@stop
@section('adminlte_js')
    <script>
        $(function() {
            var showtable = $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/logistik/so/data/semua',
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
                        className: 'align-center nowrap-text',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'so',
                        className: 'nowrap-text'
                    },
                    {
                        data: 'no_po',
                        className: 'nowrap-text'
                    },
                    {
                        data: 'batas',
                        className: 'align-center nowrap-text',
                    },
                    {
                        data: 'nama_customer',
                        className: 'align-center nowrap-text minimizechar'
                    },
                    {
                        data: 'ket',
                        className: 'align-center nowrap-text minimizechar',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'status',
                        className: 'align-center nowrap-text',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'button',
                        className: 'align-center nowrap-text',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $(document).on('hidden.bs.modal', '#noserimodal', function(event) {
                $('#batalmodal').find('#modal-overlay').addClass('hide');
            });

            $("#showtable").on('click', '.batalmodal', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                var jenis = $(this).data('jenis');
                $.ajax({
                    url: '/logistik/so/cancel/' + id,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#batalmodal').modal("show");
                        $('#batal').html(result).show();
                        produktable(id, jenis);
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

            function produktable(id, jenis) {
                $('#produktable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/logistik/so/data/detail/belum_kirim/' + id + '/' + jenis,
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'nama_produk',
                        },
                        {
                            data: 'jumlah',
                            orderable: false,
                            searchable: false,
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'array_check',
                            className: 'hide'
                        },
                        {
                            data: 'aksi',
                            orderable: false,
                            searchable: false,
                            className: 'align-center nowrap-text'
                        }
                    ],
                });
            }

            $(document).on('click', '#pills-selesai_kirim-tab', function() {
                selesaitable();
            });

            function selesaitable() {
                var selesaitable = $('#selesaitable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/logistik/so/data/selesai',
                        'dataType': 'json',
                        'type': 'GET',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            className: 'align-center nowrap-text',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'so',
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'no_po',
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'tgl_awal',
                            className: 'align-center nowrap-text',
                        },
                        {
                            data: 'tgl_akhir',
                            className: 'align-center nowrap-text',
                        },
                        {
                            data: 'nama_customer',
                            className: 'align-center minimizechar'
                        },
                        {
                            data: 'ket',
                            className: 'align-center minimizechar',
                            orderable: false,
                            searchable: false
                        }, {
                            data: 'button',
                            className: 'align-center nowrap-text',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            }

            $(document).on('click', '#produktable .noseri', function(event) {
                event.preventDefault();
                var array = $(this).closest('tr').find('div[name="array_check[]"]').text();
                var id = $(this).attr('data-id');
                $('#batalmodal').find('#modal-overlay').removeClass('hide');
                $('#noserimodal').modal("show");
                noseritable(id, array);
            });

            function noseritable(id, array) {
                $('#noseritable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    autowidth: true,
                    ajax: {
                        'url': '/api/logistik/so/noseri/detail/belum_kirim/' + id + '/' + array,
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
                            className: 'nowrap-text align-center',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'no_seri',
                            className: 'nowrap-text align-center',
                            orderable: true,
                            searchable: true
                        }
                    ]
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
                    var x = ['semua'];
                }
                $('#showtable').DataTable().ajax.url('/logistik/so/data/' + x).load();
                return false;
            });

        })
    </script>
@stop
