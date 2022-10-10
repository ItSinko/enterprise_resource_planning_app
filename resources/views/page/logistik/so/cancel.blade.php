<div class="row filter">
    <div class="col-12">
        <div class="card card-navy card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tabs-detail-tab" data-toggle="pill" href="#tabs-detail"
                            role="tab" aria-controls="tabs-detail" aria-selected="true">Informasi Umum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tabs-produk-tab" data-toggle="pill" href="#tabs-produk" role="tab"
                            aria-controls="tabs-produk" aria-selected="false">Detail Pembatalan</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                    <div class="tab-pane fade active show" id="tabs-detail" role="tabpanel" aria-labelledby="tabs-detail-tab">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="info-box bg-warning">
                                    <span class="info-box-icon"><i class="fas fa-receipt"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">@if(!empty($p->Ekatalog)) No Paket @else No PO @endif</span>
                                    <span class="info-box-number">@if(!empty($p->Ekatalog)) {{$p->Ekatalog->no_paket}} @else {{$p->no_po}} @endif</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="info-box bg-olive">
                                    <span class="info-box-icon"><i class="far fa-user"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">Nama Customer</span>
                                    <span class="info-box-number">@if(!empty($p->Ekatalog)) {{$p->Ekatalog->Customer->nama}} @elseif(!empty($p->Spa)) {{$p->Spa->Customer->nama}} @else {{$p->Spb->Customer->nama}} @endif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="info-box bg-maroon">
                                    <span class="info-box-icon"><i class="fas fa-truck"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">No SO</span>
                                    <span class="info-box-number">{{$p->so}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="info-box bg-navy" style="box-shadow: none">
                                    <span class="info-box-icon"><i class="far fa-calendar"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">Tanggal Batal</span>
                                    <span class="info-box-number">{{Carbon::now()->isoFormat('D MMMM Y')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tabs-produk" role="tabpanel" aria-labelledby="tabs-produk-tab">
                        <div class="row">
                            <div class="col-12">
                                <div class="info-box bg-danger">
                                    <span class="info-box-icon"><i class="fas fa-exclamation-triangle fa-fw"></i></span>
                                    <div class="info-box-content">
                                    <span class="info-box-text">Alasan Batal</span>
                                    <span class="info-box-number"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card removeshadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="produktable" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Array</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-danger btn-sm @if(Auth::user()->divisi->nama != "Logistik") float-right @endif" data-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                @if(Auth::user()->divisi->nama == "Logistik")
                <button type="button" class="btn btn-dark btn-sm float-right"><i class="fas fa-check"></i> Terima</button>
                @endif
            </div>
        </div>
    </div>
</div>
