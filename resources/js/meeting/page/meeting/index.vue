<script>
import Header from '../../components/header.vue'
import Table from './table.vue'
import pagination from '../../components/pagination.vue';
import Tambah from './aksi/tambah'
export default {
    components: {
        Header,
        Table,
        pagination,
        Tambah,
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
                    id: 1,
                    nama: 'Meeting 1',
                    tanggal: '2023-01-01',
                    mulai: '08:00',
                    selesai: '09:00',
                    // count array peserta
                    jumlah_peserta: 10,
                    peserta : null,
                    status: 'menyusun_hasil_meeting',
                    lokasi: 'Gedung A',
                },
                {
                    id: 2,
                    nama: 'Meeting 2',
                    tanggal: '2023-02-01',
                    mulai: '08:00',
                    selesai: '09:00',
                    // count array peserta
                    jumlah_peserta: 10,
                    peserta : null,
                    status: 'belum_terlaksana',
                    lokasi: 'Gedung A',
                },
            ],
            // modal tambah
            modalTambah: false,
            // selesai
            searchSelesai: '',
            renderPaginateSelesai: [],
            dataTableSelesai: [
                {
                    id: 3,
                    nama: 'Meeting 2',
                    tanggal: '2023-02-01',
                    mulai: '08:00',
                    selesai: '09:00',
                    jumlah_peserta: 10,
                    status: 'terlaksana',
                    lokasi: 'Gedung A',
                },
                {
                    id: 4,
                    nama: 'Meeting 3',
                    tanggal: '2023-02-01',
                    mulai: '08:00',
                    selesai: '09:00',
                    jumlah_peserta: 10,
                    status: 'batal',
                    lokasi: 'Gedung A',
                }
            ]
        }
    },
    methods: {
        updatePage(page){
            this.renderPaginate = page
        },
        updatePageSelesai(page){
            this.renderPaginateSelesai = page
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
        },
        paginateDataSelesai(){
            return this.dataTableSelesai.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.searchSelesai.toLowerCase())
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
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-proses-tab" data-toggle="pill" data-target="#pills-proses" type="button" role="tab" aria-controls="pills-proses" aria-selected="true">Belum Selesai</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-selesai-tab" data-toggle="pill" data-target="#pills-selesai" type="button" role="tab" aria-controls="pills-selesai" aria-selected="false">Selesai</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-proses" role="tabpanel" aria-labelledby="pills-proses-tab">
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
                    <div class="tab-pane fade" id="pills-selesai" role="tabpanel" aria-labelledby="pills-selesai-tab">
                        <div class="d-flex bd-highlight mb-3">
                            <div class="mr-auto p-2 bd-highlight">
                            </div>
                            <div class="p-2 bd-highlight">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari..." v-model="searchSelesai">
                                </div>
                            </div>
                        </div>

                        <Table :dataTable="renderPaginateSelesai" />
                        <pagination :DataTable="paginateDataSelesai" @updatePage="updatePageSelesai" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>