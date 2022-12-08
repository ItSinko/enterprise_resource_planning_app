<script>
export default {
    props: {
        dataTable: {
            type: Array,
            default: () => []
        }
    },
    methods: {
        getStatusColor(status) {
            switch (status) {
                case 'belum selesai':
                    return 'badge-warning'
                case 'selesai':
                    return 'badge-success'
                default:
                    return 'badge-danger'
            }
        },
        detail(idx) {
            this.$emit('detail', idx)
        }
    },
}
</script>
<template>
    <div>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th>Nama Produk</th>
                    <th>No Laporan</th>
                    <th>Tgl Sampling</th>
                    <th>Jumlah Sampling</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, idx) in dataTable" :key="idx">
                    <td>{{ data.nama_produk }}</td>
                    <td>{{ data.no_laporan }}</td>
                    <td>{{ data.tgl_sampling }}</td>
                    <td>{{ data.jumlah_sampling }}</td>
                    <td>{{ data.keterangan }}</td>
                    <td>
                        <span class="badge" :class="getStatusColor(data.status)">
                            {{ data.status }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm">
                            Unduh Dokumen
                        </button>
                        <button class="btn btn-sm btn-outline-info" @click="detail(idx)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>