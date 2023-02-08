<script>
import Table from './table.vue';
import Pagination from '../../../components/pagination.vue';
export default {
    components: {
        Table,
        Pagination,
    },
    data() {
        return {
            dalamProses: [
                {
                    id: 1,
                    no_referensi: 'PRM/2020/0001',
                    no_permintaan: 'PRM/2020/0001',
                    tujuan: 'perakitan',
                    tgl_permintaan: '2020-01-01',
                    tgl_dibutuhkan: '2020-01-01',
                    status: 'selesai',
                    persentase: 70,
                },
                {
                    id: 2,
                    no_referensi: 'PRM/2020/0002',
                    no_permintaan: 'PRM/2020/0002',
                    tujuan: 'perbaikan',
                    tgl_permintaan: '2020-01-01',
                    tgl_dibutuhkan: '2020-01-01',
                    status: 'belum proses',
                }
            ],
            search: '',
            renderPaginate: [],
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data
        },
    },
    computed: {
        filteredDalamProses() {
            const dataIsNotNull = (data) => data !== null && data !== undefined && data !== "" ? data : "-";
            return this.dalamProses.filter((proses) => {
                return dataIsNotNull(proses.no_referensi).toString().toLowerCase().includes(this.search.toLowerCase()) ||
                    dataIsNotNull(proses.no_permintaan).toString().toLowerCase().includes(this.search.toLowerCase()) ||
                    dataIsNotNull(proses.tujuan).toString().toLowerCase().includes(this.search.toLowerCase()) ||
                    dataIsNotNull(proses.tgl_permintaan).toString().toLowerCase().includes(this.search.toLowerCase()) ||
                    dataIsNotNull(proses.tgl_dibutuhkan).toString().toLowerCase().includes(this.search.toLowerCase()) ||
                    dataIsNotNull(proses.status).toString().toLowerCase().includes(this.search.toLowerCase())
            })
        },
    }
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="search" class="form-control" placeholder="Cari" v-model="search">
            </div>
</div>
        <Table :dalamProses="renderPaginate" />
        <Pagination :filteredDalamProses="filteredDalamProses" 
        @updateFilteredDalamProses="updateFilteredDalamProses"
        />
    </div>
</template>