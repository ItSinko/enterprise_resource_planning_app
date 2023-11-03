<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Seri</title>
    <style>
        .text-center {
            text-align: center;
        }

        /* medium */
        @page {
            margin-top: 0.2cm;
            margin-left: 0.2cm;
        }
    </style>
</head>

<body>
    @php
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
    @endphp

    {{-- ulangi 5 kali --}}
    @foreach ($data as $item)
        <div class="image-container">
            <span>Elitech</span><br>
            <img
                src="data:image/png;base64,{{ base64_encode($generator->getBarcode($item, $generator::TYPE_CODE_128_B, 0.95, 29)) }}" />
            <div class="text-center">{{ $item }}</div>
        </div>
    @endforeach
</body>

</html>
