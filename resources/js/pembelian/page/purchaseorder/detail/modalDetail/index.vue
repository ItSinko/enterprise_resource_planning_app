<script>
    import status from '../../../../components/status.vue'
    export default {
        components: {
            status
        },
        props: {
            kurs: {
                type: String,
                required: true
            },
            dataTables: {
                type: Array,
                required: true
            }
        },
        methods: {
            subtotal(item) {
                return item.jumlah * item.harga + item.ongkir + item.biaya_lain
            },
            formatHarga(price, kurs) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: kurs
                }).format(price)
            },
            closeModal() {
                this.$emit('close')
            }
        }
    }

</script>
<template>
    <div class="modal fade modalDetailPO" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail PO</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                                        <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>Kode & Nama Part</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Ongkir</th>
                                <th>Biaya Lain</th>
                                <th>Subtotal</th>
                                <th>Konversi</th>
                                <!-- <th>Status</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(datatable, idx) in dataTables" :key="idx">
                                <td>{{ datatable.nama }}</td>
                                <td>{{ datatable.jumlah }}</td>
                                <td>{{ formatHarga(datatable.harga, kurs) }}</td>
                                <td>{{ formatHarga(datatable.ongkir, kurs) }}</td>
                                <td>{{ formatHarga(datatable.biaya_lain, kurs) }}</td>
                                <td>{{ formatHarga(subtotal(datatable), kurs) }}</td>
                                <td>{{ formatHarga(datatable.konversi, 'idr') }}</td>
                                <td>
                                    <!-- <status :status="datatable.status"></status> -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
