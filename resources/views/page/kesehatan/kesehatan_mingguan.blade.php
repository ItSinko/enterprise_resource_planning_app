@extends('adminlte.page')
@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Kesehatan Mingguan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kesehatan.dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Kesehatan Mingguan</li>
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
<div class="row">
  <div class="col-lg-12">
    @if(session()->has('success'))
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      Data berhasil di ubah
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
                    <a class="nav-link active" id="pills-data-tab" data-toggle="pill" href="#pills-data" role="tab" aria-controls="pills-data" aria-selected="true">Data</a>
                    </li>
                    <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-detail-tab" data-toggle="pill" href="#pills-detail" role="tab" aria-controls="pills-detail" aria-selected="false">Detail</a>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-data" role="tabpanel" aria-labelledby="pills-data-tab">
                        <div class="form-group row">
                            <label for="no_pemeriksaan" class="col-sm-4 col-form-label" style="text-align:right;">Data</label>
                            <div class="col-sm-8">
                                <select type="text" class="form-control @error('form') is-invalid @enderror select2" name="form" style="width:45%;" id="form">
                                    <option value=""></option>
                                    <option value="tensi">Pengukuran Tensi</option>
                                    <option value="rapid">Pengecekan Covid</option>
                                </select>
                                <div id="detail_gagal" class="form-text">Data yang dicari tidak ada</div>
                            </div>
                        </div>
                        <div class='table-responsive'>
                            <table id="tensi_tabel_data" class="table table-hover styled-table table-striped" style="display:none">
                                <thead style="text-align: center;">
                                <tr>
                                    <th colspan="12">
                                    <a href="/kesehatan/mingguan/tensi/tambah" style="color: white;"><button type="button" class="btn btn-block btn-success btn-sm" style="width: 200px;"><i class="fas fa-plus"></i> &nbsp; Tambah</i></button></a>
                                    </th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th colspan="2">Pengukuran Tensi</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>No</th>
                                    <th>Hasil</th>
                                    <th>Tgl Pengecekan</th>
                                    <th>Divisi</th>
                                    <th>Nama</th>
                                    <th>Sistolik</th>
                                    <th>Diastolik</th>
                                    <th>Catatan</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                </tbody>
                            </table>
                            <table id="rapid_tabel_data" class="table table-hover styled-table table-striped" style="display:none">
                                <thead style="text-align: center;">
                                <tr>
                                    <th colspan="12">
                                    <a href="/kesehatan/mingguan/rapid/tambah" style="color: white;"><button type="button" class="btn btn-block btn-success btn-sm" style="width: 200px;"><i class="fas fa-plus"></i> &nbsp; Tambah</i></button></a>
                                    </th>
                                </tr>
                                <tr>
                                    <th>No</th>
                                    <th>Tgl Cek</th>
                                    <th>Pengecekan</th>
                                    <th>Divisi</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Hasil</th>
                                    <th>Catatan</th>
                                    <th>Aksi</th>
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
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <!-- LINE CHART -->
                                                            <div class="card card-info">
                                                                <div class="card-header">
                                                                    <h3 class="card-title">Pengukuran Tensi</h3>
                                                                    <div class="card-tools">
                                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                            <i class="fas fa-minus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="card-body">
                                                                        <canvas id="tensi_sistolik_chart"></canvas>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <!-- LINE CHART -->
                                                            <div class="card card-info">
                                                                <div class="card-header">
                                                                    <h3 class="card-title">Pengukuran Tensi</h3>
                                                                    <div class="card-tools">
                                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                            <i class="fas fa-minus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="card-body">
                                                                        <canvas id="tensi_diastolik_chart"></canvas>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <!-- LINE CHART -->
                                                            <div class="card card-info">
                                                                <div class="card-header">
                                                                    <h3 class="card-title">Rapid Test</h3>
                                                                    <div class="card-tools">
                                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                            <i class="fas fa-minus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="card-body">
                                                                        <canvas id="rapid_chart"></canvas>
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
                                            <div class="card-title">Tensi</div>
                                        </div>
                                        <div class="card-body">
                                            <div class='table-responsive'>
                                                <table id="tensi_tabel" class="table table-hover styled-table table-striped" style="width:100%;">
                                                    <thead style="text-align: center;">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Tgl Pengecekan</th>
                                                            <th>Sistolik</th>
                                                            <th>Diastolik</th>
                                                            <th>Catatan</th>
                                                            <th></th>
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
                                <form action="/kesehatan_harian/aksi_tambah" method="post">
                                    {{ csrf_field() }}
                                    <div class="card">
                                        <div class="card-header bg-success">
                                            <div class="card-title">Rapid</div>
                                        </div>
                                        <div class="card-body">
                                            <div class='table-responsive'>
                                                <table id="rapid_tabel" class="table table-hover styled-table table-striped" style="width:100%;">
                                                    <thead style="text-align: center;">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Tgl Pengecekan</th>
                                                            <th>Pemeriksa</th>
                                                            <th>Hasil Rapid</th>
                                                            <th>Catatan</th>
                                                            <th></th>
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
<div class="modal fade  bd-example-modal-lg" id="detail_mod_tensi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <form method="post" action="/kesehatan/mingguan/tensi/aksi_ubah">
      {{ csrf_field() }}
      {{ method_field('PUT')}}
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">
            <div class="data_detail_head_tensi"></div>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="data_detail">
            <table style="text-align: center;" class="table table-hover styled-table table-striped" width="100%" id="tabel_detail">
              <thead>
                <tr>
                  <th></th>
                  <th colspan="2">Pengukuran Tensi</th>
                  <th></th>
                </tr>
                <tr>
                  <th>Tgl Pengecekan</th>
                  <th>Sistolik</th>
                  <th>Diastolik</th>
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
                      <input type="text" class="form-control d-none" name="id" id="id">
                      <input type="text" class="form-control" name="sistolik" id="sistolik">
                      <div class="input-group-append">
                        <span class="input-group-text">mmHg</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" name="diastolik" id="diastolik">
                      <div class="input-group-append">
                        <span class="input-group-text">mmHg</span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <textarea type="text" class="form-control" name="catatan" id="catatan"></textarea>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success rounded-pill" id="button_tambah" onclick="return confirm('Data akan di ubah ?');"><i class="fas fa-plus"></i>&nbsp;Ubah Data</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- End Modal Detail -->
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-lg" id="detail_mod_rapid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <form method="post" action="/kesehatan/mingguan/rapid/aksi_ubah">
      {{ csrf_field() }}
      {{ method_field('PUT')}}
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">
            <div class="data_detail_head_rapid"></div>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="data_detail">
            <table style="text-align: center;" class="table table-hover styled-table table-striped" width="100%" id="tabel_detail">
              <thead>
                <tr>
                  <th>Tgl Pengecekan</th>
                  <th>Jenis</th>
                  <th>Hasil</th>
                  <th>Catatan</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <input type="text" class="form-control" readonly id="tgl">
                  </td>
                  <td>
                    <div class="form-check form-check-inline">
                      <input type="text" class="form-control d-none" name="id" id="id">
                      <input class="form-check-input" type="radio" name="jenis" id="jenis" value="Rapid">
                      <label class="form-check-label">Rapid</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="jenis" id="jenis" value="Antigen">
                      <label class="form-check-label">Antigen</label>
                    </div>
                  </td>
                  <td>
                    <div id="rapid" hidden>
                      <div class="form-check form-check-inline">
                        <input type="text" class="form-control d-none" name="id" id="id">
                        <input class="form-check-input" type="radio" name="hasil" id="hasil" value="Non reaktif">
                        <label class="form-check-label">Non reaktif</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="hasil" id="hasil" value="IgG">
                        <label class="form-check-label">IgG</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="hasil" id="hasil" value="IgM">
                        <label class="form-check-label">IgM</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="hasil" id="hasil" value="IgG-IgM">
                        <label class="form-check-label">IgG-IgM</label>
                      </div>
                    </div>
                    <div id="antigen" hidden>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="hasil" id="hasil" value="C">
                        <label class="form-check-label">C</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="hasil" id="hasil" value="C/T">
                        <label class="form-check-label">C/T</label>
                      </div>
                    </div>
                  </td>
                  <td>
                    <textarea type="text" class="form-control" name="catatan" id="catatan"></textarea>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success rounded-pill" id="button_tambah" onclick="return confirm('Data akan di ubah ?');"><i class="fas fa-plus"></i>&nbsp;Ubah Data</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- End Modal Detail -->
