<script>
import Header from '../../../../components/header.vue';
import DataTable from '../../../../components/DataTable.vue';
import Tambah from './tambah.vue';
import axios from 'axios';
import modalDetail from '../../../../../produksiNew/page/prosesSet/proses/modalDetail'
import LihatSeri from '../../../../../produksiNew/page/prosesSet/proses/modalCreate/modalSeri.vue'
export default {
    components: {
        Header,
        DataTable,
        Tambah,
        modalDetail,
        LihatSeri
    },
    data() {
        return {
            title: 'Detail Set Kardus',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/logistik/dashboard'
                },
                {
                    name: 'Set Pemetian',
                    link: '/logistik/pengiriman/pemetian'
                },
                {
                    name: 'Set Pembagian Wilayah',
                    link: this.$route.params.linkNow
                },
                {
                    name: 'Set Kardus',
                    link: '#'
                }
            ],
            search: '',
            headers: [
                { text: 'No.', value: 'no', sortable: false },
                { text: 'No Seri', value: 'noseri', align: 'text-left' },
                { text: 'Tanggal Dibuat', value: 'tanggal_dibuat', align: 'text-left', sortable: false },
                { text: 'Packer', value: 'packer', align: 'text-left', sortable: false },
                { text: 'Aksi', value: 'aksi', sortable: false, align: 'text-left' },
            ],
            items: [],
            showModal: false,
            dataModalDetail: null,
            showModalDetail: false,
            dataLihatNoSeri: null,
            showModalNoSeri: false,
            tanggalAwal: '',
            tanggalAkhir: '',
            filterProses: [],
        }
    },
    methods: {
        async getKardus() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data } = await axios.get(`/api/logistik/rw/pack/details/${this.$route.params.id}`)
                this.items = data.map((item, index) => {
                    return {
                        ...item,
                        no: index + 1,
                        tanggal_dibuat: this.dateTimeFormat(item.tgl_buat),
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        },
        openModalCreate() {
            this.showModal = true
            this.$nextTick(() => {
                $('.modalGenerate').modal('show')
            })
        },
        async detailProdukSeri(data) {
            try {
                const { data: detail } = await axios.get(`/api/logistik/rw/pack/detail/${data.id}`)
                const noseri = {
                    ...data,
                    seri: detail.itemnoseri
                }
                this.dataModalDetail = noseri
                this.showModalDetail = true

                this.$nextTick(() => {
                    $('.modalDetailSeri').modal('show')
                })
            } catch (error) {
                console.log(error)
            }
        },
        lihatNoseri(noseri) {
            this.dataLihatNoSeri = noseri
            this.showModalNoSeri = true
            this.$nextTick(() => {
                $('.modalSeri').modal('show')
            })
        },
        cetakNoseri(noseri) {
            // window open with params
            window.open(`/produksiReworks/cetakseriReworkAllKardus?data=[${noseri}]`, '_blank');
        },
        lihatPackingList(id) {
            window.open(`/produksiReworks/viewpackinglist/${id}`, '_blank');
        },
        cetakPackingList(id) {
            window.open(`/produksiReworks/cetakpackinglist?data=[${id}]`, '_blank');
        },
        renderNo(data) {
            return data.map((item, index) => {
                return {
                    ...item,
                    no: index + 1,
                }
            })
        },
        clickFilterProses(filter) {
            if (this.filterProses.includes(filter)) {
                this.filterProses = this.filterProses.filter(item => item !== filter)
            } else {
                this.filterProses.push(filter)
            }
        },
    },
    created() {
        this.getKardus()
    },
    computed: {
        filterData() {
            let filtered = this.renderNo(this.items)
            if (this.filterProses.length > 0) {
                filtered = this.renderNo(filtered.filter(data => this.filterProses.includes(data.packer)))
            }

            if (this.tanggalAwal && this.tanggalAkhir) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_buat);
                    date.setHours(0, 0, 0, 0);
                    return date >= startDate && date <= endDate;
                }));
            } else if (this.tanggalAwal) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_buat);
                    date.setHours(0, 0, 0, 0);
                    return date >= startDate;
                }));
            } else if (this.tanggalAkhir) {
                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_buat);
                    date.setHours(0, 0, 0, 0);
                    return date <= endDate;
                }));
            }

            return filtered
        },
        getAllStatusUnique() {
            const packer = this.items.map((data) => data.packer)
            return [...new Set(packer)]
        },
    }
}
</script>
<template>
    <div>
        <Tambah v-if="showModal" @closeModal="showModal = false" @refresh="getKardus" />
        <modalDetail v-if="showModalDetail" @closeModal="showModalDetail = false" :dataModalDetailSeri="dataModalDetail" />
        <LihatSeri v-if="showModalNoSeri" :hasilGenerate="dataLihatNoSeri" @closeModal="showModalNoSeri = false" />
        <Header :breadcumbs="breadcumbs" :title="title" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="openModalCreate" v-if="$route.params.belum !== 0">
                            <i class="fas fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <DataTable :headers="headers" :items="filterData" :search="search" v-if="!$store.state.loading">
                    <template #header.tanggal_dibuat>
                        <span class="text-bold pr-2">Tanggal Dibuat</span>
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

                    <template #header.packer>
                        <span class="text-bold pr-2">Packer</span>
                        <span class="filter">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i>
                            </a>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3">
                                        <div :class="getAllStatusUnique.length > 5 ? 'scrollable' : ''">
                                            <div class="form-group" v-for="status in getAllStatusUnique" :key="status">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" :ref="status"
                                                        :value="status" id="status1" @click="clickFilterProses(status)" />
                                                    <label class="form-check-label text-uppercase font-weight-normal"
                                                        for="status1">
                                                        {{ status }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>

                    <template #item.aksi="{ item }">
                        <button class="btn btn-sm btn-outline-info" @click="detailProdukSeri(item)">
                            <i class="fas fa-info-circle"></i>
                            Detail No. Seri Produk
                        </button>
                        <br>
                        <button class="btn btn-sm btn-outline-info my-1" @click="lihatNoseri(item.noseri)">
                            <i class="fa fa-eye"></i> Lihat No. Seri
                        </button>
                        <button class="btn btn-sm btn btn-outline-primary my-1" @click="cetakNoseri(item.id)">
                            <i class="fas fa-print"></i>
                            Cetak No. Seri
                        </button>
                        <br>
                        <button class="btn btn-sm btn-outline-info my-1" @click="lihatPackingList(item.id)">
                            <i class="fas fa-eye"></i>
                            Lihat Packing List
                        </button>
                        <button class="btn btn-sm btn btn-outline-primary my-1" @click="cetakPackingList(item.id)">
                            <i class="fas fa-print"></i>
                            Cetak Packing List
                        </button>
                    </template>
                </DataTable>
                <div class="spinner-border" role="status" v-else>
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>