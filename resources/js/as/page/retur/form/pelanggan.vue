<script>
import autocomplete from '../../../components/autocomplete.vue';
import axios from 'axios';
export default {
    props: ['form'],
    components: {
        autocomplete
    },
    data() {
        return {
            karyawan: [],
            transaksi: [],
            transaksi_data: [],
            customer: [],
            customer_data: [],
        }
    },
    methods: {
        async getKaryawan() {
            try {
                const { data } = await axios.get('/api/karyawan_all')
                this.karyawan = data.map(karyawan => karyawan.nama)
            } catch (error) {
                console.log(error)
            }
        },
        async getCustomer() {
            try {
                const { data } = await axios.get('/api/customer/select')
                this.customer_data = data
                this.customer = data.map(customer => customer.nama)
            } catch (error) {
                console.log(error)
            }
        },
        async getTransaksi(){
            const api = '/api/as/list/so_selesai/'
            switch (this.form?.selectPO) {
                case 'po':
                    try {
                        const { data } = await axios.get(`${api}po`)
                        this.transaksi_data = data
                        this.transaksi = data.map(po => po.nama)
                    } catch (error) {
                        console.log(error)
                    }
                    break;
                case 'so':
                    try {
                        const { data } = await axios.get(`${api}so`)
                        this.transaksi_data = data
                        this.transaksi = data.map(so => so.nama)
                    } catch (error) {
                        console.log(error)
                    }
                    break;
                case 'no_akn':
                    try {
                        const { data } = await axios.get(`${api}no_akn`)
                        this.transaksi_data = data
                        this.transaksi = data.map(akn => akn.nama)
                    } catch (error) {
                        console.log(error)
                    }
                    break;
                case 'no_retur':
                    try {
                        const { data } = await axios.get(`${api}no_retur`)
                        this.transaksi_data = data
                        this.transaksi = data.map(retur => retur.nama)
                    } catch (error) {
                        console.log(error)
                    }
                    break;
                case 'no_sj':
                    try {
                        const { data } = await axios.get(`${api}no_sj`)
                        this.transaksi_data = data
                        this.transaksi = data.map(sj => sj.nama)
                    } catch (error) {
                        console.log(error)
                    }
                    break;
                case 'sj_retur':
                    try {
                        const { data } = await axios.get(`${api}sj_retur`)
                        this.transaksi_data = data
                        this.transaksi = data.map(retur => retur.nama)
                    } catch (error) {
                        console.log(error)
                    }
                    break;
                default:
                    break;
            }
        },
        getIdTransaksi() {
            const transaksi = this.transaksi_data.find(transaksi => transaksi.nama === this.form.no_transaksi)
            this.form.id_transaksi = transaksi?.id
        },
        getCustomerData() {
            const customer = this.customer_data.find(customer => customer.nama === this.form.customer_nama)
            this.form.customer_id = customer?.id
            this.form.alamat = customer?.alamat
            this.form.telepon = customer?.telepon
        },
    },
    computed: {
        isDisabled() {
            if (this.form.customer_id || this.form.id_transaksi) {
                return true
            } else {
                return false
            }
        }
    },
    mounted() {
        this.getKaryawan()
        this.getCustomer()
        this.getTransaksi()
    },
}
</script>
<template>
   <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">
                Informasi Pelanggan
            </div>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-lg-5 col-md-12 col-form-label text-right">PIC</label>
                <div class="col-lg-2 col-md-6">
                    <autocomplete :options="karyawan" v-model="form.pic"/>
                    <small class="text-success mt-1">* Pilih PIC / isi jika tidak tersedia</small>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-5 col-md-12 col-form-label text-right">Telepon PIC</label>
                <div class="col-lg-2 col-md-6">
                    <input type="text" class="form-control" v-model="form.telepon_pic"/>
                </div> 
            </div>
            <div class="form-group row" id="no_transaksi_input">
                <label for="no_transaksi"
                    class="col-lg-5 col-md-12 col-form-label text-right">Jenis Transaksi
                </label>
                <div class="col-lg-1 col-md-6">
                    <select
                        class="form-control" v-model="form.selectPO" @change="getTransaksi">
                        <option value="po">No PO</option>
                        <option value="so">No SO</option>
                        <option value="no_akn">No AKN</option>
                        <option value="no_retur">No Retur</option>
                        <option value="no_sj">No SJ</option>
                        <option value="sj_retur">No SJ (Retur)</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-6">
                    <autocomplete :options="transaksi" v-model="form.no_transaksi" 
                    @input="getIdTransaksi"/>
                </div>
            </div>
            <div class="form-group row" id="customer_nama_input">
                <label for="customer_nama"
                    class="col-lg-5 col-md-12 col-form-label text-right"
                    id="label_cust">Nama Customer</label>
                <div class="col-lg-4 col-md-8">
                    <autocomplete 
                    @input="getCustomerData"
                    :options="customer" v-model="form.customer_nama"/>
                </div>
            </div>
            <div class="form-group row" id="customer_nama_input">
                <label for="customer_nama"
                    class="col-lg-5 col-md-12 col-form-label text-right"
                    id="label_cust">Alamat</label>
                <div class="col-lg-4 col-md-8">
                    <textarea class="form-control" 
                    :disabled="isDisabled"
                    v-model="form.alamat"></textarea>
                </div>
            </div>
            <div class="form-group row" id="customer_nama_input">
                <label for="customer_nama"
                    class="col-lg-5 col-md-12 col-form-label text-right"
                    id="label_cust">Telepon</label>
                <div class="col-lg-4 col-md-8">
                    <input type="number" 
                    :disabled="isDisabled"
                    class="form-control" v-model="form.telepon">
                </div>
            </div>
        </div>
    </div>
</template> 