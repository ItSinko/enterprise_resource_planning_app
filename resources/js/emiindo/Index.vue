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
                                        <h5 class="card-title pl-2 py-2">
                                            <b v-if="detailModalSO.instansi == null">-</b>
                                            <b v-else>{{ detailModalSO.instansi.instansinm }}</b>
                                            </h5>
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
                                                                        <th rowspan="2">Ongkos Kirim</th>
                                                                        <th rowspan="2">Subtotal</th>
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
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp. {{ formatRupiah(parseInt(item.shippingcharge)) }}</td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp. {{ formatRupiah(subtotal(item.qty,item.price,item.shippingcharge)) }}</td>
                                                                    </tr>

                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="6">Total Harga
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="checklist(detailModalSO.epurno)">Checklist</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
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
                tab: 'salesorder',
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
            },

            subtotal(qty, price, ongkir) {
                return (parseInt(qty) * parseInt(price)) + parseInt(ongkir);
            },
            checklist(id) {
                this.dataSO.filter(item => {
                    if (item.epurno == id) {
                        this.checkEkat.push(item);
                    }
                });
                this.$refs.cbcheck.forEach(item => {
                    if (item.value == id) {
                        item.checked = true;
                    }
                });
            },
        },
        mounted() {
            this.loadData();
        },
        computed: {
            total() {
                let total = 0;
                this.detailModalSO.sodetail.forEach(item => {
                    total += this.subtotal(item.qty, item.price, item.shippingcharge);
                });
                return total;
            }
        },
        updated() {
            $('.tableSo').DataTable()
        },
    }

</script>
    <style scoped>

ul#status {
    padding: 0;
}
        ul#status li {
            /* float: left; */
            display:inline;
            padding: 0;
            list-style-type: none;
            margin: 0; /* To remove default bottom margin */
            /* margin: 10px; */
        }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }

        .separator {
            border-top: 1px solid #bbb;
            width: 90%;
        }

        .wb {
            word-break: break-all;
            white-space: normal;
        }

        .nowraptxt {
            white-space: nowrap;
        }

        .filter {
            margin: 5px;
        }

        /* thead {
            text-align: center;
        }

        td {
            text-align: center;
            white-space: nowrap;
        } */

        #urgent {
            color: #dc3545;
            font-weight: 600;
        }

        #warning {
            color: #FFC700;
            font-weight: 600;
        }

        #info {
            color: #3a7bb0;
            font-weight: 600;
        }

        .minimizechar {
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 13ch;
        }

        .hide {
            display: none;
        }

        .dropdown-toggle:hover {
            color: #4682B4;
        }

        .dropdown-toggle:active {
            color: #C0C0C0;
        }

        td.details-control {
            content: "\f055";
            font-family: FontAwesome;
            left: -5px;
            position: absolute;
            top: 0;
        }

        #detailekat {
            background-color: #E9DDE5;

        }

        #detailspa {
            background-color: #FFE6C9;
        }

        #detailspb {
            background-color: #E1EBF2;
            /* color: #7D6378; */

        }

        .tabnum {
            font-variant-numeric: tabular-nums;
        }

        .removeshadow {
            box-shadow: none;
        }

        .align-center {
            text-align: center;
        }

        .bordertopnone {
            border-top: 0;
            border-left: 0;
            border-right: 0;
            border-bottom: 0;
            vertical-align: top;
        }

        .margin {
            margin-left: 10px;
            margin-right: 10px;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .card-detail {
            align-items: center;
            flex-direction: row;
            shadow: none;
            border: none;
        }

        .card-detail img {
            width: 25%;
            border-top-right-radius: 0;
            border-bottom-left-radius: calc(0.25rem - 1px);
        }

        @media screen and (min-width: 1440px) {

            body {
                font-size: 14px;
            }

            #detailmodal {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .overflowy {
                max-height: 550px;
                width: auto;
                overflow-y: scroll;
                box-shadow: none;
            }

            .labelket {
                text-align: right;
            }
        }

        @media screen and (max-width: 1439px) {
            body {
                font-size: 12px;
            }

            h4 {
                font-size: 20px;
            }

            #detailmodal {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .overflowy {
                max-height: 450px;
                width: auto;
                overflow-y: scroll;
                box-shadow: none;
            }

            .labelket {
                text-align: right;
            }
        }

        @media screen and (max-width: 991px) {
            .labelket {
                text-align: left;
            }
        }

    </style>
