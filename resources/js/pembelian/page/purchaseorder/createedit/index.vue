<script>
import Headers from '../../../components/header.vue'
import HeadersPo from './headersPO.vue'
import InfoPo from './infoPO.vue'
import InfoSupplier from './infoSupplier.vue'
import Parts from './parts.vue'
export default {
    components: {
        Headers,
        HeadersPo,
        InfoPo,
        InfoSupplier,
        Parts
    },
    data() {
        return {
            title: 'Purchase Order',
            breadcumbs: [
                {
                    name: 'Pembelian',
                    link: '/pembelian'
                },
                {
                    name: 'Purchase Order',
                    link: '/pembelian/po'
                },
                {
                    name: 'Create',
                    link: '/pembelian/po/create'
                }
            ],
            formPO: {
                nomor_po: '',
                tanggal_po: '',
                estimasi_datang: '',
                ekspedisi: '',
            },
            formSupplier: {
                supplier: '',
                alamat: '',
                telepon: '',
                kurs: '',
            },
            formPart: [{
                kode_part: 'SP-0001',
                nama_part: 'Sparepart 1',
                stok: 2,
                jumlah: 45,
                jumlah_beli: 20,
                estimasi_harga: 45,
                harga_beli: 45,
                ongkir: 24,
                biaya_lain: 20,
                konversi: 0,
            }]
        }
    },
}
</script>
<template>
    <div>
        <p>{{ this.$route.params }}</p>
        <headers :title="title" :breadcumbs="breadcumbs"/>
        <headers-po />
        <div class="row">
            <div class="col d-flex align-items-stretch">
                <info-po :formPO="formPO"/>
            </div>
            <div class="col d-flex align-items-stretch">
                <info-supplier :formSupplier="formSupplier"/>
            </div>
        </div>
        <parts :kurs="formSupplier.kurs" :formPart="formPart"/>
    </div>
</template>