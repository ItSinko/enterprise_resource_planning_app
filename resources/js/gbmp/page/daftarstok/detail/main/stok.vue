<script>
export default {
    data() {
        return {
            search: '',
            dataTable: [
                {
                    lot_number: 'A5001',
                    tgl_kedatangan: '2020-01-01',
                    jumlah_ok: 100,
                    jumlah_nok: 10,
                }
            ]
        }
    },
    computed: {
        filteredDataTable() {
            return this.dataTable.filter(data => {
                return Object.keys(data).some(key => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        }
    },
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari">
            </div>
        </div>

        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Lot Number</th>
                    <th rowspan="2">Tanggal Kedatangan</th>
                    <th colspan="2">Jumlah</th>
                </tr>
                <tr>
                    <th>OK</th>
                    <th>NOK</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in filteredDataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.lot_number }}</td>
                    <td>{{ formatTanggal(data.tgl_kedatangan) }}</td>
                    <td>{{ data.jumlah_ok }}</td>
                    <td>{{ data.jumlah_nok }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>