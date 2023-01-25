<script>
import mix from './mix'
export default {
    mixins: [mix],
    data() {
        return {
            dataTable: [
                {
                    id: 1,
                    no_po: 'PO-001',
                    tanggal_po: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    tanggal_kedatangan: '2020-01-01',
                    status: 'selesai'
                },
                {
                    id: 2,
                    no_po: 'PO-001',
                    tanggal_po: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    tanggal_kedatangan: '2020-01-01',
                    status: 'menunggu permintaan'
                },
                {
                    id: 3,
                    no_po: 'PO-001',
                    tanggal_po: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    tanggal_kedatangan: '2020-01-01',
                    status: 'selesai',
                    persentase: 50,
                },
                {
                    id: 4,
                    no_po: 'PO-001',
                    tanggal_po: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    tanggal_kedatangan: '2020-01-01',
                    status: 'proses po',
                },
            ]
        }
    },
    methods: {
        status(status, persentase) {
            switch (status) {
                case 'selesai':
                    if (persentase) {
                        return `
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: ${persentase}%" aria-valuenow="${persentase}" aria-valuemin="0" aria-valuemax="100">${persentase}%</div>
                            </div>
                            <small class="text-center">Selesai</small>
                        `
                    }
                    return '<span class="badge badge-success">Selesai</span>'
                case 'menunggu permintaan':
                    return '<span class="badge badge-warning">Menunggu Permintaan</span>'
                case 'proses po':
                    return '<span class="badge badge-info">Proses PO</span>'
                case 'batal':
                    return '<span class="badge badge-danger">Batal</span>'
                default:
                    return `<span class="badge badge-secondary">${status}</span>`
            }
        },
        checkEdit(data) {
            switch (data) {
                case 'menunggu permintaan':
                    return true
                case 'proses po':
                    return true
                default:
                    return false
            }
        }
    },
}
</script>
<template>
    <div>
        <table class="table">
            <thead class="thead">
                <tr>
                    <th>No</th>
                    <th>No PO</th>
                    <th>Tanggal PO</th>
                    <th>Estimasi Kedatangan</th>
                    <th>Tanggal Kedatangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in dataTable" :key="data.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.no_po }}</td>
                    <td>{{ moment(data.tanggal_po) }}</td>
                    <td>{{ moment(data.estimasi_kedatangan) }}</td>
                    <td>{{ moment(data.tanggal_kedatangan) }}</td>
                    <td v-html="status(data.status, data.persentase)"></td>
                    <td>
                        <button class="btn btn-outline-info">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-warning" v-if="checkEdit(data.status)">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>