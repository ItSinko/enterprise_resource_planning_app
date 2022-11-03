<style>
    .va {
        vertical-align: bottom;
    }
</style>
@if ($seri == 'kosong')
    <table border="1">
        <thead>
            <tr>
                <th colspan="22" style="text-align:center">
                    {{ $header }}
                </th>
            </tr>
            <tr>
                <th>No</th>
                <th>No SxO</th>
                <th>No PO</th>
                <th>Tanggal PO</th>
                <th>Surat Jalan</th>
                <th>Tgl Surat Jalan</th>
                <th>No Urut</th>
                <th>No AKN</th>
                <th>Customer / Distributor</th>
                <th>Instansi</th>
                <th>Satuan</th>
                <th>Tanggal Pesan</th>
                <th>Batas Kontrak</th>
                <th>Produk</th>
                <th>Produk(E-Purchasing)</th>
                <th>Detail Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Ongkir</th>
                <th>Subtotal</th>
                <th>Status Penjualan</th>
                <th>Status AKN</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @if ($tampilan == 'merge')
                @foreach ($data as $index => $d)
                    <?php $countprd = 0; ?>
                    <tr>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">{{ $loop->iteration }}
                        </td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">{{ $d->so }}
                        </td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">{{ $d->no_po }}
                        </td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                            {{ date('d-m-Y', strtotime($d->tgl_po)) }}</td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                            @if (count($d->DetailPesanan) > 0)
                                @if ($d->DetailPesanan[0]->getJumlahProduk() > 1)
                                    @foreach ($d->DetailPesanan[0]->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                                        @foreach ($p->DetailLogistik as $q)
                                            {{ $q->Logistik->nosurat }}
                                        @endforeach
                                    @endforeach
                                @else
                                    @foreach ($d->DetailPesanan[0]->DetailPesananProduk as $p)
                                        @foreach ($p->DetailLogistik as $q)
                                            {{ $q->Logistik->nosurat }}
                                        @endforeach
                                    @endforeach
                                @endif
                            @else
                                @if (isset($d->DetailPesananPart[0]->DetailLogistikPart->Logistik))
                                    {{ $d->DetailPesananPart[0]->DetailLogistikPart->Logistik->nosurat }}
                                @endif
                            @endif
                        </td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                            @if (count($d->DetailPesanan) > 0)
                                @if ($d->DetailPesanan[0]->getJumlahProduk() > 1)
                                    @foreach ($d->DetailPesanan[0]->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                                        @foreach ($p->DetailLogistik as $q)
                                            {{ date('d-m-Y', strtotime($q->Logistik->tgl_kirim)) }}
                                        @endforeach
                                    @endforeach
                                @else
                                    @foreach ($d->DetailPesanan[0]->DetailPesananProduk as $p)
                                        @foreach ($p->DetailLogistik as $q)
                                            {{ date('d-m-Y', strtotime($q->Logistik->tgl_kirim)) }}
                                        @endforeach
                                    @endforeach
                                @endif
                            @else
                                @if (isset($d->DetailPesananPart[0]->DetailLogistikPart->Logistik))
                                    {{ date('d-m-Y', strtotime($d->DetailPesananPart[0]->DetailLogistikPart->Logistik->tgl_kirim)) }}
                                @endif
                            @endif
                        </td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                            @if ($d->Ekatalog)
                                {{ $d->Ekatalog->no_urut }}
                            @else
                                -
                            @endif
                        </td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                            @if ($d->Ekatalog)
                                {{ $d->Ekatalog->no_paket }}
                            @else
                                -
                            @endif
                        </td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                            @if ($d->Ekatalog)
                                {{ $d->Ekatalog->Customer->nama }}
                            @elseif($d->Spa)
                                {{ $d->Spa->Customer->nama }}
                            @elseif($d->Spb)
                                {{ $d->Spb->Customer->nama }}
                            @endif
                        </td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                            @if ($d->Ekatalog)
                                {{ $d->Ekatalog->instansi }}
                            @else
                                -
                            @endif
                        </td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                            @if ($d->Ekatalog)
                                {{ $d->Ekatalog->satuan }}
                            @else
                                -
                            @endif
                        </td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                            @if ($d->Ekatalog)
                                {{ date('d-m-Y', strtotime($d->Ekatalog->tgl_buat)) }}
                            @else
                                -
                            @endif
                        </td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                            @if ($d->Ekatalog)
                                {{ date('d-m-Y', strtotime($d->Ekatalog->tgl_kontrak)) }}
                            @else
                                -
                            @endif
                        </td>

                        @if ($d->DetailPesanan)
                            @foreach ($d->DetailPesanan as $e)
                                <?php $countdet = 0; ?>
                                @if ($countprd > 0)
                    <tr>
                @endif
                <td rowspan="{{ $e->DetailPesananProduk->count() }}">
                    {{ $e->PenjualanProduk->nama }}
                </td>
                <td rowspan="{{ $e->DetailPesananProduk->count() }}">
                    @if ($e->PenjualanProduk->nama_alias != '')
                        {{ $e->PenjualanProduk->nama_alias }}
                    @else
                        {{ $e->PenjualanProduk->nama }}
                    @endif
                </td>
                @foreach ($e->DetailPesananProduk as $f)
                    @if ($countdet > 0)
                        <tr>
                    @endif
                    <td>{{ $f->GudangBarangJadi->Produk->nama }} {{ $f->GudangBarangJadi->nama }}</td>

                    @if ($countdet <= 0)
                        <td rowspan="{{ $e->DetailPesananProduk->count() }}">{{ $e->jumlah }}</td>
                        <td rowspan="{{ $e->DetailPesananProduk->count() }}">{{ $e->harga }}</td>
                        <td rowspan="{{ $e->DetailPesananProduk->count() }}">{{ $e->ongkir }}</td>
                        <td rowspan="{{ $e->DetailPesananProduk->count() }}">
                            {{ $e->jumlah * $e->harga + $e->ongkir }}</td>
                        @if ($countprd <= 0)
                            <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                                {{ $d->State->nama }}
                            </td>
                            <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                                @if ($d->Ekatalog)
                                    {{ $d->Ekatalog->status }}
                                @else
                                    -
                                @endif
                            </td>
                            <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                                @if ($d->Ekatalog)
                                    {{ $d->Ekatalog->ket }}
                                @elseif($d->Spa)
                                    {{ $d->Spa->ket }}
                                @elseif($d->Spb)
                                    {{ $d->Spb->ket }}
                                @endif
                            </td>
                        @endif
                    @endif
                    </tr>
                    <?php $countdet++; ?>
                @endforeach
                <?php $countprd++; ?>
            @endforeach
@endif

@if ($d->DetailPesananPart)
    @foreach ($d->DetailPesananPart as $e)
        @if ($countprd > 0)
            <tr>
        @endif
        <td>{{ $e->Sparepart->nama }}</td>
        <td>-</td>
        <td>{{ $e->jumlah }}</td>
        <td>{{ $e->harga }}</td>
        <td>{{ $e->ongkir }}</td>
        <td>{{ $e->jumlah * $e->harga + $e->ongkir }}</td>
        @if ($countprd <= 0)
            <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                {{ $d->State->nama }}
            </td>
            <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                @if ($d->Ekatalog)
                    {{ $d->Ekatalog->status }}
                @else
                    -
                @endif
            </td>
            <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                @if ($d->Ekatalog)
                    {{ $d->Ekatalog->ket }}
                @elseif($d->Spa)
                    {{ $d->Spa->ket }}
                @elseif($d->Spb)
                    {{ $d->Spb->ket }}
                @endif
            </td>
        @endif
        </tr>
        <?php $countprd++; ?>
    @endforeach
@endif
@endforeach
@else
@foreach ($data as $d)
    <tr>
        <td style="text-align:left">{{ $loop->iteration }}</td>
        <td style="text-align:left">{{ $d->Pesanan->so }}</td>
        <td style="text-align:left">{{ $d->Pesanan->no_po }}</td>
        <td style="text-align:left">
            {{ $d->Pesanan->tgl_po }}
        </td>
        <td style="text-align:left">
            @if (isset($d->PenjualanProduk))
                @if ($d->getJumlahProduk() > 1)
                    @foreach ($d->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                        @foreach ($p->DetailLogistik as $q)
                            {{ $q->Logistik->nosurat }}
                        @endforeach
                    @endforeach
                @else
                    @foreach ($d->DetailPesananProduk as $p)
                        @foreach ($p->DetailLogistik as $q)
                            {{ $q->Logistik->nosurat }}
                        @endforeach
                    @endforeach
                @endif
            @else
                @if (isset($d->DetailLogistikPart->Logistik))
                    {{ $d->DetailLogistikPart->Logistik->nosurat }}
                @endif
            @endif
        </td>
        <td style="text-align:left">
            @if (isset($d->PenjualanProduk))
                @if ($d->getJumlahProduk() > 1)
                    @foreach ($d->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                        @foreach ($p->DetailLogistik as $q)
                            {{ $q->Logistik->tgl_kirim }}
                        @endforeach
                    @endforeach
                @else
                    @foreach ($d->DetailPesananProduk as $p)
                        @foreach ($p->DetailLogistik as $q)
                            {{ $q->Logistik->tgl_kirim }}
                        @endforeach
                    @endforeach
                @endif
            @else
                @if (isset($d->DetailLogistikPart->Logistik))
                    {{ $d->DetailLogistikPart->Logistik->tgl_kirim }}
                @endif
            @endif
        </td>
        <td style="text-align:left">
            @if (isset($d->Pesanan->Ekatalog))
                {{ $d->Pesanan->Ekatalog->no_urut }}
            @endif
        </td>
        <td style="text-align:left">
            @if (isset($d->Pesanan->Ekatalog))
                {{ $d->Pesanan->Ekatalog->no_paket }}
            @endif
        </td>
        <td style="text-align:left">
            @if (isset($d->Pesanan->Ekatalog))
                {{ $d->Pesanan->Ekatalog->Customer->nama }}
            @elseif(isset($d->Pesanan->Spa))
                {{ $d->Pesanan->Spa->Customer->nama }}
            @else
                {{ $d->Pesanan->Spb->Customer->nama }}
            @endif
        </td>
        <td style="text-align:left">
            @if (isset($d->Pesanan->Ekatalog))
                {{ $d->Pesanan->Ekatalog->instansi }}
            @endif
        </td>
        <td style="text-align:left">
            @if (isset($d->Pesanan->Ekatalog))
                {{ $d->Pesanan->Ekatalog->satuan }}
            @endif
        </td>
        <td style="text-align:left">
            @if (isset($d->Pesanan->Ekatalog))
                {{ $d->Pesanan->Ekatalog->tgl_buat }}
            @endif
        </td>
        <td style="text-align:left">
            @if (isset($d->Pesanan->Ekatalog))
                {{ $d->Pesanan->Ekatalog->tgl_kontrak }}
            @endif
        </td>
        <td style="text-align:left">
            @if (isset($d->PenjualanProduk))
                {{ $d->PenjualanProduk->nama }}
            @else
                {{ $d->Sparepart->nama }}
            @endif
        <td style="text-align:left">
            @if (isset($d->PenjualanProduk))
                @if ($d->PenjualanProduk->nama_alias != '')
                    {{ $d->PenjualanProduk->nama_alias }}
                @else
                    {{ $d->PenjualanProduk->nama }}
                @endif
            @else
                {{ $d->Sparepart->nama }}
            @endif
        </td>
        <td style="text-align:left">
            @if (isset($d->PenjualanProduk))
                @foreach ($d->DetailPesananProduk as $p)
                    {{ $p->gudangbarangjadi->produk->nama }}

                    @if ($p->gudangbarangjadi->nama != '')
                        {{ $p->gudangbarangjadi->nama }}
                    @endif

                    @if (!$loop->last)
                        ,
                    @endif
                @endforeach
            @else
                {{ $d->Sparepart->nama }}
            @endif
        </td>
        <td style="text-align:left">
            {{ $d->jumlah }}
        </td>
        <td style="text-align:left">{{ $d->harga }}</td>
        <td style="text-align:left">{{ $d->ongkir }}</td>
        <td style="text-align:left">{{ $d->jumlah * $d->harga + $d->total }}</td>
        <td style="text-align:left">{{ $d->pesanan->state->nama }}</td>
        <td style="text-align:left">
            @if (isset($d->Pesanan->Ekatalog))
                {{ $d->Pesanan->Ekatalog->status }}
            @endif
        </td>
        <td style="text-align:left">{{ $d->Pesanan->ket }}</td>
    </tr>
@endforeach
@endif
</tbody>
{{-- <tbody>
        @foreach ($data as $index => $d)
        <?php
        $cPrd = $d->getJumlahPaket();
        $cPart = $d->DetailPesananPart->count();
        $tot = $cPrd + $cPart;
        ?>
        <tr>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$index + 1}}
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->so}}
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->no_po}}
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->tgl_po}}
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                @if (count($d->DetailPesanan) > 0)
                    @if ($d->DetailPesanan[0]->getJumlahPaket() > 1)

                    @foreach ($d->DetailPesanan[0]->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                    @foreach ($p->DetailLogistik as $q)
                    {{$q->Logistik->nosurat}}
                    @endforeach
                    @endforeach


                    @else

                    @foreach ($d->DetailPesanan[0]->DetailPesananProduk as $p)
                    @foreach ($p->DetailLogistik as $q)
                    {{$q->Logistik->nosurat}}
                    @endforeach
                    @endforeach


                    @endif
            @else
                @if (isset($d->DetailPesananPart[0]->DetailLogistikPart->Logistik))
                {{$d->DetailPesananPart[0]->DetailLogistikPart->Logistik->nosurat}}
                @endif
            @endif

            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                @if (count($d->DetailPesanan) > 0)
                @if ($d->DetailPesanan[0]->getJumlahProduk() > 1)

                @foreach ($d->DetailPesanan[0]->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                @foreach ($p->DetailLogistik as $q)
                {{$q->Logistik->tgl_kirim}}
                @endforeach
                @endforeach


                @else

                @foreach ($d->DetailPesanan[0]->DetailPesananProduk as $p)
                @foreach ($p->DetailLogistik as $q)
                {{$q->Logistik->tgl_kirim}}
                @endforeach
                @endforeach


                @endif
        @else
            @if (isset($d->DetailPesananPart[0]->DetailLogistikPart->Logistik))
            {{$d->DetailPesananPart[0]->DetailLogistikPart->Logistik->tgl_kirim}}
            @endif
        @endif
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if (isset($d->Ekatalog))
                        {{$d->Ekatalog->no_paket}}
                        @endif
            </td>


            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if (isset($d->Ekatalog))
                        {{$d->Ekatalog->Customer->nama}}
                        @elseif(isset($d->Spa))
                        {{$d->Spa->Customer->nama}}
                        @elseif(isset($d->Spb))
                        {{$d->Spb->Customer->nama}}
                        @endif
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if (isset($d->Ekatalog))
                        {{$d->Ekatalog->instansi}}
                        @endif
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if (isset($d->Ekatalog))
                        {{$d->Ekatalog->satuan}}
                        @endif
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if (isset($d->Ekatalog))
                        {{$d->Ekatalog->tgl_buat}}
                        @endif
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if (isset($d->Ekatalog))
                        {{$d->Ekatalog->tgl_kontrak}}
                        @endif
            </td>

            <td>
                @if (count($d->DetailPesanan) > 0)

                    @if ($d->DetailPesanan[0]->PenjualanProduk->nama_alias != '')
                    {{ $d->DetailPesanan[0]->PenjualanProduk->nama_alias }}
                    @else
                    {{ $d->DetailPesanan[0]->PenjualanProduk->nama }}
                    @endif

                @else
                {{ $d->DetailPesananPart[0]->Sparepart->nama}}
                @endif
            </td>
            <td>
                @if (count($d->DetailPesanan) > 0)

                @foreach ($d->DetailPesanan[0]->DetailPesananProduk as $p)
                {{ $p->gudangbarangjadi->produk->nama}}

                @if ($p->gudangbarangjadi->nama != '')
                {{ $p->gudangbarangjadi->nama}}
                @endif

                @if (!$loop->last)
                ,
                @endif

                @endforeach


                @endif
            </td>
            <td>
                @if (count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->jumlah}}
                @else
                {{ $d->DetailPesananPart[0]->jumlah}}
                @endif
            </td>
            <td>
                @if (count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->harga}}
                @else
                {{ $d->DetailPesananPart[0]->harga}}
                @endif
            </td>
            <td>
                @if (count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->ongkir}}
                @else
                {{ $d->DetailPesananPart[0]->ongkir}}
                @endif
            </td>
            <td>
                @if (count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->harga *  $d->DetailPesanan[0]->jumlah}}
                @else
                {{ $d->DetailPesananPart[0]->harga *  $d->DetailPesananPart[0]->jumlah}}
                @endif
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->state->nama}}
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                @if (isset($d->Ekatalog))
                {{$d->Ekatalog->status}}
                @endif
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->ket}}
            </td>

        </tr>

        {{-- Rowspan --}}
{{-- @if (count($d->DetailPesanan) > 0)
             @for ($i = 1; $i < $d->getJumlahPaket(); $i++)
             <tr>
                <td>
                    @if ($d->DetailPesanan[$i]->PenjualanProduk->nama_alias != '')
                    {{ $d->DetailPesanan[$i]->PenjualanProduk->nama_alias }}
                    @else
                    {{ $d->DetailPesanan[$i]->PenjualanProduk->nama }}
                    @endif
                </td>
                <td>
                    @foreach ($d->DetailPesanan[$i]->DetailPesananProduk as $p)
                {{ $p->gudangbarangjadi->produk->nama}}

                @if ($p->gudangbarangjadi->nama != '')
                {{ $p->gudangbarangjadi->nama}}
                @endif

                @if (!$loop->last)
                ,
                @endif

                @endforeach
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->jumlah}}
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->harga}}
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->ongkir}}
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->harga *  $d->DetailPesanan[$i]->jumlah }}
                </td>
             </tr>
             @endfor
        @else
            @for ($i = 1; $i < $d->DetailPesananPart->count(); $i++)
            <tr>
              <td> {{ $d->DetailPesananPart[$i]->Sparepart->nama }}</td>
              <td></td>
              <td>
                {{ $d->DetailPesananPart[$i]->jumlah}}
            </td>
              <td>
                {{ $d->DetailPesananPart[$i]->harga}}
            </td>
              <td>
                {{ $d->DetailPesananPart[$i]->ongkir}}
            </td>
              <td>
                {{ $d->DetailPesananPart[$i]->harga *  $d->DetailPesananPart[$i]->jumlah }}
            </td>
            </tr>
            @endfor
        @endif
        @endforeach
    </tbody> --}}
