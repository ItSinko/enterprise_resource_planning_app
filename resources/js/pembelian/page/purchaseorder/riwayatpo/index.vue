<script>
import Table from './table.vue'
export default {
    components: {
        Table
    },
    data() {
        return {
            dataTable: [
                {
                    id: 1,
                    no_po: 'PO-0001',
                    divisi: 'Divisi 1',
                    supplier: 'Supplier 1',
                    tanggal_dimitra: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    ekspedisi: 'JNE',
                }
            ],
            searchRiwayat: '',
        }
    },
    computed: {
        filteredRiwayat() {
            const dataIsNotNull = (data) => data !== null && data !== undefined && data !== "" ? data : "-";
            return this.dataTable.filter((data) => {
                return dataIsNotNull(data.no_po).toString().toLowerCase().includes(this.searchRiwayat.toLowerCase()) ||
                    dataIsNotNull(data.divisi).toString().toLowerCase().includes(this.searchRiwayat.toLowerCase()) ||
                    dataIsNotNull(data.supplier).toString().toLowerCase().includes(this.searchRiwayat.toLowerCase()) ||
                    dataIsNotNull(data.tanggal_dimitra).toString().toLowerCase().includes(this.searchRiwayat.toLowerCase()) ||
                    dataIsNotNull(data.estimasi_kedatangan).toString().toLowerCase().includes(this.searchRiwayat.toLowerCase()) ||
                    dataIsNotNull(data.ekspedisi).toString().toLowerCase().includes(this.searchRiwayat.toLowerCase())
            })
        }
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
                                    <label for="jenis_penjualan">Tanggal PO</label>
                                </div>
                                <div class="form-group"><label for="">Mulai Tanggal</label><input type="date" class="form-control"></div>
                                <div class="form-group"><label for="">Hingga Tanggal</label><input type="date" class="form-control"></div>
                            </div>
                        </div>
                    </form>
                </span>
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="searchRiwayat" placeholder="Cari...">
            </div>
        </div>
        <Table :filteredDatatables="filteredRiwayat" />
    </div>
</template>