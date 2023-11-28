<script>
import DataTable from '../../../components/DataTable.vue';
export default {
    components: {
        DataTable,
    },
    data() {
        return {
            headers: [
                { text: 'No', value: 'no' },
                { text: 'Nama', value: 'nama' },
                { text: 'Tanggal Pengajuan', value: 'tanggal', sortable: false },
                { text: 'Jenis Pelaporan', value: 'jenis' },
                { text: 'Aksi', value: 'aksi' },
            ],
            dataTable: [
                {
                    id: 1,
                    nama: 'Prita Hanifah',
                    tanggal: '03 Januari 2021',
                    tgl_pengajuan: '2021-01-03',
                    jenis: 'Izin Keperluan Kantor',
                },
            ],
            search: '',
            tanggalAwal: '',
            tanggalAkhir: '',
        }
    },
    methods: {
        setuju(item) {
            this.$swal({
                title: 'Apakah anda yakin, ingin menyetujui pengajuan ini?',
                text: "Pengajuan akan masuk ke dalam daftar kehadiran anggota",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal({
                        title: 'Berhasil!',
                        text: 'Pengajuan berhasil disetujui',
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                    })
                }
            })
        },
        tolak(item) {
            this.$swal({
                title: 'Apakah anda yakin, ingin menolak pengajuan ini?',
                text: "Pengajuan akan masuk ke dalam daftar pengajuan batal",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal({
                        title: 'Berhasil!',
                        text: 'Pengajuan berhasil ditolak',
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                    })
                }
            })
        },
        detail(item) {
            this.$router.push({
                name: 'detail-pengajuan',
                params: {
                    id: item.id,
                    // bawa link url saat ini
                    link: this.$route.fullPath,
                }
            })
        },
    },
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control">
            </div>
        </div>
        <DataTable :headers="headers" :items="dataTable" :search="search">
            <template #header.tanggal>
                <span class="text-bold pr-2">Tanggal Pengajuan</span>
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

            <template #item.no="{ item, index }">
                <div>
                    {{ index + 1 }}
                </div>
            </template>

            <template #item.aksi="{item}">
                <div>
                    <button class="btn btn-sm btn-outline-success" @click="setuju(item)">
                        <i class="fas fa-check"></i>
                        Setujui
                    </button>
                    <button class="btn btn-sm btn-outline-danger" @click="tolak(item)">
                        <i class="fas fa-times"></i>
                        Tolak
                    </button>
                    <button class="btn btn-sm btn-outline-info" @click="detail(item)">
                        <i class="fas fa-info-circle"></i>
                        Detail
                    </button>
                    
                </div>
            </template>


        </DataTable>
    </div>
</template>