@extends('adminlte.page')

@section('title', 'ERP')

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
    </style>
@endsection
@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Tambah Produk</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '26')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.produk.show') }}">Produk</a></li>
                        <li class="breadcrumb-item active">Tambah PO</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
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
                    <form action="{{ route('penjualan.produk.store') }}" method="post">
                        {{ csrf_field() }}
                        <div class="row d-flex justify-content-center">
                            <div class="col-11">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Info Umum Paket</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group row">
                                                    <label for="jenis_paket" class="col-5 col-form-label"
                                                        style="text-align: right">Jenis Paket</label>
                                                    <div class="col-6 col-form-label">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="jenis_paket" id="jenis_paket1" value="ekat"
                                                                checked="true" />
                                                            <label class="form-check-label" for="jenis_paket1">Produk
                                                                Ekatalog</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="jenis_paket" id="jenis_paket2" value="non" />
                                                            <label class="form-check-label" for="jenis_paket2">Produk Non
                                                                Ekatalog</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="nama_produk" class="col-5 col-form-label"
                                                        style="text-align: right">Nama Alias</label>
                                                    <div class="col-6">
                                                        <textarea type="text" class="form-control @error('nama_alias') is-invalid @enderror" name="nama_alias"
                                                            id="nama_alias" placeholder="Masukkan Nama Alias / Panjang"></textarea>
                                                        <div class="invalid-feedback" id="msgnama_alias">
                                                            @if ($errors->has('nama_alias'))
                                                                {{ $errors->first('nama_alias') }}
                                                            @endif
                                                        </div>
                                                        <div class="feedback" id="msgcustomer_id">
                                                            <small class="text-muted">Kosongi bila tidak ada</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="nama_produk" class="col-5 col-form-label"
                                                        style="text-align: right">Nama Paket</label>
                                                    <div class="col-6">
                                                        <input type="text"
                                                            class="form-control @error('nama_paket') is-invalid @enderror"
                                                            name="nama_paket" id="nama_paket"
                                                            placeholder="Masukkan Nama Paket" />
                                                        <div class="invalid-feedback" id="msgnama_paket">
                                                            @if ($errors->has('nama_paket'))
                                                                {{ $errors->first('nama_paket') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="nama_produk" class="col-5 col-form-label"
                                                        style="text-align: right">Harga</label>
                                                    <div class="input-group col-5">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">Rp</span>
                                                        </div>
                                                        <input type="text" class="form-control" name="harga"
                                                            id="harga" placeholder="Masukkan Harga" />
                                                        <div class="invalid-feedback" id="msgharga">
                                                            @if ($errors->has('harga'))
                                                                {{ $errors->first('harga') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="is_aktif" class="col-5 col-form-label"
                                                        style="text-align: right">Status</label>
                                                    <div class="col-6 col-form-label">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="is_aktif" id="is_aktif1" value="1"
                                                                checked="true" />
                                                            <label class="form-check-label" for="is_aktif1">Aktif</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="is_aktif" id="is_aktif2" value="0" />
                                                            <label class="form-check-label" for="is_aktif2">Tidak
                                                                Aktif</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Detail Produk Paket</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    <table class="table" style="text-align: center;" id="createtable">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="5">
                                                                    <button type="button"
                                                                        class="btn btn-primary float-right"
                                                                        id="addrow">
                                                                        <i class="fas fa-plus"></i>
                                                                        Produk
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th width="5%">No</th>
                                                                <th width="40%">Nama Produk</th>
                                                                <th width="32%">Kelompok</th>
                                                                <th width="18%">Jumlah</th>
                                                                <th width="5%">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>
                                                                    <div class="form-group row">
                                                                        <select class="select-info form-control produk_id"
                                                                            name="produk_id[]" id="0">
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td><span class="badge" name="kelompok_produk[]"
                                                                        id="kelompok_produk0"></span></td>
                                                                <td>
                                                                    <div class="form-group d-flex justify-content-center">
                                                                        <input type="number" class="form-control jumlah"
                                                                            name="jumlah[]" id="jumlah0"
                                                                            style="width: 50%" />
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <a id="removerow"><i class="fas fa-minus"
                                                                            style="color: red"></i></a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <span>
                                            <a type="button" class="btn btn-danger"
                                                href="{{ route('penjualan.produk.show') }}">
                                                Batal
                                            </a>
                                        </span>
                                        <span class="float-right">
                                            <button type="submit" class="btn btn-info float-right " id="btntambah"
                                                disabled>Tambah</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('adminlte_js')
    <script>
        $(document).ready(function() {
            select_data();
            var inputproduk = false;
            var inputjumlah = false;

            function validasi() {

                $('#createtable').find('.produk_id').each(function() {
                    if ($(this).val() != null) {
                        inputproduk = true;
                    } else {
                        inputproduk = false;
                        return false;
                    }
                });

                $('#createtable').find('.jumlah').each(function() {
                    if ($(this).val() != "") {
                        inputjumlah = true;
                    } else {
                        inputjumlah = false;
                        return false;
                    }
                });

                // var jumprodukterisi = $('#createtable').find('.produk_id').has(':selected').length;
                // var jumproduktdkterisi = $('#createtable').find('.produk_id').length;
                // if (jumproduktdkterisi <= jumprodukterisi) {
                //     console.log(jumproduktdkterisi +" "+ jumprodukterisi)
                //     inputproduk = true;
                // } else {
                //     inputproduk = false;
                //     return false;
                // }

                if (($('#nama_paket').val() != "" && !$('#nama_paket').hasClass('is-invalid')) && $('#nama_alias')
                    .val() != "" && inputproduk == true && inputjumlah == true && $("#createtable tbody").length >
                    0 && $("#harga").val() != "" && $('input[name="is_aktif"]:checked').val() != "" && $(
                        'input[name="jenis_paket"]:checked').val() != "") {
                    $("#btntambah").attr('disabled', false);
                } else {
                    $("#btntambah").attr('disabled', true);
                }
            }

            function numberRows($t) {
                var c = 0 - 2;
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;
                    $(el).find('.jumlah').attr('name', 'jumlah[' + j + ']');
                    $(el).find('.jumlah').attr('id', 'jumlah' + j);
                    $(el).find('.kelompok_produk').attr('name', 'kelompok_produk[' + j + ']');
                    $(el).find('.kelompok_produk').attr('id', 'kelompok_produk' + j);
                    $(el).find('.produk_id').attr('name', 'produk_id[' + j + ']');
                    $(el).find('.produk_id').attr('id', j);
                    select_data();
                });
            }

            $('#addrow').on('click', function() {
                $('#createtable tr:last').after(`<tr>
                <td></td>
                <td>
                    <div class="form-group row">
                            <select class="select-info form-control produk_id" name="produk_id[]" id="0">
                            </select>
                    </div>
                </td>
                <td><span class="badge kelompok_produk" name="kelompok_produk[]" id="kelompok_produk0"></span></td>
                <td>
                    <div class="form-group d-flex justify-content-center">
                        <input type="number" class="form-control jumlah" name="jumlah[]" id="jumlah0" style="width: 50%" />
                    </div>
                </td>
                <td>
                    <a id="removerow"><i class="fas fa-minus" style="color: red"></i></a>
                </td>
                </tr>`);
                validasi();
                numberRows($("#createtable"));
            });

            $('#createtable').on('click', '#removerow', function(e) {
                if ($('#createtable > tbody > tr').length > 1) {
                    $(this).closest('tr').remove();

                }
                numberRows($("#createtable"));
                validasi();
            });

            $('#harga').on('keyup change', function() {
                var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(result);

                if ($(this).val() != "") {
                    $('#msgharga').text("");
                    $('#harga').removeClass("is-invalid");
                } else if ($(this).val() == "") {
                    $('#msgharga').text("Harga Harus diisi");
                    $('#harga').addClass("is-invalid");
                }

                validasi();
            });

            $('#nama_paket').on('keyup change', function() {
                if ($(this).val() != "") {
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: '/api/penjualan_produk/check/0/' + $(this).val(),
                        success: function(data) {
                            if (data.jumlah >= 1) {
                                $("#msgnama_paket").text("Nama sudah terpakai");
                                $('#nama_paket').addClass('is-invalid');
                            } else {
                                $('#msgnama_paket').text("");
                                $('#nama_paket').removeClass("is-invalid");
                            }
                        }
                    });

                } else if ($(this).val() == "") {
                    $('#msgnama_paket').text("Nama Paket Harus diisi");
                    $('#nama_paket').addClass("is-invalid");
                }
                validasi();
            });

            $('#nama_alias').on('keyup change', function() {
                validasi();
            });

            $(document).on('keyup change', '#createtable .jumlah', function() {
                validasi();
            });

            $(document).on('change', 'input[name="is_aktif"]', function() {
                validasi();
            });

            $(document).on('change', 'input[name="jenis_paket"]', function() {
                validasi();
            });

            $(document).on('keyup change', '#createtable .jumlah', function() {
                validasi();
            });

            function select_data() {
                $('.produk_id').select2({
                    placeholder: "Pilih Produk",
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/produk/select/',
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
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
                }).change(function() {
                    var value = $(this).val();
                    var index = $(this).attr('id');
                    $.ajax({
                        url: '/api/produk/select/' + value,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#kelompok_produk' + index).text(data[0].kelompok_produk.nama);
                        }
                    });

                    validasi();
                });
            }
        });
    </script>
@endsection
