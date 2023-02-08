<script>
import Header from './header.vue';
import Pengembalian from './table/pengembalian.vue';
import Permintaan from './table/permintaan.vue';
import Penyerahan from './table/penyerahan.vue';
export default {
    components: {
        Header,
        Pengembalian,
        Permintaan,
        Penyerahan
    },
    methods: {
        closeModal(){
            this.$emit('close');
        },
    },
}
</script>
<template>
    <div class="modal fade modalPermintaan" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Permintaan</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <Header />

                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-permintaan-tab" data-toggle="pill" data-target="#pills-permintaan" type="button" role="tab" aria-controls="pills-permintaan" aria-selected="true">Permintaan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-penyerahan-tab" data-toggle="pill" data-target="#pills-penyerahan" type="button" role="tab" aria-controls="pills-penyerahan" aria-selected="false">Penyerahan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-pengembalian-tab" data-toggle="pill" data-target="#pills-pengembalian" type="button" role="tab" aria-controls="pills-pengembalian" aria-selected="false">Pengembalian</a>
                    </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-permintaan" role="tabpanel" aria-labelledby="pills-permintaan-tab">
                        <Permintaan />
                    </div>
                    <div class="tab-pane fade" id="pills-penyerahan" role="tabpanel" aria-labelledby="pills-penyerahan-tab">
                        <Penyerahan />
                    </div>
                    <div class="tab-pane fade" id="pills-pengembalian" role="tabpanel" aria-labelledby="pills-pengembalian-tab">
                        <Pengembalian />
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>