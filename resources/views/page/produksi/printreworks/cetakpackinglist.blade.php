<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<html>

<head>
    <title>Cetak Packing List</title>

    <style>
        .judul {
            font-family: 'Book Antiqua';
            font-size: 16pt;
            text-align: center;
        }

        header table {
            font-family: 'Times New Roman';
            font-size: 12pt;
        }

        header table tr td,
        footer table tr td {
            padding: 10px 0px;
        }

        .text-center {
            text-align: center;
        }

        main table {
            /* all bordered */
            border-collapse: collapse;
            border: 1px solid black;
            outline: 1px double black;
            outline-offset: -3px;
            font-family: 'Times New Roman';
            font-size: 10pt;
        }

        main table tr td {
            border: 1px solid black;
            padding: 5px;
        }

        table {
            width: 100%;
        }

        .dokumen {
            text-align: right;
            font-family: 'Times New Roman';
            font-size: 10pt;
        }

        .dokumenfooter {
            position: fixed;
            bottom: 0;
            width: 98%;
        }

        .width-table {
            width: 120px;
        }

        .text-bold {
            /* set semi bold */
            font-weight: 500;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

@php
    function date_format_indo($date)
    {
        $BulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl = substr($date, 8, 2);

        $result = $tgl . ' ' . $BulanIndo[(int) $bulan - 1] . ' ' . $tahun;

        return $result;
    }

@endphp

@foreach ($dataview as $index => $data)

    <body>
        <p class="judul">Packing List</p>
        @php
            $dataItem = $data['items'];

            $dataNull = [
                [
                    'produk' => '',
                    'quantity' => '',
                    'noseri' => '',
                ],
            ];

            // // // tambah data Array
            // // jika dataItem kurang dari 16, maka tambahkan dataNull hingga 16
            if (count($dataItem) < 6) {
                $dataNullIsi = [];
                for ($i = 0; $i < 6 - count($dataItem); $i++) {
                    $dataNullIsi[] = $dataNull;
                }
                $dataItem = array_merge($dataItem, $dataNullIsi);
            }

            $optionsConfiguration = [];

            //
            for ($o = 0; $o < 3; $o++) {
                $optionsConfiguration[] = $dataNull;
            }
        @endphp
        <header>
            <table>
                <tr>
                    <td class="width-table text-bold">Product Name</td>
                    <td>:</td>
                    <td>{{ $data['produk'] }}</td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td>Model</td>
                    <td>:</td>
                    <td>{{ $data['model'] }}</td>
                    <td colspan="3"></td>
                </tr>

                <tr>
                    <td>Serial Number</td>
                    <td>:</td>
                    <td>{{ $data['noseri'] }}</td>
                    <td colspan="3"></td>
                </tr>
            </table>
        </header>

        <main>
            <table>
                <thead class="text-center">
                    <tr>
                        <td>Item</td>
                        <td>No.</td>
                        <td>Contents</td>
                        <td>Qty</td>
                        <td>Remark</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="{{ count($dataItem) + 1 }}" class="text-center">Standart Configuration</td>
                    </tr>
                    @foreach ($dataItem as $item)
                        <tr class="text-uppercase">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ isset($item->produk) ? $item->produk : '' }}
                                {{ isset($item->varian) ? $item->varian : '' }}</td>
                            <td class="text-center">{{ isset($item->produk) && $item->produk != 'TAS' ? 1 : '' }}</td>
                            <td>{{ isset($item->noseri) ? $item->noseri : '' }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td rowspan="{{ count($optionsConfiguration) + 1 }}" class="text-center">Options
                            Configuration
                        </td>
                        @foreach ($optionsConfiguration as $options)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ isset($options['product_name']) ? $options['product_name'] : '' }}</td>
                        <td class="text-center">{{ isset($options['quantity']) ? $options['quantity'] : '' }}</td>
                        <td>{{ isset($options['serial_number']) ? $options['serial_number'] : '' }}</td>
                    </tr>
@endforeach
</tr>
</tbody>
</table>
</main>

<footer>
    <table>
        <tr>
            <td class="width-table">Packing Date</td>
            <td>:</td>
            <td>{{ date_format_indo($data['tgl_buat']) }}</td>
        </tr>
        <tr>
            <td>Packer</td>
            <td>:</td>
            <td>{{ $data['packer'] }}</td>
            <td></td>
            <td>Checker :
                <br>
                PT Sinko Prima Alloy
            </td>
        </tr>
    </table>
</footer>

<footer class="dokumenfooter">
    <p class="dokumen">Nomor Dokumen: SPA-FR/QC-13, Tanggal Terbit : 04 Oktober 2018, Revisi : 00</p>
</footer>
</body>
@if ($index !== count($dataview) - 1)
    <div class="page-break"></div>
@endif
@endforeach


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
