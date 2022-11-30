<script>
export default {
    props: {
        dataTable: {
            type: Array,
            default: () => []
        },
    }
}
</script>
<template>
    <div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(table, idx) in dataTable" :key="idx">
                    
                </tr>
            </tbody>
        </table>
    </div>
</template>