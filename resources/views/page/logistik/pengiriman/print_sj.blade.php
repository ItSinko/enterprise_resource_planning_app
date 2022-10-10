<style>
    .page-break {
        page-break-after: always;
    }

    .mdtxt {
        font-size: 15px;
    }

    .nospace {
        width: 5%;
        white-space: nowrap;
    }

    .margin {
        margin-left: 10px;
        margin-right: 10px;
    }

    .vera {
        vertical-align: top;
    }

    .veramd {
        vertical-align: middle;
    }

    .wb {
        word-break: break-all;
    }

    .align-center {
        text-align: center;
    }

    .align-right {
        text-align: right;
    }

    .align-left {
        text-align: left;
    }



    div.header {

        height: auto;
        top: -30px;
        left: 0px;
        right: 0px;
    }

    div.body {

        height: auto;
        top: 50px;
        left: 0px;
        right: 0px;
    }

    div.footer {
        bottom: 0px;
        left: 0px;
        right: 0px;
        height: auto;
    }



    .back {
        background-image: url("{{public_path('assets/image/spa_long.jpg') }}");
        background-size: 100%;
        background-repeat: no-repeat;
    }
</style>
<div class="wrapper-page">
    <div class="header ">
        <table border="0" style="border-collapse: collapse; text-align:center;" class="table" width="100%">
            <tbody style="border-bottom: 1px solid;">
                <tr>
                    <td style="width:370px;">
                        <h2>SURAT JALAN</h2>
                    </td>
                    <td class="align-right back">

                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="body">
        <div class="row mdtxt">
            <div class="col-12">
                <table style="width:100%; table-layout: fixed" border="0">
                    <tbody>
                        <tr>
                            <td style="width:10%;" class="vera">Nomor SJ</td>
                            <td style="width:2% ;" class="vera">:</td>
                            <td class="wb">SJ.{{$data->nosurat}}</td>
                            <td class="align-right">
                                Tanggal : {{App\Http\Controllers\LogistikController::tgl_footer($data->tgl_kirim)}}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="width:100%; table-layout: fixed" border="0">
                    <tbody>
                        <tr>
                            <td style="width:10%;" class="vera">Nomor PO</td>
                            <td style="width:2% ;" class="vera">:</td>
                            <td class="wb" class="vera">
                                <?php
                                if (isset($data->DetailLogistik[0])) {
                                    echo $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po;
                                } else {
                                    echo $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->no_po;
                                } ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="vera">Kepada</td>
                            <td class="vera">:</td>
                            <td class="vera">
                                <?php
                                if (isset($data->DetailLogistik[0])) {
                                    $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                                    if ($name[1] == 'EKAT') {
                                        echo    $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->satuan;
                                    } else if ($name[1] == 'SPA') {
                                        echo   $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                                    } else if ($name[1] == 'SPB') {
                                        echo   $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                                    }
                                } else {

                                    $name = explode('/', $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so);
                                    if ($name[1] == 'SPA') {
                                        echo $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->nama;
                                    } else if ($name[1] == 'SPB') {
                                        echo $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->nama;
                                    }
                                }
                                ?>

                            </td>
                        </tr>
                        <tr>
                            <td class="vera">Alamat</td>
                            <td class="vera">:</td>
                            <td class="vera">
                                <?php
                                if (isset($data->DetailLogistik[0])) {
                                    $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                                    if ($name[1] == 'EKAT') {
                                        echo    $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->alamat;
                                    } else if ($name[1] == 'SPA') {
                                        echo   $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat;
                                    } else if ($name[1] == 'SPB') {
                                        echo   $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat;
                                    }
                                } else {
                                    $name = explode('/', $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so);
                                    if ($name[1] == 'SPA') {
                                        echo $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->alamat;
                                    } else if ($name[1] == 'SPB') {
                                        echo $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->alamat;
                                    }
                                }
                                ?>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <table border=0 class="table" width="100%">
                <tbody>
                    <tr>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row mdtxt">
            <div class="col-12">
                <table class="table" border="1" style="border-collapse: collapse; text-align:center;" width="100%">
                    <thead class="align-center" style="border-top: 1px solid black; border-bottom: 1px solid black;">
                        <tr>
                            <th>Nama Barang</th>
                            <th class="nospace">Jumlah</th>
                            <th>No Seri</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data_produk as $e)
                        @if(isset($e->DetailPesananProduk))
                        <tr>
                            <td class="wb align-left">
                                @if($e->DetailPesananProduk->GudangBarangJadi->nama == '')
                                {{$e->DetailPesananProduk->GudangBarangJadi->produk->nama}}
                                @else
                                {{$e->DetailPesananProduk->GudangBarangJadi->nama}}
                                @endif
                            </td>
                            <td class="nospace align-right">{{$e->NoseriDetailLogistik->count()}} pcs</td>
                            <td class="wb">
                                @foreach($e->NoseriDetailLogistik as $x)
                                {{$x->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri}}
                                @if( !$loop->last)
                                ,
                                @endif
                                @endforeach
                            </td>
                            <td class="wb">-</td>
                        </tr>
                        @else
                        <tr>
                            <td class="wb align-left">
                                {{$e->DetailPesananPart->Sparepart->nama}}
                            </td>
                            <td class="nospace align-right">{{$e->DetailPesananPart->jumlah}} pcs</td>
                            <td class="wb">
                                -
                            </td>
                            <td class="wb">-</td>
                        </tr>
                        @endif
                        @endforeach



                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="row mdtxt">
            <div class="col-12">
                <table class="table" border="0" style="border-collapse: collapse;" width="100%">
                    <tbody>
                        <tr>
                            <td class="nospace">Ekspedisi </td>
                            <td class="nospace"> : </td>
                            <td class="wb align-left">
                                @if ($data->nama_pengirim == '')
                                {{$data->Ekspedisi->nama}}
                                @else
                                {{$data->nama_pengirim}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="nospace">Total Ongkir </td>
                            <td class="nospace"> : </td>
                            <td class="wb align-left">-</td>
                        </tr>
                        <tr>
                            <td class="nospace">Catatan </td>
                            <td class="nospace"> : </td>
                            <td class="wb align-left">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="footer">
            <table border=0 class="table align-center" width="100%">
                <thead>
                    <tr>
                        <th>Diterima Oleh,</th>
                        <th>Dibawa Oleh,</th>
                        <th>Dibuat Oleh,</th>
                    </tr>
                </thead>
                <tbody style="border-bottom: 1px solid black;">
                    <tr style="border-bottom: 1px solid;">
                        <td style="height:40px;"></td>
                        <td style="height:40px;"></td>
                        <td style="height:40px;"></td>
                    </tr>
                    <tr style="border-bottom: 1px solid;">
                        <td>_____________</td>
                        <td>_____________</td>
                        <td>_____________</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>@if ($data->nama_pengirim == '')
                            {{$data->Ekspedisi->nama}}
                            @else
                            {{$data->nama_pengirim}}
                            @endif
                        </td>
                        <td>Erna Cantika A</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div class="align-right">No Dokumen: SPA-FR/GUD-04, Tanggal Terbit: 20 Maret 2020, Revisi:02</div>
        </div>
    </div>
</div>

