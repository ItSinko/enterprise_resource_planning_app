<script>
import Header from '../../../components/header.vue'
import HeaderCard from './header.vue'
import Table from './table.vue'
export default {
    components: {
        Header,
        HeaderCard,
        Table
    },
    data() {
        return {
            title: 'Detail Supplier',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Supplier',
                    link: '/pembelian/supplier'
                },
                {
                    name: 'Detail Supplier',
                    link: '/pembelian/supplier/detail'
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
            <div class="row">
                <div class="col-3">
                    <HeaderCard/>
                </div>
                <div class="col">
                    <Table />
                </div>
            </div>
        </div>
    </div>
    </div>
</template>