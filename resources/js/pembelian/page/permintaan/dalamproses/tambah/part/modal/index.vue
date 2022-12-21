<script>
import axios from 'axios'
import Terdaftar from './terdaftar'
import TidakTerdaftar from './tidakterdaftar'
    export default {
        props: {
            detail: {
                type: Object,
                default: () => {}
            }
        },
        components: {
            Terdaftar,
            TidakTerdaftar
        },
        data() {
            return {
                form: {
                    produk: null,
                    versi: null,
                    jumlah: null,
                },
                produk: [],
                bom: [],
                detailBom: []
            }
        },
        mounted(){
            if(this.detail){
                this.getDetail()
            }
            this.getProduk()
        },
        destroyed(){
            this.resetData()
        },
        methods: {
            resetData(){
                this.form = {
                    produk: null,
                    versi: null,
                    jumlah: null,
                }
                this.bom = []
                this.detailBom = []
            },
            getDetail(){
                console.log("detail", this.detail)
                const { produk, versi, jumlah, detail } = this.detail
                    this.form.produk = produk
                    this.form.versi = versi
                    this.form.jumlah = jumlah
                    this.getDetailBom()
            },
            async getProduk() {
                try {
                    const { data } = await axios.get('/api/bom/produk/0').then(res => res.data)
                    this.produk = data
                } catch (error) {
                    console.log(error)
                }
            },
            async getBom(produk) {
                const id = produk.value
                this.bom = []
                this.detailBom = []
                this.form.versi = null
                try {
                    const { data } = await axios.get(`/api/bom/produk/${id}`).then(res => res.data)
                    this.bom = data
                } catch (error) {
                    console.log(error)
                }
            },
            getDetailBom() {
                const alert = (alert) => {
                    return this.$swal('Peringatan', `${alert}`, 'warning')
                }

                const getData = async () => {
                const id = this.form.produk.value
                    try {
                        const { data } = await axios.get(`/api/bom/detail/${id}`).then(res => res.data)
                        const checkData = data.length > 0 ? true : false
                        if (checkData) {
                            this.detailBom = []
                            data[0].data_tabel.forEach(item => {
                                this.detailBom.push({
                                    id: item.id,
                                    kode: item.kode,
                                    nama: item.nama,
                                    jumlah_per_set: item.jumlah,
                                    jumlah_kebutuhan: item.jumlah * this.form.jumlah,
                                    harga: 0
                                })
                            })
                        } else {
                            this.detailBom = []
                            alert('Data masih kosong')
                        }
                        console.log(data)
                    } catch (error) {
                        console.log(error)
                    }
                }

                this.checkFormNull ? alert('Silahkan pilih produk dan versi BOM dan jumlah terlebih dahulu') : getData()
            },
            isNumber(event) {
                return new RegExp('[0-9]').test(event.key) || event.preventDefault()
            },
            close(){
                this.$emit('close')
            },
            simpan(){
                const save = () => {
                    const data = {
                    form : this.form,
                    detail : {
                        terdaftar : this.detailBom.length == 0
                        ? [] : this.$refs.terdaftar.formPart,
                        tidakTerdaftar : this.$refs.tidakterdaftar.formPartNotRegistered.length == 0
                        ? []
                        : this.$refs.tidakterdaftar.formPartNotRegistered
                    ,
                    },
                    idx: this.detail ? this.detail.idx : null
                }
                this.$emit('simpan', data)
                this.form = {
                    produk: null,
                    versi: null,
                    jumlah: null,
                },
                this.$refs.terdaftar.formPart = []
                this.$refs.tidakterdaftar.formPartNotRegistered = [{
                    nama: null,
                    jumlah: 0,
                    harga: 0,
                }]
                }
                this.checkFormNull ? this.$swal('Peringatan', 'Silahkan pilih produk dan versi BOM dan jumlah terlebih dahulu', 'warning') : save()
            }
        },
        computed: {
            checkFormNull() {
                return Object.values(this.form).some(item => item === null)
            }
        }
    }

</script>
<template>
    <div class="modal fade modalPart" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-bold">Tambah Part</h5>
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-text">
                                <div class="form-group row">
                                    <label for="" class="col-1 text-center">Nama Produk</label>
                                    <v-select v-model="form.produk" :options="produk" class="col-2" @input="getBom($event)"></v-select>
                                    <label for="" class="col-1 text-center">Versi BOM</label>
                                    <v-select v-model="form.versi" :options="bom" class="col-2"></v-select>
                                    <label for="" class="col-1 text-center">Jumlah</label>
                                    <input type="text" v-model.number="form.jumlah" class="form-control col-1"
                                        @keypress="isNumber($event)">
                                    <div class="col-2">
                                        <button class="btn btn-primary" @click="getDetailBom">Cari</button>
                                    </div>
                                </div>
 
                                <div class="card" v-if="!checkFormNull">
                                    <div class="card-body">
                                        <h4 class="card-title text-bold">Detail Produk dan Part</h4>
                                        <div class="card-text mt-5">
                                            <div class="row">
                                                <div class="col-2">
                                                    <p>Nama Produk</p>
                                                </div>
                                                <div class="col-5 text-bold">
                                                    <p>{{ form.produk ? form.produk.label : '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-2">
                                                    <p>Versi BOM</p>
                                                </div>
                                                <div class="col-5 text-bold">
                                                    <p>{{ form.versi ? form.versi.label : '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-2">Jumlah</div>
                                                <div class="col-5 text-bold">
                                                    <p>{{ form.jumlah ? form.jumlah : 0 }}</p>
                                                </div>
                                            </div>

                                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill"
                                                        data-target="#pills-home" type="button" role="tab"
                                                        aria-controls="pills-home" aria-selected="true">Part Terdaftar</a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill"
                                                        data-target="#pills-profile" type="button" role="tab"
                                                        aria-controls="pills-profile" aria-selected="false">Part Tidak Terdaftar</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                                    aria-labelledby="pills-home-tab">
                                                    <terdaftar ref="terdaftar" v-if="detailBom.length > 0" 
                                                        :detailSelected="detail ?
                                                        detail.detail.terdaftar : []"
                                                        :dataTable="detailBom"/>
                                                    </div>
                                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                                    aria-labelledby="pills-profile-tab">
                                                    <tidak-terdaftar 
                                                    :detailSelected="detail ?
                                                    detail.detail.tidakTerdaftar : []"
                                                    ref="tidakterdaftar" />
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" @click="close">Batal</button>
                    <button type="button" class="btn btn-warning" @click="simpan">Tambah Ke Daftar Pembelian</button>
                </div>
            </div>
        </div>
    </div>
</template>
