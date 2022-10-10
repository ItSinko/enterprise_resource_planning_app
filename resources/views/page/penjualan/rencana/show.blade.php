@extends('adminlte.page')

@section('title', 'ERP')

@section('adminlte_css')

{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css"> --}}
<style>

    td.dt-control {
        background: url("/assets/image/logo/plus.png") no-repeat center center;
        cursor: pointer;
        background-size: 15px 15px;
    }
    tr.shown td.dt-control {
        background: url("/assets/image/logo/minus.png") no-repeat center center;
        background-size: 15px 15px;
    }

    table tr td:nth-child(2),
    table tr td:nth-child(5),
    {
        text-align: center;
    }

    table tr td:nth-child(3),
    table tr td:nth-child(4),
    table tr td:nth-child(7),
    table tr td:nth-child(6), {
        text-align: right;
    }

    .align-center {
        text-align: center;
    }

    .align-right {
        text-align: right;
    }

    .borderright {
        border-right: 1px solid #E1EBF2;
    }

    .form-inline {
        display: flex;
        flex-flow: row wrap;
        align-items: center;
        margin: 20px;
    }

    .form-inline label {
        margin: 5px 10px 5px 0;
    }

    .form-inline input {
        vertical-align: middle;
        margin: 5px 10px 5px 0;
        padding: 10px;
        background-color: #fff;
        border: 1px solid #ddd;
    }

    .nowraptxt {
        white-space: nowrap;
    }

    .tabnum{
        font-variant-numeric: tabular-nums;
    }
    /* .form-inline button {
    padding: 10px 20px;
    background-color: dodgerblue;
    border: 1px solid #ddd;
    color: white;
    cursor: pointer;
    } */

    .form-inline button:hover {
        background-color: darkgrey;
    }

    #customer_id {
        width: 70%;
    }

    #tahun {
        width: 70%;
    }

    #btntambah {
        margin-bottom: 10px;
    }

    .va-mid{
        vertical-align: middle !important;
    }

    @media (min-width: 993px) {
        body {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }
    }

    @media (max-width: 992px) {
        body {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }

        .form-inline input {
            margin: 10px 0;
        }

        .form-inline {
            flex-direction: column;
            align-items: stretch;
        }

        #customer_id {
            width: 100%;
        }

        #tahun {
            width: 100%;
        }

        .form-inline button {
            float: right;
        }

        #btntambah {
            margin-bottom: 5px;
        }
    }



 .editable-container.editable-inline {
     max-width: 100%
 }

 .editable-container.editable-inline .editableform {
     max-width: 100%
 }

 .editable-container.editable-inline .editableform .control-group {
     max-width: 100%;
     white-space: initial
 }

 .editable-container.editable-inline .editableform .control-group>div {
     max-width: 100%
 }

 .editable-container.editable-inline .editableform .control-group .editable-input input,
 .editable-container.editable-inline .editableform .control-group .editable-input textarea {
     max-width: 100%;
     width: 100%

 }
