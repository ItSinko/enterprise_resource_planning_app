@extends('adminlte.page')

@section('title', 'Beta Version')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Inventory</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Beranda</a></li>
                        <li class="breadcrumb-item active">Inventory</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h2>Inventory Divisi</h2>
                        <table id="example" class="table table-hover styled-table-ss table-striped table-item"
                            style="width:100%;">
                            <thead style="text-align: center;">
                                <tr>
                                    <th colspan="20">
                                        <a href="{{ route('inventory.create', ['divisi_id' => Auth::user()->Karyawan->divisi_id]) }}"
                                            style="color: white;"><button type="button"
                                                class="btn btn-block btn-success btn-sm" style="width: 200px;"><i
                                                    class="fas fa-plus"></i> &nbsp; Tambah</i></button></a>
                                    </th>
                                </tr>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Perolehan</th>
                                    <th>Harga Perolehan</th>
                                    <th>Lokasi</th>
                                    <th>Nilai Penyusutan</th>
                                    <th>Akum Nilai Penyusutan</th>
                                    <th>Nilai Sisa Buku</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;">

                            </tbody>
                        </table>

                        <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color:#cc0000;">
                                        <h4 class="modal-title" id="myModalLabel" style="color:white;">Hapus Data</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body" id="delete">
                                        <div class="card">
                                            <div class="card-body" style="text-align:center;">
                                                <input id="labelid" name="labelid" hidden>
                                                <h6>Kenapa anda ingin menghapus data ini?</h6>
                                            </div>
                                            <form id="deleteform" action="" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <div class="form-group row">
                                                    <div class="col-sm-1"></div>
                                                    <div class="col-sm-3">
                                                        <div class="icheck-danger d-inline">
                                                            <input type="radio" id="revisi" name="keterangan_log"
                                                                value="revisi" checked>
                                                            <label for="revisi">
                                                                Revisi
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="icheck-danger d-inline">
                                                            <input type="radio" id="salah_input" name="keterangan_log"
                                                                value="salah_input">
                                                            <label for="salah_input">
                                                                Salah Input
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <div class="icheck-danger d-inline">
                                                            <input type="radio" id="pembatalan" name="keterangan_log"
                                                                value="pembatalan">
                                                            <label for="pembatalan">
                                                                Pembatalan
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="card-footer col-12" style="margin-bottom: 2%;">
                                                    <span>
                                                        <button type="button" class="btn btn-block btn-info batalsk"
                                                            data-dismiss="modal" id="batalhapussk"
                                                            style="width:30%;float:left;">Batal</button>
                                                    </span>
                                                    <span>
                                                        <button type="submit" class="btn btn-block btn-danger"
                                                            id="hapussk" style="width:30%;float:right;">Hapus</button>
                                                    </span>
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
    </section>
@endsection

@section('adminlte_js')
    <script>
        $(function() {
            $('#example').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('inventory.show', ['divisi_id' => $divisi_id]) }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_barang',
                        name: 'nama_barang'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'tanggal_perolehan',
                        name: 'tanggal_perolehan'
                    },
                    {
                        data: 'harga_perolehan',
                        name: 'harga_perolehan',
                        render: $.fn.dataTable.render.number('.', ',', 2, 'Rp. ')
                    },
                    {
                        data: 'lokasi',
                        name: 'lokasi'
                    },
                    {
                        data: 'nilai_penyusutan',
                        name: 'nilai_penyusutan',
                        render: $.fn.dataTable.render.number('.', ',', 2, 'Rp. ')
                    },
                    {
                        data: 'akum_nilai_penyusutan',
                        name: 'akum_nilai_penyusutan',
                        render: $.fn.dataTable.render.number('.', ',', 2, 'Rp. ')
                    },
                    {
                        data: 'nilai_sisa_buku',
                        name: 'nilai_sisa_buku',
                        render: $.fn.dataTable.render.number('.', ',', 2, 'Rp. ')
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $(document).on('click', '.deletemodal', function() {
                var url = $(this).attr('data-url');
                $("#deleteform").attr("action", url);
            });
        });
    </script>
@stop
