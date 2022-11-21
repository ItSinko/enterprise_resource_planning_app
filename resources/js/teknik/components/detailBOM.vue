<script>
export default {
    props: {
        detailBOMs: null
    }
}
</script>
<template>
        <div v-if="detailBOMs"
      class="modal modalDetailBOM"
      id="modelId"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modelTitleId"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-bold">Detail BOM</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <div class="card bg-card-danger">
                      <div class="card-body">
                        <div class="card-title">Nama Produk</div>
                        <div class="card-text text-bold">
                          {{ detailBOMs.header.produk }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="card bg-card-success">
                      <div class="card-body">
                        <div class="card-title">Nama BOM</div>
                        <div class="card-text text-bold">
                          {{ detailBOMs.header.nama_bom }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="card bg-card-secondary">
                      <div class="card-body">
                        <div class="card-title">Kode BOM</div>
                        <div class="card-text text-bold">
                          {{ detailBOMs.header.kode_bom }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="card bg-card-danger">
                      <div class="card-body">
                        <div class="card-title">Tahun Pembuatan</div>
                        <div class="card-text text-bold">
                          {{ detailBOMs.header.tahun_pembuatan }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="card bg-card-secondary">
                      <div class="card-body">
                        <div class="card-title">Status</div>
                        <div class="card-text">
                          <span class="badge badge-danger">{{
                            detailBOMs.header.status
                          }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card">
              <div class="card-body">
                <div class="card-title text-bold">Part Bill Of Material</div>
                <div class="card-text">
                  <table class="table tableDetailBOM">
                    <thead class="thead-light">
                      <tr>
                        <th>No</th>
                        <th>Kode Part</th>
                        <th>Nama Part</th>
                        <th>Jumlah</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(table, key) in detailBOMs.data_tabel" :key="key">
                        <td>{{ key + 1 }}</td>
                        <td>{{ table.kode }}</td>
                        <td>{{ table.nama }}</td>
                        <td>{{ table.jumlah }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>