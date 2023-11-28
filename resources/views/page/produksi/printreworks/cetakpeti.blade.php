<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Peti</title>

    <style>
        /* position all to center */
        .image-container {
            margin-left: 23%;
        }

        .text-center {
            text-align: center;
        }

        table {
            width: 100%;
            margin-bottom: 5px;
            margin-left: 2%;
        }

        .box {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }


        .first-item {
            margin-bottom: 15px;
        }

        .last-item {
            margin-top: 15px;
        }

        .table {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td style="width: 80%">
                <h1>ANTROPOMETRI KIT 10</span>
            </td>
            <td style="width: 10%"></td>
            <td style="width: 15%">
                {{-- <div class="box">PETI - 1000</div> --}}
            </td>
        </tr>
    </table>

    @php
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();

        $seri = ['AK1023A000003', 'AK1023B009994', 'AK1023B000005'];
    @endphp

    @foreach ($loadView as $index => $s)
        <table class="table {{ $index === 0 ? 'first-item' : '' }} {{ $index === count($loadView) - 1 ? 'last-item' : '' }}">
            <tr>
                <td>
                    <div
                        class="image-container">
                        <span>Elitech</span> <br>
                        <img
                            src="data:image/png;base64,{{ base64_encode($generator->getBarcode($s['noseri'], $generator::TYPE_CODE_128_B, 2, 60)) }}" />
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-top: 1px solid black">
                    <div class="text-center">{{ $s['noseri'] }}</div>
                </td>
            </tr>
        </table>
    @endforeach
</body>

</html>
