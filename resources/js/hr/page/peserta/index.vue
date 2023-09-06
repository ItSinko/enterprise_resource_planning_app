<script>
import Header from "../../components/header.vue";
import Table from "./table.vue";
import pagination from "../../components/pagination.vue";
export default {
    components: {
        Header,
        Table,
        pagination,
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
            renderPaginate: [],

            searchSelesai: "",
            renderPaginateSelesai: [],
            dataTableSelesai: [
                {
                    id: 3,
                    nama: "Meeting 2",
                    tanggal: "2023-02-01",
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
                    mulai: "08:00",
                    selesai: "09:00",
                    jumlah_peserta: 10,
                    status: "batal",
                    lokasi: "Gedung A",
                    kehadiran: "tidak_hadir",
                },
            ],

            filterKehadiran: [],
        };
    },
    methods: {
        updatePage(page) {
            this.renderPaginate = page;
        },
        updatePageSelesai(page) {
            this.renderPaginateSelesai = page;
        },
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
    },
    computed: {
        paginateData() {
            let filtered = [];
            if (this.filterKehadiran.length > 0) {
                this.filterKehadiran.forEach((filter) => {
                    filtered = filtered.concat(
                        this.dataTable.filter(
                            (data) => data.kehadiran == filter
                        )
                    );
                });
            } else {
                filtered = this.dataTable;
            }

            return filtered.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key])
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                });
            });
        },
        paginateDataSelesai() {
            return this.dataTableSelesai.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key])
                        .toLowerCase()
                        .includes(this.searchSelesai.toLowerCase());
                });
            });
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
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a
                            class="nav-link active"
                            id="pills-proses-tab"
                            data-toggle="pill"
                            data-target="#pills-proses"
                            type="button"
                            role="tab"
                            aria-controls="pills-proses"
                            aria-selected="true"
                            >Belum Selesai</a
                        >
                    </li>
                    <li class="nav-item" role="presentation">
                        <a
                            class="nav-link"
                            id="pills-selesai-tab"
                            data-toggle="pill"
                            data-target="#pills-selesai"
                            type="button"
                            role="tab"
                            aria-controls="pills-selesai"
                            aria-selected="false"
                            >Selesai</a
                        >
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div
                        class="tab-pane fade show active"
                        id="pills-proses"
                        role="tabpanel"
                        aria-labelledby="pills-proses-tab"
                    >
                        <div class="d-flex bd-highlight mb-3">
                            <div class="mr-auto p-2 bd-highlight">
                                <span class="float-left filter">
                                    <button
                                        class="btn btn-outline-info"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                    >
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <form id="filter_ekat">
                                        <div class="dropdown-menu">
                                            <div class="px-3 py-3">
                                                <div class="form-group">
                                                    <label for="jenis_penjualan"
                                                        >Kehadiran</label
                                                    >
                                                </div>
                                                <div
                                                    class="form-group"
                                                    v-for="status in getAllStatusUnique"
                                                    :key="status"
                                                >
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            :ref="status"
                                                            :value="status"
                                                            id="status1"
                                                            @click="
                                                                clickFilterKehadiran(
                                                                    status
                                                                )
                                                            "
                                                        />
                                                        <label
                                                            class="form-check-label text-uppercase"
                                                            for="status1"
                                                        >
                                                            {{ changeTextToLowercaseandChangeUnderscoreTextToSpace(status) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </span>
                            </div>
                            <div class="p-2 bd-highlight">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Cari..."
                                        v-model="search"
                                    />
                                </div>
                            </div>
                        </div>

                        <Table :dataTable="renderPaginate" />
                        <pagination
                            :DataTable="paginateData"
                            @updatePage="updatePage"
                        />
                    </div>
                    <div
                        class="tab-pane fade"
                        id="pills-selesai"
                        role="tabpanel"
                        aria-labelledby="pills-selesai-tab"
                    >
                        <div class="d-flex bd-highlight mb-3">
                            <div class="mr-auto p-2 bd-highlight"></div>
                            <div class="p-2 bd-highlight">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Cari..."
                                        v-model="searchSelesai"
                                    />
                                </div>
                            </div>
                        </div>

                        <Table :dataTable="renderPaginateSelesai" />
                        <pagination
                            :DataTable="paginateDataSelesai"
                            @updatePage="updatePageSelesai"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
