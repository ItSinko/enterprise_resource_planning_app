<script>
import HeaderMaster from '../../../components/header.vue'
import Header from './header.vue'
import daftarBarang from './daftarbarang'
import dokumen from './dokumen'
import hasilpengecekan from './hasilpengecekan'
import axios from 'axios'

export default {
    components: {
        HeaderMaster,
        Header,
        daftarBarang,
        dokumen,
        hasilpengecekan
    },
    data() {
        return {
            title: 'Purchase Order',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Purchase Order',
                    link: '/pembelian/po'
                },
                {
                    name: 'Detail Purchase Order',
                    link: '/pembelian/po/detail'
                }
            ],
            kurs: 'usd',
            header: {},
            daftarBarangData: [],
        }
    },
    created() {
        this.getData()
    },
    methods: {
        async getData() {
            const id = this.$route.params.id
            const { pp_header, po_header, supplier_header, data } = await axios.get(`/api/pembelian/po/${id}`).then(res => res.data.data)
            this.header = {
                pp_header,
                po_header,
                supplier_header,
            }
            this.daftarBarangData = data
        }
    },

}
</script>
<template>
    <div>
        <header-master :title="title" :breadcumbs="breadcumbs" />
        <Header :header="header"/>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-bold">Daftar Part</h4>
                <div class="card-text pt-3">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Daftar Barang</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-hasilpengecekan-tab" data-toggle="pill" data-target="#pills-hasilpengecekan" type="button" role="tab" aria-controls="pills-hasilpengecekan" aria-selected="false">Hasil Pengecekan</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Dokumen</a>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        <daftar-barang :dataTable="daftarBarangData" :header="header" :kurs="kurs"/>
  </div>
  <div class="tab-pane fade" id="pills-hasilpengecekan" role="tabpanel" aria-labelledby="pills-hasilpengecekan-tab">
        <hasilpengecekan />
  </div>
  <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
        <dokumen />
  </div>
</div>
                </div>
            </div>
        </div>
    </div>
</template>