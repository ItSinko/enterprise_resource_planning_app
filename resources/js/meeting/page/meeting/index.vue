<script>
import Header from '../../components/header.vue'
import Tambah from './aksi/tambah'
import DataTable from '../../components/DataTable.vue';
import status from '../../components/status.vue';
import terlaksana from "./aksi/terlaksana.vue";
import Edit from "./aksi/edit";
import Batal from "./aksi/batal";
import catatan from "./aksi/catatan_peserta";
import axios from 'axios';
export default {
    components: {
        Header,
        Tambah,
        DataTable,
        status,
        terlaksana,
        Edit,
        Batal,
        catatan,
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
                    link: '/meeting/hr'
                },
            ],
            search: '',
            dataTable: [],
            headers: [
                { text: 'No', value: 'no', sortable: false },
                { text: 'Nomor Meeting', value: 'urutan' },
                { text: 'Judul Meeting', value: 'judul' },
                { text: 'Tanggal', value: 'tanggal_meet' },
                { text: 'Mulai', value: 'mulai' },
                { text: 'Selesai', value: 'selesai' },
                { text: 'Lokasi', value: 'lokasi' },
                { text: 'Status', value: 'status' },
                { text: 'Aksi', value: 'aksi', sortable: false },

            ],
            // modal tambah
            modalTambah: false,
            // selesai
            searchSelesai: '',
            dataTableSelesai: [],
            dataTerlaksana: {},
            modalTerlaksana: false,
            editData: null,
            modalEdit: false,
            modalBatal: false,
            batalData: null,
            cataanData: null,
            modalCatatan: false,
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: belum_terlaksana } = await axios.get('/api/hr/meet/jadwal')
                this.dataTable = belum_terlaksana.map((item, index) => {
                    return {
                        ...item,
                        no: index + 1,
                        tanggal_meet: this.dateFormat(item.tanggal),
                        mulai: this.timeFormat(item.mulai),
                        selesai: this.timeFormat(item.selesai),
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        },
        tambahMeeting() {
            this.modalTambah = true
            this.$nextTick(() => {
                $('.modalMeetingBaru').modal('show');
            })
        },
        closeTambah() {
            this.modalTambah = false
        },
        showTerlaksana(data) {
            this.dataTerlaksana = JSON.parse(JSON.stringify(data));
            this.modalTerlaksana = true;
            this.$nextTick(() => {
                $(".modalterlaksana").modal("show");
            });
        },
        resetTerlaksana() {
            this.dataTerlaksana = {};
            this.modalTerlaksana = false;
        },
        editMeeting(data) {
            this.editData = JSON.parse(JSON.stringify(data));
            this.modalEdit = true;
            this.$nextTick(() => {
                $(".modalMeetingEdit").modal("show");
            });
        },
        resetEdit() {
            this.editData = null;
            this.modalEdit = false;
        },
        batalMeeting(data) {
            this.batalData = data;
            this.modalBatal = true;
            this.$nextTick(() => {
                $(".modalBatal").modal("show");
            });
        },
        resetBatal() {
            this.batalData = null;
            this.modalBatal = false;
        },
        catatanPeserta(data) {
            this.catatanData = data;
            this.modalCatatan = true;
            this.$nextTick(() => {
                $(".modalCatatan").modal("show");
            });
        },
        resetCatatan() {
            this.catatanData = null;
            this.modalCatatan = false;
        },
        detail(id, status) {
            if (status == "terlaksana" || status == "menyusun_hasil_meeting") {
                this.$router.push({
                    name: "detail-meeting-terlaksana",
                    params: { id: id },
                });
            } else {
                this.$router.push({
                    name: "detail-meeting-nonterlaksana",
                    params: { id: id },
                });
            }
        },
        cetakUndangan(id) {
            window.open(`/api/hr/meet/jadwal/print/${id}`, "_blank");
        },
    },
    created() {
        this.getData()
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <Tambah v-if="modalTambah" @closeModal="closeTambah" @refresh="getData" />
        <Batal :meeting="batalData" v-if="modalBatal" @closeModal="resetBatal" />
        <Edit :meeting="editData" v-if="modalEdit" @closeModal="resetEdit" @refresh="getData" />
        <terlaksana :meeting="dataTerlaksana" v-if="modalTerlaksana" @closeModal="resetTerlaksana" />
        <catatan :meeting="catatanData" v-if="modalCatatan" @closeModal="resetCatatan" />
        <div class="card">
        
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-proses-tab" data-toggle="pill" data-target="#pills-proses"
                            type="button" role="tab" aria-controls="pills-proses" aria-selected="true">Belum Selesai</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-selesai-tab" data-toggle="pill" data-target="#pills-selesai"
                            type="button" role="tab" aria-controls="pills-selesai" aria-selected="false">Selesai</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-proses" role="tabpanel"
                        aria-labelledby="pills-proses-tab">
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

                        <DataTable :headers="headers" :items="dataTable" :search="search">
                            <template #item.status="{ item }">
                                <div>
                                    <status :status="item.status" />
                                </div>
                            </template>
                            <template #item.aksi="{ item }">
                                <div>

                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                        aria-haspopup="true" aria-expanded="true">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </div>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                        v-if="item.status == 'belum_terlaksana'">
                                        <a>
                                            <button class="dropdown-item" type="button" @click="cetakUndangan(item.id)">
                                                <i class="fas fa-print"></i>
                                                Cetak Undangan Meeting
                                            </button>
                                        </a>
                                        <a>
                                            <button class="dropdown-item" type="button" @click="showTerlaksana(item)">
                                                <i class="fas fa-check"></i>
                                                Terlaksana
                                            </button>
                                        </a>
                                        <a>
                                            <button class="dropdown-item" type="button"
                                                @click="detail(item.id, item.status)">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </button>
                                        </a>
                                        <a>
                                            <button class="dropdown-item" type="button" @click="editMeeting(item)">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>
                                        </a>
                                        <a>
                                            <button class="dropdown-item" type="button" @click="batalMeeting(item)">
                                                <i class="fas fa-times"></i>
                                                Batal
                                            </button>
                                        </a>
                                    </div>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                        v-else-if="item.status == 'menyusun_hasil_meeting'">
                                        <a>
                                            <button class="dropdown-item" @click="catatanPeserta(item)">
                                                <i class="fas fa-edit"></i>
                                                Catatan Peserta
                                            </button>
                                            <button class="dropdown-item" type="button"
                                                @click="detail(item.id, item.status)">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </button>
                                        </a>
                                    </div>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                        v-else-if="item.status == 'terlaksana'">
                                        <a>
                                            <button class="dropdown-item">
                                                <i class="fas fa-print"></i>
                                                Cetak Hasil Meeting
                                            </button>
                                            <button class="dropdown-item" type="button"
                                                @click="detail(item.id, item.status)">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </button>
                                        </a>
                                    </div>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                        v-else-if="item.status == 'batal'">
                                        <button class="dropdown-item" type="button"
                                            @click="detail(item.id, item.status)">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                    <div class="tab-pane fade" id="pills-selesai" role="tabpanel" aria-labelledby="pills-selesai-tab">
                        <div class="d-flex bd-highlight mb-3">
                            <div class="mr-auto p-2 bd-highlight">
                            </div>
                            <div class="p-2 bd-highlight">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari..."
                                        v-model="searchSelesai">
                                </div>
                            </div>
                        </div>

                        <DataTable :headers="headers" :items="dataTableSelesai" :search="searchSelesai">
                            <template #item.no="{ item, index }">
                                <div>
                                    {{ index + 1 }}
                                </div>
                            </template>
                            <template #item.no_meet="{ item, index }">
                                <div>
                                    Meet-{{ index + 1 }}
                                </div>
                            </template>
                            <template #item.status="{ item }">
                                <div>
                                    <status :status="item.status" />
                                </div>
                            </template>
                            <template #item.aksi="{ item }">
                                <div>

                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                        aria-haspopup="true" aria-expanded="true">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </div>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                        v-if="item.status == 'belum_terlaksana'">
                                        <a>
                                            <button class="dropdown-item" type="button">
                                                <i class="fas fa-print"></i>
                                                Cetak Undangan Meeting
                                            </button>
                                        </a>
                                        <a>
                                            <button class="dropdown-item" type="button" @click="showTerlaksana(item)">
                                                <i class="fas fa-check"></i>
                                                Terlaksana
                                            </button>
                                        </a>
                                        <a>
                                            <button class="dropdown-item" type="button"
                                                @click="detail(item.id, item.status)">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </button>
                                        </a>
                                        <a>
                                            <button class="dropdown-item" type="button" @click="editMeeting(item)">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>
                                        </a>
                                        <a>
                                            <button class="dropdown-item" type="button" @click="batalMeeting(item)">
                                                <i class="fas fa-times"></i>
                                                Batal
                                            </button>
                                        </a>
                                    </div>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                        v-else-if="item.status == 'menyusun_hasil_meeting'">
                                        <a>
                                            <button class="dropdown-item" @click="catatanPeserta(item)">
                                                <i class="fas fa-edit"></i>
                                                Catatan Peserta
                                            </button>
                                            <button class="dropdown-item" type="button"
                                                @click="detail(item.id, item.status)">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </button>
                                        </a>
                                    </div>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                        v-else-if="item.status == 'terlaksana'">
                                        <a>
                                            <button class="dropdown-item">
                                                <i class="fas fa-print"></i>
                                                Cetak Hasil Meeting
                                            </button>
                                            <button class="dropdown-item" type="button"
                                                @click="detail(item.id, item.status)">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </button>
                                        </a>
                                    </div>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                        v-else-if="item.status == 'batal'">
                                        <button class="dropdown-item" type="button"
                                            @click="detail(item.id, item.status)">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>