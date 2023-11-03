<div class="row filter">
    <div class="col-12">
        <div class="card card-detail removeshadow">
            {{-- <img src="https://picsum.photos/200/200" class="card-img-top" alt="..."> --}}
            {{-- <div id="profileImage" class="center card-img-top"></div> --}}
            <div class="card-body border-0">
                <h5 class="card-title pl-2 py-2"><b>{{ $data->Customer->nama }}</b></h5>
                <ul class="fa-ul card-text">
                    <li class="py-2"><span class="fa-li"><i class="fas fa-address-card fa-fw"></i></span>
                        @if (!empty($data->Customer->alamat))
                            {{ $data->Customer->alamat }}
                        @else
                            <em class="text-muted">Belum Tersedia</em>
                        @endif
                    </li>
                    <li class="py-2"><span class="fa-li"><i class="fas fa-map-marker-alt fa-fw"></i></span>
                        @if (!empty($data->Customer->provinsi))
                            {{ $data->Customer->Provinsi->nama }}
                        @else
                            <em class="text-muted">Belum Tersedia</em>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-12">
        @if ($data->ket != '')
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <strong>Catatan: </strong>{{ $data->ket }}
            </div>
        @endif
        <div class="card card-orange card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tabs-detail-tab" data-toggle="pill" href="#tabs-detail"
                            role="tab" aria-controls="tabs-detail" aria-selected="true">Informasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tabs-produk-tab" data-toggle="pill" href="#tabs-produk" role="tab"
                            aria-controls="tabs-produk" aria-selected="false">Produk</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                    <div class="tab-pane fade active show" id="tabs-detail" role="tabpanel"
                        aria-labelledby="tabs-detail-tab">

                        <div class="row d-flex justify-content-between">

                            <div class="p-2">
                                <div class="margin">
                                    <div><small class="text-muted">No SO</small></div>
                                    <div><b>
                                            @if ($data->Pesanan->so)
                                                {{ $data->pesanan->so }}
                                            @else
                                                <em class="text-muted">Belum Tersedia</em>
                                            @endif
                                        </b>
                                    </div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Status</small></div>
                                    <div>{!! $status !!}</div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="margin">
                                    <div><small class="text-muted">No PO</small></div>
                                    <div><b>
                                            @if ($data->Pesanan->no_po)
                                                {{ $data->Pesanan->no_po }}
                                            @else
                                                <em class="text-muted">Belum Tersedia</em>
                                            @endif
                                        </b>
                                    </div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Tanggal PO</small></div>
                                    <div><b>
                                            @if ($data->Pesanan->tgl_po)
                                                {{ date('d-m-Y', strtotime($data->Pesanan->tgl_po)) }}
                                            @else
                                                <em class="text-muted">Belum Tersedia</em>
                                            @endif
                                        </b>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="margin">
                                    <div><small class="text-muted">No DO</small></div>
                                    <div><b>
                                            @if ($data->Pesanan->no_do)
                                                {{ $data->Pesanan->no_do }}
                                            @else
                                                <em class="text-muted">Belum Tersedia</em>
                                            @endif
                                        </b>
                                    </div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Tanggal DO</small></div>
                                    <div><b>
                                            @if ($data->Pesanan->tgl_do)
                                                {{ date('d-m-Y', strtotime($data->Pesanan->tgl_do)) }}
                                            @else
                                                <em class="text-muted">Belum Tersedia</em>
                                            @endif
                                        </b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs-produk" role="tabpanel" aria-labelledby="tabs-produk-tab">
                        {{-- <div class="card removeshadow"> --}}
                        {{-- <div class="card-body"> --}}
                        <div class="row">
                            {{-- <div class="card"> --}}
                            <div class="card col-lg-4 col-md-12 removeshadow">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-4">
                                            <canvas id="myChart" width="400" height="400"
                                                class="mb-5"></canvas>
                                            <div class="card card-secondary card-outline mt-3">
                                                <div class="card-body">
                                                    <h3 class="profile-username text-center"><span
                                                            id="nama_prd">-</span></h3>
                                                    <ul class="list-group list-group-unbordered mb-3">
                                                        <li class="list-group-item">
                                                            <span class="align-self-center"><span
                                                                    class="foo bg-chart-orange mr-2"></span><span>Gudang</span></span>
                                                            <a class="float-right mr-2"><b><span id="c_gudang"
                                                                        class="text-danger">0</span></b><sub
                                                                    id="tot_gudang"> dari 0</sub></a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <span class="align-self-center"><span
                                                                    class="foo bg-chart-yellow mr-2"></span><span>QC</span></span>
                                                            <a class="float-right mr-2"><b><span id="c_qc"
                                                                        class="text-danger">0</span></b><sub
                                                                    id="tot_qc"> dari 0</sub></a>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <span class="align-self-center"><span
                                                                    class="foo bg-chart-green mr-2"></span><span>Logistik</span></span>
                                                            <a class="float-right mr-2"><b><span id="c_log"
                                                                        class="text-danger">0</span></b><sub
                                                                    id="tot_log"> dari 0</sub></a>
                                                        </li>
                                                        <li class="list-group-item bg-chart-blue text-white">
                                                            <span class="align-self-center"><span
                                                                    class="foo mr-2"></span><b>Kirim</b></span> <b
                                                                class="float-right mr-2"><span id="c_kirim">0</span>
                                                                <sub>unit</sub> {{-- <sub  id="tot_kirim"> dari 0</sub> --}}</b>
                                                        </li>
                                                    </ul>
                                                    <div class="alert alert-info show" role="alert">
                                                        <small>
                                                            <i class="fas fa-info-circle"></i> <strong>Catatan:
                                                            </strong>
                                                            <ol
                                                                style="list-item-style:none; margin-left:0px;padding-left:15px;">
                                                                <li>Angka warna <b class="text-danger">merah</b>
                                                                    menunjukkan jumlah unit yang <i>belum diproses</i>
                                                                    oleh divisi tersebut</li>
                                                                <li>Angka warna <b class="text-dark">hitam</b>
                                                                    menunjukkan total yang <i>telah diberikan dan harus
                                                                        diproses</i> oleh divisi tersebut</li>
                                                                <li>Angka pada Kirim merupakan total unit yang <i>telah
                                                                        terkirim</i></li>
                                                            </ol>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <h6><b>Status Barang</b></h6>
                                                    <div id="chartproduk"></div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="info-box bg-light removeshadow" style="background-color:#5F7A90; color:white;">
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text">Produk</span>
                                                                    <span class="info-box-number" id="nama_produk">-</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="info-box removeshadow" style="background-color:#EA8B1B; color:white;">
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text">Gudang</span>
                                                                    <span class="info-box-number" id="count_gudang">0</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="info-box removeshadow" style="background-color:#FFC700;">
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text">QC</span>
                                                                    <span class="info-box-number" id="count_qc">0</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4">
                                                            <div class="info-box removeshadow" style="background-color:#456600; color:white;">
                                                                <div class="info-box-content">
                                                                    <span class="info-box-text">Logistik</span>
                                                                    <span class="info-box-number" id="count_log">0</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                </div>
                            </div>
                            <div class="card col-lg-8 col-md-12">
                                <div class="card-body">
                                    <h6><b>Detail Produk</b></h6>
                                    <div class="table-responsive overflowcard">
                                        <?php $totalharga = 0; ?>
                                        <?php

                                        $no_dsb = 0;

                                        if (isset($data->Pesanan->detailpesanandsb)) {
                                            $no = count($data->Pesanan->detailpesanandsb);
                                        } else {
                                            $no = 0;
                                        }

                                        ?>
                                        @if (isset($data->Pesanan))
                                            <table class="table"
                                                style="max-width:100%; overflow-x: hidden; background-color:white;"
                                                id="tabledetailpesan">
                                                <thead class="bg-chart-light">
                                                    <tr>
                                                        <th rowspan="2">No</th>
                                                        <th rowspan="2">Produk</th>
                                                        <th rowspan="2"></th>
                                                        <th rowspan="2">Qty</th>
                                                        <th rowspan="2">Harga</th>
                                                        <th rowspan="2">Subtotal</th>
                                                        @if (Auth::user()->divisi_id == '8')
                                                            <th rowspan="2">Aksi</th>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (isset($data->Pesanan->detailpesanandsb))
                                                        @foreach ($data->pesanan->detailpesanandsb as $e)
                                                            <?php $no_dsb = $no_dsb + 1; ?>
                                                            <tr>
                                                                <td rowspan="{{ count($e->DetailPesananProdukDsb) + 1 }}"
                                                                    class="nowraptxt">{{ $no_dsb }}
                                                                </td>
                                                                <td><b
                                                                        class="wb">{{ $e->PenjualanProduk->nama }}</b>
                                                                </td>
                                                                <td class="nowraptxt"
                                                                    style="vertical-align : middle;text-align:center;">
                                                                    <span class="badge info-text">Stok
                                                                        distributor</span>
                                                                </td>
                                                                <td class="nowraptxt">{{ $e->jumlah }}
                                                                </td>
                                                                <td rowspan="{{ count($e->DetailPesananProdukDsb) + 1 }}"
                                                                    class="nowraptxt tabnum">@currency($e->harga)</td>

                                                                <td rowspan="{{ count($e->DetailPesananProdukDsb) + 1 }}"
                                                                    class="nowraptxt tabnum">@currency($e->harga * $e->jumlah + $e->ongkir)</td>
                                                                <?php $totalharga = $totalharga + ($e->harga * $e->jumlah + $e->ongkir); ?>
                                                            </tr>
                                                            @if (isset($e->DetailPesananProdukDsb))
                                                                @foreach ($e->DetailPesananProdukDsb as $l)
                                                                    <tr>
                                                                        <td><span class="text-muted">
                                                                                @if (!empty($l->GudangBarangJadi->nama))
                                                                                    {{ $l->GudangBarangJadi->Produk->nama }}
                                                                                    -
                                                                                    <b>{{ $l->GudangBarangJadi->nama }}</b>
                                                                                @else
                                                                                    {{ $l->GudangBarangJadi->Produk->nama }}
                                                                                @endif
                                                                            </span>
                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                        <td>
                                                                            {{ $l->getJumlahPesanan() }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    @if (isset($data->Pesanan->detailpesanan))
                                                        @foreach ($data->pesanan->detailpesanan as $e)
                                                            <?php $no = $no + 1; ?>
                                                            <tr>
                                                                <td rowspan="{{ count($e->DetailPesananProduk) + 1 }}"
                                                                    class="nowraptxt">{{ $no }}</td>
                                                                <td><b
                                                                        class="wb">{{ $e->PenjualanProduk->nama }}</b>
                                                                </td>
                                                                <td class="nowraptxt">

                                                                    <button class="btn btn-sm btn-outline-primary"
                                                                        id="lihatstok" data-id="{{ $e->id }}"
                                                                        data-produk="paket"><i
                                                                            class="fas fa-eye"></i></button>
                                                                </td>
                                                                <td class="nowraptxt tabnum">
                                                                    {{ $e->jumlah }}</td>
                                                                <td rowspan="{{ count($e->DetailPesananProduk) + 1 }}"
                                                                    class="nowraptxt tabnum">@currency($e->harga)</td>
                                                                <td rowspan="{{ count($e->DetailPesananProduk) + 1 }}"
                                                                    class="nowraptxt tabnum">@currency($e->harga * $e->jumlah)</td>
                                                                @if (Auth::user()->divisi_id == '8')
                                                                    <td
                                                                        rowspan="{{ count($e->DetailPesananProduk) + 1 }}">
                                                                        -
                                                                    </td>
                                                                @endif
                                                                <?php $totalharga = $totalharga + ($e->harga * $e->jumlah + $e->ongkir); ?>
                                                            </tr>
                                                            @if (isset($e->DetailPesananProduk))
                                                                @foreach ($e->DetailPesananProduk as $l)
                                                                    <tr>
                                                                        <td><span class="text-muted">
                                                                                @if (!empty($l->GudangBarangJadi->nama))
                                                                                    {{ $l->GudangBarangJadi->Produk->nama }}
                                                                                    -
                                                                                    <b>{{ $l->GudangBarangJadi->nama }}</b>
                                                                                @else
                                                                                    {{ $l->GudangBarangJadi->Produk->nama }}
                                                                                @endif
                                                                            </span>
                                                                        </td>
                                                                        <td class="nowraptxt">
                                                                            <button
                                                                                class="btn btn-sm btn-outline-primary"
                                                                                id="lihatstok"
                                                                                data-id="{{ $l->id }}"
                                                                                data-produk="variasi"><i
                                                                                    class="fas fa-eye"></i></button>
                                                                        </td>
                                                                        <td>
                                                                            {{ $l->getJumlahPesanan() }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                    @if (isset($data->Pesanan->detailpesananpart))
                                                        @foreach ($data->pesanan->detailpesananpart as $e)
                                                            <?php $no = $no + 1; ?>
                                                            <tr>
                                                                <td class="nowraptxt">{{ $no }}</td>
                                                                <td class="wb">
                                                                    <b>{{ $e->Sparepart->nama }}</b>
                                                                    {{-- <i class="fas fa-info-circle text-danger" data-toggle="tooltip" data-html="true" data-placement="bottom" role="button" title='Ini Adalah Tooltipnya' ></i> --}}
                                                                </td>
                                                                <td class="nowraptxt">
                                                                    @if (strpos($e->Sparepart->kode, 'JASA') === false)
                                                                        <button class="btn btn-sm btn-outline-primary"
                                                                            id="lihatstok"
                                                                            data-id="{{ $e->id }}"
                                                                            data-produk="part"><i
                                                                                class="fas fa-eye"></i></button>
                                                                    @endif
                                                                </td>
                                                                <td class="nowraptxt tabnum"><span
                                                                        class="text-muted">{{ $e->jumlah }}</span>
                                                                </td>
                                                                <td class="nowraptxt tabnum">@currency($e->harga)</td>
                                                                <td class="nowraptxt tabnum">@currency($e->harga * $e->jumlah)</td>
                                                                @if (Auth::user()->divisi_id == '8')
                                                                    @if ($data->Pesanan->log_id == '8' || $data->Pesanan->log_id == '9')
                                                                        <td><a data-toggle="komentar"
                                                                                class="komentarmodal"
                                                                                data-id="{{ $e->id }}">
                                                                                <button type="button"
                                                                                    class="btn btn-outline-warning btn-sm">
                                                                                    <i class="fas fa-pencil-alt"></i>
                                                                                    Komentar
                                                                                </button></a>
                                                                        </td>
                                                                    @else
                                                                        <td>-</td>
                                                                    @endif
                                                                @endif
                                                                <?php $totalharga = $totalharga + $e->harga * $e->jumlah; ?>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                                <tfoot class="bg-chart-light">
                                                    <tr>
                                                        <th colspan="5" class="align-center">Total Harga</th>
                                                        <th class="tabnum nowraptxt">@currency($totalharga)</th>
                                                        @if (Auth::user()->divisi_id == '8')
                                                            <th></th>
                                                        @endif
                                                    </tr>
                                                </tfoot>

                                            </table>
                                        @else
                                            <div class="align-center"><i>Detail Pesanan Belum Tersedia</i></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- </div> --}}
                        </div>
                        {{-- </div> --}}
                        {{-- </div> --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- <div class="col-12"> --}}
    {{-- <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Info Customer</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 align-center">
                                <!-- <div id="profileImage" class="center margin-all"></div> -->
                                <div>
                                    <h6><b>{{$data->Customer->nama}}</b></h6>
                                </div>
                                <div>{{$data->Customer->alamat}}</div>
                                <div>{{$data->Customer->Provinsi->nama}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Info Penjualan</h5>
                    </div>
                    <div class="card-body">
                        <div class="margin">
                            <a class="text-muted">No SO</a>
                            <b class="float-right">@if ($data->Pesanan->so)
                                {{ $data->Pesanan->so}}
                                @else
                                -
                                @endif</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">No PO</a>
                            <b class="float-right">
                                @if ($data->Pesanan->no_po)
                                {{ $data->Pesanan->no_po}}
                                @else
                                -
                                @endif</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">Tanggal PO</a>
                            <b class="float-right">@if ($data->Pesanan->tgl_po != '' && $data->Pesanan->tgl_po != '0000-00-00')
                                {{ date('d-m-Y', strtotime($data->Pesanan->tgl_po)) }}
                                @else
                                -
                                @endif</b>
                        </div>
                        <div class="margin">
                            <a class="text-muted">Status</a>
                            <b class="float-right" id="status">
                                @if (!empty($data->Pesanan->log_id))
                                @if ($data->Pesanan->State->nama == 'Penjualan')
                                <span class="red-text badge">
                                    @elseif ($data->Pesanan->State->nama == "PO")
                                    <span class="purple-text badge">
                                        @elseif ($data->Pesanan->State->nama == "Gudang")
                                        <span class="orange-text badge">
                                            @elseif ($data->Pesanan->State->nama == "QC")
                                            <span class="yellow-text badge">
                                                @elseif ($data->Pesanan->State->nama == "Belum Terkirim")
                                                <span class="red-text badge">
                                                    @elseif ($data->Pesanan->State->nama == "Terkirim Sebagian")
                                                    <span class="blue-text badge">
                                                        @elseif ($data->Pesanan->State->nama == "Kirim")
                                                        <span class="green-text badge">
                                                            @endif
                                                            {{ucfirst($data->Pesanan->State->nama)}}</span>
                                                        @else
                                                        -
                                                        @endif
                            </b>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                @if ($data->ket != '')
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Catatan: </strong>{{$data->ket}}
                </div>
                @endif
                <h5>Detail Pemesanan</h5>
                <div class="card overflowy" id="detailspa">
                    <div class="card-body">
                        <div class="table-responsive">
                            <!-- <div class="form-horizontal"> -->
                            <?php $totalharga = 0; ?>
                            <?php $no = 0; ?>
                            @if (isset($data->Pesanan))
                            <div class="card removeshadow" id="detailspa">
                                <div class="card-body">
                                    <table class="table" style="max-width:100%; overflow-x: hidden; background-color:white;" id="tabledetailpesan">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">No</th>
                                                <th rowspan="2">Produk</th>
                                                <th colspan="2">Qty</th>
                                                <th rowspan="2">Harga</th>
                                                <th rowspan="2">Subtotal</th>
                                            </tr>
                                            <tr>
                                                <th><i class="fas fa-shopping-cart"></i></th>
                                                <th><i class="fas fa-truck"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (isset($data->Pesanan->detailpesanan))
                                            @foreach ($data->pesanan->detailpesanan as $e)
                                            <?php $no = $no + 1; ?>
                                            <tr>
                                                <td rowspan="{{count($e->DetailPesananProduk) + 1}}" class="nowraptxt">{{$no}}</td>
                                                <td><b class="wb">{{$e->PenjualanProduk->nama}}</b></td>
                                                <td colspan="2" class="nowraptxt tabnum">{{$e->jumlah}}</td>
                                                <td rowspan="{{count($e->DetailPesananProduk) + 1}}" class="nowraptxt tabnum">@currency($e->harga)</td>
                                                <td rowspan="{{count($e->DetailPesananProduk) + 1}}" class="nowraptxt tabnum">@currency($e->harga * $e->jumlah)</td>
                                                <?php $totalharga = $totalharga + $e->harga * $e->jumlah; ?>
                                            </tr>
                                            @if (isset($e->DetailPesananProduk))
                                            @foreach ($e->DetailPesananProduk as $l)
                                            <tr>
                                                <td><span class="text-muted">@if (!empty($l->GudangBarangJadi->nama))
                                                        {{$l->GudangBarangJadi->Produk->nama}} - <b>{{$l->GudangBarangJadi->nama}}</b>
                                                        @else
                                                        {{$l->GudangBarangJadi->Produk->nama}}
                                                        @endif</span>
                                                </td>
                                                <td>
                                                    {{$l->getJumlahPesanan()}}
                                                </td>
                                                <td>{{$l->getJumlahKirim()}}</td>
                                            </tr>
                                            @endforeach
                                            @endif
                                            @endforeach
                                            @endif

                                            @if (isset($data->Pesanan->detailpesananpart))
                                            @foreach ($data->pesanan->detailpesananpart as $e)
                                            <?php $no = $no + 1; ?>
                                            <tr>
                                                <td class="nowraptxt">{{$no}}</td>
                                                <td class="wb"><b>{{$e->Sparepart->nama}}</b></td>
                                                <td class="nowraptxt tabnum"><span class="text-muted">{{$e->jumlah}}</span></td>
                                                <td class="nowraptxt tabnum">@if (isset($e->detaillogistikpart)) {{$e->jumlah}} @else 0 @endif</td>
                                                <td class="nowraptxt tabnum">@currency($e->harga)</td>
                                                <td class="nowraptxt tabnum">@currency($e->harga * $e->jumlah)</td>
                                                <?php $totalharga = $totalharga + $e->harga * $e->jumlah; ?>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">Total Harga</td>
                                                <td class="tabnum nowraptxt">@currency($totalharga)</td>
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>
                            </div>
                            @else
                            <div class="align-center"><i>Detail Pesanan Belum Tersedia</i></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    {{-- </div> --}}
</div>
