<script>
import DataTable from '../../../../components/DataTable.vue'
export default {
    components: {
        DataTable,
    },
    props: ['dataTable'],
    data() {
        return {
            headers: [
                { text: 'No', value: 'no' },
                { text: 'Nama', value: 'nama' },
                { text: 'Bagian', value: 'bagian', sortable: false },
                { text: 'Tanggal Pengajuan', value: 'tanggal', sortable: false },
                { text: 'Jenis Pelaporan', value: 'jenis' },
                { text: 'Aksi', value: 'aksi' },
            ],
            search: '',
            tanggalAwal: '',
            tanggalAkhir: '',
            bagianFilter: [],
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
                    link: this.$route.fullPath,
                }
            })
        },
        renderNo(data) {
            return data.map((item, index) => {
                return {
                    ...item,
                    no: index + 1
                }
            })
        }
    },
    computed: {
        filterData() {
            let filtered = this.renderNo(this.dataTable);

            if (this.tanggalAwal && this.tanggalAkhir) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = filtered.filter(item => {
                    const date = new Date(item.tgl_pengajuan);
                    return date >= startDate && date <= endDate;
                })
            } else if (this.tanggalAwal) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                filtered = filtered.filter(item => {
                    const date = new Date(item.tgl_pengajuan);
                    return date >= startDate;
                })
            } else if (this.tanggalAkhir) {
                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = filtered.filter(item => {
                    const date = new Date(item.tgl_pengajuan);
                    return date <= endDate;
                })
            }

            if (this.bagianFilter.length > 0) {
                filtered = filtered.filter(item => {
                    return this.bagianFilter.includes(item.bagian);
                })
            }

            return filtered;
        },

        getAllBagianUnique() {
            const bagian = this.dataTable.map(item => item.bagian);
            return [...new Set(bagian)];
        }
    }
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control">
            </div>
        </div>
        <DataTable :headers="headers" :items="filterData" :search="search" >
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