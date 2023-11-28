<script>
import Header from "../../components/header.vue";
import DataTable from "../../components/DataTable.vue";
import status from "../../components/status.vue";
import kehadiran from "./aksi/kehadiran.vue";
export default {
    components: {
        Header,
        DataTable,
        status,
        kehadiran,
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
                    link: "/meeting/jadwal_meet",
                },
            ],
            dataTable: [
                {
                    id: 1,
                    nama: "Meeting 1",
                    tanggal: "2023-01-01",
                    tgl_meet: "01 Januari 2023",
                    mulai: "08:00",
                    selesai: "09:00",
                    // count array peserta
                    jumlah_peserta: 10,
                    peserta: null,
                    status: "menyusun_hasil_meeting",
                    lokasi: "Gedung A",
                    kehadiran: "tidak_hadir",
                },
                {
                    id: 2,
                    nama: "Meeting 2",
                    tanggal: "2023-02-01",
                    tgl_meet: "01 Februari 2023",
                    mulai: "08:00",
                    selesai: "09:00",
                    // count array peserta
                    jumlah_peserta: 10,
                    peserta: null,
                    status: "belum_terlaksana",
                    lokasi: "Gedung A",
                    kehadiran: "hadir",
                },
                {
                    id: 3,
                    nama: "Meeting 3",
                    tanggal: "2023-02-01",
                    tgl_meet: "01 Februari 2023",
                    mulai: "10:00",
                    selesai: "11:00",
                    // count array peserta
                    jumlah_peserta: 10,
                    peserta: null,
                    status: "belum_terlaksana",
                    lokasi: "Gedung A",
                    kehadiran: "belum_mengisi_daftar_hadir",
                },
            ],
            search: "",

            searchSelesai: "",
            dataTableSelesai: [
                {
                    id: 3,
                    nama: "Meeting 2",
                    tanggal: "2023-02-01",
                    tgl_meet: "01 Februari 2023",
                    mulai: "08:00",
                    selesai: "09:00",
                    jumlah_peserta: 10,
                    status: "terlaksana",
                    lokasi: "Gedung A",
                    kehadiran: "hadir",
                },
                {
                    id: 3,
                    nama: "Meeting 2",
                    tanggal: "2023-02-01",
                    tgl_meet: "01 Februari 2023",
                    mulai: "08:00",
                    selesai: "09:00",
                    jumlah_peserta: 10,
                    status: "batal",
                    lokasi: "Gedung A",
                    kehadiran: "tidak_hadir",
                },
            ],
            headers: [
                { text: 'No', value: 'no', sortable: false },
                { text: 'Nomor Meeting', value: 'no_meet' },
                { text: 'Judul Meeting', value: 'nama' },
                { text: 'Tanggal', value: 'tgl_meet', sortable: false },
                { text: 'Mulai', value: 'mulai' },
                { text: 'Selesai', value: 'selesai' },
                { text: 'Lokasi', value: 'lokasi' },
                { text: 'Status', value: 'status' },
                { text: 'Kehadiran', value: 'kehadiran', sortable: false },
                { text: 'Aksi', value: 'aksi', sortable: false },
            ],

            filterKehadiran: [],
            tanggalAwal: '',
            tanggalAkhir: '',
            modalKehadiran: false,
            dataKehadiran: null,
        };
    },
    methods: {
        clickFilterKehadiran(filter) {
            if (this.filterKehadiran.includes(filter)) {
                this.filterKehadiran = this.filterKehadiran.filter(
                    (item) => item !== filter
                );
            } else {
                this.filterKehadiran.push(filter);
            }
        },
        changeTextToLowercaseandChangeUnderscoreTextToSpace(text) {
            return text
                .toLowerCase()
                .split("_")
                .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                .join(" ");
        },
        openModalKehadiran(data) {
            this.dataKehadiran = JSON.parse(JSON.stringify(data));
            this.modalKehadiran = true;
            this.$nextTick(() => {
                $(".modalKehadiran").modal("show");
            });
        },
        detail(id, status) {
            if (status == "terlaksana" || status == "menyusun_hasil_meeting") {
                this.$router.push({
                    name: "jadwal-meeting-peserta-detail-terlaksana",
                    params: { id: id },
                });
            } else {
                this.$router.push({
                    name: "jadwal-meeting-peserta-detail-nonterlaksana",
                    params: { id: id },
                });
            }
        },
        renderNo(data) {
            return data.map((item, index) => {
                return {
                    ...item,
                    no: index + 1,
                };
            });
        }
    },
    computed: {
        paginateData() {
            let filtered = this.renderNo(this.dataTable);
            if (this.filterKehadiran.length > 0) {
                filtered = this.renderNo(this.dataTable).filter((item) => {
                    return this.filterKehadiran.includes(item.kehadiran);
                });
            }

            if (this.tanggalAwal && this.tanggalAkhir) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_meet);
                    date.setHours(0, 0, 0, 0);
                    return date >= startDate && date <= endDate;
                }));
            } else if (this.tanggalAwal) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_meet);
                    date.setHours(0, 0, 0, 0);
                    return date >= startDate;
                }));
            } else if (this.tanggalAkhir) {
                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_meet);
                    date.setHours(0, 0, 0, 0);
                    return date <= endDate;
                }));
            }
            return filtered
        },
        getAllStatusUnique() {
            return this.dataTable
                .map((data) => {
                    return data.kehadiran;
                })
                .filter((value, index, self) => {
                    return self.indexOf(value) === index;
                });
        },
    },
};
</script>
<template>
    <div>
        <kehadiran v-if="modalKehadiran" @closeModal="modalKehadiran = false" :kehadiran="dataKehadiran" />
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
                        <div class="d-flex flex-row-reverse bd-highlight">
                            <div class="p-2 bd-highlight"><input type="text" class="form-control" placeholder="Cari..."
                                    v-model="search" /></div>
                        </div>
                        <DataTable :headers="headers" :items="paginateData" :search="search">
                            <template #header.tgl_meet>
                                <span class="text-bold pr-2">Tanggal Dibuat</span>
                                <span class="filter">
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i>
                                    </a>
                                    <form id="filter_ekat">
                                        <div class="dropdown-menu">
                                            <div class="px-3 py-3">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Tanggal Awal</label>
                                                            <input type="date" class="form-control" v-model="tanggalAwal"
                                                                :max="tanggalAkhir">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Tanggal Akhir</label>
                                                            <input type="date" class="form-control" v-model="tanggalAkhir"
                                                                :min="tanggalAwal">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </span>
                            </template>

                            <template #header.kehadiran>
                                <span class="text-bold pr-2">Kehadiran</span>
                                <span class="filter">
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i>
                                    </a>
                                    <form id="filter_ekat">
                                        <div class="dropdown-menu">
                                            <div class="px-3 py-3">
                                                <div class="form-group">
                                                    <label for="jenis_penjualan">Kehadiran</label>
                                                </div>
                                                <div class="form-group font-weight-normal"
                                                    v-for="status in getAllStatusUnique" :key="status">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" :ref="status"
                                                            :value="status" id="status1" @click="
                                                                clickFilterKehadiran(
                                                                    status
                                                                )
                                                                " />
                                                        <label class="form-check-label text-uppercase" for="status1">
                                                            {{ changeTextToLowercaseandChangeUnderscoreTextToSpace(status)
                                                            }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </span>
                            </template>

                            <template #item.no_meet="{ item, index }">
                                Meet-{{ index + 1 }}
                            </template>
                            <template #item.status="{ item }">
                                <status :status="item.status" />
                            </template>
                            <template #item.kehadiran="{ item }">
                                <status :status="item.kehadiran" />
                            </template>
                            <template #item.aksi="{ item }">
                                <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                    aria-haspopup="true" aria-expanded="true">
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                    v-if="item.status == 'belum_terlaksana'">
                                    <a>
                                        <button class="dropdown-item" @click="openModalKehadiran(item)">
                                            <i class="fas fa-check-circle"></i>
                                            Kehadiran
                                        </button>
                                        <button class="dropdown-item" @click="detail(item.id, item.status)">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </button>
                                    </a>
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                    v-if="item.status == 'menyusun_hasil_meeting'">
                                    <a>
                                        <button class="dropdown-item" type="button" @click="detail(item.id, item.status)">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </button>
                                    </a>
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                    v-if="item.status == 'terlaksana'">
                                    <a>
                                        <button class="dropdown-item">
                                            <i class="fas fa-print"></i>
                                            Cetak Hasil Meeting
                                        </button>
                                        <button class="dropdown-item" type="button" @click="detail(item.id, item.status)">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </button>
                                    </a>
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                    v-if="item.status == 'batal'">
                                    <a>
                                        <button class="dropdown-item" type="button" @click="detail(item.id, item.status)">
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
                            <div class="mr-auto p-2 bd-highlight"></div>
                            <div class="p-2 bd-highlight">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Cari..." v-model="searchSelesai" />
                                </div>
                            </div>
                        </div>

                        <DataTable :headers="headers" :items="dataTableSelesai" :search="searchSelesai">
                            <template #item.no="{ item, index }">
                                {{ index + 1 }}
                            </template>
                            <template #item.no_meet="{ item, index }">
                                Meet-{{ index + 1 }}
                            </template>
                            <template #item.status="{ item }">
                                <status :status="item.status" />
                            </template>
                            <template #item.kehadiran="{ item }">
                                <status :status="item.kehadiran" />
                            </template>
                            <template #item.aksi="{ item }">
                                <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                    aria-haspopup="true" aria-expanded="true">
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                    v-if="item.status == 'belum_terlaksana'">
                                    <a>
                                        <button class="dropdown-item" @click="openModalKehadiran(item)">
                                            <i class="fas fa-check-circle"></i>
                                            Kehadiran
                                        </button>
                                        <button class="dropdown-item" @click="detail(item.id, item.status)">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </button>
                                    </a>
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                    v-if="item.status == 'menyusun_hasil_meeting'">
                                    <a>
                                        <button class="dropdown-item" type="button" @click="detail(item.id, item.status)">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </button>
                                    </a>
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                    v-if="item.status == 'terlaksana'">
                                    <a>
                                        <button class="dropdown-item">
                                            <i class="fas fa-print"></i>
                                            Cetak Hasil Meeting
                                        </button>
                                        <button class="dropdown-item" type="button" @click="detail(item.id, item.status)">
                                            <i class="fas fa-eye"></i>
                                            Detail
                                        </button>
                                    </a>
                                </div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                    v-if="item.status == 'batal'">
                                    <a>
                                        <button class="dropdown-item" type="button" @click="detail(item.id, item.status)">
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
