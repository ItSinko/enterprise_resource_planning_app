@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Produk</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @if(Auth::user()->divisi_id == "26")
                <li class="breadcrumb-item"><a href="{{route('penjualan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Produk</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
<style>
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

    .borderless {
        border: 0 none;
    }

    .filter {
        margin: 10px;
    }

    .blue-bg {
        background-color: #dae6f0;
        color: #4682B4;
    }

    .yellow-bg {
        background-color: #ffe680;
        color: #997a00;
    }
</style>
@stop
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if(Session::has('error') || count($errors) > 0 )
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
                                            <a href="{{route('penjualan.produk.create')}}">
                                                <button class="btn btn-info">
                                                    <i class="fas fa-plus"></i> Tambah
                                                </button>
                                            </a>
                                        </span>
                                        <span class="float-right" style="margin-right: 5px">
                                            <button class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-filter"></i> Filter
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <form id="filter" class="px-4 py-3">
                                                    <div class="dropdown-header">
                                                        Kelompok Produk
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="dropdownkelompokproduk" value="1" name="produk" />
                                                            <label class="form-check-label" for="dropdownkelompokproduk">
                                                                Alat Kesehatan
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="dropdownkelompokproduk" value="2" name="produk" />
                                                            <label class="form-check-label" for="dropdownkelompokproduk">
                                                                Sarana Kesehatan
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="dropdownkelompokproduk" value="3" name="produk" />
                                                            <label class="form-check-label" for="dropdownkelompokproduk">
                                                                Aksesoris
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="dropdownkelompokproduk" value="4" name="produk" />
                                                            <label class="form-check-label" for="dropdownkelompokproduk">
                                                                Lain - lain
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-header">
                                                        Harga Minimum
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" style="width:200px;" class="form-control" id="harga_min" name="stok" value="0" />
                                                    </div>
                                                    <div class="dropdown-header">
                                                        Harga Maksimum
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" style="width:200px;" class="form-control" id="harga_maks" name="stok" value="0" disabled />
                                                    </div>

                                                    <button class="btn btn-primary float-right">
                                                        Cari
                                                    </button>
                                                </form>
                                            </div>
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
                                                        <th width="16%">No AKD</th>
                                                        <th width="12%">Merk</th>
                                                        <th width="50%">Nama Alias</th>
                                                        <th width="50%">Nama Produk</th>
                                                        <th width="12%">Harga</th>
                                                        <th width="5%">Aksi</th>
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
                <div class="modal fade" id="modaldetail" role="dialog" aria-labelledby="modaldetail" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header borderless blue-bg">
                                <h4 class="modal-title"><b>Detail</b></h4>
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
                </div>
                <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header yellow-bg">
                                <h4 class="modal-title"><b>Ubah</b></h4>
                            </div>
                            <div class="modal-body" id="edit">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="hapusmodal" role="dialog" aria-labelledby="hapusmodal" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header bg-danger">
                                <h4 class="modal-title"><b>Hapus</b></h4>
                            </div>
                            <div class="modal-body" id="hapus">
                                <div class="row">
                                    <div class="col-12">
                                        <form method="post" action="" id="form-hapus" data-target="">
                                            @method('delete')
                                            @csrf
                                            <div class="card">
                                                <div class="card-body">Apakah Anda yakin ingin menghapus data ini?</div>
                                                <div class="card-footer">
                                                    <span class="float-left">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    </span>
                                                    <span class="float-right">
                                                        <button type="submit" class="btn btn-danger " id="btnhapus">Hapus</button>
                                                    </span>
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
    </div>
</section>
@endsection

