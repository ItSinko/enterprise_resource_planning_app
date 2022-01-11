<form action="/api/logistik/so/create/{{$id}}/{{$id_produk}}/{{$jenis}}" method="POST" id="form-logistik-create">
    @method('PUT')
    @csrf
    <div class="content">
        <div class=" d-flex justify-content-center">
            <div class="col-11">
                <div class="d-flex justify-content-center">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-horizontal">
                                    <h5>Data Pengiriman</h5>
                                    <div class="form-group row">
                                        <label class="col-form-label col-5 align-right" for="no_invoice">No Surat Jalan</label>
                                        <div class="col-6">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">

                                                    <span class="input-group-text">
                                                        @if($jenis != "SPB")
                                                        SPA-
                                                        @else
                                                        B.
                                                        @endif</span>
                                                </div>
                                                <input type="text" class="form-control col-form-label" name="no_invoice" id="no_invoice">
                                                <div class="invalid-feedback" id="msgnoinvoice"></div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-5 align-right" for="tgl_kirim">Tanggal Pengiriman</label>
                                        <div class="col-4">
                                            <input type="date" class="form-control col-form-label" name="tgl_kirim" id="tgl_kirim">
                                            <div class="invalid-feedback" id="msgtgl_kirim"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-5 align-right">Pengiriman</label>
                                        <div class="col-5 col-form-label">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="pengiriman" id="pengiriman1" value="ekspedisi" />
                                                <label class="form-check-label" for="pengiriman1">Ekspedisi</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="pengiriman" id="pengiriman2" value="nonekspedisi" />
                                                <label class="form-check-label" for="pengiriman2">Non Ekspedisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="card col-12 hide" id="ekspedisi">
                                            <div class="card-body">
                                                <h6><b>Ekspedisi</b></h6>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-5 align-right" for="ekspedisi_id">Jasa Pengiriman</label>
                                                    <div class="col-7">
                                                        <select class="select2 select-info form-control ekspedisi_id" name="ekspedisi_id" id="ekspedisi_id" style="width: 100%;">

                                                        </select>
                                                        <div class="invalid-feedback" id="msgekspedisi_id"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card col-12 hide" id="nonekspedisi">
                                            <div class="card-body">
                                                <h6><b>Non Ekspedisi</b></h6>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-5 align-right" for="nama_pengirim">Nama Pengirim</label>
                                                    <div class="col-7">
                                                        <textarea type="text" class="form-control col-form-label" name="nama_pengirim" id="nama_pengirim"></textarea>
                                                        <div class="invalid-feedback" id="msgnama_pengirim"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Data Barang</h5>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-center" style="width:100%;" id="detailpesanan">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Barang</th>
                                                        <th>Jumlah</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- <tr>
                                                    <td>1</td>
                                                    <td>MTB 2 MTR</td>
                                                    <td>10</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>CENTRAL MONITOR PM-9000+ + PC + INSTALASI</td>
                                                    <td>1</td>
                                                </tr> -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 float-left">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-info float-right" id="btnsimpan" disabled>Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{$id}}
    {{$id_produk}}
</form>