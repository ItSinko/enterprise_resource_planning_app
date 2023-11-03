<style>
    .va {
        vertical-align: bottom;
    }
</style>

<table border="1">
    <thead>
        <tr>
            <th colspan="22" style="text-align:center">
                Laporan Penjualan
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No SO</th>
            <th>No PO</th>
            <th>Tanggal PO</th>
            <th>Surat Jalan (Tgl Surat Jalan)</th>
            <th>No Urut</th>
            <th>No AKN</th>
            <th>Customer / Distributor</th>
            <th>Instansi</th>
            <th>Alamat Instansi</th>
            <th>Satuan</th>
            <th>Tanggal Pesan</th>
            <th>Batas Kontrak</th>
            <th>Produk</th>
            <th>Produk (E-purchasing)</th>
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
        @php
            $no = 1;
        @endphp
        @foreach ($data as $d)
            @foreach ($d->DetailPesananUniqueDsb() as $e)
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ $e->Pesanan->so }}</td>
                    <td>{{ $e->Pesanan->no_po }} (Stok Distributor)</td>
                    <td>{{ date('d-m-Y', strtotime($e->Pesanan->tgl_po)) }}</td>
                    <td>-</td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->no_urut }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->no_paket }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->Customer->nama }}
                        @elseif($e->Pesanan->Spa)
                            {{ $e->Pesanan->Spa->Customer->nama }}
                        @elseif($e->Pesanan->Spb)
                            {{ $e->Pesanan->Spb->Customer->nama }}
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->instansi }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->alamat }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->satuan }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ date('d-m-Y', strtotime($e->Pesanan->Ekatalog->tgl_buat)) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ date('d-m-Y', strtotime($e->Pesanan->Ekatalog->tgl_kontrak)) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Sparepart)
                            {{ $e->Sparepart->nama }}
                        @else
                            {{ $e->PenjualanProduk->nama }}
                            {{$d->GetProduk($e->penjualan_produk_id)}}
                        @endif

                    </td>
                    <td>
                        @if ($e->Sparepart)
                            {{ $e->Sparepart->nama }}
                        @else
                            @if ($e->PenjualanProduk->nama_alias != '')
                                {{ $e->PenjualanProduk->nama_alias }}
                            @else
                                {{ $e->PenjualanProduk->nama }}
                            @endif
                            {{$d->GetProduk($e->penjualan_produk_id)}}
                        @endif

                    </td>


                    <td>-
                    </td>

                    <td>

                        {{ $d->getJumlahPesananUniqueDsb($e->penjualan_produk_id) }}

                    </td>
                    <td>
                        {{ $e->harga }}
                    </td>
                    <td>
                        @if ($e->Sparepart)
                            0
                        @else
                            {{ $d->getOngkirPesananUniqueDsb($e->penjualan_produk_id) }}
                        @endif
                    </td>
                    <td>

                        {{ $d->getTotalPesananUniqueDsb($e->penjualan_produk_id) + $d->getOngkirPesananUniqueDsb($e->penjualan_produk_id) }}

                    </td>
                    <td>
                        {{ $e->Pesanan->state->nama }}
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->status }}
                        @else
                            -
                        @endif

                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            @if ($e->Pesanan->Ekatalog->ket != '')
                                {{ $e->Pesanan->Ekatalog->ket }}
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif


                    </td>
                </tr>
            @endforeach
            @foreach ($d->DetailPesananUnique() as $e)
                <tr>
                    <td>
                        @php
                            if ($d->DetailPesananDsb) {
                                echo $no++ + count($d->detailpesanandsb);
                            } else {
                                echo $no++;
                            }
                        @endphp
                    </td>
                    <td>{{ $e->Pesanan->so }}</td>
                    <td>{{ $e->Pesanan->no_po }}</td>
                    <td>{{ date('d-m-Y', strtotime($e->Pesanan->tgl_po)) }}</td>
                    <td>
                        @if ($e->Sparepart)
                            @if ($d->getSuratJalanPart($d->DetailPesananUnique()[0]->m_sparepart_id)->count() > 0)
                                @foreach ($d->getSuratJalanPart($d->DetailPesananUnique()[0]->m_sparepart_id) as $f)
                                    {{ $f->Logistik->nosurat }}({{ date('d-m-Y', strtotime($f->Logistik->tgl_kirim)) }})
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            @else
                                -
                            @endif
                        @endif
                        @if ($e->PenjualanProduk)
                            @if ($d->getSuratJalanProduk($d->DetailPesananUnique()[0]->penjualan_produk_id)->count() > 0)
                                @foreach ($d->getSuratJalanProduk($d->DetailPesananUnique()[0]->penjualan_produk_id) as $f)
                                    {{ $f->Logistik->nosurat }}({{ date('d-m-Y', strtotime($f->Logistik->tgl_kirim)) }})
                                    @if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            @else
                                -
                            @endif
                        @endif

                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->no_urut }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->no_paket }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->Customer->nama }}
                        @elseif($e->Pesanan->Spa)
                            {{ $e->Pesanan->Spa->Customer->nama }}
                        @elseif($e->Pesanan->Spb)
                            {{ $e->Pesanan->Spb->Customer->nama }}
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->instansi }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->alamat }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->satuan }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ date('d-m-Y', strtotime($e->Pesanan->Ekatalog->tgl_buat)) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ date('d-m-Y', strtotime($e->Pesanan->Ekatalog->tgl_kontrak)) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($e->Sparepart)
                            {{ $e->Sparepart->nama }}
                        @else

                            {{ $e->PenjualanProduk->nama }}
                            {{$d->GetProduk($e->penjualan_produk_id)}}



                        @endif

                    </td>
                    <td>
                        @if ($e->Sparepart)
                            {{ $e->Sparepart->nama }}
                        @else
                            @if ($e->PenjualanProduk->nama_alias != '')
                                {{ $e->PenjualanProduk->nama_alias }}
                            @else
                                {{ $e->PenjualanProduk->nama }}
                            @endif
                            {{$d->GetProduk($e->penjualan_produk_id)}}
                        @endif

                    </td>


                    <td>

                        @if ($e->Sparepart)
                            -
                        @else
                            @if ($seri == 'seri')
                                @if ($d->getNoseri($e->penjualan_produk_id)->count() > 0)
                                    @foreach ($d->getNoseri($e->penjualan_produk_id) as $g)
                                        {{ $g->NoseriTGbj->seri->noseri }}
                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        @endif

                    </td>

                    <td>
                        @if ($e->Sparepart)
                            {{ $d->getJumlahPartUnique($e->m_sparepart_id) }}
                        @else
                            {{ $d->getJumlahPesananUnique($e->penjualan_produk_id) }}
                        @endif
                    </td>
                    <td>
                        {{ $e->harga }}
                    </td>
                    <td>
                        @if ($e->Sparepart)
                            0
                        @else
                            {{ $d->getOngkirPesananUnique($e->penjualan_produk_id) }}
                        @endif
                    </td>
                    <td>
                        @if ($e->Sparepart)
                            {{ $d->getTotalPartUnique($e->m_sparepart_id) }}
                        @else
                            {{ $d->getTotalPesananUnique($e->penjualan_produk_id) + $d->getOngkirPesananUnique($e->penjualan_produk_id) }}
                        @endif
                    </td>
                    <td>
                        {{ $e->Pesanan->state->nama }}
                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            {{ $e->Pesanan->Ekatalog->status }}
                        @else
                            -
                        @endif

                    </td>
                    <td>
                        @if ($e->Pesanan->Ekatalog)
                            @if ($e->Pesanan->Ekatalog->ket != '')
                                {{ $e->Pesanan->Ekatalog->ket }}
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif


                    </td>
                </tr>
            @endforeach
        @endforeach
        {{-- @php
                $no = 1;
            @endphp
            @foreach ($data as $d)
            @foreach ($d->DetailPesananUnique() as $e)
            <tr>
                <td>{{$no++}}</td>
               <td>{{$e->Pesanan->so}}</td>
                <td >{{$e->Pesanan->no_po}}</td>
                <td >{{date('d-m-Y', strtotime($e->Pesanan->tgl_po))}}</td>
                <td >
                    @if ($d->getSuratJalanProduk($e->penjualan_produk_id)->count() > 0)
                            @foreach ($d->getSuratJalanProduk($e->penjualan_produk_id) as $f)
                            {{ $f->Logistik->nosurat }}({{ date('d-m-Y', strtotime($f->Logistik->tgl_kirim)) }})
                            @if (!$loop->last)
                            ,
                            @endif
                            @endforeach
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if ($e->Pesanan->Ekatalog)
                    {{$e->Pesanan->Ekatalog->no_urut}}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if ($e->Pesanan->Ekatalog)
                    {{$e->Pesanan->Ekatalog->no_paket}}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if ($e->Pesanan->Ekatalog)
                    {{$e->Pesanan->Ekatalog->Customer->nama}}
                    @elseif($e->Pesanan->Spa)
                    {{$e->Pesanan->Spa->Customer->nama}}
                    @elseif($e->Pesanan->Spb)
                    {{$e->Pesanan->Spb->Customer->nama}}
                    @endif
                </td>
                <td>
                    @if ($e->Pesanan->Ekatalog)
                    {{$e->Pesanan->Ekatalog->instansi}}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if ($e->Pesanan->Ekatalog)
                    {{$e->Pesanan->Ekatalog->satuan}}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if ($e->Pesanan->Ekatalog)
                    {{date('d-m-Y', strtotime($e->Pesanan->Ekatalog->tgl_buat))}}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if ($e->Pesanan->Ekatalog)
                    {{date('d-m-Y', strtotime($e->Pesanan->Ekatalog->tgl_kontrak))}}
                    @else
                    -
                    @endif
                </td>
                <td>
                    @if ($e->PenjualanProduk->nama_alias != '')
                    {{$e->PenjualanProduk->nama_alias}}
                @else
                    {{$e->PenjualanProduk->nama}}
                @endif
                </td>
                <td >
                    @if ($seri == 'seri')
                    @if ($d->getNoseri($e->penjualan_produk_id)->count() > 0)
                    @foreach ($d->getNoseri($e->penjualan_produk_id) as $g)
                    {{ $g->NoseriTGbj->seri->noseri}}
                    @if (!$loop->last)
                    ,
                    @endif
                    @endforeach
                    @else
                    -
                    @endif
                    @else
                    -
                    @endif
                </td>
                <td>
                    {{$d->getJumlahPesananUnique($e->penjualan_produk_id)}}
                  </td>
                  <td>
                    {{$d->getOngkirPesananUnique($e->penjualan_produk_id)}}
                </td>
                <td>
                    {{  $d->getTotalPesananUnique($e->penjualan_produk_id) +   $d->getOngkirPesananUnique($e->penjualan_produk_id) }}
                </td>
                <td >
                    {{$e->Pesanan->state->nama}}
                   </td>
                   <td >
                    @if ($e->Pesanan->Ekatalog)
                    {{$e->Pesanan->Ekatalog->status}}
                    @else
                    -
                    @endif

                </td>
                <td >
                    @if ($e->Pesanan->Ekatalog)
                        @if ($e->Pesanan->Ekatalog->ket != '')
                        {{$e->Pesanan->Ekatalog->ket}}
                        @else
                        -
                        @endif
                    @else
                    -
                    @endif


                </td>
            </tr>
            @endforeach
    @endforeach --}}
    </tbody>
</table>
