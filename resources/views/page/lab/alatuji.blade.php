@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Daftar Alat Uji</h1>
@stop

@section('content')
    <script src="https://cdn.datatables.net/rowgroup/1.2.0/css/rowGroup.dataTables.min.css"></script>
    <div class="container-fluid">
        <div class="container bg-white p-3">

        @if(session()->has('err'))
        <div class="alert alert-warning" role="alert">
            {{ session('err') }}
        </div>
        @endif

            <div class="table-responsive">
            <table class="table table-hover table-sm text-center border" id="dataTabel">
                <thead>
                    <tr class="bc-grey text-light">
                        <th scope="col">No</th>
                        <th scope="col">Kode Alat</th>
                        <th scope="col">Serial Number</th>
                        <th scope="col">Merk</th>
                        <th scope="col">Tanggal Masuk</th>
                        <th scope="col">Lokasi</th>
                        <th scope="col">Kondisi</th>
                        <th scope="col" style="width:75px;max-width:75px;">Status</th>
                        <th>nama</th>
                        <th>klasifikasi</th>
                        <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr class="bc-primary text-start">
                        <th scope="row" colspan="9" class="text-light">Bunyi</th>
                    </tr>
                    <tr class="bc-secondary text-start">
                        <th scope="row" colspan="9" class="txt-primary">Digital Sound Level Meter</th>
                    </tr>
                    <tr>
                        <th scope="row">1</th>
                        <td>DSN-01</td>
                        <td>AT-SM1357</td>
                        <td>ATTEN</td>
                        <td>09-01-2022</td>
                        <td>LM1/3</td>
                        <td  data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                        </td>
                        <td>
                            <span class="badge w-100 bc-success">
                                <span class="text-success">Tersedia</span>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary py-0 w-100">
                                Detail
                            </button>
                        </td>
                    </tr>
                    <tr class="bc-primary text-start">
                        <th scope="row" colspan="9" class="text-light">Intensitas Cahaya</th>
                    </tr>
                    <tr class="bc-secondary text-start">
                        <th scope="row" colspan="9" class="txt-primary">Eye Care Litecheck 10 Lux</th>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>
                            TRN-01
                        </td>
                        <td>AT-SM1357</td>
                        <td>TRANS INST</td>
                        <td>14-01-2014</td>
                        <td>LM1/3</td>
                        <td  data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                        </td>
                        <td>
                            <span class="badge w-100 bc-success">
                                <span class="text-success">Tersedia</span>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary py-0 w-100">
                                Detail
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>
                            TRN-01
                        </td>
                        <td>AT-SM1357</td>
                        <td>TRANS INST</td>
                        <td>14-01-2014</td>
                        <td>LM1/3</td>
                        <td  data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                        </td>
                        <td>
                            <span class="badge w-100 bc-danger">
                                <span class="text-danger">Tidak Tersedia</span>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary py-0 w-100">
                                Detail
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">4</th>
                        <td>
                            TRN-01
                        </td>
                        <td>AT-SM1357</td>
                        <td>TRANS INST</td>
                        <td>14-01-2014</td>
                        <td>LM1/3</td>
                        <td  data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                        </td>
                        <td>
                            <span class="badge w-100 bc-success">
                                <span class="text-success">Tersedia</span>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary py-0 w-100">
                                Detail
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">5</th>
                        <td>
                            TRN-01
                        </td>
                        <td>AT-SM1357</td>
                        <td>TRANS INST</td>
                        <td>14-01-2014</td>
                        <td>LM1/3</td>
                        <td class="" data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Bisa Di Gunakan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-x-circle-fill text-danger" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                            </svg>
                        </td>
                        <td>
                            <span class="badge w-100 bc-danger">
                                <span class="text-danger">Tidak Tersedia</span>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary py-0 w-100">
                                Detail
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">6</th>
                        <td>
                            TRN-01
                        </td>
                        <td>AT-SM1357</td>
                        <td>TRANS INST</td>
                        <td>14-01-2014</td>
                        <td>LM1/3</td>
                        <td  data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                        </td>
                        <td>
                            <span class="badge w-100 bc-success">
                                <span class="text-success">Tersedia</span>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary py-0 w-100">
                                Detail
                            </button>
                        </td>
                    </tr> -->
                </tbody>
            </table>
            </div>
        </div>

    </div>
@stop
@section('adminlte_js')
    <script src="https://cdn.datatables.net/rowgroup/1.2.0/js/dataTables.rowGroup.min.js"></script>
    <script>
    $(document).ready(function(){
        // emable bootstrap tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        $.ajax({
           type:'GET',
           url:'{{ url("/api/inventory/data")}}',
           success:function(data) {
              //console.log(data);
           }
        });

        var table = $('#dataTabel').DataTable({
            processing: false,
            serverSide: false,
            destroy: true,
            ajax: "{{ url('/api/inventory/data') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'kode_alat', name: 'kode_alat'},
                {data: 'serial_number', name: 'serial_number'},
                {data: 'nama_merk', name: 'nama_merk'},
                {data: 'tgl_masuk', name: 'tgl_masuk'},
                {data: 'lokasi', name: 'lokasi'},
                {data: 'kondisi_id', name: 'kondisi_id'},
                {data: 'status', name: 'status'},
                {data: 'nama_klasifikasi', name: 'nama_klasifikasi'},
                {data: 'nm_alatuji', name: 'nm_alatuji'},
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: true,
                    searchable: true
                },
            ],
            order: [[0, 'asc']],
            rowGroup: {
                dataSrc: ['nama_klasifikasi', 'nm_alatuji'],
                startRender: function(rows, group) {
                    var namaCek = new RegExp('- nama*');
                    if(namaCek.test(group) == true){
                        group = group.replace(' - nama','');
                        group = $('<tr class="group group-start"><td  class="bc-primary" style="color:#27445C;" colspan="11"><strong>' + group + '</strong></td></tr>');
                    }else{
                        group = group.replace(' - klasifikasi', '');
                        group = $('<tr class="group group-start"><td class="text-white" style="background-color:#27445C;" colspan="11"><strong>' + group + '</strong></td></tr>');
                    }
                    return group;
                }

            },
            columnDefs: [{
                targets: [8,9],
                visible: false,
            }],
        });

    });
    </script>

@endsection
