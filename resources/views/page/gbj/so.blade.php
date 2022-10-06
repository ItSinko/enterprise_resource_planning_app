@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    .nomor-so{
        background-color: #717FE1;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .nomor-akn{
        background-color: #DF7458;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .nomor-po{
        background-color: #85D296;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .instansi{
        background-color: #36425E;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

</style>
<input type="hidden" name="" id="auth" value="{{ Auth::user()->divisi_id }}">
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
            @if(Auth::user()->divisi_id !== 31)
          <h1 class="m-0">Daftar Sales Order</h1>
          @else
          <h1 class="m-0">Daftar Batal PO</h1>
            @endif
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
                        <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                            @if(Auth::user()->divisi_id !== 31)
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-proses_kirim-tab" data-toggle="pill" href="#pills-proses_kirim" role="tab" aria-controls="pills-proses_kirim" aria-selected="true">Dalam Proses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-selesai_kirim-tab" data-toggle="pill" href="#pills-selesai_kirim" role="tab" aria-controls="pills-selesai_kirim" aria-selected="false">Sudah Proses</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" id="pills-batal_po-tab" data-toggle="pill" href="#pills-batal_po" role="tab" aria-controls="pills-batal_po" aria-selected="false">Batal Proses</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" id="pills-rekap_produk-tab" data-toggle="pill" href="#pills-rekap_produk" role="tab" aria-controls="pills-rekap_produk" aria-selected="false">Permintaan Barang</a>
                            </li>
                            @endif
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            @if(Auth::user()->divisi_id !== 31)
                            <div class="tab-pane fade show active" id="pills-proses_kirim" role="tabpanel" aria-labelledby="pills-proses_kirim-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" style="width: 100%" id="belum-dicek">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor SO</th>
                                                        <th>Nomor PO</th>
                                                        <th>Customer</th>
                                                        <th>Batas Transfer</th>
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
                            <div class="tab-pane fade show" id="pills-selesai_kirim" role="tabpanel" aria-labelledby="pills-selesai_kirim-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" style="width: 100%" id="sudah-dicek">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor SO</th>
                                                        <th>Nomor PO</th>
                                                        <th>Customer</th>
                                                        <th>Batas Transfer</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="pills-batal_po" role="tabpanel" aria-labelledby="pills-batal_po-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" style="width: 100%" id="batal-po">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor SO</th>
                                                        <th>Nomor PO</th>
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
                            <div class="tab-pane fade show" id="pills-rekap_produk" role="tabpanel" aria-labelledby="pills-rekap_produk-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" style="width: 100%" id="rekap-produk">
                                                <thead>
                                                    <tr align="center">
                                                        <th rowspan="2">No</th>
                                                        <th rowspan="2">Nama Produk</th>
                                                        <th colspan="2">Permintaan</th>
                                                        <th rowspan="2">Jumlah Transfer</th>
                                                        <th rowspan="2">Aksi</th>
                                                    </tr>
                                                    <tr align="center">
                                                        <th>Total</th>
                                                        <th>Sisa</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="tab-pane fade show active" id="pills-batal_po" role="tabpanel" aria-labelledby="pills-batal_po-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" style="width: 100%" id="batal-po">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor SO</th>
                                                        <th>Nomor PO</th>
                                                        <th>Customer</th>
                                                        <th>Batas Transfer</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>SO-00001</td>
                                                        <td>PO-00001</td>
                                                        <td>PT. ABC</td>
                                                        <td>20 Desember 2019</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-success button_batal"><i class="fas fa-eye"></i> Detail</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addProdukModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
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
                    <form action="" method="post">
                        <input type="hidden" name="pesanan_id" id="ids">
                        <input type="hidden" name="userid" id="userid" value="{{ Auth::user()->id }}">
                        <div class="card">
                            <div class="card-header">
                                <div class="row row-cols-2">
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor SO</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span id="soo"></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor AKN</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="aknn"></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor PO</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="poo"></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Customer</label>
                                        <div class="card instansi">
                                            <div class="card-body">
                                                <span id="instansii"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped add-produk">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>ID</th>
                                            <th><input type="checkbox" name="" id="head-cb-so"></th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
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
                {{-- Tambahkan DataTable --}}

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnDelete">Hapus</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" id="btnSave">Simpan</button>
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
                                                <span id="so"></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor AKN</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="akn"></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Nomor PO</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po"></span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- col --}}
                                    <div class="col"> <label for="">Instansi</label>
                                        <div class="card instansi">
                                            <div class="card-body">
                                                <span id="instansi"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped" id="view-produk">
                                    <thead>
                                        <tr>
                                            <th>Paket</th>
                                            <th>Paket</th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
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

<div class="modal fade" id="viewDetailBrg" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
<div class="modal-dialog modal-xl">
    <div class="modal-content">
    <div class="modal-header">
        <h5 id="titlebrg"></h5>
        {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button> --}}
    </div>
    <div class="modal-body">
        <div class="card">
            <div class="card-header">
                <div class="row row-cols-3">
                    {{-- col --}}
                    <div class="col"> <label for="">Total Permintaan</label>
                        <div class="card nomor-so">
                            <div class="card-body">
                                <span id="total_tf"></span>
                            </div>
                        </div>
                    </div>
                    {{-- col --}}
                    <div class="col"> <label for="">Belum Terpenuhi</label>
                        <div class="card nomor-akn">
                            <div class="card-body">
                                <span id="belum_tf"></span>
                            </div>
                        </div>
                    </div>
                    {{-- col --}}
                    <div class="col"> <label for="">Sudah Terpenuhi</label>
                        <div class="card nomor-po">
                            <div class="card-body">
                                <span id="sudah_tf"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table table-striped" id="reakpdetail-produk">
                    <thead>
                        <tr align="center">
                            <th rowspan="2" style="vertical-align : middle;text-align:center;">No</th>
                            <th colspan="2">Nomor</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;">Customer</th>
                            <th colspan="2">Jumlah</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;">Aksi</th>
                        </tr>
                        <tr align="center">
                            <th>Sales Order</th>
                            <th>Purchase Order</th>
                            <th>Pesanan</th>
                            <th>Terkirim</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary close1">Close</button>
      </div>
