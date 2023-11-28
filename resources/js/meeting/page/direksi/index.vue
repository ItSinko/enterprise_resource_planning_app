<script>
import Header from '../../components/header.vue';
import DataTable from '../../components/DataTable.vue';
import status from '../../components/status.vue';
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
            dataTable: [
                {
                    id: 1,
                    nama: 'Meeting 1',
                    tanggal: '2023-01-01',
                    tgl_meet: '02 Januari 2023',
                    mulai: '08:00',
                    selesai: '09:00',
                    // count array peserta
                    jumlah_peserta: 10,
                    peserta: null,
                    status: 'menyusun_hasil_meeting',
                    lokasi: 'Gedung A',
                },
                {
                    id: 2,
                    nama: 'Meeting 2',
                    tanggal: '2023-02-01',
                    tgl_meet: '02 Februari 2023',
                    mulai: '08:00',
                    selesai: '09:00',
                    // count array peserta
                    jumlah_peserta: 10,
                    peserta: null,
                    status: 'belum_terlaksana',
                    lokasi: 'Gedung A',
                },
            ],
            searchSelesai: '',
            dataTableSelesai: [
                {
                    id: 3,
                    nama: 'Meeting 2',
                    tanggal: '2023-02-01',
                    tgl_meet: '02 Februari 2023',
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
                    tgl_meet: '02 Februari 2023',
                    mulai: '08:00',
                    selesai: '09:00',
                    jumlah_peserta: 10,
                    status: 'batal',
                    lokasi: 'Gedung A',
                }
            ],
            headers: [
                { text: 'No', value: 'no', sortable: false },
                { text: 'Nomor Meeting', value: 'no_meet' },
                { text: 'Judul Meeting', value: 'nama' },
                { text: 'Tanggal', value: 'tgl_meet' },
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