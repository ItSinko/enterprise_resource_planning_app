<script>
    export default {
        props: {
            dataTable: {
                type: Array,
                default: () => []
            },
            headers: {
                type: Object,
                default: () => {}
            },
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
                    <h5 class="modal-title text-bold">Klasifikasi Part</h5>
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-bold">Detail Packing List</h4>
                            <div class="card-text">
                                <div class="container mt-5 text-left">
                                    <div class="row">
                                        <div class="col-6">
                                                                                                                                    <div class="row">
                                                <div class="col">Nama Produk</div>
                                                <div class="col">
                                                    <p>{{ headers.namaProduk }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                                                                        <div class="row">
                                                <div class="col">Nama Packing List</div>
                                                <div class="col">
                                                    <p>{{ headers.nama_packing_list }}</p>
                                                </div>
                                            </div>
    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col">Jumlah Pemesanan</div>
                                                <div class="col">
                                                    <p>{{ headers.jumlah_pemesanan }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="close">Batal</button>
                    <button type="button" class="btn btn-warning">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Tambahkan ke Draft
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
