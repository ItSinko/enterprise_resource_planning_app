<script>
import axios from 'axios';
import detailProduk from './detailproduk.vue';
import moment from 'moment';
export default {
    props: ['showModal', 'dataProduk', 'maxDate'],
    components: {
        detailProduk
    },
    data() {
        return {
            judul: this.dataProduk?.id ? 'Form Edit Penjadwalan Rework' : 'Form Tambah Penjadwalan Rework',
            produk: [],
            showDetailProduk: false,
            showModalNow: false,
            // get first date of month
            minDateConversion: moment(this.maxDate).format('YYYY-MM-DD'),
            maxDateConversion: moment(this.maxDate).endOf('month').format('YYYY-MM-DD'),
            stokGBJ: 0,
        }
    },
    methods: {
        closeModal() {
            this.$emit('closeModal')
        },
        async getProduk() {
            const { data } = await axios.get('/api/produk/rw/select')
            this.produk = data.map((item) => {
                return {
                    id: item.id,
                    label: item.nama
                }
            })
            this.showModalNow = true
        },
        detailProdukButton() {
            if (!this.dataProduk.detail) {
                this.dataProduk.detail = [
                    {
                        produk: null,
                        jumlah: null
                    },
                    {
                        produk: null,
                        jumlah: null
                    }
                ]
            }
            this.showModalNow = false
            this.$nextTick(() => {
                this.showDetailProduk = true
            })
        },
        simpan() {
            const ceknull = Object.values(this.dataProduk).filter((item) => item === null || item === '')
            if(this.tanggalMulaiError || this.tanggalSelesaiError || ceknull.length > 0) {
                this.$swal({
                    title: 'Gagal!',
                    text: 'Data yang anda masukkan tidak valid',
                    icon: 'error',
                    confirmButtonColor: '#00d1b2',
                    confirmButtonText: 'OK'
                })
                return
            }

            if (this.dataProduk.id) {
                this.$emit('edit', this.dataProduk)
            } else {
                this.$emit('tambah', this.dataProduk)
            }
        },
        async getStok(id) {
            try {
                const { data } = await axios.get(`/api/produk/rw/select/${id}`)
                this.stokGBJ = data.jumlah
            } catch (error) {
                console.log(error)
            }
        },
    },
    mounted() {
        this.getProduk()
        if (this.dataProduk.produk_id?.id != undefined) {
            this.getStok(this.dataProduk.produk_id.id)
        }
    },
    computed: {
        tanggalMulaiError() {
            const tanggal_mulai = moment(this.dataProduk.tanggal_mulai)
            const tanggal_selesai = moment(this.dataProduk.tanggal_selesai)
            if (tanggal_mulai.isAfter(tanggal_selesai)) {
                return true
            }
            return false
        },
        tanggalSelesaiError() {
            const tanggal_mulai = moment(this.dataProduk.tanggal_mulai)
            const tanggal_selesai = moment(this.dataProduk.tanggal_selesai)
            if (tanggal_selesai.isBefore(tanggal_mulai)) {
                return true
            }
            return false
        },
        stokGBJError() {
            if (this.stokGBJ < this.dataProduk.jumlah) {
                return true
            }
            return false
        }
    }
}
</script>
<template>
    <div>
        <detailProduk v-if="showDetailProduk" :dataProduk="dataProduk" :showModal="showDetailProduk"
            @closeModal="showDetailProduk = false, showModalNow = true" />
        <div class="modal" :class="{ 'is-active': showModalNow }">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">{{ judul }}</p>
                    <button class="delete" @click="closeModal"></button>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">Tanggal Mulai</label>
                                <div class="control">
                                    <input class="input" type="date" placeholder="Text input"
                                        :min="minDateConversion" :max="maxDateConversion"
                                        :class="{ 'is-danger': tanggalMulaiError }" v-model="dataProduk.tanggal_mulai">
                                    <p class="help is-danger" v-if="tanggalMulaiError">
                                        Tanggal mulai harus lebih kecil dari tanggal selesai
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Tanggal Selesai</label>
                                <div class="control">
                                    <input class="input" type="date" placeholder="Text input"
                                        :min="minDateConversion" :max="maxDateConversion"
                                        :class="{ 'is-danger': tanggalSelesaiError }"
                                        v-model="dataProduk.tanggal_selesai">
                                    <p class="help is-danger" v-if="tanggalSelesaiError">
                                        Tanggal selesai harus lebih besar dari tanggal mulai
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Nama Rework</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <p class="control">
                                    <v-select :options="produk" label="label" v-model="dataProduk.produk_id" :reduce="option => option.id" @input="getStok(dataProduk.produk_id)"/>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Jumlah</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <input class="input" type="text" placeholder="Jumlah" v-model="dataProduk.jumlah" :class="{ 'is-danger': stokGBJError }"
                                        @keypress="numberOnly($event)">
                                    <p v-if="stokGBJError" class="help is-danger">
                                        Jumlah tidak boleh melebihi stok
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Stok GBJ</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <p class="control">
                                    <input class="input" type="text" placeholder="Stok GBJ" v-model.number="stokGBJ" disabled
                                        @keypress="numberOnly($event)">
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label"></label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <p class="control">
                                    <button class="button is-link is-light" @click="detailProdukButton">
                                        Detail Produk
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div> -->
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-success" @click="simpan">
                        Simpan
                    </button>
                </footer>
            </div>
        </div>
    </div>
</template>
