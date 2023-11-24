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
            title: 'Laporan Kehadiran',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Laporan Kehadiran',
                    link: '/hr/form_pengajuan'
                }
            ],
            search: '',
            laporan: [
                {
                    id: 1,
                    tgl_pelaporan: '23 Agustus 2021',
                    jenis_pelaporan: 'Izin Keperluan Pribadi',
                    status: 'draft',
                },
                {
                    id: 2,
                    tgl_pelaporan: '23 Agustus 2021',
                    jenis_pelaporan: 'Izin Keperluan Pribadi',
                    status: 'menunggu_persetujuan_kepala_bagian',
                },
                {
                    id: 3,
                    tgl_pelaporan: '23 Agustus 2021',
                    jenis_pelaporan: 'Izin Keperluan Pribadi',
                    status: 'menunggu_persetujuan_hrga',
                },
                {
                    id: 4,
                    tgl_pelaporan: '23 Agustus 2021',
                    jenis_pelaporan: 'aktif',
                    status: 'aktif',
                },
                {
                    id: 5,
                    tgl_pelaporan: '23 Agustus 2021',
                    jenis_pelaporan: 'Izin Keperluan Pribadi',
                    status: 'selesai',
                },
                {
                    id: 6,
                    tgl_pelaporan: '23 Agustus 2021',
                    jenis_pelaporan: 'Izin Keperluan Pribadi',
                    status: 'batal',
                },
                {
                    id: 7,
                    tgl_pelaporan: '23 Agustus 2021',
                    jenis_pelaporan: 'Izin Keperluan Pribadi',
                    status: 'ditolak',
                },
            ],
            headers: [
                {
                    text: 'No.',
                    align: 'center',
                    value: 'no',
                    sortable: false,
                },
                {
                    text: 'Tanggal Pelaporan',
                    align: 'center',
                    value: 'tgl_pelaporan',
                },
                {
                    text: 'Jenis Pelaporan',
                    align: 'center',
                    value: 'jenis_pelaporan',
                },
                {
                    text: 'Status',
                    align: 'center',
                    value: 'status',
                },
                {
                    text: 'Aksi',
                    align: 'center',
                    sortable: false,
                    value: 'aksi',
                },
            ],
        }
    },
    methods: {
        tambah() {
            this.$router.push({ name: 'tambah-pengajuan' })
        },
        batal() {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: 'Pengajuan ini akan dibatalkan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, batalkan!',
                cancelButtonText: 'Tidak, jangan batalkan!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal(
                        'Dibatalkan!',
                        'Pengajuan telah dibatalkan.',
                        'success'
                    )
                }
            })
        },
        edit(id) {
            this.$router.push({ name: 'edit-pengajuan', params: { id } })
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="tambah">
                            Buat Pengajuan Form Kehadiran
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                    </div>
                </div>
                <DataTable :headers="headers" :items="laporan" :search="search" >
                    <template #item.no = "{item, index}">
                        <div>
                            {{ index + 1 }}
                        </div>
                    </template>
                    <template #item.status="{item}">
                        <div>
                            <status :status="item.status" />
                        </div>
                    </template>
                    <template #item.aksi = "{item}">
                        <div>
                            <button class="btn btn-sm btn-outline-info">
                                <i class="fas fa-info-circle"></i>
                                Detail
                            </button>
                            <button class="btn btn-sm btn-outline-warning" v-if="item.status == 'draft'" @click="edit(item.id)">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger" v-if="item.status == 'aktif'" @click="batal">
                                <i class="fas fa-times"></i>
                                Batal
                            </button>
                        </div>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</template>