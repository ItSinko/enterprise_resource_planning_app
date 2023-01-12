<script>
export default {
    props: {
        dataTables: {
            type: Array,
            default: () => []
        }
    },
    methods: {
        
    }
}
</script>
<template>
    <div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No PP</th>
                    <th>Jenis Barang</th>
                    <th>Tanggal Diminta</th>
                    <th>Tanggal Dibutuhkan</th>
                    <th>PP</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in dataTables" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.no_pp }}</td>
                    <td>{{ data.jenis_barang }}</td>
                    <td>{{ data.tanggal_diminta }}</td>
                    <td>{{ data.tanggal_dibutuhkan }}</td>
                    <td><button class="btn btn-warning btn-sm">
                        <i class="fa fa-file" aria-hidden="true"></i>
                        </button></td>
                    <td>{{ data.status }}</td>
                    <td>
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>