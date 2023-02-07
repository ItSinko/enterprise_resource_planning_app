<script>
import Header from '../../components/header'
import DalamProses from './dalamproses'
import Peminjaman from './peminjaman'
import RiwayatPermintaan from './riwayatpermintaan'
export default {
    components: {
        DalamProses,
        Peminjaman,
        RiwayatPermintaan,
        Header
    },
    data() {
        return {
            title: 'Permintaan Bahan Baku',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Permintaan Bahan Baku',
                    link: '/gbmp/bahan-baku-request'
                },
            ],
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />

        <div class="card">
            <div class="card-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-dalamproses-tab" data-toggle="pill" data-target="#pills-dalamproses" type="button" role="tab" aria-controls="pills-dalamproses" aria-selected="true">Dalam Proses</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-riwayatpermintaan-tab" data-toggle="pill" data-target="#pills-riwayatpermintaan" type="button" role="tab" aria-controls="pills-riwayatpermintaan" aria-selected="false">Riwayat Permintaan</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-peminjaman-tab" data-toggle="pill" data-target="#pills-peminjaman" type="button" role="tab" aria-controls="pills-peminjaman" aria-selected="false">Peminjaman</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-dalamproses" role="tabpanel" aria-labelledby="pills-dalamproses-tab">
                <dalam-proses />
            </div>
            <div class="tab-pane fade" id="pills-riwayatpermintaan" role="tabpanel" aria-labelledby="pills-riwayatpermintaan-tab">
                <riwayat-permintaan />
            </div>
            <div class="tab-pane fade" id="pills-peminjaman" role="tabpanel" aria-labelledby="pills-peminjaman-tab">
                <peminjaman />
            </div>
        </div>
            </div>
        </div>
    </div>
</template>