@stop
@section('adminlte_js')
<script>
    $(function(){
        $('#rapid_tabel_data > tbody').on('click', '#delete', function() {
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
        $('#tensi_tabel_data > tbody').on('click', '#delete', function() {
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
    })
     $('.select2').select2();
  $('#form').change(function() {
    var form = $(this).val();
    if (form == 'tensi') {
      $('#detail_mod_rapid form')[0].reset();
      var rapid = $('#rapid_tabel_data').DataTable();
      rapid.destroy();
      $("#rapid_tabel_data").hide();
      $("#detail_gagal").hide();
      $("#tensi_tabel_data").show();
      $(function() {
        var tensi_tabel = $('#tensi_tabel_data').DataTable({
          processing: true,
          serverSide: false,
          language: {
            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
          },
          ajax: {
            'url': '/kesehatan/mingguan/tensi/data',
            'type': 'POST',
            'headers': {
              'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
          },
          columns: [{
              data: 'DT_RowIndex',
              orderable: false,
              searchable: false
            },
            {
              data: 'hasil'
            },
            {
              data: 'tgl_cek',
              render: function (data, type, row) {
                return moment(new Date(data).toString()).format('DD-MM-YYYY');
              }
            },
            {
              data: 'x'
            },
            {
              data: 'y'
            },
            {
              data: 'sis'
            },
            {
              data: 'dias'
            },
            {
              data: 'keterangan'
            },
            {
              data: 'button'
            },
          ]
        });
        $('#tensi_tabel_data > tbody').on('click', '#edit_tensi', function() {
          var rows = tensi_tabel.rows($(this).parents('tr')).data();
          $('.data_detail_head_tensi').html(rows[0].karyawan['nama']);
          $('input[id="id"]').val(rows[0]['id']);
          $('input[id="sistolik"]').val(rows[0]['sistolik']);
          $('input[id="tgl"]').val(rows[0]['tgl_cek']);
          $('input[id="diastolik"]').val(rows[0]['diastolik']);
          $('textarea[id="catatan"]').val(rows[0]['keterangan']);
          $('#detail_mod_tensi').modal('show');
        });
      });
    } else if (form == 'rapid') {
      $('#detail_mod_tensi form')[0].reset();
      var tensi = $('#tensi_tabel_data').DataTable();
      tensi.destroy();
      $("#detail_gagal").hide();
      $("#tensi_tabel_data").hide();
      $("#rapid_tabel_data").show();

      $(function() {
        var rapid_tabel = $('#rapid_tabel_data').DataTable({
          processing: true,
          serverSide: false,
          language: {
            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
          },
          ajax: {
            'url': '/kesehatan/mingguan/rapid/data',
            'type': 'POST',
            'headers': {
              'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
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
              data: 'x'
            },
            {
              data: 'karyawan.nama'
            },
            {
              data: 'jenis'
            },
            {
              data: 'hasil'
            },
            {
              data: 'keterangan'
            },
            {
              data: 'button'
            },
          ]
        });

        $('#rapid_tabel_data > tbody').on('click', '#edit_rapid', function() {
          var rows = rapid_tabel.rows($(this).parents('tr')).data();
          console.log(rows);
          if (rows[0]['jenis'] == 'Rapid') {
            $('#rapid').removeAttr('hidden');
            $('#antigen').attr('hidden', 'hidden');
          } else {
            $('#antigen').removeAttr('hidden');
            $('#rapid').attr('hidden', 'hidden');
          }
          $('input[id="id"]').val(rows[0]['id']);
          $('textarea[id="catatan"]').val(rows[0]['keterangan']);
          $('.data_detail_head_rapid').html(rows[0].karyawan['nama']);
          $('input[id="tgl"]').val(rows[0]['tgl_cek']);
          $('input[name="hasil"]').removeAttr('checked');
          $('input[name="jenis"]').removeAttr('checked');
          $('input[name="hasil"][value="' + rows[0]['hasil'] + '"]').attr('checked', 'checked');
          $('input[name="jenis"][value="' + rows[0]['jenis'] + '"]').attr('checked', 'checked');
          $('#detail_mod_rapid').modal('show');
        });
      });
    } else {
      $("#tensi_tabel_data").hide();
      $("#rapid_tabel_data").hide();
      var tensi = $('#tensi_tabel_data').DataTable();
      tensi.destroy();
      var rapid = $('#rapid_tabel_data').DataTable();
      rapid.destroy();
      $("#detail_gagal").show();
    }

    $('input[type=radio][name=jenis]').on('change', function() {
      if (this.value == 'Rapid') {
        $('#rapid').removeAttr('hidden');
        $('#antigen').attr('hidden', 'hidden');
        $('input[name="hasil"]').prop('checked', false);
      } else {
        $('#antigen').removeAttr('hidden');
        $('#rapid').attr('hidden', 'hidden');
        $('input[name="hasil"]').prop('checked', false);
      }
    });
    $('#hasil').prop("required", true);


  });
</script>

{{-- DETAIL --}}
<script>
    $(function() {
        var karyawan_id = 0;
        var tensi_tabel = $('#tensi_tabel').DataTable({
            processing: true,
            serverSide: false,
            ajax: '/kesehatan/mingguan/tensi/detail/' + karyawan_id,
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
                    data: 'sis'
                },
                {
                    data: 'dias'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'aksi'
                }
            ]
        });

    });
</script>
<script>
    $(function() {
        var karyawan_id = 0;
        var tensi_tabel = $('#rapid_tabel').DataTable({
            processing: true,
            serverSide: false,
            ajax: '/kesehatan/mingguan/rapid/detail/' + karyawan_id,
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
                    data: 'hasil'
                },
                {
                    data: 'keterangan'
                },
                {
                    data: 'aksi'
                }
            ]
        });

    });
</script>
<script>
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

    $('#rapid_tabel > tbody').on('click', '#delete', function() {
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

    //Tensi Sistolik
    var ctx = document.getElementById("tensi_sistolik_chart");
    var tensi_sistolik_chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Sistolik',
                data: [],
                borderWidth: 2,
                backgroundColor: 'transparent',
                borderColor: 'red',
            }]
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
    //Tenesi Diastolik
    var ctx = document.getElementById("tensi_diastolik_chart");
    var tensi_diastolik_chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Diastolik',
                data: [],
                borderWidth: 2,
                backgroundColor: 'transparent',
                borderColor: 'blue',
            }]
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
    //Rapid
    var ctx = document.getElementById("rapid_chart");
    var rapid_chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                label: ['Non Reaktif', 'IgG', 'IgM', 'IgG-IgM'],
                data: [],
                borderWidth: 3,
                backgroundColor: ['rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(102, 204, 0)',
                    'rgb(255, 102, 78)',
                ],
                hoverOffset: 4,
                borderColor: 'white',
            }]
        }
    });
    $('#karyawan_id').change(function() {
        var karyawan_id = $(this).val();
        $('#tensi_tabel').DataTable().ajax.url('/kesehatan/mingguan/tensi/detail/' + karyawan_id).load();
        $('#rapid_tabel').DataTable().ajax.url('/kesehatan/mingguan/rapid/detail/' + karyawan_id).load();
        var updateChart = function() {
            $.ajax({
                url: "/kesehatan/mingguan/tensi/detail/data/" + karyawan_id,
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log(data);
                    tensi_sistolik_chart.data.labels = data.tgl;
                    tensi_sistolik_chart.data.datasets[0].data = data.labels2;
                    tensi_sistolik_chart.update();

                    tensi_diastolik_chart.data.labels = data.tgl;
                    tensi_diastolik_chart.data.datasets[0].data = data.labels3;
                    tensi_diastolik_chart.update();

                    rapid_chart.data.labels = ['Non Reaktif', 'IgG', 'IgM', 'IgG-IgM'];
                    rapid_chart.data.datasets[0].data = [data.data2, data.data3, data.data4, data.data5];
                    rapid_chart.update();
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
