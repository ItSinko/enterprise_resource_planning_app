<script>
import Header from '../components/header.vue'
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
        }
    },    
}
</script>
<template>
    <div>
      <Header :title="title" :breadcumbs="breadcumbs" />
      <h1>Hello</h1>
    </div>
</template>