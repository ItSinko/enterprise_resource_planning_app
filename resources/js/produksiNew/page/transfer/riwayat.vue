<script>
import DataTable from '../../components/DataTable.vue'
import modalRiwayat from './modalRiwayat.vue'
export default {
    props: ['dataTable'],
    components: {
        DataTable,
        modalRiwayat,
    },
    data() {
        return {
            headers: [
                { text: 'Tanggal Pengiriman', value: 'tanggal', sortable: false },
                { text: 'Waktu Pengiriman', value: 'waktu' },
                { text: 'No BPPB', value: 'no_bppb' },
                { text: 'Nama Produk', value: 'produk', sortable: false },
                { text: 'Jumlah', value: 'jml' },
                { text: 'Aksi', value: 'aksi', sortable: false },
            ],
            search: '',
            tanggalAwal: '',
            tanggalAkhir: '',
            filterProduk: '',
            detailRiwayat: null,
            showModalRiwayat: false,
        }
    },
    methods: {
        openModalRiwayat(item) {
            this.detailRiwayat = item
            this.showModalRiwayat = true
            this.$nextTick(() => {
                $('.modalRiwayat').modal('show')
            })
        },
    },
    computed: {
        produkUnique() {
            const produk = this.dataTable.map(item => item.produk)
            return [...new Set(produk)]
        },
        filterData() {
            let filtered = this.dataTable
            if (this.tanggalAwal && this.tanggalAkhir) {
                const startDate = new Date(this.tanggalAwal)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhir)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter(item => {
                    const date = new Date(item.waktu_tf)
                    return date >= startDate && date <= endDate
                })
            }else if (this.tanggalAwal) {
                const startDate = new Date(this.tanggalAwal)
                startDate.setHours(0, 0, 0, 0)

                filtered = filtered.filter(item => {
                    const date = new Date(item.waktu_tf)
                    return date >= startDate
                })
            } else if (this.tanggalAkhir) {
                const endDate = new Date(this.tanggalAkhir)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter(item => {
                    const date = new Date(item.waktu_tf)
                    return date <= endDate
                })
            }

            if (this.filterProduk.length > 0) {
                filtered = filtered.filter(item => {
                    return this.filterProduk.includes(item.produk)
                })
            }

            return filtered
        }
    }
}
</script>
<template>
    <div>
        <modalRiwayat :produk="detailRiwayat" v-if="showModalRiwayat" @close="showModalRiwayat = false"></modalRiwayat>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <DataTable :headers="headers" :items="filterData" :search="search">
            <template #header.tanggal>
                <span class="text-bold pr-2">Tanggal Pengiriman</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Awal</label>
                                            <input type="date" class="form-control" v-model="tanggalAwal"
                                                :max="tanggalAkhir">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Akhir</label>
                                            <input type="date" class="form-control" v-model="tanggalAkhir"
                                                :min="tanggalAwal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>
            <template #header.produk>
                <span class="text-bold pr-2">Nama Produk</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3 font-weight-normal">
                                <v-select :options="produkUnique" v-model="filterProduk" label="produk" multiple></v-select>
                            </div>
                        </div>
                    </form>
                </span>
            </template>
            <template #item.aksi="{item}">
                <div>   
                    <button class="btn btn-outline-info btn-sm" @click="openModalRiwayat(item)">
                        <i class="fas fa-info-circle"></i>
                        Detail
                    </button>
                </div>
            </template>
        </DataTable>
    </div>
</template>