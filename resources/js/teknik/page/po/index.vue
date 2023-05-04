<script>
import Header from '../../components/header.vue'
import dalamProses from './dalamProses'
import riwayatPO from './riwayatPO'
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
                }],
                riwayatPO: [
                    {
                        id: 1,
                        nopo: 'PO-0001',
                        divisi: 'IT',
                        supplier: 'PT. ABC',
                        tanggal_diminta: '2020-01-01',
                        estimasi_kedatangan: '2020-01-01',
                        tanggal_kedatangan: '2020-01-01',
                    },
                    {
                        id: 2,
                        nopo: 'PO-0002',
                        divisi: 'IT',
                        supplier: 'PT. ABC',
                        tanggal_diminta: '2020-01-01',
                        estimasi_kedatangan: '2020-01-01',
                        tanggal_kedatangan: '2020-01-01',
                    }
                ],

                breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Purchase Order',
                        link: '/teknik/bom'
                    },
                ],
            }
        },
    }

</script>
<template>
    <div>
        <Header :title="'Purchase Order'" :breadcumbs="breadcumbs"/>
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Dalam Proses</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Riwayat PO</a>
                    </li>
                    </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <dalamProses :purchaseorders="purchaseorders"/>
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <riwayatPO :riwayatPO="riwayatPO"/>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
