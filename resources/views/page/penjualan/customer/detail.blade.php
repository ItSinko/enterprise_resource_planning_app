@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Customer</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @if(Auth::user()->divisi_id == "26" || Auth::user()->divisi_id == "8")
                <li class="breadcrumb-item"><a href="{{route('penjualan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{route('penjualan.customer.show')}}">Customer</a></li>
                <li class="breadcrumb-item active">Detail Customer</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
<style>
    .modal-body{
        max-height: 80vh;
        overflow-y: auto;
    }

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

    .hide{
        display: none !important
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
<<<<<<< HEAD

=======
>>>>>>> dd058260e517c3328df4d9c0883f45f986a3133c
    li.list-group-item {
        border: 0 none;
    }

    #historitabel {
        text-align: center;
    }

    .align-center {
        text-align: center;
    }

    .margin-all {
        margin: 5px;
    }

    .margin-side {
        margin-left: 5px;
        margin-right: 5px;
    }

    #profileImage {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #4682B4;
        font-size: 22px;
        color: #fff;
        text-align: center;
        line-height: 100px;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
    }


    .overflowy {
        max-height: 550px;
        width: auto;
        overflow-y: scroll;
        box-shadow: none;
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

    @media screen and (min-width: 1440px) {
        section {
            font-size: 14px;
        }

        .dropdown-item {
            font-size: 14px;
        }

        .labelinfo{
            text-align: center;
        }
    }

    @media screen and (max-width: 1439px) {
        section {
            font-size: 12px;
        }

        .dropdown-item {
            font-size: 12px;
        }

        .labelinfo{
            text-align: center;
        }
<<<<<<< HEAD

=======
>>>>>>> dd058260e517c3328df4d9c0883f45f986a3133c
        .overflowcard {
            max-height: 500px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
        }
    }

    @media screen and (max-width:991px){
        .labelinfo{
            text-align: left;
        }
    }

    @media screen and (max-width:767px){
        .labelinfo{
            text-align: center;
        }
    }
</style>
@stop

@section('content_header')
<h1 class="m-0 text-dark">Customer</h1>
@stop

@section('content')
<section class="section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <h5>Info</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-4 align-center">
                                <div id="profileImage" class="center margin-all"></div>
                            </div>
                            <div class="col-lg-12 col-md-8 labelinfo">
                                <div class="margin-all">
                                    <h5><b>{{$customer->nama}}</b></h5>
                                </div>
                                @if(isset($customer->nama_pemilik))
                                <div class="margin-all"><a class="text-muted margin-side">Pemilik :</a><b>{{$customer->nam_pemilik}}</b></div>
                                @endif
                                <div class="margin-all"><b>{{$customer->alamat}}</b></div>
                                <div class="margin-all"><b>{{$customer->Provinsi->nama}}</b></div>
                                <div class="margin-all">
                                    <span class="margin-side"><i class="fas fa-phone text-muted margin-side"></i> <b>{{$customer->telp}}</b></span>
                                    <span class="margin-side"><i class="fas fa-envelope text-muted margin-side"></i><b>@if(!empty($customer->email)) {{$customer->email}} @else - @endif</b></span>
                                </div>
                                <div class="margin-all"><a class="text-muted margin-side">NPWP :</a><b>{{$customer->npwp}}</b></div>
                                <div class="margin-all hide">
                                   <span><a class="text-muted margin-side">IZIN USAHA :</a><b class="badge blue-text"> @if(isset($customer->izin_usaha)) {{$customer->izin_usaha}} @else - @endif</b></span> |
                                   <span>
                                        <a class="text-muted margin-side">MODAL USAHA :</a><b class="badge blue-text">
                                            @switch($customer->modal_usaha)
                                            @case(1)
                                            <span>< 1 M </span>
                                            @break
                                            @case(2)
                                            <span>> 1 M & < 5 M </span>
                                            @break
                                            @case(3)
                                            <span>> 5 M & < 10 M</span>
                                            @break
                                        @default
                                            <span>-</span>
                                            @endswitch
                                    </b>
                                    </span>
                                    |<span>
                                        <a class="text-muted margin-side">HASIL PENJUALAN :</a><b class="badge blue-text">
                                            @switch($customer->hasil_penjualan)
                                            @case(1)
                                            <span>< 2 M </span>
                                            @break
                                            @case(2)
                                            <span>> 2 M & < 15 M </span>
                                            @break
                                            @case(3)
                                            <span>> 15 M & < 50 M</span>
                                            @break
                                        @default
                                            <span>-</span>
                                            @endswitch
                                        </b>
                                    </span>
                                </div>
                                <div class="margin-all"><a class="text-muted">{{$customer->ket}}</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <h5>Histori Penjualan</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-center" id="showtable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No SO</th>
                                        <th>No PO</th>
                                        <th>Tanggal PO</th>
                                        <th>Jenis</th>
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
    <div class="modal fade" id="detailmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="margin: 10px">
                <div class="modal-header">
                    <h4>Detail</h4>
                </div>
                <div class="modal-body" id="detail">
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
                'url': '/api/customer/detail/' + '{{$customer->id}}',
                'type': 'POST',
                'dataType': 'json',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
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
                    data: 'nopo',
                },
                {
                    data: 'tglpo',
                },
                {
                    data: 'jenis',
                },
                {
                    data: 'status',
                },
                {
                    data: 'button',
                    orderable: false,
                    searchable: false
                }
            ]
        });
        var cust = <?php echo json_encode($customer->nama); ?>;
        var cust = cust.replace('.', '').replace('PT ', '').replace('CV ', '').replace('& ', '').replace('(', '').replace(')', '');
        var init = cust.split(" ");
        var initial = "";
        for (var i = 0; i < init.length; i++) {
            initial = initial + init[i].charAt(0);
        }
        var profileImage = $('#profileImage').text(initial);
    });
</script>
<script>
    $(function() {
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
                        $('#detailmodal').find(".modal-header").removeClass(
                            'bg-orange bg-lightblue');
                        $('#detailmodal').find(".modal-header").addClass('bg-purple');
                        $('#detailmodal').find(".modal-header > h4").text('E-Catalogue');

                        detailtabel_ekatalog(id);
                    } else if (label == 'spa') {
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

        function detailtabel_spa(id) {
            $('#detailtabel_spa').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/spa/paket/detail/' + id,
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
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/spb/paket/detail/' + id,
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
    });
</script>
@stop
