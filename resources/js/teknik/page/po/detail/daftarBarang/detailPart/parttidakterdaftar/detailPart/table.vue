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
            <h4 class="card-title">Daftar Part</h4>
            <div class="card-text mt-5">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2"></th>
                            <th rowspan="2">Nama Part</th>
                            <th rowspan="2">Dipesan</th>
                            <th colspan="2">Gudang</th>
                            <th rowspan="2">Sample</th>
                            <th rowspan="2">OK</th>
                        </tr>
                        <tr>
                            <th>Diterima</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dataTable" :key="index">
                            <td><img src="https://picsum.photos/20" alt=""></td>
                            <td>{{ item.nama_part }}</td>
                            <td>{{ item.dipesan }}</td>
                            <td>{{ item.diterima }}</td>
                            <td>{{ item.keterangan }}</td>
                            <td>{{ item.sample }}</td>
                            <td>{{ item.ok }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>