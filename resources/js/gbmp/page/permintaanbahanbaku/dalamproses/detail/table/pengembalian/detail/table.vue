<script>
export default {
    data() {
        return {
            dataTable: [
                {
                    kode_part: 'P-001',
                    nama_part: 'Pasta Gigi',
                    jumlah_OK: 22,
                    jumlah_NOK: 0,
                    keterangan: '-', 
                }
            ]
        }
    },
}
</script>
<template>
    <div>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2">Kode Part</th>
                    <th rowspan="2">Nama Part</th>
                    <th colspan="2">Jumlah</th>
                    <th rowspan="2">Keterangan</th>
                </tr>
                <tr>
                    <th>OK</th>
                    <th>NOK</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in dataTable" :key="index">
                    <td>{{ item.kode_part }}</td>
                    <td>{{ item.nama_part }}</td>
                    <td>
                        {{ item.jumlah_OK }}
                    </td>
                    <td>
                        {{ item.jumlah_NOK }}
                    </td>
                    <td>
                        {{ item.keterangan }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>