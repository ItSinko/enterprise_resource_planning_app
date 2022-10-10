<form action="/api/logistik/pengiriman/update/{{$data->id}}" method="POST" id="form-pengiriman-update">
    @method('PUT')
    @csrf
    <div class="content">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="row">
                    <div class="col-12">
                        {{-- <div class="card">
                            <div class="card-header">Customer</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 align-center">
                                        <!-- <div id="profileImage" class="center margin-all"></div> -->
                                        @if(isset($data->DetailLogistik[0]))

                                        @if(isset($data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog))
                                        <div>
                                            <h6><b>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi}}</b></h6>
                                        </div>
                                        <div>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->alamat}}</div>
                                        <div>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama}}</div>
                                        @elseif(isset($data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa))
                                        <div>
                                            <h6><b>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama}}</b></h6>
                                        </div>
                                        <div>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat}}</div>
                                        <div>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama}}</div>
                                        @else
                                        <div>
                                            <h6><b>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama}}</b></h6>
                                        </div>
                                        <div>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat}}</div>
                                        <div>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama}}</div>
                                        @endif
                                        @elseif(isset($data->DetailLogistik[0]) && isset($data->DetailLogistikPart))
                                        @if(isset($data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog))
                                        <div>
                                            <h6><b>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi}}</b></h6>
                                        </div>
                                        <div>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->alamat}}</div>
                                        <div>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama}}</div>
                                        @elseif(isset($data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa))
                                        <div>
                                            <h6><b>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama}}</b></h6>
                                        </div>
                                        <div>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat}}</div>
                                        <div>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama}}</div>
                                        @else
                                        <div>
                                            <h6><b>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama}}</b></h6>
                                        </div>
                                        <div>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat}}</div>
                                        <div>{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama}}</div>
                                        @endif
                                        @else
                                        @if(isset($data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa))
                                        <div>
                                            <h6><b>{{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->nama}}</b></h6>
                                        </div>
                                        <div>{{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->alamat}}</div>
                                        <div>{{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->Provinsi->nama}}</div>
                                        @else
                                        <div>
                                            <h6><b>{{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->nama}}</b></h6>
                                        </div>

                                        <div>{{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->alamat}}</div>
                                        <div>{{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->provinsi->nama}}</div>
                                        @endif
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h6 class="card-title">Customer</h6>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($jenis == "EKAT")
                                    <strong>Distributor</strong>
                                    <p class="text-muted">{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama}}</p>
                                    <hr>
                                @endif

                                <strong>{{--<i class="fas fa-book mr-1 fa-fw"></i>--}} Nama Customer</strong>
                                <p class="text-muted">
                                    @if($jenis == "EKAT")
                                    {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->satuan}}
                                    @else
                                        @if(isset($data->DetailLogistik[0]))
                                            @if(isset($data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa))
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama}}
                                            @else
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama}}
                                            @endif
                                        @elseif(isset($data->DetailLogistik[0]) && isset($data->DetailLogistikPart))
                                            @if(isset($data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa))
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama}}
                                            @else
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama}}
                                            @endif
                                        @else
                                            @if(isset($data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa))
                                            {{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->nama}}
                                            @else
                                            {{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->nama}}
                                            @endif
                                        @endif
                                    @endif
                                </p>
                                <hr>
                                <strong>{{--<i class="fas fa-calendar-alt fa-fw"></i>--}} Alamat</strong>
                                <p class="text-muted">
                                    @if($jenis == "EKAT")
                                    {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->alamat}}
                                    @else
                                        @if(isset($data->DetailLogistik[0]))
                                            @if(isset($data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa))
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat}}
                                            @else
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat}}
                                            @endif
                                        @elseif(isset($data->DetailLogistik[0]) && isset($data->DetailLogistikPart))
                                            @if(isset($data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa))
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat}}
                                            @else
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat}}
                                            @endif
                                        @else
                                            @if(isset($data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa))
                                            {{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->alamat}}
                                            @else
                                            {{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->alamat}}
                                            @endif
                                        @endif
                                    @endif
                                </p>
                                <hr>
                                <strong>{{--<i class="fas fa-asterisk fa-fw"></i>--}} Provinsi</strong>
                                <p class="text-muted">
                                    @if($jenis == "EKAT")
                                    {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama}}
                                    @else
                                        @if(isset($data->DetailLogistik[0]))
                                            @if(isset($data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa))
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama}}
                                            @else
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama}}
                                            @endif
                                        @elseif(isset($data->DetailLogistik[0]) && isset($data->DetailLogistikPart))
                                            @if(isset($data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa))
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama}}
                                            @else
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama}}
                                            @endif
                                        @else
                                            @if(isset($data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa))
                                            {{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spa->Customer->Provinsi->nama}}
                                            @else
                                            {{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->Spb->Customer->Provinsi->nama}}
                                            @endif
                                        @endif
                                    @endif</p>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h6 class="card-title">Info Penjualan</h6>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            {{-- <div class="card-header">Info Penjualan</div> --}}
                            <div class="card-body">
                                <ul class="list-group list-group-unbordered mb-3">
                                    @if($jenis == "EKAT")
                                    <li class="list-group-item">
                                        <b>No AKN</b> <a class="float-right">{{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->no_paket}}</a>
                                    </li>
                                    @endif
                                    <li class="list-group-item">
                                        <b>No SO</b> <a class="float-right">@if($jenis == "EKAT")
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so}}
                                            @else
                                            @if(isset($data->DetailLogistik[0]))
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so}}
                                            @elseif(isset($data->DetailLogistik[0]) && isset($data->DetailLogistikPart))
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so}}
                                            @else
                                            {{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so}}
                                            @endif
                                            @endif</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>No PO</b> <a class="float-right">@if($jenis == "EKAT")
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}
                                            @else
                                            @if(isset($data->DetailLogistik[0]))
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}
                                            @elseif(isset($data->DetailLogistik[0]) && isset($data->DetailLogistikPart))
                                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}
                                            @else
                                            {{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->no_po}}
                                            @endif
                                            @endif</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>No SJ</b> <a class="float-right">{{$data->nosurat}}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Tanggal Kirim</b> <a class="float-right">{{ date('d-m-Y', strtotime($data->tgl_kirim)) }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Status</b> <a class="float-right">@if($data->status_id == "11")
                                            <span class="badge red-text">Draft Pengiriman</span>
                                            @else
                                            <span class="badge blue-text">Dalam Pengiriman</span>
                                            @endif</a>
                                    </li>
                                </ul>
                                {{-- <div class="margin">
                                    <a class="text-muted">No SO</a>
                                    <b class="float-right">@if($jenis == "EKAT")
                                        {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so}}
                                        @else
                                        @if(isset($data->DetailLogistik[0]))
                                        {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so}}
                                        @elseif(isset($data->DetailLogistik[0]) && isset($data->DetailLogistikPart))
                                        {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so}}
                                        @else
                                        {{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->so}}
                                        @endif
                                        @endif</b>
                                </div>
                                <div class="margin">
                                    <a class="text-muted">No PO</a>
                                    <b class="float-right">
                                        @if($jenis == "EKAT")
                                        {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}
                                        @else
                                        @if(isset($data->DetailLogistik[0]))
                                        {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}
                                        @elseif(isset($data->DetailLogistik[0]) && isset($data->DetailLogistikPart))
                                        {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}
                                        @else
                                        {{$data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->no_po}}
                                        @endif
                                        @endif</b>
                                </div>
                                <div class="margin">
                                    <a class="text-muted">Surat Jalan</a>
                                    <b class="float-right">{{$data->nosurat}}</b>
                                </div>
                                <div class="margin">
                                    <a class="text-muted">Tanggal Kirim</a>
                                    <b class="float-right">{{ date('d-m-Y', strtotime($data->tgl_kirim)) }}</b>
                                </div>
                                <div class="margin">
                                    <a class="text-muted">Status</a>
                                    <b class="float-right" id="status">
                                        @if($data->status_id == "11")
                                        <span class="badge red-text">Draft Pengiriman</span>
                                        @else
                                        <span class="badge blue-text">Dalam Pengiriman</span>
                                        @endif
                                    </b>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">

                <div class="col-12">
                    <div class="card card-outline card-warning">
                        <div class="card-header"><h6 class="card-title">@if($data->status_id == "11") Ubah Surat Jalan @else Tambah Penerima & Resi @endif</h6></div>
                        <div class="card-body">
                            <h5>Data Pengiriman</h5>
                            <div class="form-horizontal">
                                @if($data->status_id == "11")
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5 align-right">No Surat Jalan</label>
                                    <div class="col-6">
                                        <input type="text" class="form-control col-form-label" name="no_invoice" id="no_invoice" value="{{$data->nosurat}}">
                                        <div class="invalid-feedback" id="msgno_invoice"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5 align-right">Pengiriman</label>
                                    <div class="col-5 col-form-label">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pengiriman" id="pengiriman1" value="ekspedisi" @if(!empty($data->ekspedisi_id)) checked @endif/>
                                            <label class="form-check-label" for="pengiriman1">Ekspedisi</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pengiriman" id="pengiriman2" value="nonekspedisi" @if(!empty($data->nama_pengirim)) checked @endif/>
                                            <label class="form-check-label" for="pengiriman2">Non Ekspedisi</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row @if(empty($data->ekspedisi_id)) hide @endif" id="ekspedisi">
                                    <label class="col-form-label col-5 align-right" for="ekspedisi_id">Jasa Pengiriman</label>
                                    <div class="col-7">
                                        <select class="select2 select-info form-control ekspedisi_id" name="ekspedisi_id" id="ekspedisi_id" style="width: 100%;">
                                            @if(!empty($data->ekspedisi_id))
                                            <option value="{{$data->Ekspedisi->id}}">{{$data->Ekspedisi->nama}}</option>
                                            @endif
                                        </select>
                                        <div class="invalid-feedback" id="msgekspedisi_id"></div>
                                    </div>
                                </div>
                                <div class="form-group row @if(empty($data->nama_pengirim)) hide @endif" id="nonekspedisi">
                                    <label class="col-form-label col-5 align-right" for="nama_pengirim">Nama Pengirim</label>
                                    <div class="col-6">
                                        <input type="text" class="form-control col-form-label" name="nama_pengirim" id="nama_pengirim" value="@if(!empty($data->nama_pengirim)) {{$data->nama_pengirim}} @endif">
                                        <div class="invalid-feedback" id="msgnama_pengirim"></div>
                                    </div>
                                </div>
                                @else
                                <div class="form-group row">
                                    <label class="col-form-label col-5 align-right" for="nama_penerima">Nama Penerima</label>
                                    <div class="col-6">
                                        <input type="text" class="form-control col-form-label" name="nama_penerima" id="nama_penerima">
                                        <div class="invalid-feedback" id="msgnama_penerima"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-5 align-right" for="no_resi">No Resi</label>
                                    <div class="col-6">
                                        <input type="text" class="form-control col-form-label" name="no_resi" id="no_resi">
                                        <div class="invalid-feedback" id="msgno_resi"></div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <h5>Data Barang</h5>
                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="table-responsive @if($jenis == "EKAT") overflowtableekat @else overflowtablenonekat @endif">
                                        <table class="table table-hover align-center" style="width:100%;" id="barangtable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Barang</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6 float-left">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                </div>
                                <div class="col-6">
                                    <button type="submit" class="btn btn-warning float-right" id="btnsimpan">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>
