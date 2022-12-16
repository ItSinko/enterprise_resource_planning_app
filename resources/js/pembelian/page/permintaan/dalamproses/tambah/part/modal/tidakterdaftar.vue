<script>
import inputprice from '../../../../../../components/inputprice.vue'
import mix from './mix'
export default {
    mixins: [mix],
    components: {
        inputprice
    },
    data() {
        return {
            formPartNotRegistered: [
                {
                    nama: null,
                    jumlah: 0,
                    harga: 0,
                }
            ]
        }
    },
    methods: {
            tambahPart() {
        this.formPartNotRegistered.push({
            nama: null,
            jumlah: 0,
            harga: 0,
        })
    },
    hapus(idx) {
        this.formPartNotRegistered.splice(idx, 1)
    },
    }
}
</script>
<template>
    <div>
        <div class="d-flex justify-content-end bd-highlight mb-3">
            <div class="p-2 bd-highlight">
                <button class="btn btn-primary btn-sm" @click="tambahPart">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Tambah Part Baru
                </button>
            </div>
        </div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, idx) in formPartNotRegistered" :key="idx">
                    <td>{{ idx + 1 }}</td>
                    <td>
                        <input type="text" class="form-control" v-model="item.nama">
                    </td>
                    <td>
                        <input type="text" class="form-control" v-model="item.jumlah" @keypress="onlyNumber($event)">
                    </td>
                    <td>
                        <inputprice :nilai="item.harga" v-model="item.harga" @keypress="onlyNumber($event)">
                        </inputprice>
                    </td>
                    <td>
                        {{ formatRupiah(subtotal(item.harga, item.jumlah)) }}
                    </td>
                    <td>
                        <i class="fa fa-minus" aria-hidden="true" @click="hapus(idx)" style="color: red"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>