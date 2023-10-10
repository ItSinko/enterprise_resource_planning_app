<script>
import TableReworks from './table.vue'
export default {
    components: { TableReworks },
    data() {
        return {
            reworks: [
                {
                    id: 1,
                    nama_produk: 'Produk 1',
                    kategori: 'Kategori 1',
                    status: true,
                },
                {
                    id: 2,
                    nama_produk: 'Produk 2',
                    kategori: 'Kategori 2',
                    status: false,
                }
            ],
        }
    },
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
                <table-reworks 
                :reworks="reworks"
                v-else></table-reworks>
            </v-container>
        </v-main>
    </v-app>
</template>