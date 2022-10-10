<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h6 class="card-title">Info Produk</h6>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">

                        <strong>{{--<i class="fas fa-book mr-1 fa-fw"></i>--}} Jenis Produk</strong>
                        <p class="text-muted">
                            {{$data->product->nama}}
                        </p>
                        <hr>
                        <strong>{{--<i class="fas fa-calendar-alt fa-fw"></i>--}} Tipe</strong>
                        <p class="text-muted">
                            {{$data->nama}}
                        </p>
                        <hr>
                        <strong>{{--<i class="fas fa-asterisk fa-fw"></i>--}} Kelompok Produk</strong>
                        <p class="text-muted">
                            @if($data->KelompokProduk->nama == 'Alat Kesehatan')
                                        <span class="badge blue-text">
                                    @elseif($data->KelompokProduk->nama == 'Water Treatment')
                                        <span class="badge orange-text">
                                    @elseif($data->KelompokProduk->nama == 'Aksesoris')
                                        <span class="badge purple-text">
                                    @elseif($data->KelompokProduk->nama == 'Lain Lain')
                                        <span class="badge red-text">
                                    @elseif($data->KelompokProduk->nama == 'Sparepart')
                                        <span class="badge green-text">
                                    @endif
                                    {{$data->KelompokProduk->nama}}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-12">
                <form method="POST" action="/api/master/produk/update_coo/{{$data->id}}" id="form-update">
                    @method('PUT')
                    @csrf
                    <div class="card card-outline card-warning">
                        <div class="card-header">
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <h6 class="card-title">Ubah Data</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-md-12 labelket" for="nama_coo">Nama COO</label>
                                <div class="col-lg-7 col-md-12">
                                    <input type="text" class="form-control col-form-label" name="nama_coo" id="nama_coo" value="{{$data->nama_coo}}">
                                    <div class="invalid-feedback" id="msgnama_coo"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-lg-4 col-md-12 labelket" for="no_akd">No AKD</label>
                                <div class="col-lg-5 col-md-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control col-form-label" name="no_akd" id="no_akd" value="{{$data->no_akd}}" readonly="true">
                                        <span class="input-group-append" >
                                           <button type="button" class="btn btn-outline-info btn-round tambah_akd" id="btn_akd"><i class="fas fa-plus"></i> Tambah AKD Baru</button>
                                        </span>
                                    </div>
                                    <div class="invalid-feedback" id="msgno_akd"></div>
                                </div>
                            </div>

                            <div class="row d-flex justify-content-center hide" id="akd_field">
                                <fieldset class="col-lg-8 col-md-11 form-group border px-3">
                                    <legend><h6>Tambah AKN Baru</h6></legend>
                                    <div class="form-group">
                                      <label for="no_akd_baru">No AKD</label>
                                      <input type="text" id="no_akd_baru" class="form-control col-8" placeholder="Masukkan No AKD Baru">
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                          <label for="validationTooltip01">Tanggal Terbit</label>
                                          <input type="date" class="form-control" id="validationTooltip01" value="">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                          <label for="validationTooltip02">Tanggal Kadaluarsa</label>
                                          <input type="date" class="form-control" id="validationTooltip02" value="">

                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-form-label col-lg-4 col-md-12 labelket">COO</label>
                                <div class="col-lg-7 col-md-12 col-form-label">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="coo" id="coo1" value="1" @if($data->coo == '1') checked @endif/>
                                        <label class="form-check-label" for="coo1">Produk Utama</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="coo" id="coo2" value="0" @if($data->coo != '1') checked @endif/>
                                        <label class="form-check-label" for="coo2">Bukan Produk Utama</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span><button class="btn btn-danger" type="button" id="btncancel" data-dismiss="modal">Batal</button></span>
                            <span class="float-right"><button class="btn btn-warning" type="submit" id="btnsimpan">Simpan</button></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
