<script>
import mix from './mix'
import status from '../../../../../components/status'
export default {
    components: {
        status
    },
    props: {
        id: {
            type: Number,
            required: true
        }
    },
    mixins: [mix],
    data() {
        return {
            dataTable: [
                {
                    id: 1,
                    nama_barang: 'Buku',
                    jumlah: 10,
                    estimasi_harga: 10000,
                    link: 'https://www.google.com',
                    status: 'selesai'
                },
                {
                    id: 2,
                    nama_barang: 'Buku',
                    jumlah: 10,
                    estimasi_harga: 10000,
                    link: 'https://www.google.com',
                    status: 'proses'
                },
                {
                    id: 3,
                    nama_barang: 'Buku',
                    jumlah: 10,
                    estimasi_harga: 10000,
                    link: '',
                    status: 'selesai'
                },
            ],
            dataTableSelected: []
        }
    },
    methods: {
        checkAll(){
            this.dataTableSelected != this.dataTable ? this.dataTableSelected = this.dataTable : this.dataTableSelected = []
            this.$refs.cbChild.forEach(cb => {
                cb.checked = !cb.checked
            })
        },
        checkOne(index){
            if(this.$refs.cbChild[index].checked){
                this.dataTableSelected.push(this.dataTable[index])
            }else{
                this.dataTableSelected = this.dataTableSelected.filter(data => data.id != this.dataTable[index].id)
            }
        },
        addPO() {
            this.$router.push({
                name: 'purchaseorderCreate',
                params: {
                    id: this.id,
                    open: 'dalamproses',
                    dataSelected: this.dataTableSelected
                },
            })
            $('.modalDetail').modal('hide')
        }
    },
}
</script>
<template>
    <div>
        <button class="btn btn-primary" @click="addPO">
            <i class="fa fa-plus"></i>
            <span>Buat Purchase Order</span>
        </button>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th><input type="checkbox" @click="checkAll"></th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Estimasi Harga</th>
                    <th>Subtotal</th>
                    <th>Link</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in dataTable" :key="data.id">
                    <td><input type="checkbox" ref="cbChild" @click="checkOne(index)"></td>
                    <td>{{ data.nama_barang }}</td>
                    <td>{{ data.jumlah }}</td>
                    <td>{{ formatRupiah(data.estimasi_harga) }}</td>
                    <td>{{ formatRupiah(data.jumlah * data.estimasi_harga) }}</td>
                    <td>
                        <a v-if="data.link" :href="data.link" target="_blank">{{ data.link }}</a>
                        <span v-else class="text-muted">OFFLINE</span>
                    </td>
                    <td>
                        <status :status="data.status" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>