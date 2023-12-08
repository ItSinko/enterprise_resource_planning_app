<script>
import axios from 'axios'
import pagination from '../../components/pagination.vue'
import seriviatext from '../../../gbj/page/PermintaanReworkGBJ/permintaan/formPermintaan/seriviatext.vue'
export default {
    props: ['produk'],
    components: {
        pagination,
        seriviatext,
    },
    data() {
        return {
            detailProduk: null,
            headerSeri: [
                { text: 'id', value: 'id', align: 'text-left', sortable: false },
                { text: 'Nomor Seri', value: 'noseri', align: 'text-left' },
            ],
            loading: false,
            tanggal_kirim: new Date().toISOString().substr(0, 10),
            search: '',
            renderPaginate: [],
            checkAll: false,
            showmodalviatext: false,
            noSeriSelected: [],
        }
    },
    methods: {
        closeModal() {
            $('.modalTransfer').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        async getDetailProduk() {
            try {
                this.loading = true
                const { data: header } = await axios.get(`/api/prd/headerSeri/${this.produk.id}`)
                const { data: seri } = await axios.get(`/api/prd/detailSeri1/${this.produk.produk_id}/${this.produk.id}`)
                this.detailProduk = {
                    header,
                    seri
                }
            } catch (error) {
                console.log(error)
            } finally {
                this.loading = false
            }
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        simpan() {
            if (this.noSeriSelected.length === 0) {
                this.$swal('Peringatan', 'Pilih nomor seri terlebih dahulu', 'warning')
                return
            }

            const today = new Date()
            const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds()

            let form = {
                qty: this.detailProduk?.header.jumlah,
                gbj_id: this.produk.produk_id,
                jadwal_id: this.produk.id,
                noseri: this.noSeriSelected,
                tgl_transfer: `${this.tanggal_kirim} ${time}`,
            }

            axios.post('/api/prd/send', form, {
                headers: {
                    'Authorization': `Bearer` + localStorage.getItem('lokal_token'),

                }

            })
                .then((response) => {
                    this.$swal('Berhasil', 'Berhasil Transfer', 'success')
                    this.closeModal()
                    this.$emit('refresh')

                })
                .catch((error) => {
                    console.log(error)
                    this.$swal('Gagal', 'Gagal Transfer', 'error')
                })
        },
        selectNoSeri(noseri) {
            if (this.noSeriSelected.find((item) => item === noseri)) {
                this.noSeriSelected = this.noSeriSelected.filter((item) => item !== noseri)
                this.checkAll = false
            } else {
                this.noSeriSelected.push(noseri)
            }

            if (this.noSeriSelected.length === this.detailProduk.seri.length) {
                this.checkAll = true
            }
        },
        checkAllSeri() {
            this.checkAll = !this.checkAll
            if (this.checkAll) {
                this.noSeriSelected = this.detailProduk.seri.map((item) => item.noseri)
            } else {
                this.noSeriSelected = []
            }
        },
        submit(noseri) {
            let noserinotfound = []

            let noseriarray = noseri.split(/[\n, \t]/)

            // remove empty string
            noseriarray = noseriarray.filter((data) => data !== "")

            // remove duplicate
            noseriarray = [...new Set(noseriarray)]

            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.detailProduk?.seri.length; j++) {
                    if (noseriarray[i] === this.detailProduk?.seri[j].noseri) {
                        if (!this.noSeriSelected.find((data) => data === noseriarray[i])) {
                            this.noSeriSelected.push(this.detailProduk?.seri[j].noseri)
                        }
                        found = true
                        break
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }

            if (this.noSeriSelected.length == this.detailProduk?.seri.length) {
                this.checkAll = true
            } else {
                this.checkAll = false
            }

            noserinotfound = [...new Set(noserinotfound)]

            if (noserinotfound.length > 0 && noserinotfound != "") {
                this.$swal('Peringatan', `Nomor Seri ${noserinotfound.join(', ')} tidx1ak ditemukan`, 'warning')
            }
        },
        showSeriText() {
            this.showmodalviatext = true
            $('.modalTransfer').modal('hide')
            this.$nextTick(() => {
                $('.modalChecked').modal('show')
            })
        },
        closeModalSeriviatext() {
            this.showmodalviatext = false
            this.$nextTick(() => {
                $('.modalTransfer').modal('show')
            })
        },
    },
    created() {
        this.getDetailProduk()
    },
    computed: {
        filteredSeri() {
            return this.detailProduk?.seri.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
    },
}
</script>
<template>
    <div>
        <seriviatext v-if="showmodalviatext" @close="closeModalSeriviatext" @submit="submit" />

        <div class="modal fade modalTransfer" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Transfer Produk</h5>
                        <button type="button" class="close" @click="closeModal">
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
                                <div class="d-flex justify-content-start">
                                    <div class="form-group">
                                        <label for="">Tanggal Pengiriman</label>
                                        <input type="date" v-model="tanggal_kirim" class="form-control">
                                    </div>
                                </div>
                                <div class="d-flex bd-highlight mb-3">
                                    <div class="mr-auto p-2 bd-highlight">
                                        <button class="btn btn-primary" @click="showSeriText">
                                            Pilih Nomor Seri Via Text
                                        </button>
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <input type="text" v-model="search" class="form-control"
                                            placeholder="Cari Nomor Seri">
                                    </div>
                                </div>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">
                                                <input type="checkbox" :checked="checkAll" @click="checkAllSeri">
                                            </th>
                                            <th>
                                                Nomor Seri
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="renderPaginate.length > 0">
                                        <tr v-for="(item, index) in renderPaginate" :key="index">
                                            <td>
                                                <input type="checkbox" @click="selectNoSeri(item.noseri)"
                                                    :checked="noSeriSelected && noSeriSelected.find((data) => data === item.noseri)">
                                            </td>
                                            <td>
                                                {{ item.noseri }}
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="100%" class="text-center">
                                                Tidak ada data
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <pagination :filteredDalamProses="filteredSeri"
                                    @updateFilteredDalamProses="updateFilteredDalamProses" />

                            </div>
                            <div class="card-footer">
                                No Seri Yang Dipilih : {{ noSeriSelected.length }}
                            </div>
                        </div>
                        <div class="spinner-border" role="status" v-else>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        <button type="button" class="btn btn-danger" @click="noSeriSelected = []">Hapus</button>
                        <button type="button" class="btn btn-primary" :disabled="loading" @click="simpan">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>