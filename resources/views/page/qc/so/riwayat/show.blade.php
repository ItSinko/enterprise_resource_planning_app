@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Riwayat Pengujian</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '23')
                        <li class="breadcrumb-item"><a href="{{ route('qc.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Riwayat Pengujian</li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Riwayat Pengujian</li>
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

        #detmodal:hover {
            color: #4682B4;
            text-shadow: 2px 2px 4px #4682B4;
        }

        #detmodal:active {
            color: #708090;
        }

        .nowrap-text {
            white-space: nowrap;
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
                            <!-- <div class="row">
                                    <div class="col-12">
                                        <span class="float-right filter">
                                            <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-filter"></i> Filter
                                            </button>
                                            <div class="dropdown-menu">
                                                <div class="px-3 py-3">
                                                    <div class="form-group">
                                                        <label for="jenis_penjualan">Status</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="ekatalog" id="defaultCheck1" />
                                                            <label class="form-check-label" for="defaultCheck1">
                                                                E-Catalogue
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2" />
                                                            <label class="form-check-label" for="defaultCheck2">
                                                                SPA
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2" />
                                                            <label class="form-check-label" for="defaultCheck2">
                                                                SPB
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
                                </div> -->

                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table" style="text-align:center;" id="showtable">
                                            <thead>
                                                <tr>
                                                    <th class="nowrap">No</th>
                                                    <th class="nowrap">No SO</th>
                                                    <th>Nama Produk</th>
                                                    <th class="nowrap">Tanggal Pengujian</th>
                                                    <th class="nowrap">Tanggal Selesai</th>
                                                    <th class="nowrap">Jumlah</th>
                                                    <th class="nowrap">Aksi</th>
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
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-info">
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
            var showtable = $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/qc/so/riwayat/data',
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
                        data: 'so'
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'tgl_mulai'
                    },
                    {
                        data: 'tgl_selesai'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'button'
                    }
                ]
            })

            function noseritable(id, jenis) {
                $('#noseritable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'type': 'POST',
                        'datatype': 'JSON',
                        'url': '/api/qc/so/riwayat/detail/' + id + '/' + jenis,
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
                            visible: jenis == "produk" ? true : false
                        },
                        {
                            data: 'hasil',
                            visible: jenis == "produk" ? true : false
                        },
                        {
                            data: 'tanggal_uji',
                            visible: jenis == "part" ? true : false
                        },
                        {
                            data: 'jumlah_ok',
                            visible: jenis == "part" ? true : false
                        },
                        {
                            data: 'jumlah_nok',
                            visible: jenis == "part" ? true : false
                        }
                    ]
                })
            }

            function select_produk(id, jenis) {
                $('.detail_produk').select2({
                    placeholder: 'Pilih Produk',
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        delay: 250,
                        type: 'GET',
                        url: '/api/qc/so/riwayat/select/' + id,
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
                            console.log(data);
                            return {
                                results: $.map(data, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.gudang_barang_jadi.produk.nama + ' ' +
                                            obj.gudang_barang_jadi.nama
                                    };
                                })
                            };
                        },
                    }
                }).change(function() {
                    var ids = $(this).val();
                    noseritable(ids, jenis);
                });
            }

            $(document).on('click', '.detailmodal', function(event) {
                event.preventDefault();
                var penjualan_produk_id = $(this).attr('data-attr');
                var produk_count = $(this).attr('data-count');
                var produk_id = $(this).attr('data-produk');
                var produk_jenis = $(this).attr('data-jenis');
                var id = $(this).attr('data-id');
                $.ajax({
                    url: "/api/qc/so/riwayat/detail_modal/" + id + "/" + produk_jenis,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#detailmodal').modal("show");
                        $('#detail').html(result).show();
                        console.log("data " + id);
                        // $("#editform").attr("action", href);
                        if (produk_jenis == "produk") {
                            if (produk_count <= 1) {
                                noseritable(produk_id, produk_jenis);
                            } else {
                                select_produk(id, produk_jenis);
                            }
                        } else {
                            noseritable(id, produk_jenis);
                        }
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
