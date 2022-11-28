<script>
import headerBarang from './headerTerimaBarang.vue'
export default {
    components: {
        headerBarang
    },
    props: {
        barangs: {
            type: Array,
            default: () => []
        }
    },
}
</script>
<template>
    <div class="modal fade modalTerimaBarang" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-bold">Terima Barang PO</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <header-barang :header="headerBarang"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Terima</button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .card-primary {
        background-color: #dee4e9;
        color: #5b7a8f;
    }
    .card-danger {
        background-color: #f5efef;
        color: #985d61;
    }
    .card-success {
        background-color: #e0e2df;
        color: #616e61;
    }
    .card-purple {
        background-color: #ece8eb;
        color: #7f6377;
    }
</style>