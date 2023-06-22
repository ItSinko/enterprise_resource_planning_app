<script>
import axios from 'axios'
import addnoseri from './addnoseri.vue'
export default {
    components: {
        addnoseri,
    },
    props: ['barangretur', 'form'],
    data() {
        return {
            produk: [],
            showmodal: false,
            produkSelected: null,
        }
    },
    methods: {
        addProduk() {
            this.barangretur.produk.push({
                nama: '',
                jumlah: '',
                no_seri: [],
            })
        },
        removeProduk(index) {
            this.barangretur.produk.splice(index, 1)
        },
        async getProduk() {
            const { data } = await axios.get('/api/produk/variasi')
            this.produk = data
        },
        async getProdukTransaksi() {
            switch (this.form?.selectPO) {
                case 'po':
                    try {
                    const { data:customer } = await axios.get(`/api/as/detail/so_retur/${this.form.id_transaksi}/jual`)
                    const { nama, alamat, telp } = customer
                    this.form.customer_id = data?.customer_id
                    this.form.customer_nama = nama
                    this.form.alamat = alamat
                    this.form.telepon = telp

                    const { data:prd } = await axios.get(`/api/as/list/so_selesai_paket/${this.form.id_transaksi}/jual`)
                    this.barangretur.produk = prd?.produk.map(prd => {
                        return {
                            nama: {
                                id: prd.id,
                                nama: prd.nama,
                            },
                            jumlah: prd.no_seri.length,
                            no_seri: prd.no_seri,
                        }
                    })
                    } catch (error) {
                        console.log(error)    
                    }                    
                    break;
                case 'so':
                        
                    break;
                case 'no_akn':
                            
                    break;
                case 'no_retur':
                
                    break;
                case 'no_sj':

                    break;
                case 'sj_retur':

                    break;
                default:
                    break;
            }
        },
        addSerialNumber(index) {
            this.produkSelected = Object.assign({}, this.barangretur.produk[index])
            this.produkSelected.index = index
            const jml = this.produkSelected.jumlah
            this.produkSelected.no_seri = []
            for (let i = 0; i < jml; i++) {
                this.produkSelected.no_seri.push({
                    text: '',
                })
            }
            this.showmodal = true
            this.$nextTick(() => {
                this.$refs.addnoseri.show()
            })
        },
        closeSerialNumber() {
            this.produkSelected = null
            this.showmodal = false
        },
        simpanSerialNumber() {
            this.barangretur.produk[this.produkSelected.index] = this.produkSelected
        }
    },
    created() {
        this.getProduk()
    },
    computed: {
        isDisabled() {
            return this.form.id_transaksi ? true : false
        }
    },
    watch: {
        // if form.id_transaksi is changed, then this function will be executed
        'form.id_transaksi': function (val) {
            if (val) {
                // get data from api
                this.getProdukTransaksi()
            } else {
                // reset form
                this.form.customer_id = ''
                this.form.customer_nama = ''
                this.form.alamat = ''
                this.form.telepon = ''
                this.barangretur.produk = []
            }
        }
    }
}
</script>
<template>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">Barang Retur</div>
        </div>
        <addnoseri 
        @hide="closeSerialNumber"
        @simpan="simpanSerialNumber"
        v-if="showmodal" ref="addnoseri" :produk="produkSelected"></addnoseri>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <div class="d-flex">
                                <div class="form-check flex-grow-1 form-check-inline col-form-label card-title">
                                    <h6 class="form-check-label">Produk</h6>
                                </div>
                                <button class="btn btn-outline-info" v-if="!form.id_transaksi" @click="addProduk">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="50%">Nama Produk</th>
                                            <th width="20%">Jumlah</th>
                                            <th width="20%" v-if="!form.id_transaksi">No Seri</th>
                                            <th width="5%"  v-if="!form.id_transaksi">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(prd, index) in barangretur.produk">
                                            <td>{{index+1}}</td>
                                            <td>
                                                <v-select v-model="prd.nama" 
                                                :disabled="isDisabled"
                                                label="nama"
                                                :options="produk"></v-select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" v-model="prd.jumlah" :disabled="isDisabled">
                                            </td>
                                            <td>
                                                <button class="btn btn-info" @click="addSerialNumber(index)" v-if="!form.id_transaksi">
                                                    <i class="fa fa-plus"></i>
                                                     Tambah
                                                </button>
                                            </td>
                                            <td  v-if="!form.id_transaksi">
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
                </div>
                <div class="col-lg-6 col-md-12"></div>
            </div>
        </div> 
    </div>
</template>