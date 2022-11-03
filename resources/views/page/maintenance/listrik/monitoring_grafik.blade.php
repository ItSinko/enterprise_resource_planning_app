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

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


            <div class="d-flex bd-highlight">
                <div class="flex-grow-1 bd-highlight">

                    <select class="js-example-basic-single" id="masuk" name="state">
                        <option selected>pilih panel</option>


                    </select>

                    <div class="dropdown my-2">
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
                </div>
                <div class="bd-highlight">
                    <ul class="nav nav-pills mb-3" id="pills-tab-1" role="tablist">
                        <li class="nav-item ml-2">
                            <a class="nav-link active" id="pills-home-tab_1" data-toggle="pill" href="#pills-home-1" role="tab" aria-controls="pills-home-1" aria-selected="True">Real time</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-home-tab_2" data-toggle="pill" href="#pills-home-2" role="tab" aria-controls="pills-home-2" aria-selected="false">15 Menit</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-home-tab_3" data-toggle="pill" href="#pills-home-3" role="tab" aria-controls="pills-home-3" aria-selected="false">1 Jam</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-home-tab_4" data-toggle="pill" href="#pills-home-4" role="tab" aria-controls="pills-home-4" aria-selected="false">1 Hari</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-home-tab_5" data-toggle="pill" href="#pills-home-5" role="tab" aria-controls="pills-home-5" aria-selected="false">1 Bulan</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content" id="pills-tab-1Content">
                <div class="tab-pane fade show active" id="pills-home-1" role="tabpanel" aria-labelledby="pills-home-1">
                    <div class="row">
                        <div class="col-12">
                            <div class="container">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="title-rt"></div>
                                        <canvas id="grafik_rt"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row">
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
                             <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <canvas id="gvln1"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                             </div>
                             <div class="container" id="p1">
                             <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <canvas id="gp1"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                             </div>
                             <div class="container" id="pf1">
                             <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <canvas id="gpf1"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                             </div>
                             <div class="container" id="dpf1">
                             <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <canvas id="gdpf1"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                             </div>
                             <div class="container" id="f1">
                             <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <canvas id="gf1"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                             </div>
                        </div>
                    </div> --}}
                </div>
                <div class="tab-pane fade" id="pills-home-2" role="tabpanel" aria-labelledby="pills-home-2">
                    <div class="row m-3">
                        <div class="col-12">
                            <div class="container">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="title-15m"></div>
                                        <canvas id="grafik_15m"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-home-3" role="tabpanel" aria-labelledby="pills-home-3">
                    <div class="row m-3">
                        <div class="col-12">
                            <div class="container">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="title-1j"></div>
                                        <canvas id="grafik_1j"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-home-4" role="tabpanel" aria-labelledby="pills-home-4">
                    <div class="row m-3">
                        <div class="col-12">
                            <div class="container">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="title-1h"></div>
                                        <canvas id="grafik_1h"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-home-5" role="tabpanel" aria-labelledby="pills-home-5">
                    <div class="row m-3">
                        <div class="col-12">
                            <div class="container">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="title-1b"></div>
                                        <canvas id="grafik_1b"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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


