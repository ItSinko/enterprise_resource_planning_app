<script>
export default {
    props: ['dataTable'],
    methods: {
        setKardus(id) {
            this.$router.push({
                name: 'detailPembagian',
                params: {
                    id
                }
            });
        }
    },
}
</script>
<template>
    <div>
        <table class="table text-center">
            <thead>
                <tr>
                    <th rowspan="2">No Urut</th>
                    <th rowspan="2">Nama Produk</th>
                    <th colspan="2">Jumlah</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Selesai</th>
                    <th>Belum Selesai</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, idx) in dataTable" :key="idx">
                    <td>{{ data.urutan }}</td>
                    <td>{{ data.nama }}</td>
                    <td>{{ data.sudah }}</td>
                    <td>{{ data.belum }}</td>
                    <td>
                        <button class="btn btn-outline-primary btn-sm" @click="setKardus(data.id)">Set Kardus</button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="100%" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>