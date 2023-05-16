<script>
import status from '../../../../../../components/status.vue'
export default {
    props: {
        dataTable: {
            type: Array,
            required: true
        },
    },
    components: {
        status
    },
    methods: {
        deleteBarang(id){
            this.$emit('deleteBarang', id)
        }
    },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="button btn btn-warning mb-2">
                <i class="fa fa-print" aria-hidden="true"></i>
                Cetak PP
            </div>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Estimasi Harga</th>
                        <th>Subtotal</th>
                        <th>Link</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, key) in dataTable" :key="key">
                        <td>{{ key + 1 }}</td>
                        <td>{{ item.nama }}</td>
                        <td>{{ item.jumlah }}</td>
                        <td>{{ formatRupiah(item.estimasi_harga) }}</td>
                        <td>{{ formatRupiah(item.subtotal) }}</td>
                        <td>
                            <a v-if="item.link != null" :href="item.link" target="_blank">{{ item.link }}</a>
                            <p v-else class="text-muted text-uppercase">offline</p>
                        </td>
                        <td>
                            <!-- <status :status="item.status" /> -->
                        </td>
                        <td>
                            <i class="fa fa-trash red" aria-hidden="true" @click="deleteBarang(item.id)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<style>
    .red {
        color: red;
    }
</style>