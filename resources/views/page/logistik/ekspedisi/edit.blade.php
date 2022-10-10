@foreach($ekspedisi as $e)
<form action="" method="post" data-attr="{{route('logistik.ekspedisi.update', ['id' => $e->id])}}" data-id="{{$e->id}}" id="form-ekspedisi-update">
    @method('PUT')
    <div class="row d-flex justify-content-center">
        <div class="col-10">
            @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show col-12" role="alert">
                        <strong>Gagal menambahkan!</strong> Periksa
                        kembali data yang diinput
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
            @elseif(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show col-12" role="alert">
                        <strong>Berhasil menambahkan data</strong>,
                        Terima kasih
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
            @endif
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h6 class="card-title">Informasi Umum</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="nama_ekspedisi" class="col-lg-4 col-md-12 col-form-label labelket">Nama Ekspedisi</label>
                        <div class="col-lg-6 col-md-10">
                            <input type="text" class="form-control @error('nama_ekspedisi') is-invalid @enderror" placeholder="Masukkan Nama Ekspedisi" id="nama_ekspedisi" name="nama_ekspedisi" value="{{$e->nama}}" />
                            <div class="invalid-feedback" id="msgnama_ekspedisi">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="alamat" class="col-lg-4 col-md-12 col-form-label labelket">Alamat</label>
                        <div class="col-lg-8 col-md-11">
                            <textarea class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan Alamat" id="alamat" name="alamat">{{$e->alamat}}</textarea>
                            <div class="invalid-feedback" id="msgalamat">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-lg-4 col-md-12 col-form-label labelket">Email</label>
                        <div class="col-lg-8 col-md-12">
                            <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Email" id="email" name="email" value="{{$e->email}}" />
                            <div class="invalid-feedback" id="msgemail">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="telepon" class="col-lg-4 col-md-12 col-form-label labelket">No Telp</label>
                        <div class="col-lg-5 col-md-8">
                            <input type="text" class="form-control @error('telepon') is-invalid @enderror" placeholder="Masukkan Telepon" id="telepon" name="telepon" value="{{$e->telp}}" />
                            <div class="invalid-feedback" id="msgtelepon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h6 class="card-title">Detail Ekspedisi</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label for="" class="col-lg-4 col-md-12 col-form-label labelket">Jalur</label>
                        <div class="col-lg-8 col-md-12 col-form-label">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input jalur" type="checkbox" id="jalur1" value="1" name="jalur[]">
                                <label class="form-check-label" for="jalur1">Darat</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input jalur" type="checkbox" id="jalur2" value="2" name="jalur[]">
                                <label class="form-check-label" for="jalur2">Laut</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input jalur" type="checkbox" id="jalur3" value="3" name="jalur[]">
                                <label class="form-check-label" for="jalur3">Udara</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input jalur" type="checkbox" id="jalur4" value="4" name="jalur[]">
                                <label class="form-check-label" for="jalur4">Lain</label>
                            </div>
                            <div class="invalid-feedback" id="msgjalur">
                                @if($errors->has('jalur'))
                                {{ $errors->first('jalur')}}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-lg-4 col-md-12 col-form-label labelket">Jurusan</label>
                        <div class="col-lg-8 col-md-12 col-form-label">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input jurusan" type="radio" name="jurusan" id="jurusan1" value="indonesia" />
                                <label class="form-check-label" for="jurusan1">Seluruh Provinsi</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input jurusan" type="radio" name="jurusan" id="jurusan2" value="provinsi" />
                                <label class="form-check-label" for="jurusan2">Per Provinsi</label>
                            </div>

                            <div class="invalid-feedback" id="msgjurusan">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" id="prov_select">
                        <label for="keterangan" class="col-lg-4 col-md-12 col-form-label labelket">Provinsi</label>
                        <div class="col-lg-8">
                            <select class="provinsi form-control" name="provinsi_id[]" id="provinsi" style="width: 100%;" >
                            </select>
                        </div>
                    </div>

                    {{-- <div class="form-group row hide" id="kota_kabupaten_select">
                        <label for="jurusan" class="col-lg-4 col-md-12 col-form-label labelket">Kota / Kabupaten</label>
                        <div class="col-8">
                            <select class="select-info form-control custom-select kota_kabupaten" name="kota_kabupaten" id="kota_kabupaten" style="width: 100%;">
                            </select>
                            <div class="invalid-feedback" id="msgkota_kabupaten">
                                @if($errors->has('kota_kabupaten'))
                                {{ $errors->first('kota_kabupaten')}}
                                @endif
                            </div>
                        </div>
                    </div> --}}

                    <div class="form-group row">
                        <label for="keterangan" class="col-lg-4 col-md-12 col-form-label labelket">Keterangan</label>
                        <div class="col-lg-5 col-md-10">
                            <textarea class="form-control" name="keterangan" id="keterangan">{{$e->ket}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <span class="float-right filter">
                        <button type="submit" class="btn btn-warning" id="btnsimpan" disabled="true">
                            Simpan
                        </button>
                    </span>
                    <span class="float-left filter"><button type="button" class="btn btn-danger" data-dismiss="modal">
                            Batal
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</form>
@endforeach
