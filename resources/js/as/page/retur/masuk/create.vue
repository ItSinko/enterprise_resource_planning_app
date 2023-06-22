<script>
import FormTransaksi from '../form/transaksi'
import FormPelanggan from '../form/pelanggan'
import FormBarangRetur from '../form/barangretur'
import Header from '../../../components/header'
export default {
    components: {
        FormTransaksi,
        FormPelanggan,
        FormBarangRetur,
        Header
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
                produk: []
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
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <FormTransaksi :form="form"/>
        <FormPelanggan :form="form"/>
        <FormBarangRetur :barangretur="barangretur" :form="form"/>
    </div>
</template>