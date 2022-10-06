<template >
    <div>
        <div class="card mt-6">
            <div class="card-header">
                <div class="card-header-title is-uppercase">{{ jenis }}</div>
            </div>
            <div class="card-content">
                <div class="content field columns is-desktop">
                    <div class="column">
                        <table class="table penjualantable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor SO</th>
                                    <th>Nomor AKN</th>
                                    <th>Nomor PO</th>
                                    <th>Tanggal Buat</th>
                                    <th>Tanggal Edit</th>
                                    <th>Tanggal Delivery</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(penjualan, idx) in penjualans" :key="idx">
                                    <td>{{ idx+1 }}</td>
                                    <td>{{ penjualan.so }}</td>
                                    <td v-html="checkdata(akn(penjualan.no_paket_ppic, penjualan.status_paket))"></td>
                                    <td>{{ penjualan.nopo }}</td>
                                    <td>{{ penjualan.tgl_buat }}</td>
                                    <td>{{ checkdata(penjualan.tgl_edit) }}</td>
                                    <td v-html="checkdata(penjualan.tgl_kontrak)"></td>
                                    <td>{{ penjualan.nama_customer }}</td>
                                    <td v-html="status(penjualan.status_ppic)"></td>
                                    <td><button class="button is-info is-light is-small" @click="detail(penjualan.pesanan.id, penjualan.jenis_ppic, penjualan.status_ppic)">Detail</button></td>
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
    import $ from "jquery";
    import mixins from "../../mixins/mix";
    export default {
        mixins: [mixins],
        props: {
            penjualans: {
                type: Array,
                required: true
            },
            jenis: {
                type: String,
                required: true
            }
        },

        updated() {
            if(this.penjualans.length > 0){
                $('.penjualantable').DataTable();
            }
        }
    }
    </script>