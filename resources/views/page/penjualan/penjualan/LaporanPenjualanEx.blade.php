<table border="1">
    <thead>
        <tr>
            <th colspan="17" style="text-align:center">
                {{$header}}
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No SO</th>
            <th>No PO</th>
            <th>Tanggal PO</th>
            <th>Surat Jalan</th>
            <th>Tgl Surat Jalan</th>
            <th>No AKN</th>
            <th>Customer / Distributor</th>
            <th>Instansi</th>
            <th>Satuan</th>
            <th>Tanggal Pesan</th>
            <th>Batas Kontrak</th>
            <th>Produk</th>
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
        @foreach ($data as $d)
        <tr>
            <td style="text-align:left">{{ $loop->iteration }}</td>
            <td style="text-align:left">{{$d->Pesanan->so}}</td>
            <td style="text-align:left">{{$d->Pesanan->no_po}}</td>
            <td style="text-align:left">
                {{$d->Pesanan->tgl_po}}
            </td>
            <td style="text-align:left">
                @if(isset($d->PenjualanProduk))

                @if($d->getJumlahProduk() > 1)

                @foreach($d->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                @foreach( $p->DetailLogistik as $q)
                {{$q->Logistik->nosurat}}
                @endforeach
                @endforeach

                @else
                @foreach($d->DetailPesananProduk as $p)
                @foreach( $p->DetailLogistik as $q)
                {{$q->Logistik->nosurat}}
                @endforeach
                @endforeach
                @endif

                @else

                @if(isset($d->DetailLogistikPart->Logistik))
                {{$d->DetailLogistikPart->Logistik->nosurat}}
                @endif

                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->PenjualanProduk))

                @if($d->getJumlahProduk() > 1)

                @foreach($d->DetailPesananProduk->unique('detail_pesanan_id') as $p)
                @foreach( $p->DetailLogistik as $q)
                {{$q->Logistik->tgl_kirim}}
                @endforeach
                @endforeach

                @else
                @foreach($d->DetailPesananProduk as $p)
                @foreach( $p->DetailLogistik as $q)
                {{$q->Logistik->tgl_kirim}}
                @endforeach
                @endforeach
                @endif

                @else

                @if(isset($d->DetailLogistikPart->Logistik))
                {{$d->DetailLogistikPart->Logistik->tgl_kirim}}
                @endif

                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->no_paket}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->Customer->nama}}
                @elseif(isset($d->Pesanan->Spa))
                {{$d->Pesanan->Spa->Customer->nama}}
                @else
                {{$d->Pesanan->Spb->Customer->nama}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->instansi}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->satuan}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->tgl_buat}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->tgl_kontrak}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->PenjualanProduk))

                @if($d->PenjualanProduk->nama_alias != '')
                {{$d->PenjualanProduk->nama_alias}}
                @else
                {{$d->PenjualanProduk->nama}}
                @endif

                @else
                {{$d->Sparepart->nama}}
                @endif
            </td>
            <td style="text-align:left">
                @if(isset($d->PenjualanProduk))

                @foreach($d->DetailPesananProduk as $p)
                {{ $p->gudangbarangjadi->produk->nama}}

                @if ($p->gudangbarangjadi->nama != '')
                {{ $p->gudangbarangjadi->nama}}
                @endif

                @if( !$loop->last)
                ,
                @endif

                @endforeach

                @else
                {{$d->Sparepart->nama}}
                @endif
            </td>
            <td style="text-align:left">
                {{$d->jumlah}}
            </td>
            <td style="text-align:left">{{$d->harga}}</td>
            <td style="text-align:left">{{$d->ongkir}}</td>
            <td style="text-align:left">{{($d->jumlah * $d->harga) + $d->total}}</td>
            <td style="text-align:left">{{$d->pesanan->state->nama}}</td>
            <td style="text-align:left">
                @if(isset($d->Pesanan->Ekatalog))
                {{$d->Pesanan->Ekatalog->status}}
                @endif
            </td>
            <td style="text-align:left">{{$d->Pesanan->ket}}</td>
        </tr>
        @endforeach
    </tbody>
</table>