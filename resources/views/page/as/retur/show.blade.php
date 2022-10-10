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
                    @if (Auth::user()->divisi_id == '8')
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

        .removeshadow{
            box-shadow:none;
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
                        <a href="{{route('as.retur.create')}}" type="button" class="btn btn-info float-right my-2"><i class="fas fa-plus"></i> Tambah</a>
                        <div class="table-responsive">
                            <table class="table table-hover" id="showtable" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Retur</th>
                                        <th>No Ref Penjualan</th>
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
                                        <td><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>RET/0012/02/1293</td>
                                        <td>AKN-P2207-102345</td>
                                        <td>20-02-2022</td>
                                        <td><span class="badge orange-text">Service</span></td>
                                        <td>PT. Emiindo Jaya Bersama</td>
                                        <td><span class="badge red-text">Belum Diproses</span></td>
                                        <td><a data-toggle="detailmodal" data-target="#detailmodal" class="detailmodal" id="detailmodal"><button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button></a></td>
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
    $(function(){
        $('#showtable').DataTable();

        $(document).on('click', "#detailmodal", function(event){
            event.preventDefault();
            $.ajax({
                url: "/api/as/retur/detail",
                beforeSend: function() {
                    $('#loader').show();
                },
                success: function(result) {

                    $('#detail_modal').modal("show");
                    $('#detail').html(result).show();
                    $('#barangtable').DataTable();
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
    })
</script>
@endsection
