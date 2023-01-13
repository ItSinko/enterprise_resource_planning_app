<script>
export default {
    
}
</script>
<template>
    <div class="modal fade modalDetail" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Permintaan Pembelian</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="card card-danger">
                                        <div class="card-body">
                                            <p>No Permintaan Pembelian</p>
                                            <h5 class="text-bold">PO-0001</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                            <p>Jenis Barang</p>
                                            <h5 class="text-bold">Barang 1</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                            <p>Divisi</p>
                                            <h5 class="text-bold">Divisi 1</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="card card-secondary">
                                        <div class="card-body">
                                            <p>Tanggal Diminta</p>
                                            <h5 class="text-bold">01 Januari 2020</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card card-danger">
                                        <div class="card-body">
                                            <p>Tanggal Dibutuhkan</p>
                                            <h5 class="text-bold">01 Januari 2020</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card card-secondary">
                                        <div class="card-body">
                                            <p>Status</p>
                                            <h5 class="text-bold">Menunggu Persetujuan</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-secondary">
            <div class="card-body">
                <div class="row">
                    <div class="col-1">
                        <i class="fas fa-info-circle fa-4x"></i>
                    </div>
                    <div class="col">
                        <p>Jadwal Pengecekan</p>
                        <p class="text-bold">
                            01 Januari 2020 - 31 Desember 2020
                        </p>
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
        .card-primary {
        background-color: #eff2f4;
        color: #5b7a8f;
    }

    .card-danger {
        background-color: #f5efef;
        color: #985d61;
    }

    .card-success {
        background-color: #e7e9e7;
        color: #616e61;
    }

    .card-secondary {
        background-color: #d8d8d8;
        color: #616161;
    }
</style>