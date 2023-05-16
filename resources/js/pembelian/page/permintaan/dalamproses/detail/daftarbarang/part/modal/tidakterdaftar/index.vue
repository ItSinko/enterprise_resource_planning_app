<script>
import mix from '../mix'
import status from '../../../../../../../../components/status.vue';
export default {
    mixins: [mix],
    components: {
        status
    },
    props: {
        dataTable: {
            type: Array,
            required: true,
            default: () => []
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
                    <th>Nama Sparepart</th>
                    <th>Jumlah</th>
                    <th>Estimasi Harga</th>
                    <th>Subtotal</th>
                    <!-- <th>Status</th> -->
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.nama }}</td>
                    <td>{{ item.jumlah }}</td>
                    <td>{{ formatRupiah(item.harga) }}</td>
                    <td>{{ subtotal(item.jumlah, item.harga) }}</td>
                    <!-- <td>
                        <status :status="item.status" />
                    </td> -->
                </tr>
            </tbody>
        </table>
    </div>
</template>