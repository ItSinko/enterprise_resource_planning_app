<script>
    import Table from './table.vue'
    export default {
        components: {
            Table
        },
        props: {
            dataTable: {
                type: Array,
                default: () => []
            },
        },
    }

</script>
<template>
    <div class="modal fade modalKlasifikasi" id="modelId" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-bold">Klasifikasi Part</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="card card-primary">
                                        <div class="card-body">
                                            <h4 class="card-title">Supplier</h4>
                                            <p class="card-text text-bold">Text</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body card-success">
                                            <h4 class="card-title">Nama Produk</h4>
                                            <p class="card-text text-bold">Text</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="card card-danger">
                                        <div class="card-body">
                                            <h4 class="card-title">Jumlah Pemesanan</h4>
                                            <p class="card-text text-bold">Text</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card card-purple">
                                        <div class="card-body">
                                            <h4 class="card-title">No Purchase Order</h4>
                                            <p class="card-text text-bold">Text</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card card-secondary">
                                        <div class="card-body">
                                            <h4 class="card-title">Tanggal Kedatangan</h4>
                                            <p class="card-text text-bold">Text</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Table :dataTable="dataTable" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-warning">Terima</button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .card-primary {
        background-color: #dee4e9;
        color: #638195;
    }

    .card-danger {
        background-color: #f5efef;
        color: #a46f73;
    }

    .card-success {
        background-color: #e0e2df;
        color: #707c70;
    }

    .card-purple {
        background-color: #ece8eb;
        color: #8d7385;
    }

    .card-secondary {
        background-color: #e7e7e7;
        color: #727272;
    }

</style>
