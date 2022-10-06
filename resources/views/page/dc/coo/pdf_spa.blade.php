<head>
    <style>
        /* body {
            margin-left: 90px;
            margin-right: 90px;
            margin-bottom: 70px;
            margin-top: 70px;
        } */
        @page {
            margin: 0;

        }

        .mdtxt {
            font-size: 18px;

        }

        .nospace {
            width: 2%;
            white-space: nowrap;
        }

        .bold {
            font-weight: 600;
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

        .imgsize {
            width: auto;
            height: 35px;
        }

        div.header {
            position: fixed;
            height: 20px;
            margin-left: 84px;
            margin-right: 84px;
            margin-top: 65px;
        }

        div.body {
            position: fixed;
            height: auto;
            margin-left: 90px;
            margin-right: 90px;
            margin-bottom: 70px;
            margin-top: 120px;
        }

        div.footer {
            position: fixed;
            bottom: 100px;
            left: 0px;
            right: 0px;
        }

        .trheight {
            height: 40px;
        }

        .back {
            background-image: url("{{ public_path('assets/image/background_coo.jpg') }}");
            background-size: 100%;
        }

        .noback {
            background-image: url("{{ public_path('assets/image/no_background_coo.jpg') }}");
            background-size: 100%;
        }
    </style>
</head>
@if ($jenis == 'back' || $jenis == 'ttd')

    <body class="back">
    @else

        <body class="noback">
@endif
<div class="header">
    <table border="0" style="border-collapse: collapse; text-align:right;width:625px" class="table">
        <tbody>
            <tr>
                <td style="width:52.3%;"></td>
                <td class="veramd align-center" style="width:47.7%;">
                    <h3>{{ $data->id }} / SKA / {{ $romawi }} / SPA / {{ $tahun }}</h3>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<div class="body">
    <table border="0" style="border-collapse: collapse; text-align:center;" class="table" width="100%">
        <tbody>
            <tr>
                <td style="height: auto;">
                    <h1><u>CERTIFICATE OF ORIGIN</u></h1>
                </td>
            </tr>
            <tr>
                <td style="height:10px"></td>
            </tr>
        </tbody>
    </table>
    <div class="row mdtxt">
        <table border="0" class="table" style="border-collapse: collapse;" width="100%">
            <tbody>
                <tr>
                    <td colspan="3" class="align-center">Berdasarkan ijin edar/produksi dari</td>
                </tr>
                <tr>
                    <td colspan="3" style="height:5px"></td>
                </tr>
                <tr>
                    <td class="nospace vera"><b>Kementrian Kesehatan Nomor</b></td>
                    <td class="nospace vera"><b>:</b></td>
                    <td class="wb vera"><b>KEMENKES RI AKD
                            @if ($data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->no_akd != '')
                                {{ $data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->no_akd }}
                            @endif
                        </b></td>
                </tr>
                <tr>
                    <td colspan="3" style="height:20px"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row mdtxt">
        <div class="col-12">
            <table border="0" style="width:100%" id="produktable">
                <tbody>
                    <tr>
                        <td style="width:2%"></td>
                        <td colspan="3" class="nospace">PT SINKO PRIMA ALLOY (penyedia), menyerahkan hasil produksi:
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="height:10px"></td>
                    </tr>
                    <tr class="vera bold">
                        <td style="width:2%"></td>
                        <td class="nospace trheight">Nama Produk</td>
                        <td class="nospace align-center"> : </td>
                        <td class="wb">
                            @if ($data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->nama_coo != '')
                                {{ $data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->nama_coo }}
                            @endif
                        </td>
                    </tr>
                    <tr class="vera bold">
                        <td style="width:2%"></td>
                        <td class="nospace trheight">Tipe</td>
                        <td class="nospace align-center"> : </td>
                        <td class="wb">
                            {{ $data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->nama }}
                        </td>
                    </tr>
                    <tr class="vera bold">
                        <td style="width:2%"></td>
                        <td class="nospace trheight">Nomor Seri</td>
                        <td class="nospace align-center"> : </td>
                        <td class="wb">
                            {{ $data->NoseriDetailLogistik->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri }}
                        </td>
                    </tr>
                    <tr class="vera bold">
                        <td style="width:2%"></td>
                        <td class="nospace trheight">Merk Produk</td>
                        <td class="nospace align-center"> : </td>
                        <td class="wb">
                            {{ $data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->merk }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mdtxt">
        <table border="0" class="table align-center" width="100%" style="border-collapse: collapse;">
            <tbody>
                <tr>
                    <td style="height:15px">
                    </td>
                </tr>
                <tr>
                    <td class="align-center">Kepada :</td>
                </tr>
                <tr>
                    <td style="height:3px"></td>
                </tr>
                <tr>
                    <td class="wb">
                        <b>{{ $data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama }}</b>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row mdtxt">
        <table border="0" class="table align-center" width="100%">
            <tbody>
                <tr>
                    <td style="height:20px"></td>
                </tr>
                <tr>
                    <td class="align-center"><i>Dalam kondisi baru, baik dan lengkap.</i></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="footer">
    <div class="row mdtxt">
        <table border="0" class="table align-center" width="100%">
            <tbody>
                <tr>
                    <td width="50%"></td>
                    <td class="align-center">Surabaya, {{ $footer }}</td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td class="align-center">PT. Sinko Prima Alloy</td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td class="align-center" style="height:75px;">
                        @if ($jenis == 'ttd')
                            @if (empty($data->nama) && $data->ket == 'spa')
                                @if ($stamp == 1)
                                    <img src="{{ public_path('assets/image/spa_stamp.png') }}" width="200"
                                        height="100">
                                @else
                                    <img src="{{ public_path('assets/image/spa.png') }}" width="200" height="100">
                                @endif
                            @elseif(empty($data->nama) && $data->ket == 'emiindo')
                                <img src="{{ public_path('assets/image/emiindo.png') }}" width="100" height="100">
                            @else
                                <img src="" width="100" height="100">
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td class="align-center"><b><u>
                                @if (empty($data->nama) && $data->ket == 'spa')
                                    Kusmardiana Rahayu
                                @elseif(empty($data->nama) && $data->ket == 'emiindo')
                                    Bambang Hendro M BE
                                @else
                                    {{ $data->nama }}
                                @endif
                            </u></b></td>
                </tr>
                <tr>
                    <td width="50%"></td>
                    <td class="align-center">
                        @if (empty($data->nama) && $data->ket == 'spa')
                            Q.A Manager
                        @elseif(empty($data->nama) && $data->ket == 'emiindo')
                            Q.A Departement
                        @else
                            {{ $data->jabatan }}
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