</style>
@stop

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Rencana Penjualan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @if(Auth::user()->divisi_id == "26" || Auth::user()->divisi_id == "8")
                <li class="breadcrumb-item"><a href="{{route('penjualan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Rencana Penjualan</li>
                @elseif(Auth::user()->divisi_id == "2")
                <li class="breadcrumb-item"><a href="{{route('direksi.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Rencana Penjualan</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <form class="form-inline" id="filter">
                                        <div class=" form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <label for="customer_id">Distributor: </label>
                                            <select class="form-control custom-select" name="customer_id" id="customer_id"></select>
                                        </div>
                                        <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <label for="tahun">Tahun: </label>
                                            <input class="form-control" type="number" id="tahun" placeholder="Masukkan Tahun" name="tahun" disabled>

                                        </div>
                                        <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                            <button class="btn btn-warning" type="submit" id="btncari" disabled>Cari</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <span class="float-left ">
                                            <div class="input-group-prepend">
                                                <button type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown" disabled id="export">
                                                    <i class="far fa-file-excel"></i> &nbsp;Export
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{route('penjualan.rencana.laporan',['customer'=> '0','tahun'=> '0'])}}" id="lap_semua">Laporan Semua</a>
                                                    <a class="dropdown-item" href="{{route('penjualan.rencana.laporan_detail',['customer'=> '0','tahun'=> '0'])}}" id="lap_detail">Laporan Detail</a>
                                                </div>
                                            </div>
                                        </span>
                                        <span class="float-right" id="btntambah"><a href="{{route('penjualan.rencana.create')}}" class="btn btn-outline-info"><i class="fas fa-plus"></i>&nbsp;Tambah Rencana</a></span>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="showtable" style="width:100%;">
                                            <thead style="text-align:center;">
                                                <tr>
                                                    <th rowspan="2">Instansi</th>
                                                    <th rowspan="2"></th>
                                                    <th rowspan="2" class="borderright"  width="30%">Produk</th>
                                                    <th colspan="4" class="borderright">Rencana</th>
                                                    {{-- <th colspan="4">Realisasi</th> --}}
                                                </tr>
                                                <tr>
                                                    <th  width="10%">Qty</th>
                                                    <th width="18%">Harga</th>
                                                    <th class="borderright">Subtotal</th>
                                                    {{-- <th>Qty</th>
                                                    <th>Harga</th>
                                                    <th>Subtotal</th> --}}
                                                    <th width="10%">Aksi</th>
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

                <div class="modal fade" id="realmodal" role="dialog" aria-labelledby="realmodal" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header bg-warning">
                                <h4 class="modal-title"><b>Realisasi Penjualan</b></h4>
                            </div>
                            <div class="modal-body" id="real">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="hapusmodal" role="dialog" aria-labelledby="hapusmodal" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header bg-danger">
                                <h4 class="modal-title"><b>Hapus</b></h4>
                            </div>
                            <div class="modal-body" id="hapus">
                                <div class="row">
                                    <div class="col-12">
                                        <form method="post" action="" id="form-hapus" data-target="">
                                            @method('delete')
                                            @csrf
                                            <div class="card">
                                                <div class="card-body">Apakah Anda yakin ingin menghapus data ini?</div>
                                                <div class="card-footer">
                                                    <span class="float-left">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    </span>
                                                    <span class="float-right">
                                                        <button type="submit" class="btn btn-danger " id="btnhapus"><i id="load" class=""></i> Hapus</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </form>
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
@stop

@section('adminlte_js')
<script src="{{ asset('vendor/x-editable/jquery-editable-poshytip.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        $("#showtable").on('keyup change', '.harga', function() {
            var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $(this).val(result);
        });

        $('#showtable').on('click', '.update_harga', function() {
            harga_action();
        });

        function harga_action(){
            $('.update_harga').editable({
                url : '{{route("penjualan.rencana.update")}}',
                inputclass: 'form-control harga',
                type:'text',
                success : function (){
                    $('#showtable').DataTable().ajax.reload();
                }
            })
        }

        $('#showtable').on('click', '.update_jumlah', function() {
            $('.update_jumlah').editable({
                url : '{{route("penjualan.rencana.update")}}',
                inputclass: 'form-control jumlah',
                type:'number',
                success : function (){
                    $('#showtable').DataTable().ajax.reload();
                }
            })
        });

        $.fn.editable.defaults.mode = 'inline';
        $.fn.editableform.buttons =
            `<button type="submit" class="editable-submit btn btn-primary btn-sm">
                <i class="fa fa-fw fa-check"></i>
                </button>
            <button type="button" class="editable-cancel btn btn-danger btn-sm">
                <i class="fa fa-fw fa-times"></i>
                </button>`;


        $.fn.editableform.template =
            `<form class="form-inline editableform justify-content-center" width="100%">
                <div class="control-group">
                    <div class="form-group">
                        <div class="editable-input mr-1"></div>
                        <div class="editable-buttons"></div>
                    </div>
                    <div class="editable-error-block"></div>
                </div>
            </form>`;
    });
