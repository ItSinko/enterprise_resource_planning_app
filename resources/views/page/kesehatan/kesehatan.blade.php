@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Kesehatan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kesehatan.dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Kesehatan Awal</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('adminlte_css')
<style>
    .align-center{
        text-align: center;
    }
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
      Data berhasil ditambahkan
    </div>
    @elseif(session()->has('error') || count($errors) > 0)
    <div class="alert alert-danger alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      Data gagal ditambahkan
    </div>
    @endif
    <div class="card">
      <div class="card-body">
        <div class='table-responsive'>
          <table id="tabel" class="table table-hover styled-table table-striped align-center">
            <thead>
              <tr>
                <th colspan="14">
                  <a href="/kesehatan/tambah" style="color: white;"><button type="button" class="btn btn-block btn-success btn-sm" style="width: 200px;"><i class="fas fa-plus"></i> &nbsp; Tambah</i></button></a>
                </th>
              </tr>
              <tr>
                <th>No</th>
                <th>Divisi</th>
                <th>Nama</th>
                <th>Umur</th>
                <th>Berat</th>
                <th>Tinggi</th>
                <th>Vaksin</th>
                <th>Buta warna</th>
                <th>Aksi</th>
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
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-xl" id="berat_mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xl" role="document">
    <div class="card-body">
      <form method="post" action="/kesehatan_harian_mingguan_tensi/aksi_ubah">
        {{ csrf_field() }}
        {{ method_field('PUT')}}
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">
              <div class="data_detail_head"></div>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <table class="table table-hover styled-table table-striped" width="100%" id="tabel_berat">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tgl Cek</th>
                  <th>Berat</th>
                  <th>Lemak</th>
                  <th>Kandungan_air</th>
                  <th>Otot</th>
                  <th>Tulang</th>
                  <th>Kalori</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal Detail -->
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-lg" id="vaksin_mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="card-body">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">
            <div class="data_detail_head"></div>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <table class="table table-hover styled-table table-striped align-center" width="100%" id="tabel_detail">
            <thead>
              <tr>
                <th colspan="12">
                  <button type="button" class="btn btn-block btn-success btn-sm" style="width: 200px;" data-target="#tambah_mod" data-toggle="modal" data-dismiss="modal"><i class="fas fa-plus"></i> &nbsp;Tambah Data</i></button>
                </th>
              </tr>
              <tr>
                <th>No</th>
                <th>Tgl</th>
                <th>Dosis</th>
                <th>Tahap</th>
                <th></th>
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
<!-- End Modal Detail -->
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-xl" id="tambah_mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">
                <div class="data_detail_head"></div>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="data_detail">
                    <div class="row">
                        <div class="col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-header"><b class="card-title">Form Tambah Vaksin</b></div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="date_form">Vaksin</label>
                                        <input type="date" class="form-control date" name="date_form" id="date_form" width="50%">
                                    </div>
                                    <div class="form-group">
                                        <label for="dosis_form">Dosis Vaksin</label>
                                        <select class="form-control select2 dosis" name="dosis_form" id="dosis_form" placeholder="Pilih Jenis Vaksin">
                                            <option value=""></option>
                                            <option value="Astrazeneca">Astrazeneca</option>
                                            <option value="Janssen">Janssen</option>
                                            <option value="Moderna">Moderna</option>
                                            <option value="Pfizer">Pfizer</option>
                                            <option value="Sinovac">Sinovac</option>
                                            <option value="Sinopharm">Sinopharm</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Urutan Vaksin</label>
                                        <select class="form-control select2 ket" name="ket_form" id="ket_form">
                                            <option value=""></option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-outline-warning btn-sm" id="btnreset_vaksin">Reset Form</button>
                                    <button type ="button" class="btn btn-outline-secondary btn-sm float-right" id="tambahitem_vaksin">Tambahkan ke Draf</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12">
                        <form method="post" action="/kesehatan/vaksin/aksi_tambah/2">
                        @csrf
                            <div class="card">
                                <div class="card-header">
                                    <b class="card-title">Draft Data Vaksin</b>
                                </div>
                                <div class="card-body">
                                    <input type="text" name="fk_karyawan_id" class="d-none form-control" id="fk_karyawan_id" readonly>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped align-center" id="tabel_vaksin">
                                            <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tgl Vaksin</th>
                                                <th>Dosis</th>
                                                <th>Vaksin ke </th>
                                                <th>Aksi</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td colspan="5">Belum Ada Data Ditambahkan</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-sm btn-danger" id="btnbatal" data-dismiss="modal">Batal</button>
                                    <button class="btn btn-primary float-right" id="button_tambah" type="submit">Simpan Data</button></span>
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
<!-- End Modal Detail -->
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-xl" id="penyakit_mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xl" role="document">
    <div class="card-body">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">
            <div class="data_detail_head"></div>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <table class="table table-hover styled-table table-striped align-center" width="100%" id="tabel_detail_penyakit">
            <thead>
              <tr>
                <th colspan="12">
                  <button type="button" class="btn btn-block btn-success btn-sm" style="width: 200px;" data-target="#tambah_mod_penyakit" data-toggle="modal" data-dismiss="modal"><i class="fas fa-plus"></i> &nbsp;Tambah Data</i></button>
                </th>
              </tr>
              <tr>
                <th>No</th>
                <th>Nama Penyakit</th>
                <th>Jenis Penyakit</th>
                <th>Kriteria</th>
                <th>Keterangan</th>
                <th></th>
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
<!-- End Modal Detail -->
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-xl" id="tambah_mod_penyakit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">
          <div class="data_detail_head"></div>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="data_detail">
            <div class="row">
                <div class="col-md-12 col-lg-4">
                    <div class="card card-outline card-primary">
                        <div class="card-header"><b class="card-title">Form Tambah Penyakit</b></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nama Penyakit</label>
                                <textarea type="text" class="form-control nama_form" name="nama_form" id="nama_form"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="jenis">Jenis Penyakit</label>
                                <select class="form-control select2 jenis" style="width:100%" name="jenis_form" id="jenis_form" placeholder="Pilih jenis penyakit">
                                    <option value=""></option>
                                    <option value="Penyakit saat ini">Penyakit saat ini</option>
                                    <option value="Penyakit lama">Penyakit lama</option>
                                    <option value="Penyakit keluarga">Penyakit keluarga</option>
                                    <option value="Penyakit karena pekerjaan">Penyakit karena pekerjaan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="jenis">Kriteria</label>
                                <div class="form-group">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="kriteria_form" id="kriteria_form" value="1">
                                        <label class="form-check-label">Menular</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="kriteria_form" id="kriteria_form" value="0">
                                        <label class="form-check-label">Tidak menular</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Keterangan</label>
                                <textarea type="text" class="form-control keterangan_form" name="keterangan_form" id="keterangan_form"></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-outline-warning btn-sm" id="btnreset_penyakit">Reset Form</button>
                            <button class="btn btn-outline-secondary btn-sm float-right" id="tambahitem_penyakit">Tambahkan ke Draf</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-8">
                    <div class="card card-outline card-info">
                        <form method="post" action="/kesehatan/riwayat_penyakit/aksi_tambah">
                        @csrf
                        <div class="card-header"><b class="card-title">Draft Daftar Penyakit</b></div>
                        <div class="card-body">
                            <input type="text" name="fk_karyawan_id" class="d-none form-control" id="fk_karyawan_id" readonly>
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped align-center" id="tabel_penyakit">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="22%">Nama Penyakit</th>
                                        <th width="30%">Jenis Penyakit</th>
                                        <th width="15%">Kriteria</th>
                                        <th width="23%">Keterangan</th>
                                        <th width="5%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6">Data Tidak Tersedia</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-sm btn-danger" id="btnbatal" data-dismiss="modal">Batal</button>
                            <button class="btn btn-primary float-right" id="button_tambah" type="submit">Simpan Data</button></span>
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
<!-- End Modal Detail -->
@stop
@section('adminlte_js')
<script>
  $(function() {
    $('#tabel_detail_penyakit > tbody').on('click', '#delete', function() {
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
                        url: '/kesehatan/riwayat_penyakit/delete/'+data_id,
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
                                $('#tabel_detail_penyakit').DataTable().ajax.reload();
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

    $('#tabel_detail > tbody').on('click', '#delete', function() {
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
                        url: '/kesehatan/riwayat_penyakit/delete/'+data_id,
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
            }
        });
    });

    var tabel = $('#tabel').DataTable({
      processing: true,
      serverSide: true,
      language: {
        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
      },
      ajax: {
        'url': '/kesehatan/data',
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
          data: 'divisi'
        },
        {
          data: 'nama',
          searchable: true
        },
        {
          data: 'umur'
        },
        {
          data: 'berat_kg'
        },
        {
          data: 'tinggi_cm'
        },
        {
          data: 'vaksin_detail'
        },
        {
          data: 'status_mata'
        },
        {
          data: 'detail'
        }
      ]
    });

    $('#tabel > tbody').on('click', '#vaksin_detail', function() {
      var rows = tabel.rows($(this).parents('tr')).data();
      $('.data_detail_head').html(
        'Riwayat Vaksin : ' + rows[0]['karyawan']['nama']
      );
      $('input[id="fk_karyawan_id"]').val(rows[0]['karyawan_id']);
      var y = $('#tabel_detail').DataTable({
        processing: true,
        destroy: true,
        serverSide: true,
        language: {
          processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        ajax: {
          'type': 'POST',
          'headers': {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
          },
          'url': '/kesehatan/vaksin/' + rows[0]['karyawan_id'],
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
        //   {
        //     data: 'keterangan'
        //   },
          {
            data: 'aksi'
          },
        ],
      });
      $('#vaksin_mod').modal('show');
    })
    $('#tabel > tbody').on('click', '#berat', function() {
      var rows = tabel.rows($(this).parents('tr')).data();
      $('.data_detail_head').html(
        rows[0]['karyawan']['nama']
      );
      var x = rows[0]['karyawan_id'];
      var y = $('#tabel_berat').DataTable({
        processing: true,
        destroy: true,
        serverSide: true,
        language: {
          processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        ajax: {
          'type': 'POST',
          'headers': {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
          },
          'url': '/kesehatan/bulanan/berat/detail/' + rows[0]['karyawan_id']
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
        ],
      });
      $('#berat_mod').modal('show');
    })

    $('#tabel > tbody').on('click', '#penyakit', function() {
      var rows = tabel.rows($(this).parents('tr')).data();
      $('.data_detail_head').html(
        rows[0]['karyawan']['nama']
      );
      $('input[id="id"]').val(rows[0]['karyawan_id']);
      $('#tabel_detail_penyakit').DataTable({
        processing: true,
        destroy: true,
        serverSide: true,
        language: {
          processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        ajax: {
          'type': 'POST',
          'headers': {
            'X-CSRF-TOKEN': '{{csrf_token()}}'
          },
          'url': '/kesehatan/penyakit/' + rows[0]['karyawan_id']
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
          },
        ],
      });
      $('#penyakit_mod').modal('show');
    })
    $('#tambah_mod').on('hidden.bs.modal', function() {
      $('#tambah_mod form')[0].reset();
    });

    function numberRow_vaksin($t) {
      var c = 0 - 1;
      $t.find("tr").each(function(ind, el) {
        $(el).find("td:eq(0)").html(++c);
        var j = c - 1;
        $(el).find('.dosis_table').attr('name', 'dosis[' + j + ']');
        $(el).find('.date').attr('name', 'date[' + j + ']');
        $(el).find('.ket_table').attr('name', 'ket[' + j + ']');
        $('.dosis').select2();
        $('.ket').select2();
      });
    }

    $(document).on('click', '#btnreset_vaksin', function(){
        $('#date_form').val('');
        $('#dosis_form').val('');
        $('#ket_form').val('');
        $('.dosis').select2();
        $('.ket').select2();
    })
    $('#tambahitem_vaksin').click(function(e) {
        var date_form = $('#date_form').val();
        var dosis_form = $('#dosis_form').val();
        var ket_form = $('#ket_form').val();

        const day = new Date(date_form).getDate();
        const month = (new Date(date_form).getMonth() + 1).toString().padStart(2,"0");
        const year = new Date(date_form).getFullYear();

        const date_format = day+"-"+month+"-"+year;
        $('#date_form').val('');
        $('#dosis_form').val('');
        $('#ket_form').val('');
        var data = `  <tr>
            <td>1</td>
            <td>`+date_format+`<input type="date" class="form-control date d-none" name="date[]" value="`+date_form+`"></td>
            <td>`+dosis_form+`<input type="text" class="form-control dosis_table d-none" name="dosis[]" value="`+dosis_form+`"></td>
            <td>`+ket_form+`<input type="text" class="form-control ket_table d-none" name="ket[]" value="`+ket_form+`"></td>
            <td><i class="fas fa-minus text-danger" id="closetable_vaksin"></i></td>
        </tr>`;

        if($('#tabel_vaksin > tbody > tr > td > .date').length <= 0){
            $('#tabel_vaksin > tbody > tr').remove();
            $('#tabel_vaksin tbody').append(data);
        }else{
            $('#tabel_vaksin tbody tr:last').after(data);
        }
        numberRow_vaksin($("#tabel_vaksin"));
    });

    $('#tabel_vaksin').on('click', '#closetable_vaksin', function(e) {
        $(this).closest('tr').remove();
        numberRow_vaksin($("#tabel_vaksin"));
        if($('#tabel_vaksin > tbody > tr').length <= 0){
            $('#tabel_vaksin tbody').append('<tr><td colspan="5">Belum Ada Data Ditambahkan</td></tr>');
        }
    });

    function numberRow_penyakit($t) {
      var c = 0 - 1;
      $t.find("tr").each(function(ind, el) {
        $(el).find("td:eq(0)").html(++c);
        var j = c - 1;
        $(el).find('.nama').attr('name', 'nama[' + j + ']');
        $(el).find('.jenis_table').attr('name', 'jenis[' + j + ']');
        $(el).find('.keterangan').attr('name', 'keterangan[' + j + ']');
        $(el).find('.kriteria').attr('name', 'kriteria[' + j + ']');
        // $(el).find('input[type="radio"]').attr('name', 'kriteria[' + j + ']');
        $('.jenis').select2();
      });
    }

    $(document).on('click', '#btnreset_penyakit', function(){
        $('#nama_form').val('');
        $('#jenis_form').val('');
        $('input[type="radio"][name="kriteria_form"]').prop('checked', false);
        $('#keterangan_form').val('');
        $('.jenis').select2();
    });

    $('#tambahitem_penyakit').click(function(e) {
      var nama_form = $('#nama_form').val();
      var jenis_form = $('#jenis_form').val();
      var kriteria_form = $('input[name="kriteria_form"]:checked').val();
      var keterangan_form = $('#keterangan_form').val();
      var kriteria_exp = (kriteria_form == '0' ? 'Tidak Menular' : 'Menular');

      $('#nama_form').val('');
      $('#jenis_form').val('');
      $('input[type="radio"][name="kriteria_form"]').prop('checked', false);
      $('#keterangan_form').val('');

      var data = `<tr>
            <td>1</td>
            <td>`+nama_form+`<input type="text" class="form-control d-none nama" name="nama[]" id="nama" value="`+nama_form+`"></td>
            <td>`+jenis_form+`<input type="text" class="form-control d-none jenis_table" name="jenis[]" id="jenis" value="`+jenis_form+`"></td>
            <td>`+kriteria_exp+`<input type="text" class="form-control d-none kriteria" name="kriteria[]" id="kriteria" value="`+kriteria_form+`"></td>
            <td>`+keterangan_form+`<input type="text" class="form-control d-none keterangan" name="keterangan[]" id ="kriteria" value="`+keterangan_form+`"></td>
            <td>
                <i class="fas fa-minus text-danger" id="closetable_penyakit"></i>
            </td>
        </tr>`;
      if($('#tabel_penyakit > tbody > tr > td > .nama').length <= 0){
        $('#tabel_penyakit > tbody > tr').remove();
        $('#tabel_penyakit tbody').append(data);
      }else{
        $('#tabel_penyakit tbody tr:last').after(data);
      }

      numberRow_penyakit($("#tabel_penyakit"));
    });
    $('#tabel_penyakit').on('click', '#closetable_penyakit', function(e) {

      $(this).closest('tr').remove();
      numberRow_vaksin($("#tabel_penyakit"));
      if($('#tabel_penyakit > tbody > tr').length <= 0){
        $('#tabel_penyakit tbody').append('<tr><td colspan="6">Data tidak tersedia</td></tr>');
      }

    });
    $('.select2').select2();
  });
</script>
@endsection
