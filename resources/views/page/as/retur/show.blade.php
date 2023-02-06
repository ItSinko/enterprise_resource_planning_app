@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Memo Retur</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Memo Retur</li>
                    @endif
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

        .margin {
            margin: 5px;
        }

        .align-center {
            text-align: center;
        }

        .removeshadow {
            box-shadow: none;
        }

        .nowraps {
            white-space: nowrap;
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
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('as.retur.create') }}" type="button" class="btn btn-info float-right my-2"><i
                                    class="fas fa-plus"></i> Tambah</a>
                            <div class="table-responsive">
                                <table class="table table-hover" id="showtable" style="text-align: center;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Retur</th>
                                            <th>No Referensi</th>
                                            <th>Tanggal Retur</th>
                                            <th>Jenis Retur</th>
                                            <th>Customer</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>RET/0012/02/1293</td>
                                            <td>AKN-P2207-102345</td>
                                            <td>20-02-2022</td>
                                            <td><span class="badge blue-text">Komplain</span></td>
                                            <td>PT. Emiindo Jaya Bersama</td>
                                            <td><span class="badge red-text">Belum Diproses</span></td>
                                            <td><button type="button" class="btn btn-outline-primary btn-sm"><i
                                                        class="fas fa-eye"></i> Detail</button></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>RET/0012/02/1293</td>
                                            <td>AKN-P2207-102345</td>
                                            <td>20-02-2022</td>
                                            <td><span class="badge orange-text">Service</span></td>
                                            <td>PT. Emiindo Jaya Bersama</td>
                                            <td><span class="badge red-text">Belum Diproses</span></td>
                                            <td><a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal"
                                                    id="detailmodal"><button type="button"
                                                        class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i>
                                                        Detail</button></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="detail_modal" role="dialog" aria-labelledby="detail_modal" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header">
                                <h4 class="modal-title">Detail Memo Retur</h4>
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
@endsection

