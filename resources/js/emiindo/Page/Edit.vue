<template>
    <div>
        <div v-if="loading">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div v-else>
            <ekatalog-vue :ekatalog="ekatalogs"></ekatalog-vue>
        </div>
    </div>
</template>
<script>
    import axios from 'axios';
    import ekatalogVue from '../subpage/ekatalog.vue';
    export default {
        data() {
            return {
                ekatalogs: {},
                jenis: '',
                loading: true
            }
        },
        created() {
            this.getInit();
        },
        components: {
            ekatalogVue
        },

        methods: {
            async getInit() {
                let id = this.$route.params.id;
                this.jenis = this.$route.params.jenis;
                switch (this.jenis) {
                    case 'ekatalog':
                        try {
                            await axios.get('/api/penjualan/edit', )
                                .then(response => {
                                    this.ekatalogs = response.data;
                                    this.loading = false;
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        } catch (error) {
                            console.log(error);
                        }
                        break;

                    default:
                        break;
                }
                if (this.$store.state.provinsi.length == 0) {
                    await axios.get('/api/provinsi/select').then(response => {
                        this.$store.commit('setProvinsi', response.data);
                    }).catch(error => {
                        console.log(error);
                    });
                }
            },
        },
    }

</script>
