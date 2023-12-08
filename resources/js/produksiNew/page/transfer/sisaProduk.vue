<script>
import axios from 'axios'
export default {
    props: ['produk'],
    data() {
        return {
            detailProduk: null,
            keterangan: '',
            loading: false,
        }
    },
    methods: {
        closeModal() {
            $('.modalSisaProduk').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
                this.$emit('refresh')
            })
        },
        async getDetailProduk() {
            try {
                this.loading = true
                const { data: header } = await axios.get(`/api/prd/headerSeri/${this.produk.id}`)
                this.detailProduk = {
                    header,
                }
            } catch (error) {
                console.log(error)
            } finally {
                this.loading = false
            }
        },
        async simpan() {
            try {
                if (this.keterangan == '') {
                    this.$swal('Peringatan', 'Keterangan tidak boleh kosong', 'warning')
                    return
                }

                const { data } = await axios.post('/api/tfp/closeTransfer', {
                    jadwal_id: this.produk.id,
                    keterangan_transfer: this.keterangan,
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.closeModal()
            }
        }
    },
    created() {
        this.getDetailProduk()
    },
}
</script>
<template>
    <div class="modal fade modalSisaProduk" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer Sisa Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card" v-if="!loading">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Nomor BPPB</label>
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            <span id="no_bppb">{{ detailProduk?.header.bppb }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Nama Produk</label>
                                    <div class="card" style="background-color: #F89F81">
                                        <div class="card-body">
                                            <span id="produk">{{ detailProduk?.header.produk }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Kategori</label>
                                    <div class="card" style="background-color: #FCF9C4">
                                        <div class="card-body">
                                            <span id="kategori">{{ detailProduk?.header.kategori }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Jumlah Rakit</label>
                                    <div class="card" style="background-color: #FFCC83">
                                        <div class="card-body">
                                            <span id="jml">{{ detailProduk?.header.jumlah }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Tanggal Mulai</label>
                                    <div class="card" style="background-color: #FFE0B4">
                                        <div class="card-body">
                                            <span id="start">{{ detailProduk?.header.start }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Tanggal Selesai</label>
                                    <div class="card" style="background-color: #FFECB2">
                                        <div class="card-body">
                                            <span id="end">{{ detailProduk?.header.end }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="">Keterangan</label>
                                <textarea v-model="keterangan" cols="10" rows="10" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="spinner-border" role="status" v-else>
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" :disabled="loading" @click="simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>