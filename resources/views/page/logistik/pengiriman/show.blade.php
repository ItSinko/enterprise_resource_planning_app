@extends('adminlte.page')

@section('title', 'ERP')
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Pengiriman</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '15')
                        <li class="breadcrumb-item"><a href="{{ route('logistik.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Pengiriman</li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Pengiriman</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop
@section('adminlte_css')
    <style>
        .overflowtableekat {
            max-height:
                500px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
        }

        .overflowtablenonekat {
            max-height:
                300px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
        }

        li.list-group-item {
            border: 0 none;
        }

        .smtxt {
            font-size: 13px;
        }

        #showtable {
            text-align: center;
            white-space: nowrap;
        }

        .ok {
            color: green;
            font-weight: 600;
        }

        .nok {
            color: #dc3545;
            font-weight: 600;
        }

        .warning {
            color: #FFC700;
            font-weight: 600;
        }

        .align-right {
            text-align: right;
        }

        .align-center {
            text-align: center;
        }

        .margin {
            margin-bottom: 5px;
        }

        .filter {
            margin: 5px;
        }

        .hide {
            display: none !important;
        }

        .bgcolor {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .fa-search:hover {
            color: #ADD8E6;
        }

        .fa-search:active {
            color: #808080;
        }

        .nowrap-text {
            white-space: nowrap;
        }

        .minimizechar {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 25ch;
        }

        .dropdown-toggle:hover {
            color: #4682B4;
        }

        .dropdown-toggle:active {
            color: #C0C0C0;
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
        }

        @media screen and (max-width: 1439px) {
            section {
                font-size: 12px;
            }

            .dropdown-item {
                font-size: 12px;
            }
        }
    </style>
@stop

@section('content_header')
    <h1 class="m-0 text-dark">Pengiriman</h1>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-proses_kirim-tab" data-toggle="pill"
                                        href="#pills-proses_kirim" role="tab" aria-controls="pills-proses_kirim"
                                        aria-selected="true">Proses Kirim</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-selesai_kirim-tab" data-toggle="pill"
                                        href="#pills-selesai_kirim" role="tab" aria-controls="pills-selesai_kirim"
                                        aria-selected="false">Selesai Kirim</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-proses_kirim" role="tabpanel"
                                    aria-labelledby="pills-proses_kirim-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="float-right filter">
                                                <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                                <form id="filter_logistik">
                                                    <div class="dropdown-menu">
                                                        <div class="px-3 py-3">
                                                            <div class="form-group">
                                                                <label for="pengiriman">Pengiriman</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="ekspedisi" name="pengiriman[]"
                                                                        id="pengiriman1" />
                                                                    <label class="form-check-label" for="pengiriman1">
                                                                        Ekspedisi
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="nonekspedisi" name="pengiriman[]"
                                                                        id="pengiriman2" />
                                                                    <label class="form-check-label" for="pengiriman2">
                                                                        Non Ekspedisi
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="provinsi">Provinsi</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="2" name="provinsi[]" id="provinsi1" />
                                                                    <label class="form-check-label" for="provinsi1">
                                                                        Jawa
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="1" name="provinsi[]" id="provinsi2" />
                                                                    <label class="form-check-label" for="provinsi2">
                                                                        Luar Jawa
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="jenis_penjualan">Jenis Penjualan</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="ekat" name="jenis_penjualan[]"
                                                                        id="jenis_penjualan1" />
                                                                    <label class="form-check-label"
                                                                        for="jenis_penjualan1">
                                                                        Ekatalog
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="spa" name="jenis_penjualan[]"
                                                                        id="jenis_penjualan2" />
                                                                    <label class="form-check-label"
                                                                        for="jenis_penjualan2">
                                                                        SPA
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="spb" name="jenis_penjualan[]"
                                                                        id="jenis_penjualan3" />
                                                                    <label class="form-check-label"
                                                                        for="jenis_penjualan3">
                                                                        SPB
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <span class="float-right">
                                                                    <button class="btn btn-primary" id="filter_logistik"
                                                                        type="submit">
                                                                        Cari
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </span>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table" id="showtable" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>No SO</th>
                                                            <th>No SJ</th>
                                                            <th>Ekspedisi / Pengirim</th>
                                                            <th>Tanggal Kirim</th>
                                                            <th>Nama Customer</th>
                                                            <th>Provinsi</th>
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
                                <div class="tab-pane fade show" id="pills-selesai_kirim" role="tabpanel"
                                    aria-labelledby="pills-selesai_kirim-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="float-right filter">
                                                <button class="btn btn-outline-secondary" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                                <form id="filter_riwayat">
                                                    <div class="dropdown-menu">
                                                        <div class="px-3 py-3">
                                                            <div class="form-group">
                                                                <label for="pengiriman_riwayat">Pengiriman</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="ekspedisi" name="pengiriman_riwayat[]"
                                                                        id="pengiriman_riwayat1" />
                                                                    <label class="form-check-label"
                                                                        for="pengiriman_riwayat1">
                                                                        Ekspedisi
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="nonekspedisi" name="pengiriman_riwayat[]"
                                                                        id="pengiriman_riwayat2" />
                                                                    <label class="form-check-label"
                                                                        for="pengiriman_riwayat2">
                                                                        Non Ekspedisi
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="provinsi_riwayat">Provinsi</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="2" name="provinsi_riwayat[]"
                                                                        id="provinsi_riwayat1" />
                                                                    <label class="form-check-label"
                                                                        for="provinsi_riwayat1">
                                                                        Jawa
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="1" name="provinsi_riwayat[]"
                                                                        id="provinsi_riwayat2" />
                                                                    <label class="form-check-label"
                                                                        for="provinsi_riwayat2">
                                                                        Luar Jawa
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="jenis_penjualan_riwayat">Jenis
                                                                    Penjualan</label>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="ekat" name="jenis_penjualan_riwayat[]"
                                                                        id="jenis_penjualan_riwayat1" />
                                                                    <label class="form-check-label"
                                                                        for="jenis_penjualan_riwayat1">
                                                                        Ekatalog
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="spa" name="jenis_penjualan_riwayat[]"
                                                                        id="jenis_penjualan_riwayat2" />
                                                                    <label class="form-check-label"
                                                                        for="jenis_penjualan_riwayat2">
                                                                        SPA
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="spb" name="jenis_penjualan_riwayat[]"
                                                                        id="jenis_penjualan_riwayat3" />
                                                                    <label class="form-check-label"
                                                                        for="jenis_penjualan_riwayat3">
                                                                        SPB
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <span class="float-right">
                                                                    <button class="btn btn-primary" id="filter_riwayat"
                                                                        type="submit">
                                                                        Cari
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table" id="riwayattable" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>No SO</th>
                                                            <th>No SJ</th>
                                                            <th>Ekspedisi</th>
                                                            <th>No Resi</th>
                                                            <th>Tanggal Kirim</th>
                                                            <th>Nama Customer</th>
                                                            <th>Provinsi</th>
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
                    </div>
                </div>
                <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header bg-warning-50">
                                <h5 class="modal-title">Ubah Pengiriman</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="edit">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('adminlte_js')
    <script>
        $(function() {
            $(document).on('click', '#pills-selesai_kirim-tab', function() {
                selesai_kirim();
            });

            function selesai_kirim() {
                $('#riwayattable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/logistik/pengiriman/riwayat/data/semua/semua/semua',
                        'dataType': 'json',
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
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'so'
                        },
                        {
                            data: 'sj',

                        },
                        {
                            data: 'ekspedisi',

                        },
                        {
                            data: 'no_resi',

                        }, {
                            data: 'tgl_kirim',

                        }, {
                            data: 'nama_customer',

                        }, {
                            data: 'provinsi',

                        }, {
                            data: 'status',

                        }, {
                            data: 'button',

                        }
                    ]
                });
            }

            $('#filter_riwayat').submit(function() {
                var values_pengiriman = [];
                $('input[name="pengiriman_riwayat[]"]:checked').each(function() {
                    values_pengiriman.push($(this).val());
                });

                var values_provinsi = [];
                $('input[name="provinsi_riwayat[]"]:checked').each(function() {
                    values_provinsi.push($(this).val());
                });

                var values_jenis_penjualan = [];
                $('input[name="jenis_penjualan_riwayat[]"]:checked').each(function() {
                    values_jenis_penjualan.push($(this).val());
                });

                if (values_pengiriman != 0) {
                    var x = values_pengiriman;
                } else {
                    var x = ['semua'];
                }

                if (values_provinsi != 0) {
                    var y = values_provinsi;
                } else {
                    var y = ['semua'];
                }

                if (values_jenis_penjualan != 0) {
                    var z = values_jenis_penjualan;
                } else {
                    var z = ['semua'];
                }

                $('#riwayattable').DataTable().ajax.url('/api/logistik/pengiriman/riwayat/data/' + x + '/' +
                    y + '/' + z).load();
                return false;
            });

            $(document).on('submit', '#form-pengiriman-update', function(e) {
                e.preventDefault();
                var action = $(this).attr('action');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: action,
                    data: $('#form-pengiriman-update').serialize(),
                    success: function(response) {
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan ubah data Pengiriman',
                                'success'
                            );
                            $("#editmodal").modal('hide');
                            $('#riwayattable').DataTable().ajax.reload();
                            $('#showtable').DataTable().ajax.reload();
                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan ubah data Pengiriman',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        alert($('#form-customer-update').serialize());
                    }
                });
                return false;
            });
            var idonclick = "";
            $(document).on('click', '.editmodal', function() {
                var href = $(this).attr('data-href');
                var id = $(this).data('id');
                idonclick = "";
                idonclick = id;
                var logistik_id = $(this).attr('data-id');
                var provinsi = $(this).attr('data-provinsi');
                var jenis = $(this).data('attr');
                $.ajax({
                    url: href,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#editmodal').modal("show");
                        $('#edit').html(result).show();
                        ekspedisi_select(provinsi);
                        barang_detail(logistik_id, jenis);
                        // $("#editform").attr("action", href);
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

            $(document).on('click', '#ubahstatus', function() {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                $.ajax({
                    type: "GET",
                    url: "/api/logistik/pengiriman/status/update/" + id + "/" + status,
                    success: function(response) {
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan ubah data Pengiriman',
                                'success'
                            );
                            $("#editmodal").modal('hide');
                            $('#showtable').DataTable().ajax.reload();
                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan ubah data Pengiriman',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        alert($('#form-customer-update').serialize());
                    }
                });
            });

            $(document).on('change keyup', '#no_invoice', function(event) {
                if ($(this).val() != "") {
                    var val = $(this).val();
                    $.ajax({
                        type: "POST",
                        url: '/api/logistik/cek/no_sj/' + idonclick + '/' + val + '/0',
                        dataType: 'json',
                        success: function(data) {
                            if (data > 0) {
                                $('#no_invoice').addClass('is-invalid');
                                $('#msgno_invoice').text("No Surat Jalan sudah terpakai");
                                $('#btnsimpan').attr('disabled', true);
                            } else {
                                $('#no_invoice').removeClass('is-invalid');
                                $('#msgno_invoice').text("");
                                if ($('input[type="radio"][name="pengiriman"]:checked').val() ==
                                    "ekspedisi") {
                                    if ($('.ekspedisi_id').val() != "" && $('.ekspedisi_id')
                                        .val() != null) {
                                        $('#btnsimpan').removeAttr('disabled');
                                    } else {
                                        $('#btnsimpan').attr('disabled', true);
                                    }
                                } else {
                                    if ($('#nama_pengirim').val() != "") {
                                        $('#btnsimpan').removeAttr('disabled');
                                    } else {
                                        $('#btnsimpan').attr('disabled', true);
                                    }
                                }

                            }
                        },
                        error: function() {
                            alert('Error occured');
                        }
                    });
                } else if ($(this).val() == "") {
                    $('#no_invoice').addClass('is-invalid');
                    $('#msgnoinvoice').text("No Surat Jalan tidak boleh kosong");
                    $('#btnsimpan').attr('disabled', true);
                }
            });

            $(document).on('change', 'input[type="radio"][name="pengiriman"]', function(event) {
                $('#ekspedisi_id').removeClass('is-invalid');
                $('#msgekspedisi_id').text("");
                $('#nama_pengirim').removeClass('is-invalid');
                $('#msgnama_pengirim').text("");
                $('#no_polisi').removeClass('is-invalid');
                $('#msgno_polisi').text("");
                $('#btnsimpan').attr('disabled', true);
                if ($('input[type="radio"][name="pengiriman"]:checked').val() == "ekspedisi") {
                    $('#ekspedisi').removeClass('hide');
                    $('#nonekspedisi').addClass('hide');
                    $('#no_polisi').val("");
                    $('#nama_pengirim').val("");
                    if ($('.ekspedisi_id').val() != "" && $('.ekspedisi_id').val() != null && ($(
                            '#no_invoice').val() != "" && !$('#no_invoice').hasClass('is-invalid'))) {
                        $('#btnsimpan').removeAttr('disabled');
                    } else {
                        $('#btnsimpan').attr('disabled', true);
                    }
                } else if ($('input[type="radio"][name="pengiriman"]:checked').val() == "nonekspedisi") {
                    $('#ekspedisi').addClass('hide');
                    $('#nonekspedisi').removeClass('hide');
                    $('.ekspedisi_id').val(null).trigger('change');
                    if ($('#nama_pengirim').val() != "" && ($('#no_invoice').val() != "" && !$(
                            '#no_invoice').hasClass('is-invalid'))) {
                        $('#btnsimpan').removeAttr('disabled');
                    } else {
                        $('#btnsimpan').attr('disabled', true);
                    }
                }
            });

            $(document).on('change keyup', '#no_resi', function(event) {
                if ($(this).val() != "") {
                    // var value = $(this).val();
                    // var val = value.replaceAll("/", "_")
                    // $.ajax({
                    //     type: 'POST',
                    //     dataType: 'json',
                    //     url: '/api/logistik/cek/no_resi/' + val,
                    //     success: function(data) {
                    //         if (data > 0) {
                    //             $('#no_resi').addClass('is-invalid');
                    //             $('#msgno_resi').text("No Resi sudah terpakai");
                    //             $('#btnsimpan').attr('disabled', true);
                    //         } else {
                    //             $('#no_resi').removeClass('is-invalid');
                    //             $('#msgno_resi').text("");
                    //             $('#btnsimpan').removeAttr('disabled');
                    //         }
                    //     },
                    //     error: function(data) {
                    //         $('#no_resi').addClass('is-invalid');
                    //         $('#msgno_resi').text("No Resi harus diisi");
                    //         $('#btnsimpan').attr('disabled', true);
                    //     }
                    // });
                    $('#no_resi').removeClass('is-invalid');
                    $('#msgno_resi').text("");
                    $('#btnsimpan').removeAttr('disabled');
                } else if ($(this).val() == "") {
                    $('#no_resi').addClass('is-invalid');
                    $('#msgno_resi').text("No Resi harus diisi");
                    $('#btnsimpan').attr('disabled', true);
                }
            });

            $(document).on('change keyup', '#nama_pengirim', function(event) {
                if ($(this).val() != "") {
                    $('#nama_pengirim').removeClass('is-invalid');
                    $('#msgnama_pengirim').text("");
                    if (($('#no_invoice').val() != "" && !$('#no_invoice').hasClass('is-invalid')) && $(
                            '#nama_pengirim').val() != "") {
                        $('#btnsimpan').removeAttr('disabled');
                    } else {
                        $('#btnsimpan').attr('disabled', true);
                    }
                } else if ($(this).val() == "") {
                    $('#nama_pengirim').addClass('is-invalid');
                    $('#msgnama_pengirim').text("Nama Pengirim harus diisi");
                    $('#btnsimpan').attr('disabled', true);
                }
            });

            // $(document).on('change keyup', '#no_polisi', function(event) {
            //     if ($(this).val() != "") {
            //         $('#no_polisi').removeClass('is-invalid');
            //         $('#msgno_polisi').text("");
            //         if (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "") {
            //             $('#btnsimpan').removeAttr('disabled');
            //         } else {
            //             $('#btnsimpan').attr('disabled', true);
            //         }
            //     } else if ($(this).val() == "") {
            //         $('#no_polisi').addClass('is-invalid');
            //         $('#msgno_polisi').text("No Kendaraan harus diisi");
            //         $('#btnsimpan').attr('disabled', true);
            //     }
            // });

            $(document).on('change keyup', '.ekspedisi_id', function(event) {
                if ($(this).val() != "") {
                    $('#ekspedisi_id').removeClass('is-invalid');
                    $('#msgekspedisi_id').text("");
                    if (($('#no_invoice').val() != "" && !$('#no_invoice').hasClass('is-invalid')) && $(
                            '.ekspedisi_id').val() != "") {
                        $('#btnsimpan').removeAttr('disabled');
                    } else {
                        $('#btnsimpan').attr('disabled', true);
                    }
                } else if ($(this).val() == "") {
                    $('#ekspedisi_id').addClass('is-invalid');
                    $('#msgekspedisi_id').text("Ekspedisi harus dipilih");
                    $('#btnsimpan').attr('disabled', true);
                }
            });

            function ekspedisi_select(id) {
                $('.ekspedisi_id').select2({
                    placeholder: "Pilih Ekspedisi",
                    allowClear: true,
                    ajax: {
                        minimumResultsForSearch: 20,
                        placeholder: "Pilih Ekspedisi",
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/logistik/ekspedisi/select/' + id,
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nama
                                    };
                                })
                            };
                        },
                    }
                });
            }

            function barang_detail(id, jenis) {
                $('#barangtable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/logistik/pengiriman/data/' + id + '/' + jenis,
                        'dataType': 'json',
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
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_produk'
                        },
                        {
                            data: 'jumlah',
                        },
                    ]
                });
            }
            var showtable = $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/logistik/pengiriman/data/semua/semua/semua',
                    'dataType': 'json',
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
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'so'
                    },
                    {
                        data: 'sj',

                    },
                    {
                        data: 'ekspedisi',

                    },
                    {
                        data: 'tgl_kirim',

                    }, {
                        data: 'nama_customer',

                    }, {
                        data: 'provinsi',

                    }, {
                        data: 'status',

                    }, {
                        data: 'button',

                    }
                ]
            });

            $('#filter_logistik').submit(function() {
                var values_pengiriman = [];
                $('input[name="pengiriman[]"]:checked').each(function() {
                    values_pengiriman.push($(this).val());
                });

                var values_provinsi = [];
                $('input[name="provinsi[]"]:checked').each(function() {
                    values_provinsi.push($(this).val());
                });

                var values_jenis_penjualan = [];
                $('input[name="jenis_penjualan[]"]:checked').each(function() {
                    values_jenis_penjualan.push($(this).val());
                });

                if (values_pengiriman != 0) {
                    var x = values_pengiriman;
                } else {
                    var x = ['semua'];
                }

                if (values_provinsi != 0) {
                    var y = values_provinsi;
                } else {
                    var y = ['semua'];
                }

                if (values_jenis_penjualan != 0) {
                    var z = values_jenis_penjualan;
                } else {
                    var z = ['semua'];
                }

                $('#showtable').DataTable().ajax.url('/logistik/pengiriman/data/' + x + '/' + y + '/' + z)
                    .load();
                return false;
            });

        });
    </script>
@stop
