@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">User Management</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">User</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        table>tbody>tr>td>.form-group>.select2>.selection>.select2-selection--single {
            height: 100% !important;
        }

        table>tbody>tr>td>.form-group>.select2>.selection>.select2-selection>.select2-selection__rendered {
            word-wrap: break-word !important;
            text-overflow: inherit !important;
            white-space: normal !important;
        }

        .modal-body {
            max-height: 80vh;
            overflow-y: auto;
        }

        .nowrap-text {
            white-space: nowrap;
        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .money {
            font-family: 'Varela Round';
        }

        .inline {
            display: inline-block;
        }

        .dropdown-item {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }

        .blue-bg {
            background-color: #e0eff3;
            color: #17a2b8;
        }


        .yellow-bg {
            background-color: #fff4dc;
            color: #ffc107;
        }

        .tabnum {
            font-variant-numeric: tabular-nums;
        }

        @media screen and (min-width: 1440px) {
            body {
                font-size: 14px;
            }

            .dropdown-item {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .labelket {
                text-align: right;
            }
        }

        @media screen and (max-width: 1439px) {
            body {
                font-size: 12px;
            }

            .dropdown-item {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: right;
            }
        }


        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
    </style>
@stop
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-7">
                    @if (Session::has('error') || count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show col-12" role="alert">
                            <strong>Gagal menambahkan!</strong> Periksa
                            kembali data yang diinput
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @elseif(Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show col-12" role="alert">
                            <strong>Berhasil menambahkan data</strong>,
                            Terima kasih
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row" style="margin-bottom: 5px">
                                        <div class="col-12">
                                            <span class="float-right">
                                                <button class="btn btn-info" id="tambah_user">
                                                    <i class="fas fa-plus"></i> Tambah
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="showtable">
                                                    <thead style="text-align: center;">
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th width="10%">Divisi</th>
                                                            <th width="10%">Username</th>
                                                            <th width="10%">Nama</th>
                                                            <th width="10%">Email</th>
                                                            <th width="10%">Status</th>
                                                            <th width="10%">Aksi</th>
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
                        </div>
                    </div>
                    <div class="modal fade" id="modaldetail" role="dialog" aria-labelledby="modaldetail"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content" style="margin: 10px">
                                <div class="modal-header borderless blue-bg">
                                    <h4 class="modal-title"><b>Detail Paket Produk</b></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body filter">
                                    <div class="row">
                                        <div class="col-4">
                                            <h5>Info</h5>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="filter">
                                                        <div><small class="text-muted">Nama Produk</small></div>
                                                        <div><b id="nama_produk"></b></div>
                                                    </div>
                                                    <div class="filter">
                                                        <div><small class="text-muted">Harga Produk</small></div>
                                                        <div><b id="harga_produk"></b></div>
                                                    </div>
                                                    <div class="filter">
                                                        <div><small class="text-muted">Jenis Produk</small></div>
                                                        <div><b id="jenis_produk"></b></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <h5>Detail Produk</h5>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table" id="showdetailtable" width="100%">
                                                        <thead class="align-center">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Produk</th>
                                                                <th>Kelompok</th>
                                                                <th>Jumlah</th>
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
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content" style="margin: 10px">
                                <div class="modal-header blue-bg">
                                    <h4 class="modal-title"><b>Ubah User</b></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="edit">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="createmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content" style="margin: 10px">
                                <div class="modal-header blue-bg">
                                    <h4 class="modal-title"><b>Tambah User</b></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="create">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('adminlte_js')

    <script>
        function select_data(x) {
            $('.karyawan').select2({
                placeholder: "Pilih Karyawan",
                dropdownParent: $("#" + x + "modal"),
            });
        }
        $(function() {
            $(document).on('submit', '#form-penjualan-user-create', function(e) {
                e.preventDefault();
                var action = $(this).attr('data-attr');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: action,
                    data: $('#form-penjualan-user-create').serialize(),

                    success: function(response) {
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan edit data',
                                'success'
                            );

                            $("#createmodal").modal('hide');
                            $('#showtable').DataTable().ajax.reload();
                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan tambah data',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        alert($('#form-penjualan-user-create').serialize());
                    }
                });
                return false;
            });
            $(document).on('submit', '#form-penjualan-user-edit', function(e) {
                e.preventDefault();
                var action = $(this).attr('data-attr');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: action,
                    data: $('#form-penjualan-user-edit').serialize(),

                    success: function(response) {
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan edit data',
                                'success'
                            );

                            $("#editmodal").modal('hide');
                            $('#showtable').DataTable().ajax.reload();
                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan edit data',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        swal.fire(
                            'Gagal',
                            'Gagal melakukan edit data',
                            'error'
                        );
                    }
                });
                return false;
            });

            function ResetPwd(x) {
                $(document).on('click', '#resetpwd' + x, function() {
                    console.log(x);
                    Swal.fire({
                        title: 'Reset',
                        text: "Reset Password ?",
                        icon: "warning",
                        showCancelButton: true,
                        cancelButtonText: 'Tidak',
                        confirmButtonText: 'Iya',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var link = '{{ route('user.resetpwd', ['id' => ':id']) }}';
                            link = link.replace(':id', x);
                            $.ajax({
                                url: link,
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    "id": x,
                                    "_method": "POST",
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(result) {
                                    if (result.info == "success") {
                                        Swal.fire({
                                            title: 'Berhasil',
                                            text: 'Password berhasil di reset',
                                            icon: 'success',
                                        });
                                    } else {
                                        swal.fire(
                                            'Error',
                                            'Gagal melakukan reset password',
                                            'error'
                                        );
                                    }
                                }
                            });
                        }
                    })
                });
            }

            $(document).on('click', '#tambah_user', function() {
                var c = 'create';
                var id = $(this).attr('data-id');
                $.ajax({
                    url: "/administrator/user/tambah/",
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {

                        $('#createmodal').modal("show");
                        $('#create').html(result).show();
                        select_data(c);

                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page " + href + " cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            })
            $(document).on('click', '#edit_user', function() {
                var id = $(this).attr('data-id');
                var e = 'edit';
                $.ajax({
                    url: "/administrator/user/ubah_data/" + id,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#editmodal').modal("show");
                        $('#edit').html(result).show();
                        select_data(e);
                        ResetPwd(id)

                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        alert("Page " + href + " cannot open. Error:" + error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            })
            $(document).on('click', '#status_user', function() {
                var id = $(this).attr('data-id');

                Swal.fire({
                    title: 'Ubah',
                    text: "Ubah status user ?",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: 'Tidak',
                    confirmButtonText: 'Iya',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('user.status') }}',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                "id": id,
                                "_method": "POST",
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(result) {
                                if (result.info == "success") {
                                    Swal.fire({
                                        title: 'Berhasil',
                                        text: 'Status user berhasil dirubah',
                                        icon: 'success',
                                    });
                                }
                            }
                        });
                    } else {
                        $('#showtable').DataTable().ajax.url(
                            '/administrator/user/data').load();
                    }
                })
            })
            var showtable = $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/administrator/user/data',
                    "dataType": "json",
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'divisi',
                        className: 'nowrap-text align-center',

                    },
                    {
                        data: 'username',
                        className: 'nowrap-text align-center',

                    },
                    {
                        data: 'nama',
                        className: 'nowrap-text align-center',

                    }, {
                        data: 'email',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'button',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false

                    }, {
                        data: 'edit',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
