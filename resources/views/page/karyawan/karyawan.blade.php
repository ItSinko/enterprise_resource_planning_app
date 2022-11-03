@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Karyawan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('kesehatan.dashboard') }}">Beranda</a></li>
                    <li class="breadcrumb-item active">Karyawan</li>
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
                        <table id="tabel" class="table table-hover">
                            <thead style="text-align: center;">
                                @if (Auth::user()->Karyawan->divisi_id == '28')
                                    <tr>
                                        <th colspan="14">
                                            <a href="/karyawan/create" style="color: white;"><button type="button"
                                                    class="btn btn-block btn-success btn-sm" style="width: 200px;"><i
                                                        class="fas fa-plus"></i> &nbsp; Tambah</i></button></a>
                                        </th>
                                    </tr>
                                @endif
                                <tr>
                                    <th>No</th>
                                    <th>Divisi</th>
                                    <th>Jabatan</th>
                                    {{--   <th>KTP</th> --}}
                                    <th>Nama</th>
                                    <th>Kelamin</th>
                                    <th>Umur</th>
                                    <th>Tgl Masuk</th>
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
    <div class="modal fade  bd-example-modal-xl" id="edit_mod" tabindex="-1" role="dialog"
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
                <form method="post" action="/karyawan/update">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="modal-body">
                        <div class="data_detail">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="col-lg-12">
                                        <input type="text" name="id" class="d-none form-control" id="id"
                                            readonly>
                                        <div class="card card-outline card-warning">
                                            <div class="card-header">
                                                <h5 class="card-title">Biodata Karyawan</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-horizontal">
                                                    <div class="form-group row">
                                                        <label for="no_pemeriksaan" class="col-sm-5 col-form-label"
                                                            style="text-align:right;">Tanggal Lahir</label>
                                                        <div class="col-sm-7">
                                                            <input type="date"
                                                                class="form-control @error('nama') is-invalid @enderror"
                                                                name="tgllahir" id="tgllahir" style="width:30%;"
                                                                placeholder="Masukkan Nama Karyawan"
                                                                value="{{ old('tgllahir') }}">
                                                            @if ($errors->has('tgllahir'))
                                                                <div class="text-danger">
                                                                    {{ $errors->first('tgllahir') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="kondisi" class="col-sm-5 col-form-label"
                                                            style="text-align:right;">Jenis Kelamin</label>
                                                        <div class="col-sm-7" style="margin-top:7px;">
                                                            <div class="icheck-success d-inline col-sm-5">
                                                                <input type="radio" name="jenis" value="L"
                                                                    checked="0">
                                                                <label for="no">
                                                                    Laki laki
                                                                </label>
                                                            </div>
                                                            <div class="icheck-warning d-inline col-sm-5">
                                                                <input type="radio" name="jenis" value="P">
                                                                <label for="sample">
                                                                    Perempuan
                                                                </label>
                                                            </div>
                                                            <span class="invalid-feedback" role="alert"
                                                                id="kondisi-msg"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card card-outline card-warning">
                                            <div class="card-header">
                                                <h5 class="card-title">Informasi Pekerjaan</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-horizontal">
                                                    <div class="form-group row">
                                                        <label for="no_pemeriksaan" class="col-sm-5 col-form-label"
                                                            style="text-align:right;">Divisi</label>
                                                        <div class="col-sm-7">
                                                            <select type="text"
                                                                class="form-control @error('divisi') is-invalid @enderror select2"
                                                                name="divisi" id="divisi" style="width:45%;"
                                                                placeholder="Masukkan Nama Karyawan"
                                                                value="{{ old('divisi') }}">
                                                                <option value="">Pilih Divisi</option>
                                                                @foreach ($karyawan as $k)
                                                                    <option value="{{ $k->id }}">{{ $k->nama }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @if ($errors->has('divisi'))
                                                                <div class="text-danger">
                                                                    {{ $errors->first('divisi') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="no_pemeriksaan" class="col-sm-5 col-form-label"
                                                            style="text-align:right;">Jabatan</label>
                                                        <div class="col-sm-7">
                                                            <select type="text"
                                                                class="form-control @error('jabatan') is-invalid @enderror select2"
                                                                name="jabatan" id="jabatan" style="width:45%;"
                                                                placeholder="Masukkan Nama Karyawan"
                                                                value="{{ old('jabatan') }}">
                                                                <option value="direktur">Direktur</option>
                                                                <option value="manager">Manager</option>
                                                                <option value="assisten manager">Ass Manager</option>
                                                                <option value="supervisor">Supervisor</option>
                                                                <option value="staff">Staff</option>
                                                                <option value="operator">Operator</option>
                                                                <option value="harian">Harian</option>
                                                                <option value="lainnya">Lainnya</option>
                                                            </select>
                                                            @if ($errors->has('jabatan'))
                                                                <div class="text-danger">
                                                                    {{ $errors->first('jabatan') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="kondisi" class="col-sm-5 col-form-label"
                                                            style="text-align:right;">Pemeriksa Rapid</label>
                                                        <div class="col-sm-7" style="margin-top:7px;">
                                                            <div class="icheck-success d-inline col-sm-6">
                                                                <input type="radio" name="pemeriksa_rapid"
                                                                    id="pemeriksa_rapid" value="1">
                                                                <label for="no">
                                                                    Ya
                                                                </label>
                                                            </div>
                                                            <div class="icheck-warning d-inline col-sm-6">
                                                                <input type="radio" name="pemeriksa_rapid"
                                                                    id="pemeriksa_rapid" value="0">
                                                                <label for="sample">
                                                                    Tidak
                                                                </label>
                                                            </div>
                                                            <span class="invalid-feedback" role="alert"
                                                                id="kondisi-msg"></span>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger float-left"
                            data-dismiss="modal">Batal</button>
                        <button class="btn btn-warning float-right" id="button_tambah">Simpan</button>
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
            var divisi = "{{ Auth::user()->Karyawan->divisi_id }}";
            var tabel = $('#tabel').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                ajax: {
                    'url': '/karyawan/data',
                    'type': 'POST',
                    'datatype': 'JSON',
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
                        data: 'x',
                    },
                    {
                        data: 'jabatans',
                    },
                    // {
                    //   data: 'ktp',
                    //   orderable: false,
                    //   searchable: false,
                    // },
                    {
                        data: 'nama',
                    },
                    {
                        data: 'kelamin',
                    },
                    {
                        data: 'umur',
                    },
                    {
                        data: 'tgl_kerja',
                    },
                    {
                        data: 'button',
                        visible: divisi == '28' ? true : false
                    },
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
                                url: '/karyawan/delete/' + data_id,
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

            $('#tabel > tbody').on('click', '#edit', function() {
                var rows = tabel.rows($(this).parents('tr')).data();
                $('.data_detail_head').html(
                    rows[0]['nama']
                );
                var optionDivisi = rows[0]['divisi_id'];
                var optionJabatan = rows[0]['jabatan'];
                var optionPemeriksa = rows[0]['pemeriksa_rapid'];

                $("#divisi").val(optionDivisi).trigger('change');
                $("#jabatan").val(optionJabatan).trigger('change');
                $('input[name="jenis"][value="' + rows[0]['kelamin'] + '"]').attr('checked', 'checked');

                if (optionPemeriksa == 1) {
                    $('input[name="pemeriksa_rapid"][value="1"]').attr('checked', 'checked');
                } else {
                    $('input[name="pemeriksa_rapid"][value="0"]').attr('checked', 'checked');
                }

                $('input[id="id"]').val(rows[0]['id']);
                $('input[id="tgllahir"]').val(rows[0]['tgllahir']);
                $('#edit_mod').modal('show');
            })
        });
        $('.select2').select2();
    </script>
@endsection
