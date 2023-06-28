@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Perbaikan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Perbaikan</li>
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

            div.contentdiv {
                max-height: 400px;
                overflow-y: scroll;
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

            div.contentdiv {
            max-height: 300px;
            overflow-y: scroll;
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

            div.contentdiv {
                max-height: 400px;
                overflow-y: scroll;
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
                            <a href="{{ route('as.perbaikan.create') }}" type="button" class="btn btn-info float-right my-2"><i
                                    class="fas fa-plus"></i> Tambah</a>
                            <div class="table-responsive">
                                <table class="table table-hover" id="showtable" style="text-align: center; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Referensi</th>
                                            <th>Tgl Perbaikan</th>
                                            <th>No Retur</th>
                                            <th>Produk</th>
                                            <th>Customer</th>
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
                            <div id="modal-overlay" class="overlay"></div>
                            <div class="modal-header">
                                <h4 class="modal-title">Detail Perbaikan</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="detail">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="perbaikan_modal" role="dialog" aria-labelledby="perbaikan_modal" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content" style="margin: 10px">
                            <div class="modal-header">
                                <h4 class="modal-title">Penggantian No Seri</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="perbaikan">

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
            $('#showtable').DataTable();
            var noseritable = "";
            var produk_id = "";
            var produk_nama = "";
            function cust_image(cust_name){
                var cust = cust_name;
                var cust = cust.replace('.', '').replace('PT ', '').replace('CV ', '').replace('& ', '').replace('(','').replace(')', '');
                var init = cust.split(" ");
                var initial = "";
                for (var i = 0; i < init.length; i++) {
                    initial = initial + init[i].charAt(0);
                }
                var profileImage = $('#profileImage').text(initial);
            }
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
                        'url': '/as/perbaikan/data',
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
                            data: 'no_perbaikan',
                            className: 'nowraps align-center'
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row) {
                                return moment(new Date(row.tanggal).toString()).format('DD-MM-YYYY');
                            }
                        },
                        {
                            data: 'retur.no',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'produk',
                        },
                        {
                            data: 'customer.nama',
                            className: 'nowraps align-center'
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row) {
                                var perbaikan = parseInt(data.count_perbaikan);
                                var done = parseInt(data.count_perbaikan_karantina) + parseInt(data.count_perbaikan_non_karantina) + parseInt(data.count_perbaikan_pengganti);
                                var hitung = Math.floor((done / perbaikan) * 100);
                                if(hitung > 0){
                                    return `<div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="`+hitung+`"  style="width:`+hitung+`%" aria-valuemin="0" aria-valuemax="100">`+hitung+`%</div>
                                    </div>
                                    <small class="text-muted">Selesai</small>`;
                                }
                                else{
                                    return `<div class="progress">
                                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="100"  style="width:100%" aria-valuemin="0" aria-valuemax="100">0%</div>
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
                                var jumlah = parseInt(data.count_pengiriman) + parseInt(data.count_pengiriman_pengganti);
                                var perbaikan = parseInt(data.count_perbaikan) - parseInt(data.count_perbaikan_karantina);

                                res += `<a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" id="detailmodal"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a> `;
                                if(perbaikan > jumlah){
                                    res += `<a href="/as/perbaikan/edit/`+row.id+`"><button class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i> Edit</button></a>`;
                                }
                                return res;
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

            no_kolom(showtable)

            $(document).on('click', "#detailmodal", function(event) {
                $('#detail_modal').find('#modal-overlay').addClass('hide');
                var rows = showtable.rows($(this).parents('tr')).data();
                var id = rows[0]['id'];
                produk_id = rows[0]['produk_id'];
                produk_nama = rows[0]['produk'];
                event.preventDefault();
                $.ajax({
                    url: "/as/perbaikan/detail",
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#detail_modal').modal("show");
                        $('#detail').html(result).show();
                        cust_image('-');

                        $('#no_perbaikan').html(rows[0]['no_perbaikan']);
                        $('#tgl_perbaikan').html(rows[0]['tanggal']);
                        $('#karyawan_id').html(rows[0]['karyawan'].join(", "));
                        $('#produk_id').html(rows[0]['produk']);
                        $('#ket_perbaikan').html(rows[0]['keterangan']);

                        $('#customer_nama').html(rows[0]['customer'].nama);
                        $('#customer_alamat').html(rows[0]['customer'].alamat);
                        $('#customer_telp').html(rows[0]['customer'].telp);

                        $('#no_retur').html(rows[0]['retur'].no);
                        $('#tgl_retur').html(rows[0]['retur'].tgl);
                        $('#retur_jenis').html(rows[0]['retur'].jenis);
                        $('#retur_keterangan').html(rows[0]['retur'].keterangan);

                        noseri_table(rows[0]['id']);
                        part_table(rows[0]['id']);
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

            function select_noseri(id){
                $('#noseri_id').select2({
                    placeholder: 'Pilih Noseri',
                    minimumResultsForSearch: 2,
                    ajax: {
                        dataType: 'json',
                        theme: "bootstrap",
                        delay: 250,
                        type: 'GET',
                        url: '/api/noseri/pengganti/'+id,
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
                                        text: obj.noseri
                                    };
                                })
                            };
                        },
                    }
                })
            }

            $(document).on('click', '#donekarantina', function(){
                var rows = noseritable.rows($(this).parents('tr')).data();
                var id = rows[0]['id'];
                Swal.fire({
                    title: 'Barang Kembali?',
                    text: "Barang Kembali adalah Barang yang dikembalikan ke Gudang tanpa ada No Seri",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#d33',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Barang Kembali'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: "{{ route('as.perbaikan.done_karantina') }}",
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
                                        $('#noseritable').DataTable().ajax.reload();
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
                                'Batal melakukan pengembalian',
                                'error'
                            );
                    }
                })
            })

            $(document).on('hidden.bs.modal', '#perbaikan_modal', function(event) {
                $('#detail_modal').find('#modal-overlay').addClass('hide');
            });

            $(document).on('click', "#perbaikanmodal", function(event) {
                $('#detail_modal').find('#modal-overlay').removeClass('hide');
                var rows = noseritable.rows($(this).parents('tr')).data();
                var id = rows[0]['id'];
                var no_seri = rows[0]['no_seri'];
                $.ajax({
                    url: "/as/perbaikan/noseri",
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(result) {
                        $('#perbaikan_modal').modal("show");
                        $('#perbaikan').html(result).show();
                        select_noseri(produk_id);
                        $('#noseri_perbaikan_id').val(id);
                        $('#nama_produk').html(produk_nama);
                        $('#no_seri').html(no_seri);
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

            $(document).on('submit', '#form-pengganti-seri', function(e) {
                e.preventDefault();
                $('#btnsubmit').attr('disabled', true);
                var noseri_id = $('#noseri_id').val();
                var noseri_perbaikan_id = $('#noseri_perbaikan_id').val();
                var action = $(this).attr('action');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: action,
                    data: {
                        'noseri_id': noseri_id,
                        'noseri_perbaikan_id': noseri_perbaikan_id
                    },
                    beforeSend: function() {
                        swal.fire({
                            title: 'Sedang Proses',
                            html: 'Loading...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading()
                            }
                        })
                    },
                    success: function(response) {
                        $('#btnsubmit').removeAttr('disabled');
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil menambahkan Pengganti',
                                'success'
                            ).then(function() {
                                location.reload(true);
                            });
                            $("#perbaikanmodal").modal('hide');
                            $('#noseritable').DataTable().ajax.reload();

                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal menambahkan Pengganti',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error, response) {
                        alert(error);
                    }
                });
            });

            function noseri_table(id){
                noseritable = $('#noseritable').DataTable({
                    destroy: true,
                    processing: true,
                    ajax: {
                        'url': '/api/as/perbaikan/detail/noseri/'+id,
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
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row) {
                                if (row.tindak_lanjut == "karantina") {
                                    if(row.noseri_pengganti_id != null){
                                        return row.no_seri+'<div><small class="text-success">Digantikan Noseri '+row.noseri_pengganti_id+'</small></div>';
                                    }else{
                                        return row.no_seri;
                                    }
                                }
                                else {
                                    return row.no_seri;
                                }
                            }
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row) {
                                if (row.tindak_lanjut == "perbaikan") {
                                    return '<span class="yellow-text badge">' + row.tindak_lanjut[0].toUpperCase() + row.tindak_lanjut.substring(1) +
                                            '</span>';
                                } else if (row.tindak_lanjut == "karantina") {
                                    return '<span class="red-text badge">' + row.tindak_lanjut[0].toUpperCase() + row.tindak_lanjut.substring(1) +
                                            '</span>';
                                } else {
                                    return '<span class="secondary-text badge">' + row.tindak_lanjut[0].toUpperCase() + row.tindak_lanjut.substring(1) +
                                            '</span>';
                                }
                            }
                        },
                        {
                            data: 'status',
                        },
                        {
                            data: null,
                            className: 'nowraps align-center',
                            render: function(data, type, row) {
                                if (row.tindak_lanjut == "karantina" && row.status != "Done") {
                                    return '<a data-toggle="perbaikanmodal" data-target="#perbaikanmodal" class="perbaikanmodal" id="perbaikanmodal"><button type="button" class="btn btn-outline-warning btn-sm"><i class="fas fa-pencil"></i> Input No Seri</button></a> '+
                                    '<button type="button" class="btn btn-outline-primary btn-sm" id="donekarantina"><i class="fas fa-check"></i> Masuk Gudang</button>';
                                } else{
                                    return '-';
                                }
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
                no_kolom(noseritable);
            }
            function part_table(id){
                var parttable = $('#parttable').DataTable({
                    destroy: true,
                    processing: true,
                    ajax: {
                        'url': '/api/as/perbaikan/detail/part_pengganti/'+id,
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
                            data: 'part',
                            className: 'nowraps align-center'
                        },
                        {
                            data: 'jumlah',
                            className: 'nowraps align-center'
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
                no_kolom(parttable);
            }
        })
    </script>
@endsection
