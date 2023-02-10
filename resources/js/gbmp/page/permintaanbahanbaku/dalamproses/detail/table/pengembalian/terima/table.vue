<script>
export default {
    data() {
        return {
            dataTable: [
                {
                    kode_part: 'P-001',
                    nama_part: 'Pasta Gigi',
                    jumlah_diserahkan: 22,
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
                    <th rowspan="4">No</th>
                    <th rowspan="4">Kode Part - Nama Part</th>
                    <th colspan="4">Jumlah</th>
                </tr>
                <tr>
                    <th>Diserahkan</th>
                    <th>OK</th>
                    <th>NOK</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.kode_part }} - {{ item.nama_part }}</td>
                    <td><input class="form-control" type="text" @keypress="inputNumberOnly($event)" v-model.number="item.jumlah_diserahkan"></td>
                    <td>
                        <input class="form-control" type="text" @keypress="inputNumberOnly($event)" v-model.number="item.jumlah_OK">
                    </td>
                    <td>
                        <input class="form-control" type="text" @keypress="inputNumberOnly($event)" v-model.number="item.jumlah_NOK">
                    </td>
                    <td>
                        <input class="form-control" type="text" v-model="item.keterangan">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>