<template>
    <v-app>
        <v-main>
            <v-container>
                <h2 class="display-1">Riwayat Transfer</h2>
                <v-row>
                    <v-col cols="12" md="12">
                        <v-data-table :headers="headers" :items="dateFilter">
                            <template v-slot:top>
                                <v-toolbar flat extended >
                                    <v-row>
                                        <v-col md="4">
                                             <v-menu :close-on-content-click="false"
                                            transition="scale-transition" offset-y max-width="290px" min-width="auto">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field v-model="dateRangeText" clearable
                                                    label="Filter Tanggal" hint="YYYY-MM-DD format"
                                                    persistent-hint prepend-icon="mdi-calendar" :attrs="attrs" v-on="on"
                                                    @click:clear="date = null"></v-text-field>
                                            </template>
                                            <v-date-picker range v-model="date"></v-date-picker>
                                        </v-menu>
                                        </v-col>
                                        <v-col md="4">
                                             <!-- <v-select v-model="selectedProduct" :items="productUnique" label="Nama Produk" multiple>
                                                    <template v-slot:prepend-item>
                                                        <v-list-item ripple @mousedown.prevent @click="toggle">
                                                            <v-list-item-action>
                                                                <v-icon :color="selectedProduct.length > 0 ? 'indigo darken-4' : ''">
                                                                    {{ icon }}
                                                                </v-icon>
                                                            </v-list-item-action>
                                                            <v-list-item-content>
                                                                <v-list-item-title>
                                                                    Select All
                                                                </v-list-item-title>
                                                            </v-list-item-content>
                                                        </v-list-item>
                                                        <v-divider class="mt-2"></v-divider>
                                                    </template>
                                                </v-select> -->
                                                 <v-autocomplete v-model="selectedProduct" :items="productUnique" outlined dense chips small-chips label="Nama Produk" multiple></v-autocomplete>
                                        </v-col>
                                    </v-row>
                                    <v-dialog v-model="dialog" max-width="1000px">
                                        <v-card>
                                            <v-card-title>
                                                <span class="text-h5">{{ nama_produk }}</span>
                                                <v-spacer></v-spacer>
                                                <v-text-field v-model="search" append-icon="mdi-magnify" label="Cari" single-line hide-details></v-text-field>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-data-table :headers="headersdetail" :items="data_detail" :search="search"></v-data-table>
                                            </v-card-text>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                            </template>
                            <template v-slot:[`item.aksi`]="{item}">
                                <v-icon small class="mr-2" @click="seeItem(item)">
                                    mdi-archive-eye-outline
                                </v-icon>
                            </template>
                        </v-data-table>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>
    import axios from 'axios'
    export default {
        data() {
            return {
                search: null,
                dialog: false,
                dataRiwayat: [],
                date: null,
                nama_produk: null,
                data_detail: [],
                selectedProduct: [],
                headers: [{
                        text: 'Tanggal Pengiriman',
                        value: 'day_kirim'
                    },
                    {
                        text: 'Waktu Pengiriman',
                        value: 'time_kirim'
                    },
                    {
                        text: 'Nomor BPPB',
                        value: 'bppb'
                    },
                    {
                        text: 'Produk',
                        value: 'produk'
                    },
                    {
                        text: 'Jumlah',
                        value: 'jml'
                    },
                    {
                        text: 'Aksi',
                        align: 'left',
                        sortable: false,
                        value: 'aksi'
                    }
                ],
                headersdetail: [{
                    text: 'No Seri',
                    value: 'no_seri'
                }],
            }
        },
        methods: {
            async loadData() {
                try {
                    await axios.post('/api/prd/history/pengiriman').then(res => {
                        this.dataRiwayat = res.data.data;
                    });
                    $('#myTable').DataTable();
                } catch (error) {
                    console.log(error);
                }
            },
            async seeItem(item) {
                let id = item.produk_id;
                let tf = item.day_kirim_filter + ' ' + item.time_kirim;
                this.data_detail = [];
                this.nama_produk = item.produk;
                try {
                    await axios.get('/api/prd/historySeri/' + id + '/' + tf).then(res => {
                        this.data_detail = res.data.data;
                    });
                    console.log("berhasil");
                } catch (error) {
                    console.log(error);
                }
                this.dialog = true;
            },
            toggle() {
                this.$nextTick(() => {
                    if (this.selectedProduct.length > 0) {
                        this.selectedProduct = [];
                    } else {
                        this.selectedProduct = this.productUnique.slice();
                    }
                })
            }
        },
        mounted() {
            this.loadData();
        },
        computed: {
            dateFilter() {
                if(this.date != null && this.selectedProduct.length > 0) {
                    dataFilter = '';
                    let dataFilter = this.dataRiwayat.filter(item => {
                        return item.day_kirim_filter >= this.date[0] && item.day_kirim_filter <= this.date[1] && this.selectedProduct.includes(item.produk)
                    })
                    return dataFilter
                }
                else if (this.date != null) {
                    dataFilter = '';
                    let dataFilter = this.dataRiwayat.filter(item => {
                        return item.day_kirim_filter >= this.date[0] && item.day_kirim_filter <= this.date[1]
                    })
                    return dataFilter
                } else if(this.selectedProduct.length > 0) {
                    dataFilter = '';
                    let dataFilter = this.dataRiwayat.filter(item => {
                        return this.selectedProduct.includes(item.produk)
                    })
                    return dataFilter
                } else {
                    return this.dataRiwayat
                }
            },
            dateRangeText() {
                if (this.date) {
                    return this.date[0] + ' - ' + this.date[1];
                }
            },
            productUnique(){
                let unique = [];
                this.dataRiwayat.forEach(item => {
                    if(!unique.includes(item.produk)) {
                        unique.push(item.produk);
                    }
                })
                return unique;
            },
            likesAllProduct(){
                return this.selectedProduct.length === this.productUnique.length
            },
            likesSomeProduct(){
                return this.selectedProduct.length > 0 && !this.likesAllProduct
            },
            icon(){
                if (this.likesAllProduct) return 'mdi-close-box'
                if (this.likesSomeProduct) return 'mdi-minus-box'
                return 'mdi-checkbox-blank-outline'
            }
        },
    }

</script>
