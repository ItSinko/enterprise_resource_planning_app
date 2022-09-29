@extends('adminlte.page')
@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Obat</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kesehatan.dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Obat</li>
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

    .align-center{
        text-align: center;
    }
    .stok{
        color: #0d6efd;
        opacity: 0.6;

    }

    .stok:hover{
        transition: color 0.5s;
        opacity: 1;
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
          <table id="tabel" class="table table-hover styled-table table-striped">
            <thead style="text-align: center;">
              <tr>
                <th colspan="12">
                  <button type="button" id="btntambahobat" class="btn btn-block btn-success btn-sm" style="width: 200px;"><i class="fas fa-plus"></i> &nbsp; Tambah</i></button>
                </th>
              </tr>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Stok</th>
                <th>Aturan Pakai</th>
                <th>Keterangan</th>
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
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-xl" id="riwayat_mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xl" role="document">
    <div class="card-body">
      {{-- <form method="post" action="/kesehatan_harian_mingguan_tensi/aksi_ubah">
        {{ csrf_field() }}
        {{ method_field('PUT')}} --}}
        <div class="modal-content">
          <div class="modal-header card-outline card-info">
            <h4 class="modal-title" id="myModalLabel">
              <div class="data_detail_head"></div>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <div class='table-responsive'>
              <table class="table table-hover styled-table table-striped align-center" width="100%" id="tabel_detail" >
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tgl</th>
                    <th>Divisi</th>
                    <th>Nama</th>
                    <th>Analisa</th>
                    <th>Diagnosa</th>
                    <th>Jumlah</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      {{-- </form> --}}
    </div>
  </div>
</div>
<!-- End Modal Detail -->
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-lg" id="edit_mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header card-outline card-warning">
        <h4 class="modal-title" id="myModalLabel">
          <div class="data_detail_head"></div>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="data_detail">
          <div class="row">
            <div class="col-lg-12">
              <div class="col-lg-12">
                <form method="post" action="/obat/aksi_ubah">
                  {{ csrf_field() }}
                  {{ method_field('PUT') }}
                  <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-horizontal">
                                    <input type="text" class="form-control d-none" name="id" placeholder="Masukkan Nama Obat" value="{{ old('id') }}" id="id">
                                    <div class="form-group row">
                                        <label for="no_pemeriksaan" class="col-sm-5 col-form-label" style="text-align:right;">Nama Obat</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Obat" value="{{ old('nama') }}" id="nama">
                                            <div class="text-danger form-text" id="nama_obat_message">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="no_pemeriksaan" class="col-sm-5 col-form-label" style="text-align:right;">Stok Obat</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="stok" readonly>
                                                <div class="input-group-append">
                                                  <span class="input-group-text">Pcs</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-sm-5 col-form-label" style="text-align: right;">Aturan</label>
                                        <div class="col-sm-7 col-form-label">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input aturan_obat" type="radio" name="aturan_obat" value="Sebelum Makan">
                                                <label class="form-check-label">Sebelum Makan</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input aturan_obat" type="radio" name="aturan_obat" value="Sesudah Makan">
                                                <label class="form-check-label">Sesudah Makan</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="tanggal" class="col-sm-5 col-form-label" style="text-align:right;">Keterangan</label>
                                        <div class="col-sm-7">
                                            <textarea type="text" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" value="{{old('keterangan')}}" placeholder="Catatan tambahan" style="width:45%;"></textarea>
                                        </div>
                                        <span role="alert" id="no_seri-msg"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6"><button class="btn btn-danger" data-dismiss="modal">Batal</button></div>
                            <div class="col-6"><button class="btn btn-warning float-right" id="button_tambah">Simpan</button></div>
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
<!-- End Modal Detail -->
<!-- Modal Detail -->
<div class="modal fade  bd-example-modal-xl" id="obat_mod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">
          <div class="data_detail_head"></div>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-lg-7 col-md-12">
                <form method="post" action="/obat/stok/aksi_tambah">
                {{ csrf_field() }}
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h6 class="card-title">Update Stok Obat</h6>
                    </div>
                    <div class="card-body">
                        <input type="text" name="id" class="d-none form-control" id="id" readonly>
                        <div class="form-group">
                            <label for="tgl_pembelian">Tgl Pembelian</label>
                            <input type="date" class="form-control" id="tgl_pembelian" name="tgl_pembelian" placeholder="Masukkan Tanggal" style="width:50%;" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok</label>
                            <div class="input-group" style="width:50%;">
                                <input type="number" class="form-control" id="stok" name="stok">
                                <div class="input-group-append">
                                    <span class="input-group-text">Pcs</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea type="text" class="form-control" name="keterangan" id="keterangan"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-danger" data-dismiss="modal" >Batal</button>
                        <button class="btn btn-warning float-right" id="button_tambah">Simpan</button>
                    </div>
                </div>
                </form>
            </div>
            <div class="col-lg-5 col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h6 class="card-title">Riwayat Pembelian</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-center" id="tabel_riwayat">
                                <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tgl Pembelian</th>
                                    <th width="5%">Jumlah</th>
                                    <th>Keterangan</th>
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
        {{-- <div class="data_detail">
          <div class="row">
            <div class="col-lg-12">
              <div class="col-lg-12">

                  <div class="card">
                    <div class="card-header bg-success">
                      Penambahan Stok
                    </div>
                    <div class="card-body">
                      <div class="col-lg-12">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="form-horizontal">
                              <input type="text" name="id" class="d-none form-control" id="id" readonly>
                              <table class="table table-bordered table-striped" id="tabel_vaksin">
                                <thead>
                                  <tr>
                                    <th>Tgl Pembelian</th>
                                    <th width="25%">Stok</th>
                                    <th>Keterangan</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>
                                      <input type="text" class="form-control d-none" name="id" id="id">
                                      <input type="date" class="form-control" name="tgl_pembelian">
                                    </td>
                                    <td>
                                      <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="stok" name="stok">
                                        <div class="input-group-append">
                                          <span class="input-group-text">Pcs</span>
                                        </div>
                                      </div>
                                    </td>
                                    <td>
                                      <textarea type="text" class="form-control" name="keterangan" id="keterangan"></textarea>
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <span class="float-right"><button class="btn btn-success rounded-pill" id="button_tambah"><i class="fas fa-plus"></i>&nbsp;Update Stok</button></span>
                    </div>
                  </div>
                </form>
                <div class="card">
                  <div class="card-header bg-success">
                    Riwayat Penambahan Stok
                  </div>
                  <div class="card-body">
                    <div class="col-lg-12">
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-horizontal">


                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> --}}
      </div>
        {{-- <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> --}}
    </div>
  </div>
</div>
<!-- End Modal Detail -->

<div class="modal fade  bd-example-modal-xl" id="tambahmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header card-outline card-primary">
                <h4 class="modal-title" id="myModalLabel">
                    Tambah Obat
                </h4>
            </div>
            <div class="modal-body" id="tambahdata">
            </div>
        </div>
    </div>
</div>
@stop
@section('adminlte_js')
<script>
  $(function() {
    $('#tabel > tbody').on('click', '#delete', function() {
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
    $('#tabel_riwayat').DataTable({

    });

    var tabel = $('#tabel').DataTable({
      processing: true,
      serverSide: true,
      language: {
        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
      },
      ajax: {
        'url': '/obat/data',
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
          data: 'nama'
        },
        {
          data: 'a'
        },
        {
            data: null
        },
        {
          data: 'keterangan'
        },
        {
          data: 'button'
        }
      ]
    });

    $(document).on('click', '#btntambahobat', function(){
                $.ajax({
                    url: "/obat/tambah",
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#tambahmodal').modal("show");
                        $('#tambahdata').html(result).show();
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
    });

    $(document).on('keyup change', '#nama_obat', function() {
        var nama = $(this).val();
        $.ajax({
            url: '/obat/cekdata/' + nama,
            method: "GET",
            dataType: "json",
            success: function(data) {
                if (data.length > 0) {
                    $('#nama_obat').addClass('is-invalid');
                    $('#nama_obat_message').html('Data Telah Terpakai');
                    $('#button_tambah').attr("disabled", true);
                } else {
                    $('#nama_obat').removeClass('is-invalid');
                    $('#nama_obat_message').html('');
                    $('#button_tambah').attr("disabled", false);
                }
            }
        })
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
    $('#tabel > tbody').on('click', '#riwayat', function() {
      var rows = tabel.rows($(this).parents('tr')).data();
      $('.data_detail_head').html(
        'Riwayat Pemakaian ' + rows[0]['nama']
      );
      var y = $('#tabel_detail').DataTable({
        processing: true,
        destroy: true,
        serverSide: false,
        language: {
          processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        ajax: '/obat/detail/data/' + rows[0]['id'],
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            data: 'tgl'
          },
          {
            data: 'div'
          },
          {
            data: 'x'
          },
          {
            data: 'anal'
          },
          {
            data: 'diag'
          },
          {
            data: 'jum'
          },
          {
            data: 'aksi'
          }
        ],
      });
      $('#riwayat_mod').modal('show');
    })


    $('#tabel > tbody').on('click', '#edit', function() {
      var rows = tabel.rows($(this).parents('tr')).data();
      $('input[id="id"]').val(rows[0]['id']);
      $('input[id="nama"]').val(rows[0]['nama']);
      $('input[id="stok"]').val(rows[0]['stok']);
      $('textarea[id="keterangan"]').val(rows[0]['keterangan']);
      $('#edit_mod').modal('show');
      $('.modal-title > .data_detail_head').text('Ubah '+rows[0]['nama']);
    })

    $('#tabel > tbody').on('click', '.stok', function() {
      var rows = tabel.rows($(this).parents('tr')).data();
      $('.data_detail_head').html(
        'Stok ' + rows[0]['nama']
      );
      $('input[id="id"]').val(rows[0]['id']);

      var y = $('#tabel_riwayat').DataTable({
        processing: true,
        destroy: true,
        serverSide: false,
        pageLength: 5,
        lengthMenu: [
          [5, 10, 20, -1],
          [5, 10, 20, "Semua"]
        ],
        language: {
          processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
        },
        ajax: '/obat/stok/data/' + rows[0]['id'],
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            data: 'tgl_pembelian',
            render: function (data, type, row) {
                return moment(new Date(data).toString()).format('DD-MM-YYYY');
            }
          },
          {
            data: 'a'
          },
          {
            data: 'keterangan'
          },

        ],
      });


      $('#obat_mod').modal('show');
    })
  });
</script>
@endsection
