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
        .topnav a {
        float: left;
        display: block;
        color: black;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
        border-bottom: 3px solid transparent;
    }

    .topnav a:hover {
        border-bottom: 3px solid red;
    }

    .topnav a.active {
        border-bottom: 3px solid red;
    }

    .hidden {
        display: none !important;
    }

    .active {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .otg{
        height: auto;
    }

    .otg:hover {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .nav-border {
        border-bottom: 2px solid black;
        content: "";
    }
    .font-weight{
        font-size: 13px;
    }
    .font-weight-transfer{
        font-size: 15px;
    }
    .tab-content{
        position: sticky;
        overflow: scroll;
    }
</style>
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <nav>
                    <div class="nav nav-tabs topnav" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab"
                            aria-controls="nav-home" aria-selected="true">Penjualan</a>
                        <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab"
                            aria-controls="nav-profile" aria-selected="false">Produk</a>
                        <a class="nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab"
                            aria-controls="nav-contact" aria-selected="false">Penerimaan</a>
                    </div>
                </nav>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col d-flex align-items-stretch">
                                <div id="transferoneday" class="card active otg" style="background-color: #E6EFFA">
                                    <div class="card-body text-center">
                                        <h4 id="he1">0</h4>
                                        <p class="card-text">Produk Melewati Batas Transfer Lebih Dari 1 Hari</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col d-flex align-items-stretch">
                                <div id="transfertwoday" class="card otg" style="background-color: #FEF7EA">
                                    <div class="card-body text-center">
                                        <h4 id="he2">0</h4>
                                        <p class="card-text">Produk Melewati Batas Transfer Lebih Dari 2 Hari</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col d-flex align-items-stretch">
                                <div id="transferthreeday" class="card otg" style="background-color: #FCEDE9">
                                    <div class="card-body text-center">
                                        <h4 id="he3">0</h4>
                                        <p class="card-text">Produk Melewati Batas Transfer Lebih Dari 3 Hari</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Batas Transfer Produk
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="transferonedaytable">
                                    <table class="table table-produk-batas-transfer-one-day">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor SO</th>
                                                <th>Nomor PO</th>
                                                <th>Customer</th>
                                                <th>Batas Transfer</th>
                                                <th>Status Penjualan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="transfertwodaytable hidden">
                                    <table class="table table-produk-batas-transfer-two-day">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor SO</th>
                                                <th>Nomor PO</th>
                                                <th>Customer</th>
                                                <th>Batas Transfer</th>
                                                <th>Status Penjualan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="transferthreedaytable hidden">
                                    <table class="table table-produk-batas-transfer-three-day">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor SO</th>
                                                <th>Nomor PO</th>
                                                <th>Customer</th>
                                                <th>Batas Transfer</th>
                                                <th>Status Penjualan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Produk yang tidak tersedia sesuai SO
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-jml-stok">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Produk</th>
                                            <th>Permintaan</th>
                                            <th>Stok Saat Ini</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col d-flex align-items-stretch">
                                        <div id="jml-produk-20" class="card active otg" style="background-color: #FEF7EA">
                                            <div class="card-body text-center">
                                                <h4 id="prd1">0</h4>
                                                <p class="card-text">Produk dengan jumlah stok 10 sampai 20</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col d-flex align-items-stretch">
                                        <div id="jml-produk-5" class="card otg" style="background-color: #FFBD67">
                                            <div class="card-body text-center">
                                                <h4 id="prd2">0</h4>
                                                <p class="card-text">Produk dengan jumlah stok 5 sampai 9</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col d-flex align-items-stretch">
                                        <div id="jml-produk-4" class="card otg" style="background-color: #FF6464">
                                            <div class="card-body text-center">
                                                <h4 id="prd3">0</h4>
                                                <p class="card-text">Produk dengan jumlah stok 1 sampai 4</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jml-produk-20-table">
                                    <table class="table jml-produk-20-tab">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Produk</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="jml-produk-5-table hidden">
                                    <table class="table jml-produk-5-tab">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Produk</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 3</td>
                                                <td>10 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 3</td>
                                                <td>20 Unit</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="jml-produk-4-table hidden">
                                    <table class="table jml-produk-4-tab">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Produk</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Produk 4</td>
                                                <td>10 Unit</td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Produk 5</td>
                                                <td>20 Unit</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col d-flex align-items-stretch">
                                        <div id="produk-masuk-3-bulan" class="card otg active" style="background-color: #FEF7EA">
                                            <div class="card-body text-center">
                                                <h4 id="prd4">0</h4>
                                                <p class="card-text font-weight">Produk masuk 3 bulan sampai 6 bulan</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col d-flex align-items-stretch">
                                        <div id="produk-masuk-6-bulan" class="card otg" style="background-color: #FFBD67">
                                            <div class="card-body text-center">
                                                <h4 id="prd5">0</h4>
                                                <p class="card-text font-weight">Produk masuk 6 bulan sampai 1 tahun</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col d-flex align-items-stretch">
                                        <div id="produk-masuk-1-tahun" class="card otg" style="background-color: #FA8282">
                                            <div class="card-body text-center">
                                                <h4 id="prd6">0</h4>
                                                <p class="card-text font-weight">Produk masuk 1 tahun sampai 3 tahun</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col d-flex align-items-stretch">
                                        <div id="produk-masuk-3-tahun" class="card otg" style="background-color: #FF6464">
                                            <div class="card-body text-center">
                                                <h4 id="prd7">0</h4>
                                                <p class="card-text font-weight">Produk masuk lebih dari 3 tahun</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Produk Masuk 3 Bulan --}}
                                <div class="produk-masuk-3-bulan-table">
                                    <table class="table waktu-produk1" id="tab3bulan">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Nama Produk</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                {{-- Produk Masuk 6 Bulan --}}
                                <div class="produk-masuk-6-bulan-table hidden">
                                <table class="table waktu-produk2" id="tab6bulan">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                </div>
                                {{-- Produk Masuk 1 Tahun --}}
                                <div class="produk-masuk-1-tahun-table hidden">
                                <table class="table waktu-produk3" id="tab1tahun">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                </div>
                                {{-- Produk Masuk 3 Tahun --}}
                                <div class="produk-masuk-3-tahun-table hidden">
                                <table class="table waktu-produk4" id="tab3tahun">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
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
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-dolly-flatbed"></i> Daftar Stok Layout
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm text-right">Layout :</div>
                                    <div class="col-sm">
                                        <select class="select2 form-control layout" id="layout" multiple="multiple">
                                      </select>
                                    </div>
                                </div>
                                <table class="table tableStokLayout">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Jumlah</th>
                                            <th>Layout</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col"></div>
                </div>
            </div>
        </section>
    </div>
    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col-6 col-md-4">
                                <div id="receiptoneday" class="card active otg" style="background-color: #FEF7EA">
                                    <div class="card-body text-center">
                                        <h4 id="r1">0</h4>
                                        <p class="card-text font-weight-transfer">Produk Melewati Batas Penerimaan Lebih Dari 1 Hari</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div id="receipttwoday" class="card otg" style="background-color: #FFBD67">
                                    <div class="card-body text-center">
                                        <h4 id="r2">0</h4>
                                        <p class="card-text font-weight-transfer">Produk Melewati Batas Penerimaan Lebih Dari 2 Hari</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div id="receiptthreeday" class="card otg" style="background-color: #FF6464">
                                    <div class="card-body text-center">
                                        <h4 id="r3">0</h4>
                                        <p class="card-text font-weight-transfer">Produk Melewati Batas Penerimaan Lebih Dari 3 Hari</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Batas Penerimaan Produk
                                    </h3>
                            </div>
                            <div class="card-body">
                                <div class="receiptonedaytable">
                                    <table class="table table-produk-batas-receipt-one-day">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Jumlah</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="receipttwodaytable hidden">
                                    <table class="table table-produk-batas-receipt-two-day">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Jumlah</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="receiptthreedaytable hidden">
                                    <table class="table table-produk-batas-receipt-three-day">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Jumlah</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Daftar Penerimaan Transfer Produk
                                </h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-produk-batas-receipt-all">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    $(document).ready(function () {
        //
        penjualan();
        $('#nav-home-tab').on('click', function(){
            console.log('jual');
            produkdestroy();
            terimadestroy();
            penjualan();
        })

        $('#nav-profile-tab').on('click', function(){
            console.log('produk');
            penjualandestroy();
            terimadestroy();
            produk();

            // Produk Stok
            $(document).on('click', '#jml-produk-20', function () {
                $('#jml-produk-20').addClass('active');
                $('.jml-produk-20-table').removeClass('hidden');
                $('#jml-produk-5').removeClass('active');
                $('#jml-produk-4').removeClass('active');
                $('.jml-produk-5-table').addClass('hidden');
                $('.jml-produk-4-table').addClass('hidden');
            })
            $(document).on('click', '#jml-produk-5', function () {
                $('#jml-produk-5').addClass('active');
                $('.jml-produk-5-table').removeClass('hidden');
                $('#jml-produk-20').removeClass('active');
                $('#jml-produk-4').removeClass('active');
                $('.jml-produk-20-table').addClass('hidden');
                $('.jml-produk-4-table').addClass('hidden');
            })
            $(document).on('click', '#jml-produk-4', function () {
                $('#jml-produk-4').addClass('active');
                $('.jml-produk-4-table').removeClass('hidden');
                $('#jml-produk-5').removeClass('active');
                $('#jml-produk-20').removeClass('active');
                $('.jml-produk-5-table').addClass('hidden');
                $('.jml-produk-20-table').addClass('hidden');
            })
            // Produk Masuk
            $(document).on('click', '#produk-masuk-3-bulan', function () {
                $('#produk-masuk-3-bulan').addClass('active');
                $('.produk-masuk-3-bulan-table').removeClass('hidden');
                $('#produk-masuk-6-bulan').removeClass('active');
                $('#produk-masuk-1-tahun').removeClass('active');
                $('#produk-masuk-3-tahun').removeClass('active');
                $('.produk-masuk-6-bulan-table').addClass('hidden');
                $('.produk-masuk-1-tahun-table').addClass('hidden');
                $('.produk-masuk-3-tahun-table').addClass('hidden');
            })
            $(document).on('click', '#produk-masuk-6-bulan', function () {
                $('#produk-masuk-6-bulan').addClass('active');
                $('.produk-masuk-6-bulan-table').removeClass('hidden');
                $('#produk-masuk-3-bulan').removeClass('active');
                $('#produk-masuk-1-tahun').removeClass('active');
                $('#produk-masuk-3-tahun').removeClass('active');
                $('.produk-masuk-3-bulan-table').addClass('hidden');
                $('.produk-masuk-1-tahun-table').addClass('hidden');
                $('.produk-masuk-3-tahun-table').addClass('hidden');
            })
            $(document).on('click', '#produk-masuk-1-tahun', function () {
                $('#produk-masuk-1-tahun').addClass('active');
                $('.produk-masuk-1-tahun-table').removeClass('hidden');
                $('#produk-masuk-6-bulan').removeClass('active');
                $('#produk-masuk-3-bulan').removeClass('active');
                $('#produk-masuk-3-tahun').removeClass('active');
                $('.produk-masuk-6-bulan-table').addClass('hidden');
                $('.produk-masuk-3-bulan-table').addClass('hidden');
                $('.produk-masuk-3-tahun-table').addClass('hidden');
            })
            $(document).on('click', '#produk-masuk-3-tahun', function () {
                $('.produk-masuk-3-tahun-table').removeClass('hidden');
                $('#produk-masuk-3-tahun').addClass('active');
                $('#produk-masuk-1-tahun').removeClass('active');
                $('.produk-masuk-1-tahun-table').addClass('hidden');
                $('#produk-masuk-6-bulan').removeClass('active');
                $('#produk-masuk-3-bulan').removeClass('active');
                $('.produk-masuk-6-bulan-table').addClass('hidden');
                $('.produk-masuk-3-bulan-table').addClass('hidden');
            })

            $.ajax({
                url: '/api/gbj/sel-layout',
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if(res) {
                        console.log(res);
                        $(".layout").empty();
                        $(".layout").append('<option value="" selected>All Layout</option>');
                        $.each(res, function(key, value) {
                            $(".layout").append('<option value="'+value.ruang+'">'+value.ruang+'</option');
                        });
                    } else {
                        $(".layout").empty();
                    }
                }
            });
        })

        $('#nav-contact-tab').on('click', function(){
            console.log('terima');
            penjualandestroy();
            produkdestroy();
            terima();
             // Transfer
            $(document).on('click', '#receiptoneday', function () {
                $('#receiptoneday').addClass('active');
                $('.receiptonedaytable').removeClass('hidden');
                $('#receipttwoday').removeClass('active');
                $('#receiptthreeday').removeClass('active');
                $('.receipttwodaytable').addClass('hidden');
                $('.receiptthreedaytable').addClass('hidden');
            })
            $(document).on('click', '#receipttwoday', function () {
                $('#receipttwoday').addClass('active');
                $('.receipttwodaytable').removeClass('hidden');
                $('#receiptoneday').removeClass('active');
                $('#receiptthreeday').removeClass('active');
                $('.receiptonedaytable').addClass('hidden');
                $('.receiptthreedaytable').addClass('hidden');
            })
            $(document).on('click', '#receiptthreeday', function () {
                $('#receiptthreeday').addClass('active');
                $('.receiptthreedaytable').removeClass('hidden');
                $('#receipttwoday').removeClass('active');
                $('#receiptoneday').removeClass('active');
                $('.receipttwodaytable').addClass('hidden');
                $('.receiptonedaytable').addClass('hidden');
            });
        })

        $(document).on('click', '#transferoneday', function () {
            $('#transferoneday').addClass('active');
            $('.transferonedaytable').removeClass('hidden');
            $('#transfertwoday').removeClass('active');
            $('#transferthreeday').removeClass('active');
            $('.transfertwodaytable').addClass('hidden');
            $('.transferthreedaytable').addClass('hidden');
        })
        $(document).on('click', '#transfertwoday', function () {
            $('#transfertwoday').addClass('active');
            $('.transfertwodaytable').removeClass('hidden');
            $('#transferoneday').removeClass('active');
            $('#transferthreeday').removeClass('active');
            $('.transferonedaytable').addClass('hidden');
            $('.transferthreedaytable').addClass('hidden');
        })
        $(document).on('click', '#transferthreeday', function () {
            $('#transferthreeday').addClass('active');
            $('.transferthreedaytable').removeClass('hidden');
            $('#transfertwoday').removeClass('active');
            $('#transferoneday').removeClass('active');
            $('.transfertwodaytable').addClass('hidden');
            $('.transferonedaytable').addClass('hidden');
        })

        $('.select2').select2({});

    });

        function filterFunction() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            div = document.getElementById("myDropdown");
            a = div.getElementsByTagName("a");
            for (i = 0; i < a.length; i++) {
                txtValue = a[i].textContent || a[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
                } else {
                a[i].style.display = "none";
                }
            }
        }
        function modalPenerimaan() {
            $('.detail-layout').modal('show');
        }
        function modalSO() {
            $('#viewProdukModal').modal('show');
        }

        function penjualan() {
            $.ajax({
                url: "/api/dashboard-gbj/list/h",
                type: "post",
                success: function(res) {
                    console.log(res);
                    $('h4#he3').text(res);
                }
            })

            $.ajax({
                url: "/api/dashboard-gbj/list1/h",
                type: "post",
                success: function(res) {
                    console.log(res);
                    $('h4#he1').text(res);
                }
            })

            $.ajax({
                url: "/api/dashboard-gbj/list2/h",
                type: "post",
                success: function(res) {
                    console.log(res);
                    $('h4#he2').text(res);
                }
            })

            // penjualan
            $('.table-produk-batas-transfer-one-day').DataTable({
                processing: true,
                destroy: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '/api/dashboard-gbj/list1',
                    type: "post",
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'so'},
                    {data: 'no_po'},
                    {data: 'nama_customer'},
                    {data: 'tgl_batas'},
                    {data: 'status_penjualan'},
                    {data: 'action'},
                ]
            });
            $('.table-produk-batas-transfer-two-day').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                ajax: {
                    url: '/api/dashboard-gbj/list2',
                    type: "post",
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'so'},
                    {data: 'no_po'},
                    {data: 'nama_customer'},
                    {data: 'tgl_batas'},
                    {data: 'status_penjualan'},
                    {data: 'action'},
                ]
            });
            $('.table-produk-batas-transfer-three-day').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                ajax: {
                    url: '/api/dashboard-gbj/list',
                    type: "post",
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'so'},
                    {data: 'no_po'},
                    {data: 'nama_customer'},
                    {data: 'tgl_batas'},
                    {data: 'status_penjualan'},
                    {data: 'action'},
                ]
            });

            $('.table-jml-stok').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                ajax: {
                    url: '/api/dashboard-gbj/list-all',
                    type: "post",
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'produk'},
                    {data: 'permintaan'},
                    {data: 'current_stok'},
                ]
            });

            $(document).on('click', '.salemodal', function() {
                var id = $(this).data('id');
                console.log(id);
                var x = $(this).data('value');
                console.log(x);

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
                // $('#view-produk').DataTable().destroy();
                $('#view-produk').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    bPaginate: false,
                    scrollY: 300,
                    ajax: {
                        url: "/api/tfp/detail-so/" + id+"/"+x,
                    },
                    columns: [
                        {data: 'detail_pesanan_id'},
                        { data: "paket" },
                        { data: "produk" },
                        { data: "jumlah" },
                        { data: "merk" },
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
                });
                modalSO();
            })
        }

        function penjualandestroy() {
            $('.table-produk-batas-transfer-one-day').DataTable().clear().destroy();
            $('.table-produk-batas-transfer-two-day').DataTable().clear().destroy();
            $('.table-produk-batas-transfer-three-day').DataTable().clear().destroy();
            $('.table-jml-stok').DataTable().clear().destroy();
        }

        function produk() {

            $.ajax({
                url: "/api/dashboard-gbj/stok/1020/h",
                success: function(res) {
                    console.log(res);
                    $('h4#prd1').text(res);
                }
            })

            $.ajax({
                url: "/api/dashboard-gbj/stok/59/h",
                success: function(res) {
                    console.log(res);
                    $('h4#prd2').text(res);
                }
            })

            $.ajax({
                url: "/api/dashboard-gbj/stok/14/h",
                success: function(res) {
                    console.log(res);
                    $('h4#prd3').text(res);
                }
            })

            $.ajax({
                url: "/api/dashboard-gbj/in/36/h",
                type: "post",
                success: function(res) {
                    console.log(res);
                    $('h4#prd4').text(res);
                }
            })

            $.ajax({
                url: "/api/dashboard-gbj/in/612/h",
                type: "post",
                success: function(res) {
                    console.log(res);
                    $('h4#prd5').text(res);
                }
            })

            $.ajax({
                url: "/api/dashboard-gbj/in/1236/h",
                type: "post",
                success: function(res) {
                    console.log(res);
                    $('h4#prd6').text(res);
                }
            })

            $.ajax({
                url: "/api/dashboard-gbj/in/36plus/h",
                type: "post",
                success: function(res) {
                    console.log(res);
                    $('h4#prd7').text(res);
                }
            })

            $('.jml-produk-20-tab').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                ajax: {
                    url: '/api/dashboard-gbj/stok/1020',
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'prd'},
                    {data: 'jml'},
                ]
            });
            $('.jml-produk-5-tab').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                ajax: {
                    url: '/api/dashboard-gbj/stok/59',
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'prd'},
                    {data: 'jml'},
                ]
            });
            $('.jml-produk-4-tab').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                ajax: {
                    url: '/api/dashboard-gbj/stok/14',
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'prd'},
                    {data: 'jml'},
                ]
            });
            var table = $('.tableStokLayout').DataTable({
                dom: 'lrtip',
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                "lengthChange": false,
                ajax: {
                    url: '/api/dashboard-gbj/byproduct',
                    type: "get",
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'prd'},
                    {data: 'jml'},
                    {data: 'layout'},
                ],

                initComplete: function () {
                    this.api().columns([3]).every( function () {
                        var column = this;
                        var select = $('<select class="form-control"><option value="">All Layout</option></select>')
                            .appendTo( $(column.footer()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );

                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );
                    $("#layout").select2();
                }
            });

            $('#layout').on('change', function(){
                var search = [];

                $.each($('#layout option:selected'), function(){
                        search.push($(this).val());
                });

                search = search.join('|');
                console.log(search);
                table.column(3).search(search, true, false).draw();
            });
            // $('.table-produk-batas-receipt-one-day').DataTable().destroy();
            // $('.table-produk-batas-receipt-two-day').DataTable().destroy();
            // $('.table-produk-batas-receipt-three-day').DataTable().destroy();
            $('.waktu-produk1').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                scrollY: "500px",
                ajax: {
                    url: '/api/dashboard-gbj/in/36',
                    type: "post",
                },
                columns: [
                    {data: 'DT_RowIndex', width: '20%'},
                    {data: 'tgl_masuk', width: '30%'},
                    {data: 'product', width: '40%'},
                    {data: 'jumlah', width: '10%'},
                ]
            });
            $('.waktu-produk2').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                scrollY: "500px",
                ajax: {
                    url: '/api/dashboard-gbj/in/612',
                    type: "post",
                },
                columns: [
                    {data: 'DT_RowIndex', width: '20%'},
                    {data: 'tgl_masuk', width: '30%'},
                    {data: 'product', width: '40%'},
                    {data: 'jumlah', width: '10%'},
                ]
            });
            $('.waktu-produk3').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                scrollY: "500px",
                ajax: {
                    url: '/api/dashboard-gbj/in/1236',
                    type: "post",
                },
                columns: [
                    {data: 'DT_RowIndex', width: '20%'},
                    {data: 'tgl_masuk', width: '30%'},
                    {data: 'product', width: '40%'},
                    {data: 'jumlah', width: '10%'},
                ]
            });
            $('.waktu-produk4').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                scrollY: "500px",
                ajax: {
                    url: '/api/dashboard-gbj/in/36plus',
                    type: "post",
                },
                columns: [
                    {data: 'DT_RowIndex', width: '20%'},
                    {data: 'tgl_masuk', width: '30%'},
                    {data: 'product', width: '40%'},
                    {data: 'jumlah', width: '10%'},
                ]
            });
        }

        function produkdestroy() {
            $('.jml-produk-20-tab').DataTable().clear().destroy();
            $('.jml-produk-5-tab').DataTable().clear().destroy();
            $('.jml-produk-4-tab').DataTable().clear().destroy();
            $('.tableStokLayout').DataTable().clear().destroy();
            $('.waktu-produk1').DataTable().clear().destroy();
            $('.waktu-produk2').DataTable().clear().destroy();
            $('.waktu-produk3').DataTable().clear().destroy();
            $('.waktu-produk4').DataTable().clear().destroy();
        }

        function terima() {
             $.ajax({
                url: "/api/dashboard-gbj/terimaproduk1/h",
                type: "post",
                success: function(res) {
                    console.log(res);
                    $('h4#r1').text(res);
                }
            })

            $.ajax({
                url: "/api/dashboard-gbj/terimaproduk2/h",
                type: "post",
                success: function(res) {
                    console.log(res);
                    $('h4#r2').text(res);
                }
            })

            $.ajax({
                url: "/api/dashboard-gbj/terimaproduk3/h",
                type: "post",
                success: function(res) {
                    console.log(res);
                    $('h4#r3').text(res);
                }
            })

            $('.table-produk-batas-receipt-all').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                ajax: {
                    url: '/api/dashboard-gbj/terimaall',
                    type: "post",
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'product'},
                    {data: 'jumlah'},
                    {data: 'tgl_masuk'},
                    {data: 'action'},
                ]
            });

            $('.table-produk-batas-receipt-one-day').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                ajax: {
                    url: '/api/dashboard-gbj/terimaproduk1',
                    type: "post",
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'product'},
                    {data: 'jumlah'},
                    {data: 'tgl_masuk'},
                    {data: 'action'},
                ]
            });
            $('.table-produk-batas-receipt-two-day').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                ajax: {
                    url: '/api/dashboard-gbj/terimaproduk2',
                    type: "post",
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'product'},
                    {data: 'jumlah'},
                    {data: 'tgl_masuk'},
                    {data: 'action'},
                ]
            });
            $('.table-produk-batas-receipt-three-day').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                autoWidth: false,
                ajax: {
                    url: '/api/dashboard-gbj/terimaproduk3',
                    type: "post",
                },
                columns: [
                    {data: 'DT_RowIndex'},
                    {data: 'product'},
                    {data: 'jumlah'},
                    {data: 'tgl_masuk'},
                    {data: 'action'},
                ]
            });

            $(document).on('click', '.editmodal', function() {
                var id = $(this).data('id');
                console.log(id);
                var brg = $(this).data('brg');
                var tipe = $(this).data('var')
                $('span#prd').text(brg + tipe);

                $('.table-seri').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    ajax: {
                        url: "/api/dashboard-gbj/noseri/" + id,
                        type: "get",
                    },
                    columns: [
                        {data: "noser"},
                        {data: "posisi"},
                    ],
                    "language": {
                            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                        }
                });
                modalPenerimaan();
            })
        }

        function terimadestroy() {
            $('.table-produk-batas-receipt-all').DataTable().clear().destroy();
            $('.table-produk-batas-receipt-three-day').DataTable().clear().destroy();
            $('.table-produk-batas-receipt-two-day').DataTable().clear().destroy();
            $('.table-produk-batas-receipt-one-day').DataTable().clear().destroy();
        }

</script>
@stop
