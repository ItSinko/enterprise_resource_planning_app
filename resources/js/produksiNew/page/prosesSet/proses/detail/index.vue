<script>
import Header from '../../../../components/header.vue';
import ModalCreate from '../modalCreate/';
import axios from 'axios';
import DataTable from '../../../../components/DataTable.vue';
import modalDetail from '../modalDetail'
import LihatSeri from '../modalCreate/modalSeri.vue'
export default {
    components: {
        Header,
        ModalCreate,
        DataTable,
        modalDetail,
        LihatSeri,
    },
    data() {
        return {
            title: 'Detail Produk',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Set Produk Reworks',
                    link: '/produksi/prosesSetReworks'
                },
                {
                    name: 'Detail Produk',
                    link: '#'
                },
            ],
            search: '',
            dataTable: [],
            renderPaginate: [],
            showModal: false,
            selectSeri: {},
            showTambah: false,

            headers: [
                {
                    text: 'id',
                    value: 'id',
                    sortable: false,
                },
                {
                    text: 'No.',
                    value: 'no',
                    sortable: false,
                },
                {
                    text: 'No. Seri',
                    value: 'noseri',
                    align: 'text-left'
                },
                {
                    text: 'Tanggal Dibuat',
                    value: 'tgl_buat',
                    align: 'text-left'
                },
                {
                    text: 'Packer',
                    value: 'packer',
                    align: 'text-left'
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false,
                    align: 'text-left'
                }
            ],
            noSeriSelected: [],
            checkAll: false,
            detailSeri: false,
            dataModalDetail: null,
            showModalDetail: false,
            dataLihatNoSeri: null,
            showModalNoSeri: false,
            filterProses: []
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        showModalCreate() {
            this.showModal = true;
            this.selectSeri = {}
            this.$nextTick(() => {
                $('.modalSet').modal('show');
            });
        },
        closeModalCreate() {
            $('.modalSet').modal('hide');

            this.$nextTick(() => {
                this.showModal = false;

            });
        },
        editNoseriProduk(data) {
            this.selectSeri = data
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalSet').modal('show');
            });
        },
        async getData() {
            try {
                this.$store.dispatch('setLoading', true);
                const id = this.$route.params.id;
                const { data } = await axios.get(`/api/prd/rw/proses/produk/${id}`);
                const { produk_reworks_id, set, urutan, item, belum } = data
                this.dataTable = item.map((data, index) => {
                    return {
                        ...data,
                        no: index + 1,
                        tgl_buat: this.dateFormat(data.tgl_buat),
                    }
                })
                this.showTambah = belum == 0 ? true : false
                let header = {
                    produk_reworks_id,
                    set,
                    urutan,
                }
                this.$store.dispatch('setSeri', header);
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch('setLoading', false);
            }
        },
        checkAllSeri() {
            this.checkAll = !this.checkAll;
            if (this.checkAll) {
                this.noSeriSelected = this.filterData.map((item) => item.id);
            } else {
                this.noSeriSelected = [];
            }
        },
        selectNoSeri(noseri) {
            if (this.noSeriSelected.find((data) => data === noseri)) {
                this.noSeriSelected = this.noSeriSelected.filter((data) => data !== noseri)
                this.checkAll = false
            } else {
                this.noSeriSelected.push(noseri)
            }

            if (this.noSeriSelected.length === this.dataTable.length) {
                this.checkAll = true
            }
        },
        detailNoseriProduk(id) {
            this.detailSeri = true;
            this.$nextTick(() => {
                $('.modalDetailSeri').modal('show');
            });
        },
        detailProdukSeri(data) {
            this.dataModalDetail = JSON.parse(JSON.stringify(data))
            this.showModalDetail = true

            this.$nextTick(() => {
                $('.modalDetailSeri').modal('show')
            })
        },
        lihatNoseri(noseri) {
            this.dataLihatNoSeri = noseri
            this.showModalNoSeri = true
            this.$nextTick(() => {
                $('.modalSeri').modal('show')
            })
        },
        hapusNoseriProduk(id) {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Anda akan menghapus data no. seri produk ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/prd/rw/gen/${id}`).then(() => {
                        this.$swal({
                            title: 'Berhasil!',
                            text: 'Data berhasil dihapus',
                            icon: 'success',
                        })
                        this.getData()
                    }).catch((err) => {
                        this.$swal({
                            title: 'Gagal!',
                            text: 'Data gagal dihapus',
                            icon: 'error',
                        })
                    })
                }
            })
        },
        lihatPackingList(id) {
            window.open(`/produksiReworks/viewpackinglist/${id}`, '_blank');
        },
        cetakAllPackingList() {
            window.open(`/produksiReworks/cetakpackinglist?data=[${this.noSeriSelected}]`, '_blank');
        },
        cetakPackingList(id) {
            window.open(`/produksiReworks/cetakpackinglist?data=[${id}]`, '_blank');
        },
        cetakAllNoseri() {
            window.open(`/produksiReworks/cetakseriReworkAll?data=[${this.noSeriSelected}]`, '_blank');
        },
        cetakNoseri(noseri) {
            // window open with params
            window.open(`/produksiReworks/cetakseriReworkAll?data=[${noseri}]`, '_blank');
        },
        clickFilterProses(filter) {
            if (this.filterProses.includes(filter)) {
                this.filterProses = this.filterProses.filter(item => item !== filter)
            } else {
                this.filterProses.push(filter)
            }
        },
    },
    mounted() {
        this.getData();
    },
    computed: {
        filterData() {
            let filtered = []

            if (this.filterProses.length > 0) {
                this.filterProses.forEach((filter) => {
                    filtered = filtered.concat(this.dataTable.filter((data) => data.packer == filter))
                })
            } else {
                filtered = this.dataTable
            }

            return filtered.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
        getAllStatusUnique() {
            const packer = this.dataTable.map((data) => data.packer)
            return [...new Set(packer)]
        },
    },
    watch: {
        search() {
            if (this.noSeriSelected.length == this.dataTable.length) {
                this.checkAll = true
            } else {
                this.checkAll = false
            }
        }
    }
}
</script>
<template>
    <div v-if="!$store.state.loading">
        <ModalCreate v-if="showModal" @closeModal="closeModalCreate" :selectSeri="selectSeri" @refresh="getData" />
        <LihatSeri v-if="showModalNoSeri" :hasilGenerate="dataLihatNoSeri" @closeModal="showModalNoSeri = false" />
        <modalDetail v-if="showModalDetail" @closeModal="showModalDetail = false" :dataModalDetailSeri="dataModalDetail" />
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="showModalCreate" v-if="!showTambah">
                            Tambah <i class="fa fa-plus"></i>
                        </button>
                        <button class="btn btn-outline-primary ml-2" v-if="noSeriSelected.length > 0"
                            @click="cetakAllNoseri">
                            <i class="fa fa-print"></i> Cetak No. Seri
                        </button>
                        <button class="btn btn-outline-secondary ml-2" v-if="noSeriSelected.length > 0"
                            @click="cetakAllPackingList">
                            <i class="fa fa-print"></i> Cetak Packing List
                        </button>
                        <span class="filter ml-2">
                            <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Keterangan</label>
                                        </div>
                                        <div class="form-group" v-for="status in getAllStatusUnique" :key="status">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" :ref="status"
                                                    :value="status" id="status1" @click="clickFilterProses(status)" />
                                                <label class="form-check-label text-uppercase" for="status1">
                                                    {{ status }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>

                    </div>
                    <div class="p-2 bd-highlight"> <input type="text" v-model="search" class="form-control"
                            placeholder="Cari...">
                    </div>
                </div>
                <DataTable :headers="headers" :items="filterData">
                    <template #header.id>
                        <div>
                            <input type="checkbox" :checked="checkAll" @click="checkAllSeri">
                        </div>
                    </template>
                    <template #item.id="{ item }">
                        <div>
                            <input type="checkbox"
                                :checked="noSeriSelected && noSeriSelected.find((noseri) => noseri === item.id)"
                                @click="selectNoSeri(item.id)">
                        </div>
                    </template>
                    <template #item.aksi="{ item }">
                        <div>
                            <button class="btn btn-sm btn-outline-info" @click="detailProdukSeri(item)">
                                <i class="fa fa-info-circle"></i> Detail No. Seri Produk
                            </button>
                            <button class="btn btn-sm btn-outline-warning" @click="editNoseriProduk(item)"
                                v-if="item.status != 'Transfer'">
                                <i class="fa fa-pencil"></i> Edit No. Seri Produk
                            </button>
                            <button class="btn btn-sm btn-outline-danger" @click="hapusNoseriProduk(item.id)"
                                v-if="item.status != 'Transfer'">
                                <i class="fa fa-trash"></i> Hapus No. Seri Produk
                            </button>
                            <br>
                            <button class="btn btn-sm btn-outline-info my-1" @click="lihatNoseri(item.noseri)">
                                <i class="fa fa-eye"></i> Lihat No. Seri
                            </button>
                            <button class="btn btn-sm btn-outline-primary my-1" @click="cetakNoseri(item.id)">
                                <i class="fa fa-print"></i> Cetak No. Seri
                            </button> <br>
                            <button class="btn btn-sm btn-outline-info" @click="lihatPackingList(item.id)">
                                <i class="fa fa-eye"></i> Lihat Packing List
                            </button>
                            <button class="btn btn-sm btn-outline-primary" @click="cetakPackingList(item.id)">
                                <i class="fa fa-print"></i> Cetak Packing List
                            </button>
                        </div>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</template>