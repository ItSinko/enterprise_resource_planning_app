<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Seri</title>
    <style>
        .text-center {
            text-align: center;
        }

        /* large */
        @page {
            margin-top: 0cm;
            margin-left: 0.3cm;
        }

        /* medium */
        /* @page {
            margin-top: 0.2cm;
            margin-left: 0.2cm;
        } */

        /* small */
        /* @page {
            margin: 0.6cm 0cm -0.2cm 0.4cm;
            font-size: 5pt;
        } */

        /* .rectangle {
            width: 100%;
            height: 28%;
            border: 1px solid black;
        } */
    </style>
</head>

<body>
    @php
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
    @endphp
    {{-- ulangi 5 kali --}}
    {{-- large --}}
    @foreach ($seri as $s)
    <div class="image-container">
        <span>Elitech</span><br>
        <img
            src="data:image/png;base64,{{ base64_encode($generator->getBarcode($s, $generator::TYPE_CODE_128_B, 2, 29)) }}" />
        <div class="text-center">{{ $s }}</div>
    </div>
    @endforeach

</body>
</html>
