<script>
import Detail from './detail'
export default {
    components: {
        Detail
    },
    props: {
        dataTable: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            modal: false,
            detailSelected: null,
        }
    },
    methods: {
        detail(idx){
            this.detailSelected = this.dataTable[idx]
            this.modal = true
            this.$nextTick(() => {
                $('.modalPartDetail').modal('show')
            })
        },
        hapus(idx){
            this.dataTable.splice(idx, 1)
        },
        tambahdraft(data){
            let index = this.dataTable.findIndex(item => item.kode_part == data.kode_part)
            this.$emit('tambahdraft', data, index)
            this.modal = false
            this.$nextTick(() => {
                $('.modalPartDetail').modal('hide')
            })
        },
        closeModalDetail(){
            this.modal = false
            $('.modalPartDetail').modal('hide')

        },
    }
}
</script>
<template>
    <div>
        <detail v-if="modal" 
        :dataTable="detailSelected" 
        @tambahdraft="tambahdraft"
        @close="closeModalDetail" />
        />
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Kode Part - Nama Part</th>
                    <th>Jumlah Diminta</th>
                    <th>Jumlah Diserahkan</th>
                    <th>Detail</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>
                        <v-select v-model="item.part" />
                    </td>
                    <td>{{ item.jumlah_diminta }}</td>
                    <td><input type="text" v-model.number="item.jumlah_diserahkan" @keypress="inputNumberOnly($event)" class="form-control"></td>
                    <td>
                        <i class="fa fa-eye text-info" aria-hidden="true" @click="detail(index)"></i>
                    </td>
                    <td>
                        <i class="fa fa-minus text-danger" aria-hidden="true" @click="hapus(index)"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>