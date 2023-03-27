<script>
    export default {
        props: {
            header: {
                type: Object,
                default: () => {
                    return {}
                }
            }
        },
    }

</script>
<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="card card-danger">
                            <div class="card-body">
                                <p>No Permintaan Pembelian</p>
                                <h5 class="text-bold">{{ header.no_pp }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-success">
                            <div class="card-body">
                                <p>Jenis Barang</p>
                                <h5 class="text-bold">{{ upperCase(header.jenis) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card card-secondary">
                            <div class="card-body">
                                <p>Tanggal Diminta</p>
                                <h5 class="text-bold">{{ formatTanggal(header.tgl_diminta) }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-danger">
                            <div class="card-body">
                                <p>Tanggal Dibutuhkan</p>
                                <h5 class="text-bold">{{ formatTanggal(header.tgl_dibutuhkan) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-body">
                <div class="d-flex justify-content-start bd-highlight mb-3">
                    <div class="p-2 bd-highlight">
                        <i class="fa fa-info-circle fa-4x" aria-hidden="true"></i>
                    </div>
                    <div class="p-2 bd-highlight">
                        <p>Tujuan</p>
                        <h5 class="text-bold">{{ header.tujuan }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .card-danger {
        background-color: #eed0d0;
        color: #985d61;
    }

    .card-success {
        background-color: #c4dfb7;
        color: #e0e2df;
    }

    .card-secondary {
        background-color: #c3c3c3;
        color: #3c3c3c;
    }

    .card-primary {
        background-color: #b1c7da;
        color: #5b7a8f;
    }

</style>
