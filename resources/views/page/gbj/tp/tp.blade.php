@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
    <style>
        .foo {
            float: left;
            width: 20px;
            height: 20px;
            margin: 5px;
            border: 1px solid rgba(0, 0, 0, .2);
        }

        .green {
            background: #28A745;
        }

        .blue {
            background: #17A2B8;
        }

        .gambar-resize {
            width: 250px;
            height: 250px;
        }

        .card-center {
            left: 20%;
        }

        .history_filter {
            display: block;
        }

        #gudang-salesorder_filter {
            display: none;
        }

        .dropdown-menu {
            width: 500px !important;
        }

        th.prev.available {
            visibility: hidden;
        }

        th.next.available {
            visibility: hidden;
        }
    </style>
    <div class="content-header">
        <input type="hidden" name="" id="authid" value="{{ Auth::user()->Karyawan->divisi_id }}">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Riwayat Transaksi</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="nav nav-tabs" id="myTab" role="tabList">
                        <li class="nav-item" role="presentation">
                            <a href="#semua-produk" class="nav-link active" id="semua-produk-tab" data-toggle="tab"
                                role="tab" aria-controls="semua-produk" aria-selected="true">Per Tanggal</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#produk" class="nav-link" id="produk-tab" data-toggle="tab" role="tab"
                                aria-controls="produk" aria-selected="true">Per Produk</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#salesorder" class="nav-link" id="salesorder-tab" data-toggle="tab" role="tab"
                                aria-controls="salesorder" aria-selected="true">Per Sales Order</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#nonsalesorder" class="nav-link" id="nonsalesorder-tab" data-toggle="tab"
                                role="tab" aria-controls="nonsalesorder" aria-selected="true">Non Sales Order</a>
                        </li>
                    </ul>
                    <div class="tab-content card" id="myTabContent">
                        <div class="tab-pane fade show active card-body" id="semua-produk" role="tabpanel"
                            aria-labelledby="semua-produk-tab">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="row align-items-center">
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 my-2 my-md-0">
                                                    <div class="input-icon">
                                                        <input type="text" class="form-control" placeholder="Cari..."
                                                            id="kt_datatable_search_query">
                                                        <span>
                                                            <i class="flaticon2-search-1 text-muted"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 my-2 my-md-0">
                                                    <div class="d-flex align-items-center">
                                                        <label class="mr-3 mb-0 d-none d-md-block"
                                                            for="">Dari</label>
                                                        <select name="" id="divisi" class="form-control ">
                                                            <option value="">All</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 my-2 my-md-0">
                                                    <div class="d-flex align-items-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-outline-secondary dropdown-toggle"
                                                                type="button" id="dropdownMenuButton"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                                Filter Tanggal
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <div class="row m-0">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="kt_datepicker_1">Tanggal
                                                                                Masuk</label>
                                                                            <input type="text" class="form-control"
                                                                                id="kt_datepicker_1">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="kt_datepicker_2">Tanggal
                                                                                Keluar</label>
                                                                            <input type="text" class="form-control"
                                                                                id="kt_datepicker_2">
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
                                {{-- <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="card-text">Keterangan Kolom <b>Dari/Ke:</b></p>
                                        <p class="card-text">
                                            <div class="foo green"></div> : Dari
                                        </p>
                                        <p class="card-text">
                                            <div class="foo blue"></div> : Ke
                                        </p>
                                    </div>
                                </div>
                            </div> --}}
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover pertanggal" width="100%" id="history">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal Masuk</th>
                                                    <th>Tanggal Keluar</th>
                                                    <th>Dari</th>
                                                    <th>Tujuan</th>
                                                    <th>Nomor PO</th>
                                                    {{-- <th>Nomor PO</th> --}}
                                                    <th>Produk</th>
                                                    <th>Jumlah</th>
                                                    {{-- <th>Status</th> --}}
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade card-body" id="produk" role="tabpanel"
                            aria-labelledby="produk-tab">
                            <div class="table-produk">
                                <table class="table table-bordered" id="gudang-barang">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Produk</th>
                                            <th>Produk</th>
                                            <th>Stok Gudang</th>
                                            <th>Stok Penjualan</th>
                                            <th>Kelompok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade card-body" id="salesorder" role="tabpanel"
                            aria-labelledby="salesorder-tab">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row align-items-center">
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 my-2 my-md-0">
                                                    <div class="input-icon">
                                                        <input type="text" class="form-control" placeholder="Cari..."
                                                            id="gudang-salesorder-search">
                                                        <span>
                                                            <i class="flaticon2-search-1 text-muted"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 my-2 my-md-0">
                                                    <div class="d-flex align-items-center">
                                                        <label class="mr-3 mb-0 d-none d-md-block" for="">Batas
                                                            Transfer</label>
                                                        <input type="text" name="" id="daterange1"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-12">
                                    <div class="table-salesorder">
                                        <table class="table table-bordered" id="gudang-salesorder">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nomor SO</th>
                                                    <th>Nomor PO</th>
                                                    <th>Customer</th>
                                                    <th>Tgl Transfer</th>
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
                        <div class="tab-pane fade card-body" id="nonsalesorder" role="tabpanel"
                            aria-labelledby="nonsalesorder-tab">
                            {{-- <div class="row">
                            <div class="col-sm-12">
                                <div class="row align-items-center">
                                    <div class="col-lg-9 col-xl-8">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 my-2 my-md-0">
                                                <div class="input-icon">
                                                    <input type="text" class="form-control" placeholder="Cari..."
                                                        id="gudang-salesorder-search">
                                                    <span>
                                                        <i class="flaticon2-search-1 text-muted"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 my-2 my-md-0">
                                                <div class="d-flex align-items-center">
                                                    <label class="mr-3 mb-0 d-none d-md-block" for="">Batas Transfer</label>
                                                    <input type="text" name="" id="daterange1" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                            <div class="row mt-2">
                                <div class="col-lg-12">
                                    <div class="table-nonsalesorder">
                                        <table class="table table-bordered" id="non-salesorder">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Deskripsi</th>
                                                    <th>Tgl Masuk</th>
                                                    <th>Dari</th>
                                                    <th>Tgl Keluar</th>
                                                    <th>Ke</th>
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
    </div>
    <!-- Modal Per Tanggal-->
    <div class="modal fade" id="modal-per-tanggal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span id="title">Produk Ambulatory</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-seri">
                        <thead>
                            <tr>
                                <th>No Seri</th>
                                <th>Layout</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewDetailBrgSO" data-keyboard="false" tabindex="-1" aria-labelledby=""
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-body" id="viewDetailBrgSObody">

                </div>
            </div>
        </div>
    </div>

@stop

@section('adminlte_js')
    <script>
        var access_token = localStorage.getItem('lokal_token');

        if (access_token == null) {
            Swal.fire({
                title: 'Session Expired',
                text: 'Silahkan login kembali',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.preventDefault();
                    document.getElementById('logout-form').submit();
                }
            })
        }

        $('#divisi').select2({
            placeholder: "Choose...",
            allowClear: true
        })
        $.ajax({
            url: '/api/gbj/sel-divisi',
            type: 'GET',
            dataType: 'json',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
            },
            success: function(res) {
                if (res) {
                    console.log(res);
                    $("#divisi").empty();
                    $("#divisi").append('<option selected></option>');
                    $.each(res, function(key, value) {
                        $("#divisi").append('<option value="' + value.nama + '">' + value.nama +
                            '</option');
                    });
                } else {
                    $("#divisi").empty();
                }
            }
        });

        function detailtanggal() {
            $('#modal-per-tanggal').modal('show');
        }

        function detailProduk() {
            $('.modalDetail').modal('show');
        }

        // Tanggal Masuk
        var start_date;
        var end_date;
        var DateFilterFunction = (function(oSettings, aData, iDataIndex) {
            var dateStart = parseDateValue(start_date);
            var dateEnd = parseDateValue(end_date);

            var evalDate = parseDateValue(aData[0]);
            if ((isNaN(dateStart) && isNaN(dateEnd)) ||
                (isNaN(dateStart) && evalDate <= dateEnd) ||
                (dateStart <= evalDate && isNaN(dateEnd)) ||
                (dateStart <= evalDate && evalDate <= dateEnd)) {
                return true;
            }
            return false;
        });

        function parseDateValue(rawDate) {
            var dateArray = rawDate.split("-");
            var parsedDate = new Date(dateArray[2], parseInt(dateArray[1]) - 1, dateArray[
                0]);
            return parsedDate;
        }

        // Tanggal Keluar
        var start_date2;
        var end_date2;
        var DateFilterFunction2 = (function(oSettings, aData, iDataIndex) {
            var dateStart = parseDateValue2(start_date2);
            var dateEnd = parseDateValue2(end_date2);

            var evalDate = parseDateValue2(aData[1]);
            if ((isNaN(dateStart) && isNaN(dateEnd)) ||
                (isNaN(dateStart) && evalDate <= dateEnd) ||
                (dateStart <= evalDate && isNaN(dateEnd)) ||
                (dateStart <= evalDate && evalDate <= dateEnd)) {
                return true;
            }
            return false;
        });

        function parseDateValue2(rawDate) {
            var dateArray = rawDate.split("-");
            var parsedDate = new Date(dateArray[2], parseInt(dateArray[1]) - 1, dateArray[
                0]);
            return parsedDate;
        }

        // Tanggal Sales Order
        var start_date3;
        var end_date3;
        var DateFilterFunction3 = (function(oSettings, aData, iDataIndex) {
            var dateStart = parseDateValue3(start_date3);
            var dateEnd = parseDateValue3(end_date3);
            var evalDate = parseDateValue3(aData[3]);
            if ((isNaN(dateStart) && isNaN(dateEnd)) ||
                (isNaN(dateStart) && evalDate <= dateEnd) ||
                (dateStart <= evalDate && isNaN(dateEnd)) ||
                (dateStart <= evalDate && evalDate <= dateEnd)) {
                return true;
            }
            return false;
        });

        function parseDateValue3(rawDate) {
            var dateArray = rawDate.split("-");
            var parsedDate = new Date(dateArray[2], parseInt(dateArray[1]) - 1, dateArray[
                0]);
            return parsedDate;
        }

        var $dTable = $('#history').DataTable({
            destroy: true,
            processing: true,
            autoWidth: false,
            deferRender: true,
            searching: false,
            ordering: false,
            ajax: {
                url: "/api/transaksi/all",
                // type: "post",
            },
            columns: [{
                    data: 'date_in',
                    name: 'date_in'
                },
                {
                    data: 'date_out',
                    name: 'date_out'
                },
                {
                    data: 'divisi',
                    name: 'divisi'
                },
                {
                    data: 'tujuan',
                    name: 'tujuan'
                },
                {
                    data: 'po',
                    name: 'po  '
                },
                // { data: 'so', name: 'so'},
                // { data: 'po', name: 'po'},
                {
                    data: 'product',
                    name: 'product'
                },
                {
                    data: 'jumlah',
                    name: 'jumlah'
                },
                // {data: 'logs'},
                {
                    data: 'action',
                    name: 'action'
                },
            ],
            dom: 'Bfrtip',
            "language": {
                // "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                processing: "<span class='fa-stack fa-md'>\n\
                                                                                                                                                                                                                                                                                                                                                                                                                                <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                                                                                                                                                                                                                                                                                                                                                                                                                        </span>&emsp;Mohon Tunggu ...",
            },
            order: [
                [0, 'desc']
            ],
        });
        // Tanggal Masuk
        $('#kt_datepicker_1').daterangepicker({
            autoUpdateInput: false
        });

        $('#kt_datepicker_1').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format(
                'DD-MM-YYYY'));
            start_date = picker.startDate.format('DD-MM-YYYY');
            end_date = picker.endDate.format('DD-MM-YYYY');
            $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
            $dTable.draw();
        });

        $('#kt_datepicker_1').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            start_date = '';
            end_date = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
            $dTable.draw();
        });

        // Tanggal Keluar
        $('#kt_datepicker_2').daterangepicker({
            autoUpdateInput: false
        });

        $('#kt_datepicker_2').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format(
                'DD-MM-YYYY'));
            start_date2 = picker.startDate.format('DD-MM-YYYY');
            end_date2 = picker.endDate.format('DD-MM-YYYY');
            $.fn.dataTableExt.afnFiltering.push(DateFilterFunction2);
            $dTable.draw();
        });

        $('#kt_datepicker_2').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            start_date2 = '';
            end_date2 = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction2, 1));
            $dTable.draw();
        });


        $("#divisi").on("change", function() {
            console.log($(this).val());
            $dTable.columns(1).search($(this).val()).draw();
        });

        $("#kt_datatable_search_query").on("keyup", function() {
            $dTable.search($(this).val()).draw();
        });
        $(document).ready(function() {

            var gudangbarang = $('#gudang-barang').dataTable({
                destroy: true,
                processing: true,
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: "/api/transaksi/history",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'kode_produk',
                        name: 'kode_produk'
                    },
                    {
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    },
                    {
                        data: 'stok_jual',
                        name: 'stok_jual'
                    },
                    {
                        data: 'kelompok',
                        name: 'kelompok'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                "language": {
                    // "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                    processing: "<span class='fa-stack fa-md'>\n\
                                                                                                                                                                                                                                                                                                                                                                                                                                    <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                                                                                                                                                                                                                                                                                                                                                                                                                            </span>&emsp;Mohon Tunggu ...",
                },
            });
            $(document).on("click", "#semua-produk-tab", function() {
                $('.produk-show').addClass('hidden-product');
            });
            $(document).on("click", "#produk-tab", function() {
                $('.produk-show').addClass('hidden-product');
            });

        });

        function detailProdukModal() {
            $('.produk-show').removeClass('hidden-product');
        }

        $(document).on('click', '.editmodal', function() {
            var id = $(this).data('id');
            console.log(id);

            $.ajax({
                url: "/api/transaksi/all-detail/" + id,
                success: function(res) {
                    console.log(res);
                    $('span#title').text(res.data[0].title);
                }
            });
            $('.table-seri').DataTable().destroy();
            $('.table-seri').dataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "/api/transaksi/all-detail/" + id,
                },
                columns: [{
                        data: 'seri',
                        name: 'seri'
                    },
                    {
                        data: 'layout',
                        name: 'layout'
                    },
                ],
                "oLanguage": {
                    "sSearch": "Cari:"
                }
            });

            detailtanggal();
        });

        var userid = $('#authid').val();
        console.log(userid);
        var soTable = $('#gudang-salesorder').DataTable({
            destroy: true,
            processing: true,
            responsive: true,
            autoWidth: false,
            lengthChange: false,
            ordering: false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
            ajax: {
                url: "/api/gbj/data-so",
                type: "post",
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'noso'
                },
                {
                    data: 'nopo'
                },
                {
                    data: 'customer'
                },
                {
                    data: 'tgl_keluar'
                },
                {
                    data: 'logs'
                },
                {
                    data: function(data) {
                        if (userid != 2) {
                            // console.log(data)
                            // return
                            return `<td><a href="export_spb/` + data.id + `">
                        <button class="btn btn-outline-primary"><i class="fas fa-print"></i> Cetak</button>
                        </a>
                        <button class="btn btn-outline-primary" data-id="` + data.id + `" id="detail-so"><i class="fas fa-eye"></i> Detail</button>
                       </td>`
                        } else {
                            return '';
                        }

                    }
                }
            ],
            language: {
                processing: "<span class='fa-stack fa-md'>\n\
                                                                                                                                                                                                                                                                                                                                                                                                                                    <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                                                                                                                                                                                                                                                                                                                                                                                                                            </span>&emsp;Mohon Tunggu ...",
            }
        });
        // Sales Order Cetak
        $('#gudang-salesorder-search').on('keyup', function() {
            soTable.search(this.value).draw();
        });
        $('#daterange1').daterangepicker({
            autoUpdateInput: false
        });

        $('#daterange1').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format(
                'DD-MM-YYYY'));
            start_date3 = picker.startDate.format('DD-MM-YYYY');
            end_date3 = picker.endDate.format('DD-MM-YYYY');
            $.fn.dataTableExt.afnFiltering.push(DateFilterFunction3);
            soTable.draw();
        });

        $('#daterange1').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            start_date3 = '';
            end_date3 = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction3, 1));
            soTable.draw();
        });

        // non so
        $('#non-salesorder').DataTable({
            destroy: true,
            processing: true,
            responsive: true,
            autoWidth: false,
            lengthChange: false,
            ordering: false,
            ajax: {
                url: "/api/v2/gbj/show_nonso_new",
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'deskripsi'
                },
                {
                    data: 'tgl_masuk'
                },
                {
                    data: 'dari'
                },
                {
                    data: 'tgl_keluar'
                },
                {
                    data: 'ke'
                },
                {
                    data: function(data) {
                        if (userid != 2) {
                            // console.log(data)
                            // return
                            return `<td>
                        <button class="btn btn-outline-primary" data-id="` + data.id + `" id="detail-nonso"><i class="fas fa-eye"></i> Detail</button>
                       </td>`
                        } else {
                            return '';
                        }

                    }
                }
                // {data: function(data){
                //     return `<td><a href="export_nonso/`+data.deskripsi+`">
            //                 <button class="btn btn-outline-primary"><i class="fas fa-eye"></i> Detail</button>
            //             </a></td>`
                // }},
            ],
            language: {
                processing: "<span class='fa-stack fa-md'>\n\                                                                                                                                                                                                                                                              </span>&emsp;Mohon Tunggu ...",
            }
        })



        function view_seri(id) {
            $('#view-seritf').DataTable({
                destroy: true,
                processing: true,
                responsive: true,
                autoWidth: false,
                lengthChange: false,
                ordering: false,
                ajax: {
                    url: "/api/gbj/modal_data_seri/" + id,
                },
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'produk'
                    },
                    {
                        data: 'seri'
                    }
                ],
            })
        }

        function view_serinon(id) {
            $('#view-seritfnon').DataTable({
                destroy: true,
                processing: true,
                responsive: true,
                autoWidth: false,
                lengthChange: false,
                ordering: false,
                ajax: {
                    url: "/api/gbj/modal_data_seri_non/" + id,
                },
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'produk'
                    },
                    {
                        data: 'seri'
                    }
                ],
            })
        }
        $(document).on('click', '#detail-so', function(event) {
            // alert('ok')
            event.preventDefault();
            var data_id = $(this).attr('data-id');

            $.ajax({
                url: "/api/gbj/modal_data/" + data_id,
                // return the result
                success: function(result) {
                    $('#viewDetailBrgSO').modal("show");
                    $('#viewDetailBrgSOtitle').text("Detail Status : ");

                    $('#viewDetailBrgSObody').html(result).show();
                    view_seri(data_id)
                },
            })
        });

        $(document).on('click', '#detail-nonso', function(event) {
            event.preventDefault();
            var data_id = $(this).attr('data-id');

            $.ajax({
                url: "/api/gbj/modal_data_non/" + data_id,
                // return the result
                success: function(result) {
                    $('#viewDetailBrgSO').modal("show");
                    $('#viewDetailBrgSOtitle').text("Detail Status : ");

                    $('#viewDetailBrgSObody').html(result).show();
                    view_serinon(data_id)
                },
            })
        });
    </script>
@stop
