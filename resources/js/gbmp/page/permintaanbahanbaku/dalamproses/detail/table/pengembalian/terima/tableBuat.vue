<script>
export default {
    props: {
        dataTable: {
            type: Array,
            default: () => []
        }
    },
    methods: {
        deleteRow(index) {
            this.dataTable.splice(index, 1)
        },
    },
}
</script>
<template>
    <div>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Kode Part - Nama Part</th>
                    <th colspan="2">Jumlah</th>
                    <th rowspan="2">Keterangan</th>
                    <th rowspan="2">Aksi</th>

                </tr>
                <tr>
                    <th>Diserahkan</th>
                    <th>Kembali</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>
                        <v-select v-model="item.part" />
                    </td>
                    <td>{{ item.jumlah_diserahkan }}</td>
                    <td>
                        <input class="form-control" type="text" @keypress="inputNumberOnly($event)" v-model.number="item.jumlah_kembali">
                    </td>
                    <td>
                        <input type="text" class="form-control" v-model="item.keterangan">
                    </td>
                    <td>
                        <i class="fa fa-minus text-danger" aria-hidden="true" @click="deleteRow(index)"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>