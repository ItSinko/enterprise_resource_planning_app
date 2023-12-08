<table border="1">
    <thead>
        <tr>
            <th colspan="9">
                Wilayah : {{$wilayah->prov}} - {{ $wilayah->kota }}
            </th>
        </tr>
        <tr>
            <th>No</th>
            <th>ANTROPOMETRI</th>
            <th>DIGIT PRO IDA NEW</th>
            <th>DIGIT PRO BABY</th>
            <th>MTB-2MTR</th>
            <th>MTR-BABY002</th>
            <th>PTB-2IN1</th>
            <th>Packer</th>
            <th>Tanggal Perakitan</th>
        </tr>
    </thead>
    @foreach ($data as $d)
    <tbody>
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$d['seri']}}</td>
            <td>{{ $d['item'][0]->noseri}}</td>
            <td>{{ $d['item'][1]->noseri}}</td>
            <td>{{ $d['item'][2]->noseri}}</td>
            <td>{{ $d['item'][3]->noseri}}</td>
            <td>{{ $d['item'][4]->noseri}}</td>
            <td>{{ $d['packer']}}</td>
            <td>{{ $d['tgl']}}</td>
        </tr>
    </tbody>
    @endforeach
</table>