<script>
import moment from 'moment'
export default {
    props: {
        dalamProses: {
            type: Array,
            default: () => []
        },
    },
    methods: {
        moment(date) {
            return moment(date).format('DD MMMM YYYY')
        },
        checked(idx) {
            this.$emit('checked', idx)
        },
        status(status) {
            switch (status) {
                case 'Barang Datang':
                    return 'badge badge-primary'
                case 'Menunggu Kedatangan':
                    return 'badge badge-warning'
                case 'Dalam Pemeriksaan':
                    return 'badge badge-info'
                default:
                    return 'badge badge-secondary'
            }
        },
        detail(idx){
            this.$emit('detail', idx)
        }
    },
}
</script>
<template>
    <div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th></th>
                    <th>No PO</th>
                    <th>Divisi</th>
                    <th>Supplier</th>
                    <th>Tanggal Diminta</th>
                    <th>Estimasi Kedatangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dalamProses.length > 0">
                <tr  v-for="(proses, idx) in dalamProses" :key="idx">
                    <td>
                        <input type="checkbox" :value="proses.no_po" ref="proses" @click="checked(idx)">
                    </td>
                    <td>
                        {{ proses.no_po }}
                    </td>
                    <td>
                        {{ proses.divisi }}
                    </td>
                    <td>
                        {{ proses.supplier }}
                    </td>
                    <td>
                        {{ moment(proses.tanggal_diminta) }}
                    </td>
                    <td>
                        {{ moment(proses.estimasi_datang) }}
                    </td>
                    <td>
                        <span :class="status(proses.status)">{{ proses.status }}</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" @click="detail(idx)">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>