<script>
import Header from '../../components/header.vue';
import DataTable from '../../components/DataTable.vue';
import status from '../../components/status.vue';
import axios from 'axios';
export default {
    components: {
        Header,
        DataTable,
        status,
    },
    data() {
        return {
            title: "Jadwal Meeting",
            breadcumbs: [
                {
                    name: "Beranda",
                    link: "#",
                },
                {
                    name: "Meeting",
                    link: "/meeting/jadwalmeeting",
                },
            ],
            search: '',
            dataTable: [],
            searchSelesai: '',
            dataTableSelesai: [],
            headers: [
                { text: 'No', value: 'no', sortable: false },
                { text: 'Nomor Meeting', value: 'urutan' },
                { text: 'Judul Meeting', value: 'judul' },
                { text: 'Tanggal', value: 'tanggal_meet' },
                { text: 'Mulai', value: 'mulai' },
                { text: 'Selesai', value: 'selesai' },
                { text: 'Jumlah Peserta', value: 'jumlah_peserta' },
                { text: 'Status', value: 'status' },
                { text: 'Lokasi', value: 'lokasi' },
                { text: 'Aksi', value: 'aksi', sortable: false },
            ]
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
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        },
        detail(id, status) {
            if (status == "terlaksana" || status == "menyusun_hasil_meeting") {
                this.$router.push({
                    name: "jadwal-meeting-direksi-detail-terlaksana",
                    params: { id: id },
                });
            } else {
                this.$router.push({
                    name: "jadwal-meeting-direksi-detail-nonterlaksana",
                    params: { id: id },
                });
            }
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

                            </div>
                            <div class="p-2 bd-highlight">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                                </div>
                            </div>
                        </div>

                        <DataTable :headers="headers" :items="dataTable">
                            <template #item.status="{ item }">
                                <div>
                                    <status :status="item.status" />
                                </div>
                            </template>
                            <template #item.aksi="{ item }">
                                <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                    aria-haspopup="true" aria-expanded="true">
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a>
                                        <button class="dropdown-item" v-if="item.status == 'terlaksana'">
                                            <i class="fas fa-print"></i>
                                            Cetak Hasil Meeting
                                        </button>
                                        <button class="dropdown-item" @click="detail(item.id, item.status)">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </button>
                                    </a>
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
                                    <input type="text" class="form-control" placeholder="Cari..." v-model="searchSelesai">
                                </div>
                            </div>
                        </div>

                        <DataTable :headers="headers" :items="dataTableSelesai">
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
                                <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                    aria-haspopup="true" aria-expanded="true">
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a>
                                        <button class="dropdown-item" v-if="item.status == 'terlaksana'">
                                            <i class="fas fa-print"></i>
                                            Cetak Hasil Meeting
                                        </button>
                                        <button class="dropdown-item" @click="detail(item.id, item.status)">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </button>

                                    </a>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>