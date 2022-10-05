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
    .minimizechar {
    overflow: hidden;
    max-width: 25ch;
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
    <div class="card">
      <div class="card-body">
        <div class="row mb-3">
            <div class="col-9">
            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-berobat-tab" data-toggle="pill" href="#pills-berobat" role="tab" aria-controls="pills-berobat" aria-selected="true">Berobat</a>
                </li>
                <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-sakit-tab" data-toggle="pill" href="#pills-sakit" role="tab" aria-controls="pills-sakit" aria-selected="false">Sakit</a>
                </li>
            </ul>
            </div>
            <div class="col-3">
                <a href="/karyawan/sakit/tambah" style="color: white;"><button type="button" class="btn btn-md btn-success btn-sm float-right"><i class="fas fa-plus"></i>&nbsp; Tambah Karyawan Sakit</i></button></a>
            </div>
        </div>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-berobat" role="tabpanel" aria-labelledby="pills-berobat-tab">
                    <div class='table-responsive'>
                        <table id="tabel_obat" class="table table-hover styled-table table-striped">
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
                        <table id="tabel_sakit" class="table table-hover styled-table table-striped" style="width:100%">
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
<div class="modal fade  bd-example-modal-xl" id="detail_mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
            <table class="table table-hover styled-table table-striped" width="100%" id="tabel_detail_obat">
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
      </form>
    </div>
  </div>
</div>
<!-- End Modal Detail -->
@stop
@section('adminlte_js')
<script>
  $(function() {
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
    var tabel = $('#tabel').DataTable({
      processing: true,
      serverSide: true,
      language: {
        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
      },
      ajax: {
        'url': '/karyawan/sakit/data',
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
          data: 'analisa'
        },
        {
          data: 'diagnosa'
        },
        {
          data: 'detail_button'
        },
        {
          data: 'keputusan',
          visible: false
        },
        {
          data: 'cetak'
        }
      ]
    });
    $('#tabel > tbody').on('click', '#detail_tindakan', function() {
      var rows = tabel.rows($(this).parents('tr')).data();
      console.log(rows);
      $('.data_detail_head').html(
        rows[0]['tindakan'] + ' : ' + rows[0]['y']
      );
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
          },
        ],
      });
      $('#detail_mod').modal('show');
      // $('input[id="nama_obat"]').val(rows[0]['o']);
      // $('input[id="aturan"]').val(rows[0]['d']);
      // $('input[id="konsumsi"]').val(rows[0]['e']);
      // $('input[id="terapi"]').val(rows[0]['f']);
    });

    var tabel = $('#tabel_sakit').DataTable({
      processing: true,
      serverSide: true,
      language: {
        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
      },
      ajax: {
        'url': '/karyawan/sakit/data',
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
          data: 'keputusan',
        },
        {
          data: 'cetak'
        }
      ]
    });
  });
</script>
@endsection
