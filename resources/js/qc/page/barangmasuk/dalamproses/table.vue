<script>
export default {
    props: {
        dataTable: {
            type: Array,
            default: () => []
        },
        selectTable: {
            type: Array,
            default: () => []
        },

    },
    methods: {
        getBadgeColor(){
            const color = [
                "badge badge-success",
                "badge badge-danger",
                "badge badge-warning",
                "badge badge-info",
                "badge badge-primary",
                "badge badge-secondary",
                "badge badge-dark",
                "badge badge-light",
            ]
            const random = Math.floor(Math.random() * color.length)
            return color[random]
        },
        checked(index){
            if(this.selectTable.includes(index)){
                this.selectTable = this.selectTable.filter(item => item !== index)
            }else{
                this.selectTable.push(this.dataTable[index])
            }
        },
    },
}
</script>
<template>
    <div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th></th>
                    <th>No PO</th>
                    <th>Divisi</th>
                    <th>Supplier</th>
                    <th>Tanggal Diminta</th>
                    <th>Estimasi Kedatangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(proses, index) in dataTable" :key="index">
                    <td><input type="checkbox" ref="proses" @click="checked(index)"></td>
                    <td>{{proses.no_po}}</td>
                    <td>{{proses.divisi}}</td>
                    <td>{{proses.supplier}}</td>
                    <td>{{formatTanggal(proses.tanggal_diminta)}}</td>
                    <td>{{formatTanggal(proses.estimasi_kedatangan)}}</td>
                    <td>
                        <span :class="getBadgeColor()">{{proses.status}}</span>
                    </td>
                    <td>
                        <button class="btn btn-outline-info">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>