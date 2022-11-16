<script>
    import Header from '../components/header.vue'
    import axios from 'axios'
    export default {
        components: {
            Header
        },
        data() {
            return {
                breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Daftar Produk',
                        link: '/teknik/produk'
                    },
                    {
                        name: 'Detail Produk',
                        link: `/teknik/produk/detail/${this.$route.params.id}`
                    }
                ],
                title: 'Detail Produk',
                loading: true,
            }
        },
        created(){
            this.init()
        },
        methods: {
            async init() {
                let id = this.$route.params.id
                try {
                    await axios.get('/api/produk/teknik/detail/' + id).then(res => {
                    console.log(res.data.data)
                    this.loading = false
                })
                } catch (error) {
                    console.log(error)
                }
            }
        }
    }

</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="card-text">
                    <div class="container">
                        <div class="row">
                            <div class="col-4">
                                <div class="text-center pb-5">
                                    <img :src="`/storage/produk/default.jpg`" width="200" />
                                </div>
                            </div>
                            <div class="col-8">
                                <h1 class="display-4 text-bold">Nama Produk</h1>
                                <p class="text-bold">Kode</p>

                                <div class="mt-1">
                                    <p>Jenis</p>
                                    <!-- .btn.btn-outline-secondary.mt -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
