<form data-attr="/administrator/user/update/data/{{ $user->id }}" method="POST" id="form-penjualan-user-edit">
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
                                        placeholder="Masukkan Username" value="{{ $user->username }}" />
                                    <div class="invalid-feedback" id="msgusername">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama_produk" class="col-4 col-form-label"
                                    style="text-align: right">E-mail</label>
                                <div class="col-4">
                                    <input type="text" class="form-control" name="email" id="email"
                                        placeholder="Masukkan Email" value="{{ $user->email }}" />
                                    <div class="invalid-feedback" id="msgemail">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nama_produk" class="col-4 col-form-label"
                                    style="text-align: right">Karyawan</label>
                                <div class="col-4">
                                    <select type="text" class="form-control karyawan" name="karyawan" id="karyawan"
                                        placeholder="Masukkan Email" value="">
                                        <option value="{{ $user->karyawan_id }}"> @php echo substr_replace( $user->Karyawan->nama, "...", 20) .' -  <i>'. $user->Karyawan->Divisi->nama .'</i>'@endphp</option>
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
                                    <span>
                                        <button type="button" id="resetpwd{{ $user->id }}"
                                            class="btn btn-danger float-left ">Reset
                                            Password</button>
                                    </span>
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
