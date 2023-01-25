<script>
import mix from './mix'
export default {
    mixins: [mix],
    data() {
        return {
            dataTable: [
                {
                    id: 1,
                    nama_barang: 'Buku',
                    jumlah: 10,
                    estimasi_harga: 10000,
                    link: 'https://www.google.com',
                    status: 'selesai'
                },
                {
                    id: 2,
                    nama_barang: 'Buku',
                    jumlah: 10,
                    estimasi_harga: 10000,
                    link: 'https://www.google.com',
                    status: 'proses'
                },
                {
                    id: 3,
                    nama_barang: 'Buku',
                    jumlah: 10,
                    estimasi_harga: 10000,
                    link: '',
                    status: 'selesai'
                },
            ],
        }
    },
        methods: {
        status(status) {
            switch (status) {
                case 'selesai':
                    return '<span class="badge badge-success">Selesai</span>'
                case 'proses':
                    return '<span class="badge badge-warning">Proses</span>'
                case 'batal':
                    return '<span class="badge badge-danger">Batal</span>'
                default:
                    return `<span class="badge badge-secondary">${status}</span>`
            }
        }
    },
}
</script>
<template>
    <div>
        <button class="btn btn-primary">
            <i class="fa fa-plus"></i>
            <span>Buat Purchase Order</span>
        </button>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th><input type="checkbox" name="" id=""></th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Estimasi Harga</th>
                    <th>Subtotal</th>
                    <th>Link</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="data in dataTable" :key="data.id">
                    <td><input type="checkbox" name="" id=""></td>
                    <td>{{ data.nama_barang }}</td>
                    <td>{{ data.jumlah }}</td>
                    <td>{{ formatRupiah(data.estimasi_harga) }}</td>
                    <td>{{ formatRupiah(data.jumlah * data.estimasi_harga) }}</td>
                    <td>
                        <a v-if="data.link" :href="data.link" target="_blank">{{ data.link }}</a>
                        <span v-else class="text-muted">OFFLINE</span>
                    </td>
                    <td v-html="status(data.status)"></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>