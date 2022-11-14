@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    #DataTables_Table_0_filter{
        display: none;
    }
    #DataTables_Table_1_filter{
        display: none;
    }
</style>
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Riwayat Perakitan</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <nav>
                    <div class="nav nav-tabs topnav" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab"
                            aria-controls="nav-home" aria-selected="true">Riwayat Perakitan</a>
                        <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab"
                            aria-controls="nav-profile" aria-selected="false">Transfer Lain Lain</a>
                        {{-- <a class="nav-link" id="nav-produk-tab" data-toggle="tab" href="#nav-produk" role="tab"
                            aria-controls="nav-produk" aria-selected="false">Per Produk</a> --}}
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">Pilih Produk</label>
                                    <select name="" id="produk_select" class="form-control produk_select" multiple>
                                        <option value="" selected="selected">All Produk</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">Tanggal Perakitan</label>
                                    <input type="text" name="" id="" class="form-control daterange">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-6">
                                <h3 class="font-weight-bold" id="h_rakit">{{ $rakit }}</h3>
                                <h4 class="font-weight-normal text-muted">Perakitan</h4>
                            </div>
                            <div class="col-6">
                                <h3 class="font-weight-bold" id="h_unit">{{ $unit }}</h3>
                                <h4 class="font-weight-normal text-muted">Unit Dirakit</h4>
                            </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-history">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal Perakitan</th>
                                    <th>Waktu Perakitan</th>
                                    <th>Nomor BPPB</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                    <th>filter_rakit</th>
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
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="">Pilih Produk</label>
                            <select name="" id="produk_select2" class="form-control produk_select2" multiple>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="">Tanggal Masuk</label>
                            <input type="text" name="" id="" class="form-control daterange3">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="">Tanggal Keluar</label>
                            <input type="text" name="" id="" class="form-control daterange4">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h3 class="font-weight-bold" id="h_rakit">{{ $rakit }}</h3>
                        <h4 class="font-weight-normal text-muted">Perakitan</h4>
                    </div>
                    <div class="col-6">
                        <h3 class="font-weight-bold" id="h_unit">{{ $unit }}</h3>
                        <h4 class="font-weight-normal text-muted">Unit Dirakit</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table tableLain">
                    <thead class="thead-light">
                        <tr>
                            <th colspan="2" class="text-center">Tanggal</th>
                            <th rowspan="2">Nomor BPPB</th>
                            <th rowspan="2">Produk</th>
                            <th colspan="2" class="text-center">Jumlah</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr class="text-center">
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Rakit</th>
                            <th>Sisa</th>
                            <th>filter_rakit</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- <div class="tab-pane fade" id="nav-produk" role="tabpanel" aria-labelledby="nav-profile-tab">
        <div class="card">
            <div class="card-body">
                <table class="table tableDetailProduk">
                    <thead class="thead-light">
                        <tr>
                            <th>Tanggal Perakitan</th>
                            <th>No BPPB</th>
                            <th>Nama Produk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Jumat, 3 Des 2021</td>
                            <td>BPPB/JKN01-00</td>
                            <td>DIGIT ONE BACKLIGHT ABU</td>
                            <td><button class="btn btn-outline-secondary btnClickDetailProduk"><i class="fas fa-eye"></i> Detail</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
  </div>

<!-- Modal -->
<div class="modal fade modal_id" id="modal_id" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Tanggal Perakitan</label>
                                <div class="card-group">
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            <p class="card-text" id="d_rakit">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Waktu Perakitan</label>
                                <div class="card-group">
                                    <div class="card" style="background-color: #FFECB2">
                                        <div class="card-body">
                                            <p class="card-text" id="t_rakit">Selasa 11-04-2021</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Nomor BPPB</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body" id="bppb">
                                        -
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #FCF9C4">
                                    <div class="card-body" id="produk">
                                        -
                                    </div>
                                  </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah</label>
                                <div class="card" style="background-color: #FFCC83">
                                    <div class="card-body" id="jml">
                                        -
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped scan-produk">
                                    <thead>
                                        <tr>
                                            <th>Nomor Seri</th>
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

<!-- Modal -->
<div class="modal fade modalTableProduk" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm">
                                <label for="">No BPPB</label>
                                <div class="card" style="background-color: #C8E1A7">
                                    <div class="card-body">
                                        <span id="no_bppb"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body">
                                        <span id="nama_produk"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-history-produk">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal Perakitan</th>
                                    <th>Waktu Perakitan</th>
                                    <th>Tanggal Pengiriman</th>
                                    <th>Waktu Pengiriman</th>
                                    <th>Jumlah</th>
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

