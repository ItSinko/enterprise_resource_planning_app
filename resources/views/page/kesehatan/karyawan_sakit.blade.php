@extends('adminlte.page')
@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Karyawan Sakit</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kesehatan.dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Karyawan Sakit</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop
@section('adminlte_css')
    <style>
        .equal {
            display: flex;
            display: -webkit-flex;
            flex-wrap: wrap;
        }

        .minimizechar {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 20ch !important;
        }

        table {
            border-collapse: collapse;
            empty-cells: show;
        }

        td {
            position: relative;
        }

        .foo {
            border-radius: 50%;
            float: left;
            width: 10px;
            height: 10px;
            align-items: center !important;
        }

        tr.line-through td:not(:nth-last-child(-n+2)):before {
            content: " ";
            position: absolute;
            left: 0;
            top: 35%;
            border-bottom: 1px solid;
            width: 100%;
        }

        @media screen and (min-width: 1440px) {

            body {
                font-size: 14px;
            }

            #detailmodal {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }


        }

        @media screen and (max-width: 1439px) {
            body {
                font-size: 12px;
            }

            h4 {
                font-size: 20px;
            }

            #detailmodal {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }


        }
    </style>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-9">
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="pills-berobat-tab" data-toggle="pill"
                                        href="#pills-berobat" role="tab" aria-controls="pills-berobat"
                                        aria-selected="true">Berobat</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-sakit-tab" data-toggle="pill" href="#pills-sakit"
                                        role="tab" aria-controls="pills-sakit" aria-selected="false">Sakit</a>
                                </li>
                            </ul>
                        </div>
                        @if(Auth::user()->divisi_id == '28')
                        <div class="col-3">
                            <a href="/karyawan/sakit/tambah" style="color: white;"><button type="button"
                                    class="btn btn-md btn-success btn-sm float-right"><i class="fas fa-plus"></i>&nbsp;
                                    Tambah Karyawan Sakit</i></button></a>
                        </div>
                        @endif
                    </div>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-berobat" role="tabpanel"
                            aria-labelledby="pills-berobat-tab">
                            <div class='table-responsive'>
                                <table id="tabel_obat" class="table table-hover styled-table table-striped" width="100%">
                                    <thead style="text-align: center;">
                                        <tr>
                                            <th style="width:1%">No</th>
                                            <th>Tgl</th>
                                            <th>Divisi</th>
                                            <th>Nama</th>
                                            <th>Pemeriksa</th>
                                            <th>Analisa</th>
                                            <th>Diagnosa</th>
                                            <th>Tindak Lanjut</th>
                                            <th>Hasil</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-sakit" role="tabpanel" aria-labelledby="pills-sakit-tab">
                            <div class='table-responsive'>
                                <table id="tabel_sakit" class="table table-hover styled-table table-striped" width="100%">
                                    <thead style="text-align: center;">
                                        <tr>
                                            <th style="width:1%">No</th>
                                            <th>Tgl</th>
                                            <th>Divisi</th>
                                            <th>Nama</th>
                                            <th>Pemeriksa</th>
                                            <th>Analisa</th>
                                            <th>Diagnosa</th>
                                            <th>Tindak Lanjut</th>
                                            <th>Hasil</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Detail -->
    <div class="modal fade  bd-example-modal-xl" id="detail_mod" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="card-body">
                <form method="post" action="/kesehatan_harian_mingguan_tensi/aksi_ubah">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">
                                <div class="data_detail_head"></div>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="card">

                                        <div class="card-body">
                                            <dl>
                                                <dt>Nama Pasien</dt>
                                                <dd id="pasien"></dd>
                                                <dt>Divisi</dt>
                                                <dd id="divisi"></dd>
                                                <dt>Tanggal</dt>
                                                <dd id="tanggal"></dd>
                                                <dt>Pemeriksa</dt>
                                                <dd id="pemeriksa"></dd>
                                            </dl>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-9">

                                    <div class="row equal">
                                        <div class="col-6">
                                            <div class="callout callout-warning" height="100%">
                                                <h6>Analisa</h6>
                                                <div id="analisa" class="font-weight-bold"></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="callout callout-danger" height="100%">
                                                <h6>Diagnosa</h6>
                                                <div id="diagnosa" class="font-weight-bold"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card" id="detail_obat">
                                                <div class="card-header">
                                                    <h6 class="card-title">Obat</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover styled-table table-striped"
                                                            width="100%" id="tabel_detail_obat">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Nama</th>
                                                                    <th>Jumlah</th>
                                                                    <th>Aturan</th>
                                                                    <th>Konsumsi</th>
                                                                    <th></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="callout callout-info" height="100%" id="detail_terapi">
                                                <h6>Terapi</h6>
                                                <div id="terapi" class="font-weight-bold"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Detail -->
