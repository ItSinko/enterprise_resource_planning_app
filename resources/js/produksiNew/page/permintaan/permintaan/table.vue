<script>
import axios from 'axios';
import status from '../../../components/status.vue';
export default {
    props: ['dataTable'],
    components: {
        status,
    },
    methods: {
        kirim(data) {
            const success = () => {
                this.$swal('Berhasil!', 'Data berhasil dikirim', 'success')
                this.$emit('refresh')
            }

            const error = () => {
                this.$swal('Gagal!', 'Data gagal dikirim', 'error')
            }

            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Anda akan mengirim data permintaan ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/api/prd/rw/permintaan', data, {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('lokal_token'),
                        }
                    }).then(() => {
                        success()
                    }).catch(() => {
                        error()
                    })
                }
            })
        },
        cetakPermintaan(id) {
            window.open(`/produksiReworks/surat_permintaan/${id}`, '_blank')
        },
    },
}
</script>
<template>
    <div>
        <table class="table text-center">
            <thead>
                <tr>
                    <th rowspan="2">No Urut</th>
                    <th rowspan="2">Tanggal Mulai</th>
                    <th rowspan="2">Tanggal Selesai</th>
                    <th rowspan="2">Nama Produk</th>
                    <th rowspan="2">Jumlah Permintaan</th>
                    <th colspan="2">Jumlah Produk</th>
                    <th rowspan="2">Status</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Selesai</th>
                    <th>Belum Selesai</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ data.no_urut }}</td>
                    <td>{{ data.tgl_mulai }}</td>
                    <td>{{ data.tgl_selesai }}</td>
                    <td>{{ data.nama }}</td>
                    <td>{{ data.jumlah }}</td>
                    <td>{{ data.selesai }}</td>
                    <td>{{ data.belum }}</td>
                    <td>
                        <status :status="data.status" />
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" v-if="data.status != 'Proses'" @click="kirim(data)">
                            <i class="fas fa-paper-plane"></i>
                            Kirim
                        </button>
                        <button class="btn btn-sm btn-outline-primary" v-else @click="cetakPermintaan(data.urutan)">
                            <i class="fas fa-print"></i>
                            Cetak Permintaan
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="100%" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
