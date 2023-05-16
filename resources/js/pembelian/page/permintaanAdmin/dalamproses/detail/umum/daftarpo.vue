<script>
import mix from './mix'
import status from '../../../../../components/status'
export default {
    mixins: [mix],
    components: {
        status
    },
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
                    <td>
                        <status :status="data.status" :persentase="data.persentase"></status>
                    </td>
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