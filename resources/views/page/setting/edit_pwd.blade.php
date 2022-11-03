@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-6 col-md-4 col-sm-4">

            </div><!-- /.col -->
            <div class="col-lg-6 col-md-8 col-sm-8">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '26')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Ubah Password</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        .hide {
            display: none !important
        }

        .removeboxshadow {
            box-shadow: none;
            border: 1px;
        }

        .bg-color {
            background-color: #e8fafc;
        }

        @media screen and (min-width: 993px) {
            .labelket {
                text-align: right;
            }

            section {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 992px) {
            .labelket {
                text-align: left;
            }

            section {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }
        }

        div.ui-tooltip {
            max-width: 400px;
        }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <form id="update_pwd" action="/edit_pwd">
                <input type="hidden" name="user_id" value={{ Auth::user()->id }}>
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-10 co-md-12">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="card card-outline card-info">
                                            <div class="card-header">
                                                <h6 class="card-title">Ubah Password</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group row">
                                                    <label for="nama_produk"
                                                        class="col-lg-5 col-md-12 col-sm-12 col-form-label labelket">Password
                                                        Lama</label>
                                                    <div class="col-lg-3 col-md-8 col-sm-12">
                                                        <input type="password"
                                                            class="form-control col-form-label @error('pwd_lama') is-invalid @enderror"
                                                            placeholder="Password Lama" id="pwd_lama" name="pwd_lama" />
                                                        <div class="invalid-feedback" id="msgpwd_lama">
                                                            @if ($errors->has('pwd_lama'))
                                                                {{ $errors->first('pwd_lama') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="password"
                                                        class="col-lg-5 col-md-12 col-sm-12 col-form-label labelket">Password
                                                        Baru</label>
                                                    <div class="col-lg-3 col-md-8 col-sm-12">
                                                        <input type="password"
                                                            class="form-control col-form-label @error('password') is-invalid @enderror"
                                                            value="" placeholder="Password Baru" id="password"
                                                            name="password" />
                                                        <div class="invalid-feedback" id="msgpassword">
                                                            @if ($errors->has('password'))
                                                                {{ $errors->first('password') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="password_confirmation"
                                                        class="col-lg-5 col-md-12 col-sm-12 col-form-label labelket">Konfirmasi
                                                        Password
                                                        Baru</label>
                                                    <div class="col-lg-3 col-md-8 col-sm-12">
                                                        <input type="password"
                                                            class="form-control col-form-label @error('password_confirmation') is-invalid @enderror"
                                                            value="" placeholder="Konfirmasi Password Baru"
                                                            id="password_confirmation" name="password_confirmation" />
                                                        <div class="invalid-feedback" id="msgpassword_confirmation">
                                                            @if ($errors->has('password_confirmation'))
                                                                {{ $errors->first('password_confirmation') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>




                                </div>
                                <div class="form-group row">
                                    <span class="float-left col-6">
                                        <a type="button" class="btn btn-danger" href="/home">
                                            Batal
                                        </a>
                                    </span>

                                    <span class="col-6">
                                        <button type="submit" class="btn btn-info float-right" id="btntambah">
                                            Update Password
                                        </button>
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@stop

@section('adminlte_js')

    <script>
        $(function() {

            $(document).on('submit', '#update_pwd', function(e) {
                e.preventDefault();
                var $form = $(this);
                var $inputs = $form.find("input, select, button, textarea");
                var serializedData = $form.serialize();
                console.log(serializedData);
                var action = $(this).attr('action');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: action,
                    data: serializedData,
                    dataType: 'JSON',
                    success: function(response) {
                        if (response['data'] == "success") {
                            $('#pwd_lama').val('');
                            $('#password').val('');
                            $('#password_confirmation').val('');
                            swal.fire(
                                'Berhasil',
                                'Password berhasil diupdate',
                                'success'
                            );

                        } else if (response['data'] == "invalid") {
                            swal.fire(
                                'Error',
                                'Password Lama Salah',
                                'warning'
                            );

                        } else if (response['data'] == "same") {
                            swal.fire(
                                'Error',
                                'Password baru tidak boleh sama dengan password lama',
                                'warning'
                            );

                        } else {
                            swal.fire(
                                'Error',
                                'Cek form kembali',
                                'warning'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr)
                    }
                });

            });
        });
    </script>
@stop
