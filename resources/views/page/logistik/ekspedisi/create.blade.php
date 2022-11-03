@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Tambah Ekspedisi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '15')
                        <li class="breadcrumb-item"><a href="{{ route('logistik.dashboard') }}">Beranda</a></li>
                    @elseif(Auth::user()->Karyawan->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ route('logistik.ekspedisi.show') }}">Ekspedisi</a></li>
                    <li class="breadcrumb-item active">Tambah</li>

                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop


@section('adminlte_css')
    <style>
        .hide {
            display: none !important;
        }

        .removeboxshadow {
            box-shadow: none;
            border: 1px;
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
    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="content">
                        <form action="{{ route('logistik.ekspedisi.store') }}" method="post">
                            {{ csrf_field() }}
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-10 co-md-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group row">
                                                @if (session('error') || count($errors) > 0)
                                                    <div class="alert alert-danger alert-dismissible show fade col-lg-12"
                                                        role="alert">
                                                        <strong>Gagal menambahkan!</strong> Periksa
                                                        kembali data yang diinput
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                @elseif(session('success'))
                                                    <div class="alert alert-success alert-dismissible show fade col-lg-12"
                                                        role="alert">
                                                        <strong>Berhasil menambahkan data</strong>,
                                                        Terima kasih
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="card card-outline card-info">
                                                <div class="card-header">
                                                    <h6 class="card-title">Informasi Umum</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label for="nama_ekspedisi"
                                                            class="col-lg-4 col-md-12 col-form-label labelket">Nama
                                                            Ekspedisi</label>
                                                        <div class="col-6">
                                                            <input type="text"
                                                                class="form-control @error('nama_ekspedisi') is-invalid @enderror"
                                                                placeholder="Masukkan Nama Ekspedisi" id="nama_ekspedisi"
                                                                name="nama_ekspedisi" />
                                                            <div class="invalid-feedback" id="msgnama_ekspedisi">
                                                                @if ($errors->has('nama_ekspedisi'))
                                                                    {{ $errors->first('nama_ekspedisi') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="alamat"
                                                            class="col-lg-4 col-md-12 col-form-label labelket">Alamat</label>
                                                        <div class="col-lg-7 col-md-8">
                                                            <textarea class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan Alamat" id="alamat"
                                                                name="alamat"></textarea>
                                                            <div class="invalid-feedback" id="msgalamat">
                                                                @if ($errors->has('alamat'))
                                                                    {{ $errors->first('alamat') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="email"
                                                            class="col-lg-4 col-md-12 col-form-label labelket">Email</label>
                                                        <div class="col-8">
                                                            <input type="text"
                                                                class="form-control @error('email') is-invalid @enderror"
                                                                placeholder="Masukkan Email" id="email"
                                                                name="email" />
                                                            <div class="invalid-feedback" id="msgemail">
                                                                @if ($errors->has('email'))
                                                                    {{ $errors->first('email') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="telepon"
                                                            class="col-lg-4 col-md-12 col-form-label labelket">No
                                                            Telp</label>
                                                        <div class="col-5">
                                                            <input type="text"
                                                                class="form-control @error('telepon') is-invalid @enderror"
                                                                value="" placeholder="Masukkan Telepon" id="telepon"
                                                                name="telepon" />
                                                            <div class="invalid-feedback" id="msgtelepon">
                                                                @if ($errors->has('telepon'))
                                                                    {{ $errors->first('telepon') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card card-outline card-info">
                                                <div class="card-header">
                                                    <h6 class="card-title">Detail Ekspedisi</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label for=""
                                                            class="col-lg-4 col-md-12 col-form-label labelket">Jalur</label>
                                                        <div class="col-lg-5 col-md-12 col-form-label">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input jalur" type="checkbox"
                                                                    id="jalur1" value="1" name="jalur[]">
                                                                <label class="form-check-label"
                                                                    for="jalur1">Darat</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input jalur" type="checkbox"
                                                                    id="jalur2" value="2" name="jalur[]">
                                                                <label class="form-check-label"
                                                                    for="jalur2">Laut</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input jalur" type="checkbox"
                                                                    id="jalur3" value="3" name="jalur[]">
                                                                <label class="form-check-label"
                                                                    for="jalur3">Udara</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input jalur" type="checkbox"
                                                                    id="jalur4" value="4" name="jalur[]">
                                                                <label class="form-check-label"
                                                                    for="jalur4">Lain</label>
                                                            </div>
                                                            <div class="invalid-feedback" id="msgjalur">
                                                                @if ($errors->has('jalur'))
                                                                    {{ $errors->first('jalur') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for=""
                                                            class="col-lg-4 col-md-12 col-form-label labelket">Jurusan</label>
                                                        <div class="col-lg-8 col-md-12 col-form-label">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input jurusan" type="radio"
                                                                    name="jurusan" id="jurusan1" value="indonesia" />
                                                                <label class="form-check-label" for="jurusan1">Seluruh
                                                                    Indonesia</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input jurusan" type="radio"
                                                                    name="jurusan" id="jurusan2" value="provinsi" />
                                                                <label class="form-check-label" for="jurusan2">Per
                                                                    Provinsi</label>
                                                            </div>
                                                            <!-- <div class="form-check form-check-inline">
                                                                        <input class="form-check-input jurusan" type="radio" name="jurusan" id="jurusan3" value="kota_kabupaten" />
                                                                        <label class="form-check-label" for="jurusan3">Per Kota / Kabupaten</label>
                                                                    </div> -->

                                                            <div class="invalid-feedback" id="msgjurusan">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row hide" id="provinsi_select">
                                                        <label for="jurusan"
                                                            class="col-lg-4 col-md-12 col-form-label labelket">Provinsi</label>
                                                        <div class="col-lg-8 col-md-12">
                                                            <select class="provinsi form-control" name="provinsi[]"
                                                                id="provinsi" style="width: 100%;">
                                                            </select>
                                                            <div class="invalid-feedback" id="msgprovinsi">
                                                                @if ($errors->has('provinsi'))
                                                                    {{ $errors->first('provinsi') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row hide" id="kota_kabupaten_select">
                                                        <label for="jurusan"
                                                            class="col-lg-4 col-md-12 col-form-label labelket">Kota /
                                                            Kabupaten</label>
                                                        <div class="col-lg-8 col-md-12">
                                                            <select
                                                                class="select-info form-control custom-select kota_kabupaten"
                                                                name="kota_kabupaten" id="kota_kabupaten"
                                                                style="width: 100%;">
                                                            </select>
                                                            <div class="invalid-feedback" id="msgkota_kabupaten">
                                                                @if ($errors->has('kota_kabupaten'))
                                                                    {{ $errors->first('kota_kabupaten') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="telepon"
                                                            class="col-lg-4 col-md-12 col-form-label labelket">Keterangan</label>
                                                        <div class="col-lg-5 col-md-8">
                                                            <textarea class="form-control" name="keterangan" id="keterangan"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="form-group row">
                                                <span class="col-lg-6 col-md-6 float-left">
                                                    <a type="button" class="btn btn-danger"
                                                        href="{{ route('logistik.ekspedisi.show') }}">
                                                        Batal
                                                    </a>
                                                </span>

                                                <span class="col-lg-6 col-md-6">
                                                    <button type="submit" class="btn btn-info  float-right"
                                                        id="btntambah" disabled>
                                                        Tambah
                                                    </button>
                                                </span>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('adminlte_js')
    <script>
        $(function() {
            $('input[name="nama_ekspedisi"]').on('keyup change', function() {
                if ($(this).val() == "") {
                    $("#msgnama_ekspedisi").text("Nama tidak boleh kosong");
                    $('#nama_ekspedisi').addClass('is-invalid');
                    $("#btntambah").attr("disabled", true);
                } else if ($(this).val() != "") {
                    // if (checkCustomer($('#nama_ekspedisi').val()) >= 1) {
                    //     $("#msgnama_customer").text("Nama sudah terpakai");
                    //     $('#nama_ekspedisi").addClass('is-invalid');
                    //     $("#btntambah").attr("disabled", true);
                    // } else {
                    //     $("#msgnama_customer").text("");
                    //     $('#nama_ekspedisi").removeClass('is-invalid');
                    //     $("#btntambah").removeAttr("disabled");
                    // }
                    $("#msgnama_customer").val("");
                    $('#nama_ekspedisi').removeClass('is-invalid');
                    if ($('#telepon').val() != "" && $('#alamat').val() != "" && $(
                            'input[type="checkbox"][name="jalur[]"]:checked').length > 0 && $('#jurusan')
                        .val() != "") {
                        $("#btntambah").removeAttr("disabled");
                    } else {
                        $("#btntambah").attr("disabled", true);
                    }
                }
            });

            $('input[name="telepon"]').on('keyup change', function() {
                if ($(this).val() == "") {
                    $("#msgtelepon").text("Telepon tidak boleh kosong");
                    $("#telepon").addClass('is-invalid');
                    $("#btntambah").attr('disabled', true);
                } else if ($(this).val() != "") {
                    if (!/^[0-9]+$/.test($(this).val())) {
                        $("#msgtelepon").text("Isi nomor telepon dengan angka");
                        $("#telepon").addClass('is-invalid');
                        $("#btntambah").attr('disabled', true);
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
                        $("#btntambah").removeAttr('disabled');
                        if ($("#nama_ekspedisi").val() != "" && $("#alamat").val() != "" && $(
                                'input[type="checkbox"][name="jalur"]:checked').length > 0 && $('#jurusan')
                            .val() != "") {
                            $("#btntambah").removeAttr('disabled');
                        } else {
                            $("#btntambah").attr('disabled', true);
                        }
                    }
                }
            })

            $('#alamat').on('keyup change', function() {
                if ($(this).val() != "") {
                    $('#msgalamat').text("");
                    $('#alamat').removeClass("is-invalid");
                    if ($("#nama_ekspedisi").val() != "" && $("#telepon").val() != "" && $(
                            'input[type="checkbox"][name="jalur[]"]:checked').length > 0 && $('#jurusan')
                        .val() != "") {
                        $("#btntambah").removeAttr('disabled');
                    } else {
                        $("#btntambah").attr('disabled', true);
                    }
                } else {
                    $('#msgalamat').text("Alamat tidak boleh kosong");
                    $('#alamat').addClass("is-invalid");
                    $("#btntambah").attr('disabled', true);
                }
            });

            $('input[name="email"]').on('keyup change', function() {
                var errorhandling = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if ($(this).val() != "") {
                    if (!errorhandling.test($(this).val())) {
                        $('#msgemail').text("Masukkan email dengan benar");
                        $('#email').addClass("is-invalid");
                        $("#btntambah").attr('disabled', true);
                    } else {
                        $('#msgemail').text("");
                        $('#email').removeClass("is-invalid");
                        if ($("#nama_ekspedisi").val() != "" && $("#telepon").val() != "" && $("#alamat")
                            .val() != "" && $('input[type="checkbox"][name="jalur[]"]:checked').length >
                            0 && $('#jurusan').val() != "") {
                            $("#btntambah").removeAttr('disabled');
                        }
                    }
                } else {
                    $('#msgemail').text("");
                    $('#email').removeClass("is-invalid");
                    if ($("#nama_ekspedisi").val() != "" && $("#telepon").val() != "" && $("#alamat")
                    .val() != "" && $('input[type="checkbox"][name="jalur[][]"]:checked').length > 0 && $(
                            '#jurusan').val() != "") {
                        $("#btntambah").removeAttr('disabled');
                    }
                }
            })

            $('input[type="radio"][name="jurusan"]').on('change', function() {
                $(".provinsi").val(null).trigger('change');
                $(".kota_kabupaten").val(null).trigger('change');
                if ($(this).val() != "") {
                    if ($(this).val() == "provinsi") {
                        $('#provinsi_select').removeClass('hide');
                        $('#kota_kabupaten_select').addClass('hide');
                    } else if ($(this).val() == "kota_kabupaten") {
                        $('#provinsi_select').addClass('hide');
                        $('#kota_kabupaten_select').removeClass('hide');
                    } else if ($(this).val() == "indonesia") {
                        $('#provinsi_select').addClass('hide');
                        $('#kota_kabupaten_select').addClass('hide');
                    }
                    $('#msgjurusan').text("");
                    $('#jurusan').removeClass("is-invalid");
                    if ($("#nama_ekspedisi").val() != "" && $("#telepon").val() != "" && $(
                            'input[type="checkbox"][name="jalur[]"]:checked').length > 0 && $('#alamat')
                        .val() != "") {
                        $("#btntambah").removeAttr('disabled');
                    } else {
                        $("#btntambah").attr('disabled', true);
                    }
                } else {
                    $('#msgjurusan').text("jurusan tidak boleh kosong");
                    $('#jurusan').addClass("is-invalid");
                    $("#btntambah").attr('disabled', true);
                }
            });

            $('input[type="checkbox"][name="jalur[]"]').on('keyup change', function() {
                console.log($('input[type="checkbox"][name="jalur[]"]:checked').val());
                if ($('input[type="checkbox"][name="jalur[]"]:checked').length > 0) {
                    $('#msgjalur').text("");
                    $('input[type="checkbox"][name="jalur[]"]').removeClass("is-invalid");
                    if ($("#nama_ekspedisi").val() != "" && $("#telepon").val() != "" && $('#jurusan')
                    .val() != "" && $('#alamat').val() != "") {
                        $("#btntambah").removeAttr('disabled');
                    } else {
                        $("#btntambah").attr('disabled', true);
                    }
                } else if ($('input[type="checkbox"][name="jalur[]"]:checked').length <= 0) {
                    $('#msgjalur').text("Jalur tidak boleh kosong");
                    $('input[type="checkbox"][name="jalur[]"]').addClass("is-invalid");
                    $("#btntambah").attr('disabled', true);
                }
            });

            $('.provinsi').select2({
                multiple: true,
                placeholder: 'Pilih Provinsi',
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
                },

            });

            $('.kota_kabupaten').select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    dataType: 'json',
                    delay: 250,
                    type: 'GET',
                    url: '/api/kota_kabupaten/select',
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
                                    text: obj.text,
                                    children: $.map(obj.children, function(objs) {
                                        return {
                                            id: objs.id,
                                            text: objs.text
                                        }
                                    })
                                };
                            })
                        };
                    },
                }
            });
        })
    </script>
@stop
