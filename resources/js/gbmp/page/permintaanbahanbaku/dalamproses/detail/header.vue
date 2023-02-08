<script>
import status from '../../../../components/status.vue'
import moment from 'moment'
export default {
    components: {
        status
    },
    data() {
        return {
            referensi: {
                no_referensi: 'FP-PRD/IX/22/001',
                nama_produk: 'Pasta Gigi',
                divisi: 'Produksi',
                tujuan: 'Perakitan',
                status: '-',
            },
            permintaan: {
                no_permintaan: 'FP-PRD/IX/22/001',
                tanggal_permintaan: '2021-09-22',
                tanggal_dibutuhkan: '2021-09-22',
                tanggal_pengembalian: '2021-09-22',
                status: '-',
            }
        }
    },
    methods: {
        formatDate(date) {
            return moment(date).lang('id').format('LL')
        }
    },
}
</script>
<template>
    <div>
        <div class="row row-whith-wide-cards">
            <div class="col d-flex align-items-stretch">
                <div class="card card-info">
                    <div class="card-body">
                        <h4 class="card-title text-bold mb-5">Referensi</h4>
                        <div class="card-text">
                            <div class="row">
                                <div class="col">
                                    <p>No Referensi</p>
                                </div>
                                <div class="col text-bold">
                                    <p>
                                        {{ referensi.no_referensi }}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p>
                                        Nama Produk
                                    </p>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ referensi.nama_produk }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p>Divisi</p>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ referensi.divisi }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p>Tujuan</p>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ referensi.tujuan }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <p>Status</p>
                                </div>
                                <div class="col text-bold">
                                    <status :status="referensi.status" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col d-flex align-items-stretch">
                <div class="card">
                    <div class="card-body card-danger">
                        <h4 class="card-title text-bold">Permintaan</h4>
                        <div class="card-text">
                            <div class="row">
                                <div class="col"></div>
                                <div class="col"></div>
                            </div>
                            <div class="row">
                                <div class="col"></div>
                                <div class="col"></div>
                            </div>
                            <div class="row">
                                <div class="col"></div>
                                <div class="col"></div>
                            </div>
                            <div class="row">
                                <div class="col"></div>
                                <div class="col"></div>
                            </div>
                            <div class="row">
                                <div class="col"></div>
                                <div class="col"></div>
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