<script>
import Table from './table.vue'
export default {
    components: {
        Table
    },
    data() {
        return {
            dataTables: [
                {
                    no_pp: 'PP-001',
                    jenis_barang: 'umum',
                    tanggal_diminta: '2021-01-01',
                    tanggal_dibutuhkan: '2021-01-01',
                    pp: 'upload',
                    status: 'draft',
                },
                {
                    no_pp: 'PP-002',
                    jenis_barang: 'part',
                    tanggal_diminta: '2021-01-01',
                    tanggal_dibutuhkan: '2021-01-01',
                    pp: 'lihat',
                    status: 'batal',
                }
            ]
        }
    },
}
</script>
<template>
    <div>
      <Table :dataTables="dataTables"/>  
    </div>
</template>