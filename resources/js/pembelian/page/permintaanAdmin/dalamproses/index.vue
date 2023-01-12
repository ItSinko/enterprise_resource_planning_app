<script>
import Table from './table.vue'
export default {
    components: {
        Table
    },
    data() {
        return {
            dalamProses: [
                {
                    no_pp: 'PP/2020/01/01',
                    jenis_barang: 'Barang 1',
                    tanggal_diminta: '01 Januari 2020',
                    tanggal_dibutuhkan: '01 Januari 2020',
                    pp: 'Admin',
                    status: 'Dalam Proses'
                }
            ]
        }
    },
}
</script>
<template>
    <div>
        <div class="d-flex bd-highlight mb-3">
            <div class="p-2 bd-highlight">
                <button class="btn btn-primary">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Tambah PP
                </button>
            </div>
            <div class="p-2 bd-highlight">
                <button class="btn btn-outline-info">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                    Filter
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                </button>
            </div>
            <div class="ml-auto p-2 bd-highlight">
                <input type="text" class="form-control" placeholder="Search...">
            </div>
        </div>
        <Table :dataTables="dalamProses"/>
    </div>
</template>