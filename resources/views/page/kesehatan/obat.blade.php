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

        tr.line-through td:not(:nth-last-child(-n+1)):before {
            content: " ";
            position: absolute;
            left: 0;
            top: 35%;
            border-bottom: 1px solid;
            width: 100%;
        }

        .align-center {
            text-align: center;
        }

        #stok_obat_edit {
            color: #0d6efd;
            opacity: 0.6;

        }

        #stok_obat_edit:hover {
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
            <div class="card">
                <div class="card-body">
                    <div class='table-responsive'>
                        <table id="tabel" class="table table-hover styled-table table-striped">
                            <thead style="text-align: center;">
                                @if (Auth::user()->divisi_id == '28')
                                    <tr>
                                        <th colspan="12">
                                            <button type="button" id="btntambahobat"
                                                class="btn btn-block btn-success btn-sm" style="width: 200px;"><i
                                                    class="fas fa-plus"></i> &nbsp; Tambah</i></button>
                                        </th>
                                    </tr>
                                @endif
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
    <div class="modal fade  bd-example-modal-xl" id="riwayat_mod" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel">
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class='table-responsive'>
                            <table class="table table-hover styled-table table-striped align-center" width="100%"
                                id="tabel_detail">
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
    <div class="modal fade  bd-example-modal-lg" id="edit_mod" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header card-outline card-warning">
                    <h4 class="modal-title" id="myModalLabel">
                        <div class="data_detail_head"></div>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="data_detail">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-12">
                                    <form method="post" action="/obat/aksi_ubah" id="form_ubah_obat">
                                        {{ csrf_field() }}
                                        {{ method_field('PUT') }}
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="form-horizontal">
                                                            <input type="text" class="form-control d-none" name="id"
                                                                placeholder="Masukkan Nama Obat" value="{{ old('id') }}"
                                                                id="id">
                                                            <div class="form-group row">
                                                                <label for="no_pemeriksaan"
                                                                    class="col-lg-4 col-sm-12 col-form-label"
                                                                    style="text-align:right;">Nama Obat</label>
                                                                <div class="col-sm-7 col-lg-5">
                                                                    <input type="text" class="form-control"
                                                                        name="nama" placeholder="Masukkan Nama Obat"
                                                                        value="{{ old('nama') }}" id="nama_obat">
                                                                    <div class="text-danger form-text"
                                                                        id="nama_obat_message">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="no_pemeriksaan"
                                                                    class="col-sm-12 col-lg-4 col-form-label"
                                                                    style="text-align:right;">Stok Obat</label>
                                                                <div class="col-sm-4 col-lg-3">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control"
                                                                            id="stok" readonly>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">Pcs</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for=""
                                                                    class="col-sm-12 col-lg-4 col-form-label"
                                                                    style="text-align: right;">Aturan</label>
                                                                <div class="col-sm-8 col-lg-7 col-form-label">
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input aturan_obat"
                                                                            type="radio" name="aturan_obat"
                                                                            value="Sebelum Makan">
                                                                        <label class="form-check-label">Sebelum
                                                                            Makan</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input aturan_obat"
                                                                            type="radio" name="aturan_obat"
                                                                            value="Sesudah Makan">
                                                                        <label class="form-check-label">Sesudah
                                                                            Makan</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="tanggal"
                                                                    class="col-sm-12 col-lg-4 col-form-label"
                                                                    style="text-align:right;">Keterangan</label>
                                                                <div class="col-sm-8 col-lg-7">
                                                                    <textarea type="text" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan"
                                                                        value="{{ old('keterangan') }}" placeholder="Catatan tambahan"></textarea>
                                                                </div>
                                                                <span role="alert" id="no_seri-msg"></span>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for=""
                                                                    class="col-sm-12 col-lg-4 col-form-label"
                                                                    style="text-align: right;">Status</label>
                                                                <div class="col-sm-8 col-lg-7 col-form-label">
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input status_obat"
                                                                            type="radio" name="status_obat"
                                                                            value="1">
                                                                        <label class="form-check-label">Aktif</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input status_obat"
                                                                            type="radio" name="status_obat"
                                                                            value="0">
                                                                        <label class="form-check-label">Tidak Aktif</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6"><button class="btn btn-danger"
                                                            data-dismiss="modal">Batal</button></div>
                                                    <div class="col-6"><button class="btn btn-warning float-right"
                                                            id="button_ubah" disabled="true">Simpan</button></div>
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
    <div class="modal fade  bd-example-modal-xl" id="obat_mod" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-xl" role="document">
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
                        <div class="col-lg-7 col-md-12">
                            <form method="post" action="/obat/stok/aksi_tambah" id="form_stok_obat">
                                {{ csrf_field() }}
                                <div class="card card-outline card-warning">
                                    <div class="card-header">
                                        <h6 class="card-title">Update Stok Obat</h6>
                                    </div>
                                    <div class="card-body">
                                        <input type="text" name="id" class="d-none form-control" id="id"
                                            readonly>
                                        <div class="form-group">
                                            <label for="tgl_pembelian">Tgl Pembelian</label>
                                            <input type="date" class="form-control" id="tgl_pembelian"
                                                name="tgl_pembelian" placeholder="Masukkan Tanggal" style="width:50%;"
                                                max="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="stok">Stok</label>
                                            <div class="input-group" style="width:50%;">
                                                <input type="number" class="form-control" id="stok_pembelian"
                                                    name="stok">
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
                                        <button class="btn btn-danger" data-dismiss="modal">Batal</button>
                                        <button class="btn btn-warning float-right"
                                            id="button_tambah_stok">Simpan</button>
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

    <div class="modal fade  bd-example-modal-xl" id="tambahmodal" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel">
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
        @if (Session::has('error'))
            Swal.fire({
                title: 'Gagal',
                text: "{{ Session::get('error') }}",
                icon: 'error',
            });
        @endif
        @if (Session::has('success'))
            Swal.fire({
                title: 'Berhasil',
                text: "{{ Session::get('success') }}",
                icon: 'success',
            });
        @endif
        $(function() {
            var divisi_id = '{{ Auth::user()->divisi_id }}';
            $(document).on('keyup change', '#form_stok_obat', function() {
                if ($('#tgl_pembelian').val() != "" && $('#stok_pembelian').val() != "") {
                    $('#button_tambah_stok').attr('disabled', false);
                } else {
                    $('#button_tambah_stok').attr('disabled', true);
                }
            });
            $(document).on('keyup change', '#form_ubah_obat', function() {
                if ($(this).find('#nama_obat').val() != "" && $(this).find(
                        'input[name="aturan_obat"][type="radio"]:checked').val() != "") {
                    $('#button_ubah').attr('disabled', false);
                } else {
                    $('#button_ubah').attr('disabled', true);
                }
            });

            $(document).on('keyup change', '#form_tambah_obat', function() {

                if ($(this).find('#nama_obat_tambah').val() != "" && !$(this).find('#nama_obat_tambah')
                    .hasClass('is-invalid') && $(this).find(
                        'input[name="aturan_obat"][type="radio"]:checked').val() != "") {
                    $('#button_tambah').attr('disabled', false);
                } else {
                    $('#button_tambah').attr('disabled', true);
                }
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
                                url: '/obat/delete/' + data_id,
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
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                        data: 'aturan'
                    },
                    {
                        data: 'keterangan'
                    },
                    {
                        data: 'button'
                    },
                    // {
                    //     data: 'button',
                    //     render: function(data, type, row) {
                    //         var btn =
                    //             `<div class="btn-toolbar d-flex justify-content-center" role="toolbar" aria-label="Toolbar with button groups"> <
                //             button type = "button"
                //         id = "riwayat"
                //         class = "btn btn-sm btn-outline-primary btn-sm m-1" > < i class =
                //             "fas fa-eye" > < /i> Riwayat Pakai</button >
                //             <
                //             button type = "button"
                //         id = "edit"
                //         class = "btn btn-sm btn-warning btn-sm m-1" > < i class =
                //             "fas fa-edit" > < /i> Ubah</button >
                //             <
                //             button type = "button"
                //         id= "delete"
                //         class = "btn btn-sm btn-danger btn-sm m-1"
                //         data-id = "' . $data->id . '" > < i class = "fas fa-trash" > <
                //             /i> Hapus</button >
                //             <
                //             /div>`;
                    //     }
                    // }
                ]
            });

            $(document).on('click', '#btntambahobat', function() {
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

            $(document).on('keyup change', '#nama_obat_tambah', function() {
                var nama = $(this).val();
                $.ajax({
                    url: '/obat/cekdata/' + nama,
                    method: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data.length > 0) {
                            $('#nama_obat_tambah').addClass('is-invalid');
                            $('#nama_obat_tambah_message').html('Data Telah Terpakai');
                            $('#button_tambah').attr("disabled", true);
                        } else {
                            $('#nama_obat_tambah').removeClass('is-invalid');
                            $('#nama_obat_tambah_message').html('');
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
                            data: 'aksi',
                            visible: divisi_id == '28' ? true : false
                        }
                    ],
                });
                $('#riwayat_mod').modal('show');
            })


            $('#tabel > tbody').on('click', '#edit', function() {
                var rows = tabel.rows($(this).parents('tr')).data();
                var statusObat = $(this).attr("data-obat-status");

                $('input[id="id"]').val(rows[0]['id']);
                $('input[id="nama_obat"]').val(rows[0]['nama']);
                $('input[type="radio"][name="aturan"][value="' + rows[0]['aturan'] + '"]').attr('checked',
                    true);
                $('input[id="stok"]').val(rows[0]['stok']);

                $('input:radio[name="aturan_obat"][value="' + rows[0]['aturan'] + '"]').attr('checked',
                    true)
                $('textarea[name="keterangan"]').val(rows[0]['keterangan']);
                $('#edit_mod').modal(
                    'show');
                $('.modal-title > .data_detail_head').text('Ubah ' + rows[0]['nama']);

                if (statusObat == 1) {
                    $('input[name="status_obat"][value="1"]').attr('checked', 'checked');
                } else {
                    $('input[name="status_obat"][value="0"]').attr('checked', 'checked');
                }
            })

            $('#tabel > tbody').on('click', '#stok_obat_edit', function() {
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
                            render: function(data, type, row) {
                                return moment(new Date(data).toString()).format(
                                    'DD-MM-YYYY');
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

                $('#button_tambah_stok').attr('disabled', true);
                $('#obat_mod').modal('show');
            })
        });
    </script>
@endsection
