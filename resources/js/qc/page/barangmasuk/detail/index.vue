<script>
import Header from './header.vue'
import HeaderMaster from '../../../components/header'

import DaftarBarang from './daftarbarang'
import Dokumen from './dokumen'
import SamplingRakit from './samplingrakit'

export default {
    components: {
        Header,
        HeaderMaster,
        DaftarBarang,
        Dokumen,
        SamplingRakit
    },
    data() {
        return {
            title: 'Barang Masuk',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Barang Masuk',
                    link: '/barangmasuk',
                },
                {
                    name: 'Detail Barang Masuk',
                    link: '/barangmasuk/detail',
                },
            ],
        }
    },
}
</script>
<template>
    <div>
        <HeaderMaster :title="title" :breadcumbs="breadcumbs" />
        <Header />

        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="pills-daftarbarang-tab" data-toggle="pill" data-target="#pills-daftarbarang" type="button" role="tab" aria-controls="pills-daftarbarang" aria-selected="true">Daftar Barang</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pills-samplingrakit-tab" data-toggle="pill" data-target="#pills-samplingrakit" type="button" role="tab" aria-controls="pills-samplingrakit" aria-selected="false">Sampling Rakit</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pills-dokumen-tab" data-toggle="pill" data-target="#pills-dokumen" type="button" role="tab" aria-controls="pills-dokumen" aria-selected="false">Dokumen</a>
        </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-daftarbarang" role="tabpanel" aria-labelledby="pills-daftarbarang-tab">
            <daftar-barang />
        </div>
        <div class="tab-pane fade" id="pills-samplingrakit" role="tabpanel" aria-labelledby="pills-samplingrakit-tab">
            <sampling-rakit />
        </div>
        <div class="tab-pane fade" id="pills-dokumen" role="tabpanel" aria-labelledby="pills-dokumen-tab">
            <dokumen />
        </div>
        </div>
    </div>
</template>