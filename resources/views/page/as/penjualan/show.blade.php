@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Penjualan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Penjualan</li>
                    @elseif(Auth::user()->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Penjualan</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->

@stop

@section('adminlte_css')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        .filter {
            margin: 5px;
        }

        thead {
            text-align: center;
        }

        td {
            text-align: center;
            white-space: nowrap;
        }

        #urgent {
            color: #dc3545;
            font-weight: 600;
        }

        #warning {
            color: #FFC700;
            font-weight: 600;
        }

        #info {
            color: #3a7bb0;
            font-weight: 600;
        }

        .minimizechar {
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 13ch;
        }

        .hide {
            display: none;
        }

        .dropdown-toggle:hover {
            color: #4682B4;
        }

        .dropdown-toggle:active {
            color: #C0C0C0;
        }

        td.details-control {
            content: "\f055";
            font-family: FontAwesome;
            left: -5px;
            position: absolute;
            top: 0;
        }

        tr.details td.details-control {
            background: url('../resources/details_close.png') no-repeat center center;
        }

        #detailekat {
            background-color: #E9DDE5;

        }

        #detailspa {
            background-color: #FFE6C9;
        }

        #detailspb {
            background-color: #E1EBF2;
            /* color: #7D6378; */

        }

        .removeshadow {
            box-shadow: none;
        }

        .align-center {
            text-align: center;
        }

        .bordertopnone {
            border-top: 0;
            border-left: 0;
            border-right: 0;
            border-bottom: 0;
            vertical-align: top;
        }

        .margin {
            margin-left: 10px;
            margin-right: 10px;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        @media screen and (min-width: 992px) {

            body {
                font-size: 14px;
            }

            #detailmodal {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .overflowy {
                max-height: 550px;
                width: auto;
                overflow-y: scroll;
                box-shadow: none;
            }
        }

        @media screen and (max-width: 991px) {

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

            .overflowy {
                max-height: 450px;
                width: auto;
                overflow-y: scroll;
                box-shadow: none;
            }
        }

    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-belum_proses-tab" data-toggle="pill" href="#pills-belum_proses" role="tab"
                                aria-controls="pills-belum_proses" aria-selected="true">Belum Proses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-selesai_proses-tab" data-toggle="pill" href="#pills-selesai_proses" role="tab"
                                aria-controls="pills-selesai_proses" aria-selected="false">Selesai Proses</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-belum_proses" role="tabpanel"
                            aria-labelledby="pills-spa-tab">
                            <div class="row">
                                <div class="col-12">
                                    <span class="float-right filter">
                                        <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <form id="filter_spa">
                                            <div class="dropdown-menu">
                                                <div class="px-3 py-3">
                                                    <div class="form-group">
                                                        <label for="jenis_penjualan">Status</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="7"
                                                                id="status1" name="status_spa[]" />
                                                            <label class="form-check-label" for="status1">
                                                                Penjualan
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="9"
                                                                id="status4" name="status_spa[]" />
                                                            <label class="form-check-label" for="status4">
                                                                PO
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="6"
                                                                id="status5" name="status_spa[]" />
                                                            <label class="form-check-label" for="status5">
                                                                Gudang
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="8"
                                                                id="status6" name="status_spa[]" />
                                                            <label class="form-check-label" for="status6">
                                                                QC
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="13"
                                                                id="status7" name="status_spa[]" />
                                                            <label class="form-check-label" for="status7">
                                                                Terkirim Sebagian
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="11"
                                                                id="status8" name="status_spa[]" />
                                                            <label class="form-check-label" for="status8">
                                                                Kirim
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <span class="float-right">
                                                            <button class="btn btn-primary" id="filter_spa" type="submit">
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
                                        <table class="table table-hover" id="spatable" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Jenis</th>
                                                    <th>Nomor SO</th>
                                                    <th>Nomor PO</th>
                                                    <th>Tanggal Order</th>
                                                    <th>Customer</th>
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
                        <div class="tab-pane fade show" id="pills-selesai_proses" role="tabpanel" aria-labelledby="pills-selesai_proses-tab">
                            <div class="row">
                                <div class="col-12">
                                    <span class="float-right filter">
                                        <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <form id="filter_spb">
                                            <div class="dropdown-menu">
                                                <div class="px-3 py-3">
                                                    <div class="form-group">
                                                        <label for="jenis_penjualan">Status</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="7"
                                                                id="status_spb1" name="status_spb[]" />
                                                            <label class="form-check-label" for="status_spb1">
                                                                Penjualan
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="9"
                                                                id="status_spb2" name="status_spb[]" />
                                                            <label class="form-check-label" for="status_spb2">
                                                                PO
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="6"
                                                                id="status_spb3" name="status_spb[]" />
                                                            <label class="form-check-label" for="status_spb3">
                                                                Gudang
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="8"
                                                                id="status_spb4" name="status_spb[]" />
                                                            <label class="form-check-label" for="status_spb4">
                                                                QC
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="13"
                                                                id="status_spb5" name="status_spb[]" />
                                                            <label class="form-check-label" for="status_spb5">
                                                                Terkirim Sebagian
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="11"
                                                                id="status_spb6" name="status_spb[]" />
                                                            <label class="form-check-label" for="status_spb6">
                                                                Kirim
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <span class="float-right">
                                                            <button class="btn btn-primary" id="filter_spb" type="submit">
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
                                        <table class="table table-hover" id="spbtable" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Jenis</th>
                                                    <th>Nomor SO</th>
                                                    <th>Nomor PO</th>
                                                    <th>Tanggal Order</th>
                                                    <th>Customer</th>
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

                <div class="modal fade" id="komentarmodal" tabindex="-1" role="dialog" aria-labelledby="komentarmodal"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header bg-warning">
                                <h4 id="modal-title">Edit Komentar</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="komentar">

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
            var options = {
                series: [0, 0, 0],
                chart: {
                    height: 300,
                    type: 'radialBar',
                },
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            name: {
                                fontSize: '20px',
                            },
                            value: {
                                fontSize: '14px',
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                color: '#5F7A90',
                                // formatter: function (val) {
                                //     console.log(val);
                                //     // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                //     return val
                                // }
                            }
                        }
                    }
                },
                colors: ['#EA8B1B', '#FFC700', '#456600'],
                labels: ['Gudang', 'QC', 'Logistik'],
            };

            var spatable = $('#spatable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/as/penjualan/belum_proses',
                    "dataType": "json",
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
                        data: 'jenis'
                    },
                    {
                        data: 'so',
                    },
                    {
                        data: 'nopo'
                    },
                    {
                        data: 'tglpo',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_customer'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'button',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            function detailtabel_spa(id) {
                $('#detailtabel_spa').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/spa/paket/detail/' + id,
                        "dataType": "json",
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
                            data: 'nama_produk',
                        },
                        {
                            data: 'harga',
                            render: $.fn.dataTable.render.number(',', '.', 2),
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'jumlah',
                            className: 'nowrap-text align-center',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'total',
                            render: $.fn.dataTable.render.number(',', '.', 2),
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'button',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api(),
                            data;
                        // converting to interger to find total
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                        // computing column Total of the complete result
                        var jumlah_pesanan = api
                            .column(3)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        // computing column Total of the complete result
                        var total_pesanan = api
                            .column(4)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        var num_for = $.fn.dataTable.render.number(',', '.', 2).display;
                        $(api.column(0).footer()).html('Total');
                        $(api.column(3).footer()).html('Total');
                        $(api.column(4).footer()).html(num_for(total_pesanan));
                    },
                })
            }

            var spbtable = $('#spbtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/as/penjualan/selesai_proses',
                    "dataType": "json",
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
                        data: 'jenis'
                    },
                    {
                        data: 'so',
                    },
                    {
                        data: 'nopo'
                    },
                    {
                        data: 'tglpo'
                    },
                    {
                        data: 'nama_customer'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'button'
                    }
                ]
            });

            function detailtabel_spb(id) {
                $('#detailtabel_spb').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'type': 'POST',
                        'datatype': 'JSON',
                        'url': '/api/spb/paket/detail/' + id,
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
                            data: 'nama_produk',
                        },
                        {
                            data: 'harga',
                            render: $.fn.dataTable.render.number(',', '.', 2),
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'jumlah',
                            className: 'nowrap-text align-center',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'total',
                            render: $.fn.dataTable.render.number(',', '.', 2),
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'button',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api(),
                            data;
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };
                        // computing column Total of the complete result
                        var jumlah_pesanan = api
                            .column(3)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        // computing column Total of the complete result
                        var total_pesanan = api
                            .column(4)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        var num_for = $.fn.dataTable.render.number(',', '.', 2).display;
                        $(api.column(0).footer()).html('Total');
                        $(api.column(3).footer()).html('Total');
                        $(api.column(4).footer()).html(num_for(total_pesanan));
                    },
                })
            }

            $('#filter_spb').submit(function() {
                var values_spb = [];
                $('input[name="status_spb[]"]:checked').each(function() {
                    values_spb.push($(this).val());
                });
                // alert(values_spb);
                if (values_spb != 0) {
                    var x = values_spb;

                } else {
                    var x = ['semua'];
                }
                console.log(x);
                $('#spbtable').DataTable().ajax.url('/penjualan/penjualan/spb/data/' + x).load();
                return false;

            });

            $(document).on('click', '.detailmodal', function(event) {
                event.preventDefault();
                var href = $(this).attr('data-attr');
                var id = $(this).data("id");
                var label = $(this).data("target");
                $.ajax({
                    url: href,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#detailmodal').modal("show");
                        $('#detail').html(result).show();
                        var chart = new ApexCharts(document.querySelector("#chartproduk"), options);
                        chart.render();
                        if (label == 'spa') {
                            $('#detailmodal').find(".modal-header").removeClass(
                                'bg-purple bg-lightblue');
                            $('#detailmodal').find(".modal-header").addClass('bg-orange');
                            $('#detailmodal').find(".modal-header > h4").text('SPA');
                            detailtabel_spa(id);
                        } else {
                            $('#detailmodal').find(".modal-header").removeClass(
                                'bg-orange bg-purple');
                            $('#detailmodal').find(".modal-header").addClass('bg-lightblue');
                            $('#detailmodal').find(".modal-header > h4").text('SPB');
                            detailtabel_spb(id);
                        }
                        $('#detailmodal').find('[data-toggle="tooltip"]').tooltip();
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

            $(document).on('mouseenter', '#detailmodal [data-toggle="tooltip"]', function(event) {
                // var title = $(this).attr('title');
                // $('[data-toggle="tooltip"]').tooltip({ html: true, content: title });
            });

            $(document).on('click', '.komentarmodal', function(event) {
                event.preventDefault();
                var id = $(this).data("id");
                $.ajax({
                    url: '/as/so/edit/'+id,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#komentarmodal').modal("show");
                        $('#komentar').html(result).show();
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

            $(document).on('click', '#tabledetailpesan #lihatstok', function(){
                var id = $(this).attr('data-id');
                var produk = $(this).attr('data-produk');
                var array = [];
                $.ajax({
                    url: '/api/get_stok_pesanan',
                    data: {'id': id, 'jenis': produk},
                    type: 'GET',
                    dataType: 'json',
                    success: function(result) {


                        $('#count_qc').text(result.count_qc);
                        $('#count_log').text(result.count_log);

                        if(produk == 'paket'){
                            $('#nama_produk').text(result.penjualan_produk.nama);
                            array = [Math.round((result.count_gudang / result.count_jumlah) * 100), Math.round((result.count_qc / result.count_jumlah) * 100), Math.round((result.count_log/ result.count_jumlah) * 100)];
                            $('#count_gudang').text(result.count_gudang);
                        }else if(produk == 'variasi'){
                            $('#nama_produk').text(result.gudang_barang_jadi.produk.nama +" "+ result.gudang_barang_jadi.nama);
                            array = [Math.round((result.count_gudang / result.count_jumlah) * 100), Math.round((result.count_qc / result.count_jumlah) * 100), Math.round((result.count_log/ result.count_jumlah) * 100)];
                            $('#count_gudang').text(result.count_gudang);
                        }else{
                            $('#nama_produk').text(result.sparepart.nama);
                            array = [Math.round((result.jumlah / result.jumlah) * 100), Math.round((result.count_qc / result.jumlah) * 100), Math.round((result.count_log/ result.jumlah) * 100)];
                            $('#count_gudang').text(result.jumlah);
                        }
                        var chart = new ApexCharts(document.querySelector("#chartproduk"), options);
                        chart.render();
                        chart.updateSeries(array);
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
        })
    </script>
@stop
