@extends('adminlte.page')

@section('title', 'ERP')

@section('adminlte_css')
    <style>
        .margin-right {
            margin-right: 5px;
        }

        .group0 {
            background-color: steelblue;
            color: #fff;
        }

        .group1 {
            background-color: #DDE4EE;
            color: #5487BA;
        }

        table tr td:nth-child(3),
        table tr td:nth-child(4) {
            text-align: center;
        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .borderright {
            border-right: 1px solid #E1EBF2;
        }

        .form-inline {
            display: flex;
            flex-flow: row wrap;
            align-items: center;
            margin: 20px;
        }

        .form-inline label {
            margin: 5px 10px 5px 0;
        }

        .form-inline input {
            vertical-align: middle;
            margin: 5px 10px 5px 0;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        .nowraptxt {
            white-space: nowrap;
        }

        .tabnum {
            font-variant-numeric: tabular-nums;
        }

        /* .form-inline button {
            padding: 10px 20px;
            background-color: dodgerblue;
            border: 1px solid #ddd;
            color: white;
            cursor: pointer;
            } */

        .form-inline button:hover {
            background-color: darkgrey;
        }

        #customer_id {
            width: 70%;
        }

        #tahun {
            width: 70%;
        }

        #btntambah {
            margin-bottom: 10px;
        }

        @media (min-width: 993px) {
            body {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }
        }

        @media (max-width: 992px) {
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .form-inline input {
                margin: 10px 0;
            }

            .form-inline {
                flex-direction: column;
                align-items: stretch;
            }

            #customer_id {
                width: 100%;
            }

            #tahun {
                width: 100%;
            }

            .form-inline button {
                float: right;
            }

            #btntambah {
                margin-bottom: 5px;
            }
        }
    </style>
