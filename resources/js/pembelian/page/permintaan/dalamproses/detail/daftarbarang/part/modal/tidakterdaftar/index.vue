<script>
import mix from '../mix'
export default {
    mixins: [mix],
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
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in dataTableDummy" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.nama }}</td>
                    <td>{{ item.jumlah }}</td>
                    <td>{{ formatRupiah(item.estimasiHarga) }}</td>
                    <td>{{ subtotal(item.jumlah, item.estimasiHarga) }}</td>
                    <td v-html="generateStatusHTML(item.status)"></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>