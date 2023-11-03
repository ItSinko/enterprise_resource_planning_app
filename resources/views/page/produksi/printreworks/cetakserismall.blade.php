<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Seri</title>
    <style>
        .text-center {
            text-align: center;
        }

        /* small new */
        @page {
            margin-top: 0.05cm;
            margin-left: 0.3cm;
            margin-bottom: 0cm;
            font-size: 8pt;
        }

        .small-text {
            margin-left: 0.8cm;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @php
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
    @endphp

    {{-- ulangi 5 kali --}}
    @foreach ($data as $item)
    {{-- when last page not page break --}}
        <div class="{{ $loop->last ? '' : 'page-break' }}">
            <img
                src="data:image/png;base64,{{ base64_encode($generator->getBarcode($item, $generator::TYPE_CODE_39, 0.55, 20)) }}" />
            <div class="small-text">{{ $item }}</div>
        </div>
    @endforeach
</body>

</html>
