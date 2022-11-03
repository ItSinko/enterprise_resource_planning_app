@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Sales Order</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Sales Order</li>
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
        .modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
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
            max-width: 30ch;
        }

        .nowraptxt {
            white-space: nowrap;
        }


        @media screen and (min-width: 992px) {
            body {
                font-size: 14px;
            }

            .dropdown-item {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 991px) {
            body {
                font-size: 12x;
            }

            .dropdown-item {
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
                                    <a class="nav-link active" id="pills-per_produk-tab" data-toggle="pill"
                                        href="#pills-per_produk" role="tab" aria-controls="pills-per_produk"
                                        aria-selected="true">Per Produk</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-per_sj-tab" data-toggle="pill" href="#pills-per_sj"
                                        role="tab" aria-controls="pills-per_sj" aria-selected="false">Per Surat
                                        Jalan</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-per_produk" role="tabpanel"
                                    aria-labelledby="pills-per_produk-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="float-right filter">
                                                <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                                <div class="dropdown-menu">
                                                    <div class="px-3 py-3">
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Pengiriman</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="ekatalog" id="defaultCheck1" />
                                                                <label class="form-check-label" for="defaultCheck1">
                                                                    Belum Dikirim
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="spa" id="defaultCheck2" />
                                                                <label class="form-check-label" for="defaultCheck2">
                                                                    Sebagian Dikirim
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="spa" id="defaultCheck2" />
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
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table" style="text-align:center;" id="showtable">
                                                    <thead>
                                                        <th>No</th>
                                                        <th>Nama Produk</th>
                                                        <th>No SO</th>
                                                        <th>Tanggal Kirim</th>
                                                        <th>Customer</th>
                                                        <th>Alamat</th>
                                                        <th>Provinsi</th>
                                                        <th>Keterangan</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </thead>
                                                    <tbody>
                                                        <!-- <tr>
                                                    <td>1</td>
                                                    <td>SO/EKAT/X/02/98</td>
                                                    <td>
                                                        31-10-2021
                                                    </td>
                                                    <td>CV. Cipta Jaya Mandiri</td>
                                                    <td>Jl Dr Wahidin Sudirohusodo</td>
                                                    <td>0841641741979</td>

                                                    <td><span class="badge green-text">Selesai</span></td>
                                                    <td>-</td>
                                                    <td>
                                                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="{{ route('as.so.list', ['id' => '1']) }}" data-id="1">
                                                            <i class="fas fa-search"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>SO/SPA/X/02/75</td>
                                                    <td>
                                                        08-11-2021
                                                    </td>
                                                    <td>PT. Emiindo Jaya Bersama</td>
                                                    <td>Jl Jaksa Agung Suprapto</td>
                                                    <td>0841641741979</td>

                                                    <td><span class="badge green-text">Selesai</span></td>
                                                    <td>-</td>
                                                    <td><a href=""></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>SO/SPB/X/21/75</td>
                                                    <td>03-11-2021</td>
                                                    <td>Bapak Muhajir</td>
                                                    <td>Jl RA Kartini</td>
                                                    <td>0841641741979</td>

                                                    <td><span class="badge green-text">Selesai</span></td>
                                                    <td>-</td>
                                                    <td><a href=""></td>
                                                </tr> -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade show" id="pills-per_sj" role="tabpanel"
                                    aria-labelledby="pills-per_sj-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="float-right filter">
                                                <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                                <form id="filter_riwayat">
                                                    <div class="dropdown-menu">
                                                        <div class="px-3 py-3">
                                                            <div class="form-group">
                                                                <label for="pengiriman_riwayat">Pengiriman</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="ekspedisi" name="pengiriman_riwayat[]"
                                                                        id="pengiriman_riwayat1" />
                                                                    <label class="form-check-label"
                                                                        for="pengiriman_riwayat1">
                                                                        Ekspedisi
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="nonekspedisi" name="pengiriman_riwayat[]"
                                                                        id="pengiriman_riwayat2" />
                                                                    <label class="form-check-label"
                                                                        for="pengiriman_riwayat2">
                                                                        Non Ekspedisi
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="provinsi_riwayat">Provinsi</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="2" name="provinsi_riwayat[]"
                                                                        id="provinsi_riwayat1" />
                                                                    <label class="form-check-label"
                                                                        for="provinsi_riwayat1">
                                                                        Jawa
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="1" name="provinsi_riwayat[]"
                                                                        id="provinsi_riwayat2" />
                                                                    <label class="form-check-label"
                                                                        for="provinsi_riwayat2">
                                                                        Luar Jawa
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="jenis_penjualan_riwayat">Jenis
                                                                    Penjualan</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="ekat" name="jenis_penjualan_riwayat[]"
                                                                        id="jenis_penjualan_riwayat1" />
                                                                    <label class="form-check-label"
                                                                        for="jenis_penjualan_riwayat1">
                                                                        Ekatalog
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="spa" name="jenis_penjualan_riwayat[]"
                                                                        id="jenis_penjualan_riwayat2" />
                                                                    <label class="form-check-label"
                                                                        for="jenis_penjualan_riwayat2">
                                                                        SPA
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="spb" name="jenis_penjualan_riwayat[]"
                                                                        id="jenis_penjualan_riwayat3" />
                                                                    <label class="form-check-label"
                                                                        for="jenis_penjualan_riwayat3">
                                                                        SPB
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <span class="float-right">
                                                                    <button class="btn btn-primary" id="filter_riwayat"
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
                                                <table class="table" id="riwayattable" style="width: 100%;">
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
                </div>
            </div>

            <div class="modal fade" id="detailmodal" tabindex="-1" role="dialog" aria-labelledby="detailmodal"
                aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header">
                            <h4 id="modal-title">Detail</h4>
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
            $('#pills-per_sj-tab').on('click', function() {
                riwayat();
            })

            function riwayat() {
                $('#riwayattable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/logistik/pengiriman/riwayat/data/semua/semua/semua',
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
                            data: 'button',

                        }
                    ]
                });
            }

            $('#filter_riwayat').submit(function() {
                var values_pengiriman = [];
                $('input[name="pengiriman_riwayat[]"]:checked').each(function() {
                    values_pengiriman.push($(this).val());
                });

                var values_provinsi = [];
                $('input[name="provinsi_riwayat[]"]:checked').each(function() {
                    values_provinsi.push($(this).val());
                });

                var values_jenis_penjualan = [];
                $('input[name="jenis_penjualan_riwayat[]"]:checked').each(function() {
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
                $('#riwayattable').DataTable().ajax.url('/api/logistik/pengiriman/riwayat/data/' + x + '/' +
                    y + '/' + z).load();
                return false;
            });
            $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/as/so/data',
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
                    orderable: false,
                    searchable: false
                }, {
                    data: 'nama_produk',
                    className: 'nowraptxt'
                }, {
                    data: 'so',
                    className: 'nowraptxt'
                }, {
                    data: 'tgl_kirim',
                }, {
                    data: 'nama_customer',
                    className: 'minimizechar',
                }, {
                    data: 'alamat',
                    className: 'minimizechar',
                }, {
                    data: 'provinsi',
                }, {
                    data: 'keterangan',
                    className: 'minimizechar',
                }, {
                    data: 'status',
                }, {
                    data: 'button',

                }]
            });

            $(document).on('click', '.detailmodal', function(event) {
                event.preventDefault();
                var href = $(this).attr('data-attr');
                var id = $(this).data('id');
                $.ajax({
                    url: href,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#detailmodal').modal("show");
                        $('#detail').html(result).show();
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

        })
    </script>
@stop
