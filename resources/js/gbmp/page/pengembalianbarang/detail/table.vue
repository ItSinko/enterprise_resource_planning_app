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
                    <th>Kode Part</th>
                    <th>Nama Part</th>
                    <th>Jumlah Kembali</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(detail, index) in dataTable" :key="index">
                    <td>{{ detail.kode_part }}</td>
                    <td>{{ detail.nama_part }}</td>
                    <td>{{ detail.jumlah_kembali }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>