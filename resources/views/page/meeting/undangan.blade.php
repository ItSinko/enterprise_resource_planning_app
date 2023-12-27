<html>
<style>
    .table {
        width: 100%;
        border-collapse: collapse;
        border: 3px double black;
    }

    .judul {
        text-align: center;
        border-bottom: 1px solid black;
    }

    .judul>td {
        padding: 30px 0px;
    }

    .company {
        font-size: 25px;
        font-weight: bold;
        font-family: 'Arial';
    }

    .undangan {
        font-size: 35px;
        font-weight: bold;
        font-family: 'Times New Roman';
    }

    .header-undangan {
        padding-left: 67%;
        padding-top: 30px;
    }

    .body-undangan {
        padding-left: 15px;
    }

    .core-undangan {
        padding-left: 25px;
    }

    hr {
        border: 1px solid black;
        width: 80%;
        margin-left: 0;
    }

    .footer-undangan {
        font-size: 12px;
        text-align: right;
    }

    .lampiran {
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        margin-top: 30px;
    }

    .page-break {
        page-break-after: always;
    }
</style>

<body>
    <table class="table">
        <tr class="judul">
            <td style="border-right: 1px solid black;"><span class="company">PT SINKO PRIMA ALLOY</span></td>
            <td><span class="undangan">UNDANGAN RAPAT</span></td>
        </tr>
        <tr>
            <td colspan="2" class="header-undangan">
                <span>Surabaya, <b>22 Agustus 2023</b></span><br>
                <span>Kepada Yth: <b>Terlampir</b></span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="body-undangan">
                    <tr>
                        <td>Nomor</td>
                        <td>:</td>
                        <td><b>001/PID/VIII/2023</b></td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td><b>2 Halaman</b></td>
                    </tr>
                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td><b>Presentasi dan Pemahaman SCM</b></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="body-undangan">
                <p>Dengan hormat,</p>
                <p>Mengharapkan kehadiran Bapak / Ibu untuk menghadiri Rapat <b>Presentasi dan Pemahaman SCM</b> <br>
                    yang akan diselenggarakan pada :</p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table class="core-undangan">
                    <tr>
                        <td>Hari / Tanggal</td>
                        <td>:</td>
                        <td><b>Kamis, 24 Agustus 2023</b></td>
                    </tr>
                    <tr>
                        <td>Waktu</td>
                        <td>:</td>
                        <td><b>13.00 WIB - 15.00 WIB</b></td>
                    </tr>
                    <tr>
                        <td>Tempat</td>
                        <td>:</td>
                        <td><b>Ruang Meeting Lantai Blok E-7</b></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top">Agenda / Materi</td>
                        <td style="vertical-align: top">:</td>
                        <td><b>- Presentasi Oleh PPIC <br>
                                - Presentasi Oleh Departemen <br>
                                - Tanggapan dari Direksi <br>
                                - Solusi dari IT
                            </b></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="body-undangan">
                <p>Demikian atas kehadiran Bapak / Ibu / Saudara kami ucapkan terima kasih.</p>
                <p>Apabila berhalangan hadir harap menunjuk wakilnya dan disampaikan secara tertulis kepada atasan atau
                    HRD selambatnya 3 (tiga) hari sebelum tanggal pelaksanaan Rapat.</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="body-undangan">
                <table style="width: 100%">
                    <tr>
                        <td>Dibuat oleh,</td>
                        <td style="width: 20%"></td>
                        <td>Diketahui oleh,</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="height: 10%"></td>
                    </tr>
                    <tr style="text-align: left">
                        <td>
                            <hr>
                        </td>
                        <td></td>
                        <td>
                            <hr>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <p class="footer-undangan">Nomor Dokumen : SPA-FR/DIR-09, Tanggal Terbit : 02 Mei 2017, Revisi : 01</p>

    <div class="page-break"></div>
    <p class="lampiran">LAMPIRAN</p>
    @php
        // buat array peserta rapat
        $peserta = ['Tan Evi Anggraini - HRD', 'Zakkiyatul Barizza - HRD', 'Elita Cindy - PPIC', 'Kukuh Setya - PPIC', 'Bangkit Nata Satria - PPIC'];

        // jika kurang dari 20, buat array kosong dengan isi ...
        if (count($peserta) < 20) {
            $kosong = array_fill(count($peserta), 20 - count($peserta), '...');
        } else {
            $kosong = [];
        }

        // gabungkan array peserta rapat dengan array kosong
        $peserta = array_merge($peserta, $kosong);
    @endphp

    <p>Turut mengundang peserta rapat:</p>
    {{-- bagi setiap per 10 --}}
    @for ($i = 0; $i < count($peserta); $i += 20)
        <table style="width: 100%">
            <tr>
                <td style="width: 50%">
                    <table>
                        @for ($j = $i; $j < $i + 10; $j++)
                            @if (isset($peserta[$j]))
                                <tr>
                                    <td style="width: 5%">{{ $j + 1 }}.</td>
                                    <td style="width: 45%">{{ $peserta[$j] }}</td>
                                </tr>
                            @endif
                        @endfor
                    </table>
                </td>
                <td style="width: 50%">
                    <table>
                        @for ($j = $i + 10; $j < $i + 20; $j++)
                        @if (isset($peserta[$j]))
                            <tr>
                                <td style="width: 5%">{{ $j + 1 }}.</td>
                                <td style="width: 45%">{{ $peserta[$j] }}</td>
                            </tr>
                        @endif
                        @endfor
                    </table>
                </td>
            </tr>
        </table>
    @endfor

</body>

</html>
