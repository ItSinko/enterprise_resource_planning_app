<script>
    import Header from '../../../components/header.vue'
    import HeaderCard from './header.vue'
    import Table from './table.vue'
    import axios from 'axios'
    export default {
        components: {
            Header,
            HeaderCard,
            Table
        },
        data() {
            return {
                title: 'Detail Supplier',
                breadcumbs: [{
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
                headers: null,
                dataTable: [{
                        id: 1,
                        no_po: 'PO-001',
                        divisi: 'IT',
                        tanggal_diminta: '2020-01-01',
                        estimasi_kedatangan: '2020-01-01',
                        status: 'selesai'
                    },
                    {
                        id: 2,
                        no_po: 'PO-002',
                        divisi: 'IT',
                        tanggal_diminta: '2020-01-01',
                        estimasi_kedatangan: '2020-01-01',
                        status: 'batal'
                    }
                ],
                id: this.$route.params.id 
            }
        },
        created() {
            this.getDetailSuppliers()
        },
        methods: {
            async getDetailSuppliers () {
                const { data } = await axios.get(`/api/supplier/${this.id}`).then(res => res.data)
                this.headers = data
            }
        }
    }

</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <HeaderCard :headers="headers"/>
                    </div>
                    <div class="col">
                        <Table :dataTable="dataTable" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
