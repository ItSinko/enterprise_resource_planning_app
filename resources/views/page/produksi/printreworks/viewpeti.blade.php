<!DOCTYPE html>
<html lang="en">
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<head>
    <title>View Peti</title>

    <style>
        body {
            margin-left: 30%;
            margin-right: 30%;
        }

        /* position all to center */
        .image-container {
            margin-left: 25%;
        }

        .text-center {
            text-align: center;
        }

        table {
            width: 100%;
            margin-bottom: 5px;
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

    @foreach ($loadView as $index => $s)
        <table class="table {{ $index === 0 ? 'first-item' : '' }} {{ $index === count($loadView) - 1 ? 'last-item' : '' }}">
            <tr>
                <td>
                    <div
                        class="image-container">
                        <span>Elitech</span> <br>
                        <svg id="barcode{{ $index }}"></svg>
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
        <script>
        // Generate a barcode for each item in the array
        @foreach ($loadView as $index => $s)
            JsBarcode("#barcode{{ $index }}", "{{ $s['noseri'] }}", {
                displayValue: false // Don't display the text below the barcode
            });
        @endforeach
    </script>
</body>

</html>
