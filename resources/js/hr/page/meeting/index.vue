<script>
import Header from '../../components/header.vue'
import Table from './table.vue'
import pagination from '../../components/pagination.vue';
import Tambah from './tambah'
export default {
    components: {
        Header,
        Table,
        pagination,
        Tambah
    },
    data() {
        return {
            title: 'Meeting',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Meeting',
                    link: '/hr/meeting'
                },
            ],
            search: '',
            renderPaginate: [],
            dataTable: [
                {
                    nama: 'Meeting 1',
                    tanggal: '2023-01-01',
                    mulai: '08:00',
                    selesai: '09:00',
                    jumlah_peserta: 10,
                    status: 'selesai',
                    lokasi: 'Gedung A',
                },
                {
                    nama: 'Meeting 2',
                    tanggal: '2023-02-01',
                    mulai: '08:00',
                    selesai: '09:00',
                    jumlah_peserta: 10,
                    status: 'belum_selesai',
                    lokasi: 'Gedung A',
                }
            ],
            // modal tambah
            modalTambah: false,
        }
    },
    methods: {
        updatePage(page){
            this.renderPaginate = page
        },
        tambahMeeting(){
            this.modalTambah = true
            this.$nextTick(() => {
                $('.modalMeetingBaru').modal('show');
            })
        },
        closeTambah(){
            this.modalTambah = false
        }
    },
    computed: {
        paginateData(){
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        }
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <Tambah v-if="modalTambah" @closeModal="closeTambah" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight mb-3">
                    <div class="mr-auto p-2 bd-highlight">
                        <button class="btn btn-primary" @click="tambahMeeting">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                        </div>
                    </div>
                </div>
                <Table :dataTable="renderPaginate" />
                <pagination :DataTable="paginateData" @updatePage="updatePage" />
            </div>
        </div>
    </div>
</template>