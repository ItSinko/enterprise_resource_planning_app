<script>
    import Table from './table.vue'
    export default {
        components: {
            Table
        },
        data() {
            return {
                search: '',
                dataTable: [{
                    no_po: 'PO-001',
                    divisi: 'Produksi',
                    supplier: 'PT. ABC',
                    tanggal_diminta: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    tanggal_selesai_cek: '2020-01-01',
                }]
            }
        },
        computed: {
            filterData() {
                return this.dataTable.filter((item) => {
                    return Object.keys(item).some((key) => {
                        return String(item[key]).toLowerCase().includes(this.search.toLowerCase())
                    })
                })
            }
        }
    }

</script>
<template>
    <div>
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <div class="btn-group">
                    <button class="btn btn-outline-info dropdown-toggle" type="button" id="triggerId"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>
                    <div class="dropdown-menu" aria-labelledby="triggerId">
                        <div class="px-3 py-3">
                            <div class="form-group">
                                <label for="jenis_penjualan">Tanggal PO</label>
                            </div>
                            <div class="form-group"><label for="">Mulai Tanggal</label><input type="date"
                                    class="form-control"></div>
                            <div class="form-group"><label for="">Hingga Tanggal</label><input type="date"
                                    class="form-control"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari">

            </div>
        </div>

        <Table :dataTable="filterData" />
    </div>
</template>
