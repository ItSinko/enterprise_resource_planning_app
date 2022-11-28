<script>
export default {
    props: {
        detail: {
            type: Object,
            default: () => {}
        },
    },
}
</script>
<template>
    <div>
        <!-- Modal -->
        <div class="modal fade modalDetailTransfer" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Transfer Gudang</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="row row-whith-wide-cards">
                                    <div class="col-3 d-flex align-items-stretch">
                                        <div class="card card-danger">
                                            <div class="card-body">
                                                <p>No Transaksi</p>
                                                <p class="text-bold">PRD/FXB</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 d-flex align-items-stretch">
                                        <div class="card card-info">
                                            <div class="card-body">
                                                <p>Divisi Asal / Tujuan</p>
                                                <p class="text-bold">Produksi</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 d-flex align-items-stretch">
                                        <div class="card card-success">
                                            <div class="card-body">
                                                <p>Tanggal Transfer</p>
                                                <p class="text-bold">18 Oktober 2022</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 d-flex align-items-stretch">
                                        <div class="card card-secondary">
                                            <div class="card-body">
                                                <p>Status</p>
                                                <span class="badge badge-info">Draft</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-bold">Daftar Barang</h3>
                            </div>
                            <div class="card-body">
                                <div class="card-text">
                                    <table class="table tableDetail">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Kode & Nama Part</th>
                                                <th>Lot Number</th>
                                                <th>Jumlah Part</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>FXB-001</td>
                                                <td>LOT-001</td>
                                                <td>100</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>FXB-002</td>
                                                <td>LOT-002</td>
                                                <td>200</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>FXB-003</td>
                                                <td>LOT-003</td>
                                                <td>300</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .card-danger {
        background-color: #ebdfdf;
        color: #985d61;
    }

    .card-info {
        background-color: #dee4e9;
        color: #5b7a8f;
    }

    .card-success {
        background-color: #dbe2dc;
        color: #4b6c51;
    }

    .card-secondary {
        background-color: #d8d8d8;
        color: #4b4b4b;
    }

    .row-whith-wide-cards .card {
  width: 100%;
}
</style>