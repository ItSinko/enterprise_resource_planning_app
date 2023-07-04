<script>
import FormTransaksi from '../form/transaksi'
import FormPelanggan from '../form/pelanggan'
import FormBarangRetur from '../form/barangretur'
import Header from '../../../components/header'
import axios from 'axios'
export default {
    components: {
        FormTransaksi,
        FormPelanggan,
        FormBarangRetur,
        Header
    },
    data() {
        return {
            sparepart: [],
            form: {
                tgl_retur: '',
                jenis_retur: '',
                garansi: false,
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
                part: [],
            },
            title: 'Form Memo Retur Masuk',
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
                    name: 'Form Memo Retur Masuk',
                    link: '/as/retur/masuk/create'
                }
            ]  
        }
    },
    methods: {
        async getSparepart() {
            const { data } = await axios.post('/api/gk/sel-m-spare')
            this.sparepart = data?.map(spare => {
                return {
                    id: spare.id,
                    nama: spare.nama,
                }
            })
        },
        async getPartTransaksi() {
            try {
                const { data:prd } = await axios.get(`/api/as/list/so_selesai_paket/${this.form.id_transaksi}/jual`)
                this.barangretur.part = prd?.part.map(prd => {
                    return {
                        nama: {
                            id: prd.id,
                            nama: prd.nama,
                        },
                        jumlah: prd.jumlah,
                    }
                })
            } catch (error) {
                console.log(error)    
            }  
        },
        addSparepart() {
            this.barangretur.part.push({
                nama: '',
                jumlah: '',
            })
        },
        removePart(index) {
            this.barangretur.part.splice(index, 1)
        },
        batal() {
            this.$router.push('/as/retur')
        },
    },
    watch: {
        'form.id_transaksi': function() {
            this.getPartTransaksi()
        }
    },
    computed: {
        isDisabled() {
            return this.form.id_transaksi ? true : false
        },
        checkFormNotNull(){
            const { tgl_retur, jenis_retur, keterangan, pic, telepon_pic, selectPO, no_transaksi, id_transaksi, customer_id, customer_nama, alamat } = this.form
            if(tgl_retur && jenis_retur && keterangan && pic && telepon_pic && selectPO && no_transaksi && id_transaksi && customer_id && customer_nama && alamat) {
                return true
            }
            return false
        },
    },
    mounted() {
        this.getSparepart()
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <FormTransaksi :form="form">
            <template #jenisretur>
                <div class="form-check form-check-inline col-form-label">
                        <input class="form-check-input" type="radio"
                            name="pilih_jenis_retur"
                            value="peminjaman"
                            v-model="form.jenis_retur"
                            />
                        <label class="form-check-label"
                            for="pilih_jenis_retur1">Peminjaman</label>
                    </div>
                    <div class="form-check form-check-inline col-form-label">
                        <input class="form-check-input" type="radio"
                            name="pilih_jenis_retur"
                            v-model="form.jenis_retur"
                            value="komplain" />
                        <label class="form-check-label"
                            for="pilih_jenis_retur2">Komplain</label>
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
                                <h6 class="form-check-label">Part</h6>
                            </div>
                            <button 
                            @click="addSparepart()"
                            class="btn btn-outline-info" v-if="!form.id_transaksi">
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
                                        <th>Nama Part</th>
                                        <th>Jumlah</th>
                                        <th v-if="!form.id_transaksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(prt, index) in barangretur.part">
                                        <td>{{ index+1 }}</td>
                                        <td>
                                            <v-select v-model="prt.nama" 
                                                :disabled="isDisabled"
                                                label="nama"
                                                :options="sparepart"
                                                ></v-select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" v-model="prt.jumlah" :disabled="isDisabled">
                                        </td>
                                        <td v-if="!form.id_transaksi">
                                            <button 
                                                @click="removePart(index)"
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
        <div class="d-flex bd-highlight mb-3">
            <div class="mr-auto p-2 bd-highlight"><button class="btn btn-danger" @click="batal">Batal</button></div>
            <div class="p-2 bd-highlight"><button class="btn btn-info" :disabled="!checkFormNotNull">Tambah</button></div>
        </div>
    </div>
</template>