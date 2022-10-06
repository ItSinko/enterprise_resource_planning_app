<template>
    <div v-if="detailpenjualanekatalog != null">
        <h1 class="is-size-2 has-text-weight-bold">Detail Penjualan</h1>
        <div class="tabs is-centered">
            <ul>
                <li :class="{'is-active': !tabs}" @click="tabs = false">
                    <a><span>Informasi</span></a>
                </li>
                <li :class="{'is-active': tabs}" @click="tabs = true">
                    <a><span>Produk</span></a>
                </li>
            </ul>
        </div>
        <div class="card" :class="{'is-hidden':tabs}">
            <div class="card-header">
                <div class="card-header-title">Informasi</div>
            </div>
            <div class="card-content">
                <div class="content" v-if="$route.params.jenis == 'ekatalog'">
                    <div class="columns">
                        <div class="column">
                            <p>No SO</p>
                            <p class="has-text-weight-bold" v-html="checkdata(detailpenjualanekatalog.so)"></p>
                        </div>
                        <div class="column">
                            <p>Tanggal Buat</p>
                            <p class="has-text-weight-bold">{{ checkdata(detailpenjualanekatalog.tgl_buat) }}</p>
                        </div>
                        <div class="column">
                            <p>No PO</p>
                            <p class="has-text-weight-bold" v-html="checkdata(detailpenjualanekatalog.no_po)"></p>
                        </div>
                    </div>
                    <div class="columns" >
                        <div class="column" >
                            <p>No AKN</p>
                            <p class="has-text-weight-bold" v-html="checkdata(detailpenjualanekatalog.no_paket)"></p>
                        </div>
                        <div class="column">
                            <p>Tanggal Edit</p>
                            <p class="has-text-weight-bold" v-html="checkdata(detailpenjualanekatalog.tgl_edit)"></p>
                        </div>
                        <div class="column">
                            <p>Tanggal PO</p>
                            <p class="has-text-weight-bold" v-html="checkdata(detailpenjualanekatalog.tgl_po)"></p>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <p>No Urut</p>
                            <p class="has-text-weight-bold">{{ checkdata(detailpenjualanekatalog.no_urut) }}</p>
                        </div>
                        <div class="column">
                            <p>Tanggal Delivery</p>
                            <p class="has-text-weight-bold" v-html="checkTanggalDelivery(detailpenjualanekatalog.tgl_kontrak)"></p>
                        </div>
                        <div class="column">
                            <p>Status</p>
                            <div v-html="status($route.params.status)"></div>
                        </div>
                    </div>
                </div>
                <div class="content" v-else>
                    <div class="columns">
                        <div class="column">
                            <p>No SO</p>
                            <p class="has-text-weight-bold" v-html="checkdata(detailpenjualanekatalog.so)"></p>
                        </div>
                        <div class="column">
                            <p>No PO</p>
                            <p class="has-text-weight-bold" v-html="checkdata(detailpenjualanekatalog.no_po)"></p>
                        </div>
                    </div>
                    <div class="columns" >
                        <div class="column">
                            <p>Tanggal PO</p>
                            <p class="has-text-weight-bold" v-html="checkdata(detailpenjualanekatalog.tgl_po)"></p>
                        </div>
                        <div class="column">
                            <p>Status</p>
                            <div v-html="status($route.params.status)"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-content">
                <div class="notification is-primary">
                    <span class="has-text-weight-bold">Detail :</span> {{ detailpenjualanekatalog.deskripsi }}
                </div>
            </div>
        </div>

        <div class="card" :class="{'is-hidden':!tabs}">
            <div class="card-header">
                <div class="card-header-title">Produk</div>
            </div>
            <div class="card-content">
                <div class="content">
                    <div class="columns is-variable bd-klmn-columns is-1">
                        <div class="column is-3" v-if="checkChart">
                            <DoughnutChart :chartData="chartData" v-if="loadingChart"></DoughnutChart>
                            <div v-else class="is-loading"></div>
                        </div>
                        <div class="column" :class="[checkChart ? 'is-6' : 'is-12']">
                            <div class="bd-notification is-primary has-text-centered">
                                <table class="table is-fullwidth has-text-centered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Produk</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                            <th>Ongkir</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(paket, id) in detailpenjualanekatalog.detail_pesanan">
                                            <tr>
                                                <td>{{ id + 1 }}</td>
                                                <td>{{ paket.nama_paket }} 
                                                    <button v-if="paket.jenis != 'jasa'"
                                                        class="button is-primary is-small is-rounded is-outlined"
                                                        @click="showChart(paket.id, paket.jenis)"><i
                                                            class="fas fa-eye"></i></button></td>
                                                <td>{{ paket.jumlah }}</td>
                                                <td>Rp. {{ formatRupiah(paket.harga) }}</td>
                                                <td>Rp. {{ formatRupiah(paket.ongkir) }}</td>
                                                <td>Rp.
                                                    {{ formatRupiah(subtotal(paket.jumlah, paket.harga, paket.ongkir)) }}
                                                </td>
                                            </tr>
                                            <tr v-for="detail in paket.detail_produk">
                                                <td></td>
                                                <td>{{ detail.nama_produk }} <button
                                                        class="button is-primary is-small is-rounded is-outlined"
                                                        @click="showChart(detail.id, detail.jenis)"><i
                                                            class="fas fa-eye"></i></button></td>
                                                <td>{{ detail.jumlah }}</td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="has-text-right">Total</td>
                                            <td>Rp. {{ formatRupiah(totalHrg(detailpenjualanekatalog.detail_pesanan)) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="column is-3" v-if="checkChart">
                            <CardDetail :detailProdukStok="chartData" v-if="loadingChart"></CardDetail>
                            <div v-else class="is-loading"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios';
    import mix from '../../../emiindo/mixins/mix';
    import mix2 from '../../mixins/mix';
    import DoughnutChart from '../../components/detailpenjualan/DoughnutChart';
    import CardDetail from '../../components/detailpenjualan/CardDetail';
    export default {
        mixins: [mix, mix2],
        data() {
            return {
                detailpenjualanekatalog: null,
                tabs: false,
                chartData: null,
                loadingChart: false,
            }
        },
        components: {
            DoughnutChart,
            CardDetail
        },
        methods: {
            async getPenjualan() {
                try {
                    let id = this.$route.params.id;
                    this.$store.commit('setIsLoading', true);
                    await axios.get('/penjualan/penjualan/detail/ekatalog_ppic/' + id)
                    .then(response => {
                        this.detailpenjualanekatalog = response.data.data;
                        this.$store.commit('setIsLoading', false);
                    })
                } catch (error) {
                    console.log(error);
                }
            },
            checkTanggalDelivery(date) {
                let dateNow = new Date().toISOString().slice(0, 10);
                let difference = Math.ceil((new Date(date) - new Date(dateNow)) / (1000 * 3600 * 24));
                if(date == null){
                    return '<span class="has-text-danger">Belum ada tanggal delivery</span>';
                }else {
                    if (dateNow < date) {
                        if (difference > 7) {
                            return `<div>${date}</div><div><small><i class="fas fa-clock" id="info"></i> ${difference} Hari Lagi</small></div>`;
                        } else if (difference > 0 && difference <= 7) {
                            return `<div>${date}</div><div><small><i class="fas fa-exclamation-circle" id="warning"></i> ${difference} Hari Lagi</small></div>`;
                        } else {
                            return `<div>${date}</div><div><small><i class="fas fa-exclamation-circle" id="danger"></i> Batas Kontrak Habis</small></div>`;
                        }
                    } else {
                        return `<div class="text-danger">${date}</div><div class="text-danger"><small><i class="fas fa-check-circle" id="success"></i> Sudah Dikirim</small></div>`;
                    }
                }
            },
            totalHrg(detail) {
                let total = 0;
                detail.forEach(paket => {
                    total += this.subtotal(paket.jumlah, paket.harga, paket.ongkir);
                });
                return total;
            },
            async showChart(id, jenis) {
                this.tabs = true;
                this.loadingChart = false;
                await axios.get('/api/get_stok_pesanan', {
                    params: {
                        id,
                        jenis
                    }
                }).then(response => {
                    this.chartData = response.data;
                    this.loadingChart = true;
                })
            }
        },
        mounted() {
            this.getPenjualan();
        },
        computed: {
            checkChart() {
                if (this.chartData != null) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

</script>
