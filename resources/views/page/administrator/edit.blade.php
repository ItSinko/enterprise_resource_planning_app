@foreach ($penjualanproduk as $p)
    <form data-attr="{{ route('penjualan.produk.update', ['id' => $p->id]) }}" data-id="{{ $p->id }}"
        method="post" id="form-penjualan-produk-update">
        @method('PUT')
        <div class="row d-flex justify-content-center">
            <div class="col-11">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h6 class="card-title">Info Umum Paket</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <label for="jenis_paket" class="col-4 col-form-label" style="text-align: right">Jenis
                                        Paket</label>
                                    <div class="col-6 col-form-label">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenis_paket"
                                                id="jenis_paket1" value="ekat" checked="true"  {{ ($p->status=="ekat")? "checked" : "" }}/>
                                            <label class="form-check-label" for="jenis_paket1">Produk
                                                Ekatalog</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenis_paket"
                                                id="jenis_paket2" value="non"  {{ ($p->status=="non")? "checked" : "" }} />
                                            <label class="form-check-label" for="jenis_paket2">Produk Non
                                                Ekatalog</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama_produk" class="col-4 col-form-label" style="text-align: right">Nama
                                        Alias</label>
                                    <div class="col-6">
                                        <textarea type="text" class="form-control " name="nama_alias" id="nama_alias"
                                            placeholder="Masukkan Nama Alias / Panjang">{{ $p->nama_alias }}</textarea>
                                        <div class="invalid-feedback" id="msgnama_alias">
                                        </div>
                                        <div class="feedback" id="msgcustomer_id">
                                            <small class="text-muted">Kosongi bila tidak ada</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama_produk" class="col-4 col-form-label" style="text-align: right">Nama
                                        Paket</label>
                                    <div class="col-6">
                                        <input type="text" class="form-control" name="nama_paket" id="nama_paket"
                                            placeholder="Masukkan Nama Paket" value="{{ $p->nama }}" />
                                        <div class="invalid-feedback" id="msgnama_paket">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama_produk" class="col-4 col-form-label"
                                        style="text-align: right">Harga</label>
                                    <div class="input-group col-5">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" class="form-control" name="harga" id="harga"
                                            placeholder="Masukkan Harga" value="{{ number_format($p->harga) }}" />
                                        <div class="invalid-feedback" id="msgharga">

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="is_aktif" class="col-4 col-form-label"
                                        style="text-align: right">Status</label>
                                    <div class="col-6 col-form-label">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_aktif"
                                                id="is_aktif1" value="1" {{ ($p->is_aktif == "1")? "checked" : "" }}/>
                                            <label class="form-check-label" for="is_aktif1">Aktif</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_aktif"
                                                id="is_aktif2" value="0" {{ ($p->is_aktif == "0")? "checked" : "" }} />
                                            <label class="form-check-label" for="is_aktif2" >Tidak Aktif</label>
                                        </div>
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
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h6 class="card-title">Detail Produk Paket</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table" style="text-align: center;" id="createtable">
                                        <thead>
                                            <tr>
                                                <th colspan="5">
                                                    <button type="button" class="btn btn-primary float-right"
                                                        id="addrow">
                                                        <i class="fas fa-plus"></i>
                                                        Produk
                                                    </button>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>No</th>
                                                <th width="50%">Nama Produk</th>
                                                <th>Kelompok</th>
                                                <th width="20%">Jumlah</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($p->produk as $s)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="form-group row">
                                                                <select class="select-info select2 form-control produk_id"
                                                                    name="produk_id[]" id="{{ $loop->iteration - 1 }}"
                                                                    style="width:100%">
                                                                    <option value="{{ $s->id }}" selected>
                                                                        {{ $s->nama }}</option>
                                                                </select>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge kelompok_produk"
                                                            id="kelompok_produk{{ $loop->iteration - 1 }}">{{ $s->kelompokproduk->nama }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="form-group d-flex justify-content-center">
                                                            <input type="text" class="form-control jumlah" name="jumlah[]"
                                                                id="jumlah" style="width: 50%"
                                                                value="{{ $s->pivot->jumlah }}" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a id="removerow"><i class="fas fa-minus"
                                                                style="color: red"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
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
                    <button type="submit" class="btn btn-warning float-right" id="btnsimpan" disabled="true">Simpan</button>
                </span>
            </div>
        </div>
    </form>
@endforeach
