<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            font-family: 'Times New Roman';
            font-size: 11pt;
        }

        .text-center {
            text-align: center;
        }

        .judul {
            font-weight: bold;
            text-align: center;
            font-size: 24px;
            text-decoration: underline;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .col {
            flex: 0 0 50%;
            max-width: 50%;
        }

        header table,
        main table {
            width: 100%;
        }

        header table tr td {
            padding: 5px;
        }

        .td-full-border {
            border: 1px solid black;
        }

        .td-urutan {
            border-left: 1px solid black;
            border-right: 1px solid black;
            border-bottom: 1px solid black;
        }

        /* full border table */
        main table {
            border-collapse: collapse;
            border: 1px solid black;
        }

        main table tr th,
        main table tr td {
            border: 1px solid black;
            padding: 5px;
        }

        footer {
            position: fixed;
            bottom: 0;
        }

        .catatan-table {
            border: 1px solid black;
            border-collapse: collapse;
            width: 100%;
        }

        .catatan-table tr td {
            padding: 5px;
        }

        .table-dibuat {
            padding-left: 5%;
            margin-top: 20px;
        }

        .utama {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .judul-utama {
            border-left: 1px solid black;
            border-right: 1px solid black;
        }

        .utama-dokumen {
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }

        .utama-dokumen-td {
            padding: 10px 0px 10px 5px;
        }

        .small-text {
            font-size: 9pt;
        }
    </style>
</head>

<body>
    @php
        function dateformatIndo($date) {
        $BulanIndo = array(
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        );
        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl = substr($date, 8, 2);
        $result = $tgl . " " . $BulanIndo[(int) $bulan - 1] . " " . $tahun;
        return ($result);
        }
    @endphp

    <table class="utama">
        <tr>
            <td class="td-padding text-center" rowspan="4">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/assets/image/sinko_logo.png'))) }}"
                    width="85">
            </td>
            <td class="judul-utama" rowspan="4">
                <h1 class="judul">PERMINTAAN BARANG JADI</h1>
            </td>
            <td colspan="3">
        <tr>
            <td class="small-text" style="padding-bottom: 10px; padding-left: 5px;">No Dokumen</td>
            <td class="small-text" style="padding-bottom: 10px; padding-left: 5px;">:</td>
            <td class="small-text" style="padding-bottom: 10px; padding-left: 5px;"> SPA-FR/GUD-6</td>
        </tr>
        <tr>
            <td class="small-text utama-dokumen utama-dokumen-td">Revisi</td>
            <td class="small-text utama-dokumen utama-dokumen-td">:</td>
            <td class="small-text utama-dokumen utama-dokumen-td"> 03</td>
        </tr>
        <tr>
            <td class="small-text utama-dokumen-td">Tgl. Terbit</td>
            <td class="small-text utama-dokumen-td">:</td>
            <td class="small-text utama-dokumen-td"> 14 September 2022</td>
        </tr>
        </td>
        </tr>
    </table>

    <header>
        <table>
            <tr>
                <td style="width: 22%">No. Permintaan</td>
                <td style="width: 40%">: {{ $data->no }}</td>
                <td style="width: 15%"></td>
                <td class="text-center td-full-border">No. Referensi</td>
            </tr>
            <tr>
                <td>Tanggal Permintaan</td>
                <td>: {{ dateFormatIndo($data->tanggal_mulai) }}</td>
                <td style="width: 15%"></td>
                <td class="text-center td-urutan"><b>PRD-{{ $data->urutan }}</b></td>
            </tr>
            <tr>
                <td>Tanggal Digunakan</td>
                <td>: {{ dateFormatIndo($data->tanggal_selesai) }}</td>
            </tr>
            <tr>
                <td>Bagian</td>
                <td>: Produksi</td>
            </tr>
            <tr>
                <td>Kegunaan untuk</td>
                <td>: Rework Produksi Antropometri Kit 10</td>
            </tr>
        </table>
    </header>

    <main>
        <table class="text-center">
            <thead>
                <tr>
                    <th style="width: 20%">Kode Barang</th>
                    <th>Nama Barang</th>
                    <th style="width: 15%">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data->item as $item)
                <tr>
                    <td>-</td>
                    <td>{{ $item->produk }}</td>
                    <td>{{ $item->jumlah }} Unit</td>
                </tr>    
                @endforeach
            </tbody>
        </table>
    </main>

    <footer>
        <table class="catatan-table">
            <tr>
                <td>Catatan :</td>
            </tr>
            <tr>
                <td style="color: white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis sed quasi aperiam ipsam, nobis, sapiente sequi et doloremque modi id mollitia assumenda, rerum neque exercitationem est amet iste autem. Non!</td>
            </tr>
        </table>

        <table class="text-center table-dibuat">
            <tr>
                <td style="width: 150px">Dibuat Oleh,</td>
            </tr>
            <tr>
                <td style="padding-top: 70px;">
                    {{ $data->nama }}
                    <hr>
                </td>
            </tr>
        </table>
    </footer>
</body>

</html>
