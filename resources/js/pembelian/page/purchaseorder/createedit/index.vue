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
                    link: '/pembelian/purchaseorder'
                },
                {
                    name: 'Create',
                    link: '/pembelian/purchaseorder/create'
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
        }
    },
}
</script>
<template>
    <div>
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
        <parts :kurs="formSupplier.kurs"/>
    </div>
</template>