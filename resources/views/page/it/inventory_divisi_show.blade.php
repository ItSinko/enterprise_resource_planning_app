@extends('adminlte.page')

@section('title', 'Beta Version')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Inventory</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Calendar</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')

    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h2>Inventory Divisi</h2>
                        <table id="example" class="table table-hover styled-table table-striped table-item">
                            <thead style="text-align: center;">
                                <tr>
                                    <th colspan="12">
                                        <a href="{{ route('inventory.create', ['divisi_id' => Auth::user()->Karyawan->divisi_id]) }}"
                                            style="color: white;"><button type="button"
                                                class="btn btn-block btn-success btn-sm" style="width: 200px;"><i
                                                    class="fas fa-plus"></i> &nbsp; Tambah</i></button></a>
                                    </th>
                                </tr>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Divisi</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody style="text-align: center;">

                            </tbody>
                        </table>
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
                ajax: "{{ route('inventory.divisi.show') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'divisi',
                        name: 'divisi'
                    },
                    {
                        data: 'pic',
                        name: 'pic'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        });
    </script>
@stop
