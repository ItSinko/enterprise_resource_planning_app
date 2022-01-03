@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Sales Order</h1>
@stop

@section('adminlte_css')
<style>
    .ok {
        color: green;
        font-weight: 600;
    }

    .nok {
        color: #dc3545;
        font-weight: 600;
    }

    .warning {
        color: #FFC700;
        font-weight: 600;
    }

    .list-group-item {
        border: 0 none;
    }

    .align-right {
        text-align: right;
    }

    .align-center {
        text-align: center;
    }

    .margin {
        margin-bottom: 5px;
    }

    .filter {
        margin: 5px;
    }

    .hide {
        display: none !important;
    }

    .bgcolor {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .fa-search:hover {
        color: #ADD8E6;
    }

    .fa-search:active {
        color: #808080;
    }

    .nowrap-text {
        white-space: nowrap;
    }

    .removeboxshadow {
        box-shadow: none;
        border: 1px;
    }

    @media screen and (min-width: 1440px) {
        section {
            font-size: 14px;
        }

        .dropdown-item {
            font-size: 14px;
        }
    }

    @media screen and (max-width: 1439px) {
        section {
            font-size: 12px;
        }

        .dropdown-item {
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
                <div class="card">
                    <div class="card-body">
                        <h4>Info Penjualan</h4>
                        @if($value == "EKAT")
                        <div class="row">
                            <div class="col-5">
                                <div class="margin">
                                    <div><small class="text-muted">Distributor & Instansi</small></div>
                                </div>
                                <div class="margin">
                                    <b id="distributor">{{$data->customer->nama}}</b><small> (Distributor)</small>
                                </div>
                                <div class="margin">
                                    <div><b id="no_akn">{{$data->satuan}}</b></div>
                                    <small>({{$data->instansi}})</small>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="margin">
                                    <div><small class="text-muted">No AKN</small></div>
                                    <div><b id="no_akn">{{$data->no_paket}}</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">No SO</small></div>
                                    <div><b id="no_so">{{$data->pesanan->so}}</b></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="margin">
                                    <div><small class="text-muted">No PO</small></div>
                                    <div><b id="no_so">{{$data->pesanan->no_po}}</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Batas Pengiriman</small></div>
                                    <div class="urgent"><b></b></div>
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="margin">
                                    <div><small class="text-muted">Status</small></div>
                                    <div>{!!$status!!}</div>
                                </div>
                            </div>
                        </div>
                        @elseif($value == "SPA" || $value == "SPB" )
                        <div class="row">
                            <div class="col-5">
                                <div class="margin">
                                    <div><small class="text-muted">Customer</small></div>
                                </div>
                                <div class="margin">
                                    <b id="distributor">{{$data->customer->nama}}</b>
                                </div>
                                <div class="margin">
                                    <div><b id="no_akn">{{$data->customer->alamat}}</b></div>
                                </div>
                                <div class="margin">
                                    <div><b id="no_akn">{{$data->customer->telp}}</b></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="margin">
                                    <div><small class="text-muted">No SO</small></div>
                                    <div><b id="no_so">{{$data->pesanan->so}}</b></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="margin">
                                    <div><small class="text-muted">No PO</small></div>
                                    <div><b id="no_so">{{$data->pesanan->no_po}}</b></div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="margin">
                                    <div><small class="text-muted">Status</small></div>
                                    <div>{!!$status!!}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">


                        <div class="row">
                            <div class="col-12">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-belum_kirim-tab" data-toggle="pill" href="#pills-belum_kirim" role="tab" aria-controls="pills-belum_kirim" aria-selected="true">Belum Kirim</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-selesai_kirim-tab" data-toggle="pill" href="#pills-selesai_kirim" role="tab" aria-controls="pills-selesai_kirim" aria-selected="false">Sudah Kirim</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-belum_kirim" role="tabpanel" aria-labelledby="pills-belum_kirim-tab">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <div class="card removeboxshadow">
                                                            <div class="card-header">
                                                                <div class="card-title">Produk</div>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">

                                                                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-id="">
                                                                            <span class="float-right filter">
                                                                                <button class="btn btn-primary" type="button" id="kirim_produk" disabled><i class="fas fa-plus"></i> Pengiriman</button>
                                                                            </span>
                                                                        </a>
                                                                        <!-- <span class="float-right filter">
                                                            <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-filter"></i> Filter
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <div class="px-3 py-3">
                                                                    <div class="form-group">
                                                                        <label for="jenis_penjualan">Status</label>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" value="selesai" id="status1" name="status" />
                                                                            <label class="form-check-label" for="status1">
                                                                                Sudah Dikirim
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" value="sebagian" id="status2" name="status" />
                                                                            <label class="form-check-label" for="status2">
                                                                                Sebagian Dikirim
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" value="belum" id="status3" name="status" />
                                                                            <label class="form-check-label" for="status3">
                                                                                Belum Dikirim
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <span class="float-right">
                                                                            <button class="btn btn-primary">
                                                                                Cari
                                                                            </button>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </span> -->
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="table-responsive">
                                                                            <table class="table " style="text-align:center; width:100%;" id="belumkirimtable">

                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>
                                                                                            <div class="form-check">
                                                                                                <input class="form-check-input" type="checkbox" value="check_all" id="check_all" name="check_all" />
                                                                                                <label class="form-check-label" for="check_all">
                                                                                                </label>
                                                                                            </div>
                                                                                        </th>
                                                                                        <th>No</th>
                                                                                        <th>Nama Produk</th>
                                                                                        <th>Jumlah</th>
                                                                                        <th>Aksi</th>
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
                                                    <div class="col-5 hide" id="noseridetail">
                                                        <div class="card removeboxshadow">
                                                            <div class="card-header">
                                                                <div class="card-title">No Seri</div>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover table-striped align-center" id="noseritable">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>No Seri</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody></tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-selesai_kirim" role="tabpanel" aria-labelledby="pills-selesai_kirim-tab">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="table-responsive">
                                                            <table class="table" style="text-align:center; width:100%;" id="selesaikirimtable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Surat Jalan</th>
                                                                        <th>Tanggal Kirim</th>
                                                                        <th>Pengirim / Ekspedisi</th>
                                                                        <th>Nama Produk</th>
                                                                        <th>Jumlah</th>
                                                                        <th>Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <!-- <tr>
                                                                        <td><input type="checkbox" name="detail_produk_id" id="detail_produk_id" class="detail_produk_id" disabled></td>
                                                                        <td>26-10-2021</td>
                                                                        <td>ELITECH MINI/MEDICAL COMPRESSOR NEBULIZER PROMIST 2</td>
                                                                        <td>2</td>
                                                                        <td><span class="badge green-text">Sudah Dikirim</span></td>
                                                                        <td><a type="button" class="noserishow" data-id="1"><i class="fas fa-search"></i></a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox" name="detail_produk_id" id="detail_produk_id" class="detail_produk_id" disabled></td>
                                                                        <td>26-10-2021</td>
                                                                        <td>ELITECH ULTRASONIC POCKET DOPPLER</td>
                                                                        <td>5</td>
                                                                        <td><span class="badge green-text">Sudah Dikirim</span></td>
                                                                        <td><a type="button" class="noserishow" data-id="2"><i class="fas fa-search"></i></a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox" name="detail_produk_id" id="detail_produk_id" class="detail_produk_id available"></td>
                                                                        <td>-</td>
                                                                        <td>MTB 2 MTR</td>
                                                                        <td>10</td>
                                                                        <td><span class="badge red-text">Belum Dikirim</span></td>
                                                                        <td><a type="button" class="noserishow" data-id="3"><i class="fas fa-search"></i></a></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox" name="detail_produk_id" id="detail_produk_id" class="detail_produk_id available"></td>
                                                                        <td>-</td>
                                                                        <td>CENTRAL MONITOR PM-9000+ + PC + INSTALASI</td>
                                                                        <td>1</td>
                                                                        <td><span class="badge red-text">Belum Dikirim</span></td>
                                                                        <td><a type="button" class="noserishow" data-id="3"><i class="fas fa-search"></i></a></td>
                                                                    </tr> -->
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
                    </div>
                </div>
            </div>
            <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-info">
                            <h5 class="modal-title">Tambah Pengiriman</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="edit">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="detailmodal" role="dialog" aria-labelledby="detailmodal" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Logistik</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="detail">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@stop
@section('adminlte_js')
<script>
    $(function() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;

        y = [];
        y = <?php echo json_encode($detail_id); ?>;

        var belumkirimtable = $('#belumkirimtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/logistik/so/data/detail/belum_kirim/' + '{{$data->pesanan_id}}',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                data: 'checkbox',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'DT_RowIndex',
                className: 'align-center nowrap-text',
                orderable: false,
                searchable: false
            }, {
                data: 'nama_produk',

            }, {
                data: 'jumlah',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'button',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }]

        });

        var selesaikirimtable = $('#selesaikirimtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/logistik/so/data/detail/selesai_kirim/' + '{{$data->pesanan_id}}',
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
            }, {
                data: 'no',

            }, {
                data: 'tgl_kirim',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'pengirim',

            }, {
                data: 'nama_produk',

            }, {
                data: 'jumlah',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'button',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }]

        });

        $('#belumkirimtable').on('click', '.noserishow', function() {
            var data = $(this).attr('data-id');
            idtrf = <?php
                    if (!isset($data->pesanan->TFProduksi->id)) {
                        echo 0;
                    } else {
                        echo $data->pesanan->TFProduksi->id;
                    } ?>;
            $('#belumkirimtable').find('tr').removeClass('bgcolor');
            $(this).closest('tr').addClass('bgcolor');
            $('#noseridetail').removeClass('hide');
            $('input[name ="check_all"]').prop('checked', false);
            noseritable(data);
        })

        $('#selesaikirimtable').on('click', '.detailmodal', function() {
            var data = $(this).attr('data-id');
            $('#selesaikirimtable').find('tr').removeClass('bgcolor');
            $(this).closest('tr').addClass('bgcolor');

            $.ajax({
                url: "/api/logistik/so/noseri/detail/selesai_kirim/" + data,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#detailmodal').modal("show");
                    $('#detail').html(result).show();
                    showtable(data);
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        })

        $(document).on('submit', '#form-logistik-create', function(e) {
            e.preventDefault();
            var action = $(this).attr('action');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: action,
                data: $('#form-logistik-create').serialize(),
                success: function(response) {
                    // console.log(response);
                    if (response['data'] == "success") {
                        swal.fire(
                            'Berhasil',
                            'Berhasil menambahkan Pengiriman',
                            'success'
                        );
                        $("#editmodal").modal('hide');
                        $('#belumkirimtable').DataTable().ajax.reload();
                        $('#selesaikirimtable').DataTable().ajax.reload();
                        $('#noseridetail').addClass('hide');
                    } else if (response['data'] == "error") {
                        swal.fire(
                            'Gagal',
                            'Gagal menambahkan Pengiriman',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    alert("Page cannot open. Error:" + error);
                }
            });
            return false;
        });

        function detailpesanan(id, pesanan_id) {
            $('#detailpesanan').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/logistik/so/detail/select/' + id + '/' + pesanan_id,
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
                        data: 'nama_produk',
                    },
                    {
                        data: 'jumlah',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        }

        function noseritable(id) {
            $('#noseritable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/logistik/so/noseri/detail/belum_kirim/' + id,
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                    data: 'no_seri'
                }, ]
            })
        }

        function showtable(id) {
            $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/logistik/so/noseri/detail/selesai_kirim/data/' + id,
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
                        data: 'no_seri'
                    }
                ]
            })
        }

        // function select_produk(id) {
        //     $('.detail_produk').select2({
        //         placeholder: 'Pilih Produk',
        //         ajax: {
        //             minimumResultsForSearch: 20,
        //             dataType: 'json',
        //             delay: 250,
        //             type: 'GET',
        //             url: '/api/qc/so/riwayat/select/' + id,
        //             data: function(params) {
        //                 return {
        //                     term: params.term
        //                 }
        //             },
        //             processResults: function(data) {
        //                 console.log(data);
        //                 return {
        //                     results: $.map(data, function(obj) {
        //                         return {
        //                             id: obj.id,
        //                             text: obj.gudang_barang_jadi.produk.nama + ' ' +
        //                                 obj.gudang_barang_jadi.nama
        //                         };
        //                     })
        //                 };
        //             },
        //         }
        //     }).change(function() {
        //         var ids = $(this).val();
        //         noseritable(ids);
        //     });
        // }

        var checkedAry = [];
        $('#belumkirimtable').on('click', 'input[name="check_all"]', function() {
            if ($('input[name="check_all"]:checked').length > 0) {
                $('.detail_produk_id').prop('checked', true);
                checkedAry = []
                checkedAry.push('0')
                $('#kirim_produk').removeAttr('disabled');
            } else if ($('input[name="check_all"]:checked').length <= 0) {
                $('.detail_produk_id').prop('checked', false);
                $('#kirim_produk').prop('disabled', true);
            }
        });


        $('#belumkirimtable').on('click', '.detail_produk_id', function() {
            if ($('.detail_produk_id:checked').length > 0) {
                $('#kirim_produk').removeAttr('disabled');
                checkedAry = [];
                $.each($(".detail_produk_id:checked"), function() {
                    checkedAry.push($(this).closest('tr').find('.detail_produk_id').attr('data-id'));
                });

            } else if ($('.detail_produk_id:checked').length <= 0) {
                $('#kirim_produk').attr('disabled', true);
            }
        })

        function ekspedisi_select() {
            $('.ekspedisi_id').select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Ekspedisi",
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/logistik/ekspedisi/select',
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
            }).change(function() {
                if ($(this).val() != "") {
                    $('#ekspedisi_id').removeClass('is-invalid');
                    $('#msgekspedisi_id').text("");
                    if ($('#no_invoice').val() != "" && $('#tgl_kirim').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val() != "")) {
                        $('#btnsimpan').removeAttr('disabled');
                    } else {
                        $('#btnsimpan').attr('disabled', true);
                    }
                } else if ($(this).val() == "") {
                    $('#ekspedisi_id').addClass('is-invalid');
                    $('#msgekspedisi_id').text("No Kendaraan harus diisi");
                    $('#btnsimpan').attr('disabled', true);
                }
                var value = $(this).val();
                console.log(value);
            });
        }

        function max_date() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd;
            //console.log(today);
            $("#tgl_kirim").attr("max", today);
        }

        $(document).on('click', '.editmodal', function(event) {
            event.preventDefault();
            console.log(checkedAry);
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            var pesanan_id = '{{$data->id}}';

            $.ajax({
                url: "/logistik/so/create/" + checkedAry + '/' + pesanan_id,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#editmodal').modal("show");
                    $('#edit').html(result).show();
                    detailpesanan(checkedAry, pesanan_id);
                    ekspedisi_select();
                    $('#tgl_kirim').attr('max', today);
                    // $("#editform").attr("action", href);
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

        $(document).on('change', 'input[type="radio"][name="pengiriman"]', function(event) {
            $('#ekspedisi_id').removeClass('is-invalid');
            $('#msgekspedisi_id').text("");
            $('#nama_pengirim').removeClass('is-invalid');
            $('#msgnama_pengirim').text("");

            if ($(this).val() == "ekspedisi") {
                $('#ekspedisi').removeClass('hide');
                $('#nonekspedisi').addClass('hide');
                $('#nama_pengirim').val("");
                if ($('#no_invoice').val() != "" && $('#tgl_kirim').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val() != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }

            } else if ($(this).val() == "nonekspedisi") {
                $('#ekspedisi').addClass('hide');
                $('#nonekspedisi').removeClass('hide');
                $('.ekspedisi_id').val("");
                if ($('#no_invoice').val() != "" && $('#tgl_kirim').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val() != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            }
        });

        $(document).on('change keyup', '#no_invoice', function(event) {
            if ($(this).val() != "") {
                var val = $(this).val();
                $.ajax({
                    type: "GET",
                    url: '/api/logistik/cek/no_sj/' + val,
                    dataType: 'json',
                    success: function(data) {
                        if (data.data > 0) {
                            $('#no_invoice').addClass('is-invalid');
                            $('#msgnoinvoice').text("No Surat Jalan sudah terpakai");
                            $('#btnsimpan').attr('disabled', true);
                        } else {
                            $('#no_invoice').removeClass('is-invalid');
                            $('#msgnoinvoice').text("");
                            if ($('#no_invoice').val() != "" && $('#tgl_kirim').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val() != "")) {
                                $('#btnsimpan').removeAttr('disabled');
                            } else {
                                $('#btnsimpan').attr('disabled', true);
                            }
                        }
                    },
                    error: function() {
                        alert('Error occured');
                    }
                });
            } else if ($(this).val() == "") {
                $('#no_invoice').addClass('is-invalid');
                $('#msgnoinvoice').text("No Surat Jalan tidak boleh kosong");
                $('#btnsimpan').attr('disabled', true);
            }
        });

        $(document).on('change keyup', '#tgl_kirim', function(event) {
            if ($(this).val() != "") {
                $('#tgl_kirim').removeClass('is-invalid');
                $('#msgtgl_kirim').text("");
                if ($('#no_invoice').val() != "" && $('#tgl_kirim').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val() != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#tgl_kirim').addClass('is-invalid');
                $('#msgtgl_kirim').text("Tanggal Kirim harus diisi");
                $('#btnsimpan').attr('disabled', true);
            }
        });

        $(document).on('change keyup', '#nama_pengirim', function(event) {
            if ($(this).val() != "") {
                $('#nama_pengirim').removeClass('is-invalid');
                $('#msgnama_pengirim').text("");
                if ($('#no_invoice').val() != "" && $('#tgl_kirim').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val() != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#nama_pengirim').addClass('is-invalid');
                $('#msgnama_pengirim').text("Nama Pengirim harus diisi");
                $('#btnsimpan').attr('disabled', true);
            }
        });


        // $(document).on('change keyup', '.ekspedisi_id', function(event) {
        //     if ($(this).val() != "") {
        //         $('#ekspedisi_id').removeClass('is-invalid');
        //         $('#msgekspedisi_id').text("");
        //         if ($('#no_invoice').val() != "" && $('#tgl_kirim').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val("") != "")) {
        //             $('#btnsimpan').removeAttr('disabled');
        //         } else {
        //             $('#btnsimpan').attr('disabled', true);
        //         }
        //     } else if ($(this).val() == "") {
        //         $('#ekspedisi_id').addClass('is-invalid');
        //         $('#msgekspedisi_id').text("No Kendaraan harus diisi");
        //         $('#btnsimpan').attr('disabled', true);
        //     }
        // });
    })
</script>
@stop