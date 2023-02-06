<div class="row filter d-flex justify-content-center">
    <div class="col-lg-12">
        <div class="card" style="box-shadow:none;">
            <div class="card-body">
                <ul class="fa-ul card-text">
                    <li class="py-2">
                        <div class="row text-md">
                            <div class="col-lg-3 col-md-5 text-muted">Nama Produk</div>
                            <div class="col-lg-9 col-md-7" id="nama_produk"></div>
                        </div>
                    </li>
                    <li class="py-2">
                        <div class="row text-md">
                            <div class="col-lg-3 col-md-5 text-muted">No Seri</div>
                            <div class="col-lg-9 col-md-7" id="no_seri"></div>
                        </div>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-12">
                        <form method="POST" action="{{route('as.perbaikan.switch')}}" id="form-pengganti-seri">
                            @csrf
                            <div class="card card-outline card-warning">
                                <div class="card-body">
                                    <input type="text" class="d-none" id="noseri_perbaikan_id" name="noseri_perbaikan_id" value="">
                                    <div class="form-group row">
                                        <label for="keterangan" class="col-lg-5 col-md-12 col-form-label labelket">No Seri Tersedia</label>
                                        <div class="col-lg-4 col-md-12">
                                            <select name="noseri_id" id="noseri_id" class="form-control custom-select noseri_id">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning float-right" id="btnsubmit">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
