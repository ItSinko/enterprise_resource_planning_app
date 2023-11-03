@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
    <style>
        .belum_diterima {
            float: left;
            width: auto;
            padding: 5px;
            margin-top: 5px;
            border: 1px solid #FFE2E5;
            background-color: #FFE2E5;
            color: #F7616B;
            font-size: 14px;
            border-radius: 6px;
        }

        .sebagian_diterima {
            float: left;
            width: auto;
            padding: 5px;
            margin-top: 5px;
            border: 1px solid #FFCC83;
            background-color: #FEF7EA;
            color: #ff9e17;
            font-size: 14px;
            border-radius: 6px;
        }

        .sudah_diterima {
            float: left;
            width: auto;
            padding: 5px;
            margin-top: 5px;
            border: 1px solid #C9F7F5;
            background-color: #C9F7F5;
            color: #1CC7CD;
            font-size: 14px;
            border-radius: 6px;
        }

        /* .dataTables_filter{
                                                            display: none;
                                                        } */

        .calendar-time {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('vendor/fullcalendar/main.css') }}">
    <script src="{{ asset('vendor/fullcalendar/main.js') }}"></script>
    <input type="hidden" name="" id="auth" value="{{ Auth::user()->divisi_id }}">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Transfer Gudang</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="row ml-2">
        <div class="col-sm-2">
            <input type="hidden" name="userid" id="userid" value="{{ Auth::user()->id }}">
            <div class="form-group">
                <label for="">Tanggal Masuk</label>
                <input type="text" name="" id="kt_datepicker_1" class="form-control">
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label for="">Tanggal Keluar</label>
                <input type="text" name="" id="kt_datepicker_2" class="form-control">
            </div>
        </div>
        {{-- <a href="{{ route('export.rakitseri') }}" class="btn btn-outline-success"><i class="far fa-file-excel"></i> Export</a> --}}
    </div>
    <div class="row ml-2">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('export.rakitseri') }}" class="btn btn-outline-success"><i
                            class="far fa-file-excel"></i> Noseri</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table_produk_perakitan ">
                                <thead class="thead-dark">
                                    <tr>
                                        <th rowspan="2">Periode</th>
                                        <th colspan="2" class="text-center">Tanggal</th>
                                        <th rowspan="2">Nomor BPPB</th>
                                        <th rowspan="2">Produk</th>
                                        <th rowspan="2">Jumlah</th>
                                        <th rowspan="2">Progress</th>
                                        <th rowspan="2">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Tanggal Masuk</th>
                                        <th class="text-center">Tanggal Keluar</th>
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

    <!-- Modal -->
    <div class="modal fade modalRakit" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Nomor BPPB</label>
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            <span id="no_bppb">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Nama Produk</label>
                                    <div class="card" style="background-color: #F89F81">
                                        <div class="card-body">
                                            <span id="produk">Produk 1</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Kategori</label>
                                    <div class="card" style="background-color: #FCF9C4">
                                        <div class="card-body">
                                            <span id="kategori">Kategori 1</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Jumlah Rakit</label>
                                    <div class="card" style="background-color: #FFCC83">
                                        <div class="card-body">
                                            <span id="jml">100 Unit</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Tanggal Mulai</label>
                                    <div class="card" style="background-color: #FFE0B4">
                                        <div class="card-body">
                                            <span id="start">10-06-2021</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Tanggal Selesai</label>
                                    <div class="card" style="background-color: #FFECB2">
                                        <div class="card-body">
                                            <span id="end"> 13-06-2021</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex bd-highlight mb-3">
                                <div class="p-2 bd-highlight">
                                    <div class="form-group">
                                        <label for="">Tanggal Pengiriman</label>
                                        <input type="text" class="form-control" id="tgl_perakitan" name="waktu_tf"
                                            placeholder="Tanggal Perakitan">
                                    </div>
                                </div>
                                <div class="ml-auto p-2 bd-highlight">
                                    <button class="btn btn-primary mt-5" data-toggle="modal"
                                        data-target="#modalautocheckbox">Pilih
                                        Nomor Seri Via Text</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="post" id="form-scan">
                                        <table class="table table-striped scan-produk" id>
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" name="" id="head-cb"></th>
                                                    <th>Nomor Seri</th>
                                                    {{-- <th>Aksi</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody class="scan"></tbody>
                                        </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            No Seri Yang Dipilih : <span id="no_seri"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="checked">Check</button>
                    <button type="button" class="btn btn-danger" id="hapusSeri">Hapus</button>
                    <button type="submit" class="btn btn-primary" id="saveButton">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    @include('page.produksi.noseriviatext')

    <!-- Modal Transfer-->
    <div class="modal fade modalSeri" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit No Seri</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">No Seri</label>
                        <input type="hidden" name="" id="idNoseri">
                        <input type="text" name="" id="noseriselect" class="form-control" placeholder=""
                            aria-describedby="helpId" style="text-transform: uppercase;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="button" class="btn btn-primary submitNoSeri">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Transfer Sisa --}}
    <div class="modal fade modalTranfer" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Nomor BPPB</label>
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            <span id="no_bppb1">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Nama Produk</label>
                                    <div class="card" style="background-color: #F89F81">
                                        <div class="card-body">
                                            <span id="produk1">Produk 1</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Kategori</label>
                                    <div class="card" style="background-color: #FCF9C4">
                                        <div class="card-body">
                                            <span id="kategori1">Kategori 1</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Jumlah Transfer</label>
                                    <div class="card" style="background-color: #FFCC83">
                                        <div class="card-body">
                                            <span id="jml1">100 Unit</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Tanggal Mulai</label>
                                    <div class="card" style="background-color: #FFE0B4">
                                        <div class="card-body">
                                            <span id="start1">10-06-2021</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Tanggal Selesai</label>
                                    <div class="card" style="background-color: #FFECB2">
                                        <div class="card-body">
                                            <span id="end1"> 13-06-2021</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="" id="formClose" name="formClose">
                            <input type="hidden" name="jadwal_id" id="jwdid">
                            <div class="card-body">
                                <label for="">Keterangan</label>
                                <textarea name="keterangan_transfer" id="keterangan_transfer" cols="10" rows="10"
                                    class="form-control keteranganTransfer"></textarea>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="submit" class="btn btn-primary simpanTransfer" id="btnCloseSimpan">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('adminlte_js')
    <script>
        // Tanggal Masuk
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
        $(document).ready(function() {
            let date = new Date();
            $('#tgl_perakitan').val(date.toISOString().substr(0, 10));
            $('#tgl_perakitan').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
            var table = $('.table_produk_perakitan').DataTable({
                processing: false,
                ordering: false,
                destroy: true,
                ajax: {
                    url: "/api/prd/kirim",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                    },
                },
                columns: [{
                        data: 'periode'
                    },
                    {
                        data: "start"
                    },
                    {
                        data: "end"
                    },
                    {
                        data: "no_bppb"
                    },
                    {
                        data: "produk"
                    },
                    {
                        data: "jml"
                    },
                    {
                        data: "progress"
                    },
                    {
                        data: "action"
                    },
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                },
                "lengthChange": false,
                "columnDefs": [{
                    "targets": [7],
                    "visible": document.getElementById('auth').value == '2' ? false : true
                }]
            });

            $('#kt_datepicker_1').daterangepicker({
                autoUpdateInput: false
            });

            $('#kt_datepicker_1').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format(
                    'DD-MM-YYYY'));
                start_date = picker.startDate.format('DD-MM-YYYY');
                end_date = picker.endDate.format('DD-MM-YYYY');
                $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
                table.draw();
            });

            $('#kt_datepicker_1').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                start_date = '';
                end_date = '';
                $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
                table.draw();
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
                table.draw();
            });

            $('#kt_datepicker_2').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                start_date2 = '';
                end_date2 = '';
                $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction2, 1));
                table.draw();
            });

        })

        function transfer() {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Data yang sudah di transfer tidak dapat diubah!",
                icon: "warning",
                buttons: true,
                showCancelButton: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    Swal.fire(
                        'Berhasil!',
                        'Data berhasil di transfer!',
                        'success'
                    );
                    $('.modalRakit').modal('hide');
                } else {
                    Swal.fire(
                        'Batal!',
                        'Data tidak berhasil di transfer!',
                        'error'
                    );
                }
            });
        }
        var id = '';
        var prd = '';
        var jml = '';



        $(document).on('click', '.detailmodal', function() {
            $('.modalRakit').modal('show');
            $('.scan-produk tbody').empty();
            id = $(this).data('id');
            console.log(id);
            prd = $(this).data('prd');
            jumlah = $(this).data('jml');
            $.ajax({
                url: "/api/prd/headerSeri/" + id,
                type: "get",
                dataType: "json",
                success: function(res) {
                    // console.log(res);
                    $('span#no_bppb').text(res.bppb);
                    $('span#produk').text(res.produk);
                    $('span#kategori').text(res.kategori);
                    $('span#jml').text(res.jumlah);
                    $('span#start').text(res.start);
                    $('span#end').text(res.end);
                }
            })
            $('.scan').empty();
            tableModal(prd, id)
        });

        function tableModal(prd, jadwal) {
            var table = $('.scan-produk').DataTable({
                'destroy': true,
                paging: true,
                retrieve: false,
                processing: true,
                "autoWidth": false,
                ordering: false,
                ajax: "/api/prd/detailSeri1/" + prd + '/' + jadwal,
                columns: [{
                        data: "no_seri"
                    },
                    {
                        data: "no_seri"
                    },
                    // {
                    //     data: "id",
                    //     render: function(data, type, row) {
                    //         return '<button type="button" class="btn btn-outline-primary btn-sm detail" data-id="' +
                    //             data + '" data-seri="' + row.no_seri +
                    //             '"><i class="fas fa-edit"></i></button>&nbsp;<button type="button" class="btn btn-outline-danger btn-sm delete" data-id="' +
                    //             data + '" data-seri="' + row.no_seri +
                    //             '"><i class="fas fa-trash"></i></button>'
                    //     }
                    // }
                ],
                columnDefs: [{
                    targets: [0],
                    checkboxes: {
                        selectRow: true,
                        selectAllPages: false,
                    },
                    width: "5%"
                }, ],
                select: {
                    style: 'multi'
                },
            });
            $('#form-scan').on('submit', function(e) {
                e.preventDefault();
                var table = $('.scan-produk').DataTable();
                const check_seri_length = [];

                table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                    var data = this.data();
                    var checkbox = $(this.node()).find('input[type="checkbox"]');
                    if (checkbox.prop('checked')) {
                        check_seri_length.push(data.noseri);
                    }
                });

                Swal.fire({
                    title: 'Apakah Kamu Yakin?',
                    text: "Melakukan Perakitan di Tanggal " + new Date($('#tgl_perakitan').val())
                        .toLocaleDateString('id-ID', {
                            year: "numeric",
                            month: "long",
                            day: "numeric"
                        }) + " Sejumlah " + check_seri_length.length + " Nomor seri",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, transfer it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var form = $(this);
                        $('#saveButton').prop('disabled', true);
                        Swal.fire({
                            title: 'Please wait',
                            text: 'Data is transferring...',
                            allowOutsideClick: false,
                            showConfirmButton: false
                        });

                        const seri = [];

                        table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                            var data = this.data();
                            var checkbox = $(this.node()).find('input[type="checkbox"]');
                            if (checkbox.prop('checked')) {
                                seri.push(data.noseri);
                            }
                        });

                        if (seri.length > 0) {
                            let tgl = $('#tgl_perakitan').val();
                            let today = new Date();
                            let time = today.getHours() + ":" + today.getMinutes() + ":" + today
                                .getSeconds();
                            let datetime = tgl + ' ' + time;
                            $.ajax({
                                url: "/api/prd/send",
                                type: "post",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    tujuan: $('#tujuanGudang').val(),
                                    userid: $('#userid').val(),
                                    qty: jumlah,
                                    gbj_id: prd,
                                    jadwal_id: id,
                                    noseri: seri,
                                    tgl_transfer: datetime,
                                },
                                success: function(res) {
                                    // console.log(res);
                                    Swal.fire(
                                        'Berhasil!',
                                        'Data berhasil di transfer!',
                                        'success'
                                    ).then(function() {
                                        location.reload();
                                    });
                                }
                            })
                            console.log("seri", seri);
                        } else {
                            Swal.fire(
                                'Error!',
                                'Tidak ada data yang dipilih',
                                'error'
                            )
                        }
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire('Changes are not saved', '', 'info')
                    }
                })


            });
        }

        function dateFormat(inputDate, format) {
            //parse the input date
            const date = new Date(inputDate);

            //extract the parts of the date
            const day = date.getDate();
            const month = date.getMonth() + 1;
            const year = date.getFullYear();

            //replace the month
            format = format.replace("MM", month.toString().padStart(2, "0"));

            //replace the year
            if (format.indexOf("yyyy") > -1) {
                format = format.replace("yyyy", year.toString());
            } else if (format.indexOf("yy") > -1) {
                format = format.replace("yy", year.toString().substr(2, 2));
            }

            //replace the day
            format = format.replace("dd", day.toString().padStart(2, "0"));

            return format;
        }

        // Produksi Checkbox
        $(document).on('click', '#checked', function() {
            const check_seri = [];

            var table = $('.scan-produk').DataTable();
            // check datatable checkbox is true
            table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                var data = this.data();
                var checkbox = $(this.node()).find('input[type="checkbox"]');
                if (checkbox.prop('checked')) {
                    check_seri.push(data.noseri);
                }
            });

            $('#no_seri').text(check_seri.length);
        });

        // Hapus Seri
        $(document).on('click', '#hapusSeri', function() {
            var table = $('.scan-produk').DataTable();
            var rows_select = table.column(0).checkboxes.selected();
            const check_seri = [];

            $.each(rows_select, function(index, rowId) {
                check_seri.push(rowId);
            });
            console.log(check_seri);
            Swal.fire({
                title: 'Are you sure?',
                text: "Hapus Noseri " + check_seri + " ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "/api/prd/deleteAll",
                        data: {
                            noseri: check_seri,
                            jadwal_id: id,
                        },
                        success: function(response) {
                            console.log(response);
                            Swal.fire(
                                'Berhasil!',
                                'Data berhasil di hapus!',
                                'success'
                            ).then(function() {
                                location.reload();
                            });
                        }
                    });
                }
            })
        });
        // Produksi Delete
        $(document).on('click', '.delete', function() {
            const noseriid = $(this).data('id');
            const noseri = $(this).data('seri');
            console.log(id);

            Swal.fire({
                title: 'Are you sure?',
                text: "Hapus Noseri " + noseri + " ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/api/prd/delete",
                        data: {
                            noseriid: noseriid,
                            jadwal_id: id,
                        },
                        type: "post",
                        success: function(res) {
                            console.log(res);
                            Swal.fire(
                                'Deleted!',
                                res.msg,
                                'success'
                            ).then(function() {
                                location.reload();
                            });
                        }
                    })


                }
            })
        })

        $(document).on('click', '.detail', function() {
            const id = $(this).data('id');
            const noseri = $(this).data('seri');
            $('#noseriselect').val(noseri);
            $('#idNoseri').val(id);
            $('.modalSeri').modal('show');
        })

        $(document).on('click', '.submitNoSeri', function() {
            const noseriid = $('#idNoseri').val();
            // console.log(noseriid);
            const noseri = $('#noseriselect').val();
            $.ajax({
                type: "post",
                url: "/api/prd/cekUpdateNoseri",
                data: {
                    noseri: noseri,
                },
                success: function(response) {
                    console.log(response);
                    if (response.error == true) {
                        Swal.fire(
                            'Oops...',
                            response.msg,
                            'error'
                        )
                    } else {
                        $.ajax({
                            url: "/api/prd/updateRakitseri",
                            data: {
                                noseriid: noseriid,
                                noseri: noseri,
                                jadwal_id: id,
                            },
                            type: "post",
                            success: function(res) {
                                console.log(res);
                                Swal.fire(
                                    'Updated!',
                                    res.msg,
                                    'success'
                                ).then(function() {
                                    location.reload();
                                });
                            }
                        })
                    }
                }
            });
        });

        // Transfer Sisa Produk
        $(document).on('click', '.detailmodalTransfer', function() {
            $('#jwdid').val($(this).data('id'));
            const jml_sisa = $(this).data('jml');
            const id = $(this).data('id');
            $('span#jml1').text(jml_sisa + ' Unit');
            $.ajax({
                url: "/api/prd/headerSeri/" + id,
                type: "get",
                dataType: "json",
                success: function(res) {
                    $('span#no_bppb1').text(res.bppb);
                    $('span#produk1').text(res.produk);
                    $('span#kategori1').text(res.kategori);
                    $('span#start1').text(res.start);
                    $('span#end1').text(res.end);
                }
            })
            $('.modalTranfer').modal('show');
        });

        $('body').on('submit', '#formClose', function(e) {
            e.preventDefault();

            if ($('#keterangan_transfer').val() == '') {
                Swal.fire(
                    'Error!',
                    'Keterangan tidak boleh kosong',
                    'error'
                )
                return false;
            }

            var formData = new FormData(this);

            Swal.fire({
                title: 'Are you sure?',
                text: "Transfer Sisa Produk ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Transfer it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Please wait',
                        text: 'Data is transferring...',
                        allowOutsideClick: false,
                        showConfirmButton: false
                    });
                    $('#btnCloseSimpan').html('Sending..');
                    $(this).prop('disabled', true);
                    $.ajax({
                        url: "/api/tfp/closeTransfer",
                        type: "post",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: (data) => {
                            console.log(data);
                            Swal.fire(
                                'Updated!',
                                data.msg,
                                'success'
                            ).then(function() {
                                location.reload();
                            });
                        }
                    })
                }
            })
        })
    </script>
@stop
