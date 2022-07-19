<template>
    <div>
        <div v-if="loading">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div class="container-fluid" v-else>
            <div class="col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="ekatalog-tab" data-toggle="tab" href="#ekatalog" role="tab"
                            aria-controls="ekatalog" aria-selected="false">Sales Order</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="spa-tab" data-toggle="tab" href="#spa" role="tab" aria-controls="spa"
                            aria-selected="false">Purchase Order</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="spb-tab" data-toggle="tab" href="#spb" role="tab" aria-controls="spb"
                            aria-selected="false">Delivery Order</a>
                    </li>
                </ul>
                <div class="tab-content card" id="myTabContent">
                    <div class="tab-pane fade show active card-body" id="ekatalog" role="tabpanel"
                        aria-labelledby="ekatalog-tab">
                        <table class="table tableSo">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nomor AKN</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, idx) in dataSO" :key="idx">
                                    <td><input type="checkbox" ref="cbcheck" :value="item.epurno" @click="checked"></td>
                                    <td>{{item.epurno}}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" @click="detail(item.epurno)"><i
                                                class="fas fa-eye"></i>
                                            Detail</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-check"></i> Proses</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade modalSO" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" v-if="modal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row filter">
                            <div class="col-12">
                                <div class="card card-detail removeshadow">
                                    <div class="card-body border-0">
                                        <h5 class="card-title pl-2 py-2"><b>{{ detailModalSO.instansi.instansinm }}</b></h5>
                                        <ul class="fa-ul card-text">
                                            <li class="py-2"><span class="fa-li"><i
                                                        class="fas fa-user-alt fa-fw"></i></span>
                                                {{ detailModalSO.satuankerja.customername }}
                                            </li>
                                            <li class="py-2"><span class="fa-li"><i class="fas fa-address-card fa-fw"></i></span>
                                                    {{ detailModalSO.satuankerja.address }}
                                            </li>
                                            <li class="py-2"><span class="fa-li"><i
                                                        class="fas fa-map-marker-alt fa-fw"></i></span>
                                                {{ detailModalSO.satuankerja.kota.provinsi.provnm }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card card-orange card-outline card-tabs">
                                    <div class="card-header p-0 pt-1 border-bottom-0">
                                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="tabs-detail2-tab" data-toggle="pill"
                                                    href="#tabs-detail2" role="tab" aria-controls="tabs-detail2"
                                                    aria-selected="true">Informasi</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="tabs-produk2-tab" data-toggle="pill"
                                                    href="#tabs-produk2" role="tab" aria-controls="tabs-produk2"
                                                    aria-selected="false">Produk</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-three-tabContent">
                                            <div class="tab-pane fade active show" id="tabs-detail2" role="tabpanel"
                                                aria-labelledby="tabs-detail2-tab">

                                                <div class="row d-flex justify-content-between">

                                                    <div class="p-2">
                                                        <div class="margin">
                                                            <div><small class="text-muted">No AKN</small></div>
                                                            <div><b>
                                                                    {{ detailModalSO.epurno }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <div class="margin">
                                                            <div><small class="text-muted">Deskripsi</small></div>
                                                            <div><b>
                                                                    {{ detailModalSO.namapaket }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="p-2">
                                                        <div class="margin">
                                                            <div><small class="text-muted">Tanggal Buat</small>
                                                            </div>
                                                            <div><b>
                                                                    {{ tgl_modal(detailModalSO.createdate) }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <div class="margin">
                                                            <div><small class="text-muted">Tanggal
                                                                    Edit</small></div>
                                                            <div><b>
                                                                    {{ tgl_modal(detailModalSO.editdate) }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-produk2" role="tabpanel"
                                                aria-labelledby="tabs-produk2-tab">
                                                <div class="table-responsive">
                                                    <div class="card removeshadow overflowy">
                                                        <div class="card-body">

                                                            <table class="table"
                                                                style="max-width:100%; overflow-x: hidden; background-color:white;"
                                                                id="tabledetailpesan">
                                                                <thead>
                                                                    <tr>
                                                                        <th rowspan="2">No</th>
                                                                        <th rowspan="2">Produk</th>
                                                                        <th colspan="2">Qty</th>
                                                                        <th rowspan="2">Harga</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th><i class="fas fa-shopping-cart"></i>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="(item, i) in detailModalSO.sodetail"
                                                                        :key="i">
                                                                        <td rowspan="1" class="nowraptxt">
                                                                            {{ i+1 }}
                                                                        </td>
                                                                        <td><b
                                                                                class="wb">{{ item.produk.productnm }}</b>
                                                                        </td>
                                                                        <td colspan="2" class="nowraptxt tabnum">
                                                                            {{ item.qty }} {{ item.datauom.uom }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp. {{ formatRupiah(parseInt(item.price)) }}</td>
                                                                    </tr>

                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="4">Total Harga
                                                                        </td>
                                                                        <td class="tabnum nowraptxt">Rp. {{ formatRupiah(total) }}
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                loading: true,
                dataSO: [],
                detailModalSO: [],
                modal: false,
                checkAllStatus: false,
                tab: 'ekatalog',
                checkEkat: [],
                checkSpa: [],
            }
        },
        methods: {
            async loadData() {
                try {
                    await axios.get('https://sinko.api.hyperdatasystem.com/api/salesorder', {
                        headers: {
                            Authorization: 'Bearer ' + sessionStorage.getItem('token')
                        }
                    }).then(response => {
                        this.dataSO = response.data
                        this.loading = false
                    })
                } catch (error) {
                    console.log(error);
                }
            },
            detail(id) {
                this.detailModalSO = this.dataSO.find(item => item.epurno == id);
                this.modal = true;
                setTimeout(() => {
                    $('.modalSO').modal('show');
                }, 100);
            },
            tgl_modal(tgl) {
                return moment(tgl).format('DD MMMM YYYY');
            },
            checkbox(id) {
                if (this.checkEkat.includes(id)) {
                    this.checkEkat.splice(this.checkEkat.indexOf(id), 1);
                } else {
                    this.checkEkat.push(id);
                }
            },
            checked(e) {
                if (e.target.checked) {
                    this.dataSO.filter(item => {
                        if (item.epurno == e.target.value) {
                            this.checkEkat.push(item);
                        }
                    })
                } else {
                    this.checkEkat.filter(item => {
                        if (item.epurno == e.target.value) {
                            this.checkEkat.splice(this.checkEkat.indexOf(item), 1);
                        }
                    });
                }
            },
            formatRupiah(e){
                const format = e.toString().split('').reverse().join('');
                const convert = format.match(/\d{1,3}/g);
                return convert.join('.').split('').reverse().join('');
            }

        },
        mounted() {
            this.loadData();
        },
        computed: {
            total() {
                return this.detailModalSO.sodetail.reduce((a, b) => a + parseInt(b.price), 0);
            }
        },
        updated() {
            $('.tableSo').DataTable()
        },
    }

</script>
