@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
    <style>
        .nomor-so {
            background-color: #717FE1;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 18px
        }

        .nomor-akn {
            background-color: #DF7458;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 18px
        }

        .nomor-po {
            background-color: #85D296;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 18px
        }

        .instansi {
            background-color: #36425E;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 18px
        }
    </style>
    <input type="hidden" name="" id="auth" value="{{ Auth::user()->Karyawan->divisi_id }}">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Sales Order</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Card -->
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered" id="gudang-barang">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor SO</th>
                                        <th>Customer</th>
                                        <th>Batas Transfer</th>
                                        <th>Status</th>
                                        <th>Action</th>
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



    <div class="modal fade" id="viewProdukModal" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row row-cols-2">
                                        {{-- col --}}
                                        <div class="col"> <label for="">Nomor SO</label>
                                            <div class="card nomor-so">
                                                <div class="card-body">
                                                    <span id="so">89798797856456</span>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- col --}}
                                        <div class="col"> <label for="">Nomor AKN</label>
                                            <div class="card nomor-akn">
                                                <div class="card-body">
                                                    <span id="akn">89798797856456</span>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- col --}}
                                        <div class="col"> <label for="">Nomor PO</label>
                                            <div class="card nomor-po">
                                                <div class="card-body">
                                                    <span id="po">89798797856456</span>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- col --}}
                                        <div class="col"> <label for="">Customer</label>
                                            <div class="card instansi">
                                                <div class="card-body">
                                                    <span id="instansi">RS. Dr. Soetomo</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped" id="view-produk">
                                        <thead>
                                            <tr>
                                                <th>x</th>
                                                <th>Paket</th>
                                                <th>Nama Produk</th>
                                                <th>Jumlah</th>
                                                {{-- <th>Tipe</th> --}}
                                                <th>Merk</th>
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
        </div>
    </div>
@stop

@section('adminlte_js')
    <script>
        $(document).ready(function() {
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

            $('#gudang-barang').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/api/prd/so',
                    type: 'post',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'so',
                        name: 'so'
                    },
                    {
                        data: 'nama_customer',
                        name: 'nama_customer'
                    },
                    {
                        data: 'batas_out',
                        name: 'batas_out'
                    },
                    {
                        data: 'status_prd',
                        name: 'status_prd'
                    },
                    {
                        data: 'button_prd',
                        name: 'button_prd'
                    },
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                }
            });
        });

        $(document).on('click', '.detailproduk', function() {
            var x = $(this).data('value');
            console.log(x);
            var id = $(this).data('id');
            console.log(id);

            $.ajax({
                url: "/api/tfp/header-so/" + id + "/" + x,
                success: function(res) {
                    console.log(res);
                    $('span#so').text(res.so);
                    $('span#po').text(res.po);
                    $('span#akn').text(res.akn);
                    $('span#instansi').text(res.customer);
                }
            });

            $('#view-produk').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "/api/tfp/detail-so/" + id + "/" + x,
                },
                columns: [{
                        data: 'detail_pesanan_id'
                    },
                    {
                        data: 'paket'
                    },
                    {
                        data: 'produk',
                        name: 'produk'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    // { data: 'tipe', name: 'tipe'},
                    {
                        data: 'merk',
                        name: 'merk'
                    },
                    {
                        data: 'status_prd',
                        name: 'status_prd'
                    },
                ],
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    var last = null;

                    api.column(0, {
                        page: 'current'
                    }).data().each(function(group, i) {

                        if (last !== group) {
                            var rowData = api.row(i).data();

                            $(rows).eq(i).before(
                                '<tr class="table-dark text-bold"><td style="display:none;">' +
                                group + '</td><td colspan="4">' + rowData.paket +
                                '</td></tr>'
                            );
                            last = group;
                        }
                    });
                },
                "columnDefs": [{
                        "targets": [0],
                        "visible": false
                    },
                    {
                        "targets": [1],
                        "visible": false
                    },
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                }
            });

            $('#viewProdukModal').modal('show');
        })


        $('.add-produk').DataTable({
            'columnDefs': [{
                'targets': 0,
                'checkboxes': {
                    'selectRow': true
                }
            }],
            'select': {
                'style': 'multi'
            },
            'order': [
                [1, 'asc']
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
    </script>
@stop
