<html>

<body>
    <style>
        .table-header {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black;
        }

        .judul-header {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            background-color: #D9D9D9;
            border: 1px solid black;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            border: 1px solid black;
        }

        .full-border {
            border: 1px solid black;
        }

        .full-border td {
            border: 1px solid black;
        }

        .company {
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            border: 1px solid black;
            text-align: center;
        }

        .table-body {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black;
        }

        .table-body th {
            border: 1px solid black;
            text-align: center;
            text-transform: uppercase;
        }

        .table-body td {
            border: 1px solid black;
            padding: 5px;
        }
        .text-center{
            text-align: center;
        }   

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        
        .table-footer {
            width: 100%;
            padding-left: 20px;
        }
    </style>
    <table class="table-header">
        <tr>
            <td rowspan="6" class="company">PT SINKO PRIMA ALLOY</td>
            <td>Hari, Tanggal</td>
            <td>:</td>
            <td>Selasa, 01 Agustus 2023</td>
            <td class="judul-header">Judul Rapat</td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>:</td>
            <td>08.50 WIB - 17.00 WIB</td>
            <td rowspan="5" class="judul">Monthly Meeting</td>
        </tr>
        <tr>
            <td>Tempat</td>
            <td>:</td>
            <td>Ruang Meeting Lt 2 Blok B</td>
        </tr>
        <tr>
            <td>Pimpinan Rapat</td>
            <td>:</td>
            <td>Yukiyasu</td>
        </tr>
        <tr>
            <td>Moderator</td>
            <td>:</td>
            <td>Tan Evi Anggraini</td>
        </tr>
        <tr>
            <td>Peserta</td>
            <td>:</td>
            <td>Terlampir Daftar Hadir</td>
        </tr>
        <tr class="full-border">
            <td>No Dokumen:</td>
            <td colspan="3">Tanggal Terbit:</td>
            <td>Revisi:</td>
        </tr>
    </table>

    @php
        $agenda = [
            [
                'pj' => 'Tan Evi Anggraini - HRD',
                'uraian' => 'Pembahasan mengenai hasil meeting',
                'kesesuaian' => 'Sesuai, Oleh Tan Evi Anggraini, 28/08/2023 14.17',
            ],
            [
                'pj' => 'Bangkit Nata Satria - PPIC',
                'uraian' => 'Pembahasan mengenai hasil meeting',
                'kesesuaian' => 'Sesuai, Oleh Bangkit Nata Satria, 28/08/2023 14.17',
            ],
        ];

        $hasil = [
            'Pertemuan perlu dicatat secara tertulis sehingga hasil meeting dapat diketahui oleh setiap department.',
            'Perlu dilakukan meeting lanjutan mengenai…..'
        ]

    @endphp
    <table class="table-body">
        <thead>
            <tr>
                <td colspan="4" class="judul-header">detail agenda rapat</td>
            </tr>
            <tr>
                <th style="width: 5%">no</th>
                <th style="width: 30%">penanggung jawab</th>
                <th>uraian</th>
                <th>kesesuaian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agenda as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="text-center">{{ $item['pj'] }}</td>
                    <td>{{ $item['uraian'] }}</td>
                    <td class="text-center">{{ $item['kesesuaian'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table-body">
        <thead>
            <tr>
                <td colspan="2" class="judul-header">hasil rapat</td>
            </tr>
            <tr>
                <th style="width: 5%">no</th>
                <th>uraian hasil rapat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasil as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        <table class="table-footer">
            <tr>
                <td>Surabaya, 28 Agustus 2023</td>
            </tr>
            <tr>
                <td class="text-center">Notulis,</td>
                <td style="width: 45%"></td>
                <td class="text-center">Pimpinan Rapat,</td>
            </tr>
            <tr>
                <td colspan="3" style="height: 10%"></td>
            </tr>
                        <tr>
                <td class="text-center"><hr></td>
                <td style="width: 45%"></td>
                <td class="text-center"><hr></td>
            </tr>
                        <tr>
                <td class="text-center">Tan Evi Anggraini</td>
                <td style="width: 45%"></td>
                <td class="text-center">Yukiyasu</td>
            </tr>
        </table>
        <p><u>Tembusan:</u></p>
        <ol>
            <li>Peserta Rapat</li>
            <li>Direksi</li>
            <li>HRD</li>
            <li>Arsip Kontrol Dokumen</li>
        </ol>
    </footer>

</body>

</html>
