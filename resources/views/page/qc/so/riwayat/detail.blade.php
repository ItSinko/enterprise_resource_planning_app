<div class="content">
    <div class="row d-flex justify-content">
        <div class="col-lg-5 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Info Customer</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 align-center">
                            @if(isset($res->Pesanan->Ekatalog))
                            <!-- <div id="profileImage" class="center margin-all"></div> -->
                            <div class="margin">
                                <h6><b>{{$res->Pesanan->Ekatalog->Customer->nama}}</b></h6>
                            </div>
                            <div class="margin"><b>{{$res->Pesanan->Ekatalog->satuan}}</b></div>
                            <div class="margin">{{$res->Pesanan->Ekatalog->alamat}}</div>
                            <div class="margin">{{$res->Pesanan->Ekatalog->Provinsi->nama}}</div>
                            @elseif(isset($res->Pesanan->Spa))
                            <!-- <div id="profileImage" class="center margin-all"></div> -->
                            <div class="margin">
                                <h6><b>{{$res->Pesanan->Spa->Customer->nama}}</b></h6>
                            </div>
                            <div class="margin">{{$res->Pesanan->Spa->Customer->alamat}}</div>
                            <div class="margin">{{$res->Pesanan->Spa->Customer->Provinsi->nama}}</div>
                            @elseif(isset($res->Pesanan->Spb))
                            <!-- <div id="profileImage" class="center margin-all"></div> -->
                            <div class="margin">
                                <h6><b>{{$res->Pesanan->Spb->Customer->nama}}</b></h6>
                            </div>
                            <div class="margin">{{$res->Pesanan->Spb->Customer->alamat}}</div>
                            <div class="margin">{{$res->Pesanan->Spb->Customer->Provinsi->nama}}</div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Info Produk</h5>
                </div>
                @if($jenis == "produk")
                <div class="card-body">
                    <div class="margin">
                        <div><small class="text-muted">Nama Produk</small></div>
                        <div><b>{{$res->PenjualanProduk->nama}}</b></div>
                    </div>
                    @if(count($res->DetailPesananProduk) <= 1) @if($res->DetailPesananProduk->first()->GudangBarangJadi->nama != "")
                        <div class="margin">
                            <div><small class="text-muted">Variasi</small></div>
                            <div>
                                <b> {{$res->DetailPesananProduk->first()->GudangBarangJadi->nama}} </b>
                            </div>
                        </div>
                        @endif
                        @endif
                        <div class="margin">
                            <div><small class="text-muted">No SO</small></div>
                            <div><b>{{$res->Pesanan->so}}</b></div>
                        </div>
                        <div class="margin">
                            <div><small class="text-muted">Jumlah</small></div>
                            <div><b>{{$res->jumlah}}</b></div>
                        </div>
                </div>
                @elseif($jenis == "part")
                <div class="card-body">
                    <div class="margin">
                        <div><small class="text-muted">Nama Part</small></div>
                        <div><b>{{$res->Sparepart->nama}}</b></div>
                    </div>
                    <div class="margin">
                        <div><small class="text-muted">No SO</small></div>
                        <div><b>{{$res->Pesanan->so}}</b></div>
                    </div>
                    <div class="margin">
                        <div><small class="text-muted">Jumlah</small></div>
                        <div><b>{{$res->jumlah}}</b></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="col-lg-7 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-horizontal">
                        <div class="form-group row d-flex justify-content-center">
                            <label class="p-2 col-form-label">Detail Produk</label>
                            <div class="p-2">
                                @if($jenis == "produk")
                                    @if(count($res->DetailPesananProduk) <= 1)
                                        <label for="" class="">{{$res->PenjualanProduk->nama}}
                                            @if($res->DetailPesananProduk->first()->GudangBarangJadi->nama != "")
                                            - {{$res->DetailPesananProduk->first()->GudangBarangJadi->nama}}
                                            @endif
                                        </label>
                                    @else
                                        <select class="form-control detail_produk custom-select col-form-label" name="detail_produk" id="detail_produk">
                                        </select>
                                    @endif
                                @else
                                    <label for="" class="col-form-label">{{$res->Sparepart->nama}}</label>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-center" id="noseritable" style="width:100%;">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">No Seri</th>
                                    <th rowspan="2">Hasil</th>
                                    <th rowspan="2">Tanggal Uji</th>
                                    <th colspan="2">Jumlah</th>
                                </tr>
                                <tr>
                                    <th><i class="fas fa-check-circle" style="color:green;"></i></th>
                                    <th><i class="fas fa-times-circle" style="color:red;"></i></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
