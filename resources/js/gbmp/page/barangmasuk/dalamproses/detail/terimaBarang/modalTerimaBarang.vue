<script>
    import headerBarang from './headerTerimaBarang.vue'
    import Table from './tableTerimaBarang.vue'
    export default {
        components: {
            headerBarang,
            Table
        },
        props: {
            barangs: {
                type: Array,
                default: () => []
            }
        },
        data() {
            return {
                checked: false,
                            parts: [{
                'kode_part': 'PRT001',
                'nama_part': 'Part 1',
                'layout': {
                    'value': '',
                    'label': 'Layout 1'
                },
                'dipesan': 10,
                'diterima': 5,
                'jumlah': 10,
                'total': 250,
            }]  
            }
        },
        methods: {
            checklpb() {
                this.checked = !this.checked
            },
        },
    }

</script>
<template>
    <div class="modal fade modalTerimaBarang" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-bold">Terima Barang PO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <header-barang />
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-bold">Penerimaan Barang</h4>
                            <div class="card-text">
                                <div class="form-group row">
                                    <label for="" class="col-4 text-right">No LPB</label>
                                    <v-select class="col-5" v-if="checked"></v-select>
                                    <input type="text" class="form-control col-5" v-else>
                                </div>
                                <div class="form-group row">
                                    <div class="col-4"></div>
                                    <div class="form-check col-5">
                                        <input class="form-check-input" @click="checklpb" type="checkbox">
                                        <label class="form-check-label" for="gridCheck">
                                            Pakai No LPB yang tersedia
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-4 text-right">Lot Number</label>
                                    <input type="text" class="form-control col-5">
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-4 text-right">Tanggal Penerimaan</label>
                                    <input type="date" class="form-control col-5">
                                </div>
                                <Table :parts="parts" />
                            </div>
                        </div>
                    </div>
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
