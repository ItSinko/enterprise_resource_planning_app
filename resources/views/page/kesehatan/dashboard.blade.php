@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('adminlte_css')
<style lang="scss">
    .card {
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
        }

    .modal-body{
        max-height: 80vh;
        overflow-y: auto;
    }
    /* #justgage2 > svg > path {
        width: 100% !important;
    } */

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
@stop
@section('content')
<div class="content">
    <div class="row">
        {{-- <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                        <h6 class="float-right mb-3 text-muted"><i>{{Carbon::now()->isoFormat('dddd, D MMMM Y')}}</i></h6>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-4">
                            <div class="info-box blue-text">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                <span class="info-box-text">Jumlah Karyawan</span>
                                <span class="info-box-number">1,410</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                        <div class="info-box green-text">
                            <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">Karyawan Masuk</span>
                              <span class="info-box-number">1,410</span>
                            </div>
                        </div>
                        </div>
                        <div class="col-4">
                        <div class="info-box red-text">
                            <span class="info-box-icon"><i class="fas fa-user-slash"></i></span>
                            <div class="info-box-content">
                              <span class="info-box-text">Karyawan Tidak Masuk</span>
                              <span class="info-box-number">1,410</span>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-12">
            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <div class="card-body">
                            <h4>Rekap Absen 1 Bulan</h4>
                            <div style="min-height:350px; max-height: 350px;">
                                <canvas id="chart_absensi" style="max-height:100%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <h4>Vaksin</h4>
                            <div style="min-height:350px; max-height: 350px;">
                                <canvas id="myChart" style="max-height:100%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <h4 class="col-6">Kunjungan Klinik {{ now()->year }}</h4>
                                <div class="col-6">
                                    <div class="btn-group float-right">
                                        <button type="button" class="btn btn-outline-info">Pilih Bulan</button>
                                        <button type="button" class="btn btn-outline-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu">
                                        @for($i = now()->month; $i >= 1; $i--)
                                        <a class="dropdown-item" href="#" value="{{$i}}">
                                            @if($i == '1') Januari
                                            @elseif ($i == '2') Februari
                                            @elseif ($i == '3') Maret
                                            @elseif ($i == '4') April
                                            @elseif ($i == '5') Mei
                                            @elseif ($i == '6') Juni
                                            @elseif ($i == '7') Juli
                                            @elseif ($i == '8') Agustus
                                            @elseif ($i == '9') September
                                            @elseif ($i == '10') Oktober
                                            @elseif ($i == '11') November
                                            @else Desember @endif</a>
                                        @endfor

                                        {{-- <a class="dropdown-item" href="#">{{ now()->year - 1 }}</a>
                                        <a class="dropdown-item" href="#">{{ now()->year - 2 }}</a>
                                        <a class="dropdown-item" href="#">{{ now()->year - 3 }}</a>
                                        <a class="dropdown-item" href="#">{{ now()->year - 4 }}</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Diagnosa</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Obat</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                            <div id="justgage1"></div>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-striped" style="text-align:center;" id="karyawan_diagnosa_table">
                                                    <thead class="bg-secondary">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Diagnosa</th>
                                                            <th>Jumlah</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>ISPA</td>
                                                            <td>50 pegawai</td>
                                                            <td><button type="button" class="btn btn-outline-primary btn-sm" id="karyawan_diagnosa_modal"><i class="fas fa-eye"></i> Detail</button></td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>Demam</td>
                                                            <td>42 pegawai</td>
                                                            <td><button type="button" class="btn btn-outline-primary btn-sm" id="karyawan_diagnosa_modal"><i class="fas fa-eye"></i> Detail</button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                            <div id="justgage2" ></div>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-striped" style="text-align:center;" id="karyawan_obat_table">
                                                    <thead class="bg-secondary">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Obat</th>
                                                            <th>Jumlah</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>ANASTAN</td>
                                                            <td>50 pcs</td>
                                                            <td><button type="button" class="btn btn-outline-primary btn-sm" id="karyawan_obat_modal"><i class="fas fa-eye"></i> Detail</button></td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>IMURAN</td>
                                                            <td>42 pcs</td>
                                                            <td><button type="button" class="btn btn-outline-primary btn-sm" id="karyawan_obat_modal"><i class="fas fa-eye"></i> Detail</button></td>
                                                        </tr>
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
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <h4 class="col-6">Karyawan Sakit {{ now()->year }}</h4>
                                <div class="col-6">
                                    <div class="btn-group float-right">
                                        <button type="button" class="btn btn-outline-info">Pilih Bulan</button>
                                        <button type="button" class="btn btn-outline-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu">
                                        @for($i = now()->month; $i >= 1; $i--)
                                        <a class="dropdown-item" href="#" value="{{$i}}">
                                            @if($i == '1') Januari
                                            @elseif ($i == '2') Februari
                                            @elseif ($i == '3') Maret
                                            @elseif ($i == '4') April
                                            @elseif ($i == '5') Mei
                                            @elseif ($i == '6') Juni
                                            @elseif ($i == '7') Juli
                                            @elseif ($i == '8') Agustus
                                            @elseif ($i == '9') September
                                            @elseif ($i == '10') Oktober
                                            @elseif ($i == '11') November
                                            @else Desember @endif</a>
                                        @endfor

                                        {{-- <a class="dropdown-item" href="#">{{ now()->year - 1 }}</a>
                                        <a class="dropdown-item" href="#">{{ now()->year - 2 }}</a>
                                        <a class="dropdown-item" href="#">{{ now()->year - 3 }}</a>
                                        <a class="dropdown-item" href="#">{{ now()->year - 4 }}</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="table-responsive">
                                <table class="table table-hover table-striped" style="text-align:center;" id="karyawan_sakit_table">
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Frekuensi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Sumaiyah</td>
                                            <td>20</td>
                                            <td><button type="button" class="btn btn-outline-primary btn-sm" id="karyawan_sakit_modal"><i class="fas fa-eye"></i> Detail</button></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Alimun</td>
                                            <td>19</td>
                                            <td><button type="button" class="btn btn-outline-primary btn-sm" id="karyawan_sakit_modal"><i class="fas fa-eye"></i> Detail</button></td>
                                        </tr>
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
<div class="modal fade  bd-example-modal-xl" id="detailmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header card-outline card-primary">
                <h4 class="modal-title" id="modal-label">
                    Tambah Obat
                </h4>
            </div>
            <div class="modal-body" id="detaildata">

            </div>
        </div>
    </div>
</div>
@stop
@section('adminlte_js')
<script>
    $(document).ready(function() {
        $("#karyawan_diagnosa_table > tbody").on('click', '#karyawan_diagnosa_modal', function(){
            $.ajax({
                url: "/kesehatan/klinik/diagnosa_detail",
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#detailmodal').modal('show');
                    $('#modal-label').text('Diagnosa');
                    $("#detaildata").html(result).show();
                    $('#table_diagnosa').DataTable();
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

        $("#karyawan_obat_table > tbody").on('click', '#karyawan_obat_modal', function(){
            $.ajax({
                url: "/kesehatan/klinik/obat_detail",
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#detailmodal').modal('show');
                    $('#modal-label').text('Obat');
                    $("#detaildata").html(result).show();
                    $('#table_obat').DataTable();
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
            });
        });

        $("#karyawan_sakit_table > tbody").on('click', '#karyawan_sakit_modal', function(){
            $.ajax({
                url: "/kesehatan/klinik/sakit_detail",
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#detailmodal').modal('show');
                    $('#modal-label').text('Karyawan Sakit');
                    $("#detaildata").html(result).show();
                    $('#table_sakit').DataTable();
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
            });
        });
        // var gauge1 = new JustGage({
        //     id: "justgage1", // the id of the html element
        //     value: 50,
        //     min: 0,
        //     max: 100,
        //     decimals: 2,
        //     gaugeWidthScale: 0.6,
        //     relativeGaugeSize: true
        // });

        // update the value randomly
        // setInterval(() => {
        // gauge1.refresh(Math.random() * 100);
        // }, 5000);

        // $(document).on('click', '#custom-tabs-four-home-tab', function(){
        //     setInterval(() => {
        //     gauge1.refresh(Math.random() * 100);
        //     }, 5000);
        // });

        // var gauge2 = new JustGage({
        //     id: "justgage2", // the id of the html element
        //     value: 50,
        //     min: 0,
        //     max: 100,
        //     decimals: 2,
        //     gaugeWidthScale: 0.6,
        //     relativeGaugeSize: true
        // });

        // $(document).on('click', '#custom-tabs-four-profile-tab', function(){
        //     setInterval(() => {
        //     gauge2.refresh(Math.random() * 100);
        //     }, 5000);
        // });
        // update the value randomly


        $('#karyawan_obat_table').DataTable();
        $('#karyawan_diagnosa_table').DataTable();
        $('#karyawan_sakit_table').DataTable();
        $.ajax({
            url: "/api/penjualan/chart",
            method: "GET",
            success: function(data) {

                var ctx = document.getElementById("chart_absensi");
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
                        datasets: [{
                                label: "Ijin",
                                backgroundColor: "#ffcc00",
                                data: [data.ekatalog_graph[1].count, data.ekatalog_graph[2].count, data.ekatalog_graph[3].count, data.ekatalog_graph[4].count, data.ekatalog_graph[5].count, data.ekatalog_graph[6].count, data.ekatalog_graph[7].count, data.ekatalog_graph[8].count, data.ekatalog_graph[9].count, data.ekatalog_graph[10].count, data.ekatalog_graph[11].count, data.ekatalog_graph[12].count],
                                borderColor: '#FFF6D4',
                            },
                            {
                                label: "Cuti",
                                backgroundColor: '#456600',
                                data: [data.spa_graph[1].count, data.spa_graph[2].count, data.spa_graph[3].count, data.spa_graph[4].count, data.spa_graph[5].count, data.spa_graph[6].count, data.spa_graph[7].count, data.spa_graph[8].count, data.spa_graph[9].count, data.spa_graph[10].count, data.spa_graph[11].count, data.spa_graph[12].count],
                                borderColor: 'rgba(69, 102, 0, 0.2)',
                            },
                            {
                                label: "Sakit",
                                backgroundColor: "#dc3545",
                                data: [data.ekatalog_graph[1].count, data.ekatalog_graph[2].count, data.ekatalog_graph[3].count, data.ekatalog_graph[4].count, data.ekatalog_graph[5].count, data.ekatalog_graph[6].count, data.ekatalog_graph[7].count, data.ekatalog_graph[8].count, data.ekatalog_graph[9].count, data.ekatalog_graph[10].count, data.ekatalog_graph[11].count, data.ekatalog_graph[12].count],
                                borderColor: '#FFDADA',
                            },
                        ]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Grafik Berat Badan'
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

        $(document).ready(function() {
        $.ajax({
            url: "/kesehatan/vaksin/chart/data",
            method: "GET",
            success: function(data) {
                const ctx = $('#myChart');
                const myChart = new Chart(ctx, {
                    type: 'pie',
                data: {
                    labels: [
                        'Belum Vaksin',
                        'Vaksin 1',
                        'Vaksin 2',
                        'Vaksin 3',
                    ],
                    datasets: [{
                        label: 'Vaksinasi',
                        data: [10, data.tahap_1, data.tahap_2, data.tahap_3],
                        backgroundColor: [
                        '#EFEFEF',
                        'rgb(255, 221, 0)',
                        'rgb(11, 171, 100)',
                        'rgb(8, 126, 225)'
                        ],
                        hoverOffset: 4
                    }]
                }
                });
            }
        });
        });



    });
</script>
@stop
