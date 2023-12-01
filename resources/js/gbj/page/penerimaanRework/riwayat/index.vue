<script>
import modalProduk from './modalProduk'
import DataTable from '../../../components/DataTable.vue'
export default {
    components: {
        modalProduk,
        DataTable,
    },
    data() {
        return {
            dataSelected: {},
            showModal: false,
            headers: [
                { text: 'No Urut', value: 'urutan' },
                { text: 'Nama Produk', value: 'nama' },
                { text: 'Jumlah Transfer', value: 'jumlah' },
                { text: 'Tanggal Penerimaan', value: 'tgl_tf' },
                { text: 'Aksi', value: 'aksi', sortable: false },
            ],
            search: '',
        }
    },
    props: ['dataTable'],
    methods: {
        detail(data) {
            this.dataSelected = JSON.parse(JSON.stringify(data))

            this.dataSelected.item = this.dataSelected.item.map((item, index) => {
                item.no = index + 1;
                item.tgl_buat = this.dateFormat(item.tgl_buat);
                item.layout = item.layout ? item.layout.label : '-';
                item.packer = item.packer ? item.packer : '-';
                return item
            })

            this.showModal = true
            this.$nextTick(() => {
                $('.modalProduk').modal('show')
            })
        },
        cetak(id) {
            window.open(`/produksiReworks/surat_penyerahan/${id}/gbj`, '_blank')
        },
    },
}
</script>
<template>
    <div v-if="!$store.state.loading">
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