<script>
import modalTable from './modalTable.vue'
import modalTerimaBarang from './terimaBarang/modalTerimaBarang.vue'
export default {
    props: {
        produks: {
            type: Array,
            default: () => []
        }
    },
    components: {
        modalTable,
        modalTerimaBarang
    },
    data() {
        return {
            produk_detail: ''
        }
    },
    methods: {
        detail(idx){
            this.produk_detail = this.produks[idx].nama_produk;
            setTimeout(() => {
            $('.modalDetail').modal('show');
            }, 100);
        },
        terimaBarang(idx){
            $('.modalTerimaBarang').modal('show');
        }
    },
    updated() {
        $('.tableDaftarBarang').DataTable({
            "destroy": true,
        });
    },
}
</script>
<template>
    <div>
        <modal-table :nama_produk="produk_detail"/>
        <modal-terima-barang />
        <table class="table text-center tableFrontDetail">
            <thead class="thead-light">
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(produk, index) in produks" :key="index">
                    <td>{{ produk.nama_produk }}</td>
                    <td>{{ produk.jumlah }}</td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" :style="{'width': `${produk.progress}%`}" :aria-valuenow="produk.progress" aria-valuemin="0" aria-valuemax="100">{{produk.progress}}%</div>
                        </div>
                        {{ produk.status }}
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" @click="terimaBarang(index)">
                            Terima dan Periksa
                        </button>
                        <button class="btn btn-outline-info btn-sm" @click="detail(index)">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>