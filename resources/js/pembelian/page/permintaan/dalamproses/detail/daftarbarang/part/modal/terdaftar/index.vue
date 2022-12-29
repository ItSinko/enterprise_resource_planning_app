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
    data() {
        return {
            dataTableDummy: [
                {
                    nama: 'Sparepart 1',
                    jumlah: 10,
                    estimasiHarga: 100000,
                    status: 'selesai',
                },
                {
                    nama: 'Sparepart 2',
                    jumlah: 20,
                    estimasiHarga: 200000,
                    status: 'proses',
                }
            ],
        }
    },
    methods: {
        formatRupiah(currency){
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(currency)
        },
        subtotal(jumlah, estimasiHarga) {
            return this.formatRupiah(jumlah * estimasiHarga)
        },
        generateStatusHTML(status) {
            if (status === 'selesai') {
                return `<span class="badge badge-success text-capitalize">${status}</span>`
            } else {
                return `<span class="badge badge-warning text-capitalize">${status}</span>`
            }
        },
    }
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