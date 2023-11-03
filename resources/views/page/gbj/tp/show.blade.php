@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
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

        .topnav a:hover {
            border-bottom: 3px solid red;
        }

        .topnav a.active {
            border-bottom: 3px solid red;
        }

        .active-link {
            box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .nav-border {
            border-bottom: 2px solid black;
            content: "";
        }

        section {
            font-family: "Source Sans Pro"
        }

        img {
            /* Jika Gambar Disamping */
            width: 330px;
            /* Jika Gambar Diatas */
            /* width: 100px; */
        }

        #DataTables_Table_0_filter {
            display: none;
        }

        .dropdown-menu {
            width: 500px !important;
        }
    </style>
    <div class="row mb-2">
        <div class="col-sm-1">
            {{-- <a href="javascript:;" onclick = "history.back()">Redirect back to Page 1</a> --}}
            {{-- <a href="javascript:history.back()"><i class="fas fa-arrow-left"></i></a> --}}
            <button class="btn btn-secondary btnBack" type="button"><i class="fas fa-arrow-left"></i></button>
        </div>
        <div class="col-6">
            <h4>Detail Riwayat Transaksi {{ $header->produk->nama }} {{ $header->nama }}</h1>
        </div><!-- /.col -->
    </div>
    <section>
        <div class="row">
            <div class="col-5">
                @foreach ($data as $d)
                    <input type="hidden" name="id" id="ids" value="{{ $d->id }}">
                    <div class="card" style="width: 40rem">
                        <div class="row no-gutters">
                            <div class="col-md-5">
                                <div class="d-flex justify-content-center mt-5">
                                    @if (isset($d->gambar))
                                        <img src="{{ asset('upload/gbj/' . $d->gambar) }}" alt=""
                                            style="width: 150px">
                                    @else
                                        <img src="{{ asset('assets/image/unknown-icon.png') }}" alt=""
                                            style="width: 150px">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-6">
                                <div class="card-body">
                                    <h5 class="card-title text-bold">{{ $d->produk->nama }} {{ $d->nama }}</h5>
                                    <p class="card-text"><small
                                            class="text-muted">{{ $d->produk->product->kode . '' . $d->produk->kode ? $d->produk->product->kode . '' . $d->produk->kode : '-' }}</small>
                                    </p>
                                    <p class="card-text">Deskripsi</p>
                                    <p class="card-text">{{ $d->deskripsi }}</p>
                                    <p class="card-text">Dimensi</p>
                                    <p class="card-text">Panjang x Lebar x Tinggi</p>
                                    <p class="card-text">{{ $d->dim_p }} x {{ $d->dim_l }} x {{ $d->dim_t }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-7">
                <div class="card">
                    <div class="card-title">
                        <div class="ml-3 mr-3">
                            <div class="row align-items-center">
                                <div class="col-lg-9 col-xl-8">
                                    <div class="row align-items-center">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="kt_datepicker_1">Tanggal Masuk</label>
                                                        <input type="text" class="form-control" id="kt_datepicker_1">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="kt_datepicker_2">Tanggal Keluar</label>
                                                        <input type="text" class="form-control" id="kt_datepicker_2">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Cari..."
                                                    id="kt_datatable_search_query">
                                                <span>
                                                    <i class="flaticon2-search-1 text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xl-4">
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-7">
                            <table class="table tableProdukView">
                                <thead>
                                    <tr>
                                        <th style="width: 20%">Nomor SO</th>
                                        <th style="width: 20%">Nomor PO</th>
                                        <th style="width: 20%">Tanggal Masuk</th>
                                        <th style="width: 20%">Tanggal Keluar</th>
                                        <th style="width: 20%">Dari/Ke</th>
                                        <th style="width: 20%">Tujuan</th>
                                        <th style="width: 20%">Jumlah</th>
                                        <th style="width: 20%">Status</th>
                                        <th style="width: 20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Modal --}}
    <div class="modal fade modalDetail" id="" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Produk {{ $header->produk->nama }} {{ $header->nama }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-seri">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Seri</th>
                                <th>Layout</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('adminlte_js')
    <script>
        // Tanggal Masuk
        var start_date;
        var end_date;
        var DateFilterFunction = (function(oSettings, aData, iDataIndex) {
            var dateStart = parseDateValue(start_date);
            var dateEnd = parseDateValue(end_date);

            var evalDate = parseDateValue(aData[2]);
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

            var evalDate = parseDateValue2(aData[3]);
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

        $('#nav-deskripsi-tab').click(function(e) {
            e.preventDefault();
            $('.is-active').addClass('font-weight-bold');
            $('.is-active').removeClass('font-weight-light');
            $('.is-disable').addClass('font-weight-light');
            $('.is-disable').removeClass('font-weight-bold');
        });
        $('#nav-dimensi-tab').click(function(e) {
            e.preventDefault();
            $('.is-active').removeClass('font-weight-bold');
            $('.is-active').addClass('font-weight-light');
            $('.is-disable').removeClass('font-weight-light');
            $('.is-disable').addClass('font-weight-bold');
        });
        $('#tanggalmasuk').daterangepicker({});

        function detailProduk() {
            $('.modalDetail').modal('show');
        }

        $(document).on('click', '.editmodal', function() {
            var id = $(this).data('id');
            console.log(id);
            $('.table-seri').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                ajax: {
                    url: "/api/transaksi/history-detail-seri/" + id,
                },
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'noser'
                    },
                    {
                        data: 'posisi'
                    },
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                }
            });
            detailProduk();
        })

        $(document).ready(function() {
            // disable button back browser
            window.history.pushState(null, "", window.location.href);
            window.onpopstate = function() {
                window.history.pushState(null, "", window.location.href);
            };

            $('.btnBack').click(function() {
                window.location.href = '{{ url('gbj/tp') }}'
            })
            var id = $('#ids').val();
            console.log(id);
            var table = $('.tableProdukView').dataTable({
                destroy: true,
                scrollY: "500px",
                processing: true,
                responsive: true,
                "lengthChange": false,
                autoWidth: false,
                ajax: {
                    url: "/api/transaksi/history-detail/" + id,
                },
                columns: [{
                        data: 'so',
                        name: 'so'
                    },
                    {
                        data: 'po'
                    },
                    {
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
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'logs'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                }
            });

            $(document).on('keyup', '#kt_datatable_search_query', function() {
                table.api().search(this.value).draw();
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
                table.api().draw();
            });

            $('#kt_datepicker_1').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                start_date = '';
                end_date = '';
                $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
                table.api().draw();
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
                table.api().draw();
            });

            $('#kt_datepicker_2').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                start_date2 = '';
                end_date2 = '';
                $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction2, 1));
                table.api().draw();
            });
        })
    </script>
@stop
