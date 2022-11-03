@extends('adminlte.page')

@section('title', 'ERP')

@section('adminlte_css')
    <style>
        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .select2 {
            width: 100% !important;
        }

        @media (min-width: 993px) {
            body {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }
        }

        @media (max-width: 992px) {
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }
        }

        .ui-widget-content.ui-autocomplete {
            width: 350px;
            max-height: 200px;
            overflow-y: scroll;
            overflow-x: hidden;
        }

        .ui-menu-item .ui-menu-item-wrapper.ui-state-active {
            background: #366aca !important;
            font-weight: bold !important;
            color: #ffffff !important;
        }
    </style>
@endsection

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Rencana Penjualan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '26' || Auth::user()->Karyawan->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.rencana.show') }}">Rencana Penjualan</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('content')
    <div class="container-fluid">
        <form action="/penjualan/rencana/store" method="post" id="form-rencana-penjualan-create">
            @csrf
            @method('PUT')
            <div class="content">
                {{-- <div class="row">
                <div class="col-12">
                @if (session('error') || count($errors) > 0)
                    <div class="form-group row">
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
                    </div>
                @endif
            </div> --}}
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-5 col-sm-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Distributor</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="customer-id">Distributor</label>
                                        <select class="form-control custom-select" name="customer_id" id="customer_id">
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="customer-id">Tahun</label>
                                        <input type="number" class="form-control" name="tahun" id="tahun"
                                            placeholder="Masukkan Tahun">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Instansi</label>
                                        <textarea class="form-control" name="nama_instansi" id="nama_instansi" placeholder="Masukkan Instansi"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Produk</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="customer-id">Nama Produk</label>
                                        <select class="form-control custom-select" name="produk_id" id="produk_id"
                                            disabled="true">
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="customer-id">Jumlah</label>
                                        <input type="number" class="form-control" name="produk_jumlah" id="produk_jumlah"
                                            disabled="true" placeholder="Masukkan Jumlah">
                                    </div>
                                    <div class="form-group">
                                        <label for="customer-id">Harga</label>
                                        <input type="text" class="form-control" name="produk_harga" id="produk_harga"
                                            disabled="true" placeholder="Masukkan Harga">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-outline-danger" id="btnresetproduk">Reset</button>
                                    <button type="button" class="btn btn-info float-right" id="btntambahproduk"
                                        disabled="true">Tambah</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-7 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Daftar Produk</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="tableproduk">
                                        <thead class="align-center">
                                            <tr>
                                                <th>No</th>
                                                <th>Instansi</th>
                                                <th>Produk</th>
                                                <th>Jumlah</th>
                                                <th>Harga</th>
                                                <th>Subtotal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="emptycol">
                                                <td colspan="7" class="align-center">Belum Ada Data</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" class="align-center">Total Harga</th>
                                                <th colspan="2" id="totalhargaprd">Rp. 0</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right" id="btnsubmit" disabled="true"> <i
                                    id="load" class=""></i> Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
    </div>
@endsection

