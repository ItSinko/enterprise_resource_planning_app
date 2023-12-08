<script>
import pagination from '../../components/pagination.vue'
import transfer from './modalTransfer.vue'
import sisaProduk from './sisaProduk.vue'
export default {
    props: ['dataTable'],
    components: {
        pagination,
        transfer,
        sisaProduk,
    },
    data() {
        return {
            pengiriman: [],
            search: '',
            renderPaginate: [],
            tanggalMasukAwal: '',
            tanggalMasukAkhir: '',
            tanggalKeluarAwal: '',
            tanggalKeluarAkhir: '',
            produkSelected: null,
            showModalTransfer: false,
            showModalSisaProduk: false,
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        openModalTransfer(item) {
            this.produkSelected = JSON.parse(JSON.stringify(item))
            this.showModalTransfer = true
            this.$nextTick(() => {
                $('.modalTransfer').modal('show')
            })
        },
        openModalSisaProduk(item) {
            this.produkSelected = JSON.parse(JSON.stringify(item))
            this.showModalSisaProduk = true
            this.$nextTick(() => {
                $('.modalSisaProduk').modal('show')
            })
        },
        refresh() {
            this.$emit('refresh')
        },
    },
    computed: {
        filterData() {
            let filtered = this.dataTable

            if (this.tanggalMasukAwal && this.tanggalMasukAkhir) {
                const startDate = new Date(this.tanggalMasukAwal)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalMasukAkhir)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tanggal_mulai)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalMasukAwal) {
                const startDate = new Date(this.tanggalMasukAwal)
                startDate.setHours(0, 0, 0, 0)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tanggal_mulai)
                    return date >= startDate
                })
            } else if (this.tanggalMasukAkhir) {
                const endDate = new Date(this.tanggalMasukAkhir)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tanggal_mulai)
                    return date <= endDate
                })
            }

            if (this.tanggalKeluarAwal && this.tanggalKeluarAkhir) {
                const startDate = new Date(this.tanggalKeluarAwal)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalKeluarAkhir)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tanggal_selesai)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalKeluarAwal) {
                const startDate = new Date(this.tanggalKeluarAwal)
                startDate.setHours(0, 0, 0, 0)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tanggal_selesai)
                    return date >= startDate
                })
            } else if (this.tanggalKeluarAkhir) {
                const endDate = new Date(this.tanggalKeluarAkhir)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tanggal_selesai)
                    return date <= endDate
                })
            }

            return filtered.filter(item => {
                return Object.keys(item).some(key => {
                    return String(item[key]).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        }
    },
}
</script>
<template>
    <div>
        <transfer v-if="showModalTransfer" :produk="produkSelected" @closeModal="showModalTransfer = false"
            @refresh="refresh" />
        <sisa-produk v-if="showModalSisaProduk" :produk="produkSelected" @closeModal="showModalSisaProduk = false"
            @refresh="refresh" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th rowspan="2">Periode</th>
                    <th colspan="2" class="text-center">Tanggal</th>
                    <th rowspan="2">Nomor BPPB</th>
                    <th rowspan="2">Produk</th>
                    <th rowspan="2">Jumlah</th>
                    <th rowspan="2">Progress</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr class="text-center">
                    <th>
                        <span class="text-bold pr-2">Tanggal Masuk</span>
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
                                                    <input type="date" class="form-control" v-model="tanggalMasukAwal"
                                                        :max="tanggalMasukAkhir">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="jenis_penjualan">Tanggal Akhir</label>
                                                    <input type="date" class="form-control" v-model="tanggalMasukAkhir"
                                                        :min="tanggalMasukAwal">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </th>
                    <th>
                        <span class="text-bold pr-2">Tanggal Keluar</span>
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
                                                    <input type="date" class="form-control" v-model="tanggalKeluarAwal"
                                                        :max="tanggalKeluarAkhir">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="jenis_penjualan">Tanggal Akhir</label>
                                                    <input type="date" class="form-control" v-model="tanggalKeluarAkhir"
                                                        :min="tanggalKeluarAwal">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>

                    </th>
                </tr>
            </thead>
            <tbody v-if="renderPaginate.length > 0">
                <tr v-for="(data, idx) in renderPaginate" :key="idx">
                    <td>{{ data.periode }}</td>
                    <td class="text-center">{{ data.tgl_mulai }}</td>
                    <td class="text-center">{{ data.tgl_selesai }}</td>
                    <td>{{ data.no_bppb }}</td>
                    <td>{{ data.nama_produk }}</td>
                    <td>{{ data.jumlah }} Unit
                        <br><span class="badge badge-dark">
                            Terisi: {{ data.jml_all }} Unit
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-success">Terkirim: {{ data.jml_kirim }} Unit {{ data.perc_kirim ??
                            0.00 }}%</span>
                        <br><span class="badge badge-dark">Rakit: {{ data.jml_rakit }} Unit {{ data.perc_rakit ??
                            0.00 }}%</span>
                    </td>
                    <td>
                        <div v-if="data.jml_rakit != 0">
                            <button class="btn btn-outline-success btn-sm" @click="openModalTransfer(data)"><i
                                    class="far fa-edit"></i>
                                Transfer</button>
                            <button class="btn btn-outline-danger btn-sm" @click="openModalSisaProduk(data)"><i
                                    class="far fa-edit"></i> Transfer
                                Sisa Produk</button>

                        </div>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="100%" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
        <pagination :filteredDalamProses="filterData" @updateFilteredDalamProses="updateFilteredDalamProses" />
    </div>
</template>