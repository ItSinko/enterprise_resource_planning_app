@extends('adminlte.page')

@section('title', 'Beta Version')

@section('content_header')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pengujian</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Pengujian</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
@stop

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="example" class="table table-hover styled-table">
                        <thead style="text-align: center;">
                            <tr>
                                <th>No</th>
                                <th>No BPPB</th>
                                <th>Gambar</th>
                                <th>Tipe dan Nama</th>
                                <th>Jumlah</th>
                                <th>Laporan</th>
                                <th>Data</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody style="text-align:center;">

                        </tbody>
                        <tfoot style="text-align:center;">
                            <tr>
                                <th>No</th>
                                <th>No BPPB</th>
                                <th>Gambar</th>
                                <th>Tipe dan Nama</th>
                                <th>Jumlah</th>
                                <th>Laporan</th>
                                <th>Data</th>
                                <th>Aksi</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="monitoringprosesmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:	#006400;">
                            <h4 class="modal-title" id="myModalLabel" style="color:white;">Laporan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body" id="monitoringproses">

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="pemeriksaanprosesmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:	#006400;">
                            <h4 class="modal-title" id="myModalLabel" style="color:white;">Laporan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body" id="pemeriksaanproses">

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color:#cc0000;">
                            <h4 class="modal-title" id="myModalLabel" style="color:white;">Hapus Laporan</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body" id="delete">

                        </div>
                    </div>
                </div>
            </div>

            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@endsection

@section('adminlte_js')
<script>
    $(function() {
        $(document).on('click', '.monitoringprosesmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var dataid = $(this).attr('data-id');
            $.ajax({
                url: "{{route('pengujian.monitoring_proses')}}",
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#monitoringprosesmodal').modal("show");
                    $('#monitoringproses').html(result).show();
                    console.log(result);
                    $('#detaildata').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: href,
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            }, {
                                data: 'tanggal',
                                name: 'tanggal'
                            },
                            {
                                data: 'operator',
                                name: 'operator'
                            },
                            {
                                data: 'jumlah',
                                name: 'jumlah'
                            },
                            {
                                data: 'aksi',
                                name: 'aksi',
                                orderable: false,
                                searchable: false
                            },
                        ]
                    });
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

        $(document).on('click', '.pemeriksaanprosesmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var dataid = $(this).attr('data-id');
            $.ajax({
                url: "{{route('pengujian.pemeriksaan_proses')}}",
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#pemeriksaanprosesmodal').modal("show");
                    $('#pemeriksaanproses').html(result).show();
                    console.log(result);
                    $('#detaildata').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: href,
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            }, {
                                data: 'tanggal',
                                name: 'tanggal'
                            },
                            {
                                data: 'no_pemeriksaan',
                                name: 'no_pemeriksaan'
                            },
                            {
                                data: 'jumlah_produksi',
                                name: 'jumlah_produksi'
                            },
                            {
                                data: 'jumlah_sampling',
                                name: 'jumlah_sampling'
                            },
                            {
                                data: 'aksi',
                                name: 'aksi',
                                orderable: false,
                                searchable: false
                            },
                        ]
                    });
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

        $('#example').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('pengujian.show') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'no_bppb',
                    name: 'no_bppb'
                },
                {
                    data: 'gambar',
                    name: 'gambar'
                },
                {
                    data: 'produk',
                    name: 'produk'
                },
                {
                    data: 'jumlah',
                    name: 'jumlah'
                },
                {
                    data: 'laporan',
                    name: 'laporan'
                },
                {
                    data: 'data',
                    name: 'data'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $(document).on('click', '.deletemodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            $.ajax({
                url: '/template_form_delete',
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#deletemodal').modal("show");
                    $('#delete').html(result).show();
                    $("#deleteform").attr("action", href);
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

    });
</script>
@stop