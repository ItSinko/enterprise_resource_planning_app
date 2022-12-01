<script>
export default {
    props: {
        dataTable: {
            type: Array,
            default: () => []
        },
    },
    data() {
        return {
            partsSelected: [],
        }
    },
    methods: {
        checkedAll() {
            if (this.partsSelected.length == this.dataTable.length) {
                this.partsSelected = []
                this.$refs.checkedPackAll.checked = false
                this.$refs.checkedPack.forEach((item) => {
                    item.checked = false
                })
            } else {
                this.partsSelected = this.dataTable.map((item) => item.id)
                this.$refs.checkedPackAll.checked = true
                this.$refs.checkedPack.forEach((item) => {
                    item.checked = true
                })
            }
            this.$emit('checked', this.partsSelected)
        },
        checked(index){
            if (this.partsSelected.includes(this.dataTable[index])) {
                this.partsSelected.splice(this.partsSelected.indexOf(this.dataTable[index]), 1)
                this.$refs.checkedPack[index].checked = false
            } else {
                this.partsSelected.push(this.dataTable[index])
                this.$refs.checkedPack[index].checked = true
            }
            this.$emit('checked', this.partsSelected)
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
                    <td><input type="checkbox" @click="checked(index)" name="" id="" ref="checkedPack"></td>
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