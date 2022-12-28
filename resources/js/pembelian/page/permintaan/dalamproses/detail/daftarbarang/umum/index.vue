<script>
export default {
    props: {
        data: {
            type: Object,
            default: () => {
                return {}
            }
        },
    },
    data() {
        return {
            dataTableDummy: [
                {
                    id: 1,
                    nama_barang: 'Baju',
                    jumlah: 10,
                    estimasi_harga: 100000,
                    subtotal: 1000000,
                    pembelian: 'online',
                    link: 'https://google.com',
                    status: 'selesai',
                },
                {
                    id: 2,
                    nama_barang: 'Celana',
                    jumlah: 10,
                    estimasi_harga: 100000,
                    subtotal: 1000000,
                    pembelian: 'offline',
                    link: '',
                    status: 'proses',
                },
                {
                    id: 3,
                    nama_barang: 'Sepatu',
                    jumlah: 10,
                    estimasi_harga: 100000,
                    subtotal: 1000000,
                    pembelian: 'online',
                    link: 'https://google.com',
                    status: 'selesai',
                },
            ]
        }
    },
    methods: {
        formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
            }).format(value)
        },
        generateStatusHTML(status){
            switch (status) {
                case 'selesai':
                    return `<span class="badge badge-success">${status}</span>`
                case 'proses':
                    return `<span class="badge badge-warning">${status}</span>`
                case 'batal':
                    return `<span class="badge badge-danger">${status}</span>`
                default:
                    return `<span class="badge badge-secondary">${status}</span>`
            }
        },
        deleteBarang(id){
            this.$emit('deleteBarang', id)
        }
    },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="button btn btn-warning mb-2">
                <i class="fa fa-print" aria-hidden="true"></i>
                Cetak PP
            </div>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Estimasi Harga</th>
                        <th>Subtotal</th>
                        <th>Link</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in dataTableDummy" :key="item.no">
                        <td>{{ item.no }}</td>
                        <td>{{ item.nama_barang }}</td>
                        <td>{{ item.jumlah }}</td>
                        <td>{{ formatCurrency(item.estimasi_harga) }}</td>
                        <td>{{ formatCurrency(item.subtotal) }}</td>
                        <td>
                            <a v-if="item.pembelian == 'online'" :href="item.link" target="_blank">{{ item.link }}</a>
                            <p v-else class="text-muted text-uppercase">offline</p>
                        </td>
                        <td v-html="generateStatusHTML(item.status)"></td>
                        <td>
                            <i class="fa fa-trash red" aria-hidden="true" @click="deleteBarang(item.id)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<style>
    .red {
        color: red;
    }
</style>