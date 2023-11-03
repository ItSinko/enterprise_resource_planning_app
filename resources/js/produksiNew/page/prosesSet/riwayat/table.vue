<script>
import DataTable from '../../../components/DataTable.vue'
import modalProduk from './modalProduk'
export default {
    components: {
        modalProduk,
        DataTable,
    },
    data() {
        return {
            dataSelected: {},
            showModal: false,
            search: '',
            headers: [
                { text: 'No Urut', value: 'no_urut' },
                { text: 'Nama Produk', value: 'nama' },
                { text: 'Jumlah Transfer', value: 'jumlah' },
                { text: 'Tanggal Transfer', value: 'tgl_tf' },
                { text: 'Aksi', value: 'aksi', sortable: false },
            ],
        }
    },
    props: ['dataTable'],
    methods: {
        detail(data) {
            this.dataSelected = JSON.parse(JSON.stringify(data))
            this.showModal = true
            this.$nextTick(() => {
                $('.modalProduk').modal('show')
            })
        },
        cetak(id) {
            window.open(`/produksiReworks/surat_penyerahan/${id}/produksi`, '_blank')
        },
    },
}
</script>
<template>
    <div>
        <modalProduk v-if="showModal" @closeModal="showModal = false" :dataSelected="dataSelected" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <DataTable :headers="headers" :items="dataTable" :search="search">
            <template #item.aksi="{ item }">
                <button class="btn btn-sm btn-outline-info" @click="detail(item)">
                    <i class="fas fa-info-circle"></i>
                    Detail
                </button>
                <button class="btn btn-sm btn-outline-primary" @click="cetak(item.id)">
                    <i class="fas fa-print"></i>
                    Cetak BPBJ
                </button>
            </template>
        </DataTable>
    </div>
</template>