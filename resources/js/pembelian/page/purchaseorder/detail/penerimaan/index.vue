<script>
import headers from './headers.vue'
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
    }
}
</script>
<template>
<div class="modal fade modalPermintaan" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terima Barang PO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                <input type="text" class="form-control" placeholder="No Surat Jalan">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="" class="col-5 text-right">Surat Jalan</label>
                            <div class="col-3">
                                <input type="file" name="" id="" class="form-control">
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
                            <td>{{ item.biaya_ongkir }}</td>
                            <td>{{ item.biaya_lain }}</td>
                        </tr>
                    </tbody>
                </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>
</template>