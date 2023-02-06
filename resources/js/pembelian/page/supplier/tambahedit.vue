<script>
    import Header from '../../components/header.vue'
    import axios from 'axios'
    export default {
        components: {
            Header
        },
        props: {
            title: {
                type: String,
                default: 'Tambah Supplier'
            },
            breadcumbs: {
                type: Array,
                default: () => [{
                    name: 'Beranda',
                    link: '#'
                }, {
                    name: 'Supplier',
                    link: '/pembelian/supplier'
                }, {
                    name: 'Tambah Supplier',
                    link: '/pembelian/supplier/tambah'
                }]
            },
        },
        data() {
            return {
                supplier: {
                    nama: '',
                    kode: '',
                    kurs: '',
                    jenis: '',
                },
                alamat_supplier: {
                    alamat: '',
                    kode_pos: '',
                    negara: '',
                },
                kontak_supplier: {
                    telepon: '',
                    email: '',
                    fax: '',
                },
                country: [],
                getKurs: [],
                id: this.$route.params.id || null,
                api: 'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies.json'
            }
        },
        created() {
            this.getSupplier()
        },
        methods: {
            async getSupplier() {
                if (this.id) {
                    const { data } = await axios.get(`/api/supplier/edit/${this.id}`).then(res => res.data)
                    const { kode, nama, email, telepon, jenis, kurs } = data
                    this.supplier = {
                        kode,
                        nama,
                        jenis,
                        kurs,
                    }
                    this.kontak_supplier = {
                        email,
                        telepon,
                    }
                }
                const { data } = await axios.get('https://restcountries.com/v3.1/all').then(res => res)
                const country = data.map(item => {
                    return item.name.common
                })
                this.country = country.sort()
                const { data: kurs } = await axios.get(this.api).then(res => res)
                Object.entries(kurs).forEach(key => {
                    this.getKurs.push({
                        value: Object.values(key)[0],
                        label: `${Object.values(key)[1]} (${Object.values(key)[0]})`
                    })
                })
            },
            batal() {
                this.$router.push('/pembelian/supplier')
            },
            simpan(){
                let data = {
                    supplier: this.supplier,
                    alamat_supplier: this.alamat_supplier,
                    kontak_supplier: this.kontak_supplier,
                }
                const checkIsNotNull = (obj) => {
                    for (let key in obj) {
                        if (obj[key] === '') {
                            return false
                        }
                    }
                    return true
                }

                const isValidSupplier = (supplier) => {
                    let isValid = Object.keys(supplier).every(key => {
                        return key === 'fax' || supplier[key] !== '';
                    });

                    return isValid;
                }

                const checkEmailIsValid = (email) => {
                    let regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
                    return regex.test(email)
                }

                const success = () => {
                    this.$swal('Berhasil', 'Data berhasil disimpan', 'success')
                    this.$router.push('/pembelian/supplier')
                }

                const error = () => {
                    this.$swal('Gagal', 'Data gagal disimpan', 'error')
                }

                const saveData = () => {
                    const { supplier, alamat_supplier, kontak_supplier } = data
                    
                    const isAdd = () => {
                        axios.post('/api/supplier/store', {
                            supplier,
                            alamat_supplier,
                            kontak_supplier,
                        }).then(res => {
                            success()
                        }).catch(err => {
                            error()
                        })
                    }

                    const isUpdated = () => {
                        axios.post(`/api/supplier/update/${this.id}`, {
                            supplier,
                            alamat_supplier,
                            kontak_supplier,
                        }).then(res => {
                            success()
                        }).catch(err => {
                            error()
                        })
                    }

                    this.id ? isUpdated() : isAdd()
                }

                if (checkIsNotNull(data.supplier) && checkIsNotNull(data.alamat_supplier) && isValidSupplier(data.kontak_supplier)) {
                    checkEmailIsValid(data.kontak_supplier.email) ? saveData() : this.$swal('Gagal', 'Email tidak valid', 'error')
                } else {
                    this.$swal('Gagal', 'Data tidak boleh kosong', 'error')
                }
            },
            isNumber(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            kursChanged(val) {
                const { value } = val
                this.supplier.kurs = value
            },
        },
    }

</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-bold">Data Supplier</h4>
                        <div class="card-text text-center">
                            <div class="form-group row">
                                <label for="" class="col-4 text-right">Nama Supplier</label>
                                <div class="col-5">
                                    <input type="text" class="form-control" v-model="supplier.nama">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-4 text-right">Kode Supplier</label>
                                <div class="col-4">
                                    <input type="text" class="form-control" v-model="supplier.kode">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-4 text-right">Kurs</label>
                                <div class="col-4">
                                    <v-select :options="getKurs" @input="kursChanged($event)" v-model="supplier.kurs"></v-select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-4 text-right">Jenis Supplier</label>
                                <div class="row" style="padding-left: 20px">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio1" value="impor" v-model="supplier.jenis">
                                        <label class="form-check-label" for="inlineRadio1">Import</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio2" value="lokal" v-model="supplier.jenis">
                                        <label class="form-check-label" for="inlineRadio2">Lokal</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-bold">Informasi Alamat</h4>
                                <div class="card-text pt-5">
                                    <div class="container">
                                        <div class="form-group row">
                                            <label for="" class="col-2">Alamat</label>
                                            <textarea class="col-8 form-control" name="" id="" cols="5"
                                                rows="5" v-model="alamat_supplier.alamat"></textarea>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-2">Postal Code</label>
                                            <input type="text" class="form-control col-3" v-model="alamat_supplier.kode_pos" @keypress="isNumber($event)">
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-2">Negara</label>
                                            <v-select class="col-8 ml-n2" :options="country" v-model="alamat_supplier.negara"></v-select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-bold">Informasi Kontak</h4>
                                <div class="card-text pt-5">
                                    <div class="container">
                                        <div class="form-group row">
                                            <label for="" class="col-2">Email</label>
                                            <textarea class="col-8 form-control" name="" id="" cols="5"
                                                v-model="kontak_supplier.email"
                                                rows="5"></textarea>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-2">No Telepon</label>
                                            <input type="text" class="form-control col-8"
                                            @keypress="isNumber($event)"
                                            v-model="kontak_supplier.telepon"
                                            >
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-2">Fax</label>
                                            <input type="text" class="form-control col-8"
                                            v-model="kontak_supplier.fax"
                                            @keypress="isNumber($event)"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-danger" @click="batal">Batal</button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <button class="btn btn-primary" @click="simpan">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
