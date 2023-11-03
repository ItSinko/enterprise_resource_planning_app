<form id="formcetaksjeditdraft" method="POST" action="/api/logistik/so/update_draft" id="form-update-draft">
    <div class="card">
        <div class="hide">
          <input type="text" name="ideditsj" id="" value="{{ $data->id }}">
        </div>
      <div class="card-body">
        <h5>Data Pengiriman</h5>
        <div class="form-horizontal">
          <div class="form-group row">
            @php
            $surat_jalan = $data->sj;
            // split string with -
            $split = explode('-', $surat_jalan);
            // get first element of array
            $first = $split[0];
            // get last element of array
            $last = $split[count($split) - 1];
            @endphp
            <label class="col-form-label col-lg-5 col-md-12 labelket" for="no_invoice">No
                Surat Jalan</label>
            <div class="col-lg-6 col-md-12">
                <div class="input-group mb-3 sj_baru" id="sj_baru" name="sj_baru">
                    <div class="input-group-prepend">
                        <select class="form-control jenis_sj" name="jenis_sj_edit" id="jenis_sj_edit">
                            <option value="SPA-" {{ $first == 'SPA' ? 'selected' : '' }}>SPA</option>
                            <option value="B." {{ $first == 'B' ? 'selected' : '' }}>B</option>
                            <option value="NBT" {{ $first == 'NBT' ? 'selected' : '' }}>NBT</option>
                        </select>
                    </div>
                    <input type="text" class="form-control col-form-label" name="no_invoice_sj_edit"
                        value="{{ $last }}"
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
            @php
                $isiData = json_decode($data->isi);
            @endphp
            <label class="col-form-label col-lg-5 col-md-12 labelket"
                for="tgl_kirim">Tanggal Pengiriman</label>
            <div class="col-lg-4 col-md-6">
                <input type="date" class="form-control col-form-label" name="tgl_kirim_edit_sj" value="{{ $isiData->tgl_sj }}"
                    id="tgl_kirim">
                <div class="invalid-feedback" id="msgtgl_kirim"></div>
            </div>
        </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <button type="button" class="btn btn-danger batalEdit">
                    Batal
                </button>
            </div>
            <div class="p-2 bd-highlight">
                <button type="button" class="btn btn-primary btnSimpanSuratJalan">
                    Simpan
                </button>
            </div>
          </div>
      </div>
    </div>
</form>