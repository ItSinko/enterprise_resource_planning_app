<script>
export default {
    data() {
        return {
            divisi: parseInt(localStorage.getItem('divisi')),
            dataTable: [
                {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    status: 'diterima',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    status: 'diterima',
                }
            ],
        }
    },
    methods: {
        checkAll() {
            this.$refs.child.forEach(item => {
                item.checked = !item.checked
            })
        }
    },
}
</script>
<template>
    <div>
        <button class="btn btn-info">
            <i class="fa fa-check"></i>
            Tambah Penerimaan
        </button>
        <table class="table text-center mt-1">
            <thead class="thead-light">
                <tr>
                    <th v-if="divisi === 7"><input type="checkbox" @click="checkAll"></th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(table, index) in dataTable" :key="index">
                    <td v-if="divisi === 7"><input type="checkbox" ref="child" v-if="divisi === 7"></td>
                    <td>{{ table.nama_produk }}</td>
                    <td>{{ table.jumlah }}</td>
                    <td>{{ table.status }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>