@stop

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Bill Of Material</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '26' || Auth::user()->Karyawan->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Bill Of Material</li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Bill Of Material</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div>
                                            <span class="float-right" id="btntambah"><a
                                                    href="{{ route('penjualan.rencana.create') }}"
                                                    class="btn btn-outline-info"><i class="fas fa-plus"></i>&nbsp;Tambah
                                                    BOM</a></span>
                                            <span class="float-right margin-right dropdown" id="btnfilter">
                                                <button type="button" class="btn btn-outline-warning dropdown-toggle"
                                                    id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i class="fas fa-filter"></i>&nbsp;Filter</button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                    <form class="px-4 py-3">
                                                        <h6 class="dropdown-header">Kelompok Produk</h6>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="kelompok_produk1" name="kelompok_produk[]">
                                                            <label class="form-check-label" for="kelompok_produk1">
                                                                Alat Kesehatan
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="kelompok_produk2" name="kelompok_produk[]">
                                                            <label class="form-check-label" for="kelompok_produk2">
                                                                Sarana Kesehatan
                                                            </label>
                                                        </div>
                                                        <button type="submit"
                                                            class="btn btn-primary float-right">Cari</button>
                                                    </form>
                                                </div>
                                            </span>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="showtable" style="width:100%;">
                                                <thead style="text-align:center;">
                                                    <tr>
                                                        <th>Kelompok Produk</th>
                                                        <th>Kategori Produk</th>
                                                        <th>Kode Produk</th>
                                                        <th>Nama Produk</th>
                                                        <th>Jumlah BOM</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Alat Kesehatan</td>
                                                        <td>Cardiology</td>
                                                        <td><a class="detailmodal" data-toggle="modal"
                                                                data-target="detailmodal" data-id="1"
                                                                data-attr="{{ route('teknik.bom.data.produk', ['id' => '1']) }}">M1903171001</a>
                                                        </td>
                                                        <td>FOX-BABY</td>
                                                        <td>2</td>
                                                        <td><a href="{{ route('teknik.bom.detail', ['id' => '1']) }}"
                                                                class="fas fa-search"></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alat Kesehatan</td>
                                                        <td>Cardiology</td>
                                                        <td>M1903171002</td>
                                                        <td>FOX-1(N)</td>
                                                        <td>1</td>
                                                        <td><a href="{{ route('teknik.bom.detail', ['id' => '1']) }}"
                                                                class="fas fa-search"></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alat Kesehatan</td>
                                                        <td>Cardiology</td>
                                                        <td>M1903171003</td>
                                                        <td>FOX-3</td>
                                                        <td>1</td>
                                                        <td><a href="{{ route('teknik.bom.detail', ['id' => '1']) }}"
                                                                class="fas fa-search"></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alat Kesehatan</td>
                                                        <td>Anesthesia</td>
                                                        <td>M1235171001</td>
                                                        <td>PROMIST 1</td>
                                                        <td>1</td>
                                                        <td><a href="{{ route('teknik.bom.detail', ['id' => '1']) }}"
                                                                class="fas fa-search"></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alat Kesehatan</td>
                                                        <td>Anesthesia</td>
                                                        <td>M1235171002</td>
                                                        <td>Ultra Mist</td>
                                                        <td>1</td>
                                                        <td><a href="{{ route('teknik.bom.detail', ['id' => '1']) }}"
                                                                class="fas fa-search"></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alat Kesehatan</td>
                                                        <td>Anesthesia</td>
                                                        <td>M1235171003</td>
                                                        <td>PROMIST 3</td>
                                                        <td>1</td>
                                                        <td><a href="{{ route('teknik.bom.detail', ['id' => '1']) }}"
                                                                class="fas fa-search"></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alat Kesehatan</td>
                                                        <td>Anesthesia</td>
                                                        <td>M1235171004</td>
                                                        <td>SP10</td>
                                                        <td>1</td>
                                                        <td><a href="{{ route('teknik.bom.detail', ['id' => '1']) }}"
                                                                class="fas fa-search"></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alat Kesehatan</td>
                                                        <td>Anesthesia</td>
                                                        <td>M1235171005</td>
                                                        <td>DS-PRO100</td>
                                                        <td>1</td>
                                                        <td><a href="{{ route('teknik.bom.detail', ['id' => '1']) }}"
                                                                class="fas fa-search"></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alat Kesehatan</td>
                                                        <td>Patient Scale</td>
                                                        <td>M1555171001</td>
                                                        <td>Baby Digit One</td>
                                                        <td>1</td>
                                                        <td><a href="{{ route('teknik.bom.detail', ['id' => '1']) }}"
                                                                class="fas fa-search"></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alat Kesehatan</td>
                                                        <td>Patient Scale</td>
                                                        <td>M1555171002</td>
                                                        <td>Digit Pro</td>
                                                        <td>1</td>
                                                        <td><a href="{{ route('teknik.bom.detail', ['id' => '1']) }}"
                                                                class="fas fa-search"></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Alat Kesehatan</td>
                                                        <td>Patient Scale</td>
                                                        <td>M1555171003</td>
                                                        <td>Digit One Baby</td>
                                                        <td>1</td>
                                                        <td><a href="{{ route('teknik.bom.detail', ['id' => '1']) }}"
                                                                class="fas fa-search"></a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="detailmodal" role="dialog" aria-labelledby="detailmodal"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content" style="margin: 10px">
                                <div class="modal-header yellow-bg">
                                    <h4 class="modal-title"><b>Ubah</b></h4>
                                </div>
                                <div class="modal-body" id="detail">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('adminlte_js')
    <script>
        $(function() {
            $('#showtable').DataTable({
                order: [
                    [1, 'asc'],
                    [0, 'asc']
                ],
                rowGroup: {
                    dataSrc: [1, 0]
                },
                columnDefs: [{
                    targets: [0, 1],
                    visible: false
                }],
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;
                    var columns = [0, 1]
                    for (c = 0; c < columns.length; c++) {
                        api.column(columns[c], {
                            page: 'current'
                        }).data().each(function(group, i) {
                            if (last !== group) {
                                $(rows).eq(i).before(
                                    '<tr class="group' + columns[c] +
                                    '" style=""><td colspan="8"><b>' + group +
                                    '</b></td></tr>'
                                );
                                last = group;
                            }
                        });
                    }

                }
            });

            $(document).on('click', '.detailmodal', function(event) {
                event.preventDefault();
                var href = $(this).attr('data-attr');
                $.ajax({
                    url: href,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#detailmodal').modal("show");
                        $('#detail').html(result).show();
                        console.log(result);
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
                });
            });
            //var table = $('#showtable').DataTable({
            //     "ajax": {
            //         'url': '/api/penjualan/rencana/show/0/0',
            //         'dataType': 'json',
            //         'type': 'POST',
            //         'headers': {
            //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
            //         }
            //     },
            //     // "scrollY": true,
            //     // "scrollX": true,
            //     // "scrollCollapse": true,
            //     // "fixedColumns": {
            //     //     left: 0
            //     // },
            //     // "columnDefs": [{
            //     //     "visible": false,
            //     //     "targets": groupColumn
            //     // }],
            //     // "order": [
            //     //     [groupColumn, 'asc']
            //     // ],
            //     // "displayLength": 10,
            //     // "drawCallback": function(settings) {
            //     //     var api = this.api();
            //     //     var rows = api.rows({
            //     //         page: 'current'
            //     //     }).nodes();
            //     //     var last = null;

            //     //     api.column(groupColumn, {
            //     //         page: 'current'
            //     //     }).data().each(function(group, i) {
            //     //         if (last !== group) {
            //     //             $(rows).eq(i).before(
            //     //                 '<tr class="group blue-text"><td colspan="8">' + group + '</td></tr>'
            //     //             );
            //     //             last = group;
            //     //         }
            //     //     });
            //     // }
            //});

            // // Order by the grouping
            // $('#showtable tbody').on('click', 'tr.group', function() {
            //     var currentOrder = table.order()[0];
            //     if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
            //         table.order([groupColumn, 'desc']).draw();
            //     } else {
            //         table.order([groupColumn, 'asc']).draw();
            //     }
            // });

            $('#customer_id').select2({
                placeholder: "Pilih Distributor",
                ajax: {
                    minimumResultsForSearch: 20,
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/customer/select_rencana/',
                    data: function(params) {
                        return {
                            term: params.term
                        }
                    },
                    processResults: function(data) {
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
            });


            $("#tahun").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        dataType: 'json',
                        url: "/api/penjualan/rencana/select_tahun",
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.tahun,
                                    id: el.id
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });


            $('#customer_id').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#tahun").attr('disabled', false);
                } else {
                    $("#tahun").attr('disabled', true);
                }
            });

            $('#tahun').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#btncari").attr('disabled', false);
                } else {
                    $("#btncari").attr('disabled', true);
                }
            });


            $('#filter').submit(function() {
                $("#parent_export").attr('disabled', false);
                var customer_id = $('#customer_id').val();
                var tahun = $('#tahun').val();

                $('#showtable').DataTable().ajax.url('/api/penjualan/rencana/show/' + customer_id + '/' +
                    tahun).load();

                var link = '/penjualan/rencana/laporan/' + customer_id + '/' + tahun;
                var link2 = '/penjualan/rencana/laporan_detail/' + customer_id + '/' + tahun;

                console.log(link);
                $('#lap_semua').attr({
                    href: link
                });

                $('#lap_detail').attr({
                    href: link2
                });

                return false;
            });

        });
    </script>

@stop
