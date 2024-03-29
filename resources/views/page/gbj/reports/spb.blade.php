
    <table>
        <tr>
            <td colspan=10 align="center"><b>SURAT PENGANTAR BARANG</b></td>
        </tr>
        @foreach ($header as $h)
        <tr align=center>
            <td colspan="2"><b>No SO</b></td>
            @if (isset($h->pesanan_id))
            <td colspan="3">{{ $h->pesanan->so }}</td>
            @else
            <td colspan="3">-</td>
            @endif
            <td colspan="2"><b>No PO</b></td>
            @if (isset($h->pesanan_id))
            <td colspan="3">{{ $h->pesanan->no_po }}</td>
            @else
            <td colspan="3">-</td>
            @endif
        </tr>
        <tr>
            <td colspan="2"><b>Distributor</b></td>
            @php
                $name = explode('/', $h->pesanan->so);
                if ($name[1] == 'EKAT') {
                    echo '<td colspan="8"><u>'.$h->pesanan->Ekatalog->Customer->nama.'</u></td>';
                } elseif ($name[1] == 'SPA') {
                    echo '<td colspan="8"><u>'.$h->pesanan->Spa->Customer->nama.'</u></td>';
                } elseif ($name[1] == 'SPB') {
                    echo '<td colspan="8"><u>'.$h->pesanan->Spb->Customer->nama.'</u></td>';
                }
            @endphp
        </tr>
        <tr>
            <td colspan="10" align="center"><b>HARAP BARANG-BARANG YANG TERTULIS DI BAWAH INI DISERAHKAN</b></td>
        </tr>
        <tr>
            <td colspan="2"><b>Kepada</b></td>
            <td colspan="8">{{ $h->divisi->nama }}</td>
        </tr>
        @endforeach
        @foreach ($data as $d)
        @php
            $i = 0;
        @endphp
            <tr></tr>
            <tr align="center">
                <td colspan="9" align="center">{{ $d->produk->produk->nama.' '.$d->produk->nama }}</td>
                <td rowspan="2" style="vertical-align:middle; text-align:center;">{{ count($d->seri) }}</td>
            </tr>
            <tr align="center">
                <th colspan="9" align="center"><b>({{ $d->paket->detailpesanan->penjualanproduk->nama }})</b></th>
            </tr>
            <tr>
        @foreach($d->noseri as $r)
            @php
                $i++;
            @endphp
            <td align="left">{{ $i }}.</td>
            @if (isset($r->seri->noseri))
                <td>{{ $r->seri->noseri }}</td>
            @else
                <td>-</td>
            @endif
            @if ($i % 5 == 0)
                </tr><tr>
            @endif
        @endforeach
            </tr>
        @endforeach
        <tr>

        </tr>
        <tr>
            <td colspan="3" align="center">Diserahkan oleh,</td>
            <td colspan="3" align="center">Diperiksa oleh,</td>
            <td colspan="3" align="center">Diterima oleh,</td>
        </tr>
        <tr>
            {{-- <td colspan="3" align="center"><img src="{{ asset('assets/image/accepted.png') }}" alt=""></td> --}}
            <td colspan="3" rowspan="4" align="center">&#10004;erified</td>
            <td colspan="3" rowspan="4" align="center"></td>
            <td colspan="3" rowspan="4" align="center"></td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td colspan="3" align="center" ><b>{{ Auth::user()->nama }}</b></td>
            <td colspan="3" align="center"><b>-</b></td>
            <td colspan="3" align="center"><b>-</b></td>
        </tr>
        <tr>
            <td colspan="3" align="center">(Gudang Barang Jadi)</td>
            <td colspan="3" align="center">-</td>
            <td colspan="3" align="center">-</td>
        </tr>
        @foreach ($header as $hh)
        <tr></tr>
        <tr>
            <td colspan="2"><b>Nomor Dokumen</b></td>
            <td>:</td>
            <td colspan="2">{{ $hh->pesanan->so }}</td>
            <td><b>Tanggal Terbit</b></td>
            <td>:</td>
            <td colspan="2">{{ Carbon\Carbon::createFromFormat('Y-m-d', $hh->tgl_keluar)->isoFormat('D MMMM Y') }}</td>
        </tr>
        @endforeach
    </table>

<style>
.rotate {
    text-align: center;
}
</style>
