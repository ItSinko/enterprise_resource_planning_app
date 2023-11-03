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
    <link rel="stylesheet" href="{{ asset('vendor/apexcharts/dist/apexcharts.css') }}">
    <script src="{{ asset('vendor/apexcharts/dist/apexcharts.min.js') }}"></script>
    <input type="hidden" name="" id="auth" value="{{ Auth::user()->divisi_id }}">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Jadwal Perakitan</h1>
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
                                {{-- <a id="produk-tab" data-toggle="tab" href="#produk" role="tab" aria-controls="produk"
                                aria-selected="false">Produk</a> --}}
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
                    <input type="hidden" name="userid" id="userid" value="{{ Auth::user()->id }}">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-layer-group"></i> Tabel Perakitan Produk
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-bordered" id="table_produk_perakitan">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th rowspan="2">Periode</th>
                                                <th colspan="2" class="text-center">Tanggal</th>
                                                <th rowspan="2">No BPPB</th>
                                                <th rowspan="2">Produk</th>
                                                <th rowspan="2">Jumlah Rakit</th>
                                                <th rowspan="2">Aksi</th>
                                                <th rowspan="2">Created_at</th>
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
            <div class="tab-pane fade" id="produk" role="tabpanel" aria-labelledby="produk-tab">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"><i class="fas fa-cog"></i> Perakitan Produk Bulan
                                {{ Carbon\Carbon::now()->isoFormat('MMMM') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Perakitan-->
    <div class="modal fade modalRakit">
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
                                    {{-- <div class="card" style="background-color: #C8E1A7">
                                    <div class="card-body">
                                        <span id="bppb">89798797856456</span>
                                    </div>
                                </div> --}}
                                    <div class="card">
                                        <div class="card-body">
                                            <input type="text" name="no_bppb" id="no_bppb" class="form-control"
                                                style="text-transform:uppercase">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Nama Produk</label>
                                    <div class="card" style="background-color: #F89F81">
                                        <div class="card-body">
                                            <span id="produk"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Kategori</label>
                                    <div class="card" style="background-color: #FCF9C4">
                                        <div class="card-body">
                                            <span id="kategori"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Jumlah Rakit</label>
                                    <div class="card" style="background-color: #FFCC83">
                                        <div class="card-body">
                                            <span id="jml"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Tanggal Mulai</label>
                                    <div class="card" style="background-color: #FFE0B4">
                                        <div class="card-body">
                                            <span id="start"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Tanggal Selesai</label>
                                    <div class="card" style="background-color: #FFECB2">
                                        <div class="card-body">
                                            <span id="end"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex bd-highlight mb-3">
                                <div class="p-2 bd-highlight">
                                    <div class="form-group">
                                        <label for="">Tanggal Perakitan</label>
                                        <input type="text" class="form-control" id="tgl_perakitan"
                                            name="tgl_perakitan" placeholder="Tanggal Perakitan">
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
                                    <table class="table table-striped scan-produk" id="scan">
                                        <thead>
                                            <tr>
                                                <th>Nomor Seri</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            No Seri Yang Diisi : <span id="no_seri"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnCheck" class="btn btn-info">Check</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" id="btnSave" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    @include('page.produksi.noseriviatextperakitangenerate')

    <!-- Modal -->
    <div class="modal fade modalDetailTransfer">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Nama Produk</label>
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            <span id="produkTransfer"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm">
                                    <label for="">Jumlah Transfer</label>
                                    <div class="card" style="background-color: #F89F81">
                                        <div class="card-body">
                                            <span id="jmlTransfer"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Tanggal Mulai</label>
                                    <div class="card" style="background-color: #FFE0B4">
                                        <div class="card-body">
                                            <span id="tglMulaiTransfer"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Tanggal Selesai</label>
                                    <div class="card" style="background-color: #FFECB2">
                                        <div class="card-body">
                                            <span id="tglSelesaiTransfer"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="" id="transferForm" name="transferForm">
                            <input type="hidden" name="jadwal_id" id="jwdid">
                            <div class="card-body">
                                <label for="">Keterangan</label>
                                <div class="form-group">
                                    <textarea name="keterangan" id="keteranganTransferSisa" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary detailtransferKirim">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade modalEvaluasiPerakitan">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Evaluasi Perakitan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Nama Produk</label>
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            <span id="produkEvaluasi"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm">
                                    <label for="">Jumlah Transfer</label>
                                    <div class="card" style="background-color: #F89F81">
                                        <div class="card-body">
                                            <span id="jmlEvaluasi"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Tanggal Mulai</label>
                                    <div class="card" style="background-color: #FFE0B4">
                                        <div class="card-body">
                                            <span id="tglMulaiEvaluasi"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Tanggal Selesai</label>
                                    <div class="card" style="background-color: #FFECB2">
                                        <div class="card-body">
                                            <span id="tglSelesaiEvaluasi"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="" id="evaluasiForm" name="evaluasiForm">
                            <input type="hidden" name="jadwal_id" id="jwdid1">
                            <input type="hidden" name="created_by" value="{{ Auth::user()->id }}">
                            <div class="card-body">
                                <label for="">Keterangan</label>
                                <div class="form-group">
                                    <textarea name="evaluasi" id="keteranganEvaluasi" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="submit" class="btn btn-primary detailEvaluasiKirim">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('adminlte_js')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js">
    </script>
    <script>
        var access_token = localStorage.getItem('lokal_token');
        $(document).ready(function() {
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
            cal_jadwal();

            $('#profile-tab').on('click', function() {
                tab_jadwal();
            })

            $("#head-cb").on('click', function() {
                var isChecked = $("#head-cb").prop('checked')
                $('.cb-child').prop('checked', isChecked)
            });
            let date = new Date();
            $('#tgl_perakitan').val(date.toISOString().substr(0, 10));
        })

        function modalRakit() {

            $('.modalRakit').modal('show');
            $("#head-cb").on('click', function() {
                var isChecked = $("#head-cb").prop('checked')
                $('.cb-child').prop('checked', isChecked)
            });
        }

        function transfer() {
            Swal.fire({
                title: "Apakah anda yakin?",
                text: "Data yang sudah di transfer tidak dapat diubah!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            });
        };

        function cal_jadwal() {
            var date = new Date()
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear()

            var Calendar = FullCalendar.Calendar;
            var calendarEl = document.getElementById('calendar');

            var calendar = new Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                weekends: false,
                locale: 'id',
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: "/api/prd/ongoing-cal",
                        type: "post",
                        dataType: "json",
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                        },
                        success: function(res) {
                            var events = [];
                            if (res != null) {
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
                            // console.log('events', events);
                            successCallback(events);
                        }
                    })
                }
            });

            calendar.render();
        }

        function tab_jadwaldestroy() {
            $('#table_produk_perakitan').DataTable().clear().destroy();
            $('.scan-produk').DataTable().clear().destroy();
        }

        function tab_jadwal() {
            $('#table_produk_perakitan').DataTable({
                destroy: true,
                processing: true,
                ordering: false,
                ajax: {
                    url: "/api/prd/ongoing",
                    type: "post",
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
                        data: 'no_bppb'
                    },
                    {
                        data: "produk"
                    },
                    {
                        data: "jml"
                    },
                    {
                        data: "action"
                    },
                    {
                        data: "created_at",
                        visible: false
                    }
                ],
                order: [
                    [6, 'desc']
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                },
                "lengthChange": false,
                "ordering": true,
                "columnDefs": [{
                    "targets": [4],
                    "visible": document.getElementById('auth').value == '2' ? false : true
                }]
            });
            $('#table_produk_perakitan').css("width", "100%");
            var id = '';
            $(document).on('click', '.detailmodal', function() {
                $('#tgl_perakitan').daterangepicker({
                    singleDatePicker: true,
                    minYear: 1901,
                    maxYear: parseInt(moment().format('YYYY'), 10),
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                });
                id = $(this).data('id');
                console.log(id);
                var jml = $(this).data('jml');
                console.log(jml);
                $.ajax({
                    url: "/api/prd/ongoing/h/" + id,
                    dataType: "json",
                    type: "get",
                    success: function(res) {
                        $('#no_bppb').val(res.no_bppb);
                        $('span#produk').text(res.produk);
                        $('span#kategori').text(res.kategori);
                        $('span#jml').text(res.jml);
                        $('span#start').text(res.start);
                        $('span#end').text(res.end);
                    }
                })

                $('.modalRakit').modal('show');

                $('.scan-produk').DataTable().destroy();
                $('.scan-produk tbody').empty();

                var $table = $(".scan-produk");
                for (var i = 0; i < jml; i++) {
                    var $row = $table.find("tbody").append("<tr></tr>").children("tr:eq(" + i + ")");
                    for (var k = 0; k < 1; k++) {
                        $row.append(
                            '<td><input type="text" name="noseri[]" class="form-control noseri" style="text-transform:uppercase" maxlength=""><div class="invalid-feedback">Nomor seri ada yang sama.</div></td>'
                        );
                    }
                }
                var scanProduk = $('.scan-produk').DataTable({
                    scrollY: '200px',
                    scrollCollapse: true,
                    paging: false,
                    ordering: false,
                    searching: false,
                    "lengthChange": false,
                    fixedHeader: true,
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                    },
                });

                $(document).on('click', '#btnSave', function(e) {
                    if ($('#no_bppb').val().length <= 10) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning',
                            text: 'Nomor BPPB Minimal 10 Karakter.',
                        })
                    } else {
                        e.preventDefault();
                        let arr = [];
                        const data = scanProduk.$('.noseri').map(function() {
                            return $(this).val();
                        }).get();

                        data.forEach(function(item) {
                            if (item != '') {
                                arr.push(item);
                            }
                        })

                        const count = arr =>
                            arr.reduce((a, b) => ({
                                ...a,
                                [b]: (a[b] || 0) + 1
                            }), {})

                        const duplicates = dict =>
                            Object.keys(dict).filter((a) => dict[a] > 1)

                        Swal.fire({
                            title: 'Apakah Kamu Yakin?',
                            text: "Melakukan Perakitan di Tanggal " + new Date($('#tgl_perakitan')
                                .val()).toLocaleDateString('id-ID', {
                                year: "numeric",
                                month: "long",
                                day: "numeric"
                            }) + " Sejumlah " + arr.length + " Nomor seri",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, save it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Please wait',
                                    text: 'Data is transferring...',
                                    allowOutsideClick: false,
                                    showConfirmButton: false
                                });
                                $(this).prop('disabled', true);

                                if (duplicates(count(arr)).length > 0) {
                                    $('.noseri').removeClass('is-invalid');
                                    $('.noseri').filter(function() {
                                        for (let index = 0; index < duplicates(count(arr))
                                            .length; index++) {
                                            if ($(this).val() == duplicates(count(arr))[
                                                    index]) {
                                                return true;
                                            }
                                        }
                                    }).addClass('is-invalid');

                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Nomor seri ' + duplicates(count(arr)) +
                                            ' ada yang sama.',
                                    }).then((result) => {
                                        if (result.value) {
                                            $(this).prop('disabled', false);
                                        }
                                    });
                                } else {
                                    $('.noseri').removeClass('is-invalid');
                                    $.ajax({
                                        url: "/api/prd/cek-noseri",
                                        type: "post",
                                        data: {
                                            noseri: arr,
                                        },
                                        success: function(res) {
                                            console.log(res);
                                            if (res.error == true) {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Oops...',
                                                    text: res.msg,
                                                }).then((result) => {
                                                    if (result.value) {
                                                        $('#btnSave').prop(
                                                            'disabled',
                                                            false);
                                                    }
                                                });
                                            } else {
                                                let tgl = $('#tgl_perakitan').val();
                                                let today = new Date();
                                                let time = today.getHours() + ":" +
                                                    today.getMinutes() + ":" + today
                                                    .getSeconds();
                                                let datetime = tgl + ' ' + time;
                                                // console.log('a');
                                                $.ajax({
                                                    url: "/api/prd/rakit-seri",
                                                    type: "post",
                                                    data: {
                                                        "_token": "{{ csrf_token() }}",
                                                        no_bppb: $('#no_bppb')
                                                            .val(),
                                                        noseri: arr,
                                                        userid: $('#userid')
                                                            .val(),
                                                        jadwal_id: id,
                                                        tgl_perakitan: datetime,
                                                    },
                                                    success: function(res) {
                                                        console.log(res);
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Berhasil',
                                                            text: 'Data berhasil disimpan.',
                                                        })
                                                        $('.modalRakit')
                                                            .modal('hide');
                                                        $('.scan-produk')
                                                            .DataTable()
                                                            .destroy();
                                                        $('.scan-produk tbody')
                                                            .empty();
                                                        $('#table_produk_perakitan')
                                                            .DataTable()
                                                            .ajax
                                                            .reload();
                                                        location.reload();
                                                    }
                                                })
                                            }
                                        }
                                    })
                                }
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                Swal.fire('Changes are not saved', '', 'info')
                            }
                        })
                    }


                })

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

                $('#btnCheck').click(function() {
                    let arr = [];
                    const data = scanProduk.$('.noseri').map(function() {
                        return $(this).val();
                    }).get();

                    data.forEach(function(item) {
                        if (item != '') {
                            arr.push(item);
                        }
                    })

                    const count = arr =>
                        arr.reduce((a, b) => ({
                            ...a,
                            [b]: (a[b] || 0) + 1
                        }), {})

                    const duplicates = dict =>
                        Object.keys(dict).filter((a) => dict[a] > 1)

                    if (duplicates(count(arr)).length > 0) {
                        $('.noseri').removeClass('is-invalid');
                        $('.noseri').filter(function() {
                            for (let index = 0; index < duplicates(count(arr))
                                .length; index++) {
                                if ($(this).val() == duplicates(count(arr))[index]) {
                                    return true;
                                }
                            }
                        }).addClass('is-invalid');

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Nomor seri ' + duplicates(count(arr)) +
                                ' ada yang sama.',
                        }).then((result) => {
                            if (result.value) {
                                $(this).prop('disabled', false);
                            }
                        });
                    } else {
                        $('#no_seri').text(arr.length);
                    }
                })
            });

            // Produksi Tab
            $('.modalRakit').on('shown.bs.modal', function() {
                const table = $('.scan-produk').DataTable()
                // focus on the first input
                setTimeout(() => {
                    $('input.noseri').eq(0).focus()
                }, 100);
            })
            $(document).on('keydown', 'input.noseri', function(e) {
                // jika tombol enter ditekan maka akan focus ke input berikutnya
                if (e.keyCode == 13) {
                    e.preventDefault();
                    $(this).parent().parent().next().find('input.noseri').focus();
                }

                // const a = $(this).val();
                // const b = a.charAt(0);
                // const regex = /^[a-zA-Z\s]*$/;
                // const length = $('#lengthNoSeri').val();
                // $(this).attr('maxlength', length);
                // if (e.keyCode == 8) {} else if (!regex.test(b)) {
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Oops...',
                //         text: 'Nomor seri harus berupa huruf.',
                //     }).then((result) => {
                //         if (result.value) {
                //             $(this).val('');
                //             $(this).focus();
                //         }
                //     });
                // } else if (a.length == length) {
                //     $(this).parent().parent().next().find('input.noseri').focus();
                // } else if (a.length > length) {
                //     $(this).val(a.substring(0, length));
                //     $(this).parent().parent().next().find('input.noseri').focus();
                // }
            });

            $("#lengthNoSeri").on("change paste keyup", function(e) {
                let length = $(this).val()
                $('.noseri').attr('maxlength', length)
            });

            // Sisa Transfer Produk
            $(document).on('click', '.detailtransfer ', function() {
                $('#jwdid').val($(this).data('id'));
                const id = $(this).data('id');
                const prd = $(this).data('prd');
                const jml = $(this).data('jml');
                const tglmulai = $(this).parent().prev().prev().prev().prev().prev().text();
                const tglselesai = $(this).parent().prev().prev().prev().prev().find('span.tanggal').text();
                console.log("tglselesai", tglselesai);
                $('#produkTransfer').text(prd);
                $('#jmlTransfer').text(jml + ' Unit');
                $('#tglMulaiTransfer').text(tglmulai);
                $('#tglSelesaiTransfer').text(tglselesai);
                $('.modalDetailTransfer').modal('show');
                console.log($(this).data('id'));
            });

            $(document).on('click', '.evaluasirakit', function() {
                const id = $(this).data('id');
                $('#jwdid1').val(id);
                const prd = $(this).parent().prev().prev().text();
                const jml = $(this).data('jml');
                const produk = $(this).data('produk');
                const eval = $(this).data('eval');
                const tglmulai = $(this).parent().prev().prev().prev().prev().prev().text();
                const tglselesai = $(this).parent().prev().prev().prev().prev().find('span.tanggal').text();
                $('#produkEvaluasi').text(prd);
                $('#jmlEvaluasi').text(jml + ' Unit');
                $('#tglMulaiEvaluasi').text(tglmulai);
                $('#tglSelesaiEvaluasi').text(tglselesai);
                $('#keteranganEvaluasi').text(eval);
                $('.modalEvaluasiPerakitan').modal('show');
            })

            $('body').on('submit', '#transferForm', function(e) {
                e.preventDefault();
                var actionType = $('.detailtransferKirim').val();
                $('.detailtransferKirim').html('Sending..');
                var formData = new FormData(this);
                const keterangan = $('#keteranganTransferSisa').val();
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: 'Data akan ditransfer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Kirim!'
                }).then((result) => {
                    if (result.value) {
                        $(this).prop('disabled', true);
                        Swal.fire({
                            title: 'Please wait',
                            text: 'Data is transferring...',
                            allowOutsideClick: false,
                            showConfirmButton: false
                        });
                        $.ajax({
                            url: "/api/tfp/closeRakit",
                            type: "post",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: (data) => {
                                console.log(data);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data berhasil dikirim.',
                                })
                                $('.modalDetailTransfer').modal('hide');
                                $('#table_produk_perakitan').DataTable().ajax
                                    .reload();
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            }
                        })
                    }
                })
            })

            $('body').on('submit', '#evaluasiForm', function(e) {
                e.preventDefault();
                var actionType = $('.detailEvaluasiKirim').val();
                $('.detailEvaluasiKirim').html('Sending..');
                var formData = new FormData(this);
                const keterangan = $('#keteranganEvaluasi').val();
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: 'Data akan ditransfer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Kirim!'
                }).then((result) => {
                    if (result.value) {
                        $(this).prop('disabled', true);
                        Swal.fire({
                            title: 'Please wait',
                            text: 'Data is transferring...',
                            allowOutsideClick: false,
                            showConfirmButton: false
                        });
                        $.ajax({
                            url: "/api/v2/prd/telat_rakit",
                            type: "post",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: (data) => {
                                console.log(data);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data berhasil dikirim.',
                                })
                                $('.modalEvaluasiPerakitan').modal('hide');
                                $('#table_produk_perakitan').DataTable().ajax
                                    .reload();
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            }
                        })
                    }
                })
            })
        }
    </script>
@stop