@section('adminlte_js')
<script>
    $(function() {
        $(document).on('submit', '#form-penjualan-produk-update', function(e) {
            console.log("tes");
            e.preventDefault();
            var action = $(this).attr('data-attr');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: action,
                data: $('#form-penjualan-produk-update').serialize(),
                success: function(response) {
                    if (response['data'] == "success") {
                        swal.fire(
                            'Berhasil',
                            'Berhasil melakukan ubah data Penjualan Produk',
                            'success'
                        );
                        $("#editmodal").modal('hide');
                        $('#showtable').DataTable().ajax.reload();
                    } else if (response['data'] == "error") {
                        swal.fire(
                            'Gagal',
                            'Gagal melakukan ubah data Penjualan Produk',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    alert($('#form-penjualan-produk-update').serialize());
                }
            });
            return false;
        });

        var showtable = $('#showtable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/penjualan_produk/data/kosong/kosong/kosong',
                "dataType": "json",
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'no_akd',
                    className: 'nowrap-text align-center',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'merk',
                    className: 'nowrap-text align-center',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'nama_alias',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'nama',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'harga',
                    className: 'nowrap-text align-right',
                    render: $.fn.dataTable.render.number(',', '.', 2)
                        // function(data) {
                        //     return '<span class="float-left">Rp. </span><span class="float-right">' + $.fn.dataTable.render.number(',', '.', 2) + '</span>';
                        // }
                        ,
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'button',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
                }
            ]
        });
        $('#showtable tbody').on('click', '#showmodal', function() {
            var rows = showtable.rows($(this).parents('tr')).data();
            $('#nama_produk').text(rows[0].nama);
            var x = (rows[0].harga).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
            $('#harga_produk').text('Rp ' + x);

            var showdetailtable = $('#showdetailtable').DataTable({
                processing: true,
                destroy: true,
                serverSide: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                ajax: {
                    'url': '/api/penjualan_produk/detail/' + rows[0].id,
                    "dataType": "json",
                    'type': 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                columns: [{
                        className: 'nowrap-text align-center',
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        className: 'nowrap-text',
                        data: 'nama'
                    },
                    {
                        className: 'nowrap-text align-center',
                        data: 'kelompok'

                    }, {
                        className: 'nowrap-text align-center',
                        data: 'jumlah'
                    }
                ],
            });
            $('#modaldetail').modal('show');
        });

        $(document).on('click', '.editmodal', function(event) {
            event.preventDefault();

            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            $.ajax({
                url: "/api/penjualan_produk/update_modal/" + id,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#editmodal').modal("show");
                    $('#edit').html(result).show();
                    console.log(result);
                    $("#editform").attr("action", href);
                    var x = 3;
                    for (i = 0; i < 10; i++) {
                        select_data(i);
                    }

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
        });
        $(document).on('click', '.hapusmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data("id");
            $('#hapusmodal').modal("show");
            $('#hapusmodal').find('form').attr('action', '/api/penjualan_produk/delete/' + id);
        });



        $(document).on('submit', '#form-hapus', function(e) {
            e.preventDefault();
            var action = $(this).attr('action');
            console.log(action);
            $.ajax({
                url: action,
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
                        $('#showtable').DataTable().ajax.reload();
                        $("#hapusmodal").modal('hide');
                    } else if (response['data'] == "error") {
                        swal.fire(
                            'Gagal',
                            'Gagal melakukan Penambahan Data Pengujian',
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
            return false;
        });

        function numberRows($t) {
            var c = 0 - 2;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('input[id="jumlah"]').attr('name', 'jumlah[' + j + ']');
                $(el).find('.produk_id').attr('name', 'produk_id[' + j + ']');
                $(el).find('.produk_id').attr('id', j);
                $(el).find('.kelompok_produk').attr('id', 'kelompok_produk' + j);
                select_data(j);
            });
        }
        $(document).on('click', '#addrow', function() {
            $('#createtable tr:last').after(`<tr>
            <td></td>
            <td>
                <div class="form-group">
                    <select class="select-info form-control  produk_id" name="produk_id[]" id="0" style="width:100%" >
                    </select>
                </div>
            </td>
            <td><span class="badge kelompok_produk" id="kelompok_produk0"></span></td>
            <td>
                <div class="form-group d-flex justify-content-center">
                    <input type="number" class="form-control" name="jumlah[]" id="jumlah" style="width: 50%" />
                </div>
            </td>
            <td>
                <a id="removerow"><i class="fas fa-minus" style="color: red"></i></a>
            </td>
            </tr>`);
            numberRows($("#createtable"));
        });

        $(document).on('click', '#createtable #removerow', function(e) {
            $(this).closest('tr').remove();
            numberRows($("#createtable"));
        });

        $(document).on('keyup change', '#harga', function() {
            var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $(this).val(result);
            console.log(result);

            if ($(this).val() != "") {
                $('#msgharga').text("");
                $('#harga').removeClass("is-invalid");
                console.log($("#createtable tbody").length);
                if (($('#nama_paket').val() != "" && !$('#nama_paket').hasClass('is-invalid')) && $("#createtable tbody").length > 0) {
                    $('#btnsimpan').removeClass('disabled');
                } else {
                    $('#btnsimpan').addClass('disabled');
                }
            } else if ($(this).val() == "") {
                $('#msgharga').text("Harga Harus diisi");
                $('#harga').addClass("is-invalid");
                $('#btnsimpan').addClass('disabled');
            }
        });

        function select_data(x) {
            $('#' + x).select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Produk",
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
                console.log(index);
                // var id = $(#produk_id).val();
                $.ajax({
                    url: '/api/produk/select/' + value,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $('#kelompok_produk' + index).text(data[0].kelompok_produk.nama);
                    }
                });
            });
        }
        $(document).on('keyup change', '#nama_paket', function() {
            var id = $('#form-penjualan-produk-update').attr('data-id');
            if ($(this).val() != "") {
                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    url: '/api/penjualan_produk/check/' + id + '/' + $(this).val(),
                    success: function(data) {
                        if (data.jumlah >= 1) {
                            $("#msgnama_paket").text("Nama sudah terpakai");
                            $('#nama_paket').addClass('is-invalid');
                            $('#btnsimpan').addClass('disabled');
                        } else {
                            $('#msgnama_paket').text("");
                            $('#nama_paket').removeClass("is-invalid");
                            console.log($("#createtable tbody").length);
                            if ($('#harga').val() != "" && $("#createtable tbody").length > 0) {
                                $('#btnsimpan').removeClass('disabled');
                            } else {
                                $('#btnsimpan').addClass('disabled');
                            }
                        }
                    }
                });
            } else if ($(this).val() == "") {
                $('#msgnama_paket').text("Nama Paket Harus diisi");
                $('#nama_paket').addClass("is-invalid");
                $('#btnsimpan').addClass('disabled');
            }
        });


        $('#harga_min').on('keyup change', function() {

            if ($(this).val().startsWith("0")) {
                $(this).val('0');
                $("#harga_maks").val('0');
            }

            var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $(this).val(result);
            if ($(this).val() == "") {
                $("#harga_maks").attr('disabled', true);
            } else if ($(this).val().startsWith("0")) {
                $("#harga_maks").attr('disabled', true);
            } else {
                $("#harga_maks").removeAttr('disabled');
            }

        });
        $('#harga_maks').on('keyup change', function() {

            if ($(this).val().startsWith("0")) {
                $(this).val('0');
            }
            var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $(this).val(result);

        });

        $('#filter').submit(function() {
            var produk = [];
            var harga_min = $('#harga_min').val();
            var harga_maks = $('#harga_maks').val();

            $("input[name=produk]:checked").each(function() {
                produk.push($(this).val());
            });

            if (produk != 0) {
                var x = produk;

            } else {
                var x = ['kosong']
            }

            if (harga_min != 0) {
                var y = harga_min.replace(/\./g, '')

            } else {
                var y = ['kosong']
            }
            if (harga_maks != 0) {
                var z = harga_maks.replace(/\./g, '');

            } else {
                var z = ['kosong']
            }
            console.log(y);
            console.log(z);
            console.log(x);

            $('#showtable').DataTable().ajax.url('/api/penjualan_produk/data/' + x + '/' + y + '/' + z + '').load();
            return false;
        });
    });
</script>
@endsection