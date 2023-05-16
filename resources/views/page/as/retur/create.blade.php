@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Tambah Retur</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('as.retur.show') }}">Memo Retur</a></li>
                        <li class="breadcrumb-item active">Tambah Memo Retur</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        table>tbody>tr>td>.select2>.selection>.select2-selection--single {
            height: 100% !important;
        }

        table>tbody>tr>td>.select2>.selection>.select2-selection>.select2-selection__rendered {
            word-wrap: break-word !important;
            text-overflow: inherit !important;
            white-space: normal !important;
        }

        .hide {
            display: none !important;
        }

        .margin {
            margin: 5px;
        }

        .align-center {
            text-align: center;
        }

        .removeshadow {
            box-shadow: none;
        }

        @media screen and (min-width: 1220px) {

            body {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .labelket {
                text-align: right;
            }

            .overflowy {
                max-height: 330px;
                overflow-y: scroll;
                box-shadow: none;
            }

            .cust {
                max-width: 40%;
            }

        }

        @media screen and (max-width: 1219px) {
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: right;
            }

            .overflowy {
                max-height: 330px;
                overflow-y: scroll;
                box-shadow: none;
            }

            .cust {
                max-width: 40%;
            }
        }

        @media screen and (max-width: 991px) {
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: left;
            }

            .margin-md {
                margin-top: 10px;
            }

            .align-md {
                text-align: center;
            }

            .overflowy {
                max-height: 455px;
                overflow-y: scroll;
                box-shadow: none;
            }
        }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="box-shadow:none;">
                        @if (Session::has('error') || count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>{{ Session::get('error') }}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @elseif(Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ Session::get('success') }}</strong>,
                                Terima kasih
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('as.retur.store') }}" id="formtambahretur">
                            @csrf
                            <div class="card-body">
                                <div class="form-horizontal">
                                    <div id="informasi_pelanggan" class="card card-outline card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Informasi Transaksi</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row" id="tgl_retur_input">
                                                <label for="tgl_retur"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Tanggal Retur</label>
                                                <div class="col-lg-2 col-md-6">
                                                    <input type="date" name="tgl_retur" id="tgl_retur"
                                                        class="form-control col-form-label tgl_retur  @error('tgl_retur') is-invalid @enderror">
                                                    <div class="invalid-feedback" id="msgtgl_retur"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="pilih_jenis_retur_input">
                                                <label for="pilih_jenis_retur" class="col-lg-5 col-md-12 col-form-label labelket">Jenis Retur</label>
                                                <div class="col-lg-4 col-md-12 d-flex justify-content-between">
                                                    <div class="form-check form-check-inline col-form-label">
                                                        <input class="form-check-input" type="radio"
                                                            name="pilih_jenis_retur" id="pilih_jenis_retur1"
                                                            value="peminjaman" />
                                                        <label class="form-check-label"
                                                            for="pilih_jenis_retur1">Peminjaman</label>
                                                    </div>
                                                    <div class="form-check form-check-inline col-form-label">
                                                        <input class="form-check-input" type="radio"
                                                            name="pilih_jenis_retur" id="pilih_jenis_retur2"
                                                            value="komplain" />
                                                        <label class="form-check-label"
                                                            for="pilih_jenis_retur2">Komplain</label>
                                                    </div>
                                                    <div class="form-check form-check-inline col-form-label">
                                                        <input class="form-check-input" type="radio"
                                                            name="pilih_jenis_retur" id="pilih_jenis_retur3"
                                                            value="service" />
                                                        <label class="form-check-label"
                                                            for="pilih_jenis_retur3">Service</label>
                                                    </div>
                                                    <div class="form-check form-check-inline col-form-label">
                                                        <input class="form-check-input" type="radio"
                                                            name="pilih_jenis_retur" id="pilih_jenis_retur4"
                                                            value="none"/>
                                                        <label class="form-check-label"
                                                            for="pilih_jenis_retur4">Tanpa Status</label>
                                                    </div>
                                                    <div class="invalid-feedback" id="msgpilih_jenis_retur"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="keterangan_input">
                                                <label for="keterangan"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Keterangan
                                                    Retur</label>
                                                <div class="col-lg-4 col-md-12">
                                                    <textarea name="keterangan" id="keterangan"
                                                        class="form-control col-form-label keterangan  @error('keterangan') is-invalid @enderror"></textarea>
                                                    <div class="invalid-feedback" id="msgketerangan"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="informasi_pelanggan" class="card card-outline card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title" id="title_info_cust">Informasi Pelanggan</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row" id="pic_peminjaman_input">
                                                <label for="pic_peminjaman"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">PIC</label>
                                                <div class="col-lg-3 col-md-7">
                                                    {{-- <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <select class="form-control custom-select select2 divisi_id"
                                                                id="divisi_id" name="divisi_id">
                                                            </select>
                                                        </div> --}}
                                                        <input type="text" name="pic_peminjaman" id="pic_peminjaman" class="form-control col-form-label pic_peminjaman  @error('pic_peminjaman') is-invalid @enderror"/>
                                                    {{-- </div> --}}
                                                    <small class="text-success mt-1" id="infono_transaksi">* Pilih PIC / isi jika tidak tersedia</small>
                                                    <div class="invalid-feedback" id="msgpic_peminjaman"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="telp_pic" class="col-lg-5 col-md-12 col-form-label labelket">Telepon PIC</label>
                                                <div class="col-lg-2 col-md-6">
                                                    <input type="text" name="telp_pic" id="telp_pic" class="form-control col-form-label telp_pic  @error('telp_pic') is-invalid @enderror"/>
                                                    <div class="invalid-feedback" id="msgtelp_pic"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row hide">
                                                <input type="text" name="karyawan_id" id="karyawan_id"
                                                            class="form-control col-form-label karyawan_id  @error('karyawan_id') is-invalid @enderror"
                                                            width="60%" />
                                            </div>
                                            <div class="form-group row" id="no_transaksi_input">
                                                <label for="no_transaksi"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">No Transaksi
                                                    </label>
                                                <div class="col-lg-3 col-md-6">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <select
                                                                    class="form-control custom-select select2 no_transaksi_ref"
                                                                    id="no_transaksi_ref" name="no_transaksi_ref">
                                                                    <option value="po">No PO</option>
                                                                    <option value="so">No SO</option>
                                                                    <option value="no_akn">No AKN</option>
                                                                    <option value="no_retur">No Retur</option>
                                                                    <option value="no_sj">No SJ</option>
                                                                    <option value="sj_retur">No SJ (Retur)</option>
                                                                </select>
                                                            </div>
                                                            <input type="text" name="no_transaksi" id="no_transaksi"
                                                                class="form-control col-form-label no_transaksi  @error('no_transaksi') is-invalid @enderror" />
                                                        </div>
                                                        <small class="text-success mt-1" id="infono_transaksi">* Pilih Nomor Referensi yang akan dipakai</small>
                                                        <div class="invalid-feedback mt-1" id="msgno_transaksi"></div>
                                                    </div>
                                            </div>
                                            <div class="form-group row hide">
                                                <input type="text" name="pesanan_id" id="pesanan_id"
                                                            class="form-control col-form-label pesanan_id  @error('pesanan_id') is-invalid @enderror"
                                                            width="60%" />
                                            </div>

                                            <div class="form-group row" id="customer_nama_input">
                                                <label for="customer_nama"
                                                    class="col-lg-5 col-md-12 col-form-label labelket"
                                                    id="label_cust">Nama Customer</label>
                                                <div class="col-lg-4 col-md-8">
                                                    <input name="customer_nama" id="customer_nama"
                                                        class="form-control col-form-label customer_nama  @error('customer_nama') is-invalid @enderror" />
                                                    <div class="invalid-feedback" id="msgcustomer_nama"></div>
                                                </div>
                                            </div>

                                            <div class="form-group row hide">
                                                <div class="col-lg-4 col-md-8">
                                                    <input name="customer_id" id="customer_id"
                                                        class="form-control col-form-label customer_id  @error('customer_id') is-invalid @enderror" />
                                                    <div class="invalid-feedback" id="msgcustomer_id"></div>
                                                </div>
                                            </div>

                                            <div class="form-group row" id="alamat_input">
                                                <label for="alamat"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Alamat</label>
                                                <div class="col-lg-4 col-md-8">
                                                    <textarea name="alamat" id="alamat"
                                                        class="form-control col-form-label alamat  @error('alamat') is-invalid @enderror"></textarea>
                                                    <div class="invalid-feedback" id="msgalamat"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="tgl_retur_input">
                                                <label for="tgl_retur"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Telepon</label>
                                                <div class="col-lg-2 col-md-6">
                                                    <input name="telepon" id="telepon"
                                                        class="form-control col-form-label telepon  @error('telepon') is-invalid @enderror" />
                                                    <div class="invalid-feedback" id="msgtelepon"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="barang_penjualan" class="card card-outline card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Barang Retur</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row align-items-start">
                                                <div id="card_produk"
                                                    class="card card-outline card-success col mx-2 collapsed-card">
                                                    <div class="card-header">
                                                        <div
                                                            class="form-check form-check-inline col-form-label card-title">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="pilih_jenis_barang[]" id="pilih_jenis_barang1"
                                                                value="produk" />
                                                            <h6 class="form-check-label" for="pilih_jenis_barang1">Produk
                                                            </h6>
                                                        </div>
                                                        <div class="card-tools col-form-label">
                                                            <button type="button" class="btn btn-tool" disabled="true"
                                                                data-card-widget="collapse" id="collapse-produk">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover table-bordered table-striped"
                                                                id="produktable" style="text-align:center; width:100%;">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="5%">No</th>
                                                                        <th width="50%">Nama Produk</th>
                                                                        <th width="20%">Jumlah</th>
                                                                        <th width="20%">No Seri</th>
                                                                        <th hidden="true">Array</th>
                                                                        <th width="5%">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td><select name="produk_id[0]" id="produk_id0" class="form-control custom-select produk_id  @error('produk_id') is-invalid @enderror"></select></td>
                                                                        <td>
                                                                            <input type="number" class="form-control jumlah_produk" name="jumlah_produk[0]" id="jumlah_produk0"/>
                                                                            <small class="text-danger" id="msg_jumlah_produk"></small>
                                                                        </td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-sm btn-outline btn-info btn_seri" id="btn_seri" disabled="true" name="btn_seri[0]"><i class="fas fa-plus"></i> Tambah</button>
                                                                            {{-- <select name="no_seri_select[0][]" id="no_seri_select0" class="form-control custom-select no_seri @error('no_seri') is-invalid @enderror" multiple="true"></select> --}}
                                                                        </td>
                                                                        <td hidden="true"><input type="text" name="no_seri_select[0]" id="no_seri_select0" class="form-control no_seri_select"></td>
                                                                        <td><a href="#" id="tambah_paket_produk"><i class="fa fa-plus text-success"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="card_part"
                                                    class="card card-outline card-success col mx-2 collapsed-card">
                                                    <div class="card-header">
                                                        <div
                                                            class="form-check form-check-inline col-form-label card-title">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="pilih_jenis_barang[]" id="pilih_jenis_barang2"
                                                                value="part" />
                                                            <h6 class="form-check-label" for="pilih_jenis_barang2">Part
                                                            </h6>
                                                        </div>
                                                        <div class="card-tools col-form-label">
                                                            <button type="button" class="btn btn-tool" disabled="true"
                                                                data-card-widget="collapse" id="collapse-part">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive" id="part_table_input">
                                                            <table class="table table-hover table-bordered table-striped"
                                                                id="parttable" style="text-align:center;">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="7%">No</th>
                                                                        <th width="62%">Nama Part</th>
                                                                        <th width="24%">Jumlah</th>
                                                                        <th width="7%">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td><select name="part_id[0]" id="part_id0"
                                                                                class="form-control custom-select part_id  @error('part_id') is-invalid @enderror"></select>
                                                                        </td>
                                                                        <td><input type="number"
                                                                                class="form-control part_jumlah"
                                                                                name="part_jumlah[0]" id="part_jumlah0" />
                                                                        </td>
                                                                        <td><a href="#" id="tambah_part"><i
                                                                                    class="fas fa-plus text-success"></i></a>
                                                                        </td>
                                                                    </tr>
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
                            <div class="card-footer">
                                <a href="{{ route('as.retur.show') }}" type="button" class="btn btn-danger">Batal</a>
                                <button type="submit" class="btn btn-info float-right" id="btnsubmit"
                                    disabled="true">Tambah</button>
                            </div>
                        </form>

                        <div class="modal fade" id="detail_modal" role="dialog" aria-labelledby="detail_modal" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content" style="margin: 10px">
                                    <div class="modal-header">
                                        <h4 class="modal-title">No Seri</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="detail">
                                        <div class="row">
                                            <div class="col-12">
                                                <input type="hidden" class="form-control index_table" id="index_table" name="index_table">
                                                <div class="table-responsive">
                                                    <table id="seri_table" class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>No Seri</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><select class="form-control no_seri" name="no_seri[]" id="no_seri0"></select></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                        <button type="button" class="btn btn-info float-right" id="btntambahseri">Tambah</button>
                                    </div>
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
        $(function() {
            var access_token = localStorage.getItem('lokal_token');

            if (access_token == null) {
                Swal.fire({
                    title: 'Session Expired',
                    text: 'Silahkan login kembali',
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.preventDefault();
                        document.getElementById('logout-form').submit();
                    }
                })
            }

            var inputjumpart = false;
            var inputno_seri = false;

            var inputpart = false;
            var inputproduk = false;

            $('#no_transaksi_ref').select2();

            function validasi() {
                var jenis_array = [];

                $('input[name="pilih_jenis_barang[]"]:checked').each(function() {
                    jenis_array.push($(this).val());
                });

                if ($.inArray("produk", jenis_array) !== -1) {
                    $('#produktable').find('.produk_id').each(function() {
                        if ($(this).val() != null) {
                            inputproduk = true;
                        } else {
                            inputproduk = false;
                            return false;
                        }
                    });

                    $('#produktable').find('.no_seri_select').each(function() {
                        if ($(this).val() != "") {
                            inputno_seri = true;
                        } else {
                            inputno_seri = false;
                            return false;
                        }
                    });
                } else if ($.inArray("produk", jenis_array) === -1) {
                    inputproduk = true;
                    inputno_seri = true;
                }

                if ($.inArray("part", jenis_array) !== -1) {

                    $('#parttable').find('.part_id').each(function() {
                        if ($(this).val() != null) {
                            inputpart = true;
                        } else {
                            inputpart = false;
                            return false;
                        }
                    });

                    $('#parttable').find('.part_jumlah').each(function() {
                        if ($(this).val() != "") {
                            inputjumpart = true;
                        } else {
                            inputjumpart = false;
                            return false;
                        }
                    });

                } else if ($.inArray("part", jenis_array) === -1) {
                    inputpart = true;
                    inputjumpart = true;
                }

                if ($('#tgl_retur').val() != "" && $(
                        'input[name="pilih_jenis_retur"]:checked').length > 0 && $('#customer_nama').val() != "" && $(
                        '#alamat').val() && $('input[name="pilih_jenis_barang[]"]:checked').length > 0 &&
                    inputproduk == true && inputno_seri == true && inputpart == true && inputjumpart == true) {
                    $('#btnsubmit').attr('disabled', false);
                } else {
                    $('#btnsubmit').attr('disabled', true);
                }
            }

            $(document).on('change keyup', '#tgl_retur', function() {
                validasi();
            })

            $(document).on('change', 'input[name="pilih_jenis_retur"]', function() {
                $('#no_transaksi').val("");
                $('#customer_nama').val("");
                $('#customer_id').val("");
                $('#alamat').val("");
                $('#telepon').val("");
                $('.produk_id').empty();
                $('.part_id').empty();
                $('.part_jumlah').val('');
                $('.no_seri_select').val('');
                $('.btn_seri').attr('disabled', true);
                $('.jumlah_produk').val('');
                $('.btn_seri').removeClass('btn-warning');
                $('.btn_seri').addClass('btn-info');
                $('.btn_seri').html('<i class="fas fa-plus"></i> Tambah');
                var value = $('input[name="pilih_jenis_retur"]:checked').val();
                if (value == "peminjaman") {
                    $('#label_cust').text('Nama Peminjam');
                    $('#title_info_cust').text('Info Peminjaman');
                } else {
                    $('#label_cust').text('Nama Customer');
                    $('#title_info_cust').text('Info Penjualan');
                }
                validasi();
            })

            $(document).on('change keyup', '#alamat', function() {
                validasi();
            })

            function get_jenis_trans(){
                if($('#no_transaksi_ref').val() == "no_retur" || $('#no_transaksi_ref').val() == "sj_retur"){
                    return "retur";
                }else{
                    return "jual";
                }
            }
            $(document).on('change', '#no_transaksi_ref', function() {
                $('#no_transaksi').val("");
                $('#pesanan_id').val("");
                $('#customer_id').val("");
                $('#customer_nama').val("");
                $('#alamat').val("");
                $('#telepon').val("");
                $('.produk_id').empty();
                $('.part_id').empty();
                $('.part_jumlah').val('');
                $('.no_seri_select').val('');
                $('.btn_seri').attr('disabled', true);
                $('.jumlah_produk').val('');
                $('.btn_seri').removeClass('btn-warning');
                $('.btn_seri').addClass('btn-info');
                $('.btn_seri').html('<i class="fas fa-plus"></i> Tambah');

                produk($('.produk_id'));
            })

            function trproduktable() {
                var produktr = $('#produktable > tbody > tr').length;
                var data = `<tr>
                    <td>1</td>
                    <td><select name="produk_id[0]" id="produk_id0" class="form-control custom-select produk_id  @error('produk_id') is-invalid @enderror"></select></td>
                    <td><input type="number" class="form-control jumlah_produk" name="jumlah_produk[0]" id="jumlah_produk0"/>
                        <small class="text-danger" id="msg_jumlah_produk"></small></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline btn-info btn_seri" id="btn_seri" disabled="true" name="btn_seri[0]"><i class="fas fa-plus"></i> Tambah</button>
                    </td>
                    <td hidden="true"><input type="text" name="no_seri_select[0]" id="no_seri_select0" class="form-control no_seri_select"></td>
                    <td>`;
                if (produktr > 0) {
                    data += `<a id="remove_paket_produk"><i class="fas fa-minus" style="color: red"></i></a>`;
                } else {
                    data += `<a href="#" id="tambah_paket_produk"><i class="fa-solid fa-plus text-success"></i></a>`;
                }
                data += `</td>
                </tr>`;

                return data;
            }

            function trparttable() {
                var parttr = $('#parttable > tbody > tr').length;
                var data = `<tr>
                    <td>1</td>
                    <td><select name="part_id[0]" id="part_id0" class="form-control custom-select part_id  @error('part_id') is-invalid @enderror"></select></td>
                    <td><input type="number" class="form-control part_jumlah" name="part_jumlah[0]" id="part_jumlah0"/></td>
                    <td>`;
                if (parttr > 0) {
                    data += `<a id="remove_part"><i class="fas fa-minus" style="color: red"></i></a>`;
                } else {
                    data += `<a href="#" id="tambah_part"><i class="fas fa-plus text-success"></i></a>`;
                }
                data += `</td>
                </tr>`;

                return data;
            }

            function trseriproduk(i, obj) {
                var seritr = ``;
                if(obj != undefined){
                    var idx = 0;
                    obj.forEach(object => {
                        seritr += `<tr><td><select class="form-control no_seri" name="no_seri[`+idx+`]" id="no_seri`+idx+`">
                             <option value="`+object['id']+`" selected="true">`+object['text']+`</option>
                        </select></td></tr>`;
                        idx++;
                    });
                }else{
                    for(var j=0; j < i; j++){
                        seritr += `<tr><td><select class="form-control no_seri" name="no_seri[`+j+`]" id="no_seri`+j+`"></select></td></tr>`;
                    }
                }

                return seritr;
            }

            $('.no_ref_penjualan').select2();
            $('.paket_produk_id').select2();

            function produk(table) {
                $('.no_seri').select2();
                var pesanan_id = $('#pesanan_id').val();
                if(pesanan_id != ""){
                    $('#produktable .no_seri_input').addClass('hide');
                    produk_penjualan_tersedia(pesanan_id, table);
                    part_tersedia(pesanan_id);
                } else {
                    $('#produktable .no_seri_input').removeClass('hide');
                    produk_penjualan_tidak_tersedia(table);
                    part_tidak_tersedia();
                }

            }

            $('.divisi_id').select2({
                placeholder: "Pilih Divisi",
                width: 'auto',
		        dropdownAutoWidth: true,
                allowClear: true,
                ajax: {
                    minimumResultsForSearch: 20,
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/gbj/sel-divisi',
                    beforeSend : function(xhr){
                        xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(obj) {
                                return {
                                    id: obj.id,
                                    text: obj.nama,
                                };
                            })
                        };
                    },
                }
            });

            function no_seri_arr(no_seri){
                $('.no_seri').select2({
                    placeholder: "Pilih No Seri",
                    allowClear: true,
                    data: no_seri
                });
            }

            function no_seri_lama(){
                $('.no_seri').select2({
                    placeholder: "Pilih No Seri",
                    language: {
                        inputTooShort: function (args) {
                            return "Minimal 10 karakter atau lebih";
                        },
                        noResults: function () {
                            return "Not Found.";
                        },
                        searching: function () {
                            return "Searching...";
                        }
                    },
                    minimumInputLength: 10,
                    ajax: {
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'POST',
                        url: '/api/as/list/no_seri_lama',
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(obj) {
                                    return {
                                        id: obj.noseri,
                                        text: obj.noseri
                                    };
                                })
                            };
                        },
                    }
                })
            }

            $(document).on('change', '#produktable .produk_id', function() {
                var btn_seri = $(this).closest('tr').find('.btn_seri');
                var jumlah_produk = $(this).closest('tr').find('.jumlah_produk');
                var no_seri_select = $(this).closest('tr').find('.no_seri_select');
                var msg = $(this).closest('tr').find('#msg_jumlah_produk');
                if(no_seri_select != ""){
                    no_seri_select.val('');
                    btn_seri.removeClass('btn-warning');
                    btn_seri.addClass('btn-info');
                    btn_seri.html('<i class="fas fa-plus"></i> Tambah');
                }

                if($(this).val() != ""){
                    if(jumlah_produk.val() != ""){
                        if($(this).select2('data')[0]['noseri'] != undefined){
                            var seri_prd = Object.keys($(this).select2('data')[0]['noseri']).length;
                            if(seri_prd >= jumlah_produk.val()){
                                btn_seri.attr('disabled', false);
                                msg.html('');
                            }else{
                                btn_seri.attr('disabled', true);
                                msg.html('Jumlah No Seri hanya ada '+seri_prd);
                            }
                        }else{
                            btn_seri.attr('disabled', false);
                        }
                    }
                }
                else{
                    btn_seri.attr('disabled', true);
                }
                validasi();
            });

            $(document).on('keyup change', '#produktable .jumlah_produk', function() {
                var btn_seri = $(this).closest('tr').find('.btn_seri');
                var produk_id = $(this).closest('tr').find('.produk_id');
                var no_seri_select = $(this).closest('tr').find('.no_seri_select');
                var msg = $(this).closest('tr').find('#msg_jumlah_produk');

                if(no_seri_select.val() != ""){
                    no_seri_select.val('');
                    btn_seri.removeClass('btn-warning');
                    btn_seri.addClass('btn-info');
                    btn_seri.html('<i class="fas fa-plus"></i> Tambah');
                }

                if($(this).val() != ""){
                    if(produk_id.val() != ""){
                        if(produk_id.select2('data')[0]['noseri'] != undefined){
                            var seri_prd = Object.keys(produk_id.select2('data')[0]['noseri']).length;
                            if(seri_prd >= $(this).val()){
                                btn_seri.attr('disabled', false);
                                msg.html('');
                            }else{
                                btn_seri.attr('disabled', true);
                                msg.html('Jumlah No Seri hanya ada '+seri_prd);
                            }
                        }else{
                            btn_seri.attr('disabled', false);
                        }
                    }
                }
                else{
                    btn_seri.attr('disabled', true);
                }
                validasi();
            })

            $(document).on('click', '#produktable .btn_seri', function() {
                var produk_id = $(this).closest('tr').find('.produk_id');
                var jumlah = $(this).closest('tr').find('.jumlah_produk');
                var no_seri_select = $(this).closest('tr').find('.no_seri_select');
                var number = $(this).closest('tr').find("td:eq(0)").html() - 1;
                $('#detail_modal').modal("show");
                if(no_seri_select.val() != ""){
                    var obj = JSON.parse(no_seri_select.val());
                    $('#seri_table tbody').empty();
                    $('#seri_table tbody').append(trseriproduk(jumlah.val(), obj));

                    $('#index_table').val(number);
                    if(produk_id.select2('data')[0]['noseri'] != undefined){
                        no_seri_arr(produk_id.select2('data')[0]['noseri']);
                    }
                    else{
                        no_seri_lama();
                    }
                }else{
                    $('#seri_table tbody').empty();
                    $('#seri_table tbody').append(trseriproduk(jumlah.val(), undefined));

                    $('#index_table').val(number);
                    if(produk_id.select2('data')[0]['noseri'] != undefined){
                        no_seri_arr(produk_id.select2('data')[0]['noseri']);
                    }
                    else{
                        no_seri_lama();
                    }
                }
                validasi();
            })

            $(document).on('click', '#btntambahseri', function() {
                var noseri_arr = [];
                var idx = $('#index_table').val();
                var inputseri = false;
                $('#seri_table').find('.no_seri').each(function() {
                    if($(this).val() != null){
                        inputseri = true;
                        var obj = {};
                        obj.id = $(this).val();
                        obj.text = $(this).select2('data')[0]['text'];
                        noseri_arr.push(obj);
                    }else{
                        inputseri = false;
                        return false;
                    }
                });

                if(inputseri == true){
                    $('#detail_modal').modal("hide");
                    $('#no_seri_select'+idx).val( JSON.stringify(noseri_arr));
                    console.log($('#no_seri_select'+idx).val());

                    $('#produktable').find('button[name="btn_seri['+idx+']"]').removeClass('btn-info');
                    $('#produktable').find('button[name="btn_seri['+idx+']"]').addClass('btn-warning');
                    $('#produktable').find('button[name="btn_seri['+idx+']"]').html('<i class="fas fa-pencil-alt"></i> Edit');
                }
                else{
                    swal.fire(
                        'Form Kosong',
                        'Ada Form yang belum diinput, Silahkan input ulang',
                        'error'
                    );
                }
                validasi();
            })

            $(document).on('change', '#parttable .part_id', function() {
                if(typeof($(this).select2('data')[0]['jumlah']) != 'undefined'){
                    $(this).closest('tr').find('.part_jumlah').val($(this).select2('data')[0]['jumlah'] );
                }
                validasi();
            });

            $(document).on('change', '#parttable .part_id', function() {
                validasi();
            });

            $(document).on('change keyup', '#parttable .part_jumlah', function() {
                validasi();
            });

            $('.no_seri').select2();
            produk($('.produk_id'));

            $('input[type="radio"][name="ref_transaksi"]').on('change', function() {
                format_informasi_ref_penjualan();
                var value = $(this).val();
                $('input[name="pilih_ref_penjualan"]').prop('checked', false);
                $('.no_ref_penjualan').empty();

                $('.paket_produk_id').empty();
                $('.produk_id').empty();
                $('.no_seri').empty();
                $('.no_seri_select').val('');
                $('.btn_seri').attr('disabled', true);
                $('.jumlah_produk').val('');

                if (value == "tidak_tersedia") {
                    $('#informasi_transaksi').addClass('hide');

                    $('#no_ref_tidak_tersedia_input').removeClass('hide');
                    $('#customer_tidak_tersedia_input').removeClass('hide');
                    $('#alamat_tidak_tersedia_input').removeClass('hide');

                    $('#pilih_ref_penjualan_input').addClass('hide');
                    $('#no_ref_penjualan_input').addClass('hide');

                    $('#produktable tr').find('.no_seri_input').removeClass('hide');
                    $('#produktable tr').find('.no_seri').next(".select2-container").hide();
                    produk_penjualan_tidak_tersedia($('.produk_id'));
                } else if (value == "tersedia") {
                    $('#informasi_transaksi').removeClass('hide');

                    $('#no_ref_tidak_tersedia_input').addClass('hide');
                    $('#customer_tidak_tersedia_input').addClass('hide');
                    $('#alamat_tidak_tersedia_input').addClass('hide');

                    $('#pilih_ref_penjualan_input').removeClass('hide');
                    $('#no_ref_penjualan_input').removeClass('hide');

                    $('#produktable tr').find('.no_seri_input').addClass('hide');
                    $('#produktable tr').find('.no_seri').next(".select2-container").show();
                }
            });

            $('input[name="pilih_ref_penjualan"]').on('change', function() {
                $('.no_ref_penjualan').empty();
                format_informasi_ref_penjualan();
                no_ref_penjualan($(this).val());
            });

            $(document).on('change', 'input[name="pilih_jenis_barang[]"]', function() {
                var jenis_arry = [];
                var x = $(this).val();

                $('input[name="pilih_jenis_barang[]"]:checked').each(function() {
                    jenis_arry.push($(this).val());
                });

                if ($('input[name="pilih_jenis_barang[]"]:checkbox:checked').length == 0) {
                    jenis_arry.push(x);
                    $('input[name="pilih_jenis_barang[]"][value="' + x + '"]').prop("checked", true);
                }
                filter_jenis(jenis_arry);
                produk($('.produk_id'));
                validasi();
            });

            function filter_jenis(x) {
                if ($.inArray("produk", x) !== -1) {
                    $("#card_produk").removeClass("collapsed-card");
                    $("#collapse-produk").attr('disabled', false);
                } else {
                    $('#produktable tbody').empty();
                    $('#produktable tbody').append(trproduktable());
                    numberRowsProduk($("#produktable"));
                    $("#card_produk").addClass("collapsed-card");
                    $("#collapse-produk").attr('disabled', true);
                }

                if ($.inArray("part", x) !== -1) {
                    $("#card_part").removeClass("collapsed-card");
                    $("#collapse-part").attr('disabled', false);
                } else {
                    $('#parttable tbody').empty();
                    $('#parttable tbody').append(trparttable());
                    numberRowsPart($('#parttable'));
                    $("#card_part").addClass("collapsed-card");
                    $("#collapse-part").attr('disabled', true);
                }
            }

            function numberRowsProduk($t) {
                var c = 0 - 1;
                var referensi = $('input[name="ref_transaksi"]:checked').val();
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;

                    $(el).find('.produk_id').attr('name', 'produk_id[' + j + ']');
                    $(el).find('.produk_id').attr('id', 'produk_id' + j);

                    $(el).find('.produk_jumlah').attr('name', 'produk_jumlah[' + j + ']');
                    $(el).find('.produk_jumlah').attr('id', 'produk_jumlah' + j);

                    $(el).find('.no_seri_select').attr('name', 'no_seri_select[' + j + ']');
                    $(el).find('.no_seri_select').attr('id', 'no_seri_select' + j);

                    $(el).find('.btn_seri').attr('name', 'btn_seri['+j+']');

                    if($(el).find('.produk_id').val() == null){
                        if($('#pesanan_id').val() != ''){
                            produk_penjualan_tersedia($('#pesanan_id').val(), $(el).find('.produk_id'));
                        }else{
                            produk_penjualan_tidak_tersedia($(el).find('.produk_id'));
                        }
                    }
                });
            }

            $(document).on('click', '#produktable #tambah_paket_produk', function(e) {
                e.preventDefault();
                $('#produktable tr:last').after(trproduktable());
                numberRowsProduk($("#produktable"));
                validasi();
            });

            $('#produktable').on('click', '#remove_paket_produk', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
                numberRowsProduk($("#produktable"));
                validasi();
            });

            function numberRowsPart($t) {
                var c = 0 - 1;
                var referensi = $('input[name="ref_transaksi"]:checked').val();
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;
                    $(el).find('.part_id').attr('name', 'part_id[' + j + ']');
                    $(el).find('.part_id').attr('id', 'part_id' + j);

                    $(el).find('.part_jumlah').attr('name', 'part_jumlah[' + j + ']');
                    $(el).find('.part_jumlah').attr('id', 'part_jumlah' + j);

                    if($(el).find('.part_id').val() == null){
                        if($('#pesanan_id').val() != ''){
                            part_tersedia($('#pesanan_id').val());
                        }else{
                            part_tidak_tersedia();
                        }
                    }
                });
            }

            $(document).on('click', '#parttable #tambah_part', function(e) {
                e.preventDefault();
                $('#parttable tr:last').after(trparttable());
                numberRowsPart($("#parttable"));
                validasi();
            });

            $('#parttable').on('click', '#remove_part', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
                numberRowsPart($("#parttable"));
                validasi();
            });

            function no_ref_penjualan(jenis) {
                $('.no_ref_penjualan').select2({
                    ajax: {
                        minimumResultsForSearch: 20,
                        placeholder: "Pilih Produk",
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/as/list/so_selesai/' + jenis,
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
                });
            }

            function produk_penjualan(id) {
                $('.paket_produk_id').select2({
                    ajax: {
                        minimumResultsForSearch: 20,
                        placeholder: "Pilih Paket Produk",
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/as/list/so_selesai_paket/' + id,
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
                                        text: obj.penjualan_produk.nama
                                    };
                                })
                            };
                        },
                    }
                })
            }

            function format_informasi_ref_penjualan() {
                $('#nama_customer').text("-");
                $('#alamat_customer').text("-");
                $('#telp_customer').text("-");
                $('#provinsi_customer').text("-");

                $('#no_so').text("-");
                $('#no_po').text("-");
                $('#tgl_po').text("-");
                $('#no_paket').text("-");
                $('#no_do').text("-");
                $('#tgl_do').text("-");
            }

            function informasi_ref_penjualan(id) {
                var jenis = get_jenis_trans();
                $.ajax({
                    type: "GET",
                    url: '/api/as/detail/so_retur/' + id + '/' + jenis,
                    dataType: 'json',
                    success: function(data) {
                        $('#nama_customer').text(data.customer.nama);
                        $('#alamat_customer').text(data.customer.alamat);
                        $('#provinsi_customer').text(data.customer.provinsi.nama);

                        $('#no_so').text(data.pesanan.so);

                        $('#no_po').text(data.pesanan.no_po);
                        $('#tgl_po').text(data.pesanan.tgl_po);
                        if (data.no_paket != undefined) {
                            $('#no_paket').text(data.no_paket);
                        } else {
                            $('#no_paket').text("-");
                        }

                        if (data.customer.telp != null) {
                            $('#telp_customer').text(data.customer.telp);
                        } else {
                            $('#telp_customer').text("-");
                        }

                        if (data.no_do != null) {
                            $('#no_do').text(data.no_do);
                        } else {
                            $('#no_do').text("-");
                        }

                        if (data.tgl_do != null) {
                            $('#tgl_do').text(data.tgl_do);
                        } else {
                            $('#tgl_do').text("-");
                        }
                    },
                    error: function(data) {
                        alert('Error occured');
                    }
                });
            }

            function produk_penjualan_tersedia(id, table) {
                var jenis = get_jenis_trans();
                table.select2({
                    placeholder: "Pilih Paket Produk",
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/as/list/so_selesai_paket/' + id + '/' + jenis,
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data.produk, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nama,
                                        noseri: obj.no_seri
                                    };
                                })
                            };
                        },
                    }
                }).trigger('change');
            }

            function part_tersedia(id){
                var jenis = get_jenis_trans();
                $('.part_id').select2({
                    placeholder: "Pilih Part",
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/as/list/so_selesai_paket/' + id + '/' + jenis,
                        data: function(params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function(data) {
                            return {
                                results: $.map(data.part, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nama,
                                        jumlah: obj.jumlah
                                    };
                                })
                            };
                        },
                    }
                })
            }

            function produk_penjualan_tidak_tersedia(table) {
                var prm;
                table.select2({
                    placeholder: "Pilih Produk",
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/produk/variasi/',
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
                }).trigger('change');

            }

            function part_tidak_tersedia(){
                $('.part_id').select2({
                    placeholder: "Pilih Part",
                    minimumResultsForSearch: 2,
                    ajax: {
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'POST',
                        url: '/api/gk/sel-m-spare',
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
                });
            }
            $("#customer_nama").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        dataType: 'json',
                        url: '/api/customer/select',
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.nama,
                                    value: el.id
                                };
                            });
                            response(transformed.slice(0, 10));
                        },
                        error: function() {
                            response([]);
                        }
                    });
                },
                focus: function(event, ui) {
                    $(this).val(ui.item.label);
                    return false;
                },
                select: function(event, ui) {
                    var id = ui.item.value;
                    $(this).val(ui.item.label);

                    if (id != "") {
                        $('#customer_id').val(id);
                        $.ajax({
                            url: '/api/customer/select/' + id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {

                                $('#alamat').val(data[0].alamat);
                                $('#telepon').val(data[0].telepon);

                                $('#alamat').attr('readonly', true);
                                $('#telepon').attr('readonly', true);
                            }
                        });
                    }
                    validasi();
                    return false;
                },
                change: function(event, ui){
                    if(ui.item == null){
                        $('#customer_id').val('');
                        $('#alamat').val("");
                        $('#telepon').val("");

                        $('#alamat').attr('readonly', false);
                        $('#telepon').attr('readonly', false);
                        validasi();
                    }
                }
            });

            $("#no_transaksi").autocomplete({
                source: function(request, response) {
                    var jenis = $('#no_transaksi_ref').val();
                    $.ajax({
                        dataType: 'json',
                        type: 'GET',
                        url: '/api/as/list/so_selesai/' + jenis,
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.nama,
                                    value: el.id
                                };
                            });
                            response(transformed.slice(0, 10));
                        },
                        error: function() {
                            response([]);
                        }
                    });
                },
                focus: function(event, ui) {
                    $(this).val(ui.item.label);
                    return false;
                },
                select: function(event, ui) {
                    var id = ui.item.value;
                    $('.no_seri_select').val('');
                    $('.btn_seri').attr('disabled', true);
                    $('.jumlah_produk').val('');
                    $('.btn_seri').removeClass('btn-warning');
                    $('.btn_seri').addClass('btn-info');
                    $('.btn_seri').html('<i class="fas fa-plus"></i> Tambah');
                    $('.no_seri').empty();
                    $('.produk_id').empty();
                    if (id != "") {
                        var jenis = get_jenis_trans();
                        $('#pesanan_id').val(id);
                        produk_penjualan_tersedia(id, $('.produk_id'));
                        part_tersedia(id);
                        $.ajax({
                            url: '/api/as/detail/so_retur/' + id +'/'+jenis,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('#customer_nama').val(data.customer.nama);
                                $('#customer_id').val(data.customer.id);
                                $('#alamat').val(data.customer.alamat);
                                $('#telepon').val(data.customer.telepon);
                                $('#alamat').attr('readonly', true);
                                $('#telepon').attr('readonly', true);
                            }
                        });
                    }
                    validasi();
                    return false;
                }, change: function (event, ui) {
                    if(ui.item == null){
                        $('.no_seri_select').val('');
                        $('.btn_seri').attr('disabled', true);
                        $('.jumlah_produk').val('');
                        $('.btn_seri').removeClass('btn-warning');
                        $('.btn_seri').addClass('btn-info');
                        $('.btn_seri').html('<i class="fas fa-plus"></i> Tambah');
                        $('.part_id').empty();
                        $('.part_jumlah').val("");
                        $('.no_seri').empty();
                        $('.produk_id').empty();
                        $('#pesanan_id').val('');
                        $('#customer_id').val("");
                        $('#customer_nama').val("");
                        $('#alamat').val("");
                        $('#telepon').val("");
                        $('#alamat').attr('readonly', false);
                        $('#telepon').attr('readonly', false);
                        produk_penjualan_tidak_tersedia($('.produk_id'));
                        part_tidak_tersedia();
                    }
                }
            });

            // $(document).on('keyup change', '.divisi_id', function(){
            //     $('#karyawan_id').val('');
            //     $('#pic_peminjaman').val('');
            //     var divisi_id = $(this).val();
            //     // get_karyawan(divisi_id);
            // });

            $(document).on('keyup change', '.no_seri', function(){
                validasi();
            })

                $("#pic_peminjaman").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            dataType: 'json',
                            type: 'GET',
                            url: '/api/karyawan_all',
                            data: {
                                term: request.term
                            },
                            beforeSend : function(xhr){
                                xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                            },
                            success: function(data) {
                                var transformed = $.map(data, function(el) {
                                    return {
                                        label: el.nama,
                                        value: el.id
                                    };
                                });
                                response(transformed.slice(0, 10));
                            },
                            error: function() {
                                response([]);
                            }
                        });
                    },
                    focus: function(event, ui) {
                        $(this).val(ui.item.label);
                        return false;
                    },
                    select: function(event, ui) {
                        var id = ui.item.value;
                        $(this).val(ui.item.label);

                        if (id != "") {
                            $('#karyawan_id').val(id);
                        }
                        return false;
                    },
                    change: function(event, ui) {
                        if(ui.item == null){
                            $('#karyawan_id').val('');
                        }
                        return false;
                    },
                });



        })
    </script>
@stop