</script>
<script>
    $(function() {
        // $.fn.editable.defaults.mode = 'inline';
        var groupColumn = 0;
        var showtable = $('#showtable').DataTable({
            destroy: true,
            processing: true,
            dom: 'Bfrtip',
            serverSide: false,
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            ajax: {
                'url': '/api/penjualan/rencana/show/0/0',
                'dataType': 'json',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            },
            columns: [
                {
                    data: 'instansi',
                    className: "instansi",
                }, {
                    "className": 'dt-control',
                    "orderable": false,
                    "data": null,
                    "defaultContent": ''
                }, {
                    data: 'produk',
                    className: 'borderright va-mid  wrap',
                }, {
                    data: 'jumlah',
                    className: 'nowraptxt align-center tabnum va-mid jumlah',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'hargas',
                    className: 'nowraptxt align-right tabnum va-mid',
                    // render: $.fn.dataTable.render.number(',', '.', 2),
                    orderable: false,
                    searchable: false
                }, {
                    data: 'sub',
                    className: 'nowraptxt align-right borderright tabnum va-mid',
                    orderable: false,
                    searchable: false
                    // render: $.fn.dataTable.render.number(',', '.', 2),
                }, {
                    data: 'hapus',
                    className: 'nowraptxt align-center va-mid',
                    orderable: false,
                    searchable: false
                }
            ],
            // createdRow: function( row, data, dataIndex ) {
            //     // $(row).find('td:eq(1)').attr('data-type', "select");
            //     $(row).find('td:eq(1)').attr('data-title', "Pilih Produk");
            //     $(row).find('td:eq(1)').attr('data-id', "produk_id");
            //     $(row).find('td:eq(1)').attr('data-name', "produk_id");
            //     $(row).find('td:eq(1)').attr('data-pk', data.id);
            // },
            // buttons: [{
            //     extend: 'excel',
            //     title: 'Laporan Penjualan',
            //     text: '<i class="far fa-file-excel"></i> Export',
            //     className: "btn btn-info"
            // }, ],
            // columns: [{
            //     data: 'instansi',
            //     orderable: false,
            //     searchable: false
            // }, {
            //     "className": 'dt-control',
            //     "orderable": false,
            //     "data": null,
            //     "defaultContent": ''
            // }, {
            //     data: 'produk',
            //     className: 'borderright va-mid'
            // }, {
            //     data: 'jumlah',
            //     className: 'nowraptxt align-center tabnum va-mid'
            // }, {
            //     data: 'harga',
            //     className: 'nowraptxt align-right tabnum va-mid',

            // }, {
            //     data: 'sub',
            //     className: 'nowraptxt align-right borderright tabnum va-mid',
            //     render: $.fn.dataTable.render.number('.', '.', 0),
            // },
            "fixedColumns": {
                left: 0
            },
            "columnDefs": [{
                "visible": false,
                "targets": groupColumn
            }],
            "order": [
                [groupColumn, 'asc']
            ],
            "displayLength": 10,
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;

                api.column(groupColumn, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group" style="background-color:steelblue; color:white;"><td colspan="9"><b>' + group + '</b></td></tr>'
                        );
                        last = group;
                    }
                });
            }
        });

        function format ( data ) {
            return `
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-none">
                        <div class="card-header"><h6 class="card-title">Realisasi</h6></div>
                        <div class="card-body">

                    <table class="table table-hover" id="detailtable`+data.id+`">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No SO</th>
                                <th>No AKN</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="align-center">Total</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
                </div>
            </div>`;
        }

        function detailtable(id){
            $('#detailtable'+id).DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                paging: false,
                info:false,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                ajax: {
                    'url': '/api/penjualan/rencana/real/show/'+id,
                    'dataType': 'json',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                footerCallback: function ( row, data, start, end, display ) {
                    var api = this.api();

                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    // Total over all pages
                    total = api
                        .column(5)
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    // Total over this page
                    pageTotal = api
                        .column(5, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    // Update footer
                    $(api.column(5).footer() ).html(
                        'Rp. '+ $.fn.dataTable.render.number(',', '.', 2).display(total)
                    );
                },
                columns: [{
                    data: 'DT_RowIndex',
                    className: 'nowrap-text align-center',
                    orderable: true,
                    searchable: false
                },{
                    data: 'no_so',
                    className: 'nowrap-text align-center',
                }, {
                    data: 'no_akn',
                    className: 'nowrap-text align-center',
                }, {
                    data: 'jumlah',
                    className: 'nowraptxt align-center tabnum'
                }, {
                    data: 'harga',
                    className: 'nowraptxt align-right tabnum',
                    render: $.fn.dataTable.render.number(',', '.', 2),
                }, {
                    data: 'sub',
                    className: 'nowraptxt align-right borderright tabnum',
                    render: $.fn.dataTable.render.number(',', '.', 2),
                }],
            });
        }

        $('#showtable tbody').on('click', 'td.dt-control', function () {
            var tr = $(this).closest('tr');
            var row = showtable.row( tr );

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                detailtable(row.data().id);
                tr.addClass('shown');
            }
        });


        $(document).on('submit', '#form-hapus', function(e) {
            e.preventDefault();
            $('#btnhapus').attr('disabled', true);
            $('#load').addClass('fas fa-circle-notch fa-spin');
            var action = $(this).attr('action');
            console.log(action);
            $.ajax({
                url: action,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response['data'] == "success") {
                        swal.fire(
                            'Berhasil',
                            'Berhasil melakukan Hapus Data',
                            'success'
                        );
                        $('#showtable').DataTable().ajax.reload();
                        $("#hapusmodal").modal('hide')
                        $('#load').removeClass();
                         $('#btnhapus').attr('disabled', false);
                    } else if (response['data'] == "error") {
                        swal.fire(
                            'Gagal',
                            'Gagal melakukan Penambahan Data Pengujian',
                            'error'
                        );
                    } else {
                        swal.fire(
                            'Error',
                            'Data telah digunakan dalam Transaksi Lain',
                            'warning'
                        );
                        $('#load').removeClass();
                        $('#btnhapus').attr('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    swal.fire(
                        'Error',
                        'Data telah digunakan dalam Transaksi Lain',
                        'warning'
                    );
                    $('#load').removeClass();
                    $('#btnhapus').attr('disabled', false);
                }
            });
            return false;
        });

        $('#customer_id').select2({
            placeholder: "Pilih Distributor",
            ajax: {
                minimumResultsForSearch: 20,
                dataType: 'json',
                theme: "bootstrap",
                delay: 250,
                type: 'GET',
                url: '/api/customer/select_rencana/',
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


        $("#tahun").autocomplete({
            source: function(request, response) {

                $.ajax({
                    dataType: 'json',
                    url: "/api/penjualan/rencana/select_tahun",
                    data: {
                        term: request.term
                    },
                    success: function(data) {

                        var transformed = $.map(data, function(el) {
                            return {
                                label: el.tahun,
                                id: el.id
                            };
                        });
                        response(transformed);
                    },
                    error: function() {
                        response([]);
                    }
                });
            }
        });


        $('#customer_id').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#tahun").attr('disabled', false);
            } else {
                $("#tahun").attr('disabled', true);
            }
        });

        $('#tahun').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#btncari").attr('disabled', false);
            } else {
                $("#btncari").attr('disabled', true);

            }
        });

        $(document).on('click', '.hapusmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data("id");
            console.log(id);
            $('#hapusmodal').modal("show");
            $('#hapusmodal').find('form').attr('action', '/api/penjualan/rencana/delete/' + id);
        });


        $('#filter').submit(function() {
           // $("#parent_export").attr('disabled', false);
            var customer_id = $('#customer_id').val();
            var tahun = $('#tahun').val();

            $('#showtable').DataTable().ajax.url('/api/penjualan/rencana/show/' + customer_id + '/' + tahun).load();

            var link = '/penjualan/rencana/laporan/' + customer_id + '/' + tahun;
            var link2 = '/penjualan/rencana/laporan_detail/' + customer_id + '/' + tahun;

            console.log(link);
            $('#lap_semua').attr({
                href: link
            });

            $('#lap_detail').attr({
                href: link2
            });
            $("#export").attr('disabled', false);
            return false;
        });

        function realtable(id){
            $('#realtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                ajax: {
                    'url': '/api/penjualan/rencana/real/data/'+id,
                    'dataType': 'json',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                columns: [{
                    data: 'checkbox',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'no_so',
                    className: 'nowraptxt',
                }, {
                    data: 'no_akn',
                }, {
                    data: 'jumlah',
                    className: 'nowraptxt align-center tabnum'
                }, {
                    data: 'harga',
                    className: 'nowraptxt align-right tabnum',
                    render: $.fn.dataTable.render.number(',', '.', 2),
                }, {
                    data: 'sub',
                    className: 'nowraptxt align-right borderright tabnum',
                    render: $.fn.dataTable.render.number(',', '.', 2),
                }]
            });
        }

        $(document).on('click', '.realmodal', function(event) {
            event.preventDefault();
            var id = $(this).attr("data-id");
            $.ajax({
                url: '/penjualan/rencana/real/show/'+id,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#realmodal').modal("show");
                    $('#real').html(result).show();
                    realtable(id);

                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + '/penjualan/rencana/real/show/'+ id + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

        $(document).on('click', '#realtable input[name="check_all"]', function() {
            var rows = $('#realtable').DataTable().rows({ 'search': 'applied' }).nodes();
            if ($('input[name="check_all"]:checked').length > 0) {
                $('.so_id', rows).prop('checked', true);
                // checkedAry = [];
                // $.each($(".so_id:checked", rows), function() {
                //     checkedAry.push($(this).closest('tr').find('.so_id').attr('data-id'));
                // });
            } else if ($('input[name="check_all"]:checked').length <= 0) {
                $('.so_id', rows).prop('checked', false);
            }
        });

        $(document).on('click', '#realtable .so_id', function() {
            $('#check_all').prop('checked', false);
            if ($('.so_id:checked').length > 0) {
                // $('#btnsubmit').attr('disabled', false);
                // $('#cekbrg').prop('disabled', false);
                // $('#cekbrgedit').prop('disabled', false);
                // checkedAry = [];
                // $.each($(".so_id:checked"), function() {
                //     checkedAry.push($(this).closest('tr').find('.so_id').attr('data-id'));
                // });
            } else if ($('.so_id:checked').length <= 0) {
                // $('#btnsubmit').attr('disabled', true);
                // $('#cekbrg').prop('disabled', true);
                // $('#cekbrgedit').prop('disabled', true);
            }
        });

        $(document).on('submit', '#formsubmit', function(e) {
            $('#btnsubmit').attr('disabled', true);
            e.preventDefault();

            var detail_pesanan_id = $('#realtable').DataTable().$('tr').find('input[type="checkbox"][name="detail_pesanan_id[]"]').serializeArray();
            var data = [];

            $.each(detail_pesanan_id, function() {
                data.push(this.value);
            });
            console.log(data);
            var action = $(this).attr('action');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: action,
                data: {detail_pesanan_id:data},
                dataType: 'JSON',
                beforeSend: function() {
                    swal.fire({
                        title: 'Sedang Proses',
                        html: 'Loading...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {Swal.showLoading()}
                    })
                },
                success: function(response) {
                    console.log(response);
                    if (response['data'] == "success") {
                        swal.fire(
                            'Berhasil',
                            'Berhasil mengubah Realisasi Penjualan',
                            'success'
                        );
                        $('#showtable').DataTable().ajax.reload();
                        $("#realmodal").modal('hide')
                        $('#load').removeClass();
                        $('#btnsubmit').attr('disabled', false);
                    } else if (response['data'] == "error") {
                        swal.fire(
                            'Gagal',
                            'Gagal mengubah Realisasi Penjualan',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                }
            });
            return false;
        });

        //EDIT TABLE
        // function produkid(){
        //     $('select').select2({
        //         placeholder: "Pilih Produk",
        //         ajax: {
        //             minimumResultsForSearch: 20,
        //             dataType: 'json',
        //             theme: "bootstrap",
        //             delay: 250,
        //             type: 'GET',
        //             url: '/api/penjualan_produk/select/',
        //             data: function(params) {
        //                 return {
        //                     term: params.term
        //                 }
        //             },
        //             processResults: function(data) {

        //                 return {
        //                     results: $.map(data, function(obj) {
        //                         return {
        //                             id: obj.id,
        //                             text: obj.nama_alias
        //                         };
        //                     })
        //                 };
        //             },
        //         }
        //     });
        // }

        // $.fn.editableform.buttons = '<button type="submit" class="btn btn-info btn-sm editable-submit"><i class="fas fa-check"></i></button>' +
        //                             '<button type="button" class="btn btn-danger btn-sm editable-cancel"><i class="fas fa-times"></i></button>';
        // function changeinlineformcss(){
        //     $('.editableform').addClass('va-mid');
        //     $('input').addClass('form-control');
        //     $('.editable-submit').addClass('btn btn-info btn-sm');
        //     $(".editable-submit").html('<i class="fas fa-check"></i>');
        //     $('.editable-cancel').addClass('btn btn-danger btn-sm');
        //     $(".editable-cancel").html('<i class="fas fa-times"></i>');
        // }


        $('#showtable tbody').on('click', '.produk_id', function() {
            var data = showtable.row(this.parentElement).data();
            console.log(data);
            var id_produk = data.penjualan_produk_id;
            if(data.jumlah_real <= 0){
                $(this).closest('.produk_id').editable({
                    tpl: '<select id="produk"><option value="'+data.penjualan_produk_id+'" selected="selected">'+data.produk+'</option></select>',
                    select2: {
                        dropdownParent: '.editable-inline',
                        placeholder:'Pilih Produk',
                        width: 250,
                        ajax: {
                            minimumResultsForSearch: 20,
                            dataType: 'json',
                            theme: "bootstrap",
                            delay: 250,
                            type: 'GET',
                            url: '/api/penjualan_produk/select/',
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
                                            text: obj.nama,
                                            selected: obj.id == id_produk ? 'selected' : '',

                                        };
                                    })
                                };
                                // console.log($(this).select2('val', id_produk).trigger('change'));
                            },
                        },
                    },

                    type: 'select2',
                    url : '{{route("penjualan.rencana.update")}}',
                    success : function (){
                        $('#showtable').DataTable().ajax.reload();
                    },

                });
                // map = {option : new Option(data.produk,data.produk_penjualan_id, true, true) };
                // var option = new Option(data.produk, data.produk_penjualan_id);
                // option.selected = true;
                // $('#produk').val(1);
                // $('#produk').trigger('change');
                // $("#produk").select2('data', { id:data.penjualan_produk_id, text: data.produk});
                // $('#produk').append(data.penjualan_produk_id).trigger('change')
                // $('#produk option').eq(data.penjualan_produk_id).prop('selected',true);
                // $('#produk').append($("<option selected='selected'></option>").val(data.penjualan_produk_id).text(data.produk));
                // $('#produk').trigger('change');
                // $('#produk').append('<option value="'+data.penjualan_produk_id+'">'+data.produk+'</option>').trigger('change');
                // $('#produk').val('data.penjualan_produk_id');
                // map= {option : new Option(data.produk,data.produk_penjualan_id,true, true) };
                // $('#produk').val(data.penjualan_produk_id);
                // $('#produk').trigger('change');

            }
            // produkid();
            // changeinlineformcss();
            // var row = this.parentElement;
            // if (!$('#showtable').hasClass("editing")) {
            //     $('#showtable').addClass("editing");
            //     var data = showtable.row(row).data();
            //     var $row = $(row);
            //     var thisProduk = $row.find("td:nth-child(2)");
            //     var thisProdukText = thisProduk.text();
            //     thisProduk.empty().append('<select class="form-control changeProduk" id="produk" name="produk" data-harga="" data-id=""><option value="'+data.penjualan_produk_id+'">'+data.produk+'</option></select>');
            //     produkid();
            //     // Select the option with a value of '1'
            //     // $('#produk').select2();
            //     // $("#Position_" + data[0]).val(thisJumlahText)
            // }
        });



        //     var row = this.parentElement;
        //     if (!$('#showtable').hasClass("editing")) {
        //         $('#showtable').addClass("editing");
        //         var data = showtable.row(row).data();
        //         var $row = $(row);
        //         var thisJumlah = $row.find("td:nth-child(3)");
        //         var thisJumlahText = thisJumlah.text();
        //         thisJumlah.empty().append('<input type="number" class="form-control changeJumlah" id="jumlah" name="jumlah" data-id="'+data.id+'" value="'+data.jumlah+'"><button type="submit" class="btn btn-primary btn-sm mb-2"><i class="fas fa-check"></button>');
        //         // $("#Position_" + data[0]).val(thisJumlahText)
        //     }
    });
</script>

@stop
