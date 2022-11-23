<script>
    import moment from 'moment'

    export default {
        props: {
            transfers: {
                type: Array,
                required: true,
                default: () => []
            }
        },
        methods: {
            moment(date) {
                return moment(date).format('DD MMMM YYYY')
            },

            divisiClass(status) {
                switch (status) {
                    case 'masuk':
                        return 'text-danger'
                    default:
                        return 'text-success'
                }
            },

            statusClass(status) {
                switch (status) {
                    case 'Draft':
                        return 'badge-info'
                    case 'Menunggu Permintaan':
                        return 'badge-warning'
                    case 'Selesai':
                        return 'badge-success'
                    default:
                        return 'badge-primary'
                }
            }
        },
    }

</script>
<template>
    <div>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No Transaksi</th>
                    <th>Divisi</th>
                    <th>Tanggal Transfer</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="transfers.length > 0">
                <tr v-for="(trf, idx) in transfers" :key="idx">
                    <td>{{ idx+1 }}</td>
                    <td>{{ trf.no_transfer }}</td>
                    <td :class="divisiClass(trf.ket)">{{ trf.divisi }}</td>
                    <td>{{ moment(trf.tanggal) }}</td>
                    <td>{{ trf.keterangan }}</td>
                    <td><span class="badge" :class="statusClass(trf.status)">{{ trf.status }}</span></td>
                    <td></td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="7">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
