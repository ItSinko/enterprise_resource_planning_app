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
                    <th rowspan="3">No</th>
                    <th rowspan="3">Kode Part - Nama Part</th>
                    <th colspan="3">Jumlah</th>
                    <th rowspan="3">Keterangan</th>
                </tr>
                <tr>
                    <th>Diserahkan</th>
                    <th>OK</th>
                    <th>NOK</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>
                        {{ item.part  }}
                    </td>
                    <td>{{ item.jumlah_diserahkan }}</td>
                    <td>
                        <input class="form-control" type="text" @keypress="inputNumberOnly($event)" v-model.number="item.ok">
                    </td>
                    <td>
                        <input class="form-control" type="text" @keypress="inputNumberOnly($event)" v-model.number="item.nok">
                    </td>
                    <td>
                        <input type="text" class="form-control" v-model="item.keterangan">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>