@extends('adminlte.page')

@section('title', 'Beta Version')

@section('content_top_nav_right')
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-danger navbar-badge" style="display: none;">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notif">
            <div class="dropdown-divider"></div>
            <span class="dropdown-item dropdown-header" id="notif-header">0 Notifications</span>
        </div>
    </li>
@stop

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Jadwal Produksi</h1>
        <div class="btn-group" id="view-button">
            <button type="button" data-view="calendar" class="btn view btn-primary">Kalender</button>
            <button type="button" data-view="table" class="btn view btn-info">Tabel</button>
            <button type="button" data-view="list" class="btn view btn-warning">Daftar</button>
        </div>
    </div>
@stop

@section('adminlte_css')
    <link href='{{ asset('vendor/fullcalendar/main.css') }}' rel='stylesheet' />
    <style>
        #calendar {
            padding: 20px;
        }

        #table-product tr:hover {
            background-color: yellow;
            cursor: pointer;
        }

        #table-product tr {
            background-color: white;
        }
    </style>
@stop

@section('content')
    <div hidden>
        <!-- container data from controller to javascript code below -->
        <div id="data_produk">{{ $detail_produk }}</div>
        <div id="data_event">{{ $event }}</div>
        <div id="data_status">{{ $status }}</div>
        <div id="data_user">{{ Auth::user() }}</div>
    </div>

    <div>
        <div class="alert alert-info alert-dismissible" id="status_penyusunan" style="display: none;">
            <h5><i class="icon fas fa-info"></i> Status </h5>
            Penyusunan
        </div>
        <div class="alert alert-info alert-dismissible" id="status_disetujui" style="display: none;">
            <h5><i class="icon far fa-thumbs-up"></i> Status </h5>
            Disetujui
        </div>
        <div class="alert alert-warning alert-dismissible" id="status_pelaksanaan" style="display: none;">
            <h5><i class="icon fas fa-hard-hat"></i> Status</h5>
            Pelaksanaan
        </div>
        <div class="alert alert-success alert-dismissible" id="status_selesai" style="display: none;">
            <h5><i class="icon fas fa-check"></i> Status</h5>
            Selesai
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 view-calendar" style="display: none">
            <div class="sticky-top">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar Produksi</h4>
                    </div>
                    <div class="card-body">
                        <table style="text-align: center; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Jumlah Produksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-product">
                                @foreach ($event as $e)
                                    <tr data-id="{{ $e->id }}">
                                        <td>{{ $e->nama }}</td>
                                        <td>{{ $e->jumlah_produksi }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if (Auth::user()->Karyawan->divisi_id == 3)
                    @if ($status == 'penyusunan')
                        <button class="btn btn-danger btn-block" id="acc-button" style="display: none;">Konfirmasi
                            jadwal</button>
                    @endif
                @endif
                @if (Auth::user()->Karyawan->divisi_id == 24)
                    <button class="btn btn-info btn-block" id="bppb-button" style="display: none;">BPPB</button>
                    <button class="btn btn-primary btn-block" id="approval-button" style="display: none;">Minta
                        persetujuan</button>
                @endif
            </div>
        </div>
        <div class="col-md-9 view-calendar" style="display: none">
            <div class="card">
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-view" style="display: none;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Table View</h3>
                <div class="card-tools">
                    <div class="btn-group" role="group" id="button-date">
                        <button type="button" class="btn btn-secondary"><i class="fas fa-less-than"></i></button>
                        <button type="button" class="btn btn-secondary"><i class="fas fa-greater-than"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-body" style="overflow-x: auto;">
                <table class="table table-bordered center">
                    <thead>
                        <tr>
                            <th style="width: 20%">Produk</th>
                            <th style="width: 10%">Jumlah Produksi</th>
                            @for ($i = 1; $i <= date('t'); $i++)
                                <th>{{ $i }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody id="table-date" data-num_date="{{ date('t') }}">
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <span
                    style="background-color: black; display: inline-block; height:15px; width:15px; margin-right:10px;"></span><span>Hari
                    libur</span><br>
                <span
                    style="background-color: yellow; display: inline-block; height:15px; width:15px; margin-right:10px;"></span><span>Hari
                    produksi</span><br>
                <span
                    style="background-color: white; display: inline-block; height:15px; width:15px; margin-right:10px;"></span><span>Hari
                    kosong</span><br>
            </div>
        </div>
    </div>

    <div class="list-view" style="display: none;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List View</h3>
                <div class="card-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed text-nowrap" id="list-view-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($event as $e)
                            <tr data-id="{{ $e->id }}">
                                <td>{{ $e->nama }}</td>
                                <td>{{ $e->tanggal_mulai }}</td>
                                <td>{{ $e->tanggal_selesai }}</td>
                                <td>On Progress</td>
                                <td>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                    </div>
                                    <span class="badge bg-danger">55%</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="modal fade" id="modal-input-product">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form onsubmit="return false" id="product-form">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Produksi</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="choose_color" class="col-sm-2 col-form-label">Pilih warna</label>
                            <div id="choose_color">
                                <button type="button" class="btn btn-primary" style="padding: 20px"></button>
                                <button type="button" class="btn btn-secondary" style="padding: 20px"></button>
                                <button type="button" class="btn btn-success" style="padding: 20px"></button>
                                <button type="button" class="btn btn-danger" style="padding: 20px"></button>
                                <button type="button" class="btn btn-warning" style="padding: 20px"></button>
                                <button type="button" class="btn btn-info" style="padding: 20px"></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="product-name" class="col-sm-2 col-form-label">Produksi</label>
                            <div class="col-sm-10">
                                <select id="product-name" class="form-control" placeholder="Pilih Produk...">
                                    <option value=""></option>
                                    @foreach ($detail_produk as $e)
                                        <option value="{{ $e->id }}">{{ $e->nama }}</option>
                                    @endforeach
                                </select>
                                <span style='color: red;'></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="product-bom-version" class="col-sm-2 col-form-label">Versi BOM</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="product-bom-version" disabled>
                                    <option value=""></option>
                                </select>
                                <span style='color: red;'></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="product-quantity" class="col-sm-2 col-form-label">Jumlah Produksi</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="product-quantity" disabled>
                                <span style='color: grey;'></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="product-days" class="col-sm-2 col-form-label">Jumlah Hari</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="product-days" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="date_start">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="date_start" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="date_end">Tanggal Berhenti</label>
                                <input type="date" class="form-control" id="date_end" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary" id="product-submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-show-bom">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Pilih BOM</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group col-6" style="margin-left: auto; margin-right: auto; margin-bottom: 10px">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="input">Search</label>
                        </div>
                        <select class="form-control" id="bom-version-input">
                            <option value=""></option>
                        </select>
                    </div>
                    <table id="table-bom" class="table table-hover styled-table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jumlah</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="create-bppb">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Pilih BOM</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" id="table-bppb" style="text-align: center">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($event as $e)
                                <tr data-id="{{ $e->id }}">
                                    <td>{{ $e->nama }}</td>
                                    <td>{{ $e->jumlah_produksi }}</td>
                                    <td><button class="btn btn-info send" data-id="{{ $e->id }}">
                                            @if ($e->status == 'permintaan')
                                                <i class="fas fa-check"></i>
                                            @else
                                                Kirim
                                            @endif
                                        </button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-danger" id="send-all">Kirim Semua</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/fullcalendar/main.js') }}"></script>
    <script src="{{ asset('vendor/fullcalendar/locales-all.js') }}"></script>
    <script src="{{ asset('vendor/bootbox/bootbox.js') }}"></script>
    <script src="{{ asset('js/notif.js') }}"></script>

    <script>
        var produk = JSON.parse($('#data_produk').html()); // GET data produk from controller
        var event = JSON.parse($('#data_event').html()); // GET data event from controller
        var user = JSON.parse($('#data_user').html()); // GET data user
        var status = $('#data_status').html(); // GET data status from controller
        console.log(user);

        // ajax setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // get object array to initialize fullcalendar
        let initial_event = [];
        for (let i = 0; i < event.length; i++) {
            initial_event[i] = {
                id: event[i].id,
                title: event[i].nama,
                start: event[i].tanggal_mulai,
                end: event[i].tanggal_selesai,
                color: event[i].warna,
            }
        }

        // count number of days
        function get_work_day(date1, date2) {
            const date1utc = Date.UTC(date1.getFullYear(), date1.getMonth(), date1.getDate());
            const date2utc = Date.UTC(date2.getFullYear(), date2.getMonth(), date2.getDate());
            day = 1000 * 60 * 60 * 24;

            var days = (date2utc - date1utc) / day;
            var weeks = Math.floor(days / 7);
            days = days - (weeks * 2);

            var startDay = date1.getDay();
            var endDay = date2.getDay() - 1;

            // Remove weekend not previously removed.
            if (startDay - endDay > 1)
                days = days - 2;

            // Remove start day if span starts on Sunday but ends before Saturday
            if (startDay == 0 && endDay != 6)
                days = days - 1;

            // Remove end day if span ends on Saturday but starts after Sunday
            if (endDay == 6 && startDay != 0)
                days = days - 1;

            return days;
        }

        // choose view template
        function choose_view(view) {
            if (view == 'calendar') {
                $('.view-calendar').show(500);
                $('.table-view, .list-view').hide();
            } else if (view == 'table') {
                $('.table-view').show(500);
                $('.view-calendar, .list-view').hide();
            } else if (view == 'list') {
                $('.list-view').show(500);
                $('.view-calendar, .table-view').hide();
            }
        }

        // action base on status sended
        function choose_status(status) {
            if (status == 'penyusunan') {
                $('#approval-button').show();
                $('#status_penyusunan').show();
                $('#status_disetujui, #status_pelaksanaan, #status_selesai').hide();
            } else if (status == 'pelaksanaan') {
                $('#status_pelaksanaan').show();
                $('#status_disetujui, #status_penyusunan, #status_selesai').hide();
            } else if (status == 'selesai') {
                $('#status_selesai').show();
                $('#status_disetujui, #status_penyusunan, #status_pelaksanaan').hide();
            } else if (status == 'disetujui' || status == "permintaan") {
                $('#bppb-button').show();
                $('#status_disetujui').show();
                $('#status_selesai, #status_penyusunan, #status_pelaksanaan').hide();
            }
        }

        // table-view function
        function date_mark(event) {
            let num = $('#table-date').data('num_date');
            let table = $('#table-date').append(`<tr data-id="${event.id}"></tr>`)
            let table_row = $(`#table-date tr[data-id="${event.id}"]`);

            let start_date = new Date(event.tanggal_mulai);
            let end_date = new Date(event.tanggal_selesai);

            let month = start_date.getMonth();
            let year = start_date.getFullYear();
            // console.log(current_date);
            table_row.append(`<td>${event.nama}</td>`);
            table_row.append(`<td>${event.jumlah_produksi}</td>`);

            for (let i = 1; i <= num; i++) {
                let date = new Date(year, month, i);
                if (date.getDay() == 0 || date.getDay() == 6)
                    table_row.append(`<td style="background-color: black;"></td>`)
                else if (i >= start_date.getDate() && i < end_date.getDate())
                    table_row.append(`<td style="background-color: yellow;"></td>`)
                else
                    table_row.append(`<td></td>`);
            }
        }
        // list-view function
        function list_view_table_update(event) {
            $('#list-view-table tbody').append(`
        <tr data-id="${event.id}">
            <td>${event.nama}</td>
            <td>${event.tanggal_mulai}</td>
            <td>${event.tanggal_selesai}</td>
            <td>On Progress</td>
            <td>
                <div class="progress progress-xs">
                    <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                </div>
                <span class="badge bg-danger">55%</span>
            </td>
        </tr>
        `);
        }

        // calendar-view setting
        var calendar_setting;
        if (event.length != 0) status = event[0].status
        if (status === "penyusunan")
            calendar_setting = {
                locale: 'id',
                headerToolbar: {
                    end: ""
                },

                weekends: false,
                weekNumbers: true,
                showNonCurrentDates: false,

                selectable: true,
                editable: false,

                events: initial_event,

                select: function(info) {
                    reset_form();
                    var date1 = new Date(info.startStr);
                    var date2 = new Date(info.endStr);

                    var days = get_work_day(date1, date2);

                    $('#date_start').val(info.startStr);
                    $('#date_end').val(info.endStr);
                    $('#product-days').val(days);

                    $('#modal-input-product').modal('show');
                },

                eventClick: function(info) {
                    bootbox.confirm({
                        centerVertical: true,
                        message: "Apakah Anda ingin menghapus produksi ini?",
                        buttons: {
                            confirm: {
                                label: 'Ya',
                                className: 'btn-success'
                            },
                            cancel: {
                                label: 'Tidak',
                                className: 'btn-danger'
                            }
                        },
                        callback: function(result) {
                            if (result) {
                                $.ajax({
                                    url: "/ppic/schedule/delete",
                                    data: {
                                        id: info.event._def.publicId
                                    },
                                    method: "POST",
                                    success: function() {
                                        info.event.remove();
                                        $(`tr[data-id="${info.event._def.publicId}"]`).remove();
                                    }
                                });
                            }
                        }
                    });
                },
            }
        else
            calendar_setting = {
                locale: 'id',
                headerToolbar: {
                    end: ""
                },

                weekends: false,
                weekNumbers: true,
                showNonCurrentDates: false,

                selectable: false,
                editable: false,

                events: initial_event,
            }

        // ajax function
        var table_product_click_callback = function(event) {
            $('#modal-show-bom').modal('show');
            $('#table-bom tbody').empty();
            let id_event = this.dataset.id;
            $.ajax({
                url: "/ppic/get_versi_bom",
                method: "GET",
                data: {
                    id: id_event,
                },
                success: function(response) {
                    console.log(response)
                    $('#bom-version-input').attr('data-detail_produk_id', response[0].detail_produk_id);
                    $('#bom-version-input').empty();
                    $('#bom-version-input').append(`<option value=""></option>`);
                    response.forEach(element => {
                        $('#bom-version-input').append(`<option value="` + element.versi + `">` +
                            element.versi + `</option>`);
                    });
                },
                error: function(xhr, status, err) {
                    console.log("get bom: " + error);
                }
            });
        }
        var product_bom_version_change = function() {
            let versi = this.value;
            let detail_produk_id = this.dataset.detail_produk_id;
            $('#product-quantity').next("span").html("");
            $.ajax({
                url: `/ppic/get_item_bom`,
                method: "GET",
                data: {
                    count: true,
                    versi: versi,
                    detail_produk_id: detail_produk_id,
                },
                success: function(result) {
                    $('#product-quantity').next('span').html(`max: ${result}`);
                    $('#product-quantity').prop('disabled', false);
                },
                error: function(xhr, status, err) {
                    console.log(err);
                }
            })
        }

        // reset form
        function reset_form() {
            $('#product-form')[0].reset();
            $('#product-form span').html("");
            $('#product-bom-version').prop('disabled', true);
            $('#product-quantity').prop('disabled', true);
        }

        $(document).ready(function() {
            var Calendar = FullCalendar.Calendar;
            var calendarEl = document.getElementById('calendar');
            // choose view
            choose_view('calendar'); // default view
            $('.view').click(function() {
                choose_view(this.dataset.view);
            });
            // generate calendar view
            var calendar = new Calendar(calendarEl, calendar_setting);
            calendar.render();
            // generate table view
            for (i in event) {
                date_mark(event[i]);
            }
            // choose status info
            if (status == "penyusunan" || status == "permintaan" || status == "disetujui") {
                calendar.next();
                $('#acc-button').show();
                if (status == "permintaan" || status == "disetujui") {
                    console.log("why you not seen");
                    $('#acc-button').prop('disabled', true);
                }
            } else if (status == "selesai") {
                calendar.prev();
            }
            choose_status(status);
            // alert for ppic
            if (user.divisi_id == 24 && status == "disetujui") {
                bootbox.alert({
                    centerVertical: true,
                    message: "<p>Jadwal telah disetujui</p>" +
                        "<p>Silahkan kirim bppb untuk penyusunan bulan ini</p>",
                });
                $('#bppb-button').show();
            }
            // fill product form
            $('#choose_color > button').click(function(e) {
                var currColor = $(this).css('background-color');
                $('#product-submit').css({
                    'background-color': currColor,
                    'border-color': currColor
                })
            });
            $('#product-name').change(function() {
                let detail_produk_id = this.value;
                $('#product-bom-version').next("span").html("");
                $('#product-quantity').next("span").html("");
                $.ajax({
                    url: "/ppic/get_versi_bom",
                    method: "GET",
                    data: {
                        detail_produk_id: detail_produk_id,
                    },
                    success: function(result) {
                        if (result.length != 0) {
                            $('#product-bom-version').attr('data-detail_produk_id',
                                detail_produk_id);
                            $('#product-bom-version').empty();
                            $('#product-bom-version').append(`<option value=""></option>`);
                            result.forEach(element => {
                                $('#product-bom-version').append(
                                    `<option value="${element.versi}">${element.versi}</option>`
                                    );
                            });
                            $('#product-bom-version').prop('disabled', false);
                            $('#product-bom-version').change(product_bom_version_change);
                        } else {
                            $('#product-bom-version').next('span').html("Bom belum tersedia");
                            $('#product-bom-version').empty().prop('disabled', true);
                        }
                        $('#product-quantity').prop('disabled', true);
                    },
                    error: function(xhr, status, err) {
                        console.log("product-bom: " + err);
                    }
                })
            });
            // submit product form
            $('#product-submit').click(function() {
                let color = $(this).css('background-color');
                let data_saved = {
                    id_produk: $('#product-name').val(),
                    start: $('#date_start').val(),
                    end: $('#date_end').val(),
                    status: "penyusunan",
                    jumlah: $('#product-quantity').val(),
                    bom: $('#product-bom-version').val(),
                    color: color,
                }
                $.ajax({
                    url: "/ppic/schedule/create",
                    method: "POST",
                    data: data_saved,
                    success: function(result) {
                        calendar.addEvent({
                            id: result.id,
                            title: result.nama,
                            start: result.tanggal_mulai,
                            end: result.tanggal_selesai,
                            backgroundColor: color,
                            borderColor: color,
                        });
                        $('#modal-input-product').modal('hide');
                        $('#table-product').append(
                            `<tr data-id="${result.id}">
                            <td>${result.nama}</td>
                            <td>${result.jumlah_produksi}</td>
                        </tr>`
                        );
                        date_mark(result);
                        list_view_table_update(result);
                        reset_form();
                    },
                    error: function(xhr, status, err) {
                        alert("Form belum lengkap");
                    }
                });
            });
            // show bom from table product
            $('#table-product tr').on('click', table_product_click_callback);
            // select and reset bom table
            let initialize = true;
            let table;
            $("#bom-version-input").change(function() {
                let versi = this.value;
                let detail_produk_id = this.dataset.detail_produk_id;
                if (initialize) {
                    initialize = false;
                } else {
                    table.destroy();
                }
                table = $('#table-bom').DataTable({
                    paging: false,
                    info: false,
                    pageLength: -1,
                    ajax: {
                        url: '/ppic/get_item_bom/',
                        data: {
                            detail_produk_id: detail_produk_id,
                            versi: versi,
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama'
                        },
                        {
                            data: 'jumlah'
                        },
                        {
                            data: 'stok'
                        }
                    ]
                });
                $('#table-card').show();
            });
            // accept production schedule
            $('#acc-button').on('click', function() {
                bootbox.confirm({
                    centerVertical: true,
                    message: "Apakah Anda menyetujui jadwal produksi bulan ini?",
                    buttons: {
                        confirm: {
                            label: 'Setuju',
                        },
                        cancel: {
                            label: 'Tolak',
                        }
                    },
                    callback: function(result) {
                        if (result) {
                            console.log(event);
                            $.ajax({
                                url: "/ppic/change_status_event",
                                method: "POST",
                                data: {
                                    event: event,
                                    user: user,
                                    message: "Jadwal telah disetujui",
                                    status: "Disetujui",
                                },
                                success: function(response) {
                                    bootbox.alert({
                                        message: "jadwal telah disetujui",
                                        centerVertical: true,
                                    })
                                },
                                error: function() {
                                    alert("error");
                                }
                            });
                        } else {
                            bootbox.prompt({
                                title: "Keterangan atas penolakan jadwal produksi",
                                centerVertical: true,
                                callback: function(result) {
                                    $.ajax({
                                        url: "/ppic/notif",
                                        method: "POST",
                                        data: {
                                            status: "Ditolak",
                                            message: result,
                                            user: user,
                                        },
                                        error: function() {
                                            alert("ajax calling error");
                                        }
                                    })
                                }
                            })
                        }
                    }
                });
            });
            // show bppb form
            $("#bppb-button").click(function() {
                $("#create-bppb").modal('show');
            });
            // send bppb
            $(".send").click(function() {
                $(this).html(`<i class="fas fa-check"></i>`);
                $.ajax({
                    url: "/ppic/change_status_event",
                    data: {
                        id: this.dataset.id,
                        divisi_id: user.divisi_id,
                        status: "permintaan",
                    },
                    method: "POST",
                });
            });
            // send all bppb
            $("#send-all").click(function() {
                $(".send").trigger("click");
            });
            // action for bppb form
            // $(".send").on("click", function() {
            //     if ($(this).val() != "permintaan") {
            //         $.ajax({
            //             url: "/ppic/schedule/create",
            //             method: "POST",
            //             data: {
            //                 status_update: true,
            //                 status: "permintaan",
            //                 id: $(this).val(),
            //             },
            //             success: function(result) {
            //                 console.log(result);
            //             }
            //         });
            //     }
            // });

            // sent request for acc schedule
            $("#approval-button").click(function() {
                bootbox.alert({
                    message: `<i class="fas fa-check-circle" style="color: green;"></i> Permintaan telah dikirim`,
                    centerVertical: true,
                });
                // action for aproval button
                // let message = "Persetujuan jadwal produksi";
                // let status = "Penyusunan";
                // $.ajax({
                //     url: "/ppic/notif",
                //     method: "POST",
                //     data: {
                //         message: message,
                //         status: status,
                //         user: user,
                //     },
                //     success: function() {

                //     },
                //     error: function() {
                //         alert("error");
                //     }
                // })
                console.log(this);
            })
        });
    </script>


    <script>
        var count = $('#notif a#notif-item').length;

        $('#notif-header').text(count + ' notifications');
        $('span.badge').text(count);
        if (count != 0) {
            $('span.badge').show();
        }

        function insertAtIndex(e) {
            var content = `
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item" id="notif-item">
                <!-- Message Start -->
                <div class="media">
                    <img src="{{ asset('assets/image/user/index.png') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                    <div class="media-body">
                        <h3 class="dropdown-item-title">` +
                e.user.nama +
                `</h3>
                        <p class="text-sm">` + e.message + `</p>
                    </div>
                </div>
                <!-- Message End -->
            </a>
            `;

            $("#notif").prepend(content);
            choose_status(e.status);
        }

        Echo.private('message-events')
            .listen('RealTimeMessage', function(e) {
                global_e = e;
                console.log(e);
                insertAtIndex(e);


                count = $('#notif a#notif-item').length;

                $('#notif-header').text(count + ' notifications');
                $('span.badge').text(count);
                $('span.badge').show();
            });
    </script>
@stop
