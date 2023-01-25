<script>
import inputPrice from '../../../../../components/inputprice'
import mix from './mix'
export default {
    mixins: [mix],
    components: {
        inputPrice
    },
    props: {
        dataTable: {
            type: Array,
            default: () => []
        },
        sisa: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        handleDelete(index) {
            this.dataTable.splice(index, 1)
        },
        trash(index) {
            this.$emit('trash', index)
        },
        changeToIdr(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(value)
        },
        isNumber(event) {
            new RegExp('[0-9]').test(event.key) || event.preventDefault()
        }
    },
    computed: {
        calcTotalEstimasiHarga() {
            let total = 0
            this.dataTable.forEach(data => {
                total += data.estimasi_harga * data.jumlah
            })
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(total)
        },
    }
}
</script>
<template>
    <div>
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary"
            @click="$emit('add')"
            >
                <i class="fas fa-plus"></i>
                Tambah
            </button>
        </div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Merek</th>
                    <th>No Perkiraan</th>
                    <th>Supplier</th>
                    <th>Jumlah</th>
                    <th>Estimasi Harga</th>
                    <th>Pembelian Via</th>
                    <th>Link</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>               
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td><input type="text" class="form-control" v-model="data.merek"></td>
                    <td><input type="text" class="form-control" v-model="data.no_perkiraan"></td>
                    <td><input type="text" class="form-control" v-model="data.supplier"></td>
                    <td><input type="text" @keypress="isNumber($event)" v-model="data.jumlah" class="form-control"></td>
                    <td><input-price :nilai="data.estimasi_harga" v-model="data.estimasi_harga" /></td>
                    <td><v-select v-model="data.pembelian_via" :options="jenisPembelian"></v-select></td>
                    <td><input type="text" v-model="data.link" class="form-control" :disabled="validationLink(data.pembelian_via)"></td>
                    <td>
                        <i class="fa fa-minus" @click="trash(index)" style="color: red" aria-hidden="true"></i>
                    </td>
                </tr>
            </tbody>
            <tfoot class="table-active">
                <tr>
                    <td colspan="5" class="text-right text-bold">Total Pembelian</td>
                    <td class="text-bold" 
                    :class="sisa ? 'text-danger' : ''">
                    {{ calcTotalEstimasiHarga }}
                    </td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>