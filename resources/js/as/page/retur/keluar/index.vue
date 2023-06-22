<script>
import Table from '../table'
import axios from 'axios'
export default {
    components: {
        Table
    },
    data(){
        return {
            returKeluar: []
        }
    },
    methods: {
        async getReturKeluar() {
            const { data } = await axios.post('/api/as/retur/data').then(res => res.data)
            for (let i = 0; i < data.length; i++) {
                data[i].no_po = data[i].pesanan?.no_po
                data[i].customer = data[i].customer?.nama
                data[i].status = data[i].state?.nama
            }
            this.returKeluar = data
        }
    },
    created() {
        this.getReturKeluar()
    }
}
</script>
<template>
    <div class="card">
        <Table :dataTable="returKeluar"/>
    </div>
</template>