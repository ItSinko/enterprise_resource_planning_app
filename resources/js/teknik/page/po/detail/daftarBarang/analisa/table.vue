<script>
export default {
    props: {
        dataTable: {
            type: Array,
            default: () => []
        }
    },
}
</script>
<template>
    <div>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2"></th>
                    <th rowspan="2">Nama Produk</th>
                    <th colspan="2">Jumlah</th>
                    <th colspan="2">Hasil</th>
                    <th rowspan="2">Permasalahan</th>
                    <th rowspan="2">Keterangan</th>
                </tr>
                <tr>
                    <th>Diterima</th>
                    <th>Sample</th>
                    <th>OK</th>
                    <th>NOK</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in dataTable" :key="index">
                    <td>
                        <img src="https://via.placeholder.com/50" alt="">
                    </td>
                    <td>{{ item.nama_produk }}</td>
                    <td>{{ item.jumlah_diterima }}</td>
                    <td>{{ item.jumlah_sample }}</td>
                    <td>{{ item.jumlah_ok }}</td>
                    <td>{{ item.jumlah_nok }}</td>
                    <td>{{ item.permasalahan }}</td>
                    <td>{{ item.keterangan }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>