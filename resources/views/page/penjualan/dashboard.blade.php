@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('adminlte_css')
<style lang="scss">
    #pengirimantable thead {
        text-align: center;
    }

    .nowrap {
        white-space: nowrap;
    }

    .align-center {
        text-align: center;
    }

    #urgent {
        color: red;
    }

    #warning {
        color: #FFC700;
    }

    #info {
        color: #3a7bb0;
    }

    .fa-search:hover {
        color: #4682B4;
    }

    .fa-search:active {
        color: #C0C0C0;
    }

    @media screen and (max-width: 1440px) {
        #pengirimantable {
            font-size: 12px;
        }

        h4 {
            font-size: 20px;
        }

        #detailmodal {
            font-size: 12px;
        }
    }
</style>
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
            font-size: 18x;
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
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h4></h4>
                                    <div class="chart">
                                        <canvas id="myChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-6 d-flex">
                                    <div class="small-box purple-text flex-fill">
                                        <div class="inner">
                                            <h3>{{$belum_so}}</h3>
                                            <p>Belum Memiliki SO</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-receipt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6 d-flex">
                                    <div class="small-box orange-text flex-fill">
                                        <div class="inner">
                                            <h3>{{$so_belum_gudang}}</h3>
                                            <p>SO Belum Diproses Gudang</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-boxes"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6 d-flex">
                                    <div class="small-box yellow-text flex-fill">
                                        <div class="inner">
                                            <h3>{{$so_belum_qc}}</h3>
                                            <p>SO Belum Diproses QC</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-tasks"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-6 d-flex">
                                    <div class="small-box green-text flex-fill">
                                        <div class="inner">
                                            <h3>{{$so_belum_logistik}}</h3>
                                            <p>SO Belum Diproses Logistik</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-truck-loading"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h4>Batas Pengiriman E-Catalogue</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="pengirimantable" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No SO</th>
                                                    <th>No PO</th>
                                                    <th>Tanggal PO</th>
                                                    <th>Distibutor</th>
                                                    <th>Status</th>
                                                    <th>Tanggal Delivery</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- <tr>
                                                    <td>1</td>
                                                    <td>SOSPA102100001</td>
                                                    <td>PO/ON/SPA/10/21/001</td>
                                                    <td><span class="badge yellow-text">Logistik</span></td>
                                                    <td>
                                                        <hgroup>
                                                            <p>30-10-2021</p>
                                                            <small id="urgent">3 Hari Lagi</small>
                                                        </hgroup>
                                                    </td>
                                                    <td><a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>SOSPA102100001</td>
                                                    <td>PO/ON/SPA/10/21/001</td>
                                                    <td><span class="badge orange-text">QC</span></td>
                                                    <td>
                                                        <hgroup>
                                                            <p>04-11-2021</p>
                                                            <small id="warning">7 Hari Lagi</small>
                                                        </hgroup>
                                                    </td>
                                                    <td><a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""><i class="fas fa-search"></i></a></td>
                                                </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h4>Lewat Batas Penyelesaian Per Divisi</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="divisitable" style="width:100%;">
                                            <thead class="align-center">
                                                <tr>
                                                    <th>No</th>
                                                    <th>No SO</th>
                                                    <th>Divisi</th>
                                                    <th>Batas Selesai</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="nowrap align-center">1</td>
                                                    <td class="nowrap align-center">SOSPA102100001</td>
                                                    <td class="align-center"><span class="badge yellow-text">Logistik</span></td>
                                                    <td class="align-center">
                                                        <div>06-11-2021</div>
                                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Lewat 5 Hari</div>
                                                    </td>
                                                    <td class="align-center"><a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""><i class="fas fa-search"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td class="nowrap align-center">2</td>
                                                    <td class="nowrap align-center">SOSPA102100001</td>
                                                    <td class="align-center"><span class="badge orange-text">QC</span></td>
                                                    <td class="align-center">
                                                        <div>04-11-2021</div>
                                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Lewat 7 Hari</div>
                                                    </td>
                                                    <td class="align-center"><a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""><i class="fas fa-search"></i></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="detailmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="margin: 10px">
                <div class="modal-header bg-purple">
                    <h4 class="modal-title">E-Catalogue</h4>
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
@stop
@section('adminlte_js')
<script>
    $(function() {
        $('#divisitable').DataTable({});
        var pengirimantable = $('#pengirimantable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/ekatalog/pengiriman/data',
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
                },
                {
                    data: 'no_po',
                    className: 'nowrap-text align-center',
                },
                {
                    data: 'tgl_po',
                    className: 'nowrap-text align-center',
                },
                {
                    data: 'nama_customer',
                    className: 'align-center',
                },
                {
                    data: 'status',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'batas_kontrak',
                    className: 'nowrap-text align-center',
                },
                {
                    data: 'button',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
            ]
        })
    })
