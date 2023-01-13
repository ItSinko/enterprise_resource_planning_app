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
                    id: 1,
                    no_pp: 'PP/2020/01/01',
                    jenis_barang: 'Barang 1',
                    tanggal_diminta: '01 Januari 2020',
                    tanggal_dibutuhkan: '01 Januari 2020',
                    pp: 'Admin',
                    status: 'minta persetujuan'
                },
                {
                    id: 2,
                    no_pp: 'PP/2020/01/02',
                    jenis_barang: 'Barang 2',
                    tanggal_diminta: '01 Januari 2020',
                    tanggal_dibutuhkan: '01 Januari 2020',
                    pp: 'Admin',
                    status: 'menunggu diproses',
                },
                {
                    id: 3,
                    no_pp: 'PP/2020/01/03',
                    jenis_barang: 'Barang 3',
                    tanggal_diminta: '01 Januari 2020',
                    tanggal_dibutuhkan: '01 Januari 2020',
                    pp: 'Admin',
                    status: 'pembelian ditolak',
                },
                {
                    no_pp: 'PP/2020/01/04',
                    jenis_barang: 'Barang 4',
                    tanggal_diminta: '01 Januari 2020',
                    tanggal_dibutuhkan: '01 Januari 2020',
                    pp: 'Admin',
                    status: 'selesai',
                    persentase: 25
                }
            ]
        }
    },
}
</script>
<template>
    <div>
        <Table :dataTables="dalamProses"/>
    </div>
</template>