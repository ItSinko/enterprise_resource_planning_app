<template>
    <div>
        <div v-if="loading">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="container-fluid" v-else>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                            <th>Nomor Urut</th>
                            <th>Nomor SO</th>
                            <th>Nomor AKN</th>
                            <th>Nomor PO</th>
                            <th>Nomor DO</th>
                            <th>Nama Instansi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(tr, idx) in terima" :key="idx">
                                <td>{{ tr.nourut }}</td>
                                <td>{{ tr.SO }}</td>
                                <td>{{ tr.AKN }}</td>
                                <td>{{ tr.PO }}</td>
                                <td>{{ tr.DO }}</td>
                                <td>{{ tr.Satuan }}</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'

export default {
    data() {
        return {
            loading: false,
            terima: [],
        }
    },
    methods: {
        async loadData(){
            this.loading = true;
            try {
                await axios.get('/api/penjualan/penjualan_emindo').then(res => {
                    this.terima = res.data.data;
                    this.loading = false;
                });
            } catch (error) {
                console.log(error);
            }
        }
    },
    mounted() {
        this.loadData();
    },
    updated(){
        $('.table').DataTable();
    }
}
</script>