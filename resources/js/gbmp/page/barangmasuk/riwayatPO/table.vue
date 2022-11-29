<script>
import moment from 'moment'
export default {
    props: {
        dataTable: {
            type: Array,
            default: () => []
        },
    },
    methods: {
        moment(date) {
            return moment(date).format('DD MMMM YYYY')
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
                    <th>No</th>
                    <th>No PO</th>
                    <th>Divisi</th>
                    <th>Supplier</th>
                    <th>Tanggal Diminta</th>
                    <th>Estimasi Kedatangan</th>
                    <th>Tanggal Kedatangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(item, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.no_po }}</td>
                    <td>{{ item.divisi }}</td>
                    <td>{{ item.supplier }}</td>
                    <td>{{ moment(item.tanggal_diminta) }}</td>
                    <td>{{ moment(item.estimasi_datang) }}</td>
                    <td>{{ moment(item.tanggal_datang) }}</td>
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