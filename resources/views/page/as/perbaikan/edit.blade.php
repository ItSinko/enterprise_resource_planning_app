@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Edit Perbaikan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('as.perbaikan.show') }}">Perbaikan</a></li>
                        <li class="breadcrumb-item active">Edit Perbaikan</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        #profileImage {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #ffbb00;
            font-size: 12px;
            color: #000000;
            text-align: center;
            line-height: 60px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

        .select2>.selection>.select2-selection--single {
            height: 100% !important;
        }

        .select2>.selection>.select2-selection>.select2-selection__rendered {
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
            <form method="POST" action="{{ route('as.perbaikan.update', ['id' => $id]) }}" id="formupdateperbaikan">
                @method('PUT')
                @csrf

                <div class="row">
                    <div class="col-lg-4 col-md-5 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-12">
                                    <div class="card card-outline card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Referensi Retur</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="no_retur">No Retur</label>
                                                <p class="text-muted mt-1" id="no_retur">
                                                    {{ $data->ReturPenjualan->no_retur }}</p>
                                            </div>
                                            <div class="form-group">
                                                <strong>Tanggal Retur</strong>
                                                <p class="text-muted mt-1" id="tgl_retur">
                                                    {{ $data->ReturPenjualan->tgl_retur }}</p>
                                            </div>
                                            <div class="form-group">
                                                <strong>Jenis Retur</strong>
                                                <p class="text-muted mt-1" id="jenis_retur">
                                                    {{ $data->ReturPenjualan->jenis }}</p>
                                            </div>
                                            <div class="form-group">
                                                <strong>No Referensi</strong>
                                                <p class="text-muted mt-1" id="no_pesanan">
                                                    @if ($data->ReturPenjualan->pesanan_id != null)
                                                        {{ $data->ReturPenjualan->Pesanan->no_po }}
                                                    @elseif($data->ReturPenjualan->retur_penjualan_id != null)
                                                        {{ $data->ReturPenjualan->ReturPenjualanChild->no_retur }}
                                                    @else
                                                        {{ $data->ReturPenjualan->no_pesanan }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="form-group">
                                                <strong>Alasan Retur</strong>
                                                <p class="text-muted mt-1" id="alasan_retur">
                                                    {{ $data->ReturPenjualan->keterangan }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title" id="title_info_cust">Informasi Customer</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body align-center">
                                            <div id="profileImage" class="center margin-all"></div>
                                            <div class="margin-all mt-2">
                                                <h5><b id="customer">{{ $data->ReturPenjualan->Customer->nama }}</b></h5>
                                            </div>
                                            <div class="margin-all text-muted"><i class="fas fa-phone"></i> <span
                                                    id="telp">{{ $data->ReturPenjualan->Customer->telp != null ? $data->ReturPenjualan->Customer->telp : '-' }}</span>
                                            </div>
                                            <div class="margin-all mt-2 text-muted">
                                                <span id="alamat">{{ $data->ReturPenjualan->Customer->alamat }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-12">
                        <div class="card" style="box-shadow:none;">
                            <div class="card-body">
                                <div class="form-horizontal">
                                    <div id="informasi_pelanggan" class="card card-outline card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Analisa Perbaikan</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row" id="no_perbaikan_input">
                                                <label for="no_perbaikan"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">No
                                                    Referensi</label>
                                                <div class="col-lg-3 col-md-8">
                                                    <input name="no_perbaikan" id="no_perbaikan"
                                                        class="form-control col-form-label no_perbaikan  @error('no_perbaikan') is-invalid @enderror"
                                                        value="{{ $data->no_perbaikan }}">
                                                    <div class="invalid-feedback" id="msgno_perbaikan"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="tgl_perbaikan_input">
                                                <label for="tgl_perbaikan"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Tanggal
                                                    Perbaikan</label>
                                                <div class="col-lg-3 col-md-6">
                                                    <input type="date" name="tgl_perbaikan" id="tgl_perbaikan"
                                                        class="form-control col-form-label tgl_perbaikan  @error('tgl_perbaikan') is-invalid @enderror"
                                                        value="{{ $data->tanggal }}">
                                                    <div class="invalid-feedback" id="msgtgl_perbaikan"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="operator_input">
                                                <label for="operator"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Operator</label>
                                                <div class="col-lg-4 col-md-8">
                                                    <select name="operator[]" id="operator"
                                                        class="operator form-control custom-select" multiple="true">
                                                        @foreach ($karyawan as $kar)
                                                            <option value="{{ $kar->id }}"
                                                                @if ($k->contains($kar->id)) selected @endif>
                                                                {{ $kar->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" id="msgoperator"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="keterangan_input">
                                                <label for="keterangan"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Keterangan
                                                    Perbaikan</label>
                                                <div class="col-lg-4 col-md-12">
                                                    <textarea name="keterangan" id="keterangan"
                                                        class="form-control col-form-label keterangan  @error('keterangan') is-invalid @enderror">{{ $data->keterangan }}</textarea>
                                                    <div class="invalid-feedback" id="msgketerangan"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="informasi_pelanggan" class="card card-outline card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title" id="title_info_cust">Informasi Produk</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="keterangan"
                                                    class="col-lg-5 col-md-12 col-form-label labelket">Produk</label>
                                                <div class="col-lg-4 col-md-12">
                                                    <select name="produk_id" id="produk_id"
                                                        class="form-control custom-select produk_id">
                                                        @foreach ($data->ReturPenjualan->TFProduksi->detail as $i)
                                                            <option value="{{ $i->gdg_brg_jadi_id }}"
                                                                @if ($i->gdg_brg_jadi_id == $data->gdg_barang_jadi_id) selected @endif>
                                                                {{ $i->produk->Produk->nama }} {{ $i->produk->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="table-responsive">
                                                    <table class="table table-hover align-center" id="noseritable">
                                                        <thead class="bg-secondary">
                                                            <tr>
                                                                <th width="10%">No</th>
                                                                <th width="40%">No Seri</th>
                                                                <th width="40%">Tindak Lanjut</th>
                                                                <th width="10%">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($noseri as $key => $i)
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td><input name="no_seri_id[{{ $key }}]"
                                                                            id="no_seri_id{{ $key }}"
                                                                            class="form-control no_seri_id d-none"
                                                                            value="{{ $i['id'] }}" />{{ $i['no_seri'] }}
                                                                    </td>
                                                                    <td>
                                                                        <select name="tindak_lanjut[{{ $key }}]"
                                                                            id="tindak_lanjut{{ $key }}"
                                                                            class=" form-control custom-select tindak_lanjut">
                                                                            <option value="{{ $i['tindak_lanjut'] }}"
                                                                                selected>{{ ucfirst($i['tindak_lanjut']) }}
                                                                            </option>
                                                                        </select>
                                                                    </td>
                                                                    <td><i class="fas fa-trash text-danger"
                                                                            id="removenoseri"></i></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="barang_penjualan" class="card card-outline card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Penggantian Part</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
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

                                                        @if (count($data->PartPenggantiPerbaikan) > 0)
                                                            @foreach ($data->PartPenggantiPerbaikan as $key => $i)
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td><select name="part_id[{{ $key }}]"
                                                                            id="part_id{{ $key }}"
                                                                            class="form-control custom-select part_id  @error('part_id') is-invalid @enderror">
                                                                            <option value="{{ $i->m_sparepart_id }}"
                                                                                selected>{{ $i->Sparepart->nama }}</option>
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="number"
                                                                            class="form-control part_jumlah"
                                                                            name="part_jumlah[{{ $key }}]"
                                                                            id="part_jumlah{{ $key }}"
                                                                            value="{{ $i->jumlah }}" />
                                                                    </td>
                                                                    <td>
                                                                        @if ($key <= 0)
                                                                            <a href="#" id="tambah_part">
                                                                                <i class="fas fa-plus text-success"></i>
                                                                            </a>
                                                                        @else
                                                                            <a id="remove_part">
                                                                                <i class="fas fa-minus"
                                                                                    style="color: red"></i>
                                                                            </a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td>1</td>
                                                                <td><select name="part_id[0]" id="part_id0"
                                                                        class="form-control custom-select part_id  @error('part_id') is-invalid @enderror"></select>
                                                                </td>
                                                                <td><input type="number" class="form-control part_jumlah"
                                                                        name="part_jumlah[0]" id="part_jumlah0" />
                                                                </td>
                                                                <td><a href="#" id="tambah_part"><i
                                                                            class="fas fa-plus text-success"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endif

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('as.perbaikan.show') }}" type="button"
                                    class="btn btn-danger">Batal</a>
                                <button type="submit" class="btn btn-warning float-right" id="btnsubmit">Simpan</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('adminlte_js')
    <script>
        $(function() {
            // var kar = "{{ $k }}";
            function reset_noseri_table() {
                $('#noseritable > tbody').empty();
                $('#noseritable > tbody').append(`<tr>
                <td colspan="4">Data Belum Tersedia</td>
            </tr>`);
            }

            $('#operator').select2({
                placeholder: 'Pilih Operator',
            });
            $('#produk_id').select2({
                placeholder: 'Pilih Produk',
                allowClear: true
            }).change(function() {
                if ($(this).val() != null) {
                    var id_retur = "{{ $data->retur_id }}";
                    $.ajax({
                        type: "POST",
                        url: '/api/as/produk_noseri_retur',
                        data: {
                            'produk_id': $(this).val(),
                            'retur_id': id_retur,
                            'id': '{{ $data->id }}'
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data != null) {
                                return_noseri(data)
                            }
                        },
                        error: function() {
                            alert('Error occured');
                        }
                    });
                } else {
                    reset_noseri_table();
                }
            });


            // select_produk();
            // reset_noseri_table();

            // function select_produk($data){
            //     $('#produk_id').empty();
            //     var arr = [];
            //     if($data != null){
            //         arr = $.map($data, function (obj) {
            //             return {
            //                         id: obj.id,
            //                         text: obj.nama_produk,
            //                         noseri: obj.noseri,
            //                     }
            //         });
            //     }
            //     $('#produk_id').prepend('<option selected=""></option>').select2({
            //         placeholder: 'Pilih Produk',
            //         data: arr
            //     });
            // }

            function numberRowsNoseri($t) {
                var c = 0 - 1;
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;
                    $(el).find('.no_seri_id').attr('name', 'no_seri_id[' + j + ']');
                    $(el).find('.no_seri_id').attr('id', 'no_seri_id' + j);

                    $(el).find('.tindak_lanjut').attr('name', 'tindak_lanjut[' + j + ']');
                    $(el).find('.tindak_lanjut').attr('id', 'tindak_lanjut' + j);

                    tindak_lanjut();
                });
            }

            function return_noseri(noseri) {
                $('#noseritable > tbody').empty();
                var data = "";
                for (var i = 0; i < noseri.length; i++) {
                    data += `<tr>
                    <td>` + (i + 1) + `</td>
                    <td><input name="no_seri_id[` + i + `]" id="no_seri_id` + i +
                        `" class="form-control no_seri_id d-none" value="` + noseri[i].id + `"/>` + noseri[i]
                        .noseri + `</td>
                    <td>
                        <select name="tindak_lanjut[` + i + `]" id="tindak_lanjut` + i + `" class=" form-control custom-select tindak_lanjut">
                        </select>
                    </td>
                    <td><i class="fas fa-trash text-danger" id="removenoseri"></i></td>
                </tr>`
                }
                $('#noseritable > tbody').append(data);
                tindak_lanjut();

            }
            tindak_lanjut();

            function tindak_lanjut() {
                $('.tindak_lanjut').select2({
                    placeholder: 'Pilih Tindak Lanjut',
                    data: [{
                            id: 'perbaikan',
                            text: 'Perbaikan'
                        },
                        {
                            id: 'karantina',
                            text: 'Karantina'
                        },
                        {
                            id: 'dibatalkan',
                            text: 'Dibatalkan'
                        }
                    ]
                });
            }
            cust_image('{{ $data->ReturPenjualan->Customer->nama }}');

            function cust_image(cust_name) {
                var cust = cust_name;
                var cust = cust.replace('.', '').replace('PT ', '').replace('CV ', '').replace('& ', '').replace(
                    '(',
                    '').replace(')', '');
                var init = cust.split(" ");
                var initial = "";
                for (var i = 0; i < init.length; i++) {
                    initial = initial + init[i].charAt(0);
                }
                var profileImage = $('#profileImage').text(initial);
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
                    // part();
                    select_part();
                });
            }
            select_part()

            function select_part() {
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

            $(document).on('click', '#parttable #tambah_part', function(e) {
                e.preventDefault();
                $('#parttable tr:last').after(trparttable());
                numberRowsPart($("#parttable"));

            });

            $('#noseritable').on('click', '#removenoseri', function(e) {
                e.preventDefault();
                if ($('#noseritable > tbody > tr').length > 1) {
                    $(this).closest('tr').remove();
                    numberRowsNoseri($("#noseritable"));

                }
            });

            $('#parttable').on('click', '#remove_part', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
                numberRowsPart($("#parttable"));

            });

            $(document).on('change keyup', '#no_perbaikan', function() {
                $.ajax({
                    type: "GET",
                    url: '/api/as/perbaikan/nomor',
                    data: {
                        'id': '{{ $id }}',
                        'nomor': $(this).val()
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data > 0) {
                            $('#no_perbaikan').addClass('is-invalid');
                            $('#msgno_perbaikan').text("No Referensi sudah terpakai");
                        } else {
                            $('#no_perbaikan').removeClass('is-invalid');
                            $('#msgno_perbaikan').text("");
                        }
                    },
                    error: function() {
                        alert('Error occured');
                    }
                });
            })
        })
    </script>
@stop
