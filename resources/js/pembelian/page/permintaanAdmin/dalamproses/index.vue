<script>
import Table from './table.vue'
import Detail from './detail'
export default {
    components: {
        Table,
        Detail
    },
    data() {
        return {
            modal: false,
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
    methods: {
        detail(id) {
            this.modal = true
            setTimeout(() => {
                $('.modalDetail').modal('show')
            }, 100);
        }
    }
}
</script>
<template>
    <div>
        <Detail v-show="modal" @close="modal = false"/>
        <Table :dataTables="dalamProses" @detail="detail"/>
    </div>
</template>