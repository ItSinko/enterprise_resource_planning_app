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
                { text: 'Jenis Kelamin', value: 'kelamin', align: 'text-left' },
                { text: 'Tanggal Masuk', value: 'tgl_kerja', align: 'text-left' },
                { text: 'Bagian', value: 'bagian' , align: 'text-left'},
                { text: 'Status Karyawan', value: 'status_karyawan', align: 'text-left' },
                { text: 'Status Pegawai', value: 'status_pegawai', align: 'text-left' },
                { text: 'Aksi', value: 'aksi', align: 'text-left' },
            ],
            search: '',
            filterBagian: [],
            getStatusKaryawan: [
                'Tetap',
                'Kontrak',
                'Outsourcing',
                'Magang'
            ],
            isOpen: false,
            modalPegawai: false,
        }
    },
    methods: {
        async getKaryawan() {
            try {
                const { data } = await axios.get('/api/karyawan_all');
                this.karyawan = data.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                        tgl_kerja: this.dateFormat(item.tgl_kerja),
                        kelamin: this.kelamin(item.kelamin),
                        status_karyawan: 'Tetap',
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
    },
    computed: {
        paginateData() {
            let filtered = []
            if (this.filterBagian.length > 0) {
                this.filterBagian.forEach((filter) => {
                    filtered = filtered.concat(this.karyawan.filter((data) => {
                        return data.divisi.nama === filter
                    }))
                })
            } else {
                filtered = this.karyawan
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
                        <span class="float-left filter">
                            <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Bagian</label>
                                            <v-select v-model="filterBagian" multiple="multiple" :close-on-select="false"
                                                :clear-on-select="false" :preserve-search="true" placeholder="Pilih Bagian"
                                                label="nama" :options="getAllBagianUnique" :taggable="true"
                                                @search="async (search) => { getAllBagianUnique }"
                                                @input="isOpen = true"></v-select>

                                        </div>
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
                                        <div class="form-group">
                                            <label for="">Status Pegawai</label>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                        id="status1" />
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
                                </div>
                            </form>
                        </span> &nbsp;
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
                        <div>
                            
                        </div>
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
                    <template #item.bagian="{ item }">
                        <div>
                            {{ item.divisi.nama }}
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
