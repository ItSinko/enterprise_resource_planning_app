<script>
import Header from '../../components/header.vue'
import DalamProses from './dalamproses'
import RiwayatPenyerahan from './riwayat'
export default {
    components: {
        Header,
        DalamProses,
        RiwayatPenyerahan
    },
    data() {
        return {
                breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Penyerahan Barang',
                        link: '/gbmp/transfer'
                    },
                ],
                title: 'Penyerahan Barang',
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="pills-dalamproses-tab" data-toggle="pill" data-target="#pills-dalamproses" type="button" role="tab" aria-controls="pills-dalamproses" aria-selected="true">Dalam Proses</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-riwayat-tab" data-toggle="pill" data-target="#pills-riwayat" type="button" role="tab" aria-controls="pills-riwayat" aria-selected="false">Riwayat Penyerahan</a>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-dalamproses" role="tabpanel" aria-labelledby="pills-dalamproses-tab">
    <DalamProses />
  </div>
  <div class="tab-pane fade" id="pills-riwayat" role="tabpanel" aria-labelledby="pills-riwayat-tab">
    <RiwayatPenyerahan />
  </div>
</div>
    </div>
</template>