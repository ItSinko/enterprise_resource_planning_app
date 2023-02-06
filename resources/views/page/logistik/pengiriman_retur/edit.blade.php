<form action="{{route('logistik.pengiriman_retur.update')}}" method="POST" id="formsavenosj">
    @csrf
<div class="row">
    <div class="col-lg-5 col-md-12">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" id="title_info_cust">Informasi Pengiriman</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body align-center">
                    <div class="margin-all mt-2">
                        <h5><b id="nama_penerima_edit">-</b></h5>
                    </div>
                    <div class="margin-all text-muted"><i class="fas fa-phone"></i> <span id="telp_penerima_edit">-</span></div>
                    <div class="margin-all mt-2 text-muted">
                        <span id="alamat_penerima_edit">-</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" id="title_info_cust">Barang Pengiriman</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body align-center">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-no_seri-tab" data-toggle="pill"
                            href="#pills-no_seri" role="tab" aria-controls="pills-no_seri"
                            aria-selected="false">No Seri</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-part-tab" data-toggle="pill"
                            href="#pills-part" role="tab" aria-controls="pills-part"
                            aria-selected="false">Part</a>
                    </li>
                </ul>
                <div class="tab-content contentdiv" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-no_seri" role="tabpanel"
                        aria-labelledby="pills-no_seri-tab">
                        <div class="table-responsive">
                            <table class="table-hover table align-center" id="noseritable" style="width:100%;">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th style="width:10%;">No</th>
                                        <th style="width:65%;">Nama Produk</th>
                                        <th style="width:25%;">No Seri</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-part" role="tabpanel"
                        aria-labelledby="pills-part-tab">
                        <div class="table-responsive">
                            <table class="table-hover table align-center" id="parttable" style="width:100%;">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th style="width:10%;">No</th>
                                        <th style="width:70%;">Nama Part</th>
                                        <th style="width:20%;">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7 col-md-12">

                <div class="form-horizontal">
                    <div id="informasi_pelanggan" class="card card-outline card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Identitas Surat Jalan</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <input type="hidden" name="id" id="id" class="form-control col-form-label id text-sm">
                            <div class="form-group row">
                                <label for="no_surat_jalan" class="col-lg-5 col-md-12 col-form-label labelket">No Surat Jalan</label>
                                <div class="col-lg-4 col-md-8">
                                    <input name="no_surat_jalan" id="no_surat_jalan" class="form-control col-form-label no_surat_jalan text-sm @error('no_surat_jalan') is-invalid @enderror">
                                    <div class="invalid-feedback" id="msg_no_sj"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tgl_pengiriman" class="col-lg-5 col-md-12 col-form-label labelket">Tanggal Pengiriman</label>
                                <div class="col-lg-4 col-md-6">
                                    <input type="date" name="tgl_pengiriman" id="tgl_pengiriman" class="form-control col-form-label tgl_pengiriman text-sm @error('tgl_pengiriman') is-invalid @enderror">
                                    <div class="invalid-feedback" id="msgtgl_pengiriman"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ekspedisi_id" class="col-lg-5 col-md-12 col-form-label labelket">Ekspedisi / Nama Pengirim</label>
                                <div class="col-lg-6 col-md-8">
                                    <div id="input-ekspedisi_id">
                                        <select name="ekspedisi_id" id="ekspedisi_id" class="ekspedisi_id form-control custom-select text-sm"></select>
                                    </div>
                                    <input type="text" name="non_ekspedisi" id="non_ekspedisi" class="non_ekspedisi form-control text-sm hide"/>
                                    <div class="form-check form-check-inline mt-1">
                                        <input class="form-check-input" type="checkbox" id="pilih_pengiriman" value="non-ekspedisi">
                                        <label class="form-check-label" for="ekspedisi_id">Pengiriman Non Ekspedisi</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="biaya_kirim" class="col-lg-5 col-md-12 col-form-label labelket">Biaya Kirim</label>
                                <div class="col-lg-4 col-md-8">
                                    <input type="text" name="biaya_kirim" id="biaya_kirim" class="biaya_kirim form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning float-right" id="btnsubmit">Simpan</button>
                        </div>
                    </div>
                </div>


    </div>
</div>
</form>
