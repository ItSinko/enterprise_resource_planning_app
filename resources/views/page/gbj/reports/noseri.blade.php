<table>
    <tr>
        <th> Noseri Gudang Barang Jadi </th>
    </tr>

    @foreach ($data as $d)
    @php
        $no = 0;
    @endphp
    <tr></tr>
    <tr align="center">
        <th colspan="2">Produk</th>
        <td colspan="3">{{ $d->produk->nama }} {{ $d->nama }}</td>
    </tr>

    <tr>
        <th colspan="2">Jumlah</th>
        <td colspan="2">{{ count($d->noseri) }}</td>
    </tr>
    <tr></tr>

    <tr>
    @foreach ($d->noseri as $dd)
    @php
        $no++;
    @endphp
        <td style="width: 5ch">{{ $no }}</td>
    @if (isset($dd->noseri))
        <td style="width: 16ch">{{ $dd->noseri }}</td>
    @else
        <td>-</td>
    @endif

    {{-- @if (isset($dd->used_by))
        <td style="width: 22ch">{{ $dd->pesanan->so }}</td>
    @else
        <td>-</td>
    @endif --}}

    @if ($no % 5 == 0)
        </tr><tr>
    @endif

    @endforeach
    </tr>
    <tr></tr>
    @endforeach
</table>
