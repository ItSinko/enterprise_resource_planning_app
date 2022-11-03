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
                    @if (Auth::user()->Karyawan->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('as.so.show') }}">Sales Order</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Penjualan</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
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

        @media screen and (min-width: 1440px) {

            section {
                font-size: 14px;
            }

            #detailmodal {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            h4 {
                font-size: 20px;
            }
        }

        @media screen and (max-width: 1439px) {

            section {
                font-size: 14px;
            }

            h4 {
                font-size: 18px;
            }

            #detailmodal {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }
        }

        @media screen and (max-width: 992px) {
            .align-md {
                text-align: center;
            }
        }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card-deck" width="100%">
                        <div class="card col-4 align-items-stretch">
                            <div class="card-body">
                                @if ($jenis == 'produk')
                                    <h5>Info Produk</h5>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="margin">
                                                <div><small class="text-muted">Nama Produk</small></div>
                                                <div><b id="no_akn">{{ $d->PenjualanProduk->nama }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Jumlah Produk</small></div>
                                                <div><b id="no_so">{{ $d->jumlah }}</b></div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <h5>Info Part</h5>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="margin">
                                                <div><small class="text-muted">Nama Part</small></div>
                                                <div><b id="no_akn">{{ $d->Sparepart->nama }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Jumlah Produk</small></div>
                                                <div><b id="no_so">{{ $d->jumlah }}</b></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card col-8 align-items-stretch">
                            <div class="card-body">
                                <h5>Info Penjualan</h5>
                                @if (isset($d->Pesanan->Ekatalog))
                                    <div class="row">
                                        <div class="col-lg-5 col-md-12 align-md">
                                            <div class="margin">
                                                <div><small class="text-muted">Distributor & Instansi</small></div>
                                            </div>
                                            <div class="margin">
                                                <b id="distributor">{{ $d->pesanan->Ekatalog->customer->nama }}</b><small>
                                                    (Distributor)</small>
                                            </div>
                                            <div class="margin">
                                                <div><b id="no_akn">{{ $d->pesanan->Ekatalog->satuan }}</b></div>
                                                <small>({{ $d->pesanan->Ekatalog->instansi }})</small>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-4">
                                            <div class="margin">
                                                <div><small class="text-muted">No AKN</small></div>
                                                <div><b id="no_akn">{{ $d->pesanan->Ekatalog->no_paket }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">No SO</small></div>
                                                <div><b id="no_so">{{ $d->pesanan->Ekatalog->pesanan->so }}</b></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-md-4">
                                            <div class="margin">
                                                <div><small class="text-muted">No PO</small></div>
                                                <div><b id="no_so">{{ $d->pesanan->no_po }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Batas Pengiriman</small></div>
                                                <div>
                                                    <b>{{ date('d-m-Y', strtotime($d->pesanan->Ekatalog->tgl_kontrak)) }}</b>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-md-4">
                                            <div class="margin">
                                                <div><small class="text-muted">Status</small></div>
                                                <div>{!! $status !!}</div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(isset($d->Pesanan->Spa))
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 align-md">
                                            <div class="margin">
                                                <div><small class="text-muted">Customer</small></div>
                                            </div>
                                            <div class="margin">
                                                <b id="distributor">{{ $d->pesanan->Spa->customer->nama }}</b>
                                            </div>
                                            <div class="margin">
                                                <div><b id="no_akn">{{ $d->pesanan->Spa->customer->alamat }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><b id="no_akn">{{ $d->pesanan->Spa->customer->Provinsi->nama }}</b>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <div class="margin">
                                                <div><small class="text-muted">No SO</small></div>
                                                <div><b id="no_so">{{ $d->pesanan->Spa->pesanan->so }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Status</small></div>
                                                <div>{!! $status !!}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <div class="margin">
                                                <div><small class="text-muted">No PO</small></div>
                                                <div><b id="no_so">{{ $d->pesanan->no_po }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Tanggal PO</small></div>
                                                <div><b
                                                        id="no_so">{{ date('d-m-Y', strtotime($d->pesanan->tgl_po)) }}</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(isset($d->Pesanan->Spb))
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 align-md">
                                            <div class="margin">
                                                <div><small class="text-muted">Customer</small></div>
                                            </div>
                                            <div class="margin">
                                                <b id="distributor">{{ $d->pesanan->Spb->customer->nama }}</b>
                                            </div>
                                            <div class="margin">
                                                <div><b id="no_akn">{{ $d->pesanan->Spb->customer->alamat }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><b id="no_akn">{{ $d->pesanan->Spb->customer->Provinsi->nama }}</b>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <div class="margin">
                                                <div><small class="text-muted">No SO</small></div>
                                                <div><b id="no_so">{{ $d->pesanan->Spb->pesanan->so }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Status</small></div>
                                                <div>{!! $status !!}</div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <div class="margin">
                                                <div><small class="text-muted">No PO</small></div>
                                                <div><b id="no_so">{{ $d->pesanan->no_po }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Tanggal PO</small></div>
                                                <div><b
                                                        id="no_so">{{ date('d-m-Y', strtotime($d->pesanan->tgl_po)) }}</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Info Pengiriman</h5>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table" style="text-align:center; width:100%;"
                                            id="selesaikirimtable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Surat Jalan</th>
                                                    <th>Tanggal Kirim</th>
                                                    <th>Pengirim / Ekspedisi</th>
                                                    <th>Nama Produk</th>
                                                    <th>Jumlah</th>
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
                    </div>
                </div>
            </div>

            <div class="modal fade" id="detailmodal" role="dialog" aria-labelledby="detailmodal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header">
                            <h4 class="modal-title">Detail</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="detail">

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
            var jenis = "{{ $jenis }}";
            var selesaikirimtable = $('#selesaikirimtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/as/so/detail/' + '{{ $d->id }}' + '/' + '{{ $jenis }}',
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
                    data: 'no',

                }, {
                    data: 'tgl_kirim',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'pengirim',

                }, {
                    data: 'nama_produk',

                }, {
                    data: 'jumlah',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'button',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false,
                    visible: jenis == "part" ? false : true,
                }]

            });

            function showtable(id) {
                $('#showtable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'type': 'POST',
                        'datatype': 'JSON',
                        'url': '/api/logistik/so/noseri/detail/selesai_kirim/data/' + id,
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
                            data: 'no_seri'
                        }
                    ]
                })
            }

            $('#selesaikirimtable').on('click', '.detailmodal', function() {
                var data = $(this).attr('data-id');
                $('#selesaikirimtable').find('tr').removeClass('bgcolor');
                $(this).closest('tr').addClass('bgcolor');

                $.ajax({
                    url: "/api/logistik/so/noseri/detail/selesai_kirim/" + data,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#detailmodal').modal("show");
                        $('#detail').html(result).show();
                        showtable(data);
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
            })
        })
    </script>
@stop
