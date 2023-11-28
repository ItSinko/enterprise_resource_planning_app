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
            tanggalAwal: '',
            tanggalAkhir: '',
            laporan: [
                {
                    id: 1,
                    tgl_pelaporan: '23 Agustus 2021',
                    tgl_lapor: '2021-08-23',
                    jenis_pelaporan: 'Izin Keperluan Pribadi',
                    status: 'draft',
                },
                {
                    id: 2,
                    tgl_pelaporan: '23 Agustus 2021',
                    tgl_lapor: '2021-08-23',
                    jenis_pelaporan: 'Izin Keperluan Pribadi',
                    status: 'menunggu_persetujuan_kepala_bagian',
                },
                {
                    id: 3,
                    tgl_pelaporan: '23 Agustus 2021',
                    tgl_lapor: '2021-08-23',
                    jenis_pelaporan: 'Izin Keperluan Pribadi',
                    status: 'menunggu_persetujuan_hrga',
                },
                {
                    id: 4,
                    tgl_pelaporan: '23 Agustus 2021',
                    tgl_lapor: '2021-08-23',
                    jenis_pelaporan: 'Cuti',
                    status: 'aktif',
                },
                {
                    id: 5,
                    tgl_pelaporan: '23 Agustus 2021',
                    tgl_lapor: '2021-08-23',
                    jenis_pelaporan: 'Izin Keperluan Pribadi',
                    status: 'selesai',
                },
                {
                    id: 6,
                    tgl_pelaporan: '23 Agustus 2021',
                    tgl_lapor: '2021-08-23',
                    jenis_pelaporan: 'Izin Keperluan Pribadi',
                    status: 'batal',
                },
                {
                    id: 7,
                    tgl_pelaporan: '23 Agustus 2021',
                    tgl_lapor: '2021-08-23',
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
                    sortable: false,
                },
                {
                    text: 'Jenis Pelaporan',
                    align: 'center',
                    value: 'jenis_pelaporan',
                    sortable: false,
                },
                {
                    text: 'Status',
                    align: 'center',
                    value: 'status',
                    sortable: false,
                },
                {
                    text: 'Aksi',
                    align: 'center',
                    sortable: false,
                    value: 'aksi',
                },
            ],
            jenisLaporan: [
                'Permohonan Tidak Masuk Kerja',
                'Permohonan Cuti',
                'Izin Keperluan Kantor',
                'Izin Keterlambatan',
                'Izin Keperluan Pribadi',
            ],
            statusLaporan: [
                'draft',
                'menunggu_persetujuan_kepala_bagian',
                'menunggu_persetujuan_hrga',
                'aktif',
                'selesai',
                'batal',
                'ditolak',
            ],
            pilihJenisLaporan: [],
            pilihStatus: [],
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
        },
        detail(id) {
            this.$router.push({ name: 'detail-pengajuan', params: { id } })
        },
        renderNo(data) {
            return data.map((item, index) => {
                return {
                    ...item,
                    no: index + 1,
                }
            })
        },
        clickFilterPelaporan(laporan) {
            if (this.pilihJenisLaporan.includes(laporan)) {
                this.pilihJenisLaporan = this.pilihJenisLaporan.filter(item => item !== laporan);
            } else {
                this.pilihJenisLaporan.push(laporan);
            }
        },
        clickFilterStatus(status) {
            if (this.pilihStatus.includes(status)) {
                this.pilihStatus = this.pilihStatus.filter(item => item !== status);
            } else {
                this.pilihStatus.push(status);
            }
        },
        changeTextStatus(status) {
            // change underscore to space and capitalize
            return status.replace(/_/g, ' ').replace(/\w\S*/g, (w) => (w.replace(/^\w/, (c) => c.toUpperCase())));
        }
    },
    computed: {
        filterDataDalamProses() {
            let filtered = this.renderNo(this.laporan);

            if (this.tanggalAwal && this.tanggalAkhir) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_lapor);
                    date.setHours(0, 0, 0, 0);
                    return date >= startDate && date <= endDate;
                }));
            } else if (this.tanggalAwal) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_lapor);
                    date.setHours(0, 0, 0, 0);
                    return date >= startDate;
                }));
            } else if (this.tanggalAkhir) {
                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_lapor);
                    date.setHours(0, 0, 0, 0);
                    return date <= endDate;
                }));
            }

            if(this.pilihJenisLaporan.length > 0) {
                filtered = this.renderNo(filtered.filter(data => {
                    return this.pilihJenisLaporan.includes(data.jenis_pelaporan);
                }));
            }

            if(this.pilihStatus.length > 0) {
                filtered = this.renderNo(filtered.filter(data => {
                    return this.pilihStatus.includes(data.status);
                }));
            }

            return filtered;
        }
    }
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
                <DataTable :headers="headers" :items="filterDataDalamProses" :search="search">
                    <template #header.tgl_pelaporan>
                        <span class="text-bold pr-2">Tanggal Pelaporan</span>
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
                    <template #header.jenis_pelaporan>
                        <span class="text-bold pr-2">Jenis Pelaporan</span>
                        <span class="filter">
                            <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i>
                            </a>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3 font-weight-normal">
                                        <div class="form-check" v-for="(item, index) in jenisLaporan" :key="index">
                                            <input class="form-check-input" type="checkbox" :value="item" @click="clickFilterPelaporan(item)"
                                                :id="`defaultCheck${index}`">
                                            <label class="form-check-label" :for="`defaultCheck${index}`">
                                                {{ item }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>
                    <template #header.status>
                        <span class="text-bold pr-2">Status</span>
                        <span class="filter">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-filter"></i>
                                </a>
                                <form id="filter_ekat">
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3 font-weight-normal">
                                            <div class="form-check" v-for="(item, index) in statusLaporan" :key="index">
                                                <input class="form-check-input" type="checkbox" :value="item" @click="clickFilterStatus(item)"
                                                    :id="`defaultCheck${index}`">
                                                <label class="form-check-label" :for="`defaultCheck${index}`">
                                                    {{ changeTextStatus(item) }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </span>
                    </template>

                    <template #item.status="{ item }">
                        <div>
                            <status :status="item.status" />
                        </div>
                    </template>
                    <template #item.aksi="{ item }">
                        <div>
                            <button class="btn btn-sm btn-outline-info" @click="detail(item.id)">
                                <i class="fas fa-info-circle"></i>
                                Detail
                            </button>
                            <button class="btn btn-sm btn-outline-warning" v-if="item.status == 'draft'"
                                @click="edit(item.id)">
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