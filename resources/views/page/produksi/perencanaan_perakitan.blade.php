@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
    <style>
        .topnav a {
            float: left;
            display: block;
            color: black;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
            border-bottom: 3px solid transparent;
        }

        .active {
            box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('vendor/fullcalendar/main.css') }}">
    <script src="{{ asset('vendor/fullcalendar/main.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('vendor/fullcalendar/main.css') }}">
    <script src="{{ asset('vendor/fullcalendar/main.js') }}"></script>
    <input type="hidden" name="" id="auth" value="{{ Auth::user()->Karyawan->divisi_id }}">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Perencanaan Perakitan</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="ml-3">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <nav>
                            <div class="nav nav-tabs topnav" id="nav-tab" role="tablist">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">Kalender</a>
                                <a id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                                    aria-selected="false">Tabel</a>
                            </div>
                        </nav>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div id='calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-layer-group"></i> Perencanaan Perakitan Bulan
                                {{ Carbon\Carbon::now()->addMonth()->isoFormat('MMMM') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-bordered table-produk-perakitan">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th colspan="2" class="text-center">Tanggal</th>
                                                <th rowspan="2">Produk</th>
                                                <th rowspan="2">Jumlah Rakit</th>
                                            </tr>
                                            <tr>
                                                <th>Tgl Mulai</th>
                                                <th>Tgl Selesai</th>
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

@stop

@section('adminlte_js')
    <script>
        $(function() {

            var Calendar = FullCalendar.Calendar;
            var calendarEl = document.getElementById('calendar');

            var calendar = new Calendar(calendarEl, {
                headerToolbar: {
                    left: '',
                    center: 'title',
                    right: ''
                },
                locale: 'id',

                dateClick: function(info) {
                    $('.tanggalModal').text(moment(info.dateStr).format('DD-MM-YYYY'));
                    $('.modalPertanggal').modal('show');
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: "/api/prd/plan-cal",
                        type: "post",
                        dataType: "json",
                        success: function(res) {
                            var events = [];
                            if (res != null) {
                                console.log(res);
                                $.each(res, function(i, item) {
                                    events.push({
                                        start: item.tanggal_mulai,
                                        end: item.tanggal_selesai +
                                            'T23:59:59',
                                        title: item.produk.produk.nama +
                                            ' ' + item.produk.nama,
                                        backgroundColor: item.warna,
                                        borderColor: item.warna,
                                    })
                                })
                            }
                            console.log('events', events);
                            successCallback(events);
                        }
                    })
                }
            });
            calendar.render();
            calendar.next();

            var oTable = $('.table-produk-perakitan').DataTable({
                destroy: true,
                "paging": true,
                "lengthChange": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/api/prd/plan",
                    type: "post",
                },
                columns: [{
                        data: "start"
                    },
                    {
                        data: "end"
                    },
                    {
                        data: "produk"
                    },
                    {
                        data: "jml"
                    },
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                }
            });
        })
    </script>
@stop
