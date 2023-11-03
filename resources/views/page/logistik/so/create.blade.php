<form action="/api/logistik/so/create/{{ $jenis }}" method="POST"
    id="form-logistik-create">
    @method('PUT')
    @csrf
    <div class="content">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-11">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                              <h5>Data PIC</h5>
                              <div class="form-group row">
                                <label class="col-form-label col-lg-5 col-md-12 labelket" for="no_invoice">Nama PIC</label>
                                <div class="col-lg-6 col-md-12">
                                  <input type="text" class="form-control" name="nama_pic" id="nama_pic">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-form-label col-lg-5 col-md-12 labelket" for="no_invoice">Nomor Telepon PIC</label>
                                <div class="col-lg-6 col-md-12">
                                  <input type="text" class="form-control" name="telp_pic" id="telp_pic" onkeypress="return isNumberKey(event)">
                                </div>
                              </div>
                            </div>
                          </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-horizontal">
                                    <h5>Data Pengiriman</h5>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-5 col-md-12 labelket"
                                            for="no_invoice">Nomor</label>
                                        <div class="col-lg-6 col-md-12  col-form-label">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="no_sj_exist"
                                                    id="no_sj_exist1" value="baru" checked="true" />
                                                <label class="form-check-label" for="no_sj_exist1">Surat Jalan
                                                    Baru</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="no_sj_exist"
                                                    id="no_sj_exist2" value="lama" />
                                                <label class="form-check-label" for="no_sj_exist2">Surat Jalan
                                                    Tersedia</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-5 col-md-12 labelket" for="no_invoice">No
                                            Surat Jalan</label>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="input-group mb-3 sj_baru" id="sj_baru" name="sj_baru">
                                                <div class="input-group-prepend">
                                                    {{-- <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</button> --}}

                                                    {{-- <span class="input-group-text"> --}}
                                                    <select class="form-control jenis_sj" name="jenis_sj" id="jenis_sj">
                                                        @if ($jenis != 'SPB')
                                                            <option value="SPA-" selected>SPA-</option>
                                                        @elseif($jenis == 'SPB')
                                                            <option value="B." selected>B.</option>
                                                        @endif
                                                        <option value="NBT">NBT</option>
                                                    </select>
                                                    {{-- @if ($jenis != 'SPB')
                                                            SPA-
                                                            @else
                                                            B.
                                                            @endif --}}
                                                    {{-- </span> --}}
                                                </div>
                                                <input type="text" class="form-control col-form-label" name="no_invoice"
                                                    id="no_invoice">
                                                <div class="invalid-feedback" id="msgnoinvoice"></div>
                                            </div>
                                            <span class="sj_lamas hide">
                                                <select class="form-control sj_lama" name="sj_lama" id="sj_lama"
                                                    placeholder="Pilih No Surat Jalan">
                                                    <option value=""></option>
                                                </select>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-5 col-md-12 labelket"
                                            for="tgl_kirim">Tanggal Pengiriman</label>
                                        <div class="col-lg-4 col-md-6">
                                            <input type="date" class="form-control col-form-label" name="tgl_kirim"
                                                id="tgl_kirim">
                                            <div class="invalid-feedback" id="msgtgl_kirim"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for=""
                                            class="col-form-label col-lg-5 col-md-12 labelket">Pengiriman</label>
                                        <div class="col-lg-5 col-md-12 col-form-label">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="pengiriman"
                                                    id="pengiriman1" value="ekspedisi" />
                                                <label class="form-check-label" for="pengiriman1">Ekspedisi</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="pengiriman"
                                                    id="pengiriman2" value="nonekspedisi" />
                                                <label class="form-check-label" for="pengiriman2">Non Ekspedisi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row hide" id="ekspedisi">
                                        {{-- <div class="card col-lg-12 hide" id="ekspedisi"> --}}
                                        {{-- <div class="card-body">
                                                    <h6><b>Ekspedisi</b></h6> --}}
                                        {{-- <div class="form-group row"> --}}
                                        <label class="col-form-label col-lg-5 col-md-12 labelket"
                                            for="ekspedisi_id">Jasa Pengiriman</label>
                                        <div class="col-lg-7 col-md-12">
                                            <select class="select2 select-info form-control ekspedisi_id"
                                                name="ekspedisi_id" id="ekspedisi_id" style="width: 100%;">

                                            </select>
                                            <div class="invalid-feedback" id="msgekspedisi_id"></div>
                                            <label for="" id="ekspedisi_nama" class="col-form-label hide"></label>
                                        </div>
                                        {{-- </div> --}}
                                        {{-- </div> --}}
                                        {{-- </div> --}}
                                    </div>
                                    <div class="form-group row hide" id="nonekspedisi">
                                        {{-- <div class="card col-12 hide" id="nonekspedisi"> --}}
                                        {{-- <div class="card-body">
                                                    <h6><b>Non Ekspedisi</b></h6> --}}
                                        {{-- <div class="form-group row"> --}}
                                        <label class="col-form-label col-lg-5 col-md-12 labelket"
                                            for="nama_pengirim">Nama Pengirim</label>
                                        <div class="col-lg-7 col-md-12">
                                            <textarea type="text" class="form-control col-form-label" name="nama_pengirim" id="nama_pengirim"></textarea>
                                            <div class="invalid-feedback" id="msgnama_pengirim"></div>
                                        </div>
                                        {{-- </div> --}}
                                        {{-- </div> --}}
                                        {{-- </div> --}}
                                    </div>
                                    <div class="form-group row">
                                        <label for="dimensi" class="col-form-label col-lg-5 col-md-12 labelket">Ekspedisi Terusan</label>
                                        <div class="col-lg-7 col-md-12">
                                          <textarea type="text" class="form-control col-form-label" name="ekspedisi_terusan" id="ekspedisi_terusan"></textarea>
                                          <div class="invalid-feedback" id="msgnama_pengirim"></div>
                                        </div>
                                      </div>
                                    <div class="form-group row">
                                        <label for="" class="col-lg-5 col-md-12 col-form-label labelket">Alamat Pengiriman</label>
                                          <div class="col-lg-6 col-md-12 col-form-label">
                                            <div class="form-check form-check-inline">
                                              <input type="radio" class="form-check-input" name="pilihan_pengiriman" id="pilihan_pengiriman0" value="penjualan" />
                                              <label for="pengiriman0" class="form-check-label">Sama dengan Penjualan</label>
                                          </div>
                                          <div class="form-check form-check-inline">
                                              <input type="radio" class="form-check-input" name="pilihan_pengiriman" id="pilihan_pengiriman1" value="lainnya" />
                                              <label for="pengiriman1" class="form-check-label">Ubah Alamat</label>
                                          </div>
                                          <input type="text" name="perusahaan_pengiriman" id="perusahaan_pengiriman" class="form-control col-form-label" readonly>
                                          <input type="text"
                                              class="form-control col-form-label mt-2" name="alamat_pengiriman" id="alamat_pengiriman" readonly/>
                                          <div class="invalid-feedback"
                                              id="msg_alamat_pengiriman">
                                          </div>
                                        </div>
                                      </div>
                                      <div class="form-group row">
                                        <label for="" class="col-lg-5 col-md-12 col-form-label labelket">Kemasan</label>
                                        <div class="col-lg-6 col-md-12 col-form-label">

                                          <div class="form-check form-check-inline">
                                              <input type="radio" class="form-check-input" name="kemasan" id="kemasan0" value="peti" />
                                              <label for="kemasan0" class="form-check-label">PETI</label>
                                          </div>
                                          <div class="form-check form-check-inline">
                                              <input type="radio" class="form-check-input" name="kemasan" id="kemasan1" value="nonpeti" />
                                              <label for="kemasan1" class="form-check-label">NON PETI</label>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="dimensi" class="col-form-label col-lg-5 col-md-12 labelket">Dimensi</label>
                                        <div class="col-lg-7 col-md-12">
                                          <textarea type="text" class="form-control col-form-label" name="dimensi" id="dimensi"></textarea>
                                          <div class="invalid-feedback" id="msgnama_pengirim"></div>
                                        </div>
                                      </div>
                                    <div class="form-group row">
                                      <label class="col-form-label col-lg-5 col-md-12 labelket"
                                          for="nama_pengirim">Keterangan Pengiriman</label>
                                      <div class="col-lg-7 col-md-12">
                                          <select name="keterangan_pengiriman" id="keterangan_pengiriman" class="form-control">
                                            <option value="bayar_tujuan">BAYAR TUJUAN</option>
                                            <option value="bayar_sinko">BAYAR SINKO</option>
                                            <option value="non_bayar">NON BAYAR</option>
                                          </select>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Data Barang</h5>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-center" style="width:100%;"
                                                id="detailpesanan">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Barang</th>
                                                        <th>Jumlah</th>
                                                        <th>Array</th>
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
</form>
<script>
    function isNumberKey(event) {
        var charCode = (event.which) ? event.which : event.keyCode
        if (charCode == 13) {
            return true;
        } else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        } else {
            return true;
        }
    }
</script>