@section('adminlte_js')
    <script>
        $(function() {
            function no_kolom(table){
                table.on('order.dt search.dt', function() {
                    table.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }

            var showtable = $('#showtable').DataTable({
                    destroy: true,
                    processing: true,
                    // serverSide: true,
                    ajax: {
                        'url': '/api/as/retur/data',
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                            data: null,
                        },
                        {
                            data: 'no_retur',
                            className: 'nowraps align-center'
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row) {
                                if (row.pesanan_id != null) {
                                    return row.pesanan.no_po;
                                } else if (row.retur_penjualan_id != null) {
                                    return row.retur_penjualan_child.no_retur;
                                } else {
                                    return row.no_pesanan;
                                }
                            }
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row) {
                                return moment(new Date(row.tgl_retur).toString()).format(
                                        'DD-MM-YYYY');
                            }
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row) {
                                if (row.jenis == "peminjaman") {
                                    return '<span class="purple-text badge">' + row.jenis[0].toUpperCase() + row.jenis.substring(1) +
                                            '</span>';
                                } else if (row.jenis == "komplain") {
                                    return '<span class="blue-text badge">' + row.jenis[0].toUpperCase() + row.jenis.substring(1) +
                                            '</span>';
                                } else if (row.jenis == "service") {
                                    return '<span class="orange-text badge">' + row.jenis[0].toUpperCase() + row.jenis.substring(1) +
                                            '</span>';
                                } else {
                                    return '<span class="red-text badge">Tanpa Status</span>';
                                }
                            }
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row) {
                                return row.customer.nama;
                            }
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row) {
                                var count_real = parseInt(data.count_part) + parseInt(data.count_noseri);
                                var count_done = parseInt(data.count_kirim_part) + parseInt(data.count_perbaikan_karantina) + parseInt(data.count_pengiriman);
                                var hitung = Math.floor((count_done / count_real) * 100);
                                if (count_done <= 0) {
                                    return '<span class="red-text badge">' + row.state.nama +
                                            '</span>';
                                } else {
                                    // return '<span class="green-text badge">' + row.state.nama +
                                    //         '</span>';
                                    return `<div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="`+hitung+`"  style="width:`+hitung+`%" aria-valuemin="0" aria-valuemax="100">`+hitung+`%</div>
                                    </div>
                                    <small class="text-muted">Selesai</small>`;
                                }
                            }
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row) {
                                var res = "";
                                if((row.count_noseri > 0 && row.count_perbaikan <= 0) || (row.count_part > 0 && row.count_kirim_noseri <= 0)){
                                    res += `<a href="/as/retur/edit/`+row.id+`"><button class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i> Edit</button></a> `;
                                }
                                res += `<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal"
                                                    id="detailmodal"><button type="button"
                                                        class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i>
                                                        Detail</button></a>`;
                                return res;
                            }
                        },
                    ],
                    columnDefs: [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0
                    }],
                    rowCallback: function (row, data) {
                        if ( data.jenis == "none" ) {
                            $(row).addClass('text-danger font-weight-bold');
                        }
                    },
                    order: [
                        [1, 'asc']
                    ],
                });

                no_kolom(showtable)

            $(document).on('click', "#detailmodal", function(event) {
                event.preventDefault();
                var rows = showtable.rows($(this).parents('tr')).data();
                var id = rows[0]['id'];
                $.ajax({
                    url: "/api/as/retur/detail",
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#detail_modal').modal("show");
                        $('#detail').html(result).show();
                        retur_detail(id);
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
            });

            function retur_detail(id){
                $.ajax({
                    url: "/api/as/retur/data_detail",
                    dataType: 'json',
                    type: 'GET',
                    data: {'id': id},
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        var jenis = "";
                        var karyawan_id = result.karyawan_id != null ? result.karyawan_id : '-';
                        $('.pic').removeClass('hide');
                        if (result.jenis == "peminjaman") {
                            jenis = '<span class="purple-text badge">Peminjaman</span>';
                        } else if (result.jenis == "komplain") {
                            jenis = '<span class="blue-text badge">Komplain</span>';
                        } else if (result.jenis == "service") {
                            jenis = '<span class="orange-text badge">Service</span>';
                        } else {
                            jenis = '<span class="red-text badge">Tanpa Status</span>';
                        }
                        $('#karyawan_id').html(karyawan_id+" <span class='text-muted'>"+result.telp_pic+"</span>");
                        const date = result.tgl_retur;
                        const [year, month, day] = date.split('-');
                        $('#no_pesanan').html(result.no_pesanan);
                        $('#customer_nama').html(result.customer);
                        $('#alamat').html(result.alamat);
                        $('#telepon').html(result.telp);

                        $('#no_retur').html(result.no_retur);
                        $('#tgl_retur').html(day+'-'+month+'-'+year);
                        $('#jenis').html(jenis);
                        $('#keterangan').html(result.keterangan);

                        show_detail_table(result.produk);
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(jqXHR, testStatus, error) {
                        console.log(error);
                        $('#loader').hide();
                    },
                    timeout: 8000
                })
                // $('#customer_nama').html()
            }

            function show_detail_table(produk){
                var barangtable = $('#barangtable').DataTable({
                    data: produk,
                    columns: [
                        {
                            data: null,
                            className: 'align-center'
                        },
                        {
                            data: "nama",
                            className: 'nowraps align-center',
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row) {
                                if (row.jenis == "Produk") {
                                    return '<span class="green-text badge">' + row.jenis +
                                            '</span>';
                                } else if (row.jenis == "Part") {
                                    return '<span class="yellow-text badge">' + row.jenis +
                                            '</span>';
                                }
                            }
                        },
                        {
                            data: "jumlah",
                            className: 'nowraps align-center',
                        },
                        {
                            data: "no_seri",
                            className: 'align-center',
                            render: "[, ]"
                        }
                    ],
                });
                no_kolom(barangtable);
            }

        })
    </script>
@endsection
