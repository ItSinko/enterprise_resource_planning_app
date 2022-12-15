<script>
import Header from '../../components/header.vue'
import DalamProses from './dalamproses'
import Riwayat from './riwayatpo'
    export default {
        components: {
            Header,
            DalamProses,
            Riwayat
        },
        data() {
            return {
                title: 'Purchase Order',
                breadcumbs: [
                    {
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Purchase Order',
                        link: '/pembelian/po'
                    }
                ]      
            }
        },
    }

</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                    type="button" role="tab" aria-controls="pills-home" aria-selected="true">Dalam Proses</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                    type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Riwayat PO</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <dalam-proses />
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <riwayat />
            </div>
        </div>
            </div>
        </div>
    </div>
</template>
