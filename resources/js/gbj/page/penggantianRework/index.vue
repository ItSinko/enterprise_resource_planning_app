<script>
import Header from '../../components/header.vue'
import terima from './terima'
import riwayat from './riwayat'
export default {
    components: {
        terima,
        riwayat,
        Header
    },
    data() {
        return {
            title: 'Penggantian Rework',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/gbj/dashboard'
                },
                {
                    name: 'Penggantian Rework',
                    link: '#'
                }
            ],
            terimaData: [
                {
                    no_urut: 'PRD-1',
                    nama: 'ANTROPOMETRI KIT-10',
                    jumlah: 2,
                },
                {
                    no_urut: 'PRD-2',
                    nama: 'ANTROPOMETRI KIT-10',
                    jumlah: 2,
                }
            ],
            riwayatData: [
                {
                    no_urut: 'PRD-1',
                    nama: 'ANTROPOMETRI KIT-10',
                    jumlah: 2,
                    tgl_tf: '2021-01-01 00:00:00',
                }
            ],
        }
    },
}
</script>
<template>
    <div>
        <Header :breadcumbs="breadcumbs" :title="title" />
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-penerimaan-tab" data-toggle="pill"
                            data-target="#pills-penerimaan" type="button" role="tab" aria-controls="pills-penerimaan"
                            aria-selected="true">Penerimaan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-riwayat-tab" data-toggle="pill" data-target="#pills-riwayat"
                            type="button" role="tab" aria-controls="pills-riwayat" aria-selected="false">Riwayat</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-penerimaan" role="tabpanel"
                        aria-labelledby="pills-penerimaan-tab">
                        <terima :dataTable="terimaData"></terima>
                    </div>
                    <div class="tab-pane fade" id="pills-riwayat" role="tabpanel" aria-labelledby="pills-riwayat-tab">
                        <riwayat :dataTable="riwayatData"></riwayat>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>