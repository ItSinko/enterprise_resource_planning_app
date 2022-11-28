<script>
    export default {
        data() {
            return {
                form: {
                    no_transaksi: '',
                    divisi: '',
                    keterangan: '',
                },
                barangs: [{
                    'kode_nama': {
                        'value': '',
                        'label': 'SPC-0001 - Solar'
                    },
                    'lot': {
                        'value': '',
                        'label': 'LOT-0001',
                        'stok': 20
                    },
                    'jumlah': 10,
                }],
            }
        },
        methods: {
            removepart(index) {
                this.barangs.splice(index, 1);
            },
            addpart() {
                this.barangs.push({
                    'kode_nama': {
                        'value': '',
                        'label': 'SPC-0001 - Solar'
                    },
                    'lot': {
                        'value': '',
                        'label': 'LOT-0001'
                    },
                    'jumlah': 10,
                });
            },
            calcStok(stok, jml){
                const stokBaru = stok - jml
                if(stokBaru < 0){
                    return true
                }else{
                    return false
                }
            },
            isNumber(evt) {
            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (
                charCode > 31 &&
                (charCode < 48 || charCode > 57) &&
                charCode !== 46
            ) {
                evt.preventDefault();
            } else {
                return true;
            }
        },
        }
    }

</script>
<template>
    <div>

        <!-- Modal -->
        <div class="modal fade modalAddTransfer" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Transfer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="text-bold">Detail Transfer</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="card-text">
                                            <div class="form-group">
                                                <label for="">No Transaksi</label>
                                                <input type="text" class="form-control" v-model="form.no_transaksi">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Divisi</label>
                                                <input type="text" class="form-control" v-model="form.divisi">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Keterangan</label>
                                                <textarea class="form-control" v-model="form.keterangan" cols="5"
                                                    rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="text-bold">Daftar Barang</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex mb-1 justify-content-end">
                                            <button class="btn btn-primary" @click="addpart">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                Tambah Part
                                            </button>
                                        </div>
                                        <table class="table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode & Nama Part</th>
                                                    <th>Lot Number</th>
                                                    <th class="col-2">Jumlah Part</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(barang, idx) in barangs" :key="idx">
                                                    <td>{{ idx + 1 }}</td>
                                                    <td>
                                                        <v-select v-model="barang.kode_nama"></v-select>
                                                    </td>
                                                    <td>
                                                        <v-select v-model="barang.lot"></v-select>
                                                        <span class="text-danger text-center" v-if="calcStok(barang.lot.stok, barang.jumlah)">
                                                            Stok saat ini {{ barang.lot.stok }}
                                                        </span>
                                                    </td>
                                                    <td><input @keypress="isNumber($event)" type="text" class="form-control"
                                                            v-model=" barang.jumlah"></td>
                                                    <td>
                                                        <i class="fa fa-minus" style="color: red;" aria-hidden="true"
                                                            @click="removepart(idx)"></i>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                        <button type="button" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
