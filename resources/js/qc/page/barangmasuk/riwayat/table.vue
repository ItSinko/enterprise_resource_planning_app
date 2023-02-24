<script>
export default {
    props: {
        dataTable: {
            type: Array,
            default: () => []
        }
    }
}
</script>
<template>
    <div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No PO</th>
                    <th>Divisi</th>
                    <th>Supplier</th>
                    <th>Tanggal Diminta</th>
                    <th>Estimasi Kedatangan</th>
                    <th>Tanggal Selesai Cek</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(proses, index) in dataTable" :key="index">
                    <td>{{ proses.no_po }}</td>
                    <td>{{ proses.divisi }}</td>
                    <td>{{ proses.supplier }}</td>
                    <td>{{ formatTanggal(proses.tanggal_diminta) }}</td>
                    <td>{{ formatTanggal(proses.estimasi_kedatangan) }}</td>
                    <td>{{ formatTanggal(proses.tanggal_selesai_cek) }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" @click="detail(proses.id)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>