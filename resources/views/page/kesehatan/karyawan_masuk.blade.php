@extends('adminlte.page')
@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Karyawan Masuk</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kesehatan.dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Karyawan Masuk</li>
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
    <div class="card">
      <div class="card-body">
        <div class='table-responsive'>
          <table id="tabel" class="table table-hover styled-table table-striped">
            <thead style="text-align: center;">
              @if(Auth::user()->divisi_id == '28')
              <tr>
                <th colspan="12">
                  <a href="/karyawan/masuk/tambah" style="color: white;"><button type="button" class="btn btn-block btn-success btn-sm" style="width: 200px;"><i class="fas fa-plus"></i> &nbsp; Tambah</i></button></a>
                </th>
              </tr>
              @endif
              <tr>
                <th>No</th>
                <th>Tgl</th>
                <th>Divisi</th>
                <th>Nama</th>
                <th>Pemeriksa</th>
                <th>Alasan</th>
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
  </div>
</div>
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-lg" id="riwayat_mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="card-body">
      <form method="post" action="/kesehatan/harian/mingguan/tensi/aksi_ubah">
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
            <div class="row">
                <div class="col-lg-4">
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
                <div class="col-lg-8">

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
                                            <table class="table table-hover styled-table table-striped" width="100%"
                                            id="tabel_detail_obat">
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
            {{-- <table class="table table-hover styled-table table-striped" width="100%" id="tabel_detail">
              <thead>
                <tr>
                  <th>Analisa</th>
                  <th>Diagnosa</th>
                  <th>Tindak Lanjut</th>
                  <th>Hasil</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table> --}}
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
    var divisi_id = '{{Auth::user()->divisi_id}}';
    var tabel = $('#tabel').DataTable({
      processing: true,
      serverSide: true,
      language: {
        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
      },
      ajax: {
        'url': '/karyawan/masuk/data',
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
          data: 'x'
        },
        {
          data: 'y'
        },
        {
          data: 'z'
        },
        {
          data: 'alasan'
        },
        {
          data: 'keterangan'
        },
        {
          data: null,
          render: function(data, type, row){
            var btn = '<div class="btn-group">';
                if (row.alasan == "Sakit") {
                    btn += '<span class="m-1"><button type="button" id="riwayat" class="btn btn-block btn-outline-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Detail</button></span>';
                } else {
                    btn += '<span class="m-1"><button type="button"  class="btn btn-block btn-light btn-sm" disabled><i class="fa fa-eye" aria-hidden="true"></i> Detail</button></span>';
                }
                if(divisi_id == "28"){
                    btn += '<span class="m-1"><button type="button" id="delete" class="btn btn-sm btn-danger" data-id="' + row.id + '"><i class="fas fa-trash"></i> Hapus</button></span>';
                }
                btn += '</div>';
                return btn;
          }
        }
      ]
    });
    $('#tabel > tbody').on('click', '#delete', function() {
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
                        url: '/karyawan/masuk/delete/'+data_id,
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
                                $('#tabel').DataTable().ajax.reload();
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
    $('#tabel > tbody').on('click', '#riwayat', function() {
      var rows = tabel.rows($(this).parents('tr')).data();
      $('.data_detail_head').html(
        'Detail Sakit'
      );
      var diagnosa = rows[0]['karyawan_sakit']['diagnosa'] != null ? rows[0]['karyawan_sakit']['diagnosa'] : '<i>Tidak Ada Diagnosa</i>';
                var analisa = rows[0]['karyawan_sakit']['analisa'] != null ? rows[0]['karyawan_sakit']['analisa'] : '<i>Tidak Ada Analisa</i>';
                $('#analisa').html(analisa);
                $('#diagnosa').html(diagnosa);
                $('#pasien').html(rows[0]['y']);
                $('#divisi').html(rows[0]['x']);
                $('#pemeriksa').html(rows[0]['z']);
                $('#tanggal').html(rows[0]['tgl_cek']);
                if(rows[0]['karyawan_sakit']['tindakan'] == "Pengobatan"){
                    $('#detail_obat').removeClass('d-none');
                    $('#detail_terapi').addClass('d-none');
                    $('#tabel_detail_obat').DataTable({
                        processing: true,
                        destroy: true,
                        serverSide: false,
                        language: {
                            processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                        },
                        ajax: '/karyawan/sakit/obat/detail/' + rows[0]['karyawan_sakit']['id'],
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
                }else{
                    $('#detail_obat').addClass('d-none');
                    $('#detail_terapi').removeClass('d-none');
                    $('#terapi').html(rows[0]['karyawan_sakit']['terapi']);
                }
    //   var y = $('#tabel_detail').DataTable({
    //     processing: true,
    //     destroy: true,
    //     serverSide: true,
    //     language: {
    //       processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
    //     },
    //     ajax: '/karyawan/masuk/detail/data/' + rows[0]['karyawan_sakit_id'],
    //     columns: [{
    //       data: 'analisa'
    //     }, {
    //       data: 'diagnosa'
    //     }, {
    //       data: 'tindakan'
    //     }, {
    //       data: 'keputusan'
    //     }, {
    //       data: 'aksi'
    //     }],
    //   });
      $('#riwayat_mod').modal('show');
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
  });
</script>
@endsection
