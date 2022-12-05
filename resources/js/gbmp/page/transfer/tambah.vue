<script>
    import axios from 'axios'
    import moment from 'moment'
    export default {
        data() {
            return {
                form: {
                    no_transaksi: '',
                    divisi: '',
                    keterangan: '',
                },
                barangs: [{
                    kode_nama: null,
                    lot: null,
                    data_lot: [],
                    jumlah: null,
                }],
                divisions: [],
                parts: [],
                divisi: null
            }
        },
        created() {
            this.getDivisi()
        },
        methods: {
            async getDivisi() {
                try {
                    const getDivisi = localStorage.getItem('divisi')
                    this.divisi = getDivisi
                    const {
                        data
                    } = await axios.get('/api/gbj/sel-divisi')
                    this.divisions = data.map((item) => {
                        return {
                            value: item.id,
                            label: item.nama
                        }
                    })
                    const {
                        parts
                    } = await axios.get(`/api/part/divisi/${getDivisi}/0`).then(res => res.data)
                    this.parts = parts

                } catch (error) {
                    return error
                }
            },

            removepart(index) {
                this.barangs.splice(index, 1);
            },
            addpart() {
                this.barangs.push({
                    kode_nama: null,
                    lot: null,
                    data_lot: [],
                    jumlah: null,
                });
            },
            calcStok(stok, jml) {
                const stokBaru = stok - jml
                if (stokBaru < 0) {
                    return true
                } else {
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
            async getLot(id, index) {
                const {
                    parts
                } = await axios.get(`/api/part/divisi/${this.divisi}/${id.value}`).then(res => res.data)
                this.barangs[index].data_lot = parts
            },
            async save() {
                const kirim = {
                    no_transaksi: this.form.no_transaksi,
                    divisifrom: this.divisi,
                    divisitransfer: this.form.divisi.value,
                    tanggal: moment(new Date()).format('YYYY-MM-DD'),
                    keterangan: this.form.keterangan,
                    barangs: this.barangs,
                    status: 'draft'
                }
                const kirimIsNotValid = Object.values(kirim).some((item) => item === null || item === '') || 
                Object.values(kirim.barangs).some((item) => 
                item.kode_nama === null || item.kode_nama === '' || 
                item.lot === null || item.lot === '' || 
                item.jumlah === null || item.jumlah === '') ||
                Object.values(kirim.barangs).some((item) => this.calcStok(item.lot.stok, item.jumlah))
                if (kirimIsNotValid) {
                    return alert('Silahkan input data dengan benar')
                }else{
                    const { data } = await axios.post('/api/gbmp/store', kirim)
                    console.log(data)
                }
                
            }
        }
    }

</script>
<template>
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
                                            <v-select :options="divisions" v-model="form.divisi"></v-select>
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
                                                    <v-select :options="parts" v-model="barang.kode_nama"
                                                        @input="getLot(barang.kode_nama, idx)"></v-select>
                                                </td>
                                                <td>
                                                    <v-select :options="barang.data_lot" v-model="barang.lot">
                                                    </v-select>
                                                    <span class="text-danger text-center" v-if="barang.lot">
                                                        <div v-if="calcStok(barang.lot.stok, barang.jumlah)">
                                                            Stok saat ini {{ barang.lot.stok }}
                                                        </div>
                                                    </span>
                                                </td>
                                                <td><input @keypress="isNumber($event)" type="text" class="form-control"
                                                        v-model.number=" barang.jumlah"></td>
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
                    <button type="button" class="btn btn-primary" @click="save">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>
