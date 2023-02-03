<script>
import Terdaftar from './terdaftar.vue'
import TidakTerdaftar from './tidakterdaftar.vue'
export default {
    components: {
        Terdaftar,
        TidakTerdaftar
    },
    methods: {
        close(){
            this.$emit('close')
        }
    },
}
</script>
<template>
    <div class="modal fade modalDetail" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Produk</h5>
                        <button type="button" class="close" @click="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pills-terdaftarModal-tab" data-toggle="pill" data-target="#pills-terdaftarModal" type="button" role="tab" aria-controls="pills-terdaftarModal" aria-selected="true">Part Terdaftar</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-tidakterdaftarModal-tab" data-toggle="pill" data-target="#pills-tidakterdaftarModal" type="button" role="tab" aria-controls="pills-tidakterdaftarModal" aria-selected="false">Part Tidak Terdaftar</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-terdaftarModal" role="tabpanel" aria-labelledby="pills-terdaftarModal-tab">
                            <terdaftar />
                        </div>
                        <div class="tab-pane fade" id="pills-tidakterdaftarModal" role="tabpanel" aria-labelledby="pills-tidakterdaftarModal-tab">
                            <tidak-terdaftar />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>