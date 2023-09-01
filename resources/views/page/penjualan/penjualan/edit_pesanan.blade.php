<form action="/api/penjualan/pesanan/update/{{$data->Pesanan->id}}/{{$jenis}}" method="POST" id="form-pesanan-update">
    @method('PUT')
    @csrf
    <div class="content">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">Info Pesanan</div>
                    <div class="card-body">
                        <div class="margin">
                            <a class="text-muted">No SO</a>
                            <b class="float-right">@if ($data->Pesanan->so) {{$data->pesanan->so}} @else - @endif</b>
                        </div>

                        @if($jenis == "ekatalog")
                        <div class="margin">
                            <a class="text-muted">No AKN</a>
                            <b class="float-right">{{$data->no_paket}}</b>
                        </div>
                        @endif

                        <div class="margin">
                            <a class="text-muted">No PO</a>
                            <b class="float-right">
                                @if ($data->Pesanan->no_po)
                                {{$data->Pesanan->no_po}}
                                @else
                                -
                                @endif</b>
                        </div>

                        @if($jenis == "ekatalog")
                        <div class="margin">
                            <a class="text-muted">Status</a>
                            <b class="float-right" id="status">
                                @if($data->status == "sepakat")
                                <span class="badge green-text">{{ucfirst($data->status)}}</span>
                                @elseif($data->status == "negosiasi")
                                <span class="badge yellow-text">{{ucfirst($data->status)}}</span>
                                @elseif($data->status == "batal")
                                <span class="badge red-text">{{ucfirst($data->status)}}</span>
                                @elseif($data->status == "draft")
                                <span class="badge blue-text">{{ucfirst($data->status)}}</span>
                                @endif
                            </b>
                        </div>
                        @else
                        <div class="margin">
                            <a class="text-muted">Status</a>
                            <b class="float-right" id="status">
                            @if (!empty($data->Pesanan->log_id))
                                @if ($data->Pesanan->State->nama == "Penjualan")
                                <span class="red-text badge">
                                @elseif ($data->Pesanan->State->nama == "PO")
                                <span class="purple-text badge">
                                @elseif ($data->Pesanan->State->nama == "Gudang")
                                <span class="orange-text badge">
                                @elseif ($data->Pesanan->State->nama == "QC")
                                <span class="yellow-text badge">
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
                        @endif

                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><i class="fas fa-edit"></i> Ubah No Urut</div>
                        <div class="card-body">
                            <div class="form-horizontal">
                                @if($jenis == "ekatalog")
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">No Urut</label>
                                    <div class="col-lg-3 col-md-12">
                                        <input type="number" class="form-control col-form-label" name="no_urut" id="no_urut" value="{{$data->no_urut}}">
                                        <div class="invalid-feedback" id="msgno_urut"></div>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">No DO</label>
                                    <div class="col-lg-5 col-md-12">
                                        <input type="text" class="form-control col-form-label" name="no_do" id="no_do" value="{{$data->Pesanan->no_do}}">
                                        <div class="invalid-feedback" id="msgno_do"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Tanggal DO</label>
                                    <div class="col-lg-5 col-md-12">
                                        <input type="date" class="form-control col-form-label" name="tgl_do" id="tgl_do" value="{{$data->Pesanan->tgl_do}}">
                                        <div class="invalid-feedback" id="msgtgl_do"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Keterangan</label>
                                    <div class="col-lg-5 col-md-12">
                                        <textarea class="form-control col-form-label" name="keterangan" id="keterangan">{{$data->Pesanan->ket}}</textarea>
                                        <div class="invalid-feedback" id="msgtgl_do"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="float-left"><button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button></span>
                            <span class="float-right"><button type="submit" class="btn btn-warning float-right" id="btnsimpan">Simpan</button></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
