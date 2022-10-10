<div class="row filter">
    <div class="col-12">
        <div class="card card-detail removeshadow">
            {{-- <img src="https://picsum.photos/200/200" class="card-img-top" alt="..."> --}}
            {{-- <div id="profileImage" class="center card-img-top"></div> --}}
            <div class="card-body border-0">
                <h5 class="pl-2 py-2"><b>{{ $data->Customer->nama }}</b></h5>
                <ul class="fa-ul card-text">
                    <li class="py-2"><span class="fa-li"><i class="far fa-building fa-fw"></i></span>
                        <div class="row">
                            <div class="col-lg-1 col-md-2">Instansi</div>
                            <div class="col-lg-11 col-md-10">
                                @if ($data->instansi != '')
                                    {{ $data->instansi }}
                                @else
                                    <em class="text-muted">Belum Tersedia</em>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li class="py-2"><span class="fa-li"><i class="fas fa-user-alt fa-fw"></i></span>
                        <div class="row">
                            <div class="col-lg-1 col-md-2">Satuan</div>
                            <div class="col-lg-11 col-md-10">
                                @if ($data->satuan != '')
                                    {{ $data->satuan }}
                                @else
                                    <em class="text-muted">Belum Tersedia</em>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li class="py-2"><span class="fa-li"><i class="fas fa-address-card fa-fw"></i></span>
                        <div class="row">
                            <div class="col-lg-1 col-md-2">Alamat</div>
                            <div class="col-lg-11 col-md-10">
                                @if ($data->alamat != '')
                                    {{ $data->alamat }}
                                @else
                                    <em class="text-muted">Belum Tersedia</em>
                                @endif
                            </div>
                        </div>

                    </li>
                    <li class="py-2"><span class="fa-li"><i class="fas fa-map-marker-alt fa-fw"></i></span>
                        <div class="row">
                            <div class="col-lg-1 col-md-2">Provinsi</div>
                            <div class="col-lg-11 col-md-10">
                                @if ($data->provinsi != '')
                                    {{ $data->Provinsi->nama }}
                                @else
                                    <em class="text-muted">Belum Tersedia</em>
                                @endif
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <div class="col-12">
        @if ($data->ket != '')
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <strong>Keterangan: </strong>{{ $data->ket }}
            </div>
        @endif
        <div class="card card-purple card-outline card-tabs">
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
                                    <div><small class="text-muted">No AKN</small></div>
                                    <div><b>
                                            @if ($data->no_paket)
                                                {{ $data->no_paket }}
                                            @else
                                                <em class="text-muted">Belum Tersedia</em>
                                            @endif
                                        </b>
                                    </div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">No Urut</small></div>
                                    <div><b>{{ $data->no_urut }}</b></div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="margin">
                                    <div><small class="text-muted">Tgl Buat</small></div>
                                    <div><b>{{ date('d-m-Y', strtotime($data->tgl_buat)) }}</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Tgl Edit</small></div>
                                    <div><b>@if (!empty($data->edit))
                                        {{ date('d-m-Y', strtotime($data->tgl_edit)) }}
                                    @else
                                     -
                                    @endif</b></div>
                                </div>
                                <div class="margin">
                                    <div><small class="text-muted">Tgl Delivery</small></div>
                                    <div><b>{!! $tgl_kontrak !!}</b></div>
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
                                <div class="margin">
                                    <div><small class="text-muted">Status</small></div>
                                    <div id="status">
                                        {!! $status !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-success" role="alert">
                            <strong>Deskripsi: </strong><p>{{ $data->deskripsi }}</p>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs-produk" role="tabpanel" aria-labelledby="tabs-produk-tab">

                            <?php $totalharga = 0; ?>
                            <?php $no = 0; ?>
                            @if(count($data->Pesanan->DetailPesanan) > 0)
                                        <div class="row">
                                                <div class="card col-lg-4 col-md-12 removeshadow">
                                                    <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-4">
                                                                    <canvas id="myChart" width="400" height="400" class="mb-5"></canvas>
                                                                    <div class="card card-secondary card-outline mt-3">
                                                                        <div class="card-body">
                                                                            <h3 class="profile-username text-center"><span id="nama_prd">-</span></h3>
                                                                            <ul class="list-group list-group-unbordered mb-3">
                                                                                <li class="list-group-item">
                                                                                    <span class="align-self-center"><span class="foo bg-chart-orange mr-2"></span><span>Gudang</span></span> <a class="float-right mr-2"><b><span id="c_gudang" class="text-danger">0</span></b><sub id="tot_gudang"> dari 0</sub></a>
                                                                                </li>
                                                                                <li class="list-group-item">
                                                                                    <span class="align-self-center"><span class="foo bg-chart-yellow mr-2"></span><span>QC</span></span> <a class="float-right mr-2"><b><span id="c_qc" class="text-danger">0</span></b><sub  id="tot_qc"> dari 0</sub></a>
                                                                                </li>
                                                                                <li class="list-group-item">
                                                                                    <span class="align-self-center"><span class="foo bg-chart-green mr-2"></span><span>Logistik</span></span> <a class="float-right mr-2"><b><span id="c_log" class="text-danger">0</span></b><sub  id="tot_log"> dari 0</sub></a>
                                                                                </li>
                                                                                <li class="list-group-item bg-chart-blue text-white">
                                                                                    <span class="align-self-center"><span class="foo mr-2"></span><b>Kirim</b></span> <b class="float-right mr-2"><span id="c_kirim">0</span> <sub>unit</sub> {{--<sub  id="tot_kirim"> dari 0</sub>--}}</b>
                                                                                </li>
                                                                            </ul>
                                                                            <div class="alert alert-info show" role="alert">
                                                                                <small>
                                                                                <i class="fas fa-info-circle"></i> <strong>Catatan: </strong>
                                                                                <ol style="list-item-style:none; margin-left:0px;padding-left:15px;" >
                                                                                    <li>Angka warna <b class="text-danger">merah</b> menunjukkan jumlah unit yang <i>belum diproses</i> oleh divisi tersebut</li>
                                                                                    <li>Angka warna <b class="text-dark">hitam</b> menunjukkan total yang <i>telah diberikan dan harus diproses</i> oleh divisi tersebut</li>
                                                                                    <li>Angka pada Kirim merupakan total unit yang <i>telah terkirim</i></li>
                                                                                </ol>
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="card col-lg-8 col-md-12">
                                                    <div class="card-body">
                                                        <h6><b>Detail Produk</b></h6>
                                                        <div class="table-responsive overflowcard">
                                                            <table class="table"
                                                                style="max-width:100%; overflow-x: hidden;"
                                                                id="tabledetailpesan">
                                                                <thead class="bg-chart-light">
                                                                    <tr>
                                                                        <th rowspan="2">No</th>
                                                                        <th rowspan="2">Produk</th>
                                                                        <th rowspan="2"></th>
                                                                        <th rowspan="2">Qty</th>
                                                                        <th rowspan="2">Harga</th>
                                                                        <th rowspan="2">Ongkir</th>
                                                                        <th rowspan="2">Subtotal</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
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
                                                                                    <button class="btn btn-sm btn-outline-primary" id="lihatstok" data-id="{{$e->id}}" data-produk="paket"><i class="fas fa-eye"></i></button>
                                                                                </td>
                                                                                <td class="nowraptxt">{{ $e->jumlah }}
                                                                                </td>
                                                                                <td rowspan="{{ count($e->DetailPesananProduk) + 1 }}"
                                                                                    class="nowraptxt tabnum">@currency($e->harga)</td>
                                                                                <td rowspan="{{ count($e->DetailPesananProduk) + 1 }}"
                                                                                    class="nowraptxt tabnum">@currency($e->ongkir)</td>
                                                                                <td rowspan="{{ count($e->DetailPesananProduk) + 1 }}"
                                                                                    class="nowraptxt tabnum">@currency($e->harga * $e->jumlah + $e->ongkir)</td>
                                                                                <?php $totalharga = $totalharga + (($e->harga * $e->jumlah) + $e->ongkir); ?>
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
                                                                                            <button class="btn btn-sm btn-outline-primary" id="lihatstok" data-id="{{$l->id}}"  data-produk="variasi"><i class="fas fa-eye"></i></button>
                                                                                        </td>
                                                                                        <td>
                                                                                            {{ $l->getJumlahPesanan() }}
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                                <tfoot class="bg-chart-light align-center">
                                                                    <tr>
                                                                        <th colspan="6">Total Harga</th>
                                                                        <th class="nowraptxt tabnum">@currency($totalharga)</th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                            @else
                                <div class="align-center text-danger"><i>Detail Pesanan Belum Tersedia</i></div>
                            @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

