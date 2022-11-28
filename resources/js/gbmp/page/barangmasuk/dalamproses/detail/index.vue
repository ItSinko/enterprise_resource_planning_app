<script>
    import Header from '../../../../components/header.vue'
    import HeaderDetail from './headerDetail.vue'
    import Table from './table.vue'
    import Dokumen from './dokumen.vue'
    export default {
        components: {
            Header,
            HeaderDetail,
            Table,
            Dokumen
        },
        data() {
            return {
                breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Barang Masuk',
                        link: '/gbmp/barang-masuk'
                    },
                    {
                        name: 'Detail Barang Masuk',
                        link: '#'
                    },
                ],
                title: 'Barang Masuk',
            }
        },
    }

</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <header-detail />
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">Daftar Barang</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Dokumen</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                        aria-labelledby="pills-home-tab">
                        <Table />
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <Dokumen />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
