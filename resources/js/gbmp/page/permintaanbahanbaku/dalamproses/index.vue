<script>
import Table from './table.vue';
import Pagination from '../../../components/pagination.vue';
import TambahPermintaan from './detail/table/permintaan/create'
export default {
    components: {
        Table,
        Pagination,
        TambahPermintaan,
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
            modal: false,
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data
        },
        addPermintaan() {
            this.modal = true
            this.$nextTick(() => {
                $('.addModalPermintaan').modal('show')
            })
        },
        closePermintaan() {
            this.modal = false
            $('.addModalPermintaan').modal('hide')
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
        <div class="d-flex bd-highlight mb-3">
            <div class="mr-auto p-2 bd-highlight">
                <button class="btn btn-primary" @click="addPermintaan" v-if="divisi !== '11'">
                    <i class="fa fa-plus"></i>
                    Tambah Permintaan
                </button>
            </div>
            <div class="p-2 bd-highlight">
                <input type="search" class="form-control" placeholder="Cari" v-model="search">
            </div>
        </div>
        <TambahPermintaan v-if="modal" @close="closePermintaan"/>
        <Table :dalamProses="renderPaginate" />
        <Pagination :filteredDalamProses="filteredDalamProses" 
        @updateFilteredDalamProses="updateFilteredDalamProses"
        />
    </div>
</template>