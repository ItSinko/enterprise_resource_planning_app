@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Riwayat Pengiriman</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '15')
                        <li class="breadcrumb-item"><a href="{{ route('logistik.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Riwayat Pengiriman</li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Riwayat Pengiriman</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        li.list-group-item {
            border: 0 none;
        }

        .smtxt {
            font-size: 13px;
        }

        #showtable {
            text-align: center;
            white-space: nowrap;
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
            text-align: right;
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
            max-width: 25ch;
        }

        .dropdown-toggle:hover {
            color: #4682B4;
        }

        .dropdown-toggle:active {
            color: #C0C0C0;
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

@section('content_header')
    <h1 class="m-0 text-dark">Riwayat Pengiriman</h1>
@stop

@section('content')
    <section class="section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <span class="float-right filter">
                                        <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <form id="filter_logistik">
                                            <div class="dropdown-menu">
                                                <div class="px-3 py-3">
                                                    <div class="form-group">
                                                        <label for="pengiriman">Pengiriman</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="ekspedisi" name="pengiriman[]" id="pengiriman1" />
                                                            <label class="form-check-label" for="pengiriman1">
                                                                Ekspedisi
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="nonekspedisi" name="pengiriman[]" id="pengiriman2" />
                                                            <label class="form-check-label" for="pengiriman2">
                                                                Non Ekspedisi
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="provinsi">Provinsi</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="2"
                                                                name="provinsi[]" id="provinsi1" />
                                                            <label class="form-check-label" for="provinsi1">
                                                                Jawa
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                name="provinsi[]" id="provinsi2" />
                                                            <label class="form-check-label" for="provinsi2">
                                                                Luar Jawa
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="jenis_penjualan">Jenis Penjualan</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="ekat"
                                                                name="jenis_penjualan[]" id="jenis_penjualan1" />
                                                            <label class="form-check-label" for="jenis_penjualan1">
                                                                Ekatalog
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="spa"
                                                                name="jenis_penjualan[]" id="jenis_penjualan2" />
                                                            <label class="form-check-label" for="jenis_penjualan2">
                                                                SPA
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="spb"
                                                                name="jenis_penjualan[]" id="jenis_penjualan3" />
                                                            <label class="form-check-label" for="jenis_penjualan3">
                                                                SPB
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <span class="float-right">
                                                            <button class="btn btn-primary" id="filter_logistik"
                                                                type="submit">
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
                                        <table class="table" id="showtable" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No SO</th>
                                                    <th>No SJ</th>
                                                    <th>Ekspedisi</th>
                                                    <th>No Resi</th>
                                                    <th>Tanggal Kirim</th>
                                                    <th>Nama Customer</th>
                                                    <th>Provinsi</th>
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
                    </div>
                </div>
                <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header bg-warning">
                                <h4 class="modal-title">Edit</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="edit">

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
            $('#showtable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/logistik/pengiriman/riwayat/data/semua/semua/semua',
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
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'so'
                    },
                    {
                        data: 'sj',

                    },
                    {
                        data: 'ekspedisi',

                    },
                    {
                        data: 'no_resi',

                    }, {
                        data: 'tgl_kirim',

                    }, {
                        data: 'nama_customer',

                    }, {
                        data: 'provinsi',

                    }, {
                        data: 'status',

                    }, {
                        data: 'button',

                    }
                ]
            });

            $('#filter_logistik').submit(function() {
                var values_pengiriman = [];
                $('input[name="pengiriman[]"]:checked').each(function() {
                    values_pengiriman.push($(this).val());
                });

                var values_provinsi = [];
                $('input[name="provinsi[]"]:checked').each(function() {
                    values_provinsi.push($(this).val());
                });

                var values_jenis_penjualan = [];
                $('input[name="jenis_penjualan[]"]:checked').each(function() {
                    values_jenis_penjualan.push($(this).val());
                });

                if (values_pengiriman != 0) {
                    var x = values_pengiriman;
                } else {
                    var x = ['semua'];
                }

                if (values_provinsi != 0) {
                    var y = values_provinsi;
                } else {
                    var y = ['semua'];
                }

                if (values_jenis_penjualan != 0) {
                    var z = values_jenis_penjualan;
                } else {
                    var z = ['semua'];
                }

                $('#showtable').DataTable().ajax.url('/api/logistik/pengiriman/riwayat/data/' + x + '/' +
                    y + '/' + z).load();
                return false;
            });

        })
    </script>
@endsection
