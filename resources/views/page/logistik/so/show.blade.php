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
                @if(Auth::user()->divisi_id == "15")
                <li class="breadcrumb-item"><a href="{{route('logistik.dashboard')}}">Beranda</a></li>
                @elseif(Auth::user()->divisi_id == "2")
                <li class="breadcrumb-item"><a href="{{route('direksi.dashboard')}}">Beranda</a></li>
                @endif
                <li class="breadcrumb-item active">Sales Order</li>

            </ol>
        </div>
    </div>
</div>
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
                                <a class="nav-link active" id="pills-proses_kirim-tab" data-toggle="pill" href="#pills-proses_kirim" role="tab" aria-controls="pills-proses_kirim" aria-selected="true">Dalam Proses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-selesai_kirim-tab" data-toggle="pill" href="#pills-selesai_kirim" role="tab" aria-controls="pills-selesai_kirim" aria-selected="false">Selesai Proses</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-proses_kirim" role="tabpanel" aria-labelledby="pills-proses_kirim-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <span class="float-right filter">
                                            <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                                                <input class="form-check-input" type="checkbox" value="belum_kirim" id="defaultCheck1" />
                                                                <label class="form-check-label" for="defaultCheck1">
                                                                    Belum Dikirim
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="sebagian_kirim" id="defaultCheck2" />
                                                                <label class="form-check-label" for="defaultCheck2">
                                                                    Sebagian Dikirim
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="sudah_kirim" id="defaultCheck2" />
                                                                <label class="form-check-label" for="defaultCheck2">
                                                                    Sudah Dikirim
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
                                                    <th>Batas Pengiriman</th>
                                                    <th>Customer</th>
                                                    <th>Alamat</th>
                                                    <th>Telepon</th>
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
                            <div class="tab-pane fade show" id="pills-selesai_kirim" role="tabpanel" aria-labelledby="pills-selesai_kirim-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table" style="text-align:center; width:100%;" id="selesaitable">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">No</th>
                                                        <th rowspan="2">No SO</th>
                                                        <th rowspan="2">Tanggal Delivery</th>
                                                        <th colspan="2">Pengiriman</th>
                                                        <th rowspan="2">Customer</th>
                                                        <th rowspan="2">Alamat</th>
                                                        <th rowspan="2">Telepon</th>
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
    </div>
</section>
@stop
@section('adminlte_js')
<script>
    $(function() {
        var showtable = $('#showtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/logistik/so/data/semua',

                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
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
                    data: 'batas',
                    className: 'align-center nowrap-text',
                },
                {
                    data: 'nama_customer',
                    className: 'align-center nowrap-text minimizechar'
                },
                {
                    data: 'alamat',
                    className: 'align-center nowrap-text minimizechar'
                }, {
                    data: 'telp',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'ket',
                    className: 'align-center nowrap-text',
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

        })

        var selesaitable = $('#selesaitable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/logistik/so/data/selesai',

                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
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
                    data: 'batas',
                    className: 'align-center nowrap-text',
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
                    data: 'alamat',
                    className: 'align-center minimizechar'
                }, {
                    data: 'telp',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, {
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

        })

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

            $('#showtable').DataTable().ajax.url('/logistik/so/data/' + x).load();
            return false;
        });
    })
</script>
@stop