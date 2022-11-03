@extends('adminlte.page')

@section('title', 'ERP')


@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Grafik Air Masuk</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                <li class="breadcrumb-item active">Grafik Air Masuk</li>
            </ol>
        </div>
    </div>
</div>


@stop

@section('adminlte_css')
<style>
    /* element.gauge {
        top: -45px;
    } */

    svg {

        height: auto;
        margin: 0;
        padding: 0;
        border: 0;
    }

    .gauge {

        width: auto;
        height: auto;
        margin: auto auto;

    }

    .gauge2 {

        width: auto;
        height: auto;
        margin: auto auto;

    }

    /* Style TDS TSS PH Meter */
    .outer-wrapper {
        display: inline-block;
        margin: auto auto;
        padding: auto auto;
        background: #eee;
        min-width: 40px;
    }

    .column-wrapper {

        height: 90px;
        width: 20px;
        background: #CFD8DC;
        transform: rotate(180deg);
        margin: 0 auto;
    }

    /* Style Gauge pH */
    .column {
        /* position: relative;
  display: block;
  bottom: 0; */
        width: 20px;
        height: 100%;
        background: #90A4AE;
        /* transfrom: -moz-translateY(-10px); */
    }

    .percentage,
    .value {
        margin-top: 5px;
        padding: 5px 10px;
        color: #FFF;
        background: #263238;
        position: relative;
        border-radius: 4px;
        text-align: center;
    }

    .value {
        background: #7986CB;
    }

    /* Style Gauge TSS */
    .TSScolumn {
        /* position: relative;
  display: block;
  bottom: 0; */
        width: 20px;
        height: 100%;
        background: #90A4AE;
        /* transfrom: -moz-translateY(-10px); */
    }

    .TSSpercentage,
    .value {
        margin-top: 5px;
        padding: 5px 10px;
        color: #FFF;
        background: #263238;
        position: relative;
        border-radius: 4px;
        text-align: center;
    }

    .value {
        background: #7986CB;
    }

    /* Style Gauge TDS */
    .TDScolumn {
        /* position: relative;
  display: block;
  bottom: 0; */
        width: 20px;
        height: 100%;
        background: #90A4AE;
        /* transfrom: -moz-translateY(-10px); */
    }

    .TDSpercentage,
    .value {
        margin-top: 5px;
        padding: 5px 10px;
        color: #FFF;
        background: #263238;
        position: relative;
        border-radius: 4px;
        text-align: center;
    }

    .value {
        background: #7986CB;
    }
</style>
@stop

@section('content')
<!-- <section class="content">
    <div class="container-fluid"> -->
<div class="row">

    <div class="col-sm-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Total Penggunaan Air Satu Minggu (L) <br>
                <?php
                echo date("d F Y", strtotime('monday this week')), " - ";
                echo date("d F Y", strtotime('sunday this week'));
                ?>

            </div>
            <div class="card-body"><canvas id="myBarChart" width="100%" height="25%"></canvas></div>
        </div>
    </div>

</div>

<div class="row card-deck">
    <!-- <div class="col-sm-12"> -->
    <div class="card card-primary card-outline card-outline-tabs col-lg-2">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Pakai</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Masuk</a>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="custom-tabs-four-tabContent">
            <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab" style="height:215px">

                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Air Dipakai (L/M)
                </div>
                <div id="gg2" class="gauge2"></div>
            </div>

            <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab" style="height:215px">

                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Air Disimpan (L/M)
                </div>
                <div id="gg1" class="gauge"></div>

            </div>

        </div>

    </div>
    <!-- </div> -->



    <!-- <div class="col-sm-12"> -->
    <div class="card col-lg-7" style="min-height:239px;">
        <div class="card-header">
            <i class="fas fa-chart-area me-1"></i>
            Penggunaan Air Hari Ini (<?php
                                        setlocale(LC_ALL, 'id-ID');
                                        echo strftime("%A"); ?>)
        </div>
        <div class="card-body" style="height:auto;">
            <div class="chart-container"><canvas id="myAreaChart" min height="110px" height="auto"></canvas></div>
        </div>
    </div>
    <!-- </div> -->

    <!-- <div class="col-sm-12"> -->
    <div class="card col-lg-3">
        <div class="card-header">
            <i class="fas fa-chart-bar me-1"></i>
            Kondisi Air
        </div>
        <div class="card-body px-0 text-center">

            <div class="outer-wrapper">
                pH
                <div class="column-wrapper">
                    <div class="column"></div>
                </div>
                <div class="percentage">--</div>
                <div class="value">pH</div>
            </div>

            <div class="outer-wrapper">
                TSS
                <div class="column-wrapper">
                    <div class="TSScolumn"></div>
                </div>
                <div class="TSSpercentage">--</div>
                <div class="value">NTU</div>
            </div>

            <div class="outer-wrapper">
                TDS
                <div class="column-wrapper">
                    <div class="TDScolumn"></div>
                </div>
                <div class="TDSpercentage">--</div>
                <div class="value">ppm</div>
            </div>
        </div>
    </div>
    <!-- </div> -->
