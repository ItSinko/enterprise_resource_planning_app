<script>
import status from '../../../../../components/status'
import moment from 'moment'
export default {
    components: {
        status
    },
    data() {
        return {
            dataTables: [
                {
                    no_po: 'PO-001',
                    tanggal_po: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    tanggal_kedatangan: '2020-01-01',
                    status: 'menunggu permintaan',
                },
                {
                    no_po: 'PO-002',
                    tanggal_po: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    tanggal_kedatangan: '2020-01-01',
                    status: 'proses po',
                },
                {
                    no_po: 'PO-003',
                    tanggal_po: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    tanggal_kedatangan: '2020-01-01',
                    status: 'selesai',
                    persentase: 50
                },
                {
                    no_po: 'PO-004',
                    tanggal_po: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    tanggal_kedatangan: '2020-01-01',
                    status: 'selesai',
                }
            ],
        }
    },
    methods: {
        checkStatusForEdit(data) {
            if (data.status == 'menunggu permintaan' || data.status == 'proses po') {
                return true
            } else {
                return false
            }
        },
        moment(date){
            return moment(date).lang('id').format('LL')
        }
    },
}
</script>
<template>
    <div>
        <table class="table">
            <thead class="thead-light">
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
                <tr v-for="(data, index) in dataTables" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.no_po }}</td>
                    <td>{{ moment(data.tanggal_po) }}</td>
                    <td>{{ moment(data.estimasi_kedatangan) }}</td>
                    <td>{{ moment(data.tanggal_kedatangan) }}</td>
                    <td>
                        <status :status="data.status" :persentase="data.persentase" />
                    </td>
                    <td>
                        <button class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-warning btn-sm" v-if="checkStatusForEdit(data)">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>