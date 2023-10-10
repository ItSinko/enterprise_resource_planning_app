<script>
import axios from 'axios'; 
import TableProduct from './table.vue'
export default {
    components: { TableProduct },
    data() {
        return {
            product: null,
        }
    },
    methods: {
        async getProduct() {
            try {
                this.$store.dispatch('setLoading', true)
                const { produk } = await axios.get('/api/produk').then(res => res.data);
                this.product = produk;
                this.$store.dispatch('setLoading', false)
            } catch (error) {
                console.log(error);
            }
        },
    },
    created() {
        this.getProduct()
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
                    <table-product 
                        :product="product"
                        @getProduct="getProduct"
                    ></table-product>
                    </div>
            </v-container>
        </v-main>
    </v-app>
</template>