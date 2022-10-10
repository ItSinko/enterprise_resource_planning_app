@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Dashboard</h1>

@stop

@section('adminlte_css')
<style lang="scss">
    .modal-body{
        max-height: 80vh;
        overflow-y: auto;
    }
    table { border-collapse: collapse; empty-cells: show; }

    td { position: relative; }

    .foo {
        border-radius: 50%;
        float: left;
        width: 10px;
        height: 10px;
        align-items: center !important;
    }

    tr.line-through td:not(:nth-last-child(-n+2)):before {
        content: " ";
        position: absolute;
        left: 0;
        top: 35%;
        border-bottom: 1px solid;
        width: 100%;
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

    .separator {
        border-top: 1px solid #bbb;
        width: 90%;
    }

    .wb {
        word-break: break-all;
        white-space: normal;
    }

    .nowraptxt {
        white-space: nowrap;
    }

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

    .warning-bg {
        background-color: #FFC700;
        color: white;
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

    .tabnum {
        font-variant-numeric: tabular-nums;
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

    .card-detail {
        align-items: center;
        flex-direction: row;
        shadow: none;
        border: none;
    }

    .card-detail img {
        width: 25%;
        border-top-right-radius: 0;
        border-bottom-left-radius: calc(0.25rem - 1px);
    }

    /* .overflowcard {
            max-height:
            700px;
        } */

    .bg-chart-light{
        background: rgba(192, 192, 192, 0.2);
    }

    .bg-chart-orange{
        background: rgb(236, 159, 5);
    }

    .bg-chart-yellow{
        background: rgb(255, 221, 0);
    }

    .bg-chart-green{
        background: rgb(11, 171, 100);
    }

    .bg-chart-blue{
        background: rgb(8, 126, 225);
    }

    @media screen and (min-width: 1440px) {

        body {
            font-size: 14px;
        }

        #detailmodal {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }

        .overflowcard {
            max-height:
            680px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
        }

        .labelket {
            text-align: right;
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

        .overflowcard {
            max-height: 650px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
        }

        .labelket {
            text-align: right;
        }
    }

    @media screen and (max-width: 991px) {
        .labelket {
            text-align: left;
        }

        .overflowcard {
            max-height: 150px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
        }
    }
</style> --}}
@stop
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-5 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Penjualan 2021</h4>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-12 d-flex">
                                        <div class="small-box bg-success flex-fill d-flex flex-column">
                                            <div class="inner">
                                                <h3>{{$daftar_so}}</h3>
                                                <p>Daftar SO</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-truck"></i>
                                            </div>
                                            <a class="small-box-footer active mt-auto" id="pengirimanso">Detail <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 d-flex">
                                        <div class="small-box warning-bg flex-fill d-flex flex-column">
                                            <div class="inner">
                                                <h3>{{$belum_coo}}</h3>
                                                <p>Belum memiliki COO</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-certificate"></i>
                                            </div>
                                            <a class="small-box-footer mt-auto" id="sotanpacoo">Detail <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 d-flex">
                                        <div class="small-box bg-danger flex-fill d-flex flex-column">
                                            <div class="inner">
                                                <h3>{{$lewat_batas}}</h3>
                                                <p>Lewat Batas Kontrak</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </div>
                                            <a class="small-box-footer mt-auto" id="lewatbataskontrak">Detail <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="pengirimansotable" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th colspan=5>

                                                            <h5><b class="text-success">Daftar SO milik Logistik</b></h5>

                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>No SO</th>
                                                        <th>Batas Pengiriman</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>


                                            <table class="table table-hover hide" id="sotanpacootable" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th colspan=4>
                                                            <h5><b class="text-warning">Daftar SO belum memiliki COO</b></h5>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>No SO</th>
                                                        <th>Batas Kontrak</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>

                                            <table class="table table-hover hide" id="lewatbataskontraktable" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th colspan=5>
                                                            <h5><b class="text-danger">Lewat Batas Kontrak</b></h5>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>No SO</th>
                                                        <th>Batas Kontrak</th>
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
                    <div class="col-lg-7 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Sales Order</h4>
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex">
                                        <div class="small-box red-text flex-fill align-center">
                                            <div class="inner">
                                                <h3>{{$penjualan}}</h3>
                                                <p>Penjualan</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex">
                                        <div class="small-box orange-text flex-fill align-center">
                                            <div class="inner">
                                                <h3>{{$gudang}}</h3>
                                                <p>Gudang</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex">
                                        <div class="small-box yellow-text flex-fill align-center">
                                            <div class="inner">
                                                <h3>{{$qc}}</h3>
                                                <p>QC</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12 d-flex">
                                        <div class="small-box green-text flex-fill align-center">
                                            <div class="inner">
                                                <h3>{{$logistik}}</h3>
                                                <p>Logistik</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="sotable">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>No PO</th>
                                                        <th>No AKN</th>
                                                        <th>Customer</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
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
        <div class="modal fade" id="detailmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header bg-warning">
                        <h4>Detail</h4>
                    </div>
                    <div class="modal-body" id="detail">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="somodal" tabindex="-1" role="dialog" aria-labelledby="somodal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header">
                        <h4 id="modal-title">Penjualan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="so">

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
        var optionpie = {
            type: 'pie',
            data: {
                labels: [
                    'Belum Diproses',
                    'Gudang',
                    'QC',
                    'Logistik',
                    'Kirim',
                ],
                datasets: [{
                    label: 'STATUS PESANAN',
                    data: [100, 0, 0, 0,0],
                    backgroundColor: [
                    'rgba(192, 192, 192, 0.2)',
                    'rgb(236, 159, 5)',
                    'rgb(255, 221, 0)',
                    'rgb(11, 171, 100)',
                    'rgb(8, 126, 225)'
                    ],
                    hoverOffset: 4
                }]
            }
        }

        function detailtabel_ekatalog(id) {
                var dt = $('#detailtabel').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/ekatalog/paket/detail/' + id,
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
                            "class": "details-control",
                            "orderable": false,
                            "data": null,
                            "defaultContent": ""
                        },
                        {
                            data: 'nama_produk',
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
                            className: 'nowrap-text align-center',
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
                            .column(4)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        // computing column Total of the complete result
                        var total_pesanan = api
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        var num_for = $.fn.dataTable.render.number(',', '.', 2).display;
                        $(api.column(0).footer()).html('Total');
                        $(api.column(4).footer()).html('Total');
                        $(api.column(5).footer()).html(num_for(total_pesanan));
                    },
                });
            }

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

            function detailtabel_spb(id) {
                $('#detailtabel_spb').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/spb/paket/detail/' + id,
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


            $(document).on('click', '.somodal', function(event) {
                event.preventDefault();
                var href = $(this).attr('data-attr');
                var id = $(this).data("id");
                var label = $(this).data("target");
                $.ajax({
                    url: href,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#somodal').modal("show");
                        $('#so').html(result).show();

                        if (label == 'ekatalog') {
                            $('#somodal').find(".modal-header").removeClass(
                                'bg-orange bg-lightblue');
                            $('#somodal').find(".modal-header").addClass('bg-purple');
                            $('#somodal').find(".modal-header > h4").text('E-Catalogue');

                            detailtabel_ekatalog(id);
                        } else if (label == 'spa') {
                            $('#somodal').find(".modal-header").removeClass(
                                'bg-purple bg-lightblue');
                            $('#somodal').find(".modal-header").addClass('bg-orange');
                            $('#somodal').find(".modal-header > h4").text('SPA');
                            detailtabel_spa(id);
                        } else {
                            $('#somodal').find(".modal-header").removeClass(
                                'bg-orange bg-purple');
                            $('#somodal').find(".modal-header").addClass('bg-lightblue');
                            $('#somodal').find(".modal-header > h4").text('SPB');
                            detailtabel_spb(id);
                        }

                        $('#somodal').find('[data-toggle="tooltip"]').tooltip();
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

            function update_chart(produk,gudang ,qc, log, ki){
                const ctx = $('#myChart');
                if(produk == 'part'){
                    const myChart = new Chart(ctx, {
                    type: 'pie',
                data: {
                    labels: [
                        'QC',
                        'Logistik',
                        'Kirim',
                    ],
                    datasets: [{
                        label: 'STATUS PESANAN',
                        data: [qc, log, ki],
                        backgroundColor: [
                        'rgb(255, 221, 0)',
                        'rgb(11, 171, 100)',
                        'rgb(8, 126, 225)'
                        ],
                        hoverOffset: 4
                    }]
                }
                });
                }else{
                    const myChart = new Chart(ctx, {
                    type: 'pie',
                data: {
                    labels: [
                        'Gudang',
                        'QC',
                        'Logistik',
                        'Kirim',
                    ],
                    datasets: [{
                        label: 'STATUS PESANAN',
                        data: [gudang ,qc, log, ki],
                        backgroundColor: [

                        'rgb(236, 159, 5)',
                        'rgb(255, 221, 0)',
                        'rgb(11, 171, 100)',
                        'rgb(8, 126, 225)'
                        ],
                        hoverOffset: 4
                    }]
                }
                });
                }

            }

            $(document).on('click', '#tabledetailpesan #lihatstok', function(){
                var id = $(this).attr('data-id');
                var produk = $(this).attr('data-produk');
                var update = 'update';
                 var array = [];
                $.ajax({
                    url: '/api/get_stok_pesanan',
                    data: {'id': id, 'jenis': produk},
                    type: 'GET',
                    dataType: 'json',
                    success: function(result) {
                        if (produk == 'part'){
                    $("#part_status").addClass('d-none');
                }else{
                    $("#part_status").removeClass('d-none');
                }

                    var chartExist = Chart.getChart("myChart"); // <canvas> id
                    if (chartExist != undefined)
                    chartExist.destroy();
                    update_chart(produk,result.gudang,result.qc,result.log,result.kir);


                $('#nama_prd').text(result.detail.penjualan_produk.nama);
                $('#tot_gudang').text(" dari " + result.detail.count_jumlah);
                $('#tot_qc').text(" dari " + result.detail.count_gudang);
                $('#tot_log').text(" dari " + result.detail.count_qc_ok);
                $('#tot_kirim').text(" dari " + result.kir);

                $('#c_gudang').text(result.gudang);
                $('#c_qc').text(result.qc);
                $('#c_log').text(result.log);
                $('#c_kirim').text(result.kir);

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

        pengirimansotable();
        $('#pengirimanso').on('click', function() {
            pengirimansotable();
            sotanpacootable_destroy()
            lewatbataskontraktable_destroy();
            $('#pengirimanso').addClass('active');
            $('#pengirimansotable').removeClass('hide');

            $('#sotanpacoo').removeClass('active');
            $('#lewatbataskontrak').removeClass('active');

            $('#sotanpacootable').addClass('hide');
            $('#lewatbataskontraktable').addClass('hide');
        })

        $('#sotanpacoo').on('click', function() {
            sotanpacootable();
            pengirimansotable_destroy();
            lewatbataskontraktable_destroy();
            $('#sotanpacoo').addClass('active');
            $('#sotanpacootable').removeClass('hide');

            $('#pengirimanso').removeClass('active');
            $('#lewatbataskontrak').removeClass('active');

            $('#pengirimansotable').addClass('hide');
            $('#lewatbataskontraktable').addClass('hide');
        })

        $('#lewatbataskontrak').on('click', function() {
            lewatbataskontraktable();
            pengirimansotable_destroy();
            sotanpacootable_destroy();
            $('#lewatbataskontrak').addClass('active');
            $('#lewatbataskontraktable').removeClass('hide');

            $('#sotanpacoo').removeClass('active');
            $('#pengirimanso').removeClass('active');

            $('#sotanpacootable').addClass('hide');
            $('#pengirimansotable').addClass('hide');
        })

        function pengirimansotable_destroy() {
            $('#pengirimansotable').DataTable().clear().destroy();
        }

        function pengirimansotable() {
            var pengirimansotable = $('#pengirimansotable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/dc/dashboard/data/pengirimansotable',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
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
                        data: 'so',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'batas_kontrak',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
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
                    },
                ]
            })
        }

        function sotanpacootable_destroy() {
            $('#sotanpacootable').DataTable().clear().destroy();
        }

        function sotanpacootable() {
            var sotanpacootable = $('#sotanpacootable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/dc/dashboard/data/sotanpacootable',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
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
                        data: 'so',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'batas_kontrak',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'button',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                ]
            })
        }

        function lewatbataskontraktable_destroy() {
            $('#lewatbataskontraktable').DataTable().clear().destroy();
        }

        function lewatbataskontraktable() {
            var lewatbataskontraktable = $('#lewatbataskontraktable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/dc/dashboard/data/lewatbataskontraktable',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
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
                        data: 'so',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'batas_kontrak',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
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
                    },
                ]
            })
        }

        $('#sotable').DataTable().clear().destroy();
        $('#sotable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/dc/dashboard/so',
                'type': 'POST',
                'datatype': 'JSON',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
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
                    data: 'no_po',
                    className: 'nowrap-text align-center',
                },
                {
                    data: 'no_paket',
                    className: 'nowrap-text align-center',
                },
                {
                    data: 'customer',
                    className: 'nowrap-text align-center',
                },
                {
                    data: 'status',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'aksi',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    })
</script>
<script>
    $(function() {
        $(document).on('click', '.detailmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            $.ajax({
                url: "",
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#detailmodal').modal("show");
                    $('#detail').html(result).show();
                    $("#detailform").attr("action", href);
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
    });
</script>
@stop
