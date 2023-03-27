<script>
import Table from './table.vue'
export default {
    components: {
        Table
    },
    props: {
        riwayatPO: {
            type: Array,
            required: true
        },
    },
    data() {
        return {
            searchRiwayatPO: '',
            filterRiwayatPO: [],
        }
    },
    methods: {
        clickFilterRiwayatPO(filter) {
            if (this.filterRiwayatPO.includes(filter)) {
                this.filterRiwayatPO = this.filterRiwayatPO.filter(item => item !== filter)
            } else {
                this.filterRiwayatPO.push(filter)
            }
        },
    },
    computed: {
        filteredRiwayatPO() {
            let filtered = []
            if (this.filterRiwayatPO.length == 0) {
                filtered = this.riwayatPO
            } else {
                this.filterRiwayatPO.forEach(filter => {
                    filtered = filtered.concat(this.riwayatPO.filter(riwayatPO => riwayatPO
                        .status == filter))
                })
            }
            return filtered.filter((item) => {
                return Object.keys(item).some((key) => {
                    return String(item[key]).toLowerCase().includes(this.searchRiwayatPO
                        .toLowerCase())
                })
            })
        },
        getAllStatusUnique() {
            return this.riwayatPO.map((riwayatPO) => {
                return riwayatPO.status
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
                <!-- <span class="float-left filter">
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
                                            id="status1" @click="clickFilterRiwayatPO(status)" />
                                        <label class="form-check-label" for="status1">
                                            {{ status }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span> -->
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="searchRiwayatPO" placeholder="Cari...">
            </div>
        </div>     
        <Table :riwayatPO="filteredRiwayatPO" />
    </div>
</template>