<script>
    export default {
        props: {
            dataTable: {
                type: Array,
                default: () => []
            }
        },
        created() {
            const test = $('.table').DataTable()
            console.log(test)
        }
    }

</script>
<template>
    <div>
        <h5 class="text-bold">Riwayat Pembelian</h5>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No PO</th>
                    <th>Divisi</th>
                    <th>Tanggal Diminta</th>
                    <th>Estimasi Kedatangan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.no_po }}</td>
                    <td>{{ data.divisi }}</td>
                    <td>{{ data.tanggal_diminta }}</td>
                    <td>{{ data.estimasi_kedatangan }}</td>
                    <td>{{ data.status }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
