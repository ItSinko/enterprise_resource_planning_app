<template>
    <div>
        <div class="card mt-6">
            <div class="card-header">
                <div class="card-header-title is-uppercase">{{ jenis }}</div>
            </div>
            <div class="card-content">
                <div class="content field columns is-desktop">
                    <div class="column">
                        <table class="table spatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor SO</th>
                                    <th>Nomor PO</th>
                                    <th>Tanggal Order</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(penjualanspa, idx) in penjualanspas" :key="idx">
                                    <td>{{ idx + 1 }}</td>
                                    <td>{{ penjualanspa.so }}</td>
                                    <td>{{ penjualanspa.nopo }}</td>
                                    <td>{{ penjualanspa.tglpo }}</td>
                                    <td>{{ penjualanspa.nama_customer }}</td>
                                    <td v-html="status(penjualanspa.status_ppic)"></td>
                                    <td><button class="button is-info is-light is-small"  @click="detail(penjualanspa.pesanan.id, jenis, penjualanspa.status_ppic)" >Detail</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import mix from '../../mixins/mix';
import $ from "jquery";
export default {
    mixins: [mix],
    props: {
        penjualanspas: {
            type: Array,
            required: true
        },
        jenis: {
            type: String,
            required: true
        }
    },
    updated() {
        if(this.penjualanspas.length > 0) {
            $('.spatable').DataTable();
        }
    }
}
</script>