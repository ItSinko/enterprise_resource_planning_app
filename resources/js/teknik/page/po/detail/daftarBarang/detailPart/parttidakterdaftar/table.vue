<script>
export default {
    props: {
        dataTable: {
            type: Array,
            default: () => []
        },
    },
    methods: {
        checkedAll() {
            this.dataTable.forEach((row) => {
                row.checked = this.checked
            })
        }
    }
}
</script>
<template>
    <div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th><input @click="checkedAll" type="checkbox" name="" id="" ref="checkedPackAll"></th>
                    <th>Nama Packing List</th>
                    <th>Jumlah Pemesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(item, index) in dataTable" :key="index">
                    <td><input type="checkbox" name="" id="" ref="checkedPack"></td>
                    <td>{{ item.nama_packing_list }}</td>
                    <td>{{ item.jumlah_pemesanan }}</td>
                    <td>
                        <button class="btn btn-outline-info btn-sm">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>