@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Sales Order</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '23')
                        <li class="breadcrumb-item"><a href="{{ route('qc.dashboard') }}">Beranda</a></li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ route('qc.so.show') }}">Sales Order QC</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
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

        .list-group-item {
            border: 0 none;
        }

        .align-right {
            float: right;
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

        /* @media screen and (min-width: 1440px) {

                section {
                    font-size: 14px;
                }

                #detailmodal {
                    font-size: 14px;
                }

                .btn {
                    font-size: 14px;
                }
            } */

        @media screen and (min-width: 993px) {

            body {
                font-size: 14px;
            }

            h4 {
                font-size: 24px;
            }

            #detailmodal {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .cust {
                max-width: 40%;
            }
        }

        @media screen and (max-width: 992px) {

            body {
                font-size: 12px;
            }

            h4 {
                font-size: 22px;
            }

            #detailmodal {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .collapsable {
                display: none;
            }
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
                            <h4>Info Penjualan SPA</h4>
                            <div class="row">
                                <div class="col-lg-11 col-md-12">
                                    @foreach ($data as $d)
                                        <div class="row d-flex justify-content-between">
                                            <div class="p-2 cust">
                                                <div class="margin">
                                                    <div><small class="text-muted">Customer</small></div>
                                                </div>
                                                <div class="margin">
                                                    <b id="distributor">{{ $d->customer->nama }}</b>
                                                </div>
                                                <div class="margin">
                                                    {{ $d->customer->alamat }}
                                                </div>
                                                <div class="margin">
                                                    {{ $d->customer->provinsi->nama }}
                                                </div>
                                                <div class="margin">
                                                    {{ $d->customer->telp }}
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No SO</small></div>
                                                    <div><b id="no_so">{{ $d->pesanan->so }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Status</small></div>
                                                    <div id="status" class="align-center">{!! $status !!}</div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No PO</small></div>
                                                    <div><b id="no_so">{{ $d->pesanan->no_po }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal PO</small></div>
                                                    <div><b id="no_so">
                                                            @if ($d->pesanan->tgl_po == '0000-00-00' || empty($d->pesanan->tgl_po))
                                                                -
                                                            @else
                                                                {{ $d->pesanan->tgl_po }}
                                                            @endif
                                                        </b></div>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($d->ket != '')
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Catatan: </strong>{{ $d->ket }}
                </div>
            @endif
            <div class="row">
                <div class="col-7">
                    <div class="card">
                        <div class="card-body">
                            @if (Auth::user()->Karyawan->divisi_id == '23')
                                <div class="row" style="margin-bottom: 5px">
                                    <div class="col-12">
                                        <span class="float-left filter">
                                            <a id="exportbutton"
                                                href="{{ route('qc.so.export', ['id' => $d->pesanan->id]) }}"><button
                                                    class="btn btn-success">
                                                    <i class="far fa-file-excel" id="load"></i> Export
                                                </button>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table" style="text-align:center;" id="showtable">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">No</th>
                                                    <th rowspan="2">Nama Produk</th>
                                                    <th rowspan="2">Jumlah</th>
                                                    <th colspan="2" class="collapsable">Hasil</th>
                                                    <th rowspan="2">Aksi</th>
                                                </tr>
                                                <tr>
                                                    <th><i class="fas fa-check ok"></i></th>
                                                    <th><i class="fas fa-times nok"></i></th>
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
                <div class="col-5 hide" id="noseridetail">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <span class="float-right filter hide" id="filter_form">
                                        <button class="btn btn-outline-secondary dropdown-toggle " type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                            id="filterpenjualan">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="filterpenjualan">
                                            <form class="px-4" style="white-space:nowrap;">
                                                <div class="dropdown-header">
                                                    Status
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="radio" class="form-check-input" id="dropdownStatus1"
                                                            value="semua" name='filter' />
                                                        <label class="form-check-label" for="dropdownStatus1">
                                                            Semua
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="radio" class="form-check-input"
                                                            id="dropdownStatus2" value="belum" name='filter' />
                                                        <label class="form-check-label" for="dropdownStatus2">
                                                            Belum di Uji
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="radio" class="form-check-input"
                                                            id="dropdownStatus3" value="sudah" name='filter' />
                                                        <label class="form-check-label" for="dropdownStatus3">
                                                            Sudah di Uji
                                                        </label>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </span>
                                    @if (Auth::user()->Karyawan->divisi_id == '23')
                                        <span class="float-right filter">
                                            <a data-toggle="modal" data-target="#editmodal" class="editmodal"
                                                data-attr="" data-id="">
                                                <button class="btn btn-warning" id="cekbrg" disabled="true">
                                                    <i class="fas fa-pencil-alt"></i> Cek Barang
                                                </button>
                                            </a>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="row hide" id="produk_detail">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table" style="text-align:center; width:100%" id="noseritable">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="form-check cek_header">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="check_all" id="check_all" name="check_all" />
                                                            <label class="form-check-label" for="check_all">
                                                            </label>
                                                        </div>
                                                    </th>
                                                    <th>No Seri</th>
                                                    <th>Tanggal Uji</th>
                                                    <th>Hasil</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row hide" id="part_detail">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table" style="text-align:center; width:100%" id="parttable">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">Tanggal Uji</th>
                                                    <th colspan="2">Jumlah</th>
                                                </tr>
                                                <tr>
                                                    <th><i class="fas fa-check-circle" style="color:green;"></i></th>
                                                    <th><i class="fas fa-times-circle" style="color:red;"></i></th>
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
                <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header bg-warning">
                                <h4 class="modal-title">Edit</h4>
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
            var divisi = '{{ Auth::user()->Karyawan->divisi_id }}';
            var showtable = $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                autowidth: true,
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/qc/so/detail/' + '{{ $id }}',
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
                }, {
                    data: 'nama_produk',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'jumlah',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'jumlah_ok',
                    className: 'nowrap-text align-center collapsable',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'jumlah_nok',
                    className: 'nowrap-text align-center collapsable',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'button',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }]

            });
            var datajenis = "";
            var dataid = "";

            $('#showtable').on('click', '.noserishow', function() {
                idpesanan = '{{ $id }}';
                dataid = $(this).attr('data-id');
                var datacount = $(this).attr('data-count');
                datajenis = $(this).attr('data-jenis');
                $(".cek_header").css("display", "block");
                $('input[type=radio][name=filter]').prop('checked', false);
                $('.nosericheck').prop('checked', false);
                $('#cekbrg').prop('disabled', true);
                $('input[name ="check_all"]').prop('checked', false);
                if (datajenis == "produk") {

                    $('#filter_form').removeClass('hide')
                    $('#produk_detail').removeClass('hide');
                    $('#part_detail').addClass('hide');
                    $('#noseritable').DataTable().ajax.url('/api/qc/so/seri/belum/' + dataid + '/' +
                        '{{ $id }}').load();
                    if (datacount == 0) {

                        // $('.sericheckbox').addClass("hide");
                        $('#noseritable').DataTable().column(0).visible(false);
                    } else {
                        // $('.sericheckbox').removeClass("hide");
                        if (divisi == '23') {
                            $('#noseritable').DataTable().column(0).visible(true);
                        } else {
                            $('#noseritable').DataTable().column(0).visible(false);
                        }
                    }

                } else {
                    $('#filter_form').addClass('hide')
                    $('#produk_detail').addClass('hide');
                    $('#part_detail').removeClass('hide');
                    listcekpart(dataid);
                    if (datacount == 0) {
                        $('#cekbrg').prop('disabled', true);
                    } else {
                        $('#cekbrg').prop('disabled', false);
                    }
                }

                $('#showtable').find('tr').removeClass('bgcolor');
                $(this).closest('tr').addClass('bgcolor');
                $('#noseridetail').removeClass('hide');
            });

            $(document).on('submit', '#form-pengujian-update', function(e) {
                $('#btnsimpan').attr('disabled', true);
                // var showLoading = swal.fire({
                //     title: 'Sedang Proses',
                //     html: 'Loading...',
                //     allowOutsideClick: false,
                //     showConfirmButton: false,
                //     willOpen: () => {Swal.showLoading()}
                // });
                if (datajenis == "produk") {
                    e.preventDefault();
                    var no_seri = $('#listnoseri').DataTable().$('tr').find('input[name="noseri_id[]"]')
                        .serializeArray();
                    var data = [];

                    var tanggal_uji = $('input[type="date"][name="tanggal_uji"]').val();
                    var cek = $('input[type="radio"][name="cek"]').val();

                    $.each(no_seri, function() {
                        data.push(this.value);
                    });

                    var action = $(this).attr('action');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "PUT",
                        url: action,
                        data: {
                            tanggal_uji: tanggal_uji,
                            cek: cek,
                            noseri_id: data,
                        },
                        dataType: 'JSON',
                        beforeSend: function() {
                            swal.fire({
                                title: 'Sedang Proses',
                                html: 'Loading...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading()
                                }
                            })
                        },
                        success: function(response) {
                            console.log(response);
                            if (response['data'] == "success") {
                                swal.fire(
                                    'Berhasil',
                                    'Berhasil melakukan Penambahan Data Pengujian',
                                    'success'
                                );
                                $("#editmodal").modal('hide');
                                $('#noseritable').DataTable().ajax.reload();
                                $('#showtable').DataTable().ajax.reload();
                                location.reload();

                            } else if (response['data'] == "error") {
                                swal.fire(
                                    'Gagal',
                                    'Gagal melakukan Penambahan Data Pengujian',
                                    'error'
                                );
                            } else {
                                console.log(response['data']);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr);
                        }
                    });
                } else if (datajenis == "part") {
                    e.preventDefault();
                    var action = $(this).attr('action');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: action,
                        data: $('#form-pengujian-update').serialize(),
                        beforeSend: function() {
                            swal.fire({
                                title: 'Sedang Proses',
                                html: 'Loading...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading()
                                }
                            })
                        },
                        success: function(response) {
                            console.log(response);
                            if (response['data'] == "success") {
                                swal.fire(
                                    'Berhasil',
                                    'Berhasil melakukan Penambahan Data Pengujian',
                                    'success'
                                );
                                $("#editmodal").modal('hide');
                                // $('#noseritable').DataTable().ajax.reload();
                                // $('#parttable').DataTable().ajax.reload();
                                // $('#showtable').DataTable().ajax.reload();

                                location.reload();
                            } else if (response['data'] == "error") {
                                swal.fire(
                                    'Gagal',
                                    'Gagal melakukan Penambahan Data Pengujian',
                                    'error'
                                );
                            } else {
                                console.log(response['data']);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert($('#form-customer-update').serialize());
                        }
                    });
                }
                return false;
            });

            var noseritable = $('#noseritable').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                autowidth: true,
                ajax: {
                    'type': 'post',
                    'datatype': 'JSON',
                    'url': '/api/qc/so/seri/semua/0/0',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                    data: 'checkbox',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false,
                    visible: divisi == '23' ? true : false
                }, {
                    data: 'seri',
                    className: 'nowrap-text align-center',
                    orderable: true,
                    searchable: true
                }, {
                    data: 'tgl_uji',
                    className: 'nowrap-text align-center collapsable',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'status',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }]
            });

            function listnoseri(seri_id, produk_id, pesanan_id) {
                $('#listnoseri').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    autowidth: true,
                    ajax: {
                        'type': 'post',
                        'datatype': 'JSON',
                        'url': '/api/qc/so/seri/select/' + seri_id + '/' + produk_id + '/' + pesanan_id,
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
                    }, {
                        data: 'seri',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'noseri_id',
                        className: 'nowrap-text align-center hide',
                        orderable: false,
                        searchable: false,
                    }, {
                        data: 'detail_pesanan_produk_id',
                        className: 'nowrap-text align-center hide',
                        orderable: false,
                        searchable: false,
                    }, ]
                });
            }

            function listcekpart(part_id) {
                $('#parttable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    ajax: {
                        'type': 'POST',
                        'datatype': 'JSON',
                        'url': '/api/qc/so/part/' + part_id,
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                        data: 'tanggal_uji',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'jumlah_ok',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false,
                    }, {
                        data: 'jumlah_nok',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false,
                    }, ]
                });
            }

            var checkedAry = [];
            $('#noseritable').on('click', 'input[name="check_all"]', function() {
                var rows = $('#noseritable').DataTable().rows().nodes();
                // $('.nosericheck').prop('checked', this.checked);
                if ($('input[name="check_all"]:checked').length > 0) {
                    $('#cekbrg').prop('disabled', false);
                    $('.nosericheck', rows).prop('checked', true);
                    checkedAry = [];
                    $.each($(".nosericheck:checked", rows), function() {
                        checkedAry.push($(this).closest('tr').find('.nosericheck').attr('data-id'));
                    });
                    $('#btnedit').removeAttr('disabled');
                } else if ($('input[name="check_all"]:checked').length <= 0) {
                    $('.nosericheck', rows).prop('checked', false);
                    $('#cekbrg').prop('disabled', true);
                }
            });

            $('#noseritable').on('click', '.nosericheck', function() {
                var rows = $('#noseritable').DataTable().rows().nodes();
                $('#check_all').prop('checked', false);
                if ($('.nosericheck:checked', rows).length > 0) {
                    $('#cekbrg').prop('disabled', false);
                    checkedAry = [];
                    $.each($(".nosericheck:checked", rows), function() {
                        checkedAry.push($(this).closest('tr').find('.nosericheck').attr('data-id'));
                    });
                } else if ($('.nosericheck:checked', rows).length <= 0) {
                    $('#cekbrg').prop('disabled', true);
                }
            });

            function max_date() {
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                today = yyyy + '-' + mm + '-' + dd;
                //console.log(today);
                $("#tanggal_uji").attr("max", today);
            }

            $(document).on('click', '.editmodal', function(event) {
                var href = "";
                event.preventDefault();
                if (datajenis == "produk") {
                    data = $(".nosericheck").data().value;
                } else {
                    data = dataid
                }
                console.log(checkedAry);
                console.log(data);
                console.log(idpesanan);
                if (datajenis == "produk") {
                    href = "/qc/so/edit/" + datajenis + '/' + data + "/" + '{{ $id }}';
                } else {
                    href = "/qc/so/edit/" + datajenis + '/' + dataid + "/" + '{{ $id }}';
                }

                $.ajax({
                    url: href,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {

                        $('#editmodal').modal("show");
                        $('#edit').html(result).show();
                        if (datajenis == "produk") {
                            listnoseri(checkedAry, data, '{{ $id }}');
                        }
                        max_date();
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

            $(document).on('change', 'input[type="radio"][name="cek"]', function(event) {
                if ($(this).val() != "") {
                    if ($('#tanggal_uji').val() != "") {
                        $('#btnsimpan').removeAttr('disabled');
                    } else {
                        $('#btnsimpan').attr('disabled', true);
                    }
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            });

            $(document).on('keyup change', 'input[type="date"][name="tanggal_uji"]', function(event) {
                if ($(this).val() != "") {
                    if (datajenis == "produk") {
                        if ($("input[name='cek'][type='radio']").prop("checked")) {
                            $('#btnsimpan').removeAttr('disabled');
                        } else {
                            $('#btnsimpan').attr('disabled', true);
                        }
                    } else {
                        if (($("input[name='jumlah_ok']").val() != "" && !$("input[name='jumlah_ok']")
                                .hasClass('is-invalid')) && ($("input[name='jumlah_nok']").val() != "" && !
                                $("input[name='jumlah_nok']").hasClass('is-invalid'))) {
                            $('#btnsimpan').removeAttr('disabled');
                        } else {
                            $('#btnsimpan').attr('disabled', true);
                        }
                    }
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            });

            $(document).on('keyup change', 'input[type="number"][name="jumlah_ok"]', function(event) {
                if (datajenis == "part") {
                    var valok = $(this).val();
                    if ($(this).val() !== "") {
                        $.ajax({
                            type: "POST",
                            url: '/api/qc/so/cek/part/' + dataid,
                            dataType: 'json',
                            success: function(data) {
                                if (valok > data) {
                                    $('input[type="number"][name="jumlah_ok"]').addClass(
                                        'is-invalid');
                                    $('#btnsimpan').attr('disabled', true);
                                    $("#msgjumlah_ok").text("Data melebihi jumlah Part");
                                } else if (valok <= data) {
                                    $('input[type="number"][name="jumlah_ok"]').removeClass(
                                        'is-invalid');
                                    $("#msgjumlah_ok").text("");
                                    if ($("input[type='number'][name='jumlah_nok']").val() !==
                                        "") {
                                        var jumlahnok = $(
                                            "input[type='number'][name='jumlah_nok']").val();
                                        var jumlahcurr = jumlahnok + valok;
                                        if (jumlahcurr > data) {
                                            $('input[type="number"][name="jumlah_ok"]')
                                                .addClass('is-invalid');
                                            $("#msgjumlah_ok").text(
                                                "Data melebihi jumlah Part");
                                            $('#btnsimpan').attr('disabled', true);
                                        } else if (jumlahcurr <= data) {
                                            if ($("input[name='tanggal_uji']").val() != "") {
                                                $('input[type="number"][name="jumlah_ok"]')
                                                    .removeClass('is-invalid');
                                                $("#msgjumlah_ok").text("");
                                                $('#btnsimpan').removeAttr('disabled');
                                            } else {
                                                $('input[type="number"][name="jumlah_nok"]')
                                                    .removeClass('is-invalid');
                                                $("#msgjumlah_ok").text("");
                                                $('#btnsimpan').attr('disabled', true);
                                            }
                                        }
                                    } else {
                                        $("input[type='number'][name='jumlah_nok']").val("0");
                                        if ($("input[name='tanggal_uji']").val() != "") {
                                            $('input[type="number"][name="jumlah_ok"]')
                                                .removeClass('is-invalid');
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
                    } else {
                        $('#btnsimpan').attr('disabled', true);
                    }
                }
            });

            $(document).on('keyup change', 'input[type="number"][name="jumlah_nok"]', function(event) {
                if (datajenis == "part") {
                    var valnok = $(this).val();
                    if ($(this).val() !== "") {
                        $.ajax({
                            type: "POST",
                            url: '/api/qc/so/cek/part/' + dataid,
                            dataType: 'json',
                            success: function(data) {
                                if (valnok > data) {
                                    $('input[type="number"][name="jumlah_nok"]').addClass(
                                        'is-invalid');
                                    $('#btnsimpan').attr('disabled', true);
                                    $("#msgjumlah_nok").text("Data melebihi jumlah Part");
                                } else if (valnok <= data) {
                                    $('input[type="number"][name="jumlah_nok"]').removeClass(
                                        'is-invalid');
                                    $("#msgjumlah_nok").text("");
                                    if ($("input[type='number'][name='jumlah_ok']").val() !==
                                        "") {
                                        var jumlahok = $(
                                            "input[type='number'][name='jumlah_ok']").val();
                                        var jumlahcurr = jumlahok + valnok;
                                        if (jumlahcurr > data) {
                                            $('input[type="number"][name="jumlah_nok"]')
                                                .addClass('is-invalid');
                                            $("#msgjumlah_nok").text(
                                                "Data melebihi jumlah Part");
                                            $('#btnsimpan').attr('disabled', true);
                                        } else if (jumlahcurr <= data) {
                                            $("#msgjumlah_nok").text("");
                                            if ($("input[name='tanggal_uji']").val() != "") {
                                                $('input[type="number"][name="jumlah_nok"]')
                                                    .removeClass('is-invalid');
                                                $('#btnsimpan').removeAttr('disabled');
                                            } else {
                                                $('input[type="number"][name="jumlah_nok"]')
                                                    .removeClass('is-invalid');
                                                $('#btnsimpan').attr('disabled', true);
                                            }
                                        }
                                    } else {
                                        $("input[type='number'][name='jumlah_ok']").val("0");
                                        if ($("input[name='tanggal_uji']").val() != "") {
                                            $('input[type="number"][name="jumlah_nok"]')
                                                .removeClass('is-invalid');
                                            $("#msgjumlah_nok").text("");
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
                    } else {
                        $('#btnsimpan').attr('disabled', true);
                    }
                }
            });



            $('input[type=radio][name=filter]').change(function() {
                $('input[name ="check_all"]').prop('checked', false);
                $(".cek_header").css("display", "block");
                var stat = this.value;
                if (stat == 'sudah' || stat == 'semua') {
                    $('.cek_header').css('display', 'none')
                }
                console.log('/api/qc/so/seri/' + stat + '/' + dataid + '/{{ $id }}');
                var dat = $('#noseritable').DataTable().ajax.url('/api/qc/so/seri/' + stat + '/' + dataid +
                    '/{{ $id }}').load();


            });

        })
    </script>
@stop
