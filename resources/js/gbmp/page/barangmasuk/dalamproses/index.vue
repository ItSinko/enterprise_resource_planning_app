<script>
    import Table from './table.vue'
    import Tambah from './tambah.vue'
    import Detail from './detail/index.vue'
    import Loading from '../../../components/loading.vue'
    import mix from './mix'
    export default {
        mixins: [mix],
        components: {
            Table,
            Tambah,
            Loading,
            Detail,
        },
        data() {
            return {
                dalamProses: [{
                        no_po: 'PO-001',
                        divisi: 'Divisi 1',
                        supplier: 'Supplier 1',
                        tanggal_diminta: '2020-01-01',
                        estimasi_datang: '2020-01-01',
                        status: 'Barang Datang',
                    },
                    {
                        no_po: 'PO-002',
                        divisi: 'Divisi 2',
                        supplier: 'Supplier 2',
                        tanggal_diminta: '2020-01-01',
                        estimasi_datang: '2020-01-01',
                        status: 'Menunggu Kedatangan',
                    }
                ],
                proses_selected: [],
                search: '',
                loading: false,
                filterProses: [],
            }
        },
        methods: {
            clickFilterdalamProses(filter) {
                if (this.filterProses.includes(filter)) {
                    this.filterProses = this.filterProses.filter(item => item !== filter)
                } else {
                    this.filterProses.push(filter)
                }
            },
            checked(idx) {
                console.log(idx)
                this.dalamProses.find((proses, index) => {
                    if (index === idx) {
                        if (this.proses_selected.find(item => item.no_po === proses.no_po)) {
                            this.proses_selected.splice(this.proses_selected.indexOf(proses), 1)
                        } else {
                            this.proses_selected.push({
                                ...proses,
                                jadwal_mulai: null,
                                jadwal_selesai: null,
                            })
                        }
                    }
                })
            },
            tambah() {
                $('.modalAddProses').modal('show')
            },
            detail(idx){
                this.$router.push({ name: 'barangmasukdalamproses', params: { id: idx } })
            },
            deleteProses(idx, po) {
                this.loading = true
                this.proses_selected.splice(idx, 1)
                this.$refs.table.$refs.proses.find((proses, index) => {
                    if (proses._value == po) {
                        proses.checked = false
                    }
                })
                this.loading = false
            },
        },
        computed: {
            getUniqueStatus() {
                return [...new Set(this.dalamProses.map(item => item.status))]
            },
            filteredDalamProses() {
                let filtered = []
                if (this.filterProses.length > 0) {
                    this.filterProses.forEach(filter => {
                        filtered = filtered.concat(this.dalamProses.filter(item => item.status == filter))
                    })
                } else {
                    filtered = this.dalamProses
                }

                const dataIsNotNull = (data) => data !== null && data !== undefined && data !== "" ? data : "-";
                return filtered.filter(item => {
                    const search = this.search.toLowerCase()
                    const no_po = dataIsNotNull(item.no_po).toString().toLowerCase()
                    const divisi = dataIsNotNull(item.divisi).toString().toLowerCase()
                    const supplier = dataIsNotNull(item.supplier).toString().toLowerCase()
                    const tanggal_diminta = dataIsNotNull(item.tanggal_diminta).toString().toLowerCase()
                    const estimasi_datang = dataIsNotNull(item.estimasi_datang).toString().toLowerCase()
                    const status = dataIsNotNull(item.status).toString().toLowerCase()
                    return no_po.includes(search) || divisi.includes(search) || supplier.includes(search) ||
                        tanggal_diminta.includes(search) || estimasi_datang.includes(search) || status.includes(
                            search)
                })
            },
        }
    }

</script>
<template>
    <div>
        <Loading v-if="loading" />
        <div class="card" v-else>
            <div class="card-body">
                <div class="card-text">
                    <div class="d-flex bd-highlight mb-3">
                        <div class="p-2 bd-highlight">
                            <button class="btn btn-primary" @click="tambah">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Tambah Jadwal Penerimaan
                            </button>
                        </div>
                        <div class="p-2 bd-highlight">
                            <button type="button" class="btn btn-outline-info" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <div class="dropdown-menu">
                                <div class="px-3 py-3">
                                    <div class="form-group"><label for="">Status</label></div>
                                    <div class="form-group" v-for="status in getUniqueStatus" :key="status">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                @click="clickFilterdalamProses(status)" :ref="status" :value="status">
                                            <label for="" class="form-check-label">{{ status }}</label>
                                        </div>
                                    </div>
                                    <!-- Ngambil dari Tanggal PO -->
                                    <div class="form-group"><label for="">Tanggal PO</label></div>
                                    <div class="form-group">
                                        <label for="">Mulai Tanggal</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="">Hingga Tanggal</label>
                                        <input type="date" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ml-auto p-2 bd-highlight">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari..."
                                    v-model="search">
                            </div>
                        </div>
                    </div>
                    <Table :dalamProses="renderPaginate" @checked="checked" @detail="detail" ref="table"></Table>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex flex-row-reverse bd-highlight">
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" :disabled="currentPage == 1" @click="previousPage">Previous</a>
                            </li>
                            <li class="page-item" :class="paginate == currentPage ? 'active' : ''"
                                v-for="paginate in pages" :key="paginate">
                                <a class="page-link" @click="nowPage(paginate)">{{paginate}}</a>
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
        <Tambah :addProses="proses_selected" @deleteProses="deleteProses" />
    </div>
</template>
