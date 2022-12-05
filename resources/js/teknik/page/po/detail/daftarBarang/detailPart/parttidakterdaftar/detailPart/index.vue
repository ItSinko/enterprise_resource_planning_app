<script>
    import Table from './table'
    export default {
        components: {
            Table
        },
        data() {
            return {
                dataTable: [{
                    nama_part: 'Part 1',
                    dipesan: 10,
                    diterima: 10,
                    keterangan: 'Keterangan',
                    sample: 10,
                    ok: 10,
                }]
            }
        },
        methods: {
            close() {
                $('.modalDetailPart').modal('hide')
            }
        },
    }

</script>
<template>
    <div class="modal fade modalDetailPart" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Klasifikasi Part</h5>
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-info">
                        <div class="card-body">
                            <h4 class="card-title text-bold">Informasi Part</h4>
                            <div class="container mt-5 text-left">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col">Nama Produk</div>
                                            <div class="col text-bold">
                                                <p>PRD</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col">Nama Packing List</div>
                                            <div class="col text-bold">
                                                <p>Acces</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col">Jumlah Pemesanan</div>
                                            <div class="col text-bold">
                                                <p>20</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Table :dataTable="dataTable" />
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .card-info {
        background-color: #eff2f4;
        color: #5b7a8f;
    }

</style>
