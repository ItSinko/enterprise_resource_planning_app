<script>
    import Header from '../../header.vue'
    import status from '../../../../../../../components/status.vue'
    import Table from './table'
    export default {
        components: {
            Header,
            status,
            Table
        },
        data() {
            return {
                formDetail: {
                    no_pengembalian: 'P-001',
                    tgl_pengembalian: '2020-01-01',
                    status: 'selesai',
                }
            }
        },
    }

</script>
<template>
    <div class="modal fade modalDetail" id="modelId" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        data-keyboard="false" data-backdrop="static" style="display: block">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pengembalian Barang</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <Header />

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-5">Detail Pengembalian</h4>
                            <div class="card-text">
                                <div class="row">
                                    <div class="col-1">
                                        <i class="fa fa-credit-card" aria-hidden="true"></i>
                                    </div>
                                    <div class="col">
                                        <p>No Pengembalian</p>
                                    </div>
                                    <div class="col">
                                        <p class="text-bold">{{ formDetail.no_pengembalian }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-1">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                    <div class="col">
                                        <p>Tanggal Pengembalian</p>
                                    </div>
                                    <div class="col">
                                        <p class="text-bold">{{ formatTanggal(formDetail.tgl_pengembalian) }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-1">
                                        <i class="fa fa-info" aria-hidden="true"></i>
                                    </div>
                                    <div class="col">
                                        <p>Status</p>
                                    </div>
                                    <div class="col">
                                        <status :status="formDetail.status" />
                                    </div>
                                </div>
                            </div>

                            <Table />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