@section('adminlte_js')
    <script>
        $(function() {

            $(document).on('submit', function(e) {
                $('#btnsubmit').attr('disabled', true);
                $('#load').addClass('fas fa-circle-notch fa-spin');
                e.preventDefault();
                var action = $('#form-rencana-penjualan-create').attr('action');
                console.log(action);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: action,
                    data: $('#form-rencana-penjualan-create').serialize(),
                    success: function(response) {
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil menambahkan Rencana Penjualan',
                                'success'
                            ).then(function() {
                                location.reload();

                            });
                            $('#tahun').val('');
                            $('#nama_instansi').val('');
                            $('#produk_jumlah').val('');
                            $('#produk_harga').val('');

                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal menambahkan Rencana Penjualan',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        alert($('#form-rencana-penjualan-create').serialize());
                    }
                });
                return false;
            });


            function formatmoney(bilangan) {
                var number_string = bilangan.toString(),
                    sisa = number_string.length % 3,
                    rupiah = number_string.substr(0, sisa),
                    ribuan = number_string.substr(sisa).match(/\d{3}/g);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return rupiah;
            }

            function replaceAll(string, search, replace) {
                return string.split(search).join(replace);
            }

            function numberRowsProduk($t) {
                var c = 0 - 1;
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;
                    $(el).find('.instansi').attr('name', 'instansi[' + j + ']');
                    $(el).find('.instansi').attr('id', 'instansi' + j);
                    $(el).find('.id_produk').attr('name', 'id_produk[' + j + ']');
                    $(el).find('.id_produk').attr('id', 'id_produk' + j);
                    $(el).find('.jumlah').attr('name', 'jumlah[' + j + ']');
                    $(el).find('.jumlah').attr('id', 'jumlah' + j);
                    $(el).find('.harga').attr('name', 'harga[' + j + ']');
                    $(el).find('.harga').attr('id', 'harga' + j);
                    $(el).find('.subtotal').attr('name', 'subtotal[' + j + ']');
                    $(el).find('.subtotal').attr('id', 'subtotal' + j);
                });
            }

            function totalhargaprd() {
                var totalharga = 0;
                $('#tableproduk').find('tr .subtotal').each(function() {
                    var subtotal = replaceAll($(this).val(), '.', '');
                    totalharga = parseInt(totalharga) + parseInt(subtotal);
                    $("#totalhargaprd").text("Rp. " + totalharga.toString().replace(
                        /(\d)(?=(\d{3})+(?!\d))/g, "$1."));
                })
            }

            // $('#tableproduk').DataTable();
            $('#customer_id').select2({
                placeholder: "Pilih Customer",
                ajax: {
                    theme: "bootstrap",
                    minimumResultsForSearch: 20,
                    delay: 250,
                    dataType: 'json',
                    type: 'GET',
                    url: '/api/customer/select/',
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

            $('#produk_id').select2({
                placeholder: "Pilih Produk",
                ajax: {
                    minimumResultsForSearch: 20,
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/penjualan_produk/select/',
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
                var id = $(this).val();
                $.ajax({
                    url: '/api/penjualan_produk/select/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        $('#produk_harga').val(formatmoney(res[0].harga));
                    }
                });
            });

            $('#tableproduk').on('click', '#removerow', function(e) {
                $(this).closest('tr').remove();
                numberRowsProduk($("#tableproduk"));
                totalhargaprd();
                if ($('#tableproduk > tbody > tr').length <= 0) {
                    $("#totalhargaprd").text("Rp. 0");
                    $('#btnsubmit').attr('disabled', true);
                    $('#tableproduk tbody').append(
                        `<tr id="emptycol"><td colspan="7" class="align-center">Belum Ada Data</td></tr>`
                        );
                }
            });

            $('#btntambahproduk').on('click', function() {
                $('#tableproduk').find('tr[id="emptycol"]').remove();
                var instansi = $('#nama_instansi').val();
                var id_produk = $('#produk_id').val();
                var nama_produk = $('#produk_id').find(':selected').text();
                var jumlah = $('#produk_jumlah').val();
                var harga = $('#produk_harga').val();
                $('#tableproduk tbody').append(`
                <tr>
                <td class="align-center"></td>
                <td><input type="hidden" class="instansi" name="instansi[]" id="instansi" value="` + instansi + `">` +
                    instansi + `</td>
                <td><input type="hidden" class="id_produk" name="id_produk[]" id="id_produk" value="` + id_produk +
                    `">` + nama_produk + `</td>
                <td><input type="hidden" class="jumlah" name="jumlah[]" id="jumlah" value="` + jumlah + `">` + jumlah + `</td>
                <td><input type="hidden" class="harga" name="harga[]" id="harga" value="` + replaceAll(harga, '.',
                    '') + `">` + harga + `</td>
                <td><input type="hidden" class="subtotal" name="subtotal[]" id="subtotal" value="` + (jumlah *
                        parseInt(replaceAll(harga, '.', ''))) + `">` + formatmoney(jumlah * parseInt(
                        replaceAll(harga, '.', ''))) + `</td>
                <td><a id="removerow"><i style="color:red;" class="fas fa-minus"></a></td>
                </tr>
            `);
                numberRowsProduk($("#tableproduk"));
                totalhargaprd();
            });

            $('#btnresetproduk').on('click', function() {
                $('#produk_id').val(null).trigger('change');
                $('#produk_jumlah').val("");
                $('#produk_harga').val("");
                $('#btntambahproduk').attr('disabled', true);
            });

            function validasiall() {
                if ($('#customer_id').val() != "" && $('#nama_instansi').val() != "" && $('#tahun').val() != "" &&
                    $('#produk_id').val() != "" && $('#produk_jumlah').val() != "" && $('#produk_harga').val() != ""
                    ) {
                    $('#btnsubmit').removeAttr('disabled');
                    $('#btntambahproduk').removeAttr('disabled');
                } else {
                    $('#btntambahproduk').attr('disabled', true);
                }
            }

            function validasicust() {
                if ($('#customer_id').val() != "" && $('#nama_instansi').val() != "" && $('#tahun').val() != "") {
                    $('#produk_id').removeAttr('disabled');
                    $('#produk_jumlah').removeAttr('disabled');
                    $('#produk_harga').removeAttr('disabled');
                } else {
                    $('#produk_id').attr('disabled', true);
                    $('#produk_jumlah').attr('disabled', true);
                    $('#produk_harga').attr('disabled', true);
                    $('#btntambahproduk').attr('disabled', true);
                    $('#btnsubmit').attr('disabled', true);
                }
            }

            $('#customer_id').on('keyup change', function() {
                validasiall();
                validasicust();
            });

            $('#nama_instansi').on('keyup change', function() {
                validasiall();
                validasicust();
            });

            $('#tahun').on('keyup change', function() {
                validasiall();
                validasicust();
            });

            $('#produk_id').on('keyup change', function() {
                validasiall();
            });

            $('#produk_jumlah').on('keyup change', function() {
                validasiall();
            });

            $('#produk_harga').on('keyup change', function() {
                var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(result);
                validasiall();
            });
            var today = new Date();
            var yyyy = today.getFullYear();
            $("#nama_instansi").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        dataType: 'json',
                        url: '/api/customer/get_instansi/' + $('#customer_id').val() + '/' +
                            yyyy,
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el,
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });

        })
    </script>
@stop
