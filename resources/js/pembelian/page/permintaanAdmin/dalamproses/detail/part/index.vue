<script>
import Terdaftar from './terdaftar'
import TidakTerdaftar from './tidakterdaftar'
    export default { 
        components: {
            Terdaftar,
            TidakTerdaftar
        },
        methods: {
            closeModal() {
                this.$emit('close')
            }
        },
    }

</script>
<template>
    <div class="modal fade modalDetailPart" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Permintaan Pembelian</h5>
                    <button type="button" class="close" @click="closeModal">
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
                                            <h5 class="text-bold text-capitalize">part</h5>
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

                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pills-daftarpo-tab" data-toggle="pill"
                                data-target="#pills-daftarpo" type="button" role="tab" aria-controls="pills-daftarpo"
                                aria-selected="true">Daftar Barang</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-daftarbarang-tab" data-toggle="pill"
                                data-target="#pills-daftarbarang" type="button" role="tab"
                                aria-controls="pills-daftarbarang" aria-selected="false">Daftar PO</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-daftarpo" role="tabpanel"
                            aria-labelledby="pills-daftarpo-tab">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="pills-terdaftar-tab" data-toggle="pill"
                                        data-target="#pills-terdaftar" type="button" role="tab"
                                        aria-controls="pills-terdaftar" aria-selected="true">Terdaftar</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-tidakterdaftar-tab" data-toggle="pill"
                                        data-target="#pills-tidakterdaftar" type="button" role="tab"
                                        aria-controls="pills-tidakterdaftar" aria-selected="false">Tidak Terdaftar</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-terdaftar" role="tabpanel"
                                    aria-labelledby="pills-terdaftar-tab">
                                    <terdaftar />
                                    </div>
                                <div class="tab-pane fade" id="pills-tidakterdaftar" role="tabpanel"
                                    aria-labelledby="pills-tidakterdaftar-tab">
                                    <tidak-terdaftar />
                                    </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-daftarbarang" role="tabpanel"
                            aria-labelledby="pills-daftarbarang-tab">...</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
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
