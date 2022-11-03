@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Kesehatan Bulanan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kesehatan.dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Kesehatan Bulanan</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop
@section('adminlte_css')
    <style>
        table {
            border-collapse: collapse;
            empty-cells: show;
        }

        td {
            position: relative;
        }

        .align-right {
            text-align: right;
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
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session()->get('success') }}
                </div>
            @elseif(session()->has('error') || count($errors) > 0)
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    Data gagal di tambahkan
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pills-data-tab" data-toggle="pill" href="#pills-data"
                                role="tab" aria-controls="pills-data" aria-selected="true">Data</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-detail-tab" data-toggle="pill" href="#pills-detail" role="tab"
                                aria-controls="pills-detail" aria-selected="false">Detail</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-data" role="tabpanel"
                            aria-labelledby="pills-data-tab">
                            <div class="form-group row">
                                <label for="no_pemeriksaan" class="col-sm-4 col-form-label"
                                    style="text-align:right;">Data</label>
                                <div class="col-sm-8">
                                    <select type="text" class="form-control @error('form') is-invalid @enderror select2"
                                        name="form" style="width:45%;" id="form">
                                        <option value="">Pilih Data</option>
                                        <option value="berat">Berat Badan</option>
                                        <option value="gcu">GCU (Glucose, Cholesterol, Uric ACID)</option>
                                    </select>
                                    <div id="detail_gagal" class="form-text">
                                        Data yang dicari tidak ada
                                    </div>
                                </div>
                            </div>
                            <div class='table-responsive'>
                                <table id="berat_tabel_data" class="table table-hover styled-table table-striped"
                                    style="display:none">
                                    <thead style="text-align: center;">
                                        @if(Auth::user()->divisi_id == '28')
                                        <tr>
                                            <th colspan="16">
                                                <a href="/kesehatan/bulanan/berat/tambah" style="color: white;"><button
                                                        type="button" class="btn btn-block btn-success btn-sm"
                                                        style="width: 200px;"><i class="fas fa-plus"></i> &nbsp;
                                                        Tambah</i></button></a>
                                            </th>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>No</th>
                                            <th>Tgl Cek</th>
                                            <th>Divisi</th>
                                            <th>Nama</th>
                                            <th>Berat</th>
                                            <th>Fat</th>
                                            <th>Tbw</th>
                                            <th>Muscle</th>
                                            <th>Bone</th>
                                            <th>Kalori</th>
                                            <th>Suhu</th>
                                            <th>Spo2</th>
                                            <th>Pr</th>
                                            <th>Sistolik</th>
                                            <th>Diastolik</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                    </tbody>
                                </table>
                                <table id="gcu_tabel_data" class="table table-hover styled-table table-striped"
                                    style="display:none">
                                    <thead style="text-align: center;">
                                        @if(Auth::user()->divisi_id == '28')
                                        <tr>
                                            <th colspan="12">
                                                <a href="/kesehatan/bulanan/gcu/tambah" style="color: white;"><button
                                                        type="button" class="btn btn-block btn-success btn-sm"
                                                        style="width: 200px;"><i class="fas fa-plus"></i> &nbsp;
                                                        Tambah</i></button></a>
                                            </th>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Divisi</th>
                                            <th>Nama</th>
                                            <th>Glucose</th>
                                            <th>Cholesterol</th>
                                            <th>Uric Acid</th>
                                            <th>Catatan</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-detail" role="tabpanel" aria-labelledby="pills-detail-tab">
                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <div class="col-lg-12">
                                        {{ csrf_field() }}
                                        <div class="card">
                                            <div class="card-header bg-success">
                                                <div class="card-title">Chart</div>
                                            </div>
                                            <div class="card-body">
                                                <div class='table-responsive'>
                                                    <div class="col-lg-12">
                                                        <div class="form-group row">
                                                            <label for="no_pemeriksaan" class="col-sm-4 col-form-label"
                                                                style="text-align:right;">Nama Karyawan</label>
                                                            <div class="col-sm-8">
                                                                <select type="text"
                                                                    class="form-control @error('divisi') is-invalid @enderror select2"
                                                                    name="divisi" style="width:45%;" id="karyawan_id">
                                                                    <option value="0">Pilih Data</option>
                                                                    @foreach ($karyawan as $k)
                                                                        <option value="{{ $k->id }}">
                                                                            {{ $k->nama }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @if ($errors->has('divisi'))
                                                                    <div class="text-danger">
                                                                        {{ $errors->first('divisi') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <!-- LINE CHART -->
                                                                <div class="card card-info">
                                                                    <div class="card-header">
                                                                        <h3 class="card-title">GCU</h3>
                                                                        <div class="card-tools">
                                                                            <button type="button" class="btn btn-tool"
                                                                                data-card-widget="collapse">
                                                                                <i class="fas fa-minus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="card-body">
                                                                            <canvas id="gcu_chart"></canvas>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <!-- LINE CHART -->
                                                                <div class="card card-info">
                                                                    <div class="card-header">
                                                                        <h3 class="card-title">Berat Badan</h3>
                                                                        <div class="card-tools">
                                                                            <button type="button" class="btn btn-tool"
                                                                                data-card-widget="collapse">
                                                                                <i class="fas fa-minus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="card-body">
                                                                            <canvas id="berat_chart"></canvas>
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
                                </div>
                                <div class="col-lg-6">
                                    <form action="/kesehatan_harian/aksi_tambah" method="post">
                                        {{ csrf_field() }}
                                        <div class="card">
                                            <div class="card-header bg-success">
                                                <div class="card-title">GCU</div>
                                            </div>
                                            <div class="card-body">
                                                <div class='table-responsive'>
                                                    <table id="tensi_tabel"
                                                        class="table table-hover styled-table table-striped"
                                                        style="width:100%;">
                                                        <thead style="text-align: center;">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Tgl Pengecekan</th>
                                                                <th>Glucose</th>
                                                                <th>Cholesterol</th>
                                                                <th>Uric Acid</th>
                                                                <th>Catatan</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="text-align: center;">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                                <div class="col-lg-6">
                                    <form action="/kesehatan/harian/aksi_tambah" method="post">
                                        {{ csrf_field() }}
                                        <div class="card">
                                            <div class="card-header bg-success">
                                                <div class="card-title">Berat Badan</div>
                                            </div>
                                            <div class="card-body">
                                                <div class='table-responsive'>
                                                    <table id="berat_tabel"
                                                        class="table table-hover styled-table table-striped"
                                                        style="width:100%;">
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
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="text-align: center;">
                                                        </tbody>
                                                    </table>
                                                </div>
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
    <!-- Modal Detail -->
    <div class="modal fade  bd-example-modal-xl" id="detail_mod_berat" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <form method="post" action="/kesehatan/bulanan/berat/aksi_ubah">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            {{-- <div class="data_detail_head_gcu"></div> --}}
                            Ubah Pemeriksaan
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-warning" role="alert">
                                    Hasil Pemeriksaan <b class="data_detail_head_gcu"></b> pada <b id="tgl"></b>
                                </div>
                                <div class="form-horizontal">
                                    <div class="card card-outline card-warning">
                                        <div class="card-header">
                                            <h6 class="card-title">Komposisi Tubuh</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="tgl_cek" class="col-lg-5 col-form-label align-right">Berat &
                                                    Lemak</label>
                                                <div class="col-lg-4">
                                                    <input type="text" class="form-control d-none" name="id"
                                                        id="id">
                                                    <div class="row">
                                                        <div class="input-group col-6">
                                                            <input type="text" class="form-control berat"
                                                                name="berat" id="berat" placeholder="Berat">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Kg</span>
                                                            </div>
                                                        </div>
                                                        <div class="input-group col-6">
                                                            <input type="text" class="form-control lemak"
                                                                name="lemak" id="lemak" placeholder="Lemak">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">gram</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tgl_cek"
                                                    class="col-lg-5 col-form-label align-right">Kandungan Air</label>
                                                <div class="input-group col-lg-2">
                                                    <input type="text" class="form-control kandungan_air"
                                                        name="kandungan_air" id="kandungan_air" placeholder="Air">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tgl_cek" class="col-lg-5 col-form-label align-right">Otot &
                                                    Tulang</label>
                                                <div class="col-lg-4">
                                                    <div class="row">
                                                        <div class="input-group col-6">
                                                            <input type="text" class="form-control otot"
                                                                name="otot" id="otot" placeholder="Otot">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Kg</span>
                                                            </div>
                                                        </div>
                                                        <div class="input-group col-6">
                                                            <input type="text" class="form-control tulang"
                                                                name="tulang" id="tulang" placeholder="Tulang">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">Kg</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tgl_cek"
                                                    class="col-lg-5 col-form-label align-right">Kalori</label>
                                                <div class="input-group col-2">
                                                    <input type="text" class="form-control kalori" name="kalori"
                                                        id="kalori" placeholder="Kalori">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">kkal</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="catatan"
                                                    class="col-lg-5 col-form-label align-right">Catatan</label>
                                                <div class="col-lg-4">
                                                    <textarea class="form-control catatan col-form-label" name="catatan" id="catatan"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-danger" data-dismiss="modal">Batal</button>
                                            <button class="btn btn-warning float-right" id="button_tambah"
                                                onclick="return confirm('Data akan di ubah ?');">Simpan</button>
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
    <!-- Modal Detail -->
    <div class="modal fade  bd-example-modal-lg" id="detail_mod_gcu" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <form method="post" action="/kesehatan/bulanan/gcu/aksi_ubah" id>
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            Ubah Pemeriksaan
                            {{-- <div class="data_detail_head_gcu"></div> --}}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            Hasil Pemeriksaan GCU <b class="data_detail_head_gcu"></b> pada <b id="tgl"></b>
                        </div>
                        <div class="card card-outline card-warning">
                            <div class="card-header">
                                <h6 class="card-title">GCU</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <input type="text" class="form-control d-none" name="id"
                                                    id="id">
                                                <label for="glukosa"
                                                    class="col-lg-4 col-md-12 col-form-label align-right">Glucose</label>
                                                <div class="input-group col-lg-4">

                                                    <input type="text" class="form-control glukosa" name="glukosa"
                                                        id="glukosa" placeholder="Glucose">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">mg/dl</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="kolesterol"
                                                    class="col-lg-4 col-md-12 col-form-label align-right">Cholestrol</label>
                                                <div class="input-group col-lg-4">
                                                    <input type="text" class="form-control kolesterol"
                                                        name="kolesterol" id="kolesterol" placeholder="Cholestrol">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">mg/dl</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="asam_urat"
                                                    class="col-lg-4 col-md-12 col-form-label align-right">Uric ACID</label>
                                                <div class="input-group col-lg-4">
                                                    <input type="text" class="form-control asam_urat" name="asam_urat"
                                                        id="asam_urat" placeholder="Uric ACID">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">mg/dl</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-danger" data-dismiss="modal">Batal</button>
                                <button class="btn btn-warning float-right" id="button_tambah"
                                    onclick="return confirm('Data akan di ubah ?');">Simpan</button>
                            </div>
                        </div>

                        {{-- <div class="data_detail">
            <table style="text-align: center;" class="table table-hover styled-table table-striped" width="100%" id="tabel_detail_gcu">
              <thead>
                <tr>
                  <th></th>
                  <th colspan="4">Pengukuran GCU (Glucose, Cholesterol, Uric ACID)</th>
                </tr>
                <tr>
                  <th>Tgl Cek</th>
                  <th>Glucose</th>
                  <th>Cholesterol</th>
                  <th>Uric Acid</th>
                  <th>Catatan</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <input type="text" class="form-control" readonly id="tgl">
                  </td>
                  <td>
                    <div class="input-group mb-3">

                      <input type="text" class="form-control" name="glukosa" id="glukosa">
                      <div class="input-group-append">
                        <span class="input-group-text">mg/dl</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" name="kolesterol" id="kolesterol">
                      <div class="input-group-append">
                        <span class="input-group-text">mg/dl</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" name="asam_urat" id="asam_urat">
                      <div class="input-group-append">
                        <span class="input-group-text">mg/dl</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <textarea type="text" class="form-control" name="catatan" id="catatan"></textarea>
                  </td>
                </tr>
              </tbody>
            </table>
          </div> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Modal Detail -->
@stop
@section('adminlte_js')
    <script>
        var divisi_id = '{{Auth::user()->divisi_id}}';
        $(function() {
            $('#berat_tabel_data > tbody').on('click', '#delete', function() {
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
                                url: '/kesehatan/bulanan/berat/delete/' + data_id,
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
                                        $('#berat_tabel_data').DataTable().ajax.reload();
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
                        }
                    });
            });

            $('#gcu_tabel_data > tbody').on('click', '#delete', function() {
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
                                url: '/kesehatan/bulanan/gcu/delete/' + data_id,
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
                                        $('#gcu_tabel_data').DataTable().ajax.reload();
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
        })
        $('#form').change(function() {
            var form = $(this).val();
            if (form == 'berat') {
                var gcu = $('#gcu_tabel_data').DataTable();
                gcu.destroy();
                $("#gcu_tabel_data").hide();
                $("#detail_gagal").hide();
                $("#berat_tabel_data").show();
                // $(function() {
                var berat_tabel = $('#berat_tabel_data').DataTable({
                    processing: true,
                    serverSide: true,
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    ajax: {
                        'url': '/kesehatan/bulanan/berat/data',
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
                            data: 'tgl_cek',
                            searchable: true,
                            render: function(data, type, row) {
                                return moment(new Date(data).toString()).format('DD-MM-YYYY');
                            }
                        },
                        {
                            data: 'x',
                            searchable: true
                        },
                        {
                            data: 'y',
                            searchable: true,
                        },
                        {
                            data: 'z',
                            searchable: true,
                        },
                        {
                            data: 'l',
                            searchable: true,
                        },
                        {
                            data: 'k',
                            searchable: true,
                        },
                        {
                            data: 'o',
                            searchable: true,
                        },
                        {
                            data: 't',
                            searchable: true,
                        },
                        {
                            data: 'ka',
                            searchable: true,
                        },
                        {
                            data: 'suhu_k',
                            searchable: true,
                        },
                        {
                            data: 'sp',
                            searchable: true,
                        },
                        {
                            data: 'pr',
                            searchable: true,
                        },
                        {
                            data: 'sis',
                            searchable: true,
                        },
                        {
                            data: 'dias',
                            searchable: true,
                        },
                        {
                            data: 'button',
                            visible: divisi_id == '28' ? true : false
                        },
                    ]
                });


                // $(function() {
                $('#berat_tabel_data > tbody').on('click', '#edit_berat', function() {
                    var rows = berat_tabel.rows($(this).parents('tr')).data();
                    console.log(rows);
                    $('input[id="id"]').val(rows[0]['id']);
                    $('textarea[id="catatan"]').val(rows[0]['keterangan']);
                    $('.data_detail_head_gcu').text(rows[0].y);
                    $('#tgl').text(rows[0]['tgl_cek']);
                    $('input[id="berat"]').val(rows[0]['berat']);
                    $('input[id="lemak"]').val(rows[0]['lemak']);
                    $('input[id="kandungan_air"]').val(rows[0]['kandungan_air']);
                    $('input[id="otot"]').val(rows[0]['otot']);
                    $('input[id="lemak"]').val(rows[0]['lemak']);
                    $('input[id="tulang"]').val(rows[0]['tulang']);
                    $('input[id="kalori"]').val(rows[0]['kalori']);
                    $('#detail_mod_berat').modal('show');
                });
                // });
                //   });


            } else if (form == 'gcu') {
                var berat = $('#berat_tabel_data').DataTable();
                berat.destroy();
                $("#detail_gagal").hide();
                $("#berat_tabel_data").hide();
                $("#gcu_tabel_data").show();

                $(function() {
                    var gcu_tabel = $('#gcu_tabel_data').DataTable({
                        processing: true,
                        serverSide: true,
                        language: {
                            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                        },
                        ajax: {
                            url: '/kesehatan/bulanan/gcu/data',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'tgl_cek',
                                render: function(data, type, row) {
                                    return moment(new Date(data).toString()).format(
                                        'DD-MM-YYYY');
                                }
                            },
                            {
                                data: 'x'
                            },
                            {
                                data: 'karyawan.nama'
                            },
                            {
                                data: 'glu',
                                render: function(data) {
                                    $l = '<br><span class="badge bg-danger">' + data +
                                        '</span>';
                                    $n = '<br><span class="badge bg-success">' + data +
                                        '</span>';
                                    $w = '<br><span class="badge bg-warning">' + data +
                                        '</span>';

                                    if (data >= 200) {
                                        return 'Diabetes' + $l;
                                    } else if (data < 200) {
                                        return 'Normal' + $n;;
                                    } else if (data >= 140 && data <= 199) {
                                        return 'Pra Diabetes' + $w;
                                    } else {
                                        return '';
                                    }
                                }
                            },
                            {
                                data: 'kol',
                                render: function(data) {
                                    $l = '<br><span class="badge bg-danger">' + data +
                                        '</span>';
                                    $n = '<br><span class="badge bg-success">' + data +
                                        '</span>';
                                    $w = '<br><span class="badge bg-warning">' + data +
                                        '</span>';
                                    if (data > 239) {
                                        return 'Bahaya' + $l;
                                    } else if (data < 200) {
                                        return 'Normal' + $n;
                                    } else if (data >= 200 && data <= 239) {
                                        return 'Hati hati' + $w;
                                    } else {
                                        return '';
                                    }
                                }
                            },
                            {
                                data: 'asam',
                                render: function(data) {
                                    $l = '<br><span class="badge bg-danger">' + data +
                                        '</span>';
                                    $n = '<br><span class="badge bg-success">' + data +
                                        '</span>';
                                    $w = '<br><span class="badge bg-warning">' + data +
                                        '</span>';

                                    if (data >= 2 && data <= 7.5) {
                                        return 'Normal' + $n;
                                    } else if (data > 7.5) {
                                        return 'Asam Urat' + $l;
                                    } else {
                                        return '';
                                    }
                                }
                            },
                            {
                                data: 'keterangan'
                            },
                            {
                                data: 'button',
                                visible: divisi_id == '28' ? true : false
                            },
                        ]
                    });
                    $('#gcu_tabel_data > tbody').on('click', '#edit_gcu', function() {
                        var rows = gcu_tabel.rows($(this).parents('tr')).data();
                        console.log(rows);
                        $('input[id="id"]').val(rows[0]['id']);
                        //$('textarea[id="catatan"]').val(rows[0]['keterangan']);
                        $('.data_detail_head_gcu').html(rows[0].karyawan['nama']);
                        $('input[id="tgl"]').val(rows[0]['tgl_cek']);
                        $('input[id="glukosa"]').val(rows[0]['glukosa']);
                        $('input[id="kolesterol"]').val(rows[0]['kolesterol']);
                        $('input[id="asam_urat"]').val(rows[0]['asam_urat']);
                        $('#detail_mod_gcu').modal('show');
                    });
                });
            } else {
                $("#berat_tabel").hide();
                $("#gcu_tabel").hide();
                var berat = $('#berat_tabel').DataTable();
                berat.destroy();
                var gcu = $('#gcu_tabel').DataTable();
                gcu.destroy();
                $("#detail_gagal").show();

            }
        });
        $('.select2').select2({
            allowClear: true,
            placeholder: 'Pilih Data'
        });
    </script>


    {{-- DETAIL --}}
    <script>
        $(function() {
            $('#tensi_tabel > tbody').on('click', '#delete', function() {
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
            $('#berat_tabel > tbody').on('click', '#delete', function() {
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
            var karyawan_id = 0;
            var tensi_tabel = $('#tensi_tabel').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    'url': '/kesehatan/bulanan/gcu/detail/' + karyawan_id,
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
                        render: function(data, type, row) {
                            return moment(new Date(data).toString()).format('DD-MM-YYYY');
                        }
                    },
                    {
                        data: 'glu',
                        render: function(data) {
                            $l = '<br><span class="badge bg-danger">' + data + '</span>';
                            $n = '<br><span class="badge bg-success">' + data + '</span>';
                            $w = '<br><span class="badge bg-warning">' + data + '</span>';

                            if (data >= 200) {
                                return 'Diabetes' + $l;
                            } else if (data < 200) {
                                return 'Normal' + $n;;
                            } else if (data >= 140 && data <= 199) {
                                return 'Pra Diabetes' + $w;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'kol',
                        render: function(data) {
                            $l = '<br><span class="badge bg-danger">' + data + '</span>';
                            $n = '<br><span class="badge bg-success">' + data + '</span>';
                            $w = '<br><span class="badge bg-warning">' + data + '</span>';
                            if (data > 239) {
                                return 'Bahaya' + $l;
                            } else if (data < 200) {
                                return 'Normal' + $n;
                            } else if (data >= 200 && data <= 239) {
                                return 'Hati hati' + $w;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'asam',
                        render: function(data) {
                            $l = '<br><span class="badge bg-danger">' + data + '</span>';
                            $n = '<br><span class="badge bg-success">' + data + '</span>';
                            $w = '<br><span class="badge bg-warning">' + data + '</span>';

                            if (data >= 2 && data <= 7.5) {
                                return 'Normal' + $n;
                            } else if (data > 7.5) {
                                return 'Asam Urat' + $l;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'keterangan'
                    },
                    {
                        data: 'aksi',
                        visible: divisi_id == '28' ? true : false
                    },
                ]
            });



            var berat_tabel = $('#berat_tabel').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                        render: function(data, type, row) {
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
                        data: 'aksi',
                        visible: divisi_id == '28' ? true : false
                    },
                ]
            });

        });
    </script>

    <script>
        //Tensi Sistolik
        var ctx = document.getElementById("gcu_chart");
        var gcu_chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                        label: 'Glucose',
                        data: [],
                        borderWidth: 2,
                        backgroundColor: 'transparent',
                        borderColor: 'red',
                    },
                    {
                        label: 'Colesterol',
                        data: [],
                        borderWidth: 2,
                        backgroundColor: 'transparent',
                        borderColor: 'blue',
                    },
                    {
                        label: 'Uri Acid',
                        data: [],
                        borderWidth: 2,
                        backgroundColor: 'transparent',
                        borderColor: 'black',
                    }
                ]
            },
            options: {
                scales: {
                    xAxes: [],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        //Berat
        var ctx = document.getElementById("berat_chart");
        var berat_chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Berat',
                    data: [],
                    borderWidth: 2,
                    backgroundColor: 'transparent',
                    borderColor: 'blue',
                }, ]
            },
            options: {
                scales: {
                    xAxes: [],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        $('#karyawan_id').change(function() {
            var karyawan_id = $(this).val();
            $('#tensi_tabel').DataTable().ajax.url('/kesehatan/bulanan/gcu/detail/' + karyawan_id).load();
            $('#berat_tabel').DataTable().ajax.url('/kesehatan/bulanan/berat/detail/' + karyawan_id).load();
            var updateChart = function() {
                $.ajax({
                    url: "/kesehatan/bulanan/detail/data/" + karyawan_id,
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        console.log(data);
                        gcu_chart.data.labels = data.tgl;
                        gcu_chart.data.datasets[0].data = data.labels2;
                        gcu_chart.data.datasets[1].data = data.labels3;
                        gcu_chart.data.datasets[2].data = data.labels4;
                        gcu_chart.update();

                        berat_chart.data.labels = data.tgl2;
                        berat_chart.data.datasets[0].data = data.labels5;
                        berat_chart.update();

                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
            updateChart();
        });
        $('.select2').select2();
    </script>
@endsection
