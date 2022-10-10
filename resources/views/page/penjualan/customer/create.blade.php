@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-lg-6 col-md-4 col-sm-4">
            <h1 class="m-0  text-dark">Tambah Customer</h1>
        </div><!-- /.col -->
        <div class="col-lg-6 col-md-8 col-sm-8">
            <ol class="breadcrumb float-sm-right">
                @if(Auth::user()->divisi_id == "26")
                <li class="breadcrumb-item"><a href="{{route('penjualan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{route('penjualan.customer.show')}}">Customer</a></li>
                <li class="breadcrumb-item active">Tambah Customer</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
<style>
    .hide{
        display: none !important
    }
    .removeboxshadow {
        box-shadow: none;
        border: 1px;
    }

    .bg-color{
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
        <form action="{{route('penjualan.customer.store')}}" method="post">
            {{csrf_field()}}
            <input type="hidden" name="user_id" value={{ Auth::user()->id }}>
            <div class="row d-flex justify-content-center">
                <div class="col-lg-10 co-md-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group row">
                                        @if(session('error') || count($errors) > 0 )
                                        <div class="alert alert-danger alert-dismissible show fade col-lg-12" role="alert">
                                            <strong>Gagal menambahkan!</strong> Periksa
                                            kembali data yang diinput
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        @elseif(session('success'))
                                        <div class="alert alert-success alert-dismissible show fade col-lg-12" role="alert">
                                            <strong>Berhasil menambahkan data</strong>,
                                            Terima kasih
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12" >
                                            <div class="card card-outline card-info">
                                                <div class="card-header">
                                                    <h6 class="card-title">Informasi Umum</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label for="nama_produk" class="col-lg-4 col-md-12 col-form-label labelket">Nama Customer</label>
                                                        <div class="col-lg-6 col-md-12">
                                                            <input type="text" class="form-control col-form-label @error('nama_customer') is-invalid @enderror" placeholder="Masukkan Nama Customer" id="nama_customer" name="nama_customer" />
                                                            <div class="invalid-feedback" id="msgnama_customer">
                                                                @if($errors->has('nama_customer'))
                                                                {{ $errors->first('nama_customer')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="alamat" class="col-lg-4 col-md-12 col-form-label labelket">Alamat</label>
                                                        <div class="col-lg-6 col-md-12">
                                                            <textarea class="form-control col-form-label @error('alamat') is-invalid @enderror" name="alamat" id="alamat" placeholder="Masukkan Alamat"></textarea>
                                                            {{-- <input type="text" class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan Alamat" id="alamat" name="alamat" /> --}}
                                                            <div class="invalid-feedback" id="msgalamat">
                                                                @if($errors->has('alamat'))
                                                                {{ $errors->first('alamat')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="email" class="col-lg-4 col-md-12 col-form-label labelket">Email</label>
                                                        <div class="col-lg-6 col-md-12">
                                                            <input type="text" class="form-control col-form-label @error('email') is-invalid @enderror" placeholder="Masukkan Email" id="email" name="email" />
                                                            <div class="invalid-feedback" id="msgemail">
                                                                @if($errors->has('email'))
                                                                {{ $errors->first('email')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="provinsi" class="col-lg-4 col-md-12 col-form-label labelket">Provinsi</label>
                                                        <div class="col-lg-5 col-md-12">
                                                            <input type="text" class="form-control col-form-label @error('pemilik') is-invalid @enderror" placeholder="Nama Pemilik" id="pemilik" name="pemilik" />
                                                            <div class="invalid-feedback" id="msgpemilik">
                                                                @if($errors->has('pemilik'))
                                                                {{ $errors->first('pemilik')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12">
                                            <div class="card card-outline card-info">
                                                <div class="card-header">
                                                    <h6 class="card-title">Detail Customer</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label for="telepon" class="col-lg-4 col-md-12 col-form-label labelket">No Telp</label>
                                                        <div class="col-lg-5 col-md-12">
                                                            <input type="text" class="form-control col-form-label @error('telepon') is-invalid @enderror" value="" placeholder="Masukkan Telepon" id="telepon" name="telepon" />
                                                            <div class="invalid-feedback" id="msgtelepon">
                                                                @if($errors->has('telepon'))
                                                                {{ $errors->first('telepon')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row hide">
                                                        <label for="pemilik" class="col-lg-4 col-md-12 col-form-label labelket">Nama Pemilik</label>
                                                        <div class="col-lg-5 col-md-12">
                                                            <input type="text" class="form-control col-form-label @error('pemilik') is-invalid @enderror" placeholder="Nama Pemilik" id="pemilik" name="pemilik" />
                                                            <div class="invalid-feedback" id="msgpemilik">
                                                                @if($errors->has('pemilik'))
                                                                {{ $errors->first('pemilik')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12">
                                            <div class="card card-outline card-info">
                                                <div class="card-header">
                                                    <h6 class="card-title">Detail Customer</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label for="pic" class="col-lg-4 col-md-12 col-form-label labelket">PIC</label>
                                                        <div class="col-lg-5 col-md-12">
                                                            <input type="text" class="form-control col-form-label @error('pic') is-invalid @enderror" placeholder="Nama PIC" id="pic" name="pic" />
                                                            <div class="invalid-feedback" id="msgpic">
                                                                @if($errors->has('pic'))
                                                                {{ $errors->first('pic')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="ktp" class="col-lg-4 col-md-12 col-form-label labelket">No KTP</label>
                                                        <div class="col-lg-5 col-md-12">
                                                            <input type="text" class="form-control col-form-label @error('ktp') is-invalid @enderror" value="" placeholder="Masukkan KTP" id="ktp" name="ktp" />
                                                            <div class="invalid-feedback" id="msgktp">
                                                                @if($errors->has('ktp'))
                                                                {{ $errors->first('ktp')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="npwp" class="col-lg-4 col-md-12 col-form-label labelket">NPWP</label>
                                                        <div class="col-lg-5 col-md-12">
                                                            <input type="text" class="form-control col-form-label @error('npwp') is-invalid @enderror" value="" placeholder="Masukkan NPWP" id="npwp" name="npwp" />
                                                            <div class="invalid-feedback" id="msgnpwp">
                                                                @if($errors->has('npwp'))
                                                                {{ $errors->first('npwp')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="batas" class="col-form-label col-lg-4 col-md-12 labelket">Batas Pembayaran</label>
                                                        <div class="col-lg-4 col-md-6 input-group">
                                                            <input type="text" class="form-control col-form-label @error('batas') is-invalid @enderror" name="batas" id="batas" aria-label="batas" placeholder="Batas hari pembayaran" />
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="ket_no_paket">Hari</span>
                                                            </div>
                                                            <div class="invalid-feedback" id="msgno_batas">
                                                                @if($errors->has('batas'))
                                                                {{ $errors->first('batas')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="keterangan" class="col-lg-4 col-md-12 col-form-label labelket">Keterangan</label>
                                                        <div class="col-lg-5 col-md-11">
                                                            <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Masukkan Keterangan"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="batas" class="col-form-label col-lg-4 col-md-12 labelket">Batas Pembayaran</label>
                                                        <div class="col-lg-4 col-md-6 input-group">
                                                            <input type="text" class="form-control col-form-label @error('batas') is-invalid @enderror" name="batas" id="batas" aria-label="batas" placeholder="Batas hari pembayaran" />
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="ket_no_paket">Hari</span>
                                                            </div>
                                                            <div class="invalid-feedback" id="msgno_batas">
                                                                @if($errors->has('batas'))
                                                                {{ $errors->first('batas')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="keterangan" class="col-lg-4 col-md-12 col-form-label labelket">Keterangan</label>
                                                        <div class="col-lg-5 col-md-11">
                                                            <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Masukkan Keterangan"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row hide">
                                                        <label for="izin_usaha" class="col-lg-4 col-md-12 col-form-label labelket">Izin Usaha</label>
                                                        <div class="col-lg-8 col-md-11 col-form-label">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="izin_usaha" id="izin_usaha1" value="IUMK" data-toggle="iumk_info" title="merge"/>
                                                                <label class="form-check-label" for="izin_usaha1">IUMK</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="izin_usaha" id="izin_usaha2" value="IUTM" data-toggle="iutm_info" title="merge" />
                                                                <label class="form-check-label" for="izin_usaha2">IUTM</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="izin_usaha" id="izin_usaha3" value="SIUP"  data-toggle="siup_info" title="merge" />
                                                                <label class="form-check-label" for="izin_usaha3">SIUP</label>
                                                            </div>
                                                            <div class="invalid-feedback" id="msgizin_usaha">
                                                                @if($errors->has('izin_usaha'))
                                                                {{ $errors->first('izin_usaha')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12 hide">
                                            <div class="card removeboxshadow">
                                                <div class="card-body">

                                                    <div class="form-group row hide">
                                                        <label for="modal_usaha" class="col-lg-4 col-md-12 col-form-label labelket">Modal Usaha</label>
                                                        <div class="col-lg-8 col-md-11 col-form-label">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="modal_usaha" id="modal_usaha1" value="1" />
                                                                <label class="form-check-label" for="modal_usaha1"> < 1 M </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="modal_usaha" id="modal_usaha2" value="2" />
                                                                <label class="form-check-label" for="modal_usaha2"> > 1 M & < 5 M </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="modal_usaha" id="modal_usaha3" value="3" />
                                                                <label class="form-check-label" for="modal_usaha3"> > 5 M & < 10 M </label>
                                                            </div>
                                                            <div class="invalid-feedback" id="msgmodal_usaha">
                                                                @if($errors->has('modal_usaha'))
                                                                {{ $errors->first('modal_usaha')}}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row hide">
                                                        <label for="hasil_penjualan" class="col-lg-4 col-md-12 col-form-label labelket">Hasil Penjualan</label>
                                                        <div class="col-lg-8 col-md-11 col-form-label">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="hasil_penjualan" id="hasil_penjualan1" value="1" />
                                                                <label class="form-check-label" for="hasil_penjualan1"> < 2 M </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="hasil_penjualan" id="hasil_penjualan2" value="2" />
                                                                <label class="form-check-label" for="hasil_penjualan2"> > 2 M & < 15 M </label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="hasil_penjualan" id="hasil_penjualan3" value="3" />
                                                                <label class="form-check-label" for="hasil_penjualan3"> > 15 M & < 50 M </label>
                                                            </div>
                                                            <div class="invalid-feedback" id="msghasil_penjualan">
                                                                @if($errors->has('hasil_penjualan'))
                                                                {{ $errors->first('hasil_penjualan')}}
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
                                            <a type="button" class="btn btn-danger" href="{{route('penjualan.customer.show')}}">
                                                Batal
                                            </a>
                                        </span>

                                        <span class="col-6">
                                            <button type="submit" class="btn btn-info float-right" id="btntambah" disabled>
                                                Tambah
                                            </button>
                                        </span>
                                    </div>
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
<script type="text/javascript" src="{{ asset('vendor/masking/masking.js') }}"></script>
<script>
    $(function() {
        function validasi(){
            if (($("#nama_customer").val() != "" && !$("#nama_customer").hasClass('is-invalid')) && ($("#npwp").val() != "" && !$("#npwp").hasClass('is-invalid')) && $("#alamat").val() != "" && $('.provinsi').val() != "" && ($("#telepon").val() != "" && !$("#telepon").hasClass('is-invalid')) && !$("#email").hasClass('is-invalid')) {
                $("#btntambah").removeAttr('disabled');
            } else {
                $("#btntambah").attr('disabled', true);
            }
        }

        $('#npwp').mask('00.000.000.0-000.000');
        $('#ktp').mask('0000000000000000');

        $('input[name="nama_customer"]').on('keyup change', function() {
            var val = $(this).val();
            if ($(this).val() == "") {
                $("#msgnama_customer").text("Nama tidak boleh kosong");
                $('#nama_customer').addClass('is-invalid');
            } else if ($(this).val() != "") {
                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    url: '/api/customer/nama/0/' + val,
                    success: function(data) {
                        if (data >= 1) {
                            $("#msgnama_customer").text("Nama sudah terpakai");
                            $('#nama_customer').addClass('is-invalid');
                            // $("#btnsimpan").attr("disabled", true);
                        } else {
                            $("#msgnama_customer").text("");
                            $('#nama_customer').removeClass('is-invalid');
                            // if ($('#telepon').val() != "" && $('#npwp').val() != "" && $('#alamat').val() != "" && $('.provinsi').val() != "") {
                            //     $("#btnsimpan").removeAttr("disabled");
                            // } else {
                            //     $("#btnsimpan").attr("disabled", true);
                            // }
                        }
                    }
                });

            }

            validasi();
        });

        $('input[name="telepon"]').on('keyup change', function() {
            if ($(this).val() == "") {
                $("#msgtelepon").text("Telepon tidak boleh kosong");
                $("#telepon").addClass('is-invalid');
                // $("#btntambah").attr('disabled', true);
            } else if ($(this).val() != "") {
                if (!/^[0-9]+$/.test($(this).val())) {
                    $("#msgtelepon").text("Isi nomor telepon dengan angka");
                    $("#telepon").addClass('is-invalid');
                    // $("#btntambah").attr('disabled', true);
                } else {
                    // if (checkTelepon(this.teleponer).value >= 1) {
                    //     this.msg["telepon"] = "Nomor Telepon sudah terpakai";
                    //     this.teleponer = true;
                    //     this.btndis = true;
                    // } else {
                    //     this.msg["telepon"] = "";
                    //     this.teleponer = false;
                    //     this.btndis = false;
                    // }
                    $("#msgtelepon").text("");
                    $("#telepon").removeClass('is-invalid');
                    // $("#btntambah").removeAttr('disabled');
                    // if (($("#nama_customer").val() != "" && !$("#nama_customer").hasClass('is-invalid')) && ($("#npwp").val() != "" && !$("#npwp").hasClass('is-invalid')) && $("#alamat").val() != "" && $('.provinsi').val() != "") {
                    //     $("#btntambah").removeAttr('disabled');
                    // } else {
                    //     $("#btntambah").attr('disabled', true);
                    // }
                }
            }

            validasi();
        })

        $("#alamat").on('keyup change', function() {
            if ($(this).val() != "") {
                $('#msgalamat').text("");
                $('#alamat').removeClass("is-invalid");
                // if (($("#nama_customer").val() != "" && !$("#nama_customer").hasClass('is-invalid')) && ($("#npwp").val() != "" && !$("#npwp").hasClass('is-invalid')) && $("#telepon").val() != "" && $('.provinsi').val() != "") {
                //     $("#btntambah").removeAttr('disabled');
                // } else {
                //     $("#btntambah").attr('disabled', true);
                // }
            } else {
                $('#msgalamat').text("Alamat tidak boleh kosong");
                $('#alamat').addClass("is-invalid");
                // $("#btntambah").attr('disabled', true);
            }
            validasi();
        });

        $('input[name="npwp"]').on('keyup change', function() {
            if ($(this).val() == "") {
                $("#msgnpwp").text("NPWP tidak boleh kosong");
                $('#npwp').addClass('is-invalid');
            } else if ($(this).val() != "") {
                // if (checkCustomer($('#npwp').val()) >= 1) {
                //     $("#msgnpwp").text("Nama sudah terpakai");
                //     $('#npwp").addClass('is-invalid');
                //     $("#btntambah").attr("disabled", true);
                // } else {
                //     $("#msgnpwp").text("");
                //     $('#npwp").removeClass('is-invalid');
                //     $("#btntambah").removeAttr("disabled");
                // }
                if (!/^[0-9.-]+$/.test($(this).val())) {
                    $('#msgnpwp').text("Masukkan npwp dengan benar");
                    $('#npwp').addClass("is-invalid");
                    // $("#btntambah").attr('disabled', true);
                } else {
                    $("#msgnpwp").text("");
                    $('#npwp').removeClass('is-invalid');
                //     if ($('#telepon').val() != "" && ($("#nama_customer").val() != "" && !$("#nama_customer").hasClass('is-invalid')) && $('#alamat').val() != "" && $('.provinsi').val() != "") {
                //         $("#btntambah").removeAttr("disabled");
                //     } else {
                //         $("#btntambah").attr("disabled", true);
                //     }
                }

                validasi();
            }
        });

        $('input[name="email"]').on('keyup change', function() {
            var errorhandling = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            if ($(this).val() != "") {
                if (!errorhandling.test($(this).val())) {
                    $('#msgemail').text("Masukkan email dengan benar");
                    $('#email').addClass("is-invalid");
                    // $("#btntambah").attr('disabled', true);
                } else {
                    $('#msgemail').text("");
                    $('#email').removeClass("is-invalid");
                    // if (($("#nama_customer").val() != "" && !$("#nama_customer").hasClass('is-invalid')) && ($("#npwp").val() != "" && !$("#npwp").hasClass('is-invalid')) && $("#telepon").val() != "" && $("#alamat").val() != "" && $('.provinsi').val() != "") {
                    //     $("#btntambah").removeAttr('disabled');
                    // }
                }
            } else {
                $('#msgemail').text("");
                $('#email').removeClass("is-invalid");
                // if (($("#nama_customer").val() != "" && !$("#nama_customer").hasClass('is-invalid')) && ($("#npwp").val() != "" && !$("#npwp").hasClass('is-invalid')) && $("#telepon").val() != "" && $("#alamat").val() != "" && $('.provinsi').val() != "") {
                //     $("#btntambah").removeAttr('disabled');
                // }
            }

            validasi();
        })

        $('.provinsi').select2({
            placeholder: "Pilih Provinsi",
            ajax: {
                minimumResultsForSearch: 20,
                dataType: 'json',
                delay: 250,
                type: 'GET',
                url: '/api/provinsi/select',
                data: function(params) {
                    return {
                        term: params.term
                    }
                },
                processResults: function(data) {
                    console.log(data);
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.nama
                            };
                        })
                    };
                },
            }
        }).on('change', function(){
            validasi();
        });

    })
</script>
<script>
    $(document).ready(function(){
      $('[data-toggle="iumk_info"]').tooltip({ content: '<p><b>Izin usaha mikro dan kecil (IUMK)</b> adalah tanda legalitas kepada seseorang atau pelaku usaha/kegiatan tertentu dalam bentuk izin usaha mikro dan kecil dalam bentuk satu lembar</p>' });
      $('[data-toggle="iutm_info"]').tooltip({ content: '<p><b>Izin Usaha Toko Modern selanjutnya (IUTM)</b> adalah izin untuk dapat melaksanakan usaha pengelolaan Toko Modern yang diterbitkan oleh Pemerintah Daerah setempat</p>' });
      $('[data-toggle="siup_info"]').tooltip({ content: '<p><b>Surat Izin Usaha Perdagangan (SIUP)</b> adalah surat ijin yang diberikan kepada suatu badan usaha untuk dapat melakukan kegiatan usaha perdagangan</p>' });
    });
    </script>
@stop