</div>
</div>

<div class="modal fade" id="viewDetailBrgSO" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <div class="modal-header">
            {{-- <h5 id="titleSO"></h5> --}}
            {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button> --}}
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
                                            <span id="nosoo"></span>
                                        </div>
                                    </div>
                                </div>
                                {{-- col --}}
                                <div class="col"> <label for="">Nomor AKN</label>
                                    <div class="card nomor-akn">
                                        <div class="card-body">
                                            <span id="noakn"></span>
                                        </div>
                                    </div>
                                </div>
                                {{-- col --}}
                                <div class="col"> <label for="">Nomor PO</label>
                                    <div class="card nomor-po">
                                        <div class="card-body">
                                            <span id="nopoo"></span>
                                        </div>
                                    </div>
                                </div>
                                {{-- col --}}
                                <div class="col"> <label for="">Instansi</label>
                                    <div class="card instansi">
                                        <div class="card-body">
                                            <span id="nminstansi"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="view-produk1">
                                <thead>
                                    <tr>
                                        <th>Paket</th>
                                        <th>Paket</th>
                                        <th>Produk</th>
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
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close2">Close</button>
          </div>
    </div>
</div>

{{-- Modal Batal --}}
<div class="modal fade" id="pesananBatal" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="margin: 10px">
            <div class="modal-header bg-navy">
                <h4 class="modal-title">Pesanan Batal</h4>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row filter">
                    <div class="col-12">
                        <div class="card card-navy card-outline card-tabs">
                            <div class="card-header p-0 pt-1 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tabs-detail-tab" data-toggle="pill" href="#tabs-detail"
                                            role="tab" aria-controls="tabs-detail" aria-selected="true">Informasi Umum</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tabs-produk-tab" data-toggle="pill" href="#tabs-produk" role="tab"
                                            aria-controls="tabs-produk" aria-selected="false">Detail Pembatalan</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-three-tabContent">
                                    <div class="tab-pane fade active show" id="tabs-detail" role="tabpanel" aria-labelledby="tabs-detail-tab">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="info-box bg-maroon">
                                                    <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                                                    <div class="info-box-content">
                                                    <span class="info-box-text">No SO</span>
                                                    <span class="info-box-number" id="noso">SO 1234</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="info-box bg-warning">
                                                    <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                                                    <div class="info-box-content">
                                                    <span class="info-box-text">No PO </span>
                                                    <span class="info-box-number" id="nopo">PO 1234</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="info-box bg-olive">
                                                    <span class="info-box-icon"><i class="far fa-user"></i></span>
                                                    <div class="info-box-content">
                                                    <span class="info-box-text">Nama Customer</span>
                                                    <span class="info-box-number" id="cust">PT Emiindo Jaya</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="info-box bg-indigo" style="box-shadow: none">
                                                    <span class="info-box-icon"><i class="far fa-calendar"></i></span>
                                                    <div class="info-box-content">
                                                    <span class="info-box-text">Tanggal Batal</span>
                                                    <span class="info-box-number" id="tglbatal">18 September 2022</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tabs-produk" role="tabpanel" aria-labelledby="tabs-produk-tab">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="info-box bg-danger">
                                                    <span class="info-box-icon"><i class="fas fa-exclamation-triangle fa-fw"></i></span>
                                                    <div class="info-box-content">
                                                    <span class="info-box-text" id="spanAlasan">Alasan Batal</span>
                                                    <span class="info-box-number"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card removeshadow">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="produktable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Produk</th>
                                                <th>Nama Produk</th>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                @if(Auth::user()->divisi_id !== 31)
                <button type="button" class="btn btn-dark btn-sm float-right" id="btnProsesBatal"><i class="fas fa-check"></i> Terima</button>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
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
    $(document).ready(function () {
        let auth = $('#auth').val();
        $('#head-cb-so').prop('checked', false);
        $('.addProduk').click(function (e) {
            $('#addProdukModal').modal('show');
        });
        $('.viewProduk').click(function (e) {
            $('#viewProdukModal').modal('show');
        });

        $("#head-cb-so").on('click', function () {
            var isChecked = $("#head-cb-so").prop('checked')
            $('.cb-child-so').prop('checked', isChecked)
        });

        if (auth !== 31) {
            $('#belum-dicek').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/api/tfp/belum-dicek',
                    beforeSend : function(xhr){
                        xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    { data: 'so', name: 'so'},
                    {data: 'po'},
                    { data: 'nama_customer', name: 'nama_customer'},
                    { data: 'batas_out', name: 'batas_out'},
                    {data: 'logs'},
                    { data: 'action', name: 'action'},
                ],
                "language": {
                    // url: "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json",
                    processing: "<span class='fa-stack fa-md'>\n\
                                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                                    </span>&emsp;Mohon Tunggu ...",
                },
                "columnDefs": [
                    {
                        "targets": [6],
                        "visible": document.getElementById('auth').value == '2' ? false : true,
                        "width": "20%",
                    },
                    { "width": "10%", "targets": 5 }
                ]
            });

            $('#sudah-dicek').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                ajax: {
                    url: '/api/tfp/sudah-dicek',
                    beforeSend : function(xhr){
                        xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    { data: 'so', name: 'so'},
                    {data: 'po'},
                    { data: 'nama_customer', name: 'nama_customer'},
                    { data: 'batas_out', name: 'batas_out'},
                    {data: 'logs'},
                    { data: 'action', name: 'action'},
                ],
                "language": {
                    // "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                    processing: "<span class='fa-stack fa-md'>\n\
                                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                                    </span>&emsp;Mohon Tunggu ...",
                },
                "columnDefs": [
                    {
                        "targets": [6],
                        "visible": document.getElementById('auth').value == '2' ? false : true,
                        "width": "20%",
                    },
                    { "width": "10%", "targets": 5 }
                ]
            });
        }

        $('#rekap-produk').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            ordering: false,
            ajax: {
                url: '/api/v2/gbj/get_rekap_so_produk',
                type: "get",
                // beforeSend : function(xhr){
                //     xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                // }
            },
            columns: [
                { data: 'DT_RowIndex',},
                { data: 'produkk'},
                {data: 'permintaan'},
                {data: 'sisa'},
                {data: 'transfer'},
                { data: 'aksi'},
            ],
            "language": {
                // "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                processing: "<span class='fa-stack fa-md'>\n\
                                        <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                                </span>&emsp;Mohon Tunggu ...",
            }
        });

        $('#batal-po').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            ajax: {
                url: '/api/v2/gbj/so_batal',
                type: 'post',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'so', name: 'so'},
                {data: 'no_po'},
                { data: 'nama_customer', name: 'nama_customer'},
                // { data: 'batas_out', name: 'batas_out'},
                {data: 'logs'},
                { data: 'aksi', name: 'aksi'},
            ],
            "language": {
                // "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                processing: "<span class='fa-stack fa-md'>\n\
                                        <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                                </span>&emsp;Mohon Tunggu ...",
            },
        })
    });

    var id = '';
    $(document).on('click', '.editmodal', function(e) {
        $('#variasiid').select2({})
        var x = $(this).data('value');
        console.log(x);
        id = $(this).data('id');
        console.log(id);
        $.ajax({
            url: "/api/tfp/header-so/" +id+"/"+x,
            success: function(res) {
                console.log(res);
                $('span#soo').text(res.so);
                $('span#poo').text(res.po);
                $('span#aknn').text(res.akn);
                $('span#instansii').text(res.customer);
            }
        });

        var table = $('.add-produk').DataTable({
            destroy: true,
            autoWidth: false,
            bPaginate: false,
            "scrollY": 300,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x
            },
            "columns": [
                { "data": "detail_pesanan_id" },
                { "data": "paket" },
                { "data": "ids" },
                { "data": "produk" },
                { "data": "qty" },
                { "data": "status" },
            ],
            "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(0, {page:'current'} ).data().each( function ( group, i ) {

                if (last !== group) {
                    var rowData = api.row(i).data();
                    $(rows).eq(i).before(
                    '<tr class="table-dark text-bold"><td style="display:none;">'+group+'</td><td colspan="4">' + rowData.paket + '</td></tr>'
                );
                    last = group;
                }
            });
        },
            "columnDefs":[
                {"targets": [0], "visible": false},
                {"targets": [1], "visible": false},
            ],
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        // tab.column(2).data().sum();
        $('#addProdukModal').modal('show');
    })
    let so = {};
    let so_dpp = {};
    $(document).on('click', '#btnSave', function(e) {
        e.preventDefault();
        let ids = {};
        let allVals = [];

        $(".cb-child-so:checked").each(function() {
            allVals.push($(this).val())
            if (ids[$(this).val()] === undefined){
                ids[$(this).val()] = []
                ids[$(this).val()].push($(this).parent().next().children()[0].value)
                ids[$(this).val()].push($(this).parent().next().next().children()[0].value)
            }
            else {
                ids[$(this).val()].push($(this).parent().next().next().children()[0].value)
            }

        });

        if (allVals.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Tidak Ada yang Dipilih',
            })
        } else {
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).prop('disabled', true);
                    Swal.fire({
                        title: 'Please wait',
                        text: 'Data is transferring...',
                        allowOutsideClick: false,
                        showConfirmButton: false
                    });
                    Swal.fire(
                        'Sukses!',
                        'Data Berhasil Disimpan',
                        'success'
                    )
                    $.ajax({
                        url: "/api/so/cek",
                        type: "post",
                        data: {
                            pesanan_id : id,
                            userid: $('#userid').val(),
                            data: ids,
                        },
                        success: function(res) {
                            location.reload();
                            console.log(res);
                        }
                    })
                }
            })
        }
    })

    $(document).on('click', '#btnDelete', function(e){
        e.preventDefault();
        let ids_del = {};
        let allVals_del = [];

        $(".cb-child-so:checked").each(function() {
            allVals_del.push($(this).val())
            if (ids_del[$(this).val()] === undefined){
                ids_del[$(this).val()] = []
                ids_del[$(this).val()].push($(this).parent().next().children()[0].value)
                ids_del[$(this).val()].push($(this).parent().next().next().children()[0].value)
            }
            else {
                ids_del[$(this).val()].push($(this).parent().next().next().children()[0].value)
            }

        });

        if (allVals_del.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Tidak Ada yang Dipilih',
            })
        } else {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/api/v2/gbj/delete_paket_so",
                        type: "post",
                        data: {
                            pesanan_id : id,
                            userid: $('#userid').val(),
                            data: ids_del,
                        },
                        success: function(res) {
                            Swal.fire(
                                'Deleted!',
                                'Your data has been deleted.',
                                'success'
                            ).then(function (){
                                console.log(res);
                                location.reload();
                            })
                        }
                    })

                }
            })

        }
    })

    $(document).on('click', '.detailmodal', function(e) {
        var x = $(this).data('value');
        console.log(x);
        var id = $(this).data('id');

        $.ajax({
            url: "/api/tfp/header-so/" +id+"/"+x,
            success: function(res) {
                console.log(res);
                $('span#so').text(res.so);
                $('span#po').text(res.po);
                $('span#akn').text(res.akn);
                $('span#instansi').text(res.customer);
            }
        });
        console.log(id+ " " +x);
        var table = $('#view-produk').DataTable({
            destroy: true,
            processing: true,
            autoWidth: false,
            bPaginate: false,
            scrollY: 300,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x,
                // url: "/api/testingJson",
            },
            columns: [
                {data: 'detail_pesanan_id'},
                { data: 'paket' },
                { data: 'produk' },
                { data: 'qty' },
                { data: 'status' },
            ],
            "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(0, {page:'current'} ).data().each( function ( group, i ) {

                if (last !== group) {
                    var rowData = api.row(i).data();

                    $(rows).eq(i).before(
                    '<tr class="table-dark text-bold"><td style="display:none;">'+group+'</td><td colspan="3">' + rowData.paket + '</td></tr>'
                );
                    last = group;
                }
            });
        },
        "columnDefs":[
                {"targets": [0], "visible": false},
                {"targets": [1], "visible": false},
            ],
            "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
        })
        $('#viewProdukModal').modal('show');
    });

    let idd;
    $(document).on('click', '.btndetail', function(e) {
        let so = $(this).parent().prev().prev().prev().prev().html();
        let po = $(this).parent().prev().prev().prev().html();
        let customer = $(this).parent().prev().prev().html();
        idd = $(this).data('id');
        let x = $(this).data('value');
        let alasan = $(this).data('alasan')
        let tgl = $(this).data('tgl')
        // console.log(alasan);

        $('span#noso').text(so);
        $('span#nopo').text(po);
        $('span#cust').text(customer);
        $('span#tglbatal').text(tgl);
        $('span#spanAlasan').text(alasan);

        $('#produktable').DataTable({
            destroy: true,
            processing: true,
            autoWidth: false,
            bPaginate: false,
            ordering: false,
            scrollY: 300,
            ajax: {
                url: "/api/tfp/detail-so/" +idd+"/"+x,
            },
            columns: [
                {data: 'detail_pesanan_id'},
                { data: 'paket' },
                { data: 'produk' },
                { data: 'qty' },
            ],
            "drawCallback": function ( settings ) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;

                api.column(0, {page:'current'} ).data().each( function ( group, i ) {

                    if (last !== group) {
                        var rowData = api.row(i).data();

                        $(rows).eq(i).before(
                        '<tr class="table-dark text-bold"><td style="display:none;">'+group+'</td><td colspan="3">' + rowData.paket + '</td></tr>'
                    );
                        last = group;
                    }
                });
            },
            "columnDefs":[
                    {"targets": [0], "visible": false},
                    {"targets": [1], "visible": false},
                ],
                "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        })
        $('#pesananBatal').modal('show');
    })

    $(document).on('click', '#btnProsesBatal', function(e) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/api/v2/gbj/proses_so_batal",
                    type: "post",
                    data: {
                        pesananid: idd,
                    },
                    success: function(res) {
                        // console.log(res)
                        if (res.error == true) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: res.msg
                            })
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: res.msg
                            }).then(() => {
                                console.log('ok');
                            })
                        }
                    }
                })
            }
        })

    })

    $(document).on('click', '.detailBrg', function(e) {
        idd = $(this).data('id')
        prd = $(this).parent().prev().prev().prev().prev().text()
        all = $(this).parent().prev().prev().prev().text()
        done = $(this).parent().prev().text()
        undone = $(this).parent().prev().prev().text()
        $('#titlebrg').html(prd)
        $('#total_tf').html(all + ' Unit')
        $('#belum_tf').html(undone + ' Unit')
        $('#sudah_tf').html(done + ' Unit')
        // console.log(prd);
        $('#reakpdetail-produk').DataTable({
            destroy: true,
            processing: true,
            serverSide: false,
            ordering: false,
            autoWidth: false,
            ajax: {
                url: '/api/v2/gbj/get_detail_rekap_so_produk/'+idd,
                type: "get",
                beforeSend : function(xhr){
                    xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
                }
            },
            columns: [
                { data: 'DT_RowIndex',},
                { data: 'so'},
                { data: 'po'},
                {data: 'customer'},
                {data: 'jumlah'},
                {data: 'status'},
                { data: 'aksi'},
            ],
            "language": {
                // "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                processing: "<span class='fa-stack fa-md'>\n\
                                        <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                                </span>&emsp;Mohon Tunggu ...",
            },
            "order": [[ 5, 'asc' ]]
        })
        $('#viewDetailBrg').modal('show')
    })

    $(document).on('click', '.penjualanmodal', function(e) {
        // $('#viewDetailBrg').modal('hide')
        var x = $(this).data('value');
        console.log(x);
        var id = $(this).data('id');

        $.ajax({
            url: "/api/tfp/header-so/" +id+"/"+x,
            success: function(res) {
                console.log(res);
                $('span#nosoo').text(res.so);
                $('span#nopoo').text(res.po);
                $('span#noakn').text(res.akn);
                $('span#nminstansi').text(res.customer);
            }
        });
        $('#view-produk1').DataTable({
            destroy: true,
            processing: true,
            autoWidth: false,
            bPaginate: false,
            scrollY: 300,
            ajax: {
                url: "/api/tfp/detail-so/" +id+"/"+x,
                // url: "/api/testingJson",
            },
            columns: [
                {data: 'detail_pesanan_id'},
                { data: 'paket' },
                { data: 'produk' },
                { data: 'qty' },
            ],
            "drawCallback": function ( settings ) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;

                api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                    if (last !== group) {
                        var rowData = api.row(i).data();

                        $(rows).eq(i).before(
                        '<tr class="table-dark text-bold"><td style="display:none;">'+group+'</td><td colspan="3">' + rowData.paket + '</td></tr>'
                    );
                        last = group;
                    }
                });
            },
            "columnDefs":[
                    {"targets": [0], "visible": false},
                    {"targets": [1], "visible": false},
                ],
                "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        })
        $('#viewDetailBrgSO').modal('show')
    })

    $(document).on('click', '.close2', function(e) {
        $('#viewDetailBrgSO').modal('hide')
        $('#viewDetailBrg').modal('show')
    })
    $(document).on('click', '.close1', function(e) {
        // $('#viewDetailBrgSO').modal('hide')
        $('#viewDetailBrg').modal('hide')
    })
</script>
@stop