@stop
@section('adminlte_js')
    <script>
        $(function() {
            var divisi_id = '{{ Auth::user()->Karyawan->divisi_id }}';
            $('#tabel_obat > tbody').on('click', '#delete', function() {
                var data_id = $(this).attr('data-id');
                Swal.fire({
                        title: 'Hapus Data',
                        text: 'Yakin ingin menghapus data ini?',
                        icon: 'warning',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak',
                        showCancelButton: true,
                        showCloseButton: true
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/karyawan/sakit/delete/' + data_id,
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
                                        $('#tabel_detail').DataTable().ajax.reload();
                                        $("#hapusmodal").modal('hide');
                                    } else if (response['data'] == "error") {
                                        swal.fire(
                                            'Gagal',
                                            'Data telah digunakan dalam Transaksi Lain',
                                            'error'
                                        );
                                    } else {
                                        swal.fire(
                                            'Error',
                                            'Data telah digunakan dalam Transaksi Lain',
                                            'warning'
                                        );
                                    }
                                },
                                error: function(xhr, status, error) {
                                    swal.fire(
                                        'Error',
                                        'Data telah digunakan dalam Transaksi Lain',
                                        'warning'
                                    );
                                }
                            });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Gagal menghapus data',
                                icon: 'error',
                                showCloseButton: true
                            });
                        }
                    });
            });
            $('#tabel_detail_obat > tbody').on('click', '#delete', function() {
                Swal.fire({
                        title: 'Hapus Data',
                        text: 'Yakin ingin menghapus data ini?',
                        icon: 'warning',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak',
                        showCancelButton: true,
                        showCloseButton: true
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Berhasil menghapus data',
                                icon: 'success',
                                showCloseButton: true
                            });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Gagal menghapus data',
                                icon: 'error',
                                showCloseButton: true
                            });
                        }
                    });
            });
            var tabel_obat = $('#tabel_obat').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                ajax: {
                    'url': '/karyawan/sakit/data/berobat',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tgl_cek'
                    },
                    {
                        data: 'x',
                    },
                    {
                        data: 'y',
                    },
                    {
                        data: 'z'
                    },
                    {
                        data: 'analisa',
                        className: 'minimizechar'
                    },
                    {
                        data: 'diagnosa',
                        className: 'minimizechar'
                    },
                    {
                        data: 'detail_button'
                    },
                    {
                        data: null,
                        render: function(data, type, row){
                            var btn = '';
                            if (row.keputusan == "Lanjut bekerja") {
                                btn += '<span class="badge green-text">';
                            } else {
                                btn += '<span class="badge red-text">';
                            }
                            btn += row.keputusan + '</span>';
                            return btn;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row){
                            var btn = '<div class="btn-group"><span><button type="button" id="detail_tindakan"  class="btn btn-xs btn-outline-info m-1"><i class="fas fa-eye"></i> Detail</button></span>';
                            if (data.keputusan == 'Dipulangkan') {
                                if(divisi_id == '28'){
                                    btn += '<a href="/karyawan/sakit/cetak/' + row.id + '" target="_break"><button type="button" class="btn btn-xs btn-warning m-1" id="cetak_gcu"><i class="fas fa-print"></i> Cetak</button></a>';
                                }
                            }
                            if(divisi_id == '28'){
                                btn += '<span><button type="button" id="delete"  data-id="' + row.id + '" class="btn btn-xs btn-danger m-1"><i class="fas fa-trash"></i> Hapus</button></span>';
                            }
                            btn += '</div>';
                            // btn = '<div class="inline-flex"><a href="/karyawan/sakit/cetak/' . $data->id . '" target="_break"><button type="button" id="cetak_gcu"  class="btn btn-block btn-success karyawan-img-small" style="border-radius:50%;" ><i class="fas fa-print"></i></button></a></div>';
                            return btn;
                        }
                    }
                ]
            });
            $('#tabel_obat > tbody').on('click', '#detail_tindakan', function() {
                var rows = tabel_obat.rows($(this).parents('tr')).data();
                $('.data_detail_head').html(
                    "Karyawan Berobat"
                );
                var diagnosa = rows[0]['diagnosa'] != null ? rows[0]['diagnosa'] :
                    '<i>Tidak Ada Diagnosa</i>';
                var analisa = rows[0]['analisa'] != null ? rows[0]['analisa'] : '<i>Tidak Ada Analisa</i>';
                $('#analisa').html(analisa);
                $('#diagnosa').html(diagnosa);
                $('#pasien').html(rows[0]['y']);
                $('#divisi').html(rows[0]['x']);
                $('#pemeriksa').html(rows[0]['z']);
                $('#tanggal').html(rows[0]['tgl_cek']);
                if (rows[0]['tindakan'] == "Pengobatan") {
                    $('#detail_obat').removeClass('d-none');
                    $('#detail_terapi').addClass('d-none');
                    $('#tabel_detail_obat').DataTable({
                        processing: true,
                        destroy: true,
                        serverSide: false,
                        language: {
                            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                        },
                        ajax: '/karyawan/sakit/obat/detail/' + rows[0]['id'],
                        columns: [{
                                data: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'x',
                            },
                            {
                                data: 'jumlah',
                            },
                            {
                                data: 'aturan',
                            },
                            {
                                data: 'konsumsi',
                            },
                            {
                                data: 'aksi',
                                visible: divisi_id == '28' ? true : false
                            },
                        ],
                    });
                } else {
                    $('#detail_obat').addClass('d-none');
                    $('#detail_terapi').removeClass('d-none');
                    $('#terapi').html(rows[0]['terapi']);
                }

                $('#detail_mod').modal('show');
                // $('input[id="nama_obat"]').val(rows[0]['o']);
                // $('input[id="aturan"]').val(rows[0]['d']);
                // $('input[id="konsumsi"]').val(rows[0]['e']);
                // $('input[id="terapi"]').val(rows[0]['f']);
            });
            $('#tabel_sakit > tbody').on('click', '#delete', function() {
                var data_id = $(this).attr('data-id');
                Swal.fire({
                        title: 'Hapus Data',
                        text: 'Yakin ingin menghapus data ini?',
                        icon: 'warning',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak',
                        showCancelButton: true,
                        showCloseButton: true
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '/karyawan/sakit/delete/' + data_id,
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
                                        $('#tabel_detail').DataTable().ajax.reload();
                                        $("#hapusmodal").modal('hide');
                                    } else if (response['data'] == "error") {
                                        swal.fire(
                                            'Gagal',
                                            'Data telah digunakan dalam Transaksi Lain',
                                            'error'
                                        );
                                    } else {
                                        swal.fire(
                                            'Error',
                                            'Data telah digunakan dalam Transaksi Lain',
                                            'warning'
                                        );
                                    }
                                },
                                error: function(xhr, status, error) {
                                    swal.fire(
                                        'Error',
                                        'Data telah digunakan dalam Transaksi Lain',
                                        'warning'
                                    );
                                }
                            });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Gagal menghapus data',
                                icon: 'error',
                                showCloseButton: true
                            });
                        }
                    });
            });
            var tabel_sakit = $('#tabel_sakit').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                ajax: {
                    'url': '/karyawan/sakit/data/sakit',
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tgl_cek'
                    },
                    {
                        data: 'x',
                    },
                    {
                        data: 'y',
                    },
                    {
                        data: 'z'
                    },
                    {
                        data: 'analisa',
                        className: 'minimizechar'
                    },
                    {
                        data: 'diagnosa',
                        className: 'minimizechar'
                    },
                    {
                        data: 'detail_button'
                    },
                    {
                        data: null,
                        render: function(data, type, row){
                            var btn = '';
                            if (row.keputusan == "Lanjut bekerja") {
                                btn += '<span class="badge green-text">';
                            } else {
                                btn += '<span class="badge red-text">';
                            }
                            btn += row.keputusan + '</span>';
                            return btn;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row){
                            var btn = '<div class="btn-group"><span><button type="button" id="detail_tindakan"  class="btn btn-xs btn-outline-info m-1"><i class="fas fa-eye"></i> Detail</button></span>';
                            if (row.keputusan == 'Dipulangkan') {
                                if(divisi_id == '28'){
                                btn += '<a href="/karyawan/sakit/cetak/' + row.id + '" target="_break"><button type="button" class="btn btn-xs btn-warning m-1" id="cetak_gcu"><i class="fas fa-print"></i> Cetak</button></a>';
                                }
                            }
                            if(divisi_id == '28'){
                                btn += '<span><button type="button" id="delete"  data-id="' + row.id + '" class="btn btn-xs btn-danger m-1"><i class="fas fa-trash"></i> Hapus</button></span>';
                            }
                            btn += '</div>';
                            // btn = '<div class="inline-flex"><a href="/karyawan/sakit/cetak/' . $data->id . '" target="_break"><button type="button" id="cetak_gcu"  class="btn btn-block btn-success karyawan-img-small" style="border-radius:50%;" ><i class="fas fa-print"></i></button></a></div>';
                            return btn;
                        }
                    }
                ]
            });
            $('#tabel_sakit > tbody').on('click', '#detail_tindakan', function() {
                var rows = tabel_sakit.rows($(this).parents('tr')).data();
                var keputusan = rows[0]['tindakan'] == "Lanjut Bekerja" ? 'Karyawan Berobat' :
                    'Karyawan Sakit';
                $('.data_detail_head').html(
                    "Karyawan Sakit"
                );
                var diagnosa = rows[0]['diagnosa'] != null ? rows[0]['diagnosa'] :
                    '<i>Tidak Ada Diagnosa</i>';
                var analisa = rows[0]['analisa'] != null ? rows[0]['analisa'] : '<i>Tidak Ada Analisa</i>'
                $('#analisa').html(analisa);
                $('#diagnosa').html(diagnosa);
                $('#pasien').html(rows[0]['y']);
                $('#divisi').html(rows[0]['x']);
                $('#pemeriksa').html(rows[0]['z']);
                $('#tanggal').html(rows[0]['tgl_cek']);
                if (rows[0]['tindakan'] == "Pengobatan") {
                    $('#detail_obat').removeClass('d-none');
                    $('#detail_terapi').addClass('d-none');
                    $('#tabel_detail_obat').DataTable({
                        processing: true,
                        destroy: true,
                        serverSide: false,
                        language: {
                            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                        },
                        ajax: '/karyawan/sakit/obat/detail/' + rows[0]['id'],
                        columns: [{
                                data: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'x',
                            },
                            {
                                data: 'jumlah',
                            },
                            {
                                data: 'aturan',
                            },
                            {
                                data: 'konsumsi',
                            },
                            {
                                data: 'aksi',
                                visible: divisi_id == '28' ? true : false
                            },
                        ],
                    });
                } else {
                    $('#detail_obat').addClass('d-none');
                    $('#detail_terapi').removeClass('d-none');
                    $('#terapi').html(rows[0]['terapi']);
                }
                $('#detail_mod').modal('show');
                // $('input[id="nama_obat"]').val(rows[0]['o']);
                // $('input[id="aturan"]').val(rows[0]['d']);
                // $('input[id="konsumsi"]').val(rows[0]['e']);
                // $('input[id="terapi"]').val(rows[0]['f']);
            });
        });
    </script>
@endsection
