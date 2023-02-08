<script>
    import tableRiwayat from './table.vue'
    import Pagination from '../../../components/pagination.vue'
    export default {
        components: {
            tableRiwayat,
            Pagination,
        },
        data() {
            return {
                riwayatPO: [{
                        no_po: 'PO-001',
                        divisi: 'Divisi 1',
                        supplier: 'Supplier 1',
                        tanggal_diminta: '2020-01-01',
                        estimasi_datang: '2020-01-01',
                        tanggal_datang: '2020-01-01',
                        status: 'Barang Datang',
                    },
                    {
                        no_po: 'PO-002',
                        divisi: 'Divisi 2',
                        supplier: 'Supplier 2',
                        tanggal_diminta: '2020-01-01',
                        estimasi_datang: '2020-01-01',
                        tanggal_datang: '2020-01-01',
                        status: 'Menunggu Kedatangan',
                    }
                ],
                search: '',
                loading: false,
                renderPaginate: [],
            }
        },
        methods: {
            detail(idx) {
                this.$router.push({
                    name: 'barangmasukdalamproses',
                    params: {
                        id: idx,
                        routeBefore: 'riwayatPO'
                    }
                })
            },
                    updateFilteredDalamProses(data) {
            this.renderPaginate = data
        },
        },
        computed: {
            filteredDalamProses() {
                const dataIsNotNull = (data) => data !== null && data !== undefined && data !== "" ? data : "-";
                return this.riwayatPO.filter(item => {
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
            }
        }
    }

</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex bd-highlight mb-3">
                <div class="mr-auto p-2 bd-highlight">
                    <button type="button" class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <div class="dropdown-menu">
                        <div class="px-3 py-3">
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
                <div class="p-2 bd-highlight">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                    </div>
                </div>
            </div>

            <table-riwayat :dataTable="renderPaginate" @detail="detail" />
        </div>
        <div class="card-footer">
        <Pagination :filteredDalamProses="filteredDalamProses" 
        @updateFilteredDalamProses="updateFilteredDalamProses"
        />
        </div>
    </div>
</template>
