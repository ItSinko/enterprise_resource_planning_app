<script>
    import Table from './table.vue'
    import Pagination from '../../../components/pagination.vue'
    export default {
        components: {
            Pagination,
            Table
        },
        data() {
            return {
                dataTables: [
                    {
                        no_permintaan: 'PMB-0001',
                        no_penyerahan: 'PSH-0001',
                        tujuan: 'Perakitan',
                        tanggal_permintaan: '2020-01-01',
                        tanggal_penyerahan: '2020-01-01',
                        status: 'menunggu penerimaan',
                    },
                    {
                        no_permintaan: 'PMB-0002',
                        no_penyerahan: 'PSH-0002',
                        tujuan: 'Perbaikan',
                        tanggal_permintaan: '2020-01-01',
                        tanggal_penyerahan: '2020-01-01',
                        status: 'kirim barang',
                    }
                ],
                renderPaginate: [],
                search: '',
            }
        },
        methods: {
            updatePenyerahanRender(data) {
                this.renderPaginate = data
            },
        },
        computed: {
            filteredPenyerahanBarang() {
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

        <pagination 
            :filteredDalamProses="filteredPenyerahanBarang" 
            @updateFilteredDalamProses="updatePenyerahanRender"/>
    </div>
</template>
