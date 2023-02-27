<script>
import Table from './table'
export default {
    components: {
        Table
    },
}
</script>
<template>
    <div class="modal fade modalDetailBarangMasuk" id="modelId" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: block">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Barang Masuk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row bd-highlight mb-3">
                        <div class="p-2 bd-highlight">
                            <button class="btn btn-info">
                                <i class="fas fa-plus"></i>
                                Tambah
                            </button>
                        </div>
                    </div>
                    <Table />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>