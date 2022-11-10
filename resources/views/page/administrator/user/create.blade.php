<form data-attr="/administrator/user/store" method="POST" id="form-penjualan-user-create">
    <div class="row d-flex justify-content-center">
        <div class="col-11">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h6 class="card-title"></h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group row">
                                <label for="nama_produk" class="col-4 col-form-label"
                                    style="text-align: right">Username</label>
                                <div class="col-4">
                                    <input type="text" class="form-control" name="username" id="username"
                                        placeholder="Masukkan Username" />
                                    <div class="invalid-feedback" id="msgusername">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama_produk" class="col-4 col-form-label"
                                    style="text-align: right">E-mail</label>
                                <div class="col-4">
                                    <input type="text" class="form-control" name="email" id="email"
                                        placeholder="Masukkan Email" />
                                    <div class="invalid-feedback" id="msgemail">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama_produk" class="col-4 col-form-label"
                                    style="text-align: right">Karyawan</label>
                                <div class="col-4">
                                    <select type="text" class="form-control karyawan" name="karyawan" value="">
                                        @foreach ($karyawan as $k)
                                            <option value="{{ $k->id }}">
                                                @php echo substr_replace( $k->nama, "...", 20) .' -  <i>'. $k->Divisi->nama .'</i>'@endphp
                                            </option>
                                        @endforeach

                                    </select>
                                    <div class="invalid-feedback" id="msgkaryawan">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama_produk" class="col-4 col-form-label" style="text-align: right"></label>
                                <div class="col-4">
                                    <p form-control>*Password default :sinkoprima</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-11">
            <span>
                <button class="btn btn-danger float-left" data-dismiss="modal">Batal</button>
            </span>
            <span class="float-right">
                <button type="submit" class="btn btn-warning float-right" id="btnsimpan">Simpan</button>
            </span>
        </div>
    </div>
</form>