{{-- <script>

    //grafik vll
    let datavll = [];
    let vll = {
    labels: [],
    datasets: []
    };
    var chLine = document.getElementById("gvll1");
        let vllchart = new Chart(chLine, {
        type: 'line',
        data: vll,
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

    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });


    $(document).ready(function () {
        var value = 'c1';
        $('#c1').hide();
        hide();
        $('#'+value).show();
        $('#select_'+value).addClass('active');

        let pilih_device = $('#masuk').val();
        getvllgrafik();
    }(jQuery));

    function getvllgrafik() {
        let pilih_device = $('#masuk').val();
        $.ajax({
        type:'get',
        url:'http://192.168.13.2:85/listrik/ambilrtvll',
        success:function(data) {
            datavll.push(data.data);
            getspecvll(pilih_device);
        }
        });
    }
    var arrayColor = ['#ff6384',
            '#36a2eb',
            '#cc65fe','#a8327d',
            '#ffce56','#000000','#32a852','#a83632',
            '#a85932','#98a832','#5da832','#32a851',
            '#32a89e'
        ];
    function getspecvll(device) {
        console.log("datavllgrafik",datavll)
        let labelsChart = [];
        let datasets = [];
        const result = datavll[0].filter((item) => item.device === device);
        const labels = result.map((item) => item.detail);
        labels.forEach(element => {
            element.forEach((item) => {
                datasets.push(item);
            });
        });
        labelsChart.push(Object.values(datasets[0].Date_Time));
        vll.labels = labelsChart[0];
        datasets.forEach((item,index) => {
            // console.log(item);
            if(Object.keys(item) != 'Date_Time'){
                vll.datasets.push({
                borderColor: arrayColor[index],
                label: Object.keys(item),
                data: Object.values(item)[0],
            });
            }
        });
        vllchart.update();
        // const test = vll.labels = Object.keys(labels[0]);
        console.log(vll);
    }
    //grafik current
    let datac = [];
        let c = {
        labels: [],
        datasets: []
        };
        var chLine = document.getElementById("gc1");
            let cchart = new Chart(chLine, {
            type: 'line',
            data: c,
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

        $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });


    $(document).ready(function () {
        let pilih_device = $('#masuk').val();
        getcgrafik();
    }(jQuery));

    function getcgrafik() {
        let pilih_device = $('#masuk').val();
        $.ajax({
        type:'get',
        url:'http://192.168.13.2:85/listrik/ambilrtcurrent',
        success:function(data) {
            datac.push(data.data);
            getspecc(pilih_device);
        }
        });
    }
    var arrayColor = ['#ff6384',
            '#36a2eb',
            '#cc65fe','#a8327d',
            '#ffce56','#000000','#32a852','#a83632',
            '#a85932','#98a832','#5da832','#32a851',
            '#32a89e'
        ];
    function getspecc(device) {
        console.log("datacgrafik",datac)
        let labelsChart = [];
        let datasets = [];
        const result = datac[0].filter((item) => item.device === device);
        const labels = result.map((item) => item.detail);
        labels.forEach(element => {
            element.forEach((item) => {
                datasets.push(item);
            });
        });
        labelsChart.push(Object.values(datasets[0].Date_Time));
        c.labels = labelsChart[0];
        datasets.forEach((item,index) => {

            if(Object.keys(item) != 'Date_Time'){
                c.datasets.push({
                borderColor: arrayColor[index],
                label: Object.keys(item),
                data: Object.values(item)[0],
            });
            }
        });
        cchart.update();
        console.log("chart current", c);
    }
    // grafik vln
    let datavln = [];
        let vln = {
        labels: [],
        datasets: []
        };
        var chLine = document.getElementById("gvln1");
            let vlnchart = new Chart(chLine, {
            type: 'line',
            data: vln,
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

        $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });


    $(document).ready(function () {

        let pilih_device = $('#masuk').val();
        getvlngrafik();
    }(jQuery));

    function getvlngrafik() {
        let pilih_device = $('#masuk').val();
        $.ajax({
        type:'get',
        url:'http://192.168.13.2:85/listrik/ambilrtvln',
        success:function(data) {
            datavln.push(data.data);
            getspecvln(pilih_device);
        }
        });
    }
    var arrayColor = ['#ff6384',
            '#36a2eb',
            '#cc65fe','#a8327d',
            '#ffce56','#000000','#32a852','#a83632',
            '#a85932','#98a832','#5da832','#32a851',
            '#32a89e'
        ];
    function getspecvln(device) {
        console.log("datavlngrafik",datavln)
        let labelsChart = [];
        let datasets = [];
        const result = datavln[0].filter((item) => item.device === device);
        const labels = result.map((item) => item.detail);
        labels.forEach(element => {
            element.forEach((item) => {
                datasets.push(item);
            });
        });
        labelsChart.push(Object.values(datasets[0].Date_Time));
        vln.labels = labelsChart[0];
        datasets.forEach((item,index) => {
            // console.log(item);
            if(Object.keys(item) != 'Date_Time'){
                vln.datasets.push({
                borderColor: arrayColor[index],
                label: Object.keys(item),
                data: Object.values(item)[0],
            });
            }
        });
        vlnchart.update();

        console.log(vln);
    }
// grafik power
    let datapower = [];
        let power = {
        labels: [],
        datasets: []
        };
        var chLine = document.getElementById("gp1");
            let powerchart = new Chart(chLine, {
            type: 'line',
            data: power,
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

        $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });


    $(document).ready(function () {
        let pilih_device = $('#masuk').val();
        getpowergrafik();
    }(jQuery) );

    function getpowergrafik() {
        let pilih_device = $('#masuk').val();
        $.ajax({
        type:'get',
        url:'http://192.168.13.2:85/listrik/ambilrtp',
        success:function(data) {
            datapower.push(data.data);
            getspecpower(pilih_device);
        }
        });
    }

    var arrayColor = ['#36a2eb','#cc65fe','#a8327d','#db0d0d',
            '#ffce56','#000000','#32a852','#a83632','#877878',
            '#a85932','#98a832','#5da832',,'#6d32a8','#32a851',
            '#32a89e'
        ];
    function getspecpower(device) {
        console.log("datapowergrafik",datavln)
        let labelsChart = [];
        let datasets = [];
        const result = datapower[0].filter((item) => item.device === device);
        const labels = result.map((item) => item.detail);
        labels.forEach(element => {
            element.forEach((item) => {
                datasets.push(item);
            });
        });
        labelsChart.push(Object.values(datasets[0].Date_Time));
        power.labels = labelsChart[0];
        datasets.forEach((item,index) => {
            console.log(arrayColor[index]+' '+Object.keys(item));
            if(Object.keys(item) != 'Date_Time'){
                power.datasets.push({
                borderColor: arrayColor[index],
                label: Object.keys(item),
                data: Object.values(item)[0],
            });
            }
        });
        powerchart.update();

        console.log(power);
    }
// grafik power faktor
let datapowerfactor = [];
    let powerfactor = {
    labels: [],
    datasets: []
    };
    var chLine = document.getElementById("gpf1");
        let powerfactorchart = new Chart(chLine, {
        type: 'line',
        data: powerfactor,
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

    $(document).ready(function() {
    $('.js-example-basic-single').select2();
});


    $(document).ready(function () {

        let pilih_device = $('#masuk').val();
        getpowerfactorgrafik();
    }(jQuery));

    function getpowerfactorgrafik() {
        let pilih_device = $('#masuk').val();
        $.ajax({
        type:'get',
        url:'http://192.168.13.2:85/listrik/ambilrtpf',
        success:function(data) {
            datapowerfactor.push(data.data);
            getspecpowerfactor(pilih_device);
        }
        });
    }
    var arrayColor = ['#ff6384',
            '#36a2eb',
            '#cc65fe','#a8327d',
            '#ffce56','#000000','#32a852','#a83632',
            '#a85932','#98a832','#5da832','#32a851',
            '#32a89e'
        ];
    function getspecpowerfactor(device) {
        console.log("datapowerfactorgrafik",datavln)
        let labelsChart = [];
        let datasets = [];
        const result = datapowerfactor[0].filter((item) => item.device === device);
        const labels = result.map((item) => item.detail);
        labels.forEach(element => {
            element.forEach((item) => {
                datasets.push(item);
            });
        });
        labelsChart.push(Object.values(datasets[0].Date_Time));
        powerfactor.labels = labelsChart[0];
        datasets.forEach((item,index) => {
            // console.log(item);
            if(Object.keys(item) != 'Date_Time'){
                powerfactor.datasets.push({
                borderColor: arrayColor[index],
                label: Object.keys(item),
                data: Object.values(item)[0],
            });
            }
        });
        powerfactorchart.update();

        console.log(powerfactor);
    }
// grafik dis power faktor
let datadpowerfactor = [];
    let dpowerfactor = {
    labels: [],
    datasets: []
    };
    var chLine = document.getElementById("gdpf1");
        let dpowerfactorchart = new Chart(chLine, {
        type: 'line',
        data: dpowerfactor,
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

    $(document).ready(function() {
    $('.js-example-basic-single').select2();
});


    $(document).ready(function () {

        let pilih_device = $('#masuk').val();
        getdpowerfactorgrafik();
    }(jQuery));

    function getdpowerfactorgrafik() {
        let pilih_device = $('#masuk').val();
        $.ajax({
        type:'get',
        url:'http://192.168.13.2:85/listrik/ambilrtdpf',
        success:function(data) {
            datadpowerfactor.push(data.data);
            getspecdpowerfactor(pilih_device);
        }
        });
    }
    var arrayColor = ['#ff6384',
            '#36a2eb',
            '#cc65fe','#a8327d',
            '#ffce56','#000000','#32a852','#a83632',
            '#a85932','#98a832','#5da832','#32a851',
            '#32a89e'
        ];
    function getspecdpowerfactor(device) {
        console.log("datadpowerfactorgrafik",datavln)
        let labelsChart = [];
        let datasets = [];
        const result = datadpowerfactor[0].filter((item) => item.device === device);
        const labels = result.map((item) => item.detail);
        labels.forEach(element => {
            element.forEach((item) => {
                datasets.push(item);
            });
        });
        labelsChart.push(Object.values(datasets[0].Date_Time));
        dpowerfactor.labels = labelsChart[0];
        datasets.forEach((item,index) => {
            // console.log(item);
            if(Object.keys(item) != 'Date_Time'){
                dpowerfactor.datasets.push({
                borderColor: arrayColor[index],
                label: Object.keys(item),
                data: Object.values(item)[0],
            });
            }
        });
        dpowerfactorchart.update();

        console.log(dpowerfactor);
    }
// grafik frequency
let datafre = [];
    let fre = {
    labels: [],
    datasets: []
    };
    var chLine = document.getElementById("gf1");
        let frechart = new Chart(chLine, {
        type: 'line',
        data: fre,
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

    $(document).ready(function() {
    $('.js-example-basic-single').select2();
});


    $(document).ready(function () {

        let pilih_device = $('#masuk').val();
        getfregrafik();
    }(jQuery));

    function getfregrafik() {
        let pilih_device = $('#masuk').val();
        $.ajax({
        type:'get',
        url:'http://192.168.13.2:85/listrik/ambilrtf',
        success:function(data) {
            datafre.push(data.data);
            getspecfre(pilih_device);
        }
        });
    }
    var arrayColor = ['#ff6384',
            '#36a2eb',
            '#cc65fe','#a8327d',
            '#ffce56','#000000','#32a852','#a83632',
            '#a85932','#98a832','#5da832','#32a851',
            '#32a89e'
        ];
    function getspecfre(device) {
        console.log("datafregrafik",datavln)
        let labelsChart = [];
        let datasets = [];
        const result = datafre[0].filter((item) => item.device === device);
        const labels = result.map((item) => item.detail);
        labels.forEach(element => {
            element.forEach((item) => {
                datasets.push(item);
            });
        });
        labelsChart.push(Object.values(datasets[0].Date_Time));
        fre.labels = labelsChart[0];
        datasets.forEach((item,index) => {
            // console.log(item);
            if(Object.keys(item) != 'Date_Time'){
                fre.datasets.push({
                borderColor: arrayColor[index],
                label: Object.keys(item),
                data: Object.values(item)[0],
            });
            }
        });
        frechart.update();

        console.log(fre);
    }
// show
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


</script> --}}

<script>
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



    function getpanel() {
        $.ajax({
            type: 'get',
            url: 'http://192.168.13.2:85/listrik/ambilpanel',
            success: function (data) {
                let x = data.data.length;

                for (a = 0; a < x; a++) {
                    $('#masuk').append(
                        '<option  value="'+ data.data[a].device_id +'" id="'+ data.data[a].device_id +'">' + data.data[a].device_id + '</option>'
                    );
                }}})}
    $(function(){
        $('#masuk').select2();
        getpanel();
        var grafik = "";
        var current_select = "rt";
        var current_grafik = "c1";
        var current_text = "CURRENT";
        var arrayColor = ['#ff6384',
            '#36a2eb',
            '#cc65fe','#a8327d',
            '#ffce56','#000000','#32a852','#a83632',
            '#a85932','#98a832','#5da832','#32a851',
            '#32a89e'
        ];

        var array_data = [];
        var data_grafik = {
            labels: [],
            datasets: []
        };


        var grafik_rt = document.getElementById("grafik_rt");
        let thechart_rt = new Chart(grafik_rt, {
            type: 'line',
            data: data_grafik,
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
                },
            }
        });

        var grafik_15m = document.getElementById("grafik_15m");
        let thechart_15m = new Chart(grafik_15m, {
            type: 'line',
            data: data_grafik,
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
                },
            }
        });

        var grafik_1j = document.getElementById("grafik_1j");
        let thechart_1j = new Chart(grafik_1j, {
            type: 'line',
            data: data_grafik,
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
                },
            }
        });

        var grafik_1h = document.getElementById("grafik_1h");
        let thechart_1h = new Chart(grafik_1h, {
            type: 'line',
            data: data_grafik,
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
                },
            }
        });

        var grafik_1b = document.getElementById("grafik_1b");
        let thechart_1b = new Chart(grafik_1b, {
            type: 'line',
            data: data_grafik,
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
                },
            }
        });

        function getgrafikdata(current_grafik, current_select, selected_chart) {
            var aspek = "";
            array_data = [];
            data_grafik.labels.length = 0;
            data_grafik.datasets.length = 0;
            if(current_grafik == "c1"){
                aspek = "current";
            }else if(current_grafik == "vll1"){
                aspek = "vll";
            }else if(current_grafik == "vln1"){
                aspek = "vln";
            }else if(current_grafik == "p1"){
                aspek = "p";
            }else if(current_grafik == "pf1"){
                aspek = "pf";
            }else if(current_grafik == "dpf1"){
                aspek = "dpf";
            }else if(current_grafik == "f1"){
                aspek = "f";
            }
            $.ajax({
                type:'get',
                url:'http://192.168.13.2:85/listrik/ambil'+current_select+aspek,
                success:function(data) {
                    array_data.push(data.data);
                    getspecdata($('#masuk').val(), selected_chart);
                }
            });
        }

        function getspecdata(device, selected_chart) {
            console.log(device+" "+$('#masuk').val());
            let labelsChart = [];
            let datasets = [];
            const result = array_data[0].filter((item) => item.device === device);
            const labels = result.map((item) => item.detail);
            labels.forEach(element => {
                element.forEach((item) => {
                    datasets.push(item);
                });
            });
            labelsChart.push(Object.values(datasets[0].Date_Time));
            data_grafik.labels = labelsChart[0];
            datasets.forEach((item,index) => {
                if(Object.keys(item) != 'Date_Time'){
                    data_grafik.datasets.push({
                    borderColor: arrayColor[index],
                    label: Object.keys(item),
                    data: Object.values(item)[0],
                });
                }
            });
            selected_chart.update();
        }

        getgrafikdata(current_grafik, current_select, thechart_rt);

        $(document).on('click', '#pills-home-tab_1', function(){
            current_select = "rt";
            getgrafikdata(current_grafik, current_select, thechart_rt);
        });

        $(document).on('click', '#pills-home-tab_2', function(){
            current_select = "15m";
            getgrafikdata(current_grafik, current_select, thechart_15m);
        });

        $(document).on('click', '#pills-home-tab_3', function(){
            current_select = "1j";
            getgrafikdata(current_grafik, current_select, thechart_1j);
        });

        $(document).on('click', '#pills-home-tab_4', function(){
            current_select = "1h";
            getgrafikdata(current_grafik, current_select, thechart_1h);
        });

        $(document).on('click', '#pills-home-tab_5', function(){
            current_select = "1b";
            getgrafikdata(current_grafik, current_select, thechart_1b);
        });

        $(document).on('click', '.dropdown-item', function(){
            current_grafik = $(this).attr('data-value');
            if(current_select == "rt"){
                getgrafikdata(current_grafik, current_select, thechart_rt);
            }
            else if(current_select == "15m"){
                getgrafikdata(current_grafik, current_select, thechart_15m);
            }
            else if(current_select == "1j"){
                getgrafikdata(current_grafik, current_select, thechart_1j);
            }
            else if(current_select == "1h"){
                getgrafikdata(current_grafik, current_select, thechart_1h);
            }
            else if(current_select == "1b"){
                getgrafikdata(current_grafik, current_select, thechart_1b);
            }
        });

        $(document).on('change keyup', '#masuk', function(){
            if(current_select == "rt"){
                getgrafikdata(current_grafik, current_select, thechart_rt);
            }
            else if(current_select == "15m"){
                getgrafikdata(current_grafik, current_select, thechart_15m);
            }
            else if(current_select == "1j"){
                getgrafikdata(current_grafik, current_select, thechart_1j);
            }
            else if(current_select == "1h"){
                getgrafikdata(current_grafik, current_select, thechart_1h);
            }
            else if(current_select == "1b"){
                getgrafikdata(current_grafik, current_select, thechart_1b);
            }
        });

    });
</script>
@stop
