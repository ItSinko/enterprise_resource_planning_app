@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Produk</h1>
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
                                                            <input type="checkbox" class="form-check-input" id="dropdownkelompokproduk" value="1" />
                                                            <label class="form-check-label" for="dropdownkelompokproduk">
                                                                Alat Kesehatan
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="dropdownkelompokproduk" value="2" />
                                                            <label class="form-check-label" for="dropdownkelompokproduk">
                                                                Sarana Kesehatan
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="dropdownkelompokproduk" value="3" />
                                                            <label class="form-check-label" for="dropdownkelompokproduk">
                                                                Aksesoris
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="dropdownkelompokproduk" value="4" />
                                                            <label class="form-check-label" for="dropdownkelompokproduk">
                                                                Lain - lain
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="dropdown-header">
                                                        Stok
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="dropdownstok" />
                                                            <label class="form-check-label" for="dropdownstok">
                                                                Tersedia
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="dropdownstok" />
                                                            <label class="form-check-label" for="dropdownstok">
                                                                Hampir Habis
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="dropdownstok" />
                                                            <label class="form-check-label" for="dropdownstok">
                                                                Habis
                                                            </label>
                                                        </div>
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
                                                        <th width="78%">Nama Produk</th>
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
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header yellow-bg">
                                <h4 class="modal-title"><b>Ubah</b></h4>
                            </div>
                            <div class="modal-body" id="edit">

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
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/penjualan_produk/data/' + 0,
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }

            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    className: 'nowrap-text align-center',
                    orderable: false,
                    searchable: false
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
                ajax: '/api/penjualan_produk/detail/' + rows[0].id,
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

            if ($(this).val() != "") {
                $('#msgharga').text("");
                $('#harga').removeClass("is-invalid");
                console.log($("#createtable tbody").length);
                if ($('#nama_paket').val() != "" && $("#createtable tbody").length > 0) {
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
        $('#filter').submit(function() {
            var values = [];
            $("input:checked").each(function() {
                values.push($(this).val());
            });
            if (values != 0) {
                var x = values;

            } else {
                var x = ['kosong']
            }
            console.log(x);
            $('#showtable').DataTable().ajax.url(' /api/penjualan_produk/data/' + x).load();
            return false;
        });
    });
</script>
@endsection