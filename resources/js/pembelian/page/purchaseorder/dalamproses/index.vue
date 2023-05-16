<script>
import Table from './table.vue'
import axios from 'axios'
export default {
    components: {
        Table
    },
    data() {
        return {
            searchdalamProses: '',
            filterdalamProses: [],
            dataTable: [],
            tanggal: {
                mulai: '',
                hingga: ''
            }
        }
    },
    mounted() {
        this.getData()
    },
    methods: {
        async getData() {
            const { data } = await axios.get('/api/pembelian/po').then(res => res.data)
            this.dataTable = data
        },
        clickFilterdalamProses(filter) {
            if (this.filterdalamProses.includes(filter)) {
                this.filterdalamProses = this.filterdalamProses.filter(item => item !== filter)
            } else {
                this.filterdalamProses.push(filter)
            }
        },
        refresh() {
            this.getData()
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

            // check if tanggal is empty
            const tanggal = Object.keys(this.tanggal).some(key => this.tanggal[key] != '')

            if (tanggal) {
                filtered = filtered.filter(purchaseorder => {
                    return purchaseorder.tanggal >= this.tanggal.mulai && purchaseorder.tanggal <= this.tanggal.hingga
                })

                return filtered.filter((purchaseorder) => {
                    return Object.keys(purchaseorder).some((key) => {
                        return String(purchaseorder[key]).toLowerCase().includes(this.searchdalamProses
                            .toLowerCase())
                    })
                })
            } else {
                return filtered.filter((purchaseorder) => {
                    return Object.keys(purchaseorder).some((key) => {
                        return String(purchaseorder[key]).toLowerCase().includes(this.searchdalamProses
                            .toLowerCase())
                    })
                })
            }
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
                                <div class="form-group"><label for="">Mulai Tanggal</label><input type="date" v-model="tanggal.mulai" class="form-control"></div>
                                <div class="form-group"><label for="">Hingga Tanggal</label><input type="date" v-model="tanggal.hingga" class="form-control"></div>
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
        <Table :filteredDatatables="filtereddalamProses" @refresh="refresh" />
    </div>
</template>