</table>
@else
<table border="1">
    <thead>
        <tr>
            <th colspan="23" style="text-align:center">
                {{ $header }} + No Seri
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No SO</th>
            <th>No PO</th>
            <th>Tanggal PO</th>
            <th>Surat Jalan</th>
            <th>Tgl Surat Jalan</th>
            <th>No Urut</th>
            <th>No AKN</th>
            <th>Customer / Distributor</th>
            <th>Instansi</th>
            <th>Satuan</th>
            <th>Tgl Buat</th>
            <th>Tgl Kontrak</th>
            <th>Produk</th>
            <th>Detail Produk</th>
            <th>No Seri</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Ongkir</th>
            <th>Subtotal</th>
            <th>Status Penjualan</th>
            <th>Status AKN</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @if ($tampilan == 'merge')
            @foreach ($data as $index => $d)
                <?php $countprd = 0; ?>
                <tr>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">{{ $loop->iteration }}
                    </td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">{{ $d->so }}
                    </td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">{{ $d->no_po }}
                    </td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                        {{ date('d-m-Y', strtotime($d->tgl_po)) }}</td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                        @if (count($d->DetailPesanan) > 0)
                            @if ($d->DetailPesanan[0]->getJumlahProduk() > 1)
                                @foreach ($d->DetailPesanan[0]->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                                    @foreach ($p->DetailLogistik as $q)
                                        {{ $q->Logistik->nosurat }}
                                    @endforeach
                                @endforeach
                            @else
                                @foreach ($d->DetailPesanan[0]->DetailPesananProduk as $p)
                                    @foreach ($p->DetailLogistik as $q)
                                        {{ $q->Logistik->nosurat }}
                                    @endforeach
                                @endforeach
                            @endif
                        @else
                            @if (isset($d->DetailPesananPart[0]->DetailLogistikPart->Logistik))
                                {{ $d->DetailPesananPart[0]->DetailLogistikPart->Logistik->nosurat }}
                            @endif
                        @endif
                    </td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                        @if (count($d->DetailPesanan) > 0)
                            @if ($d->DetailPesanan[0]->getJumlahProduk() > 1)
                                @foreach ($d->DetailPesanan[0]->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                                    @foreach ($p->DetailLogistik as $q)
                                        {{ date('d-m-Y', strtotime($q->Logistik->tgl_kirim)) }}
                                    @endforeach
                                @endforeach
                            @else
                                @foreach ($d->DetailPesanan[0]->DetailPesananProduk as $p)
                                    @foreach ($p->DetailLogistik as $q)
                                        {{ date('d-m-Y', strtotime($q->Logistik->tgl_kirim)) }}
                                    @endforeach
                                @endforeach
                            @endif
                        @else
                            @if (isset($d->DetailPesananPart[0]->DetailLogistikPart->Logistik))
                                {{ date('d-m-Y', strtotime($d->DetailPesananPart[0]->DetailLogistikPart->Logistik->tgl_kirim)) }}
                            @endif
                        @endif
                    </td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                        @if ($d->Ekatalog)
                            {{ $d->Ekatalog->no_urut }}
                        @else
                            -
                        @endif
                    </td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                        @if ($d->Ekatalog)
                            {{ $d->Ekatalog->no_paket }}
                        @else
                            -
                        @endif
                    </td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                        @if ($d->Ekatalog)
                            {{ $d->Ekatalog->Customer->nama }}
                        @elseif($d->Spa)
                            {{ $d->Spa->Customer->nama }}
                        @elseif($d->Spb)
                            {{ $d->Spb->Customer->nama }}
                        @endif
                    </td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                        @if ($d->Ekatalog)
                            {{ $d->Ekatalog->instansi }}
                        @else
                            -
                        @endif
                    </td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                        @if ($d->Ekatalog)
                            {{ $d->Ekatalog->satuan }}
                        @else
                            -
                        @endif
                    </td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                        @if ($d->Ekatalog)
                            {{ date('d-m-Y', strtotime($d->Ekatalog->tgl_buat)) }}
                        @else
                            -
                        @endif
                    </td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                        @if ($d->Ekatalog)
                            {{ date('d-m-Y', strtotime($d->Ekatalog->tgl_kontrak)) }}
                        @else
                            -
                        @endif
                    </td>

                    @if ($d->DetailPesanan)
                        @foreach ($d->DetailPesanan as $e)
                            <?php $countdet = 0; ?>
                            @if ($countprd > 0)
                <tr>
            @endif
            <td rowspan="{{ $e->DetailPesananProduk->count() }}">
                {{ $e->PenjualanProduk->nama }}
            </td>
            <td rowspan="{{ $e->DetailPesananProduk->count() }}">
                @if ($e->PenjualanProduk->nama_alias != '')
                    {{ $e->PenjualanProduk->nama_alias }}
                @else
                    {{ $e->PenjualanProduk->nama }}
                @endif
            </td>
            @foreach ($e->DetailPesananProduk as $f)
                @if ($countdet > 0)
                    <tr>
                @endif
                <td>{{ $f->GudangBarangJadi->Produk->nama }} {{ $f->GudangBarangJadi->nama }}</td>
                <td>
                    @if ($f->NoseriDetailPesanan)
                        @foreach ($f->NoseriDetailPesanan as $g)
                            {{ $loop->first ? '' : ', ' }}
                            {{ $g->NoseriTGbj->NoseriBarangJadi->noseri }}
                        @endforeach
                    @endif
                </td>
                @if ($countdet <= 0)
                    <td rowspan="{{ $e->DetailPesananProduk->count() }}">{{ $e->jumlah }}</td>
                    <td rowspan="{{ $e->DetailPesananProduk->count() }}">{{ $e->harga }}</td>
                    <td rowspan="{{ $e->DetailPesananProduk->count() }}">{{ $e->ongkir }}</td>
                    <td rowspan="{{ $e->DetailPesananProduk->count() }}">{{ $e->jumlah * $e->harga + $e->ongkir }}
                    </td>
                    @if ($countprd <= 0)
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                            {{ $d->State->nama }}
                        </td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                            @if ($d->Ekatalog)
                                {{ $d->Ekatalog->status }}
                            @else
                                -
                            @endif
                        </td>
                        <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                            @if ($d->Ekatalog)
                                {{ $d->Ekatalog->ket }}
                            @elseif($d->Spa)
                                {{ $d->Spa->ket }}
                            @elseif($d->Spb)
                                {{ $d->Spb->ket }}
                            @endif
                        </td>
                    @endif
                @endif
                </tr>
                <?php $countdet++; ?>
            @endforeach
            <?php $countprd++; ?>
        @endforeach
        @endif

        @if ($d->DetailPesananPart)
            @foreach ($d->DetailPesananPart as $e)
                @if ($countprd > 0)
                    <tr>
                @endif
                <td>{{ $e->Sparepart->nama }}</td>
                <td>-</td>
                <td>-</td>
                <td>{{ $e->jumlah }}</td>
                <td>{{ $e->harga }}</td>
                <td>{{ $e->ongkir }}</td>
                <td>{{ $e->jumlah * $e->harga + $e->ongkir }}</td>
                @if ($countprd <= 0)
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                        {{ $d->State->nama }}
                    </td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                        @if ($d->Ekatalog)
                            {{ $d->Ekatalog->status }}
                        @else
                            -
                        @endif
                    </td>
                    <td rowspan="{{ $d->getJumlahPaket() + $d->DetailPesananPart->count() }}">
                        @if ($d->Ekatalog)
                            {{ $d->Ekatalog->ket }}
                        @elseif($d->Spa)
                            {{ $d->Spa->ket }}
                        @elseif($d->Spb)
                            {{ $d->Spb->ket }}
                        @endif
                    </td>
                @endif
                </tr>
                <?php $countprd++; ?>
            @endforeach
        @endif
        @endforeach
    @else
        @foreach ($data as $d)
            <tr>
                <td style="text-align:left">{{ $loop->iteration }}</td>

                <td style="text-align:left">{{ $d->Pesanan->so }}</td>
                <td style="text-align:left">
                    {{ $d->Pesanan->no_po }}
                </td>
                <td style="text-align:left">
                    {{ $d->Pesanan->tgl_po }}
                </td>
                <td style="text-align:left">
                    @if (isset($d->PenjualanProduk))
                        @if ($d->getJumlahProduk() > 1)
                            @foreach ($d->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                                @foreach ($p->DetailLogistik as $q)
                                    {{ $q->Logistik->nosurat }}
                                @endforeach
                            @endforeach
                        @else
                            @foreach ($d->DetailPesananProduk as $p)
                                @foreach ($p->DetailLogistik as $q)
                                    {{ $q->Logistik->nosurat }}
                                @endforeach
                            @endforeach
                        @endif
                    @else
                        @if (isset($d->DetailLogistikPart->Logistik))
                            {{ $d->DetailLogistikPart->Logistik->nosurat }}
                        @endif
                    @endif
                </td>
                <td style="text-align:left">
                    @if (isset($d->PenjualanProduk))
                        @if ($d->getJumlahProduk() > 1)
                            @foreach ($d->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                                @foreach ($p->DetailLogistik as $q)
                                    {{ $q->Logistik->tgl_kirim }}
                                @endforeach
                            @endforeach
                        @else
                            @foreach ($d->DetailPesananProduk as $p)
                                @foreach ($p->DetailLogistik as $q)
                                    {{ $q->Logistik->tgl_kirim }}
                                @endforeach
                            @endforeach
                        @endif
                    @else
                        @if (isset($d->DetailLogistikPart->Logistik))
                            {{ $d->DetailLogistikPart->Logistik->tgl_kirim }}
                        @endif
                    @endif
                </td>
                <td style="text-align:left">
                    @if (isset($d->Pesanan->Ekatalog))
                        {{ $d->Pesanan->Ekatalog->no_urut }}
                    @endif
                </td>
                <td style="text-align:left">
                    @if (isset($d->Pesanan->Ekatalog))
                        {{ $d->Pesanan->Ekatalog->no_paket }}
                    @endif
                </td>
                <td style="text-align:left">
                    @if (isset($d->Pesanan->Ekatalog))
                        {{ $d->Pesanan->Ekatalog->Customer->nama }}
                    @elseif(isset($d->Pesanan->Spa))
                        {{ $d->Pesanan->Spa->Customer->nama }}
                    @else
                        {{ $d->Pesanan->Spb->Customer->nama }}
                    @endif
                </td>
                <td style="text-align:left">
                    @if (isset($d->Pesanan->Ekatalog))
                        {{ $d->Pesanan->Ekatalog->instansi }}
                    @endif
                </td>
                <td style="text-align:left">
                    @if (isset($d->Pesanan->Ekatalog))
                        {{ $d->Pesanan->Ekatalog->satuan }}
                    @endif
                </td>
                <td style="text-align:left">
                    @if (isset($d->Pesanan->Ekatalog))
                        {{ $d->Pesanan->Ekatalog->tgl_buat }}
                    @endif
                </td>
                <td style="text-align:left">
                    @if (isset($d->Pesanan->Ekatalog))
                        {{ $d->Pesanan->Ekatalog->tgl_kontrak }}
                    @endif
                </td>
                <td style="text-align:left">
                    @if (isset($d->PenjualanProduk))
                        {{ $d->PenjualanProduk->nama }}
                    @else
                        {{ $d->Sparepart->nama }}
                    @endif
                </td>
                <td style="text-align:left">
                    @if (isset($d->PenjualanProduk))
                        @if ($d->PenjualanProduk->nama_alias != '')
                            {{ $d->PenjualanProduk->nama_alias }}
                        @else
                            {{ $d->PenjualanProduk->nama }}
                        @endif
                    @else
                        {{ $d->Sparepart->nama }}
                    @endif
                </td>
                <td>
                    @if (isset($d->PenjualanProduk))
                        @foreach ($d->DetailPesananProduk as $p)
                            {{ $p->gudangbarangjadi->produk->nama }}

                            @if ($p->gudangbarangjadi->nama != '')
                                {{ $p->gudangbarangjadi->nama }}
                            @endif

                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    @else
                        {{ $d->Sparepart->nama }}
                    @endif
                </td>


                <td style="text-align:left">
                    @if (isset($d->PenjualanProduk))
                        @foreach ($d->DetailPesananProduk as $p)
                            @foreach ($p->NoseriDetailPesanan as $n)
                                {{ $n->NoseriTGbj->NoseriBarangJadi->noseri }}
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    @endif
                </td>
                <td style="text-align:left">
                    {{ $d->jumlah }}
                </td>
                <td style="text-align:left">{{ $d->harga }}</td>
                <td style="text-align:left">{{ $d->ongkir }}</td>
                <td style="text-align:left">{{ $d->jumlah * $d->harga + $d->total }}</td>
                <td style="text-align:left">{{ $d->pesanan->state->nama }}</td>
                <td style="text-align:left">
                    @if (isset($d->Pesanan->Ekatalog))
                        {{ $d->Pesanan->Ekatalog->status }}
                    @endif
                </td>
                <td style="text-align:left">{{ $d->Pesanan->ket }}</td>

            </tr>
        @endforeach

        @endif
    </tbody>
    {{-- <tbody>
        @foreach ($data as $index => $d)
        <?php
        $cPrd = $d->getJumlahPaket();
        $cPart = $d->DetailPesananPart->count();
        $tot = $cPrd + $cPart;
        ?>

        <tr>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$index + 1}}
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->no_po}}
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->tgl_po}}
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                @if (count($d->DetailPesanan) > 0)
                    @if ($d->DetailPesanan[0]->getJumlahProduk() > 1)

                    @foreach ($d->DetailPesanan[0]->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                    @foreach ($p->DetailLogistik as $q)
                    {{$q->Logistik->nosurat}}
                    @endforeach
                    @endforeach


                    @else

                    @foreach ($d->DetailPesanan[0]->DetailPesananProduk as $p)
                    @foreach ($p->DetailLogistik as $q)
                    {{$q->Logistik->nosurat}}
                    @endforeach
                    @endforeach


                    @endif
            @else
                @if (isset($d->DetailPesananPart[0]->DetailLogistikPart->Logistik))
                {{$d->DetailPesananPart[0]->DetailLogistikPart->Logistik->nosurat}}
                @endif
            @endif

            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                @if (count($d->DetailPesanan) > 0)
                @if ($d->DetailPesanan[0]->getJumlahProduk() > 1)

                @foreach ($d->DetailPesanan[0]->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                @foreach ($p->DetailLogistik as $q)
                {{$q->Logistik->tgl_kirim}}
                @endforeach
                @endforeach


                @else

                @foreach ($d->DetailPesanan[0]->DetailPesananProduk as $p)
                @foreach ($p->DetailLogistik as $q)
                {{$q->Logistik->tgl_kirim}}
                @endforeach
                @endforeach


                @endif
        @else
            @if (isset($d->DetailPesananPart[0]->DetailLogistikPart->Logistik))
            {{$d->DetailPesananPart[0]->DetailLogistikPart->Logistik->tgl_kirim}}
            @endif
        @endif
            </td>

            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if (isset($d->Ekatalog))
                        {{$d->Ekatalog->no_paket}}
                        @endif
            </td>


            <td rowspan="{{$tot}}" style="vertical-align: center;">
                        @if (isset($d->Ekatalog))
                        {{$d->Ekatalog->Customer->nama}}
                        @elseif(isset($d->Spa))
                        {{$d->Spa->Customer->nama}}
                        @elseif(isset($d->Spb))
                        {{$d->Spb->Customer->nama}}
                        @endif
            </td>




            <td>
                @if (count($d->DetailPesanan) > 0)

                    @if ($d->DetailPesanan[0]->PenjualanProduk->nama_alias != '')
                    {{ $d->DetailPesanan[0]->PenjualanProduk->nama_alias }}
                    @else
                    {{ $d->DetailPesanan[0]->PenjualanProduk->nama }}
                    @endif

                @else
                {{ $d->DetailPesananPart[0]->Sparepart->nama}}
                @endif
            </td>
            <td>
                @if (count($d->DetailPesanan) > 0)

                @foreach ($d->DetailPesanan[0]->DetailPesananProduk as $p)
                {{ $p->gudangbarangjadi->produk->nama}}

                @if ($p->gudangbarangjadi->nama != '')
                {{ $p->gudangbarangjadi->nama}}
                @endif

                @if (!$loop->last)
                ,
                @endif

                @endforeach


                @endif
            </td>
            <td>
                @if (count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->jumlah}}
                @else
                {{ $d->DetailPesananPart[0]->jumlah}}
                @endif
            </td>
            <td>
                @if (count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->harga}}
                @else
                {{ $d->DetailPesananPart[0]->harga}}
                @endif
            </td>
            <td>
                @if (count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->ongkir}}
                @else
                {{ $d->DetailPesananPart[0]->ongkir}}
                @endif
            </td>
            <td>
                @if (count($d->DetailPesanan) > 0)
                {{ $d->DetailPesanan[0]->harga *  $d->DetailPesanan[0]->jumlah}}
                @else
                {{ $d->DetailPesananPart[0]->harga *  $d->DetailPesananPart[0]->jumlah}}
                @endif
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->state->nama}}
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                @if (isset($d->Ekatalog))
                {{$d->Ekatalog->status}}
                @endif
            </td>
            <td rowspan="{{$tot}}" style="vertical-align: center;">
                {{$d->ket}}
            </td>
            <td  style="vertical-align: center;">
                @if (count($d->DetailPesanan) > 0)
                @foreach ($d->DetailPesanan[0]->DetailPesananProduk as $p)
                @foreach ($p->NoseriDetailPesanan as $n)
                    {{$n->NoseriTGbj->NoseriBarangJadi->noseri}}
                        @if (!$loop->last)
            ,
            @endif
                @endforeach
                @if (!$loop->last)
                ,
                @endif
            @endforeach
            @endif
            </td>

        </tr>

        {{-- Rowspan --}}
    {{-- @if (count($d->DetailPesanan) > 0)
             @for ($i = 1; $i < $d->getJumlahPaket(); $i++)
             <tr>
                <td>
                    @if ($d->DetailPesanan[$i]->PenjualanProduk->nama_alias != '')
                    {{ $d->DetailPesanan[$i]->PenjualanProduk->nama_alias }}
                    @else
                    {{ $d->DetailPesanan[$i]->PenjualanProduk->nama }}
                    @endif
                </td>
                <td>
                    @foreach ($d->DetailPesanan[$i]->DetailPesananProduk as $p)
                {{ $p->gudangbarangjadi->produk->nama}}

                @if ($p->gudangbarangjadi->nama != '')
                {{ $p->gudangbarangjadi->nama}}
                @endif

                @if (!$loop->last)
                ,
                @endif

                @endforeach
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->jumlah}}
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->harga}}
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->ongkir}}
                </td>
                <td>
                    {{ $d->DetailPesanan[$i]->harga *  $d->DetailPesanan[$i]->jumlah }}
                </td>
                <td>

                    @foreach ($d->DetailPesanan[$i]->DetailPesananProduk as $p)
                    @foreach ($p->NoseriDetailPesanan as $n)
                        {{$n->NoseriTGbj->NoseriBarangJadi->noseri}}
                            @if (!$loop->last)
                ,
                @endif
                    @endforeach
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach

                </td>
             </tr>
             @endfor
        @else
            @for ($i = 1; $i < $d->DetailPesananPart->count(); $i++)
            <tr>
              <td> {{ $d->DetailPesananPart[$i]->Sparepart->nama }}</td>
              <td></td>
              <td>
                {{ $d->DetailPesananPart[$i]->jumlah}}
            </td>
              <td>
                {{ $d->DetailPesananPart[$i]->harga}}
            </td>
              <td>
                {{ $d->DetailPesananPart[$i]->ongkir}}
            </td>
              <td>
                {{ $d->DetailPesananPart[$i]->harga *  $d->DetailPesananPart[$i]->jumlah }}
            </td>
              <td>

            </td>
            </tr>
            @endfor
        @endif
        @endforeach
    </tbody> --}}
</table>
@endif
