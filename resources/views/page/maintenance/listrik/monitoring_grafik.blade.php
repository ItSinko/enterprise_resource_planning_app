@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Grafik Monitoring Listrik</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                <li class="breadcrumb-item active">Grafik Monitoring Listrik</li>
            </ol>
        </div>
    </div>
</div>

@stop

@section('adminlte_css')
<style>
</style>
@stop

@section('content')
<section class="content">

<div class="container-fluid bg-white text-dark">
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active bg-white text-dark ml-2" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"><h6>Grafik Listrik</h6>
          <ul class="nav nav-pills mb-3" id="pills-tab-1" role="tablist">
                <li class="nav-item ml-2">
                    <a class="nav-link active" id="pills-home-tab-1" data-toggle="pill" href="#pills-home-1" role="tab" aria-controls="pills-home-1" aria-selected="True">Real time</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-home-tab-2" data-toggle="pill" href="#pills-home-2" role="tab" aria-controls="pills-home-2" aria-selected="false">15 Menit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-home-tab-3" data-toggle="pill" href="#pills-home-3" role="tab" aria-controls="pills-home-3" aria-selected="false">1 Jam</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-home-tab-4" data-toggle="pill" href="#pills-home-4" role="tab" aria-controls="pills-home-4" aria-selected="false">1 Hari</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-home-tab-5" data-toggle="pill" href="#pills-home-5" role="tab" aria-controls="pills-home-5" aria-selected="false">1 Bulan</a>
                </li>
          </ul>
        </div>
    </div>
    <div class="tab-content" id="pills-tab-1Content">
        <div class="tab-pane fade show active" id="pills-home-1" role="tabpanel" aria-labelledby="pills-home-1">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Pilih Grafik
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item filter_grafik active" id="select_c1"  data-value="c1" >Current</a>
                  <a class="dropdown-item filter_grafik" id="select_vll1"data-value="vll1" >Voltage Line to Line</a>
                  <a class="dropdown-item filter_grafik" id="select_vln1"data-value="vln1" >Voltage Line to Netral</a>
                  <a class="dropdown-item filter_grafik" id="select_p1" data-value="p1" >Power</a>
                  <a class="dropdown-item filter_grafik" id="select_pf1" data-value="pf1">Power faktor</a>
                  <a class="dropdown-item filter_grafik" id="select_dpf1"data-value="dpf1" >Displacement Power Faktor</a>
                  <a class="dropdown-item filter_grafik" id="select_f1" data-value="f1">Frequency</a>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="container" id="vll1">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <canvas id="gvll1"></canvas>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>

                     <div class="container" id="c1">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <canvas id="gc1"></canvas>
                                    </div>
                                </div>
                            </div>
                         </div>
                     </div>
                     <div class="container" id="vln1">
                        vol1
                     </div>
                     <div class="container" id="p1">
                        pow1
                     </div>
                     <div class="container" id="pf1">
                        pf1
                     </div>
                     <div class="container" id="dpf1">
                        dpf1
                     </div>
                     <div class="container" id="f1">
                        f1
                     </div>
                </div>
            </div>
        </div>
    </div>


{{--  --}}
</div>
</section>
@stop

@section('adminlte_js')


<script>

    var chartData = {
    labels: ["S", "M", "T", "W", "T", "F", "S"],
    datasets: [{
        label: 'Voltage r-s',
        data: [589, 445, 483, 503, 689, 692, 634],
    },
    {   label:'volatage s-t',
        data: [639, 465, 493, 478, 589, 632, 674],
    }]
    };
    var chLine = document.getElementById("gvll1");
    if (chLine) {
        new Chart(chLine, {
        type: 'line',
        data: chartData,
        options: {
        scales: {
            yAxes: [{
            ticks: {
                beginAtZero: false
            }
            }]
        },
        legend: {
            display: false
        }
        }
        });
    }
    var chartData = {
    labels: ["S", "M", "T", "W", "T", "F", "S"],
    datasets: [{
        label:'current A',
        data: [589, 445, 483, 503, 689, 692, 634],
    },
    {
        label:'Current B',
        data: [639, 465, 493, 478, 589, 632, 674],
    }]
    };

    var chLine = document.getElementById("gc1");
    if (chLine) {
        new Chart(chLine, {
        type: 'line',
        data: chartData,
        options: {
        scales: {
            yAxes: [{
            ticks: {
                beginAtZero: false
            }
            }]
        },
        legend: {
            display: false
        }
        }
        });
    }


    var gc1 = $('#gc1');
      $('#c1').show();

      function hide(){
        $("#select_c1").removeClass('active');
        $("#select_vll1").removeClass('active');
        $("#select_vln1").removeClass('active');
        $("#select_p1").removeClass('active');
        $("#select_pf1").removeClass('active');
        $("#select_dpf1").removeClass('active');
        $("#select_f1").removeClass('active');

     $('#vll1').hide();
     $('#vln1').hide();
     $('#p1').hide();
     $('#pf1').hide();
     $('#dpf1').hide();
     $('#f1').hide();
    }
    $(".filter_grafik").click(function(){
        var value = $(this).attr('data-value');
        $('#c1').hide();
        hide();
        $('#'+value).show();
        $('#select_'+value).addClass('active');
    });


</script>
@stop
