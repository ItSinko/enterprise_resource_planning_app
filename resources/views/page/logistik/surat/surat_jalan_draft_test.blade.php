<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<html>

<head>
    <script defer src="https://unpkg.com/pagedjs/dist/paged.polyfill.js"></script>
    <style>
        @media print {
            @page {
                margin: 60px 25px;

                @bottom-center {
                    content: 'Hal. ' counter(page);
                }

                @bottom-right {
                    content: '';
                    /* style */
                    font-size: 10px;
                    font-style: italic;
                }
            }

            main table thead {
                display: table-header-group;
            }

        }

        header {
            background-color: #ffffff;
            color: rgb(0, 0, 0);
            line-height: 20px;
            font-size: 14px;
            margin-top: -32px;
        }


        footer {
            /** Extra personal styles **/
            margin-top: 50px;
            bottom: 0;
            left: 0px;
            right: 0px;
            background-color: #ffffff;
            color: rgb(0, 0, 0);
            line-height: 20px;
        }

        .vera {
            vertical-align: top;
        }

        .align-left {
            text-align: left;
        }

        .align-right {
            text-align: right;
        }

        .align-center {
            text-align: center;
        }

        .td-width-header {
            width: 40%;
        }

        main table {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid #000000;
            border-bottom: 1px solid #000000;
            font-size: 13px;
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <header>
        <table style="font-size: 18px;">
            <tr>
                <td>
                    <b>SURAT JALAN</b>
                </td>
                <th style="text-align: right;">
                    PT. Sinko Prima Alloy
                    </td>
            </tr>
        </table>
        <hr>
        <table>
            <tr>
                <td class="vera">
                    <table>
                        <tr>
                            <td class="vera" width="20%">Pelanggan :</td>
                            <td><b><u>{{ $data->customer }}</u></b></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>{{ $data->alamat_customer }}</td>
                        </tr>
                    </table>
                    <br>
                    <table>
                        <tr>
                            <td class="vera" width="20%">Pengiriman :</td>
                            <td class="vera"><b><u>{{ $data->tujuan_kirim }}</u></b></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>{{ $data->alamat_kirim }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><b>UP : </b>{{ $data->up }}</td>
                        </tr>
                    </table>
                </td>
                <td width="1%"></td>
                <td class="vera" width="35%">
                    <table style="width: 100%">
                        <tr>
                            <td class="td-width-header">Nomor SJ</td>
                            <td>: {{ $data->nosj }}</td>
                        </tr>
                        <tr>
                            <td class="td-width-header">Tanggal SJ</td>
                            {{-- {{ \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY') }} --}}
                            <td>: {{ \Carbon\Carbon::parse($data->tgl_sj)->isoFormat('DD MMMM YYYY') }}</td>
                        </tr>
                        <tr>
                            <td class="td-width-header">Nomor PO</td>
                            <td>: {{ $data->no_po }}</td>
                        </tr>
                        <tr>
                            <td class="td-width-header">Ket. Pengiriman</td>
                            <td>:
                                @switch($data->keterangan_pengiriman)
                                    @case('bayar_tujuan')
                                        <span>BAYAR TUJUAN <span>
                                            @break

                                            @case('bayar_sinko')
                                                <span>BAYAR SINKO </span>
                                            @break

                                            @default
                                                <span>NON BAYAR<span>
                                                    @break
                                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <td class="td-width-header">Ekspedisi</td>
                            <td>: {{ $data->ekspedisi }}</td>
                        </tr>
                    </table>
            </tr>
        </table>
    </header>
    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        {{-- Hal -1 --}}
        <table>
            <thead>
                <tr>
                    <td class="vera align-center" width="8%" style="border-bottom: 1px solid black">
                        <b>No</b>
                    </td>
                    <td class="vera align-center" width="16%" style="border-bottom: 1px solid black">
                        <b>Kode Barang</b>
                    </td>
                    <td class="vera align-center" style="border-bottom: 1px solid black">
                        <b>Nama Barang</b>
                    </td>
                    <td class="vera" width="8%" style="border-bottom: 1px solid black">
                        <b>Jumlah</b>
                    </td>
                    <td class="vera" width="8%" style="border-bottom: 1px solid black">
                        <b>Satuan</b>
                    </td>
                </tr>
            </thead>
            @foreach ($data->item as $key => $item)
                <tbody>
                    <tr @if (!isset($item->detail)) style="border-bottom: 1px solid black;" @endif>
                        <td class="vera align-center">
                            {{ $key + 1 }}
                        </td>
                        <td class="vera align-center">
                            @if (isset($item->kode))
                                {{ $item->kode }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="vera">
                            {{ $item->nama }}
                        </td>
                        @php
                            $satuan = null;
                            if (isset($item->detail)) {
                                $satuan = $item->detail[0]->satuan;
                            } else {
                                $satuan = $item->satuan;
                            }
                        @endphp
                        <td class="vera">
                            {{ $item->jumlah }}.00
                        </td>
                        <td class="vera">
                            {{ $satuan }}
                        </td>
                    </tr>
                    @if (isset($item->detail))
                        <tr style="border-bottom: 1px solid black;">
                            <td></td>
                            <td class="vera" colspan="4">
                                <b>No Seri</b> :
                                @foreach ($item->detail as $key => $detail)
                                    @if ($key == count($item->detail) - 1)
                                        {{ $detail->nama }} :
                                        @if($detail->noseri == null)
                                            -
                                        @else
                                        {{ implode(', ', $detail->noseri) }}
                                        @endif
                                    @else
                                        {{ $detail->nama }} :
                                        @if($detail->noseri == null)
                                            -
                                        @else
                                        {{ implode(', ', $detail->noseri) }}
                                        @endif
                                        <br>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @endif
                </tbody>
            @endforeach

        </table>
        @if ($data->dimensi != '')
            <div style="margin: 10px 0px;">
                <b>Dimensi</b>
                <br>
                @php
                    echo nl2br($data->dimensi);
                @endphp
        @endif
        @if ($data->ekspedisi_terusan != '')
            </div @if ($data->dimensi == '')
            style="margin: 10px 0px;"
        @endif
        >
        <b>Ekspedisi Terusan : </b><br>
        @php
            echo nl2br($data->ekspedisi_terusan);
        @endphp
        <br>
        </div>
        @endif
    </main>

    <footer>
        {{-- show when end page --}}
        <table>
            </tr>
            <tr>
                <td class="align-left vera" width="12%">
                    <b>Keterangan : </b><br>
                    {{ $data->paket }}
                    @if ($data->ket != null)
                        - {{ $data->ket }}
                    @else
                        <br>
                    @endif
                </td>
            </tr>
        </table>
        <hr>
        <table>
            <tr>
                <td class="align-center">
                    Diterima Oleh,
                </td>
                <td class="align-center">
                    Dibawa Oleh,
                </td>
                <td class="align-center">
                    Dibuat Oleh,
                </td>
            </tr>
            <td class="align-right" colspan="2">
                <br>
                <br>
                <br>

                <tr>
                    <td class="align-center">
                        <hr style="width:30%">

                    </td>
                    <td class="align-center">
                        <hr style="width:40%">
                        {{-- KURIR --}}
                    </td>
                    <td class="align-center">
                        <hr style="width:30%">
                        {{-- LOGISTIK --}}
                    </td>
                </tr>
        </table>
    </footer>
</body>

</html>
<script>
    $(document).ready(function() {
        setTimeout(() => {
            window.print();
        }, 100);
    });
    // click cancel close window
    window.onafterprint = function() {
        window.close();
    }
</script>
