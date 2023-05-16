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
            formPart: this.$route.params.dataSelected ? this.$route.params.dataSelected : []
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
        <parts :kurs="formSupplier.kurs" :formPart="formPart"/>
    </div>
</template>