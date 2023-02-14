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
            formHeader: {
                tanggal_permintaan: '',
                tujuan: 'perakitan',
                no_referensi: '',
                tanggal_peminjaman: '',
                tanggal_pengembalian: '',
            },
            formTable: [
                {
                    part: {
                        value: 1,
                        label: 'P-001 - Part 1'
                    },
                    jumlah_diminta: 100,
                }
            ]
        }
    },
}
</script>
<template>
    <div class="modal fade addModalPermintaan" id="modelId" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        data-keyboard="false" data-backdrop="static" style="display: block">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Permintaan</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <Header :form="formHeader" />
                    <Table :form="formTable" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>