<script>
    import Table from './table.vue'
import Pagination from '../../../components/pagination.vue'
    export default {
        components: {
            Table,
            Pagination,
        },
        data() {
            return {
                search: '',
                dataTables: [
                    {
                        no_permintaan: 'PMB-0001',
                        no_penyerahan: 'PSH-0001',
                        tujuan: 'Perakitan',
                        tanggal_permintaan: '2020-01-01',
                        tanggal_penyerahan: '2020-01-01',
                    }
                ],
                renderPaginate: [],
            }
        },
        methods: {
            updatePenyerahanRender(data) {
                this.renderPaginate = data
            },
        },
        computed: {
            filteredPenyerahanRiwayatBarang() {
                const dataIsNotNull = (data) => data !== null && data !== undefined && data !== "" ? data : "-";
                return this.dataTables.filter((penyerahanBarang) => {
                    return Object.keys(penyerahanBarang).some((key) => {
                        return String(dataIsNotNull(penyerahanBarang[key])).toLowerCase().includes(this.search
                            .toLowerCase())
                    })
                })
            },
        }
    }

</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input v-model="search" type="text" class="form-control" placeholder="Cari" />
            </div>
        </div>

        <Table :renderPaginate="renderPaginate" />

        <Pagination :filteredDalamProses="filteredPenyerahanRiwayatBarang" @updateFilteredDalamProses="updatePenyerahanRender" />
    </div>
</template>
