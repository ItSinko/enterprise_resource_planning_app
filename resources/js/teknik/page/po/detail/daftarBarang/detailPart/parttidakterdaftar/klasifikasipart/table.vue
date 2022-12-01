<script>
export default {
    props: {
        dataTable: {
            type: Array,
            default: () => []
        },
    },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-bold">Klasifikasi Part</h4>
            <div class="card-text">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Packing List</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dataTable" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ item.nama_packing_list }}</td>
                            <td>{{ item.jumlah_pemesanan }}</td>
                            <td>
                                <i class="fas fa-edit text-warning"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>