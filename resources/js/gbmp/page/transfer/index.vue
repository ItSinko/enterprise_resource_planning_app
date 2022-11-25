<script>
    import Header from '../../components/header.vue'
    import Table from './table.vue'
    import Loading from '../../components/loading.vue'
    import axios from 'axios'
    import mix from './mix'
    export default {
        mixins: [mix],
        components: {
            Header,
            Table,
            Loading,
        },
        data() {
            return {
                breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Transfer Antar Gudang',
                        link: '/gbmp/transfer'
                    },
                ],
                title: 'Transfer Antar Gudang',
                gudangtransfers: [{
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-02',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    },
                    {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Selesai',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Selesai',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Selesai',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Selesai',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Selesai',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Selesai',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Selesai',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Selesai',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    }, {
                        'no_transfer': 'TRF-0001',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Draft',
                        'ket': 'masuk',
                    }, {
                        'no_transfer': 'TRF-0002',
                        'divisi': 'GBMP',
                        'tanggal': '2020-01-01',
                        'keterangan': 'Transfer Barang',
                        'status': 'Menunggu Permintaan',
                        'ket': 'keluar',
                    },
                ],
                search: '',
                gudangtransfer: [],
            }
        },
        methods: {
            clickFilterdalamProses(filter) {
                if (this.gudangtransfer.includes(filter)) {
                    this.gudangtransfer = this.gudangtransfer.filter(item => item !== filter)
                } else {
                    this.gudangtransfer.push(filter)
                }
            },
        },
        computed: {
            filteredGudangTransfers() {
                let filtered = []
                if (this.gudangtransfer.length > 0) {
                    this.gudangtransfer.forEach(filter => {
                        filtered = filtered.concat(this.gudangtransfers.filter(item => item.status == filter))
                    })
                } else {
                    filtered = this.gudangtransfers
                }

                const dataIsNotNull = (data) => data !== null && data !== undefined && data !== "" ? data : "-";
                return filtered.filter((gudangtransfer) => {
                    const search = this.search.toLowerCase();
                    const no_transfer = dataIsNotNull(gudangtransfer.no_transfer).toString().toLowerCase();
                    const divisi = dataIsNotNull(gudangtransfer.divisi).toString().toLowerCase();
                    const tanggal = dataIsNotNull(gudangtransfer.tanggal).toString().toLowerCase();
                    const keterangan = dataIsNotNull(gudangtransfer.keterangan).toString().toLowerCase();
                    const status = dataIsNotNull(gudangtransfer.status).toString().toLowerCase();
                    const ket = dataIsNotNull(gudangtransfer.ket).toString().toLowerCase();
                    return no_transfer.includes(search) || divisi.includes(search) || tanggal.includes(
                        search) || keterangan.includes(search) || status.includes(search) || ket.includes(
                        search);
                })
            },

            getUniqueStatus() {
                const unique = [...new Set(this.gudangtransfers.map(item => item.status))];
                return unique;
            },
        },
    }

</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <Loading v-if="loading" />
        <div class="card" v-else>
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex">
                                    <p class="bd-highlight pr-2">Keterangan</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div class="box-ket bg-danger"></div>
                                        <p class="pr-2">Divisi Tujuan Barang Keluar</p>
                                    </div>
                                    <div class="d-flex">
                                        <div class="box-ket bg-success"></div>
                                        <p class="pr-2">Divisi Tujuan Barang Keluar</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex bd-highlight">
                    <div class="p-2 bd-highlight">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTransfer">
                            <i class="fas fa-plus"></i> Transfer Antar Gudang
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <span class="float-left filter">
                            <button type="button" class="btn btn-outline-info" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <div class="dropdown-menu">
                                <div class="px-3 py-3">
                                    <div class="form-group"><label for="">Status</label></div>
                                    <div class="form-group" v-for="status in getUniqueStatus" :key="status">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" @click="clickFilterdalamProses(status)" :ref="status" :value="status">
                                            <label for="" class="form-check-label">{{ status }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="ml-auto p-2 bd-highlight">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari Transfer Antar Gudang"
                                v-model="search">
                        </div>
                    </div>
                </div>
                <Table :transfers="renderPaginate" />
                <div class="card-footer">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <nav aria-label="...">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" :disabled="currentPage == 1" @click="previousPage">Previous</a>
                                </li>
                                <li class="page-item" :class="paginate == currentPage ? 'active' : ''"
                                    v-for="paginate in pages" :key="paginate">
                                    <a class="page-link" @click="nowPage(paginate)">{{
                  paginate
                }}</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" :disabled="currentPage == pages[pages.length - 1]"
                                        @click="nextPage">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .box-ket {
        width: 20px;
        height: 20px;
        border-radius: 5px;
        margin-right: 5px;
    }

</style>
