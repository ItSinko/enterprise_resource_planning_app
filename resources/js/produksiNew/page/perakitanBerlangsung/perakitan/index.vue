<script>
import modalGenerate from './modalGenerate.vue';
import modalPilihan from '../riwayat/modalPilihan.vue';
import DataTable from '../../../components/DataTable.vue';
export default {
    props: ['dataTable'],
    components: {
        modalGenerate,
        modalPilihan,
        DataTable,
    },
    data() {
        return {
            showModal: false,
            detailData: {},
            search: '',
            headers: [
                {
                    text: 'Periode',
                    value: 'periode'
                },
                {
                    text: 'Tanggal Mulai',
                    value: 'tgl_mulai'
                },
                {
                    text: 'Tanggal Selesai',
                    value: 'tgl_selesai'
                },
                {
                    text: 'No BPPB',
                    value: 'no_bppb'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama_produk'
                },
                {
                    text: 'Jumlah Rakit',
                    value: 'jumlah_unit'
                },
                {
                    text: 'Aksi Produk',
                    sortable: false,
                    value: 'aksi'
                },
            ]
        }
    },
    methods: {
        selisih(selisih, tanggal_selesai) {
            if (tanggal_selesai) {
                if (selisih > 0) {
                    return `<span class="badge badge-danger">Lebih ${selisih} hari</span>`
                } else if (selisih < 0) {
                    return `<span class="badge badge-warning">Kurang ${selisih * -1} hari</span>`
                } else {
                    return `<span class="badge badge-success">Tepat Waktu</span>`
                }
            }
        },
        detail(data) {
            this.detailData = JSON.parse(JSON.stringify(data))
            this.showModal = true
            this.$nextTick(() => {
                $('.modalGenerate').modal('show')
            })
        },
        refresh() {
            this.$emit('refresh')
        }
    },
}
</script>
<template>
    <div v-if="!$store.state.loading">
        <modalGenerate v-if="showModal" :dataGenerate="detailData" @closeModal="showModal = false" @refresh="refresh"></modalGenerate>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <DataTable :headers="headers" :items="dataTable" :search="search">
            <template #item.tgl_selesai="{ item }">
                <span>{{ item.tgl_selesai }}</span> <br>
                <span v-html="selisih(item.selisih, item.tanggal_selesai)"></span>
            </template>

            <template #item.jumlah_unit="{ item }">
                <span>{{ item.jumlah_unit }}</span><br>
                <span class="badge badge-dark">{{ item.kurang_rakit }}</span>
            </template>

            <template #item.aksi="{ item }">
                <button class="btn btn-sm btn-outline-primary" @click="detail(item)">
                    <i class="fa fa-barcode"></i>
                    Generate Nomor Seri
                </button>
            </template>
        </DataTable>
    </div>
</template>