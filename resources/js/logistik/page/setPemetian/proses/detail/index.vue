<script>
import Header from '../../../../components/header.vue';
import DataTable from '../../../../components/DataTable.vue';
import Generate from '../generate';
import DetailSeri from '../modalDetail';
import axios from 'axios';
export default {
    components: {
        Header,
        DataTable,
        Generate,
        DetailSeri,
    },
    data() {
        return {
            title: 'Detail Set Pemetian',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/logistik/dashboard'
                },
                {
                    name: 'Pemetian',
                    link: '/logistik/pengiriman/pemetian'
                },
                {
                    name: 'Detail Set Pemetian',
                    link: '#'
                }
            ],
            search: '',
            headers: [
                { text: 'No.', value: 'no', sortable: false },
                { text: 'No. Peti', value: 'no_peti', sortable: false },
                { text: 'Tanggal Dibuat', value: 'tanggal_dibuat', sortable: false },
                {
                    text: 'Tanggal Update',
                    value: 'tgl_update',
                    align: 'text-left',
                    sortable: false,
                },
                { text: 'Packer', value: 'packer', sortable: false },
                { text: 'Aksi', value: 'action', sortable: false },
            ],
            items: [],
            showModalGenerate: false,
            showModalDetail: false,
            detailSeriSelected: {},
            filterProses: [],
            tanggalAwal: '',
            tanggalAkhir: '',
            tanggalAwalUpdate: '',
            tanggalAkhirUpdate: '',
            filterPerubahan: false,
        }
    },
    methods: {
        openModalGenerate() {
            this.detailSeriSelected = {};
            this.showModalGenerate = true;
            this.$nextTick(() => {
                $('.modalGenerate').modal('show');
            });
        },
        async openModalDetail(item) {
            const { data } = await axios.get(`/api/logistik/rw/peti/detail/${item.id}`)
            this.detailSeriSelected = {
                ...item,
                seri: data.map((item, index) => {
                    return {
                        ...item,
                        produk: 'ANTROPOMETRI KIT 10',
                        no: index + 1,
                    }
                })
            }
            this.showModalDetail = true;
            this.$nextTick(() => {
                $('.modalDetailSeri').modal('show');
            });
        },
        async openEditNomorSeri(item) {
            const { data } = await axios.get(`/api/logistik/rw/peti/detail/${item.id}`)
            this.detailSeriSelected = {
                ...item,
                seri: data.map((item, index) => {
                    return {
                        ...item,
                        produk: 'ANTROPOMETRI KIT 10',
                        no: index + 1,
                    }
                })
            }
            this.showModalGenerate = true;
            this.$nextTick(() => {
                $('.modalGenerate').modal('show');
            });
        },
        async getPeti() {
            const { data } = await axios.get('/api/logistik/rw/peti/show');
            this.items = data.map((item, index) => {
                return {
                    ...item,
                    no_peti: `PETI-${item.no_urut}`,
                    tanggal_dibuat: this.dateFormat(item.tgl_buat),
                    tgl_update: item.tgl_ubah ? this.dateFormat(item.tgl_ubah) : '-',
                }
            });
        },
        cetakPackingList(id) {
            window.open(`/produksiReworks/cetakpeti/${id}`, '_blank');
        },
        viewPackingList(id) {
            window.open(`/produksiReworks/viewpeti/${id}`, '_blank');
        },
        clickFilterProses(filter) {
            if (this.filterProses.includes(filter)) {
                this.filterProses = this.filterProses.filter(item => item !== filter)
            } else {
                this.filterProses.push(filter)
            }
        },
        renderNo(data) {
            return data.map((item, index) => {
                return {
                    ...item,
                    no: index + 1
                }
            })
        },
        refresh() {
            this.filterProses = []
            this.tanggalAwal = ''
            this.tanggalAkhir = ''
            this.tanggalAwalUpdate = ''
            this.tanggalAkhirUpdate = ''
            this.search = ''
            this.getPeti()
            this.showModalGenerate = false
        }
    },
    mounted() {
        this.getPeti();
    },
    computed: {
        filterData() {
            let filtered = this.renderNo(this.items)

            if (this.filterProses.length > 0) {
                filtered = this.renderNo(filtered.filter(data => this.filterProses.includes(data.packer)))
            }

            if (this.filterPerubahan) {
                filtered = this.renderNo(filtered.filter(data => data.ket))
            }

            if (this.tanggalAwal && this.tanggalAkhir) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const dataDate = new Date(data.tgl_buat);
                    return dataDate >= startDate && dataDate <= endDate;
                }));
            } else if (this.tanggalAwal) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                filtered = this.renderNo(filtered.filter(data => {
                    const dataDate = new Date(data.tgl_buat);
                    return dataDate >= startDate;
                }));
            } else if (this.tanggalAkhir) {
                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const dataDate = new Date(data.tgl_buat);
                    return dataDate <= endDate;
                }));
            }


            if (this.tanggalAwalUpdate && this.tanggalAkhirUpdate) {
                const startDate = new Date(this.tanggalAwalUpdate);
                startDate.setHours(0, 0, 0, 0);

                const endDate = new Date(this.tanggalAkhirUpdate);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const dataDate = new Date(data.tgl_ubah);
                    return dataDate >= startDate && dataDate <= endDate;
                }));
            } else if (this.tanggalAwalUpdate) {
                const startDate = new Date(this.tanggalAwalUpdate);
                startDate.setHours(0, 0, 0, 0);

                filtered = this.renderNo(filtered.filter(data => {
                    const dataDate = new Date(data.tgl_ubah);
                    return dataDate >= startDate;
                }));
            } else if (this.tanggalAkhirUpdate) {
                const endDate = new Date(this.tanggalAkhirUpdate);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const dataDate = new Date(data.tgl_ubah);
                    return dataDate <= endDate;
                }));
            }


            return filtered.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
        getAllStatusUnique() {
            const packer = this.items.map((data) => data.packer)
            return [...new Set(packer)]
        },
    },

}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <Generate v-if="showModalGenerate" @closeModal="refresh" :selectSeri="detailSeriSelected" />
        <DetailSeri v-if="showModalDetail" :dataModalDetailSeri="detailSeriSelected"
            @closeModal="showModalDetail = false" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="openModalGenerate">
                            <i class="fas fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <DataTable :headers="headers" :items="filterData">
                    <template #header.no_peti>
                        <span class="text-bold pr-2">No. Peti</span>
                        <span class="filter">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i>
                            </a>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3">
                                        <div class="form-check form-check-inline my-3">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1"
                                                :checked="filterPerubahan" @click="filterPerubahan = !filterPerubahan">
                                            <label class="form-check-label font-weight-normal"
                                                for="inlineCheckbox1">Mengalami
                                                Perubahan</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>

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


                            <template #header.tgl_update>
                                <span class="text-bold pr-2">Tanggal Update</span>
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
                                                            <input type="date" class="form-control" v-model="tanggalAwalUpdate"
                                                                :max="tanggalAkhirUpdate">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Tanggal Akhir</label>
                                                            <input type="date" class="form-control" v-model="tanggalAkhirUpdate"
                                                                :min="tanggalAwalUpdate">
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

                    <template #item.no_peti="{ item }">
                        <div>
                            <span>{{ item.no_peti }}</span> <br>
                            <span class="badge badge-info" v-if="item.ket">Sudah diubah</span>
                        </div>
                    </template>

                    <template #item.action="{ item }">
                        <div>
                            <button class="btn btn-sm btn-outline-info" @click="openModalDetail(item)">
                                <i class="fas fa-info-circle"></i>
                                Detail No. Seri Peti
                            </button>
                            <button class="btn btn-sm btn-outline-warning" @click="openEditNomorSeri(item)">
                                <i class="fas fa-pencil"></i>
                                Edit No. Seri Peti
                            </button>
                            <br>
                            <button class="btn btn-sm btn-outline-info my-1" @click="viewPackingList(item.id)">
                                <i class="fas fa-eye"></i>
                                Lihat Packing List
                            </button>
                            <button class="btn btn-sm btn btn-outline-primary my-1" @click="cetakPackingList(item.id)">
                                <i class="fas fa-print"></i>
                                Cetak Packing List
                            </button>
                        </div>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</template>
<style>
.scrollable {
    overflow-x: scroll;
    height: 400px;
}
</style>
