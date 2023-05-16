<script>
import headers from './headers.vue'
import axios from 'axios'
export default {
    components: {
        headers
    },
    props: {
        header: {
            type: Object,
            required: true
        },
        permintaan : {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            surat_jalan: '',
            file: null,
        }
    },
    methods: {
        uploadFile(file) {
            this.file = file
        },
        async simpan() {
            const formData = new FormData()
            formData.append('po_pembelian_id', this.$route.params.id)
            formData.append('surat_jalan', this.surat_jalan)
            formData.append('file', this.file)
            formData.append('aset', JSON.stringify(this.permintaan))

            const { data } = await axios.post('/api/pembelian/po/terima', formData).then(res => res.data)
            if (data.status) {
                this.$router.push('/pembelian/po')
            }
        },
    }
}
</script>
<template>
<div class="modal fade modalPermintaan" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terima Barang PO</h5>
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <headers :headers="header"/>

                <div class="card">
                    <div class="card-body">
                        <h1 class="text-bold">Penerimaan Barang</h1>

                        <div class="my-5">
                        <div class="form-group row">
                            <label for="" class="col-5 text-right">No Surat Jalan</label>
                            <div class="col-3">
                                <input type="text" class="form-control" v-model="surat_jalan" placeholder="No Surat Jalan">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-5 text-right">Surat Jalan</label>
                            <div class="col-3">
                                <input type="file" class="form-control" @change="uploadFile($event.target.files[0])">
                            </div>
                        </div>
                        </div>

                        <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Biaya Ongkir</th>
                            <th>Biaya Lain</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in permintaan" :key="item.id">
                            <td>{{ item.nama_produk }}</td>
                            <td>{{ item.jumlah }}</td>
                            <td>{{ item.harga }}</td>
                            <td>{{ item.ongkir }}</td>
                            <td>{{ item.biaya_lain }}</td>
                        </tr>
                    </tbody>
                </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="close">Keluar</button>
                <button type="button" class="btn btn-primary" @click="simpan">Terima</button>
            </div>
        </div>
    </div>
</div>
</template>