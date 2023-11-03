<script>
import axios from 'axios'
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
            search: '',
            headers: [
                { text: 'No Urut', value: 'no_urut' },
                { text: 'Nama Produk', value: 'nama' },
                { text: 'Jumlah Transfer', value: 'jumlah' },
                { text: 'Tanggal Penerimaan', value: 'tgl_tf' },
                { text: 'Aksi', value: 'aksi', sortable: false },
            ]
        }
    },
    props: ['dataTable'],
    methods: {
        async detail(data) {
            try {
                const { data: mapArray } = await axios.get(`/api/gbj/rw/riwayat_permintaan/${data.id}`)

                this.dataSelected = {
                    header: data,
                    data: mapArray.map(item => {
                        return {
                            ...item,
                            kelompok: this.showNamaProduk(item),
                            varian: item.varian ? item.varian : '-'
                        }
                    })
                }

                const unique = [...new Set(this.dataSelected.data.map(item => item.kelompok))]

                const grouped = unique.map(item => {
                    return {
                        kelompok: item,
                        item: this.dataSelected.data.filter(data => data.kelompok === item),
                        jumlah: this.dataSelected.data.filter(data => data.kelompok === item).length
                    }
                })

                this.dataSelected.data = grouped

                this.showModal = true
                this.$nextTick(() => {
                    $('.modalProduk').modal('show')
                })
            } catch (error) {
                console.log(error)
            }
        },
        cetakPengantar(id) {
            window.open(`/produksiReworks/surat_pengiriman/${id}`, '_blank')
        },
        cetakPermintaan(id) {
            window.open(`/produksiReworks/surat_permintaan/${id}`, '_blank')
        },
                showNamaProduk(item) {
            if (item.varian !== null && item.varian !== '' && item.varian !== '-') {
                return `${item.nama} - ${item.varian}`
            } else {
                return item.nama
            }
        }
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
                <button class="btn btn-sm btn-outline-warning" @click="cetakPengantar(item.id)">
                    <i class="fas fa-print"></i>
                    Cetak FPBJ
                </button>
                <!-- cetak sesuai urutannya -->
                <button class="btn btn-sm btn-outline-primary" @click="cetakPermintaan(item.urutan)">
                    <i class="fas fa-print"></i>
                    Cetak Permintaan
                </button>
            </template>
        </DataTable>
    </div>
</template>