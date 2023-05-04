<script>
import Header from './header.vue'
import Table from './table.vue'
export default {
    components: {
        Header,
        Table
    },
    data() {
        return {
            
        }
    },
}
</script>
<template>
    <div class="modal fade modalPeriksaBarangMasuk" id="modelId" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: block">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sampling Rakit</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <Header />

                    <Table />
                </div>
            </div>
        </div>
    </div>
</template>