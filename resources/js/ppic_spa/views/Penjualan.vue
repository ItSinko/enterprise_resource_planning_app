<template>
    <div>
        <div class="columns">
            <div class="column is-four-fifths">
                <h1 class="title">Penjualan</h1>
            </div>
            <div class="column">
                <v-select :options="getFiveYearsBeforeNow" @input="changeYear($event)" v-model="yearsSelected"/>
            </div>
        </div>
        <div class="tabs is-centered">
            <ul>
                <li :class="{'is-active': tabs == 'ekatalog'}" @click="category('ekatalog')">
                    <a><span>E-Katalog</span></a>
                </li>
                <li :class="{'is-active': tabs == 'spa'}" @click="category('spa')">
                    <a><span>SPA</span></a>
                </li>
                <li :class="{'is-active': tabs == 'spb'}" @click="category('spb')">
                    <a><span>SPB</span></a>
                </li>
                <li :class="{'is-active': tabs == 'penjualan'}" @click="category('penjualan')">
                    <a><span>Penjualan</span></a>
                </li>
            </ul>
        </div>
        <ekatalog-vue :penjualanekatalogs="penjualanekatalogs" :jenis="tabs" v-show="tabs == 'ekatalog'" />
        <spa-vue :penjualanspas="penjualanspas" :jenis="tabs" v-show="tabs == 'spa'" />
        <spb-vue :penjualanspbs="penjualanspbs" :jenis="tabs" v-show="tabs == 'spb'" />
        <penjualan-vue :penjualans="penjualans" :jenis="tabs" v-show="tabs == 'penjualan'" />
    </div>
</template>
<script>
    import axios from 'axios';
    import ekatalogVue from '../components/penjualan/ekatalog.vue';
    import spaVue from '../components/penjualan/spa.vue';
    import spbVue from '../components/penjualan/spb.vue';
    import penjualanVue from '../components/penjualan/penjualan.vue';
    export default {
        components: {
            ekatalogVue,
            spaVue,
            spbVue,
            penjualanVue
        },
        data() {
            return {
                penjualanekatalogs: [],
                penjualanspas: [],
                penjualanspbs: [],
                penjualans: [],
                tabs: 'ekatalog',
                yearsSelected: new Date().getFullYear()
            }
        },
        methods: {
            category(tabs) {
                switch (tabs) {
                    case 'ekatalog':
                        this.tabs = 'ekatalog'
                        this.getPenjualan('ekatalog')
                        break;
                    case 'spa':
                        this.tabs = 'spa'
                        this.getPenjualan('spa')
                        break;
                    case 'spb':
                        this.tabs = 'spb'
                        this.getPenjualan('spb')
                        break;
                    case 'penjualan':
                        this.tabs = 'penjualan'
                        this.getPenjualan('penjualan')
                        break;
                    default:
                        break;
                }
            },
            async getPenjualan(category) {
                switch (category) {
                    case 'ekatalog':
                            try {
                                this.$store.commit('setIsLoading', true);
                                await axios.post(`/penjualan/penjualan/ekatalog/data/semua/${this.yearsSelected}`)
                                    .then(response => {
                                        this.penjualanekatalogs = response.data.data;
                                    })
                                    .catch(error => {
                                        console.log(error);
                                    });
                                this.$store.commit('setIsLoading', false);
                            } catch (error) {
                                console.log(error);
                            }
                        break;
                    case 'spa':
                            try {
                                this.$store.commit('setIsLoading', true);
                                await axios.post(`/penjualan/penjualan/spa/data/semua/${this.yearsSelected}`)
                                    .then(response => {
                                        this.penjualanspas = response.data.data;
                                    })
                                    .catch(error => {
                                        console.log(error);
                                    });
                                this.$store.commit('setIsLoading', false);
                            } catch (error) {
                                console.log(error);
                            }
                        break;

                    case 'spb':
                            try {
                                this.$store.commit('setIsLoading', true);
                                await axios.post(`/penjualan/penjualan/spb/data/semua/${this.yearsSelected}`)
                                    .then(response => {
                                        this.penjualanspbs = response.data.data;
                                    })
                                    .catch(error => {
                                        console.log(error);
                                    });
                                this.$store.commit('setIsLoading', false);
                            } catch (error) {
                                console.log(error);
                            }

                    case 'penjualan':
                            try {
                                this.$store.commit('setIsLoading', true);
                                await axios.post(`/api/penjualan/penjualan/data/semua/semua/${this.yearsSelected}`)
                                    .then(response => {
                                        this.penjualans = response.data.data;
                                    })
                                    .catch(error => {
                                        console.log(error);
                                    });
                                this.$store.commit('setIsLoading', false);
                            } catch (error) {
                                console.log(error);
                            }
                    default:
                        break;
                }
            },
            changeYear(year) {
                this.yearsSelected = year;
                this.getPenjualan(this.tabs);
            }
        },
        computed: {
            getFiveYearsBeforeNow() {
                let date = new Date();
                let year = date.getFullYear();
                let years = [];
                for (let i = 0; i < 5; i++) {
                    years.push(year - i);
                }
                return years;
            }
        },
        mounted() {
            this.getPenjualan('ekatalog')
        },
    }

</script>
