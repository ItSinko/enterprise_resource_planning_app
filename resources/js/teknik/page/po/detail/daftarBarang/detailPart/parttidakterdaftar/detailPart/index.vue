<script>
export default {
    
}
</script>
<template>
    <div class="modal fade modalDetailPart" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Klasifikasi Part</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="card card-info">
                        <div class="card-body">
                            <h4 class="card-title">Informasi Part</h4>
                                                                        <div class="container mt-5 text-left">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="row">
                                                            <div class="col">Nama Produk</div>
                                                            <div class="col">
                                                                <p>PRD</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="row">
                                                            <div class="col">Nama Packing List</div>
                                                            <div class="col">
                                                                <p>Acces</p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="row">
                                                            <div class="col">Jumlah Pemesanan</div>
                                                            <div class="col">
                                                                <p>20</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
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