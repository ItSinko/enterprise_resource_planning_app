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
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No PO</th>
                    <th>Divisi</th>
                    <th>Supplier</th>
                    <th>Tanggal Diminta</th>
                    <th>Tanggal Kedatangan</th>
                    <th>Ekspedisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.no_po }}</td>
                    <td>{{ data.divisi }}</td>
                    <td>{{ data.supplier }}</td>
                    <td>{{ data.tanggal_diminta }}</td>
                    <td>{{ data.tanggal_kedatangan }}</td>
                    <td>{{ data.ekspedisi }}</td>
                    <td>
                        <button class="btn btn-outline-primary btn-sm" @click="detail(data.id)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>