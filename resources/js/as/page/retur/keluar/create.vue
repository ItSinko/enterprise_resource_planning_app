<script>
import FormTransaksi from '../form/transaksi'
import FormPelanggan from '../form/pelanggan'
import FormBarangRetur from '../form/barangretur'
import Header from '../../../components/header'
import addnoseri from '../form/addnoseri.vue'
import axios from 'axios'
export default {
    components: {
        FormTransaksi,
        FormPelanggan,
        FormBarangRetur,
        Header,
        addnoseri,
    },
    data() {
        return {
            form: {
                tgl_retur: '',
                jenis_retur: '',
                keterangan: '',
                pic: '',
                telepon_pic: '',
                selectPO: 'po',
                no_transaksi: '',
                id_transaksi: null,
                customer_id: null,
                customer_nama: '',
                alamat: '',
                telepon: '',
            },
            barangretur: {
                produk: [],
                produkPengganti: [],
            },
            title: 'Form Memo Retur Keluar',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Memo Retur',
                    link: '/as/retur'
                },
                { 
                    name: 'Form Memo Retur Keluar',
                    link: '/as/retur/keluar/create'
                }
            ],
            produk: [],
            showmodal: false,
            produkSelected: null,
            loading: false,
        }
    },
    methods: {
        async getProduk() {
            const { data } = await axios.get('/api/produk/variasi')
            this.produk = data
        },
        tambahProduk() {
            this.barangretur.produkPengganti.push({
                nama: '',
                no_seri: [],
            })
        },
        removeProduk(index) {
            this.barangretur.produkPengganti.splice(index, 1)
        },
        addSerialNumber(index) {
            this.produkSelected = JSON.parse(JSON.stringify(this.barangretur.produkPengganti[index]))
            this.produkSelected.index = index
            this.showmodal = true
            this.$nextTick(() => {
                this.$refs.addnoseri.show()
            })
        },
        closeSerialNumber() {
            this.produkSelected = null
            this.showmodal = false
        },
        closeSerialNumber() {
            this.produkSelected = null
            this.showmodal = false
        },
        simpanSerialNumber() {
            this.loading = true
            this.barangretur.produkPengganti[this.produkSelected.index] = this.produkSelected
            this.loading = false
        }
    },
    created() {
        this.getProduk()
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <addnoseri 
        @hide="closeSerialNumber"
        @simpan="simpanSerialNumber"
        v-if="showmodal" ref="addnoseri" :produk="produkSelected"></addnoseri>
        <FormTransaksi :form="form">
            <template #jenisretur>
                <div class="form-check form-check-inline col-form-label">
                        <input class="form-check-input" type="radio"
                            name="pilih_jenis_retur"
                            value="service"
                            v-model="form.jenis_retur"
                            />
                        <label class="form-check-label"
                            for="pilih_jenis_retur1">Service</label>
                    </div>
                    <div class="form-check form-check-inline col-form-label">
                        <input class="form-check-input" type="radio"
                            name="pilih_jenis_retur"
                            v-model="form.jenis_retur"
                            value="draft" />
                        <label class="form-check-label"
                            for="pilih_jenis_retur2">Draft</label>
                    </div>
            </template>
        </FormTransaksi>
        <FormPelanggan :form="form"/>
        <FormBarangRetur :barangretur="barangretur" :form="form">
            <template #form>
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="form-check flex-grow-1 form-check-inline col-form-label card-title">
                                <h6 class="form-check-label">Produk Pengganti</h6>
                            </div>
                            <button 
                            class="btn btn-outline-info" @click="tambahProduk">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Jumlah</th>
                                        <th>No Seri</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody v-if="!loading">
                                    <tr v-for="(prd, index) in barangretur.produkPengganti">
                                        <td>{{ index+1 }}</td>
                                        <td>
                                            <v-select v-model="prd.nama"
                                            label="nama"
                                            :options="produk"
                                            >
                                            </v-select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" :value="prd.no_seri.length" disabled>
                                        </td>
                                        <td>
                                            <button class="btn btn-info" @click="addSerialNumber(index)">
                                                    <i class="fa fa-plus"></i>
                                                     Tambah
                                                </button>
                                        </td>
                                        <td>
                                            <button 
                                                @click="removeProduk(index)"
                                                class="btn btn-outline-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </template>
        </FormBarangRetur>
    </div>
</template>