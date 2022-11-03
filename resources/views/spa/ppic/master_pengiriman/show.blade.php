@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Permintaan Pengiriman</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Permintaan Pengiriman</li>
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

        .m-fadeOut {
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s linear 300ms, opacity 300ms;
        }

        .m-fadeIn {
            visibility: visible;
            opacity: 0.6;
            transition: visibility 0s linear 0s, opacity 300ms;
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

        .foo {
            border-radius: 50%;
            float: left;
            width: 10px;
            height: 10px;
            align-items: center !important;
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

        .hide {
            display: none;
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

        .bg-chart-light {
            background: rgba(192, 192, 192, 0.2);
        }

        .bg-chart-orange {
            background: rgb(236, 159, 5);
        }

        .bg-chart-yellow {
            background: rgb(255, 221, 0);
        }

        .bg-chart-green {
            background: rgb(11, 171, 100);
        }

        .bg-chart-blue {
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
                    550px;
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
                max-height: 500px;
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

        .borderright {
            border-right: 1px solid #F0ECE3;
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped table-bordered"
                                            style="text-align:center;" id="showtable">
                                            <thead>
                                                <tr class="bg-navy">
                                                    <th class="nowrap" rowspan="2">No</th>
                                                    <th rowspan="2">Nama Produk</th>
                                                    <th class="nowrap" rowspan="2">Jumlah Pesanan</th>
                                                    <th class="nowrap borderright" colspan="2">Pengiriman</th>
                                                    <th class="nowrap" rowspan="2">Aksi</th>
                                                </tr>
                                                <tr class="bg-secondary">
                                                    <th>Jumlah Selesai</th>
                                                    <th>Jumlah Sisa</th>
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
        <div class="modal fade" id="detailmodal" role="dialog" aria-labelledby="detailmodal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div id="modal-overlay" class="overlay m-fadeOut"></div>
                    <div class="modal-header">
                        <h4 class="modal-title">Info</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="detail">

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="penjualanmodal" role="dialog" aria-labelledby="penjualanmodal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header">
                        <h4 class="modal-title">Info</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="penjualan">

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('adminlte_js')
    <script>
        $(function() {
            var access_token = localStorage.getItem('lokal_token');
            if (access_token == null) {
                Swal.fire({
                    title: 'Session Expired',
                    text: 'Silahkan login kembali',
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.preventDefault();
                        document.getElementById('logout-form').submit();
                    }
                })
            }
            $(document).on('click', '.detailmodal', function(event) {
                event.preventDefault();
                var id = $(this).data('id');
                $.ajax({
                    url: "/ppic/master_pengiriman/detail/" + id,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#detailmodal').modal("show");
                        $('#detail').html(result).show();
                        detailtable(id);
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


            var showtable = $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/ppic/master_pengiriman/data',
                    'type': 'POST',
                    'dataType': 'JSON',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'borderright',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_produk',
                        className: 'borderright',
                    },
                    {
                        data: 'jumlah',
                        className: 'borderright',
                    },
                    {
                        data: 'jumlah_pengiriman',
                    },
                    {
                        data: 'belum_pengiriman',
                        className: 'borderright',
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });


            function detailtable(id) {
                $('#detailtable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/ppic/master_pengiriman/detail/' + id,
                        'type': 'POST',
                        'dataType': 'JSON',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
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
                            data: 'so',
                        },
                        {
                            data: 'customer',
                        },
                        {
                            data: 'tgl_delivery',
                        },
                        {
                            data: 'jumlah_pesanan',
                        },
                        {
                            data: 'jumlah_selesai_kirim',
                        },
                        {
                            data: 'jumlah_belum_kirim',
                        },
                        {
                            data: 'aksi',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
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

            $(document).on('hidden.bs.modal', '#penjualanmodal', function(event) {
                $('#detailmodal').find('#modal-overlay').addClass('m-fadeOut');
                $('#detailmodal').find('#modal-overlay').removeClass('m-fadeIn');
            });
            $(document).on('click', '.penjualanmodal', function(event) {
                $('#detailmodal').find('#modal-overlay').removeClass('m-fadeOut');
                $('#detailmodal').find('#modal-overlay').addClass('m-fadeIn');

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
                        $('#penjualanmodal').modal("show");
                        $('#penjualan').html(result).show();

                        if (label == 'ekatalog') {
                            $('#penjualanmodal').find(".modal-header").removeClass(
                                'bg-orange bg-lightblue');
                            $('#penjualanmodal').find(".modal-header").addClass('bg-purple');
                            $('#penjualanmodal').find(".modal-header > h4").text('E-Catalogue');

                            detailtabel_ekatalog(id);
                        } else if (label == 'spa') {
                            $('#penjualanmodal').find(".modal-header").removeClass(
                                'bg-purple bg-lightblue');
                            $('#penjualanmodal').find(".modal-header").addClass('bg-orange');
                            $('#penjualanmodal').find(".modal-header > h4").text('SPA');
                            detailtabel_spa(id);
                        } else {
                            $('#penjualanmodal').find(".modal-header").removeClass(
                                'bg-orange bg-purple');
                            $('#penjualanmodal').find(".modal-header").addClass('bg-lightblue');
                            $('#penjualanmodal').find(".modal-header > h4").text('SPB');
                            detailtabel_spb(id);
                        }

                        $('#penjualanmodal').find('[data-toggle="tooltip"]').tooltip();
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

            function update_chart(produk, gudang, qc, log, ki) {
                const ctx = $('#myChart');
                if (produk == 'part') {
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
                } else {
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
                                data: [gudang, qc, log, ki],
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

            $(document).on('click', '#tabledetailpesan #lihatstok', function() {
                var id = $(this).attr('data-id');
                var produk = $(this).attr('data-produk');
                var update = 'update';
                var array = [];
                $.ajax({
                    url: '/api/get_stok_pesanan',
                    data: {
                        'id': id,
                        'jenis': produk
                    },
                    type: 'GET',
                    dataType: 'json',
                    success: function(result) {
                        if (produk == 'part') {
                            $("#part_status").addClass('d-none');
                        } else {
                            $("#part_status").removeClass('d-none');
                        }

                        var chartExist = Chart.getChart("myChart"); // <canvas> id
                        if (chartExist != undefined)
                            chartExist.destroy();
                        update_chart(produk, result.gudang, result.qc, result.log, result.kir);


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
        });
    </script>
@stop
