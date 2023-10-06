<script>
import Header from '../../components/header.vue';
import Table from './table.vue';
import axios from 'axios';
import pagination from '../../components/pagination.vue';
export default {
    components: {
        Header,
        Table,
        pagination,
    },
    data() {
        return {
            title: 'Karyawan',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Karyawan',
                    link: '/hr/karyawan'
                },
            ],
            karyawan: [],
            search: '',
            renderPaginate: [],
            filterBagian: [],
            getStatusKaryawan: [
                'Tetap',
                'Kontrak',
                'Outsourcing',
                'Magang'
            ],
            isOpen: false,
        }
    },
    methods: {
        async getKaryawan() {
            try {
                const { data } = await axios.get('/api/karyawan_all');
                this.karyawan = data;
            } catch (error) {
                console.log(error);
            }
        },
        updatePage(page) {
            this.renderPaginate = page
        },
        tambah() {
            this.$router.push({ name: 'tambah-karyawan' })
        },
    },
    computed: {
        paginateData() {
            let filtered = []
            if (this.filterBagian.length > 0) {
                this.filterBagian.forEach((filter) => {
                    filtered = filtered.concat(this.karyawan.filter((data) => {
                        return data.divisi.nama === filter
                    }))
                })
            } else {
                filtered = this.karyawan
            }

            return filtered.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        },
        getAllBagianUnique() {
            return this.karyawan.map((karyawan) => {
                return karyawan.divisi.nama
            }).filter((value, index, self) => {
                return self.indexOf(value) === index
            })
        },
    },
    mounted() {
        this.getKaryawan();
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight mb-3">
                    <div class="mr-auto p-2 bd-highlight">
                        <span class="float-left filter">
                            <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Bagian</label>
                                            <v-select v-model="filterBagian" multiple="multiple" :close-on-select="false"
                                                :clear-on-select="false" :preserve-search="true" placeholder="Pilih Bagian"
                                                label="nama" :options="getAllBagianUnique" :taggable="true"
                                                @search="async (search) => { getAllBagianUnique }"
                                                @input="isOpen = true"
                                                ></v-select>

                                        </div>
                                        <div class="form-group">
                                            <label for="">Status Karyawan</label>
                                            <div class="form-group" v-for="statusKaryawan in getStatusKaryawan"
                                                :key="statusKaryawan">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" :ref="statusKaryawan"
                                                        :value="statusKaryawan" id="status1" />
                                                    <label class="form-check-label text-uppercase" for="status1">
                                                        {{ statusKaryawan }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Status Pegawai</label>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                        id="status1" />
                                                    <label class="form-check-label text-uppercase" for="status1">
                                                        Aktif
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="0"
                                                            id="status1" />
                                                        <label class="form-check-label text-uppercase" for="status1">
                                                            Tidak Aktif
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span> &nbsp;
                        <button class="btn btn-primary" @click="tambah">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                        </div>
                    </div>
                </div>
                <Table :dataTable="renderPaginate" />
                <pagination :DataTable="paginateData" @updatePage="updatePage" />
            </div>
        </div>
    </div>
</template>
<style>
.scrollable {
    overflow-y: auto;
    max-height: 300px;
    height: 200px;
}
</style>
