@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Pengiriman</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Pengiriman</li>
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
        #profileImage {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #4682B4;
            font-size: 12px;
            color: #fff;
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
                            <a href="{{ route('as.pengiriman.create') }}" type="button" class="btn btn-info float-right my-2"><i
                                    class="fas fa-plus"></i> Tambah</a>
                            <div class="table-responsive">
                                <table class="table table-hover" id="showtable" style="text-align: center; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Surat Jalan</th>
                                            <th>Tgl Pengiriman</th>
                                            <th>No Retur</th>
                                            <th>Customer</th>
                                            <th>Pengirim/Ekspekdisi</th>
                                            <th>Status</th>
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
                    'url': '/api/as/pengiriman/data',
                    'dataType': 'json',
                    'type': 'GET',
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
                        data: 'no_pengiriman',
                        className: 'nowraps align-center'
                    },
                    {
                        data: null,
                        className: 'nowraps align-center',
                        render: function(data, type, row) {
                            if(data.tanggal != null){
                                return moment(new Date(row.tanggal).toString()).format('DD-MM-YYYY');
                            }else{
                                return data.tanggal;
                            }
                        }
                    },
                    {
                        data: 'no_retur',
                        className: 'nowraps align-center'
                    },
                    {
                        data: 'customer_id',
                        className: 'nowraps align-center'
                    },
                    {
                        data: null,
                        className: 'nowraps align-center',
                        render: function(data, type, row) {
                            if (row.ekspedisi_id != null) {
                                return row.ekspedisi_id;
                            } else {
                                return row.pengirim;
                            }
                        }
                    },
                    {
                        data: null,
                        className: 'nowraps align-center',
                        render: function(data, type, row) {
                            if (row.status == "Belum Terkirim") {
                                return '<span class="red-text badge">' + row.status +'</span>';
                            } else {
                                return '<span class="green-text badge">' + row.status +'</span>';
                            }
                        }
                    },
                    {
                        data: null,
                        className: 'nowraps align-center',
                        render: function(data, type, row) {
                            var data = '';
                            if(row.no_pengiriman == null){
                                data = `<a href="/as/pengiriman/edit/`+ row.id +`"><button type="button" class="btn btn-warning btn-sm"><i class="fas fa-pencil"></i> Edit</button></a> `;
                            }
                            data += `<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" id="detailmodal"><button type="button" class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i> Detail</button></a>`;
                            return data;
                        }
                    },
                ],
                columnDefs: [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }, ],
                order: [
                    [1, 'asc']
                ],
            });

            no_kolom(showtable);

            $(document).on('click', '#kirimbutton', function(){
                var rows = showtable.rows($(this).parents('tr')).data();
                var id = rows[0]['id'];
                var ekspedisi_id = rows[0]['ekspedisi_id'];
                if(ekspedisi_id == null){
                    Swal.fire({
                        title: 'Kirim Barang?',
                        text: "Setelah klik Kirim Barang, Anda tidak bisa merubahnya kembali",
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Kirim Barang'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: "POST",
                                url: "{{ route('as.pengiriman.send') }}",
                                data: {'id': id },
                                beforeSend: function() {
                                    $('#loader').show();
                                },
                                success: function(response) {
                                    if (response['res'] == "success") {
                                        swal.fire(
                                            'Berhasil',
                                            response['msg'],
                                            'success'
                                        );
                                        $('#showtable').DataTable().ajax.reload();
                                    } else if (response['res'] == "error") {
                                        swal.fire(
                                            'Gagal',
                                            response['msg'],
                                            'error'
                                        );
                                    }
                                },
                                complete: function() {
                                    $('#loader').hide();
                                },
                                error: function(xhr, status, error) {
                                    alert(error);
                                }
                            });
                        }
                        else{
                            swal.fire(
                                'Batal',
                                'Batal melakukan pengiriman',
                                'error'
                            );
                        }
                    })
                }
                else{
                    const { value: resi } = Swal.fire({
                        title: 'No Resi',
                        input: 'text',
                        inputPlaceholder: 'Masukkan No Resi dari Pengiriman ini',
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Kirim'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: "POST",
                                url: "{{ route('as.pengiriman.send') }}",
                                data: {'id': id, 'resi': result.value},
                                beforeSend: function() {
                                    $('#loader').show();
                                },
                                success: function(response) {
                                    if (response['res'] == "success") {
                                        swal.fire(
                                            'Berhasil',
                                            response['msg'],
                                            'success'
                                        );
                                        $('#showtable').DataTable().ajax.reload();
                                    } else if (response['res'] == "error") {
                                        swal.fire(
                                            'Gagal',
                                            response['msg'],
                                            'error'
                                        );
                                    }
                                },
                                complete: function() {
                                    $('#loader').hide();
                                },
                                error: function(xhr, status, error) {
                                    alert(error);
                                }
                            });
                        }
                        else{
                            swal.fire(
                                'Batal',
                                'Batal melakukan pengiriman',
                                'error'
                            );
                        }
                    })
                }
            });



            function cust_image(cust_name){
                var cust = cust_name;
                var cust = cust.replace('.', '').replace('PT ', '').replace('CV ', '').replace('& ', '').replace('(',
                    '').replace(')', '');
                var init = cust.split(" ");
                var initial = "";
                for (var i = 0; i < init.length; i++) {
                    initial = initial + init[i].charAt(0);
                }
                var profileImage = $('#profileImage').text(initial);
            }
            $(document).on('click', "#detailmodal", function(event) {
                var rows = showtable.rows($(this).parents('tr')).data();
                var id = rows[0]['id'];
                event.preventDefault();
                $.ajax({
                    url: "/as/pengiriman/detail",
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#detail_modal').modal("show");
                        $('#detail').html(result).show();
                        pengiriman_detail(id);
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
            function noseri_table(produk){
                if(produk != null){
                var noseritable = $('#noseritable').DataTable({
                    destroy: true,
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
                            data: "noseri",
                            className: 'nowraps align-center',
                        },
                    ],
                });

                no_kolom(noseritable);
                }
                else{
                    $('#noseritable').DataTable({
                        destroy: true
                    });
                }
            }
            function part_table(part){
                if(part != null){
                    var parttable = $('#parttable').DataTable({
                        destroy: true,
                        data: part,
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
                                data: "jumlah",
                                className: 'nowraps align-center',
                            },
                        ],
                    });
                    no_kolom(parttable);
                }
                else{
                    $('#parttable').DataTable({
                        destroy: true
                    });
                }
            }


            // var showtable = $('#showtable').DataTable({
            //         destroy: true,
            //         processing: true,
            //         // serverSide: true,
            //         ajax: {
            //             'url': '/api/as/retur/data',
            //             'dataType': 'json',
            //             'type': 'POST',
            //             'headers': {
            //                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
            //             }
            //         },
            //         language: {
            //             processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            //         },
            //         columns: [{
            //                 data: null,
            //             },
            //             {
            //                 data: 'no_retur',
            //                 className: 'nowraps align-center'
            //             },
            //             {
            //                 data: null,
            //                 className: 'nowraps align-center',
            //                 render: function(data, type, row) {
            //                     if (row.pesanan_id != null) {
            //                         return row.pesanan.no_po;
            //                     } else {
            //                         return row.no_pesanan;
            //                     }
            //                 }
            //             },
            //             {
            //                 data: null,
            //                 className: 'nowraps align-center',
            //                 render: function(data, type, row) {
            //                     return moment(new Date(row.tgl_retur).toString()).format(
            //                             'DD-MM-YYYY');
            //                 }
            //             },
            //             {
            //                 data: null,
            //                 className: 'nowraps align-center',
            //                 render: function(data, type, row) {
            //                     if (row.jenis == "peminjaman") {
            //                         return '<span class="purple-text badge">' + row.jenis[0].toUpperCase() + row.jenis.substring(1) +
            //                                 '</span>';
            //                     } else if (row.jenis == "komplain") {
            //                         return '<span class="blue-text badge">' + row.jenis[0].toUpperCase() + row.jenis.substring(1) +
            //                                 '</span>';
            //                     } else if (row.jenis == "service") {
            //                         return '<span class="orange-text badge">' + row.jenis[0].toUpperCase() + row.jenis.substring(1) +
            //                                 '</span>';
            //                     }
            //                 }
            //             },
            //             {
            //                 data: null,
            //                 className: 'nowraps align-center',
            //                 render: function(data, type, row) {
            //                     return row.customer.nama;
            //                 }
            //             },
            //             {
            //                 data: null,
            //                 className: 'nowraps align-center',
            //                 render: function(data, type, row) {
            //                     if (row.state.id == "4") {
            //                         return '<span class="red-text badge">' + row.state.nama +
            //                                 '</span>';
            //                     } else {
            //                         return '<span class="green-text badge">' + row.state.nama +
            //                                 '</span>';
            //                     }
            //                 }
            //             },
            //             {
            //                 data: null,
            //                 className: 'nowraps align-center',
            //                 render: function(data, type, row) {
            //                     return `<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal"
            //                                         id="detailmodal"><button type="button"
            //                                             class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i>
            //                                             Detail</button></a>`;
            //                 }
            //             },
            //         ],
            //         columnDefs: [{
            //             "searchable": false,
            //             "orderable": false,
            //             "targets": 0
            //         }, ],
            //         order: [
            //             [1, 'asc']
            //         ],
            // });

            // no_kolom(showtable)

            // $(document).on('click', "#detailmodal", function(event) {
            //     event.preventDefault();
            //     var rows = showtable.rows($(this).parents('tr')).data();
            //     var id = rows[0]['id'];
            //     $.ajax({
            //         url: "/api/as/retur/detail",
            //         beforeSend: function() {
            //             $('#loader').show();
            //         },
            //         success: function(result) {
            //             $('#detail_modal').modal("show");
            //             $('#detail').html(result).show();
            //             retur_detail(id);

            //         },
            //         complete: function() {
            //             $('#loader').hide();
            //         },
            //         error: function(jqXHR, testStatus, error) {
            //             console.log(error);
            //             $('#loader').hide();
            //         },
            //         timeout: 8000
            //     })
            // });

            function pengiriman_detail(id){
                $.ajax({
                    url: "/api/as/pengiriman/detail",
                    dataType: 'json',
                    type: 'GET',
                    data: {'id': id},
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        var date_pengiriman = "";
                        if(result.tgl_pengiriman){
                            var date_pengiriman = result.tgl_pengiriman;
                            var [year, month, day] = date_pengiriman.split('-');
                        }

                        var date_retur = result.tgl_retur;
                        var [year_r, month_r, day_r] = date_retur.split('-');

                        $('#no_pengiriman').html(result.no_pengiriman ? result.no_pengiriman : '-');
                        $('#tgl_pengiriman').html(date_pengiriman != "" ? day+'-'+month+'-'+year : '-');
                        $('#ekspedisi_id').html(result.ekspedisi_id ? result.ekspedisi_id : '-');
                        $('#biaya_kirim').html(result.biaya_kirim ? result.biaya_kirim : '-');
                        $('#no_resi').html(result.no_resi);

                        $('#nama_penerima').html(result.nama_penerima);
                        $('#alamat_penerima').html(result.alamat_penerima);
                        $('#telp_penerima').html(result.telp_penerima != null ? result.telp_penerima : '-');
                        cust_image(result.customer_id);
                        $('#customer').html(result.customer_id);
                        $('#alamat').html(result.alamat);
                        $('#telp').html(result.telp);

                        $('#no_retur').html(result.no_retur);
                        $('#tgl_retur').html(day_r+'-'+month_r+'-'+year_r);
                        $('#jenis_retur').html(result.jenis);
                        $('#keterangan').html(result.keterangan);


                        noseri_table(result.produk);
                        part_table(result.part);
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
            }

            // function show_detail_table(produk){
            //     var barangtable = $('#barangtable').DataTable({
            //         data: produk,
            //         columns: [
            //             {
            //                 data: null,
            //                 className: 'align-center'
            //             },
            //             {
            //                 data: "nama",
            //                 className: 'nowraps align-center',
            //             },
            //             {
            //                 data: null,
            //                 className: 'nowraps align-center',
            //                 render: function(data, type, row) {
            //                     if (row.jenis == "Produk") {
            //                         return '<span class="green-text badge">' + row.jenis +
            //                                 '</span>';
            //                     } else if (row.jenis == "Part") {
            //                         return '<span class="yellow-text badge">' + row.jenis +
            //                                 '</span>';
            //                     }
            //                 }
            //             },
            //             {
            //                 data: "jumlah",
            //                 className: 'nowraps align-center',
            //             },
            //             {
            //                 data: "no_seri",
            //                 className: 'align-center',
            //                 render: "[, ]"
            //             }
            //         ],
            //     });
            //     no_kolom(barangtable);
            // }

        })
    </script>
@endsection
