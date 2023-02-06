<script>
import Table from './table.vue'
export default {
    components: {
        Table
    },
    data() {
        return {
            searchdalamProses: '',
            filterdalamProses: [],
            dataTable: [
                {
                    id: 1,
                    no_po: 'PO-0001',
                    divisi: 'Divisi 1',
                    supplier: 'Supplier 1',
                    tanggal_diminta: '2020-01-01',
                    tanggal_estimasi: '2020-01-01',
                    status: 'proses po',
                },
                {
                    id: 2,
                    no_po: 'PO-0002',
                    divisi: 'Divisi 2',
                    supplier: 'Supplier 2',
                    tanggal_diminta: '2020-01-01',
                    tanggal_estimasi: '2020-01-01',
                    status: 'menunggu permintaan',
                },
                {
                    id: 3,
                    no_po: 'PO-0003',
                    divisi: 'Divisi 3',
                    supplier: 'Supplier 3',
                    tanggal_diminta: '2020-01-01',
                    tanggal_estimasi: '2020-01-01',
                    status: 'batal',
                },
                {
                    id: 4,
                    no_po: 'PO-0004',
                    divisi: 'Divisi 4',
                    supplier: 'Supplier 4',
                    tanggal_diminta: '2020-01-01',
                    tanggal_estimasi: '2020-01-01',
                    status: 'selesai',
                    progress: 50,
                }
            ],
        }
    },
    methods: {
        clickFilterdalamProses(filter) {
            if (this.filterdalamProses.includes(filter)) {
                this.filterdalamProses = this.filterdalamProses.filter(item => item !== filter)
            } else {
                this.filterdalamProses.push(filter)
            }
        },
    },
    computed: {
        filtereddalamProses() {
            let filtered = []
            if (this.filterdalamProses.length == 0) {
                filtered = this.dataTable
            } else {
                this.filterdalamProses.forEach(filter => {
                    filtered = filtered.concat(this.dataTable.filter(purchaseorder => purchaseorder
                        .status == filter))
                })
            }
            return filtered.filter((purchaseorder) => {
                return purchaseorder.no_po.toLowerCase().includes(this.searchdalamProses.toLowerCase()) ||
                    purchaseorder.divisi.toLowerCase().includes(this.searchdalamProses.toLowerCase()) ||
                    purchaseorder.supplier.toLowerCase().includes(this.searchdalamProses.toLowerCase()) ||
                    purchaseorder.tanggal_diminta.toLowerCase().includes(this.searchdalamProses.toLowerCase()) ||
                    purchaseorder.tanggal_estimasi.toLowerCase().includes(this.searchdalamProses.toLowerCase()) ||
                    purchaseorder.status.toLowerCase().includes(this.searchdalamProses.toLowerCase())
            })
        },
        getAllStatusUnique() {
            return this.dataTable.map((purchaseorder) => {
                return purchaseorder.status
            }).filter((value, index, self) => {
                return self.indexOf(value) === index
            })
        },
    },
}
</script>
<template>
    <div>
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <span class="float-left filter">
                    <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="form-group">
                                    <label for="jenis_penjualan">Status</label>
                                </div>
                                <div class="form-group" v-for="status in getAllStatusUnique" :key="status">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" :ref="status" :value="status"
                                            id="status1" @click="clickFilterdalamProses(status)" />
                                        <label class="form-check-label text-uppercase" for="status1">
                                            {{ status }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_penjualan">Tanggal PO</label>
                                </div>
                                <div class="form-group"><label for="">Mulai Tanggal</label><input type="date" class="form-control"></div>
                                <div class="form-group"><label for="">Hingga Tanggal</label><input type="date" class="form-control"></div>
                            </div>
                        </div>
                    </form>
                </span>
                <router-link :to="{name : 'purchaseorderCreate'}">Tambah</router-link>
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="searchdalamProses" placeholder="Cari...">
            </div>
        </div>
        <Table :filteredDatatables="filtereddalamProses" />
    </div>
</template>
