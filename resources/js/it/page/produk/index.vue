<script>
import axios from 'axios'
import TableProduk from './table.vue'
import CreateProduk from './create'
export default {
    components: { TableProduk, CreateProduk },
    data() {
        return {
            produk: null,
            // dialog create or edit
            dialogCreate: false,
            produkEdit: null,
        }
    },
    methods: {
        async getProduk() {
            this.dialogCreate = false
            try {
                this.$store.dispatch('setLoading', true)
                const { produk } = await axios.get('/api/produk').then(res => res.data)
                this.produk = produk
                this.$store.dispatch('setLoading', false)
            } catch (error) {
                console.log(error)
            }
        },
        editProduk(item) {
            this.produkEdit = Object.assign({}, item)
            this.dialogCreate = true
        },
        addProduk() {
            this.produkEdit = null
            this.dialogCreate = true
        },
        closeDialog() {
            this.dialogCreate = false
            this.produkEdit = null
        }
    },
    created() {
        this.getProduk()
    }
}
</script>
<template>
    <v-app>
        <v-main>
            <v-container>
                <v-skeleton-loader
                        v-if="$store.state.loading"
                        class="mx-auto"
                        type="table"
                        ></v-skeleton-loader>
                <div v-else>
                    <table-produk 
                    :produk="produk"
                    @editProduk="editProduk"
                    @addProduk="addProduk"
                    ></table-produk>

                    <create-produk
                    :dialogCreate="dialogCreate"
                    :produk="produkEdit"
                    @closeDialog="closeDialog"
                    @refresh="getProduk"
                    ></create-produk>
                </div>
            </v-container>
        </v-main>
    </v-app>
    
</template>