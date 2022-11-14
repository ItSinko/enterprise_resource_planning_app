<script>
import Header from '../components/header.vue'
import dalamProses from '../barangmasuk/dalamProses.vue'
import riwayatPO from '../barangmasuk/riwayatPO.vue'
    export default {
        components: {
            Header,
            dalamProses,
            riwayatPO,
        },
        data() {
            return {
                message: 'Hello World',
                purchaseorders: [{
                    id: 1,
                    nopo: 'PO-0001',
                    divisi: 'IT',
                    supplier: 'PT. ABC',
                    tanggal: '2020-01-01',
                    estimasi: '2020-01-01',
                    status: 'Dalam Pemeriksaan QC'
                },{
                    id: 2,
                    nopo: 'PO-0002',
                    divisi: 'IT',
                    supplier: 'PT. ABC',
                    tanggal: '2020-01-01',
                    estimasi: '2020-01-01',
                    status: 'Pemeriksaan Lanjutan'
                }]
            }
        },
    }

</script>
<template>
    <div>
        <Header :title="'Purchase Order'"/>
        <div class="card">
            <div class="card-body">
                <dalamProses :purchaseorders="purchaseorders"/>
            </div>
        </div>
    </div>
</template>
