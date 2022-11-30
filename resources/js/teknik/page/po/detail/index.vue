<script>
        import Header from '../../../components/header.vue'
        import daftarBarang from './daftarBarang'
        import headerDetail from './header'
        export default {
        components: {
            Header,
            daftarBarang,
            headerDetail
        },
        data() {
            return {
                title: 'Purchase Order',
                breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Purchase Order',
                        link: '/teknik/purchaseOrder'
                    },
                    {
                        name: 'Detail',
                        link: '#'
                    }
                ],
            }
        },
    }

</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <headerDetail />
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                    type="button" role="tab" aria-controls="pills-home" aria-selected="true">Daftar Barang</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                    type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Sampling Rakit</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" data-target="#pills-contact"
                    type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Dokumen</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <daftarBarang />
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
        </div>
            </div>
        </div>
    </div>
</template>
