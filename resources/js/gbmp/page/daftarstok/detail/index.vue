<script>
import Header from './header.vue'
import detail from './main/detail.vue'
import bom from './main/bom/index.vue'
import riwayat from './main/riwayat.vue'
import stok from './main/stok.vue'

export default {
    components: {
        Header,
        detail,
        bom,
        riwayat,
        stok
    },
}
</script>
<template>
    <div class="modal fade modalDetailPart" id="modelId" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        data-keyboard="false" data-backdrop="static" style="display: block">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Part</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <Header />

                    <ul class="nav nav-pills mt-3" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="pills-detail-tab" data-toggle="pill" data-target="#pills-detail" type="button" role="tab" aria-controls="pills-detail" aria-selected="true">Detail</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-riwayat-tab" data-toggle="pill" data-target="#pills-riwayat" type="button" role="tab" aria-controls="pills-riwayat" aria-selected="false">Riwayat Part</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-stok-tab" data-toggle="pill" data-target="#pills-stok" type="button" role="tab" aria-controls="pills-stok" aria-selected="false">Stok Part</a>
  </li>
    <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-bom-tab" data-toggle="pill" data-target="#pills-bom" type="button" role="tab" aria-controls="pills-bom" aria-selected="false">BOM</a>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-detail" role="tabpanel" aria-labelledby="pills-detail-tab">
    <detail />
  </div>
  <div class="tab-pane fade" id="pills-riwayat" role="tabpanel" aria-labelledby="pills-riwayat-tab">
    <riwayat />
  </div>
  <div class="tab-pane fade" id="pills-stok" role="tabpanel" aria-labelledby="pills-stok-tab">
    <stok />
  </div>
  <div class="tab-pane fade" id="pills-bom" role="tabpanel" aria-labelledby="pills-bom-tab">
    <bom />
  </div>

</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>