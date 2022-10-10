@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Lacak</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->divisi_id == '26' || Auth::user()->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Lacak</li>
                    @elseif(Auth::user()->divisi_id == '15')
                        <li class="breadcrumb-item"><a href="{{ route('logistik.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Lacak</li>
                    @elseif(Auth::user()->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Lacak</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        .filter {
            margin: 5px;
        }

        .hide {
            display: none !important;
        }

        .nowraps {
            white-space: nowrap;
        }

        .align-center {
            text-align: center;
        }

        #warning {
            color: #FFC700;
            font-weight: 600;
        }

        #info {
            color: #3a7bb0;
            font-weight: 600;
        }


        @media screen and (min-width: 992px) {
            .labelket {
                text-align: right;
            }

            body {
                font-size: 14px;
            }

            #detailmodal {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .overflowy {
                max-height: 550px;
                width: auto;
                overflow-y: scroll;
                box-shadow: none;
            }

            .dropdown-item {
                font-size: 14px;
            }
        }

        @media screen and (max-width: 991px) {
            .labelket {
                text-align: left;
            }

            body {
                font-size: 12px;
            }

            h4 {
                font-size: 18x;
            }

            #detailmodal {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .overflowy {
                max-height: 450px;
                width: auto;
                overflow-y: scroll;
                box-shadow: none;
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
                        <div class="card-header bg-secondary">
                            <div class="card-title">Pencarian</div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-horizontal">
                                        <div class="form-group row">
                                            <label for=""
                                                class="col-form-label col-lg-5 col-md-12 labelket">Lacak</label>
                                            <div class="col-lg-4 col-md-12">
                                                <input type="text"
                                                    class="form-control col-form-label @error('data') is-invalid @enderror"
                                                    id="data" name="data" placeholder="Masukkan data" />
                                                <div class="invalid-feedback" id="msgdata">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for=""
                                                class="col-form-label col-lg-5 col-md-12 labelket">Pilih</label>
                                            <div class="col-lg-4 col-md-12">
                                                <select name="pilih_data" id="pilih_data"
                                                    class="select2 select-info form-control custom-select col-form-label pilih_data"
                                                    placeholder="Pilih Data" disabled>
                                                    <option value=""></option>
                                                    <option value="produk">Produk</option>
                                                    <option value="customer">Distributor / Customer / Satuan Kerja /
                                                        Instansi</option>
                                                    <option value="no_po">No Purchase Order</option>
                                                    <option value="no_akn">No Paket</option>
                                                    <option value="no_seri">No Seri</option>
                                                    <option value="no_so">No Sales Order</option>
                                                    <option value="no_sj">No Surat Jalan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-5"></div>
                                            <div class="col-lg-4 col-md-12">
                                                <span class="float-right filter"><button type="button"
                                                        class="btn btn-success" id="btncari" disabled><i
                                                            class="fas fa-search"></i> Cari</button></span>
                                                <span class="float-right filter"><button type="button"
                                                        class="btn btn-outline-danger" id="btnbatal"><i
                                                            class="fas fa-sync"></i> Reset</button></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card hide result" id="nopo">
                        <div class="card-body">
                            <h4>Hasil Pencarian Purchase Order</h4>
                            <div class="table-responsive">
                                <table class="table table-hover" id="potable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No SO</th>
                                            <th>No PO</th>
                                            <th>Tanggal PO</th>
                                            <th>Customer</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card hide result" id="customer">
                        <div class="card-body">
                            <h4>Hasil Pencarian Distributor/Customer/Satuan Kerja/Instansi</h4>
                            <div class="table-responsive">
                                <table class="table table-hover" id="customertable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Seri</th>
                                            <th>No PO</th>
                                            <th>Customer</th>
                                            <th>Nama Produk</th>
                                            <th>Tanggal Uji</th>
                                            <th>No SJ</th>
                                            <th>Tanggal Kirim</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card hide result" id="produk">
                        <div class="card-body">
                            <h4>Hasil Pencarian Produk</h4>
                            <div class="table-responsive">
                                <table class="table table-hover" id="produktable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Seri</th>
                                            <th>No PO</th>
                                            <th>Customer</th>
                                            <th>Nama Produk</th>
                                            <th>Tanggal Uji</th>
                                            <th>No SJ</th>
                                            <th>Tanggal Kirim</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card hide result" id="noseri">
                        <div class="card-body">
                            <h4>Hasil Pencarian No Seri</h4>
                            <div class="table-responsive">
                                <table class="table table-hover" id="noseritable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Seri</th>
                                            <th>No PO</th>
                                            <th>Customer</th>
                                            <th>Nama Produk</th>
                                            <th>Tanggal Uji</th>
                                            <th>No SJ</th>
                                            <th>Tanggal Kirim</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card hide result" id="noakn">
                        <div class="card-body">
                            <h4>Hasil Pencarian No Paket</h4>
                            <div class="table-responsive">
                                <table class="table table-hover" id="noakntable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No AKN</th>
                                            <th>No SO</th>
                                            <th>Tgl Buat</th>
                                            <th>Batas Kontrak</th>
                                            <th>Distributor</th>
                                            <th>Instansi</th>
                                            <th>Posisi</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card hide result" id="noso">
                        <div class="card-body">
                            <h4>Hasil Pencarian Sales Order</h4>
                            <div class="table-responsive">
                                <table class="table table-hover" id="nosotable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No SO</th>
                                            <th>No PO</th>
                                            <th>Tanggal PO</th>
                                            <th>Customer</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card hide result" id="nosj">
                        <div class="card-body">
                            <h4>Hasil Pencarian Surat Jalan</h4>
                            <div class="table-responsive">
                                <table class="table table-hover" id="nosjtable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No PO</th>
                                            <th>No Surat Jalan</th>
                                            <th>No Resi</th>
                                            <th>Customer</th>
                                            <th>Tanggal Kirim</th>
                                            <th>Status</th>
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
    </section>
@endsection

@section('adminlte_js')
    <script>
        $(function() {
            $('.pilih_data').select2({
                placeholder: "Pilih Data Lacak",
                allowClear: true
            });

            function po(data){
                var potable = $('#potable').DataTable({
                    destroy: true,
                    processing: true,
                    // serverSide: true,
                    ajax: {
                        'url': '/api/penjualan/lacak/data/no_po/'+data,
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [
                        {
                            data: null,
                        }, {
                            data: null,
                            className: 'nowraps align-center',
                            render: function (data, type, row) {
                                if (row.so != null) {
                                    return row.so;
                                } else{
                                    return '-';
                                }
                            }
                        }, {
                            data: 'no_po',
                            className: 'nowraps align-center'
                        }, {
                            data: 'tgl_po',
                            className: 'nowraps align-center',
                            render: function (data, type, row){
                                return moment(new Date(data).toString()).format('DD-MM-YYYY');
                            }
                        }, {
                            data: null,
                            className: 'nowraps align-center',
                            render: function (data, type, row) {
                                if (row.c_ekat_nama != null) {
                                    return row.c_ekat_nama;
                                }else if (row.c_spa_nama != null) {
                                    return row.c_spa_nama;
                                }else if (row.c_spb_nama != null) {
                                    return row.c_spb_nama;
                                }else if (row.customer != null) {
                                    return row.customer;
                                }else{
                                    return '-';
                                }
                            }
                        }, {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row){
                                if (row.state_nama != null || row.state_nama != undefined) {
                                    if (row.state_nama == "Penjualan") {
                                        return '<span class="red-text badge">'+row.state_nama + '</span>';
                                    } else if (row.state_nama == "PO") {
                                        return '<span class="purple-text badge">'+row.state_nama + '</span>';
                                    } else if (row.state_nama == "Gudang") {
                                        return '<span class="orange-text badge">'+row.state_nama + '</span>';
                                    } else if (row.state_nama == "QC") {
                                        return '<span class="yellow-text badge">'+row.state_nama + '</span>';
                                    } else if (row.state_nama == "Belum Terkirim") {
                                        return '<span class="red-text badge">'+row.state_nama + '</span>';
                                    } else if (row.state_nama == "Terkirim Sebagian") {
                                        return '<span class="blue-text badge">'+row.state_nama + '</span>';
                                    } else if (row.state_nama == "Kirim") {
                                        return '<span class="green-text badge">'+row.state_nama + '</span>';
                                    }else{
                                        return '-';
                                    }
                                }
                                else{
                                    return "-";
                                }
                            }
                        }
                    ],
                    columnDefs : [
                        {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        },
                    ],
                    order: [[2, 'asc']],
                });

                potable.on('order.dt search.dt', function () {
                    potable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }

            function so(data){
                $('#nosotable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/penjualan/lacak/data/no_so/'+ data,
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'nowraps'
                    }, {
                        data: 'so',
                        className: 'nowraps align-center'
                    }, {
                        data: 'no_po',
                        className: 'nowraps align-center'
                    }, {
                        data: 'tgl_po',
                        className: 'nowraps align-center'
                    }, {
                        data: 'nama_customer',
                        className: 'align-center'
                    }, {
                        data: 'log',
                        className: 'nowraps align-center'
                    }, ]
                });
            }

            function akn(data){
                var d = new Date();
                var month = d.getMonth()+1;
                var day = d.getDate();

                var output = d.getFullYear() + '-' +
                    (month<10 ? '0' : '') + month + '-' +
                    (day<10 ? '0' : '') + day;

                var akntable = $('#noakntable').DataTable({
                    destroy: true,
                    processing: true,
                    // serverSide: true,
                    ajax: {
                        'url': '/api/penjualan/lacak/data/no_akn/'+data,
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                            data: null,
                        },
                        {
                            data: 'no_paket',
                            className: 'nowraps align-center'
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row){
                                if (row.pesanan != null) {
                                    return row.pesanan.so
                                }  else {
                                    return  '-';
                                }
                            }
                        },
                        {
                            data: 'tgl_buat',
                            className: 'nowraps align-center',
                            render: function(data, type, row){
                                return moment(new Date(data).toString()).format('DD-MM-YYYY')
                            }
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row){
                                if (row.tgl_kontrak_custom != null) {
                                    if(row.pesanan.state.nama == "Kirim"){
                                        return moment(new Date(row.tgl_kontrak_custom).toString()).format('DD-MM-YYYY');
                                    } else {
                                        var hari = (new Date(row.tgl_kontrak_custom) - new Date(output)) / (1000 * 3600 * 24);
                                        if (hari > 7) {
                                            return  `<div>`+ moment(new Date(row.tgl_kontrak_custom).toString()).format('DD-MM-YYYY') + `</div>
                                            <div><small><i class="fas fa-clock" id="info"></i> `+ hari+ ` Hari Lagi</small></div>`;
                                        } else if (hari > 0 && hari <= 7) {
                                            return  `<div id="warning">`+ moment(new Date(row.tgl_kontrak_custom).toString()).format('DD-MM-YYYY') + `</div>
                                            <div><small><i class="fas fa-exclamation-circle" id="warning"></i> `+ hari + ` Hari Lagi</small></div>`;
                                        } else {
                                            return  `<div class="text-danger font-weight-bold">` + moment(new Date(row.tgl_kontrak_custom).toString()).format('DD-MM-YYYY') + `</div>
                                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi `+Math.abs(hari)+` Hari</div>`;
                                        }
                                    }
                                }  else {
                                    return  '-';
                                }
                            }
                        },
                        {
                            data: null,
                            className: 'align-center',
                            render: function(data, type, row){
                                if (row.customer != null) {
                                    if($.type(row.customer) != "string"){
                                        return row.customer.nama;
                                    }
                                    else{
                                        return row.customer;
                                    }
                                }  else {
                                    return  '-';
                                }
                            }
                        },
                        {
                            data: 'instansi',
                            className: 'align-center'
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row){
                                if (row.pesanan != null) {
                                    if (row.pesanan.state.nama == "Penjualan") {
                                        return '<span class="red-text badge">'+row.pesanan.state.nama + '</span>';
                                    } else if (row.pesanan.state.nama == "PO") {
                                        return '<span class="purple-text badge">'+row.pesanan.state.nama + '</span>';
                                    } else if (row.pesanan.state.nama == "Gudang") {
                                        return '<span class="orange-text badge">'+row.pesanan.state.nama + '</span>';
                                    } else if (row.pesanan.state.nama == "QC") {
                                        return '<span class="yellow-text badge">'+row.pesanan.state.nama + '</span>';
                                    } else if (row.pesanan.state.nama == "Belum Terkirim") {
                                        return '<span class="red-text badge">'+row.pesanan.state.nama + '</span>';
                                    } else if (row.pesanan.state.nama == "Terkirim Sebagian") {
                                        return '<span class="blue-text badge">'+row.pesanan.state.nama + '</span>';
                                    } else if (row.pesanan.state.nama == "Kirim") {
                                        return '<span class="green-text badge">'+row.pesanan.state.nama + '</span>';
                                    } else{
                                        return '-'
                                    }
                                }
                                else{
                                    return '-'
                                }
                            }
                        },
                        {
                            data: 'status',
                            className: 'nowraps align-center',
                            render: function(data, type, row){
                                if (row.status == "draft") {
                                    return '<span class="badge blue-text">Draft</span>';
                                } else if (row.status == "sepakat") {
                                    return '<span class="green-text badge">Sepakat</span>';
                                } else if (row.status == "negosiasi") {
                                    return  '<span class="yellow-text badge">Negosiasi</span>';
                                } else {
                                    return  '<span class="red-text badge">Batal</span>';
                                }
                            }
                        }
                    ], columnDefs : [
                        {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        },
                    ],
                    order: [[1, 'asc']],
                });

                akntable.on('order.dt search.dt', function () {
                        akntable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                            cell.innerHTML = i + 1;
                        });
                    }).draw();

            }

            function noseri(data){
                var noseritable = $('#noseritable').DataTable({
                    destroy: true,
                    processing: true,
                    // serverSide: true,
                    ajax: {
                        'url': '/api/penjualan/lacak/data/no_seri/'+ data,
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                            data: null,
                        },
                        {
                            data: 'noseri',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'no_po',
                            className: 'nowraps align-center',

                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function (data, type, row) {
                                if (row.c_ekat_nama != null) {
                                    return row.c_ekat_nama;
                                }else if (row.c_spa_nama != null) {
                                    return row.c_spa_nama;
                                }else if (row.c_spb_nama != null) {
                                    return row.c_spb_nama;
                                }else{
                                    return '-'
                                }
                            }
                        },
                        {
                            data: 'p_nama',
                            className: 'nowraps align-center'
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function (data, type, row) {
                                if (row.tgl_uji != null) {
                                    return moment(new Date(row.tgl_uji).toString()).format('DD-MM-YYYY');
                                }else if (row.tglserah_on != null) {
                                    return moment(new Date(row.tglserah_on).toString()).format('DD-MM-YYYY');
                                }else if (row.tglserah_off != null) {
                                    return moment(new Date(row.tglserah_off).toString()).format('DD-MM-YYYY');
                                }else if (row.tglserah_spb != null) {
                                    return moment(new Date(row.tglserah_spb).toString()).format('DD-MM-YYYY');
                                }else{
                                    return '-'
                                }
                            }
                        },
                        {
                            data: 'no_sj',
                            className: 'nowraps align-center'
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function (data, type, row) {
                                if (row.tgl_sj != null) {
                                    return moment(new Date(row.tgl_sj).toString()).format('DD-MM-YYYY');
                                }else if (row.tgl_kirim != null) {
                                    return moment(new Date(row.tgl_kirim).toString()).format('DD-MM-YYYY');
                                }else{
                                    return '-'
                                }
                            }
                        },
                        {
                            name: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row){

                                if (row.state_nama != null) {
                                    if (row.state_nama == "Penjualan") {
                                        return '<span class="red-text badge">'+row.state_nama + '</span>';
                                    } else if (row.state_nama == "PO") {
                                        return '<span class="purple-text badge">'+row.state_nama + '</span>';
                                    } else if (row.state_nama == "Gudang") {
                                        return '<span class="orange-text badge">'+row.state_nama + '</span>';
                                    } else if (row.state_nama == "QC") {
                                        return '<span class="yellow-text badge">'+row.state_nama + '</span>';
                                    } else if (row.state_nama == "Belum Terkirim") {
                                        return '<span class="red-text badge">'+row.state_nama + '</span>';
                                    } else if (row.state_nama == "Terkirim Sebagian") {
                                        return '<span class="blue-text badge">'+row.state_nama + '</span>';
                                    } else if (row.state_nama == "Kirim") {
                                        return '<span class="green-text badge">'+row.state_nama + '</span>';
                                    }
                                }
                                else{
                                    return '<span class="green-text badge">Kirim</span>'
                                }
                            }
                        }
                    ],
                    columnDefs : [
                        {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        },
                    ],
                    order: [[7, 'asc']],
                });

                noseritable.on('order.dt search.dt', function () {
                        noseritable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                            cell.innerHTML = i + 1;
                        });
                    }).draw();
            }

            function customer(data){
                $('#customertable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/penjualan/lacak/data/customer/'+data,
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'noseri',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'no_so',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'nama_customer',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'nama_produk',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'tgl_uji',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'no_sj',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'tgl_kirim',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'status',
                            className: 'nowraps align-center'
                        }
                    ]
                });

            }

            function produk(data){
                $('#produktable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/penjualan/lacak/data/produk/'+data,
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'noseri',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'no_so',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'nama_customer',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'nama_produk',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'tgl_uji',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'no_sj',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'tgl_kirim',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'status',
                            className: 'nowraps align-center'
                        }
                    ]
                });
            }

            function sj(data){
                $('#nosjtable').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/penjualan/lacak/data/no_sj/'+data,
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                            data: null,
                        },
                        {
                            data: 'po',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'nosurat',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'resi',
                            className: 'align-center'
                        },
                        {
                            data: 'customer',
                            className: 'align-center'
                        },
                        {
                            data: 'tgl_kirim',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'status',
                            className: 'nowraps align-center'
                        }
                    ]
                });
            }

            $('#data').on('keyup change', function() {
                if ($(this).val() != "") {
                    $('.pilih_data').removeAttr('disabled');
                    if ($('.pilih_data').find(":selected").val() != "") {
                        $('#btncari').removeAttr('disabled');
                    } else if ($('.pilih_data').find(":selected").val() == "") {
                        $('#btncari').attr('disabled', true);
                    }
                } else {
                    $('#btncari').attr('disabled', true);
                }
            });
            $('.pilih_data').on('keyup change', function() {
                console.log($(this).val());
                if ($(this).val() != "") {
                    if ($('#data').val() != "") {
                        $('#btncari').removeAttr('disabled');
                    } else {
                        $('#btncari').attr('disabled', true);
                    }
                } else if ($(this).val() == "") {
                    $('#btncari').attr('disabled', true);
                }
            });

            $('#btncari').on('click', function() {
                if ($('.pilih_data').val() == "no_seri") {
                    var data = $('#data').val();
                    // $('#noseritable').DataTable().ajax.url('/api/penjualan/lacak/data/no_seri/' + data).load();
                    noseri(data);
                    $('#noseri').removeClass('hide');
                    $('#customer').addClass('hide');
                    $('#nopo').addClass('hide');
                    $('#noakn').addClass('hide');
                    $('#noso').addClass('hide');
                    $('#nosj').addClass('hide');
                    $('#produk').addClass('hide');
                } else if ($('.pilih_data').val() == "produk") {
                    var data = $('#data').val();
                    produk(data);
                    //   $('#produktable').DataTable().ajax.url('/api/penjualan/lacak/data/produk/' + data).load();
                    $('#produk').removeClass('hide');
                    $('#nopo').addClass('hide');
                    $('#nopo').addClass('hide');
                    $('#noakn').addClass('hide');
                    $('#noso').addClass('hide');
                    $('#nosj').addClass('hide');
                    $('#customer').addClass('hide');

                } else if ($('.pilih_data').val() == "customer") {
                    var data = $('#data').val();
                    customer(data);
                    //  $('#customertable').DataTable().ajax.url('/api/penjualan/lacak/data/customer/' + data).load();
                    $('#customer').removeClass('hide');
                    $('#nopo').addClass('hide');
                    $('#nopo').addClass('hide');
                    $('#noakn').addClass('hide');
                    $('#noso').addClass('hide');
                    $('#nosj').addClass('hide');
                    $('#produk').addClass('hide');

                } else if ($('.pilih_data').val() == "no_po") {
                    var data = $('#data').val();
                    po(data.replace(/([~!@#$%^&*()_+=`{}\[\]\|\\:;'<>,.\/? ])+/g, '-'));
                    //  $('#potable').DataTable().ajax.url('/api/penjualan/lacak/data/no_po/' + data).load();
                    $('#nopo').removeClass('hide');
                    $('#noseri').addClass('hide');
                    $('#noakn').addClass('hide');
                    $('#customer').addClass('hide');
                    $('#noso').addClass('hide');
                    $('#nosj').addClass('hide');
                    $('#produk').addClass('hide');
                } else if ($('.pilih_data').val() == "no_akn") {
                    var data = $('#data').val();
                    // $('#noakntable').DataTable().ajax.url('/api/penjualan/lacak/data/no_akn/' + data).load();
                    akn(data);
                    $('#noakn').removeClass('hide');
                    $('#customer').addClass('hide');
                    $('#noseri').addClass('hide');
                    $('#nopo').addClass('hide');
                    $('#noso').addClass('hide');
                    $('#nosj').addClass('hide');
                    $('#produk').addClass('hide');
                } else if ($('.pilih_data').val() == "no_so") {
                    var data = $('#data').val();
                    var p = 'O';
                    var xxx = data.replace('/' + p + '/g', ':');
                    so(data);
                    // $('#nosotable').DataTable().ajax.url('/api/penjualan/lacak/data/no_so/' + data).load();
                    $('#customer').addClass('hide');
                    $('#noakn').addClass('hide');
                    $('#noseri').addClass('hide');
                    $('#nopo').addClass('hide');
                    $('#noso').removeClass('hide');
                    $('#nosj').addClass('hide');
                    $('#produk').addClass('hide');
                } else if ($('.pilih_data').val() == "no_sj") {
                    var data = $('#data').val();
                    sj(data);
                    //$('#nosjtable').DataTable().ajax.url('/api/penjualan/lacak/data/no_sj/' + data).load();
                    $('#nosj').removeClass('hide');
                    $('#customer').addClass('hide');
                    $('#noseri').addClass('hide');
                    $('#nopo').addClass('hide');
                    $('#noso').addClass('hide');
                    $('#noakn').addClass('hide');
                    $('#produk').addClass('hide');
                }
                // $('#btncari').attr("disabled", true);
                // $('.pilih_data').attr("disabled", true);
                // $('#data').attr('disabled', true);
            });

            $('#btnbatal').on('click', function() {
                $('.pilih_data').val(null).trigger('change');
                $('#data').val('');
                $('#data').removeAttr('disabled');
                $('#pilih_data').attr('disabled', true);
                $('#btncari').attr('disabled', true);
            });

        // $('#btncari').on('click', function() {
        //     if ($('.pilih_data').val() == "no_seri") {
        //         var data = $('#data').val();
        //         console.log(data);
        //        // $('#noseritable').DataTable().ajax.url('/api/penjualan/lacak/data/no_seri/' + data).load();
        //         noseri(data);
        //         $('#noseri').removeClass('hide');
        //         $('#customer').addClass('hide');
        //         $('#nopo').addClass('hide');
        //         $('#noakn').addClass('hide');
        //         $('#noso').addClass('hide');
        //         $('#nosj').addClass('hide');
        //         $('#produk').addClass('hide');
        //     } else if ($('.pilih_data').val() == "produk") {
        //         var data = $('#data').val();
        //         produk(data);
        //      //   $('#produktable').DataTable().ajax.url('/api/penjualan/lacak/data/produk/' + data).load();
        //         $('#produk').removeClass('hide');
        //         $('#nopo').addClass('hide');
        //         $('#nopo').addClass('hide');
        //         $('#noakn').addClass('hide');
        //         $('#noso').addClass('hide');
        //         $('#nosj').addClass('hide');
        //         $('#customer').addClass('hide');

        //     } else if ($('.pilih_data').val() == "customer") {
        //         var data = $('#data').val();
        //         customer(data);
        //       //  $('#customertable').DataTable().ajax.url('/api/penjualan/lacak/data/customer/' + data).load();
        //         $('#customer').removeClass('hide');
        //         $('#nopo').addClass('hide');
        //         $('#nopo').addClass('hide');
        //         $('#noakn').addClass('hide');
        //         $('#noso').addClass('hide');
        //         $('#nosj').addClass('hide');
        //         $('#produk').addClass('hide');

        //     } else if ($('.pilih_data').val() == "no_po") {
        //         var data = $('#data').val();
        //         po(data.replace('/', '_'));
        //       //  $('#potable').DataTable().ajax.url('/api/penjualan/lacak/data/no_po/' + data).load();
        //         $('#nopo').removeClass('hide');
        //         $('#noseri').addClass('hide');
        //         $('#noakn').addClass('hide');
        //         $('#customer').addClass('hide');
        //         $('#noso').addClass('hide');
        //         $('#nosj').addClass('hide');
        //         $('#produk').addClass('hide');
        //     } else if ($('.pilih_data').val() == "no_akn") {
        //         var data = $('#data').val();
        //        // $('#noakntable').DataTable().ajax.url('/api/penjualan/lacak/data/no_akn/' + data).load();
        //        akn(data);
        //        $('#noakn').removeClass('hide');
        //         $('#customer').addClass('hide');
        //         $('#noseri').addClass('hide');
        //         $('#nopo').addClass('hide');
        //         $('#noso').addClass('hide');
        //         $('#nosj').addClass('hide');
        //         $('#produk').addClass('hide');
        //     } else if ($('.pilih_data').val() == "no_so") {
        //         var data = $('#data').val();
        //         // var p = 'O';
        //         // var xxx = data.replace('/' + p + '/g', ':');
        //         so(data.replace('/', '_'));
        //        // $('#nosotable').DataTable().ajax.url('/api/penjualan/lacak/data/no_so/' + data).load();
        //         $('#customer').addClass('hide');
        //         $('#noakn').addClass('hide');
        //         $('#noseri').addClass('hide');
        //         $('#nopo').addClass('hide');
        //         $('#noso').removeClass('hide');
        //         $('#nosj').addClass('hide');
        //         $('#produk').addClass('hide');
        //     } else if ($('.pilih_data').val() == "no_sj") {
        //         var data = $('#data').val();
        //         sj(data);
        //         //$('#nosjtable').DataTable().ajax.url('/api/penjualan/lacak/data/no_sj/' + data).load();
        //         $('#nosj').removeClass('hide');
        //         $('#customer').addClass('hide');
        //         $('#noseri').addClass('hide');
        //         $('#nopo').addClass('hide');
        //         $('#noso').addClass('hide');
        //         $('#noakn').addClass('hide');
        //         $('#produk').addClass('hide');
        //     }
        //     // $('#btncari').attr("disabled", true);
        //     // $('.pilih_data').attr("disabled", true);
        //     // $('#data').attr('disabled', true);
        // });

        // $('#btnbatal').on('click', function() {
        //     $('.pilih_data').val(null).trigger('change');
        //     $('#data').val('');
        //     $('#data').removeAttr('disabled');
        //     $('#pilih_data').attr('disabled', true);
        //     $('#btncari').attr('disabled', true);
        //     $('#noseri').addClass('hide');
        //     $('#nopo').addClass('hide');
        //     $('#noso').addClass('hide');
        //     $('.result').addClass('hide');
        // });


        })
    </script>
@stop
