<script>
import Header from '../../components/header'
import DalamProses from './dalamproses'
import Riwayat from './riwayat'
import Jadwal from './jadwal'
    export default {
        components: {
            Header,
            DalamProses,
            Riwayat,
            Jadwal
        },
        data() {
            return {
                title: 'Barang Masuk',
                    breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Barang Masuk',
                        link: '/qcinc/barang-masuk'
                    },
                ],
            }
        },
    }
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-dalamproses-tab" data-toggle="pill" data-target="#pills-dalamproses"
                    type="button" role="tab" aria-controls="pills-dalamproses" aria-selected="true">Dalam Proses</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-riwayat-tab" data-toggle="pill" data-target="#pills-riwayat"
                    type="button" role="tab" aria-controls="pills-riwayat" aria-selected="false">Riwayat Incoming</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-jadwal-tab" data-toggle="pill" data-target="#pills-jadwal"
                    type="button" role="tab" aria-controls="pills-jadwal" aria-selected="false">Jadwal Pengecekan</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-dalamproses" role="tabpanel" aria-labelledby="pills-dalamproses-tab">
                <dalam-proses />
            </div>
            <div class="tab-pane fade" id="pills-riwayat" role="tabpanel" aria-labelledby="pills-riwayat-tab">
                <riwayat />
            </div>
            <div class="tab-pane fade" id="pills-jadwal" role="tabpanel" aria-labelledby="pills-jadwal-tab">
                <jadwal />
            </div>
        </div>
    </div>
</template>