</script>
<script>
    $(function() {
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
                // return the result
                success: function(result) {
                    $('#detailmodal').modal("show");
                    $('#detail').html(result).show();
                    if (label == 'ekatalog') {
                        detailtabel_ekatalog(id);
                    } else if (label == 'spa') {
                        detailtabel_spa(id);
                    } else {
                        detailtabel_spb(id);
                    }
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

        function detailtabel_ekatalog(id) {
            $('#detailtabel').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/ekatalog/paket/detail/' + id,
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
    });
</script>
<script>
    $(document).ready(function() {
        $.ajax({
            type: "POST",
            url: "https://sinko.api.hyperdatasystem.com/api/login",
            data: {
                username: "superuser.api",
                password: "password"
            },
            success: function (response) {
                sessionStorage.setItem('token', response.token);
            }
        });
        $.ajax({
            url: "/api/penjualan/chart",
            method: "GET",
            success: function(data) {
                var ctx = document.getElementById("myChart");
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
                        datasets: [{
                                label: "E-Catalogue",
                                backgroundColor: "#7D6378",
                                data: [data.ekatalog_graph[1].count, data.ekatalog_graph[2].count, data.ekatalog_graph[3].count, data.ekatalog_graph[4].count, data.ekatalog_graph[5].count, data.ekatalog_graph[6].count, data.ekatalog_graph[7].count, data.ekatalog_graph[8].count, data.ekatalog_graph[9].count, data.ekatalog_graph[10].count, data.ekatalog_graph[11].count, data.ekatalog_graph[12].count],
                                borderColor: '#7D6378',
                            },
                            {
                                label: "SPA",
                                backgroundColor: "#EA8B1B",
                                data: [data.spa_graph[1].count, data.spa_graph[2].count, data.spa_graph[3].count, data.spa_graph[4].count, data.spa_graph[5].count, data.spa_graph[6].count, data.spa_graph[7].count, data.spa_graph[8].count, data.spa_graph[9].count, data.spa_graph[10].count, data.spa_graph[11].count, data.spa_graph[12].count],
                                borderColor: '#EA8B1B',
                            },
                            {
                                label: "SPB",
                                backgroundColor: "#5F7A90",
                                data: [data.spb_graph[1].count, data.spb_graph[2].count, data.spb_graph[3].count, data.spb_graph[4].count, data.spb_graph[5].count, data.spb_graph[6].count, data.spb_graph[7].count, data.spb_graph[8].count, data.spb_graph[9].count, data.spb_graph[10].count, data.spb_graph[11].count, data.spb_graph[12].count],
                                borderColor: '#5F7A90',
                            }
                        ]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Grafik Penjualan Tahunan'
                            }
                        },
                        scales: {
                            // y: { // defining min and max so hiding the dataset does not change scale range
                            //     min: 0,
                            //     max: 2,
                            //     stepSize: 1,
                            // }
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        });
    });
</script>
@stop
