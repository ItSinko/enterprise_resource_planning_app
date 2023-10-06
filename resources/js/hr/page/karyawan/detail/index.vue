<script>
import Header from '../../../components/header.vue';
import headerDetail from './header.vue';
export default {
    components: {
        Header,
        headerDetail
    },
    data() {
        return {
            title: 'Detail Karyawan',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Karyawan',
                    link: '/hr/karyawan'
                },
                {
                    name: 'Detail Karyawan',
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
        <headerDetail />
    </div>
</template>