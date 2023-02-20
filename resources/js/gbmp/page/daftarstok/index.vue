<script>
import Header from '../../components/header.vue'
import Pagination from '../../components/pagination.vue'
import Table from './table.vue'
export default {
    components: {
        Header,
        Table,
        Pagination,
    },
    data() {
        return {
                    breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Daftar Stok',
                        link: '/gbmp/transfer'
                    },
                ],
                title: 'Daftar Stok',
                search: '',
                dataTable: [
                    {
                        img: 'https://picsum.photos/200/300',
                        kode_part: '123456',
                        nama_part: 'Nama Part',
                        jenis: 'screw',
                        deskripsi: 'Deskripsi',
                        jumlah_ok: 123,
                        jumlah_nok: 123,
                        satuan: 'Satuan',
                    }
                ],
                renderPaginate: [],
        }
    },
    methods: {
        updateFilteredRiwayat(data) {
            this.renderPaginate = data
        },
    },
    computed: {
        filteredStok () {
            const dataIsNotNull = (data) => data !== null && data !== undefined && data !== "" ? data : "-";
            return this.dataTable.filter((stok) => {
                return Object.keys(stok).some((key) => {
                    return String(dataIsNotNull(stok[key])).toLowerCase().includes(this.search
                        .toLowerCase())
                })
            })
        }
    }
}
</script>
<template>
    <div>
        <Header :breadcumbs="breadcumbs" :title="title" />  

        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" placeholder="Cari" v-model="search">
            </div>
        </div>

        <Table :dataTable="renderPaginate" />
        <Pagination :filteredDalamProses="filteredStok" @updateFilteredDalamProses="updateFilteredRiwayat" />

    </div>
</template>