</div>

<!-- </div>


</section> -->
@endsection

@section('adminlte_js')

<script src="{{ asset('js/justgage.js') }}"></script>
<script src="{{ asset('js/raphael-2.1.4.min.js') }}"></script>
<script type="text/javascript">
    // var x = document.getElementById("gg1");
    // x.style.display = "none";

    // $('.d_masuk').on('click', function() {
    //     x.style.display = "block"
    // })

    var clientHeight = $('.column-wrapper')[0].style.height;
    console.log(clientHeight);

    // Javascript bar chart / barchart
    var ctx = document.getElementById("myBarChart");
    var BarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                ["Senin", ],
                ["Selasa", ],
                ["Rabu", ],
                ["Kamis", ],
                ["Jum'at", ],
                ["Sabtu", ],
                ["Minggu", ]
            ],
            datasets: [{
                label: "Air Digunakan",
                backgroundColor: "rgba(2,117,216,1)",
                borderColor: "rgba(2,117,216,1)",
                data: [<?php
                        for ($i = 0; $i <= 6; $i++) {
                            echo '0' . ',';
                        } ?>],
            }, {
                label: "Air Disimpan",
                backgroundColor: "rgba(255,0,255)",
                borderColor: "rgba(255,0,255)",
                data: [<?php
                        for ($i = 6; $i >= 0; $i--) {
                            echo '0' . ',';
                        } ?>],
            }],
        },
        options: {

            scales: {
                xAxes: [{
                    time: {
                        unit: 'day'
                    },
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        maxTicksLimit: 6,
                        autoSkip: false
                    }
                }],
                yAxes: [{
                    ticks: {
                        min: 0,
                        max: 50,
                        maxTicksLimit: 5
                    },
                    gridLines: {
                        display: true
                    }
                }],
            },
            legend: {
                display: false
            }
        }
    });

    function UpdateBarVolume() {
        $.ajax({
            url: "http://192.168.13.2:85/air/rekap_volume_in",
            type: "get",
            success: function(res) {

                for ($i = 0; $i < 6; $i++) {
                    // console.log(res.data[$i].volume);
                    if (res.data[$i].hari == 'Monday') {
                        BarChart.data.datasets[0].data[0] = res.data[$i].volume;
                    } else if (res.data[$i].hari == 'Tuesday') {
                        BarChart.data.datasets[0].data[1] = res.data[$i].volume;
                    } else if (res.data[$i].hari == 'Wednesday') {
                        BarChart.data.datasets[0].data[2] = res.data[$i].volume;
                    } else if (res.data[$i].hari == 'Thursday') {
                        BarChart.data.datasets[0].data[3] = res.data[$i].volume;
                    } else if (res.data[$i].hari == 'Friday') {
                        BarChart.data.datasets[0].data[4] = res.data[$i].volume;
                    } else if (res.data[$i].hari == 'Saturday') {
                        BarChart.data.datasets[0].data[5] = res.data[$i].volume;
                    } else if (res.data[$i].hari == 'Sunday') {
                        BarChart.data.datasets[0].data[6] = res.data[$i].volume;
                    } else {
                        BarChart.data.datasets[0].data[$i] = "0.0";
                    }
                    BarChart.update();
                }

            },
        })

        $.ajax({
            url: "http://192.168.13.2:85/air/rekap_volume_out",
            type: "get",
            success: function(res) {

                for ($i = 0; $i < 6; $i++) {
                    // console.log(res.data[$i].volume);
                    if (res.data[$i].hari == 'Monday') {
                        BarChart.data.datasets[1].data[0] = res.data[$i].volume;
                    } else if (res.data[$i].hari == 'Tuesday') {
                        BarChart.data.datasets[1].data[1] = res.data[$i].volume;
                    } else if (res.data[$i].hari == 'Wednesday') {
                        BarChart.data.datasets[1].data[2] = res.data[$i].volume;
                    } else if (res.data[$i].hari == 'Thursday') {
                        BarChart.data.datasets[1].data[3] = res.data[$i].volume;
                    } else if (res.data[$i].hari == 'Friday') {
                        BarChart.data.datasets[1].data[4] = res.data[$i].volume;
                    } else if (res.data[$i].hari == 'Saturday') {
                        BarChart.data.datasets[1].data[5] = res.data[$i].volume;
                    } else if (res.data[$i].hari == 'Sunday') {
                        BarChart.data.datasets[1].data[6] = res.data[$i].volume;
                    } else {
                        BarChart.data.datasets[1].data[$i] = "0.0";
                    }
                    BarChart.update();
                }

            },
        })
    }

    setInterval(function() {
        UpdateBarVolume();
    }, 1000);


    //Javascript Gauge 1
    var nilaiGauge;

    //Javascript Penggunaan Hari Ini / Penggunaan Air Hari ini
    var ctx = document.getElementById("myAreaChart");
    var kosong;
    var LineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php
                        for ($i = 0; $i <= 5; $i++) {
                            if ($i == NULL) {
                                echo '"' . "Memuat" . '"' . ',';
                            } else {
                                echo '"' . 'Memuat' . '"' . ',';
                            }
                        } ?>],
            datasets: [{
                    label: "Debit Masuk (L/M)",
                    lineTension: 0.3,
                    backgroundColor: "rgba(2,117,216,0.2)",
                    borderColor: "rgba(2,117,216,1)",
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(2,117,216,1)",
                    pointBorderColor: "rgba(255,255,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(2,117,216,1)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: [<?php for ($i = 0; $i <= 5; $i++) {
                                if ($i == NULL) {
                                    echo "0.0" . ',';
                                } else {
                                    echo "0.0" . ',';
                                }
                            } ?>],
                },
                {
                    label: "Debit Keluar (L/M)",
                    lineTension: 0.3,
                    backgroundColor: "rgba(255,0,255,0.2)",
                    borderColor: "rgba(255,0,255,1)",
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(255,0,255,1)",
                    pointBorderColor: "rgba(255,0,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(255,0,255,1)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: [<?php for ($i = 6; $i > 0; $i--) {
                                if ($i == NULL) {
                                    echo "0.0" . ',';
                                } else {
                                    echo "0.0" . ',';
                                }
                            } ?>],
                }
            ],
        },
        options: {
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        min: 0,
                        max: 20,
                        maxTicksLimit: 5

                    },
                    gridLines: {
                        color: "rgba(0, 0, 0, .125)",
                    }
                }],

            },
            legend: {
                display: false
            }
        }
    });

    $(document).ready(function() {
        var gg1 = new JustGage({
            id: "gg1",
            value: 0.00 + 'L/M',
            min: 0,
            max: 20,
            decimals: 2,
            gaugeWidthScale: 0.6,
            relativeGaugeSize: true,
            customSectors: [{
                color: "#00ff00",
                lo: 0,
                hi: 5
            }, {
                color: "#fc6f03",
                lo: 5,
                hi: 10
            }, {
                color: "#ff0000",
                lo: 10,
                hi: 15
            }, {
                color: "#fc03f0",
                lo: 15,
                hi: 20
            }],
            counter: true
        });

        var gg2 = new JustGage({
            id: "gg2",
            value: 0 + 'L/M',
            min: 0,
            max: 20,
            decimals: 2,
            gaugeWidthScale: 0.6,
            relativeGaugeSize: true,
            customSectors: [{
                color: "#00ff00",
                lo: 0,
                hi: 5
            }, {
                color: "#fc6f03",
                lo: 5,
                hi: 10
            }, {
                color: "#ff0000",
                lo: 10,
                hi: 15
            }, {
                color: "#fc03f0",
                lo: 15,
                hi: 20
            }],
            counter: true
        })

        //update data
        function updateGaugeIn() {
            $.ajax({
                url: "http://192.168.13.2:85/air/rekap_debit_in",
                type: "get",
                success: function(res) {
                    gg1.refresh(res.data2[0].debit);

                    for ($i = 0; $i <= 12; $i++) {


                        if (res.data2[$i].debit != null) {

                            LineChart.data.datasets[0].data[$i] = res.data2[$i].debit;


                        } else {
                            LineChart.data.datasets[0].data[$i] = res.data2[$i].debit;

                        }
                        LineChart.update();
                    }
                }
            })
        }

        function updateGaugeOut() {
            $.ajax({
                url: "http://192.168.13.2:85/air/rekap_debit_out",
                type: "get",
                success: function(res) {
                    gg2.refresh(res.data2[0].debit);

                    for ($i = 0; $i <= 12; $i++) {

                        let debit = res.data2[$i].created_at;
                        let jam = debit.slice(11);
                        if (res.data2[$i].debit != null) {
                            LineChart.data.datasets[1].data[$i] = res.data2[$i].debit;
                            LineChart.data.labels[$i] = jam;
                        } else {
                            LineChart.data.datasets[1].data[$i] = res.data2[$i].debit;
                            LineChart.data.labels[$i] = res.data2[$i].created_at;
                        }

                        LineChart.update();
                    }
                }
            })
        }

        setInterval(function() {
            updateGaugeIn();
            updateGaugeOut();
            kualitas();
        }, 1000);

    });

    function kualitas() {
        $.ajax({
            url: "http://192.168.13.2:85/air/rekap_kualitas",
            type: "get",
            success: function(res) {


                var tds = res.data3[0].tds;
                var tss = res.data3[0].tss;
                var ph = res.data3[0].ph;

                if (ph > 14.00) {
                    ph = 14.00;
                } else if (ph < 0.00) {
                    ph = 0;
                }


                // function PhSens() {

                var pHmeter = res.data3[0].ph;
                var randPercent = (pHmeter / 14.00) * 100;
                var FloorPhMeter = Math.floor(pHmeter * 100) / 100
                //Generic column color
                var color = '#90A4AE';

                if (randPercent <= 100 && randPercent >= 80) {
                    color = '#9e00ff';
                } else if (randPercent < 80 && randPercent >= 53) {
                    color = '#0013ff';
                } else if (randPercent < 53 && randPercent >= 43) {
                    color = '#3aff00';
                } else if (randPercent < 43 && randPercent >= 20) {
                    color = '#fbff00';
                } else if (randPercent < 20 && randPercent >= 0) {
                    color = '#ff0000';
                }

                $('.column').css({
                    background: color
                });

                $('.column').animate({
                    height: randPercent + '%',
                });


                $('.percentage').text(FloorPhMeter);


                // }

                // function TSSSens() {

                var TSSmeter = res.data3[0].tss;
                var TSSrandPercent = (TSSmeter / 3000) * 100;
                var floorTSS = Math.floor(TSSmeter);

                //Generic column color
                var color = '#90A4AE';

                if (TSSrandPercent >= 90) {
                    color = '#FF3D00';
                } else if (TSSrandPercent < 90 && TSSrandPercent >= 60) {
                    color = '#FF9800';
                } else if (TSSrandPercent < 60 && TSSrandPercent >= 40) {
                    color = '#FFEB3B';
                } else if (TSSrandPercent < 40 && TSSrandPercent >= 10) {
                    color = '#81C784';
                } else if (TSSrandPercent < 10 && TSSrandPercent >= 0) {
                    color = '#00E676';
                }

                $('.TSScolumn').css({
                    background: color
                });

                $('.TSScolumn').animate({
                    height: TSSrandPercent + '%',
                });
                console.log(TSSrandPercent)


                $('.TSSpercentage').text(floorTSS);
                // }


                // function TDSSens() {



                var TDSmeter = res.data3[0].tds;
                var TDSrandPercent = (TDSmeter / 1000) * 100;
                var floorTDS = Math.floor(TDSmeter);

                //Generic column color
                var color = '#90A4AE';

                if (TDSrandPercent >= 90) {
                    color = '#FF3D00';
                } else if (TDSrandPercent < 90 && TDSrandPercent >= 60) {
                    color = '#FF9800';
                } else if (TDSrandPercent < 60 && TDSrandPercent >= 45) {
                    color = '#FFEB3B';
                } else if (TDSrandPercent < 45 && TDSrandPercent >= 20) {
                    color = '#81C784';
                } else if (TDSrandPercent < 20 && TDSrandPercent >= 0) {
                    color = '#00E676';
                }

                $('.TDScolumn').css({
                    background: color
                });

                $('.TDScolumn').animate({
                    height: TDSrandPercent + '%',
                });


                $('.TDSpercentage').text(floorTDS);

                // }

            }
        })
    }
</script>


@stop
