<script>
import tableRework from './table.vue'
import axios from 'axios'
export default {
    components: { tableRework },
    data() {
        return {
            product: [],
        }
    },
    methods: {
        async getProductRework() {
            try {
                this.$store.dispatch('setLoading', true)
                const { produk } = await axios.get('/api/produk').then(res => res.data);
                this.product = produk;
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        }
    },
    created() {
        this.getProductRework()
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
                    <table-rework
                        :product="product"
                        @refresh="getProductRework"
                    ></table-rework>
                </div>
            </v-container>
        </v-main>
    </v-app>
</template>