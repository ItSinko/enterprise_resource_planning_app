<table style="border:1px solid #000">
    <thead>
        <tr>
            <th colspan="14" style="text-align:center">
                {{$header}}
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>No Retur</th>
            <th>PIC</th>
            <th>Customer</th>
            <th>Tgl Retur</th>
            <th>Jenis</th>
            <th>Keterangan</th>
            <th>Progres Perbaikan</th>
            <th>Progres Keseluruhan</th>
            <th>Produk</th>
            <th>No Seri</th>
            <th>No Perbaikan</th>
            <th>Tgl Perbaikan</th>
            <th>Keterangan</th>
            <th>Tindak Lanjut</th>
            <th>No SJ</th>
            <th>Tgl Kirim</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i)
        <tr>
            <td rowspan="{{$i['row']}}">{{$loop->iteration}}</td>
            <td rowspan="{{$i['row']}}">{{$i['no_retur']}}</td>
            <td rowspan="{{$i['row']}}">{{$i['pic']}} {{$i['telp_pic']}}</td>
            <td rowspan="{{$i['row']}}">{{$i['customer']}}</td>
            <td rowspan="{{$i['row']}}">{{$i['tgl_retur']}}</td>
            <td rowspan="{{$i['row']}}">{{$i['jenis']}}</td>
            <td rowspan="{{$i['row']}}">{{$i['ket']}}</td>
            <td rowspan="{{$i['row']}}">{{$i['prog_perbaikan']}}%</td>
            <td rowspan="{{$i['row']}}">{{$i['prog_pengiriman']}}%</td>
            <?php $count=0; ?>
            @foreach ($i['produk'] as $j)
            @if($count > 0) <tr> @endif
            <td rowspan="{{$j['jumlah_unit']}}">{{$j['produk']}}</td>
                <?php $countrow=0; ?>
                @foreach ($j['noseri'] as $k)
                    @if($countrow > 0) <tr> @endif
                    <td>{{$k['noseri']}}</td>
                    <td>{{$k['no_perbaikan']}}</td>
                    <td>{{$k['tgl_perbaikan']}}</td>
                    <td>{{$k['keterangan']}}</td>
                    <td>{{$k['tindak_lanjut'] != NULL ? $k['tindak_lanjut'] : ''}} {{$k['noseri_pengganti'] != NULL ? '(diganti oleh '.$k['noseri_pengganti'].')' : ''}}</td>
                    <td>{{$k['no_sj']}}</td>
                    <td>{{$k['tgl_kirim']}}</td>
                    </tr>
                    <?php $countrow = $countrow + 1; ?>
                @endforeach
                <?php $count = $count + 1; ?>
            @endforeach
        @endforeach
    </tbody>
</table>