<!-- Modal Transfer Lain-->
<div class="modal fade modalTransferLain" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
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
                                    <div class="card" style="background-color: #F89F81">
                                        <div class="card-body" id="bppb1">
                                            516546546546546
                                        </div>
                                      </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Nama Produk</label>
                                    <div class="card" style="background-color: #FCF9C4">
                                        <div class="card-body" id="produk1">
                                            Produk 1
                                        </div>
                                      </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Jumlah</label>
                                    <div class="card" style="background-color: #FFCC83">
                                        <div class="card-body" id="jml1">
                                            100 Unit
                                        </div>
                                      </div>
                                </div>
                            </div>
                        </div>
                    <div class="card-body">
                        <label for="">Keterangan</label>
                        <textarea name="" class="form-control keterangan1" id="" cols="5" rows="5" disabled>Keterangan</textarea>
                        <hr>
                        <table class="table tableNoseri">
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
        </div>
    </div>
</div>

{{-- Modal Per Produk --}}

<div class="modal fade modalRakitProduk" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
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
                                    <div class="card" style="background-color: #F89F81">
                                        <div class="card-body" id="bppb1">
                                            516546546546546
                                        </div>
                                      </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Nama Produk</label>
                                    <div class="card" style="background-color: #FCF9C4">
                                        <div class="card-body" id="produk1">
                                            Produk 1
                                        </div>
                                      </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Jumlah</label>
                                    <div class="card" style="background-color: #FFCC83">
                                        <div class="card-body" id="jml1">
                                            100 Unit
                                        </div>
                                      </div>
                                </div>
                            </div>
                        </div>
                    <div class="card-body">
                        <table class="table tableNoseriProduk">
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
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
        // Tanggal Perakitan
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
    var DateFilterFunction = (function (oSettings, aData, iDataIndex) {
        var dateStart = parseDateValue(start_date);
        var dateEnd = parseDateValue(end_date);

        var evalDate = parseDateValue(aData[6]);
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
    $('.produk_select').select2({});
    $.ajax({
        type: "get",
        url: "/api/prd/product_his_rakit",
        success: function (response) {
            $.each(response, function (a,b) {
                 $('.produk_select').append('<option value="'+b+'">'+b+'</option>');
            });
        }
    });
    $(document).on('click', '.detail', function() {
        console.log("test");
        var id = $(this).data('id');
        var time = $(this).data('tf');
        var rakit = $(this).data('rakit');
        console.log($(this).parent().prev().prev().prev().prev().prev().html());
        $('p#d_rakit').text($(this).parent().prev().prev().prev().prev().prev().html());
        $('p#t_rakit').text($(this).parent().prev().prev().prev().prev().html());
        $('div#bppb').text($(this).parent().prev().prev().prev().html());
        $('div#produk').text($(this).parent().prev().prev().html());
        $('div#jml').text($(this).parent().prev().html());
        var table_perakitan = $('.scan-produk').DataTable({
            destroy: true,
            "ordering":false,
            "autoWidth": false,
            "pageLength": 10,
            processing: true,
            ajax: {
                url: "/api/prd/riwayat_seri_rakit/" + id + "/" + rakit,
            },
            columns: [
                {data: 'no_seri'}
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });

        $('#modal_id').modal('show');
    });
    // var groupCol = [0,2];
    var table = $('.table-history').DataTable({
        "columnDefs": [
            { "visible": false, "targets": 6 }
        ],
        destroy: true,
        "lengthChange": false,
        "ordering": false,
        "info": false,
        "search": {
            "regex": true
        },
        "responsive": true,
        "order": [[ 0, 'asc' ]],
        ajax: {
            url: "/api/prd/riwayat_rakit",
            type: "post",
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            beforeSend : function(xhr){
                xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
            },
        },
        columns: [
            {data: 'day_rakit'},
            {data: 'time_rakit'},
            {data: 'bppb'},
            {data: 'produk'},
            {data: 'jml'},
            {data: 'action'},
            {data: 'day_rakit_filter'},
        ],
        // "drawCallback": function ( settings ) {
        //     var api = this.api();
        //     var rows = api.rows( {page:'current'} ).nodes();
        //     var last=null;

        //     api.column(groupCol, {page:'current'} ).data().each( function ( group, i, $currTable ) {
        //         // console.log(group);
        //         if (last !== group) {
        //             var rowData = api.row(i).data();


        //             $(rows).eq(i).before(
        //                 // console.log(rows[i].children[2].textContent)
        //             '<tr class="table-dark text-bold"><td colspan="1">' + group + '</td><td colspan="5">'+rows[i].children[2].textContent+'</td></tr>'
        //         );
        //             last = group;
        //         }
        //     });
        // },
        autoWidth: false,
        processing: true,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        },
    });

    $('.produk_select').change(function() {
        var search = [];

        $.each($('.produk_select option:selected'), function () {
            let val = $.fn.dataTable.util.escapeRegex($(this).val());
            search.push(val);
        });
        search = search.join('|');
        table.column(3).search(search, true, false).draw();
    });

    // Tanggal Perakitan
    $('.daterange').daterangepicker({
            autoUpdateInput: false
        });

        $('.daterange').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format(
                'DD-MM-YYYY'));
            start_date = picker.startDate.format('DD-MM-YYYY');
            end_date = picker.endDate.format('DD-MM-YYYY');
            $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
            table.draw();
        });

        $('.daterange').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            start_date = '';
            end_date = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
            table.draw();
        });

        // Tabel PerProduk
        // $('.tablePerProduk').DataTable({
        //     destroy: true,
        //     "autoWidth": false,
        //     processing: true,
        //     ajax: {
        //         url: "/api/prd/ajax_perproduk",
        //     },
        //     columns: [
        //         {data: 'no_bppb'},
        //         {data: 'produk'},
        //         {data: 'aksi',
        //             "render": function ( data, type, row, meta ) {
        //                 return '<button class="btn btn-sm btn-outline-secondary buttonModalProduk" data-id="'+data+'" data-bppb="'+row.no_bppb+'" data-produk="'+row.produk+'"><i class="far fa-eye"></i> Detail</button>';
        //             }
        //         },
        //     ],
        //     "language": {
        //         "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        //     }
        // });

        // $(document).on('click','.buttonModalProduk', function () {
        //     var id = $(this).data('id');
        //     var bppb = $(this).data('bppb');
        //     var produk = $(this).data('produk');
        //     $('#no_bppb').text(bppb);
        //     $('#nama_produk').text(produk);
        //     var table = $('.table-history-produk').DataTable({
        //         destroy: true,
        //         "lengthChange": false,
        //         "autoWidth": false,
        //         "info": false,
        //         "responsive": true,
        //         "order": [[ 0, 'asc' ], [2, 'asc']],
        //         ajax: {
        //             url: "/api/prd/detail_perproduk/"+id,
        //         },
        //         columns: [
        //             {data: 'day_rakit'},
        //             {data: 'time_rakit'},
        //             {data: 'day_kirim'},
        //             {data: 'time_kirim'},
        //             {data: 'jml'},
        //         ],
        //         autoWidth: false,
        //         processing: true,
        //         "language": {
        //             "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        //         },
        //     });
        //     $('.modalTableProduk').modal('show');
        // });

        // Transfer Lain lain
        const produkTable = [];
        var table2 = $('.tableLain').DataTable({
            destroy: true,
            "autoWidth": false,
            processing: true,
            lengthChange: false,
            ajax: {
                url: "/api/prd/ajax_sisa",
                type: "post",
                beforeSend : function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                },
            },
            columns: [
                {data: 'start'},
                {data: 'end'},
                {data: 'no_bppb'},
                {data: 'produk'},
                {data: 'jml_rakit'},
                {data: 'jml_sisa'},
                {data: 'aksi'},
                {data: 'start_filter'},
                {data: 'end_filter'},
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
            columnDefs : [
                {
                    targets : [7,8],
                    visible : false
                }
            ],
            initComplete: function () {
                this.api().columns(3).data().unique().sort().each( function ( d, j ) {
                    $('#produk_select2').select2({
                        data: d,
                        escapeMarkup: function (markup) {
                            return markup;
                        },
                    });
                });
            },
        });
        $(document).on('click','.transferlain', function () {
            let jwdid = $(this).data('id');
            let ket = $(this).data('ket');
            let jml_sisa = $(this).data('jml');
            let mulai = $(this).parent().prev().prev().prev().prev().prev().prev().text();
            let selesai = $(this).parent().prev().prev().prev().prev().prev().text();
            let bppb = $(this).parent().prev().prev().prev().prev().text();
            let produk = $(this).parent().prev().prev().prev().text();
            $('#d_rakit1').text(mulai);
            $('#t_rakit1').text(selesai);
            $('#bppb1').text(bppb);
            $('#produk1').text(produk);
            $('#jml1').text(jml_sisa + ' Unit');
            $('.keterangan1').val(ket);

            $('.tableNoseri').DataTable({
                destroy: true,
                "autoWidth": false,
                processing: true,
                scrollY: "250px",
                lengthChange: false,
                ajax: {
                    url: "/api/prd/detail_sisa_kirim",
                    type: "post",
                    data: {id: jwdid},
                },
                columns: [
                    {data: 'noseri'}
                ],
            })
            $('.modalTransferLain').modal('show');
            $('.tableNoseri').DataTable().columns.adjust().draw();
            // console.log(jwdid);
        });

        // Filter
        $('.produk_select2').select2();
        $('.produk_select2').change(function() {
        var search2 = [];

        $.each($('.produk_select2 option:selected'), function () {
            const reg = '&quot;';
            const a = $(this).val();
            const b = a.replaceAll(reg, '"');
            console.log(b);
            search2.push(b);
        });
        search2 = search2.join('|');
        table2.column(3).search(search2, true, false).draw();
    });

    var start_date3;
    var end_date3;
    var DateFilterFunction3 = (function (oSettings, aData, iDataIndex) {
        var dateStart3 = parseDateValue3(start_date3);
        var dateEnd3 = parseDateValue3(end_date3);

        var evalDate3 = parseDateValue3(aData[7]);
        if ((isNaN(dateStart3) && isNaN(dateEnd3)) ||
            (isNaN(dateStart3) && evalDate3 <= dateEnd3) ||
            (dateStart3 <= evalDate3 && isNaN(dateEnd3)) ||
            (dateStart3 <= evalDate3 && evalDate3 <= dateEnd3)) {
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

    $('.daterange3').daterangepicker({
            autoUpdateInput: false
        });

        $('.daterange3').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format(
                'DD-MM-YYYY'));
            start_date3 = picker.startDate.format('DD-MM-YYYY');
            end_date3 = picker.endDate.format('DD-MM-YYYY');
            $.fn.dataTableExt.afnFiltering.push(DateFilterFunction3);
            table2.draw();
        });

        $('.daterange3').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            start_date3 = '';
            end_date3 = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction3, 1));
            table2.draw();
        });

        var start_date4;
    var end_date4;
    var DateFilterFunction4 = (function (oSettings, aData, iDataIndex) {
        var dateStart4 = parseDateValue4(start_date4);
        var dateEnd4 = parseDateValue4(end_date4);

        var evalDate4 = parseDateValue4(aData[8]);
        if ((isNaN(dateStart4) && isNaN(dateEnd4)) ||
            (isNaN(dateStart4) && evalDate4 <= dateEnd4) ||
            (dateStart4 <= evalDate4 && isNaN(dateEnd4)) ||
            (dateStart4 <= evalDate4 && evalDate4 <= dateEnd4)) {
            return true;
        }
        return false;
    });

    function parseDateValue4(rawDate) {
        var dateArray = rawDate.split("-");
        var parsedDate = new Date(dateArray[2], parseInt(dateArray[1]) - 1, dateArray[
        0]);
        return parsedDate;
    }

    $('.daterange4').daterangepicker({
            autoUpdateInput: false
        });

        $('.daterange4').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format(
                'DD-MM-YYYY'));
            start_date4 = picker.startDate.format('DD-MM-YYYY');
            end_date4 = picker.endDate.format('DD-MM-YYYY');
            $.fn.dataTableExt.afnFiltering.push(DateFilterFunction4);
            table2.draw();
        });

        $('.daterange4').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            start_date4 = '';
            end_date4 = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction4, 1));
            table2.draw();
        });

        // Per Produk
        // $('.tableDetailProduk').DataTable({});
        // $(document).on('click','.btnClickDetailProduk', function () {
        //     $('.tableNoseriProduk').dataTable({
        //         destroy: true,
        //         "autoWidth": false,
        //         processing: true,
        //         lengthChange: false,
        //         // ajax: {
        //         //     url: "#",
        //         //     type: "post",
        //         // },
        //         // columns: [
        //         //     {data: 'noseri'}
        //         // ],
        //     })
        //     $('.modalRakitProduk').modal('show');
        // });
</script>
@stop
