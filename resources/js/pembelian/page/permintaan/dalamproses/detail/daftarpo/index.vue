<script>
import Status from '../../../../../components/status.vue'
export default {
    components: {
        Status
    },
    props: {
        dataTable: {
            type: Array,
            default: () => {
                return []
            }
        },
    },
    methods: {
        del(id) {
            this.$emit('del', id)
        },
        terima(id) {
            this.$emit('terima', id)
        }
    }
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
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(item, key) in dataTable" :key="key+ 'daftarpo'">
                    <td>{{ key+1 }}</td>
                    <td>{{ item.no_po }}</td>
                    <td>{{ formatTanggal(item.tgl_po) }}</td>
                    <td>{{ formatTanggal(item.tgl_estimasi_datang) }}</td>
                    <td>{{ formatTanggal(item.tgl_datang) }}</td>
                    <td>
                        <button class="btn btn-sm btn-success" v-if="item.status == 'terima barang'" @click="terima(item.id)">Terima Barang</button>
                        <span v-else>
                            <status :status="item.status" />
                        </span>
                    </td>
                    <td>
                        <i class="fa fa-trash red" aria-hidden="true" @click="del(item.id)"></i>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>