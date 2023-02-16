<script>
import Header from '../../components/header.vue'
import Table from './table'
import Pagination from '../../components/pagination.vue'

export default {
    components: {
        Header,
        Table,
        Pagination
    },
        data() {
        return {
                breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Pengembalian Barang',
                        link: '/gbmp/transfer'
                    },
                ],
                title: 'Pengembalian Barang',
                search: '',

                dataTable: [
                    {
                        no_permintaan: '123',
                        no_penyerahan: '123',
                        tujuan: 'Gudang',
                        tgl_pengembalian: '2020-01-01',
                        status: 'selesai',
                    },
                    {
                        no_permintaan: '123',
                        no_penyerahan: '123',
                        tujuan: 'Gudang',
                        tgl_pengembalian: '2020-01-01',
                        status: 'terima pengembalian',
                    }
                ],
                renderPaginate: []
        }
    },
    methods: {
        updateRender(data) {
            this.renderPaginate = data
        },
    },
    computed: {
        filteredPengembalian(){
            return this.dataTable.filter((pengembalian) => {
                return Object.keys(pengembalian).some((key) => {
                    return String(pengembalian[key]).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        },
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari" />
            </div>
        </div>
        <Table :renderPaginate="renderPaginate" />
        <Pagination :filteredDalamProses="filteredPengembalian" 
        @updateFilteredDalamProses="updateRender" />
    </div>
</template>