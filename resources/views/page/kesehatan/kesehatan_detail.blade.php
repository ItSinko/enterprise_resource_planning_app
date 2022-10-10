@extends('adminlte.page')
@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Detail</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kesehatan.dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/kesehatan">Kesehatan Awal</a></li>
                    <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('adminlte_css')
<style>
    table { border-collapse: collapse; empty-cells: show; }

    td { position: relative; }

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
<section class="content-header">
    <div class="container-fluid">
    </div>
</section>
<style>
    th {
        text-align: center;
    }
</style>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label for="no_pemeriksaan" class="col-sm-4 col-form-label" style="text-align:right;">Nama Karyawan</label>
                    <div class="col-sm-8">
                        <select type="text" class="form-control @error('divisi') is-invalid @enderror select2" name="divisi" style="width:45%;" id="karyawan_id">
                            <option value="0">Pilih Data</option>
                            @foreach ($karyawan as $k)
                            <option value="{{$k->id}}">{{$k->nama}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('divisi'))
                        <div class="text-danger">
                            {{ $errors->first('divisi')}}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{url('assets/image/user')}}/index.png" alt="User profile picture">
                        </div>
                        <h3 class="profile-username text-center" id="nama">-</h3>
                        <p class="text-muted text-center" id="divisi">-</p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Umur</b> <a class="float-right" id="umur">- Tahun</a>
                            </li>
                            <li class="list-group-item">
                                <b>Kelamin</b> <a class="float-right" id="kelamin">-</a>
                            </li>
                            <li class="list-group-item">
                                <b>Tinggi</b> <a class="float-right" id="tinggi">- Cm</a>
                            </li>
                            <li class="list-group-item">
                                <b>Buta Warna</b> <a class="float-right" id="butawarna">-</a>
                            </li>
                            <li class="list-group-item">
                                <b>Rabun Mata</b> <a class="float-right" id="matakiri">(kiri)</a>
                            </li>
                            <li class="list-group-item">
                                <a class="float-right" id="matakanan">(kanan)</a>
                            </li>
                            <li class="list-group-item">
                                <b>Vaksin</b> <a class="float-right" id="status_vaksin">-</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#berat" data-toggle="tab"><i class="fas fa-weight"></i> Berat badan</a></li>
                            <li class="nav-item"><a class="nav-link" href="#vaksin" data-toggle="tab"><i class="fas fa-syringe"></i> Riwayat vaksin</a></li>
                            <li class="nav-item"><a class="nav-link" href="#penyakit" data-toggle="tab"><i class="fas fa-head-side-cough"></i> Riwayat penyakit</a></li>
                            <li class="nav-item"><a class="nav-link" href="#obat" data-toggle="tab"><i class="fas fa-tablets"></i> Riwayat permintaan obat</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="berat">
                                <div class='table-responsive'>
                                    <table id="tabel_berat" class="table table-hover styled-table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Bulan</th>
                                                <th>Berat</th>
                                                <th>Fat</th>
                                                <th>Tbw</th>
                                                <th>Muscle</th>
                                                <th>Bone</th>
                                                <th>Kalori</th>
                                                <th>Catatan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody style="text-align: center;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="vaksin">
                                <div class='table-responsive'>
                                    <table class="table table-hover styled-table table-striped" width="100%" id="tabel_detail">
                                        <thead>
                                            <tr>
                                                <th width="1%">No</th>
                                                <th>Tgl</th>
                                                <th>Dosis</th>
                                                <th>Tahap</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody style="text-align: center;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="penyakit">
                                <div class='table-responsive'>
                                    <table class="table table-hover styled-table table-striped" width="100%" id="tabel_detail_penyakit">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Penyakit</th>
                                                <th>Jenis Penyakit</th>
                                                <th>Kriteria</th>
                                                <th>Keterangan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody style="text-align: center;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="obat">
                                <div class='table-responsive'>
                                    <table class="table table-hover styled-table table-striped" width="100%" id="tabel_obat">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Nama</th>
                                                <th>Analisa</th>
                                                <th>Jumlah</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody style="text-align: center;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@section('adminlte_js')
<script>
    $(function() {
        $('#tabel_berat > tbody').on('click', '#delete', function() {
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
        $('.select2').select2();
        var karyawan_id = 0;
        var tabel_berat = $('#tabel_berat').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'dataType': 'json',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                'url': '/kesehatan/bulanan/berat/detail/' + karyawan_id,
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tgl_cek',
                    render: function (data, type, row) {
                        return moment(new Date(data).toString()).format('DD-MM-YYYY');
                    }
                },
                {
                    data: 'z'
                },
                {
                    data: 'l'
                },
                {
                    data: 'k'
                },
                {
                    data: 'o'
                },
                {
                    data: 't'
                },
                {
                    data: 'ka'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'aksi'
                }
            ]
        });
        $('#tabel_detail > tbody').on('click', '#delete', function() {
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
        var vaksin_karyawan = $('#tabel_detail').DataTable({
            processing: true,
            serverSide: false,
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            ajax: {
                'dataType': 'json',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                'url': '/kesehatan/vaksin/' + karyawan_id,
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tgl',
                    render: function (data, type, row) {
                        return moment(new Date(data).toString()).format('DD-MM-YYYY');
                    }
                },
                {
                    data: 'dosis'
                },
                {
                    data: 'tahap'
                },
                {
                    data: 'aksi'
                }
            ],
        });
        $('#tabel_detail_penyakit > tbody').on('click', '#delete', function() {
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
        $('#tabel_detail_penyakit').DataTable({
            processing: true,
            serverSide: false,

            ajax: {
                'dataType': 'json',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                'url': '/kesehatan/penyakit/' + karyawan_id,
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },

            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama'
                },
                {
                    data: 'jenis'
                },
                {
                    data: 'kriteria_penyakit'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'aksi'
                }
            ],
        });
        $('#tabel_obat > tbody').on('click', '#delete', function() {
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
        $('#tabel_obat').DataTable({
            processing: true,
            serverSide: false,
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            ajax: {
                'dataType': 'json',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                'url': '/obat/data/detail/' + karyawan_id,
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'tgl_cek',
                    orderable: false,

                }, {
                    data: 'nama_obat',

                }, {
                    data: 'diag',
                    orderable: false,

                }, {
                    data: 'jumlah_obat',
                    orderable: false,
                    searchable: false
                }, {
                    data: 'aksi'
                }

            ],
        });
    });

    $('#karyawan_id').change(function() {
        var karyawan_id = $(this).val();
        $('#tabel_detail_penyakit').DataTable().ajax.url('/kesehatan/penyakit/' + karyawan_id).load();
        $('#tabel_detail').DataTable().ajax.url('/kesehatan/vaksin/' + karyawan_id).load();
        $('#tabel_berat').DataTable().ajax.url('/kesehatan/bulanan/berat/detail/' + karyawan_id).load();
        $('#tabel_obat').DataTable().ajax.url('/obat/data/detail/' + karyawan_id).load();
        $.ajax({
            url: "/kesehatan/data/" + karyawan_id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                 $("#nama").text(data.nama);
                 $("#divisi").text(data.divisi);
                 $("#kelamin").text(data.jenis);
                $("#tinggi").text(data.tinggi);
                $("#butawarna").text(data.status_mata);
                $('#matakiri').text(data.mata_kiri);
                $('#matakanan').text(data.mata_kiri);
                $('#status_vaksin').text(data.status_vaksin);
                $("#umur").text(data.umur);
            }
        });
    });
</script>
@endsection
