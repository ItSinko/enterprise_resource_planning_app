@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <h1 class="m-0 text-dark">
        Dashboard</h1>
@stop

@section('adminlte_css')
    <style lang="scss">
        .card {
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
        }

        .modal-body {
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
                                <h4>Rekap Absen 1 Tahun</h4>
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
                                            <button type="button"
                                                class="btn btn-outline-info dropdown-toggle dropdown-toggle-split"
                                                data-display="static" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @for ($i = now()->month; $i >= 1; $i--)
                                                    <button
                                                        class="dropdown-item bulan_kunjungan @if ($i == now()->month) active @endif"
                                                        id="klinik{{ $i }}" value="{{ $i }}">
                                                        @if ($i == '1')
                                                            Januari
                                                        @elseif ($i == '2')
                                                            Februari
                                                        @elseif ($i == '3')
                                                            Maret
                                                        @elseif ($i == '4')
                                                            April
                                                        @elseif ($i == '5')
                                                            Mei
                                                        @elseif ($i == '6')
                                                            Juni
                                                        @elseif ($i == '7')
                                                            Juli
                                                        @elseif ($i == '8')
                                                            Agustus
                                                        @elseif ($i == '9')
                                                            September
                                                        @elseif ($i == '10')
                                                            Oktober
                                                        @elseif ($i == '11')
                                                            November
                                                        @else
                                                            Desember
                                                        @endif
                                                    </button>
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
                                                <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                                                    href="#custom-tabs-four-home" role="tab"
                                                    aria-controls="custom-tabs-four-home" aria-selected="true">Diagnosa</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                                    href="#custom-tabs-four-profile" role="tab"
                                                    aria-controls="custom-tabs-four-profile" aria-selected="false">Obat</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-four-tabContent">
                                            <div class="tab-pane fade show active" id="custom-tabs-four-home"
                                                role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                                <div id="justgage1"></div>
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-striped"
                                                        style="text-align:center;" id="karyawan_diagnosa_table">
                                                        <thead class="bg-secondary">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Diagnosa</th>
                                                                <th>Jumlah</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {{-- <tr>
                                                                <td>1</td>
                                                                <td>ISPA</td>
                                                                <td>50 pegawai</td>
                                                                <td><button type="button"
                                                                        class="btn btn-outline-primary btn-sm"
                                                                        id="karyawan_diagnosa_modal"><i
                                                                            class="fas fa-eye"></i> Detail</button></td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>Demam</td>
                                                                <td>42 pegawai</td>
                                                                <td><button type="button"
                                                                        class="btn btn-outline-primary btn-sm"
                                                                        id="karyawan_diagnosa_modal"><i
                                                                            class="fas fa-eye"></i> Detail</button></td>
                                                            </tr> --}}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                                                aria-labelledby="custom-tabs-four-profile-tab">
                                                <div id="justgage2"></div>
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-striped"
                                                        style="text-align:center;width:100%" id="karyawan_obat_table">
                                                        <thead class="bg-secondary">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Obat</th>
                                                                <th>Jumlah</th>
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
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <h4 class="col-6">Karyawan Sakit {{ now()->year }}</h4>
                                    <div class="col-6">
                                        {{-- <div class="btn-group float-right px-2">
                                            <button type="button" class="btn bg-olive dropdown-toggle dropdown-icon" data-toggle="dropdown" data-display="static" aria-expanded="false" id="tahun_sakit"> Tahun
                                            <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" role="menu"  aria-labelledby="tahun_sakit">
                                                <li role="presentation"><a class="dropdown-item tahun_sakit active" role="menuitem" tabindex="-1" value="{{ now()->year }}">{{ now()->year }}</a></li>
                                                <li role="presentation"><a class="dropdown-item tahun_sakit" role="menuitem" tabindex="-1" value="{{ now()->year - 1 }}">{{ now()->year - 1 }}</a></li>
                                                <li role="presentation"><a class="dropdown-item tahun_sakit" role="menuitem" tabindex="-1" value="{{ now()->year - 2 }}">{{ now()->year - 2 }}</a></li>
                                                <li role="presentation"><a class="dropdown-item tahun_sakit" role="menuitem" tabindex="-1" value="{{ now()->year - 3 }}">{{ now()->year - 3 }}</a></li>
                                                <li role="presentation"><a class="dropdown-item tahun_sakit" role="menuitem" tabindex="-1" value="{{ now()->year - 4 }}">{{ now()->year - 4 }}</a></li>
                                            </ul>
                                        </div> --}}
                                        <div class="btn-group mx-3 float-right">
                                            <button type="button" class="btn btn-outline-info">Pilih Bulan</button>
                                            <button type="button"
                                                class="btn btn-outline-info dropdown-toggle dropdown-toggle-split"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                data-display="static">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @for ($i = now()->month; $i >= 1; $i--)
                                                    <button
                                                        class="dropdown-item bulan_sakit @if ($i == now()->month) active @endif"
                                                        id="sakit{{ $i }}" value="{{ $i }}">
                                                        @if ($i == '1')
                                                            Januari
                                                        @elseif ($i == '2')
                                                            Februari
                                                        @elseif ($i == '3')
                                                            Maret
                                                        @elseif ($i == '4')
                                                            April
                                                        @elseif ($i == '5')
                                                            Mei
                                                        @elseif ($i == '6')
                                                            Juni
                                                        @elseif ($i == '7')
                                                            Juli
                                                        @elseif ($i == '8')
                                                            Agustus
                                                        @elseif ($i == '9')
                                                            September
                                                        @elseif ($i == '10')
                                                            Oktober
                                                        @elseif ($i == '11')
                                                            November
                                                        @else
                                                            Desember
                                                        @endif
                                                    </button>
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
                                    <table class="table table-hover table-striped" style="text-align:center;"
                                        id="karyawan_sakit_table">
                                        <thead class="bg-secondary">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Frekuensi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- <tr>
                                                <td>1</td>
                                                <td>Sumaiyah</td>
                                                <td>20</td>
                                                <td><button type="button" class="btn btn-outline-primary btn-sm"
                                                        id="karyawan_sakit_modal"><i class="fas fa-eye"></i>
                                                        Detail</button></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Alimun</td>
                                                <td>19</td>
                                                <td><button type="button" class="btn btn-outline-primary btn-sm"
                                                        id="karyawan_sakit_modal"><i class="fas fa-eye"></i>
                                                        Detail</button></td>
                                            </tr> --}}
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
    <div class="modal fade  bd-example-modal-xl" id="detailmodal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel">
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
            var bulan_select = {{ now()->month }};
            var bulan_sakit_select = {{ now()->month }};
            var obat_table = $('#karyawan_obat_table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/karyawan/sakit/obat/top/' + {{ now()->month }},
                    "dataType": "json",
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    "processData": true,
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                    data: 'DT_RowIndex',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'nama',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'jumlah',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'detail',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, ]
            });



            var kary_diagnosa_table = $('#karyawan_diagnosa_table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/karyawan/sakit/penyakit/top/' + {{ now()->month }},
                    "dataType": "json",
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    "processData": true,
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                    data: 'DT_RowIndex',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'diagnosa',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'jumlah',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'detail',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, ]
            });


            var karywan_sakit_table = $('#karyawan_sakit_table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/karyawan/sakit/person/top/' + {{ now()->month }},
                    "dataType": "json",
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    "processData": true,
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                    data: 'DT_RowIndex',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'nama',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'jumlah',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'detail',
                    className: 'align-center nowrap-text',
                    orderable: false,
                    searchable: false
                }, ]
            });
            $("#karyawan_diagnosa_table > tbody").on('click', '#karyawan_diagnosa_modal', function() {
                var rows = kary_diagnosa_table.rows($(this).parents('tr')).data();
                var bulan_sakit = bulan_select;
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
                        $.ajax({
                            type: "get",
                            url: "/karyawan/sakit/penyakit/top/detail/" + bulan_sakit +
                                "/2022/" + rows[0]['diagnosa'],
                            success: function(data) {
                                $('#bulan').html(data.header.bulan);
                                $('#diagnosa').html(data.header.nama);
                                $('#jumlah').html(data.header.jumlah);
                                diagnosa_table(data.data)
                            }
                        });
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





            function sakit_table(data) {
                var tablesakit = $('#table_sakit').DataTable({
                    data: data,
                    columns: [{
                            data: null
                        },

                        {
                            data: 'tgl_cek',
                            render: function(data, type, row) {
                                return moment(new Date(data).toString()).format(
                                    'DD-MM-YYYY');
                            }
                        },
                        {
                            data: 'nama_obat'
                        },
                        {
                            data: 'diagnosa'
                        },
                        {
                            data: 'tindakan'
                        },
                    ],
                    columnDefs: [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }, ],
                    order: [
                        [2, 'asc']
                    ],
                });

                tablesakit.on('order.dt search.dt', function() {
                    tablesakit.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }

            function diagnosa_table(data) {
                var diagnosatable = $('#table_diagnosa').DataTable({
                    data: data,
                    columns: [{
                            data: null
                        },

                        {
                            data: 'tgl_cek',
                            render: function(data, type, row) {
                                return moment(new Date(data).toString()).format(
                                    'DD-MM-YYYY');
                            }
                        },
                        {
                            data: 'nama'
                        },
                        {
                            data: 'nama_obat'
                        },
                    ],
                    columnDefs: [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }, ],
                    order: [
                        [2, 'asc']
                    ],
                });

                diagnosatable.on('order.dt search.dt', function() {
                    diagnosatable.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }

            $("#karyawan_obat_table > tbody").on('click', '#karyawan_obat_modal', function() {
                var rows = obat_table.rows($(this).parents('tr')).data();
                var bulan_sakit = bulan_select;
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
                        $.ajax({
                            type: "get",
                            url: "/karyawan/sakit/obat/top/detail/" + bulan_sakit +
                                "/2022/" + rows[0]['obat_id'],
                            success: function(data) {
                                $('#bulan').html(data.header.bulan);
                                $('#obat').html(data.header.nama);
                                $('#jumlah').html(data.header.jumlah);
                                obat_tables(data.data)

                            }
                        });
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

            function obat_tables(data) {
                var obattable = $('#table_obat').DataTable({
                    data: data,
                    columns: [{
                            data: null
                        },

                        {
                            data: 'tgl_cek',
                            render: function(data, type, row) {
                                return moment(new Date(data).toString()).format(
                                    'DD-MM-YYYY');
                            }
                        },
                        {
                            data: 'nama'
                        },
                        {
                            data: 'diagnosa'
                        },
                    ],
                    columnDefs: [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }, ],
                    order: [
                        [2, 'asc']
                    ],
                });

                obattable.on('order.dt search.dt', function() {
                    obattable.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }

            $("#karyawan_sakit_table > tbody").on('click', '#karyawan_sakit_modal', function() {
                var rows = karywan_sakit_table.rows($(this).parents('tr')).data();
                var bulan_sakit = bulan_sakit_select;

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
                        // $('#table_sakit').DataTable();
                        $.ajax({
                            type: "get",
                            url: "/karyawan/sakit/person/top/detail/" + bulan_sakit +
                                "/2022/" + rows[0]['karyawan_id'],
                            success: function(data) {

                                $('#bulan').html(data.header.bulan);
                                $('#nama').html(data.header.nama);
                                $('#jumlah').html(data.header.jumlah);
                                sakit_table(data.data)

                            }
                        });
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

            $('.bulan_kunjungan').click(function() {
                bulan_select = $(this).attr('value');
                var bulan_id = $(this).attr('value');
                $('.bulan_kunjungan').removeClass('active');
                $('#klinik' + bulan_id).addClass('active');
                $('#karyawan_diagnosa_table').DataTable().ajax.url('/karyawan/sakit/penyakit/top/' +
                    bulan_id).load();
                $('#karyawan_obat_table').DataTable().ajax.url('/karyawan/sakit/obat/top/' +
                    bulan_id).load();

            });

            $('.bulan_sakit').click(function() {
                bulan_sakit_select = $(this).attr('value');
                var bulan_id = $(this).attr('value');
                $('.bulan_sakit').removeClass('active');
                $('#sakit' + bulan_id).addClass('active');

                $('#karyawan_sakit_table').DataTable().ajax.url('/karyawan/sakit/person/top/' +
                    bulan_id).load();

            });

            $.ajax({
                url: "/karyawan/masuk/chart_absen",
                method: "GET",
                success: function(data) {

                    var ctx = document.getElementById("chart_absensi");
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                "Juli", "Agustus", "September", "Oktober", "November",
                                "Desember"
                            ],
                            datasets: [{
                                    label: "Ijin",
                                    backgroundColor: "#ffcc00",
                                    data: [data[1].ijin, data[2].ijin, data[3]
                                        .ijin, data[4].ijin, data[5].ijin, data[6]
                                        .ijin, data[7].ijin, data[8].ijin, data[9]
                                        .ijin, data[10].ijin, data[11].ijin, data[
                                            12].ijin
                                    ],
                                    borderColor: '#FFF6D4',
                                },
                                {
                                    label: "Cuti",
                                    backgroundColor: '#456600',
                                    data: [data[1].cuti, data[2].cuti, data[3]
                                        .cuti, data[4].cuti, data[5].cuti, data[6]
                                        .cuti, data[7].cuti, data[8].cuti, data[9]
                                        .cuti, data[10].cuti, data[11].cuti, data[
                                            12].cuti
                                    ],
                                    borderColor: 'rgba(69, 102, 0, 0.2)',
                                },
                                {
                                    label: "Sakit",
                                    backgroundColor: "#dc3545",
                                    data: [data[1].sakit, data[2].sakit, data[3]
                                        .sakit, data[4].sakit, data[5].sakit, data[
                                            6]
                                        .sakit, data[7].sakit, data[8].sakit, data[
                                            9]
                                        .sakit, data[10].sakit, data[11].sakit,
                                        data[
                                            12].sakit
                                    ],
                                    borderColor: '#FFDADA',
                                },
                            ]
                        },
                        options: {
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Grafik Absensi'
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
                                    data: [10, data.tahap_1, data.tahap_2,
                                        data
                                        .tahap_3
                                    ],
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
