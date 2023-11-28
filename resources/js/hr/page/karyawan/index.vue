<script>
import Header from '../../components/header.vue';
import axios from 'axios';
import DataTable from '../../components/DataTable.vue';
import modalPegawaiComp from './modalPegawai.vue';

export default {
    components: {
        Header,
        DataTable,
        modalPegawaiComp

    },
    data() {
        return {
            title: 'Karyawan',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Karyawan',
                    link: '/hr/karyawan'
                },
            ],
            karyawan: [],
            headers: [
                { text: 'No Urut', value: 'no' },
                { text: 'Nama Karyawan', value: 'karyawan', align: 'text-left' },
                { text: 'Jenis Kelamin', value: 'kelamin', align: 'text-left', sortable: false },
                { text: 'Tanggal Masuk', value: 'tanggaKerja', align: 'text-left', sortable: false },
                { text: 'Bagian', value: 'divisi_karyawan', align: 'text-left', sortable: false },
                { text: 'Status Karyawan', value: 'status_karyawan', align: 'text-left', sortable: false },
                { text: 'Status Pegawai', value: 'status_pegawai', align: 'text-left', sortable: false },
                { text: 'Aksi', value: 'aksi', align: 'text-left' },
            ],
            search: '',
            getStatusKaryawan: [
                'Tetap',
                'Kontrak',
                'Outsourcing',
                'Magang'
            ],
            isOpen: false,
            modalPegawai: false,
            filterBagian: [],
            jenisKelaminFilter: [],
            tanggalAwal: '',
            tanggalAkhir: '',
        }
    },
    methods: {
        async getKaryawan() {
            try {
                const { data } = await axios.get('/api/karyawan_all');
                this.karyawan = data.map((item) => {
                    return {
                        ...item,
                        tanggaKerja: this.dateFormat(item.tgl_kerja),
                        kelamin: this.kelamin(item.kelamin),
                        status_karyawan: 'Tetap',
                        divisi_karyawan: item.divisi.nama,
                    }
                })
            } catch (error) {
                console.log(error);
            }
        },
        image(src) {
            return src ? src : '/assets/image/user/blank.png';
        },
        kelamin(kelamin) {
            return kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
        },
        tambah() {
            this.$router.push({ name: 'tambah-karyawan' })
        },
        edit(id) {
            this.$router.push({ name: 'edit-karyawan', params: { id } })
        },
        detail(id) {
            this.$router.push({ name: 'detail-karyawan', params: { id } })
        },
        status(id) {
            this.modalPegawai = true;
            this.$nextTick(() => {
                $('.modalPegawai').modal('show');
            })
        },
        renderNo(data) {
            return data.map((item, index) => {
                return {
                    ...item,
                    no: index + 1,
                }
            })
        },
        selectJenisKelamin(jenisKelamin) {
            if (this.jenisKelaminFilter.includes(jenisKelamin)) {
                this.jenisKelaminFilter = this.jenisKelaminFilter.filter((item) => {
                    return item !== jenisKelamin
                })
            } else {
                this.jenisKelaminFilter.push(jenisKelamin)
            }
        },
    },
    computed: {
        paginateData() {
            let filtered = this.renderNo(this.karyawan)

            if (this.jenisKelaminFilter.length > 0) {
                filtered = this.renderNo(this.karyawan.filter((data) => {
                    return this.jenisKelaminFilter.includes(data.kelamin)
                }))
            }

            if (this.tanggalAwal && this.tanggalAkhir) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_kerja);
                    date.setHours(0, 0, 0, 0);
                    return date >= startDate && date <= endDate;
                }));
            } else if (this.tanggalAwal) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_kerja);
                    date.setHours(0, 0, 0, 0);
                    return date >= startDate;
                }));
            } else if (this.tanggalAkhir) {
                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_kerja);
                    date.setHours(0, 0, 0, 0);
                    return date <= endDate;
                }));
            }

            if (this.filterBagian.length > 0) {
                filtered = this.renderNo(filtered.filter((data) => {
                    return this.filterBagian.includes(data.divisi_karyawan)
                }))
            }

            return filtered
        },
        getAllBagianUnique() {
            return this.karyawan.map((karyawan) => {
                return karyawan.divisi.nama
            }).filter((value, index, self) => {
                return self.indexOf(value) === index
            })
        },
    },
    mounted() {
        this.getKaryawan();
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <modalPegawaiComp v-if="modalPegawai" @closeModal="modalPegawai = false"></modalPegawaiComp>
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight mb-3">
                    <div class="mr-auto p-2 bd-highlight">
                        <button class="btn btn-primary" @click="tambah">
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
                <DataTable :headers="headers" :items="paginateData" :search="search">
                    <template #header.kelamin>
                        <span class="text-bold pr-2">Jenis Kelamin</span>
                        <span class="filter">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i>
                            </a>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3 font-weight-normal">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="defaultCheck1"
                                                @click="selectJenisKelamin('Laki-laki')">
                                            <label class="form-check-label" for="defaultCheck1">
                                                Laki - laki
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="defaultCheck2"
                                                @click="selectJenisKelamin('Perempuan')">
                                            <label class="form-check-label" for="defaultCheck2">
                                                Perempuan
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>
                    <template #header.tanggaKerja>
                        <span class="text-bold pr-2">Tanggal Masuk</span>
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
                    <template #header.divisi_karyawan>
                        <span class="text-bold pr-2">Bagian</span>
                        <span class="filter">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i>
                            </a>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3 font-weight-normal">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Bagian</label>
                                            <v-select v-model="filterBagian" multiple="multiple" :close-on-select="false"
                                                :clear-on-select="false" :preserve-search="true" placeholder="Pilih Bagian"
                                                label="nama" :options="getAllBagianUnique" :taggable="true"
                                                @search="async (search) => { getAllBagianUnique }"
                                                @input="isOpen = true"></v-select>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>
                    <template #header.status_karyawan>
                        <span class="text-bold pr-2">Status Karyawan</span>
                        <span class="filter">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i>
                            </a>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3 font-weight-normal">
                                        <div class="form-group">
                                            <label for="">Status Karyawan</label>
                                            <div class="form-group" v-for="statusKaryawan in getStatusKaryawan"
                                                :key="statusKaryawan">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" :ref="statusKaryawan"
                                                        :value="statusKaryawan" id="status1" />
                                                    <label class="form-check-label text-uppercase" for="status1">
                                                        {{ statusKaryawan }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>
                    <template #header.status_pegawai>
                        <span class="text-bold pr-2">Status Pegawai</span>
                        <span class="filter">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i>
                            </a>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3 font-weight-normal">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="status1" />
                                                <label class="form-check-label text-uppercase" for="status1">
                                                    Aktif
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="0"
                                                        id="status1" />
                                                    <label class="form-check-label text-uppercase" for="status1">
                                                        Tidak Aktif
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>

                    <template #item.karyawan="{ item }">
                        <div class="d-flex bd-highlight">
                            <div class="p-2 bd-highlight">
                                <img :src="image(item.foto)" class="rounded float-left" width="50">
                            </div>
                            <div class="align-self-start p-2 bd-highlight">
                                <span class="text-bold">{{ item.nama }}</span><br>
                                <span class="text-muted">{{ item?.divisi_id }} (Nomor Induk Karyawan)</span>
                            </div>
                        </div>
                    </template>
                    <template #item.status_pegawai="{ item, index }">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" :id="`customSwitch${index}`"
                                @click="status(item.id)">
                            <label class="custom-control-label" :for="`customSwitch${index}`">Aktif</label>
                        </div>
                    </template>
                    <template #item.aksi="{ item }">
                        <div>
                            <button class="btn btn-sm btn-outline-warning" @click="edit(item.id)">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-info" @click="detail(item.id)">
                                <i class="fa fa-eye"></i> Detail
                            </button>
                        </div>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</template>
<style>
.scrollable {
    overflow-y: auto;
    max-height: 300px;
    height: 200px;
}
</style>
