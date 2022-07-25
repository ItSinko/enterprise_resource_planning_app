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
                        <a class="nav-link active" id="salesorder-tab" data-toggle="tab" href="#salesorder" role="tab"
                            aria-controls="salesorder" aria-selected="false" @click="tab = 'salesorder'">Sales Order</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="purchaseorder-tab" data-toggle="tab" href="#purchaseorder" role="tab"
                            aria-controls="purchaseorder" @click="tab = 'purchaseorder'" aria-selected="false">Purchase
                            Order</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="deliveryorder-tab" data-toggle="tab" href="#deliveryorder" role="tab"
                            aria-controls="deliveryorder" @click="tab = 'deliveryorder'" aria-selected="false">Delivery
                            Order</a>
                    </li>
                </ul>
                <div class="tab-content card" id="myTabContent">
                    <div class="tab-pane fade show active card-body" id="salesorder" role="tabpanel"
                        aria-labelledby="salesorder-tab" v-show="tab = 'salesorder'">
                        <table class="table tableSo">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nomor AKN</th>
                                    <!-- <th>Nama Instansi</th> -->
                                    <th>Nama Customer</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, idx) in dataSO" :key="idx">
                                    <td><input type="checkbox" ref="cbcheck" :value="item.epurno" @click="checked"></td>
                                    <td>{{item.epurno}}</td>
                                    <!-- <td>
                                            <span v-if="item.instansi == null">BELUM DIKETAHUI</span>
                                            <span v-else>{{ item.instansi.instansinm }}</span>
                                    </td> -->
                                    <td>{{ item.satuankerja.customername }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" @click="detail(item.epurno)"><i
                                                class="fas fa-eye"></i>
                                            Detail</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade card-body" id="purchaseorder" role="tabpanel"
                        aria-labelledby="purchaseorder-tab" v-show="tab = 'purchaseorder'">
                        <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-ekatalog-tab" data-toggle="pill"
                                    href="#pills-ekatalog" role="tab" aria-controls="pills-ekatalog"
                                    @click="tabPO = 'ekat'" aria-selected="true">E-Katalog</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-spa-tab" data-toggle="pill" href="#pills-spa" role="tab"
                                    @click="poNonEkat" aria-controls="pills-selesai_kirim" aria-selected="false">SPA</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-ekatalog" role="tabpanel"
                                v-show="tabPO == 'ekat'" aria-labelledby="pills-ekatalog-tab">
                                <table class="table tablePoEKat">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nomor AKN</th>
                                            <th>Nomor PO</th>
                                            <th>Tanggal PO</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, idx) in dataPOEkat" :key="idx">
                                            <td>
                                                <div v-if="!item.salesorderno"></div>
                                                <input v-else type="checkbox" ref="cbcheckpoekat" :value="item.poid"
                                                    @click="checkedpoekat"></td>
                                            <td>
                                                <span v-if="!item.salesorderno">-</span>
                                                <span v-else>{{item.salesorderno}}</span>
                                            </td>
                                            <td>{{item.pono}}</td>
                                            <td>{{ tgl_format(item.podate) }}</td>
                                            <td>
                                                <div v-if="!item.salesorderno"></div>
                                                <button v-else class="btn btn-sm btn-outline-primary"
                                                    @click="detailPOEkat(item.poid)"><i class="fas fa-eye"></i>
                                                    Detail</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="pills-spa" role="tabpanel" v-show="tabPO == 'nonekat'"
                                aria-labelledby="pills-spa-tab">
                                <table class="table" id="tablePoNonEKat">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Nomor AKN</th>
                                            <th>Nomor PO</th>
                                            <th>Tanggal PO</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, idx) in dataPONonEkat" :key="idx">
                                            <td><input type="checkbox" ref="cbcheckpononekat" :value="item.poid"
                                                    @click="checkedpononekat"></td>
                                            <td>
                                                <span v-if="!item.salesorderno">-</span>
                                                <span v-else>{{item.salesorderno}}</span>
                                            </td>
                                            <td>{{item.pono}}</td>
                                            <td>{{ tgl_format(item.podate) }}</td>
                                            <td>
                                                <div v-if="item.pono == '0'"></div>
                                                <button v-else class="btn btn-sm btn-outline-primary"
                                                    @click="detailPONonEkat(item.poid)"><i class="fas fa-eye"></i>
                                                    Detail</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card-body" id="deliveryorder" role="tabpanel"
                        aria-labelledby="deliveryorder-tab" v-show="tab = 'deliveryorder'">
                        <table class="table tableDO">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nomor DO</th>
                                    <th>Nomor PO</th>
                                    <th>Tanggal DO</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, idx) in dataDO" :key="idx">
                                    <td><input type="checkbox" ref="cbcheckdo" :value="item.purchaseorder.pono" @click="checkeddo"></td>
                                    <td>{{item.dono}}</td>
                                    <td>{{item.purchaseorder.pono}}</td>
                                    <td>{{ tgl_format(item.dodate) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"
                                            @click="detailDO(item.purchaseorder.poid)"><i class="fas fa-eye"></i>
                                            Detail</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i> Tolak</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal SO -->
        <div class="modal fade modalSO" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" v-if="modalSO">
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
                                            <b>
                                                {{ detailModalSO.epurno }}
                                            </b>
                                        </h5>
                                        <ul class="fa-ul card-text">
                                            <li class="py-2"><span class="fa-li"><i
                                                        class="fas fa-user-alt fa-fw"></i></span>
                                                {{ detailModalSO.satuankerja.customername }}
                                            </li>
                                            <li class="py-2"><span class="fa-li"><i
                                                        class="fas fa-address-card fa-fw"></i></span>
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
                                                            <div><small class="text-muted">Nama Instansi</small></div>
                                                            <div>
                                                                <b v-if="detailModalSO.instansi == null">-</b>
                                                                <b v-else>{{ detailModalSO.instansi.instansinm }}</b>
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
                                                                    {{ tgl_format(detailModalSO.createdate) }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <div class="margin">
                                                            <div><small class="text-muted">Tanggal
                                                                    Edit</small></div>
                                                            <div><b>
                                                                    {{ tgl_format(detailModalSO.editdate) }}
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
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(parseInt(item.price)) }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(parseInt(item.shippingcharge)) }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(subtotal(item.qty,item.price,item.shippingcharge)) }}
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="6">Total Harga
                                                                        </td>
                                                                        <td class="tabnum nowraptxt">Rp.
                                                                            {{ formatRupiah(total) }}
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
                        <button type="button" class="btn btn-primary"
                            @click="checklist(detailModalSO.epurno)">Daftarkan</button>
                        <button type="button" class="btn btn-success"
                            @click="tambah(detailModalSO.epurno)">Terima</button>
                        <button type="button" class="btn btn-danger" @click="batal(detailModalSO.epurno)">Tolak</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal PO Ekatalog -->
        <div class="modal fade modalPOEkat" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" v-if="modalPOEkat">
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
                                            <b>
                                                {{ detailModalPOEkat.PO.salesorderno }}
                                            </b>
                                        </h5>
                                        <ul class="fa-ul card-text">
                                            <li class="py-2"><span class="fa-li"><i
                                                        class="fas fa-user-alt fa-fw"></i></span>
                                                {{ detailModalPOEkat.perusahaan.satuankerja.customername }}
                                            </li>
                                            <li class="py-2"><span class="fa-li"><i
                                                        class="fas fa-address-card fa-fw"></i></span>
                                                {{ detailModalPOEkat.perusahaan.satuankerja.address }}
                                            </li>
                                            <li class="py-2"><span class="fa-li"><i
                                                        class="fas fa-map-marker-alt fa-fw"></i></span>
                                                {{ detailModalPOEkat.perusahaan.satuankerja.kota.provinsi.provnm }}
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
                                                            <div><small class="text-muted">Nomor PO</small></div>
                                                            <div>
                                                                <b>{{ detailModalPOEkat.PO.pono }}</b>
                                                            </div>
                                                        </div>
                                                        <div class="margin">
                                                            <div><small class="text-muted">Deskripsi</small></div>
                                                            <div><b>
                                                                    {{ detailModalPOEkat.perusahaan.namapaket }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="p-2">
                                                        <div class="margin">
                                                            <div><small class="text-muted">Tanggal Buat</small>
                                                            </div>
                                                            <div><b>
                                                                    {{ tgl_format(detailModalPOEkat.PO.createdate) }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <div class="margin">
                                                            <div><small class="text-muted">Tanggal PO</small></div>
                                                            <div><b>
                                                                    {{ tgl_format(detailModalPOEkat.PO.podate) }}
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
                                                                        <th rowspan="2">Diskon</th>
                                                                        <th rowspan="2">Subtotal</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th><i class="fas fa-shopping-cart"></i>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="(item, i) in detailModalPOEkat.PO.purchaseorderdetail"
                                                                        :key="i">
                                                                        <td rowspan="1" class="nowraptxt">
                                                                            {{ i+1 }}
                                                                        </td>
                                                                        <td><b
                                                                                class="wb">{{ item.produk.productnm }}</b>
                                                                        </td>
                                                                        <td colspan="2" class="nowraptxt tabnum">
                                                                            {{ item.qty }} {{ item.uom.uom }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(parseInt(item.price)) }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">
                                                                            {{ parseInt(item.discount) }} %</td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(subtotalPO(item.qty,item.price,item.discount)) }}
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="6">Total Harga
                                                                        </td>
                                                                        <td class="tabnum nowraptxt">Rp.
                                                                            {{ formatRupiah(totalPO("ekat")) }}
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
                        <button type="button" class="btn btn-primary" @click="checklistpoekat(detailModalPOEkat.PO.poid)">Daftarkan</button>
                        <button type="button" class="btn btn-success" @click="tambahlistpoekat(detailModalPOEkat.PO.poid)">Terima</button>
                        <button type="button" class="btn btn-danger" @click="batallistpoekat(detailModalPOEkat.PO.poid)">Tolak</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Po Non Ekatalog -->
        <div class="modal fade modalPONonEkat" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" v-if="modalPONonEkat">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail PO SPA {{ detailModalPONonEkat.pono }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row filter">
                            <div class="col-12">
                                <div class="card card-orange card-outline card-tabs">
                                    <div class="card-header p-0 pt-1 border-bottom-0">
                                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="tabs-detail3-tab" data-toggle="pill"
                                                    href="#tabs-detail3" role="tab" aria-controls="tabs-detail3"
                                                    aria-selected="true">Informasi</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="tabs-produk3-tab" data-toggle="pill"
                                                    href="#tabs-produk3" role="tab" aria-controls="tabs-produk3"
                                                    aria-selected="false">Produk</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-three-tabContent">
                                            <div class="tab-pane fade active show" id="tabs-detail3" role="tabpanel"
                                                aria-labelledby="tabs-detail3-tab">

                                                <div class="row d-flex justify-content-between">

                                                    <div class="p-2">
                                                        <div class="margin">
                                                            <div><small class="text-muted">Nomor PO</small></div>
                                                            <div>
                                                                <b>{{ detailModalPONonEkat.pono }}</b>
                                                            </div>
                                                        </div>
                                                        <div class="margin">
                                                            <div><small class="text-muted">Nomor AKN</small></div>
                                                            <div v-if="detailModalPONonEkat.salesorderno">
                                                                <b>
                                                                    {{ detailModalPONonEkat.salesorderno }}
                                                                </b>
                                                            </div>
                                                            <div v-else></div>
                                                        </div>
                                                    </div>
                                                    <div class="p-2">
                                                        <div class="margin">
                                                            <div><small class="text-muted">Tanggal Buat</small>
                                                            </div>
                                                            <div><b>
                                                                    {{ tgl_format(detailModalPONonEkat.createdate) }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                        <div class="margin">
                                                            <div><small class="text-muted">Tanggal PO</small></div>
                                                            <div><b>
                                                                    {{ tgl_format(detailModalPONonEkat.podate) }}
                                                                </b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tabs-produk3" role="tabpanel"
                                                aria-labelledby="tabs-produk3-tab">
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
                                                                        <th rowspan="2">Diskon</th>
                                                                        <th rowspan="2">Subtotal</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th><i class="fas fa-shopping-cart"></i>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr v-for="(item, i) in detailModalPONonEkat.purchaseorderdetail"
                                                                        :key="i">
                                                                        <td rowspan="1" class="nowraptxt">
                                                                            {{ i+1 }}
                                                                        </td>
                                                                        <td><b
                                                                                class="wb">{{ item.produk.productnm }}</b>
                                                                        </td>
                                                                        <td colspan="2" class="nowraptxt tabnum">
                                                                            {{ item.qty }} {{ item.uom.uom }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(parseInt(item.price)) }}
                                                                        </td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">
                                                                            {{ parseInt(item.discount) }} %</td>
                                                                        <td rowspan="1" class="nowraptxt tabnum">Rp.
                                                                            {{ formatRupiah(subtotalPO(item.qty,item.price,item.discount)) }}
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="6">Total Harga
                                                                        </td>
                                                                        <td class="tabnum nowraptxt">Rp.
                                                                            {{ formatRupiah(totalPO("nonekat")) }}
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
                        <button type="button" class="btn btn-primary" @click="checklistpononekat(detailModalPONonEkat.poid)">Daftarkan</button>
                        <button type="button" class="btn btn-success" @click="tambah(detailModalPONonEkat.poid)">Terima</button>
                        <button type="button" class="btn btn-danger" @click="batal(detailModalPONonEkat.poid)">Tolak</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal DO -->
        <div class="modal fade modalDO" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" v-if="modalDO">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="card">
                                    <div class="card-header">Info Pesanan</div>
                                    <div class="card-body">
                                        <div class="margin">
                                            <a class="text-muted">No AKN</a>
                                            <b class="float-right">-</b>
                                        </div>

                                        <div class="margin">
                                            <a class="text-muted">No PO</a>
                                            <b class="float-right">
                                                {{ detailModalDO.purchaseorder.pono }}
                                            </b>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="alert alert-danger" role="alert">
                                            <i class="fas fa-exclamation-triangle"></i> <strong>Catatan: </strong> {{ detailModalDO.donote }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-12">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header"><i class="fas fa-edit"></i> Ubah No Urut</div>
                                        <div class="card-body">
                                            <div class="form-horizontal">
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">No
                                                        Urut</label>
                                                    <div class="col-lg-3 col-md-12">
                                                        <input type="number" class="form-control col-form-label"
                                                            name="no_urut" id="no_urut">
                                                        <div class="invalid-feedback" id="msgno_urut"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">No
                                                        DO</label>
                                                    <div class="col-lg-5 col-md-12">
                                                        <input type="text" class="form-control col-form-label"
                                                            name="no_do" id="no_do" :value="detailModalDO.dono" readonly>
                                                        <div class="invalid-feedback" id="msgno_do"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for=""
                                                        class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                        DO</label>
                                                    <div class="col-lg-5 col-md-12">
                                                        <input type="text" class="form-control col-form-label"
                                                            name="tgl_do" id="tgl_do" :value="tgl_format(detailModalDO.dodate)" readonly>
                                                        <div class="invalid-feedback" id="msgtgl_do"></div>
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
                        <button type="button" class="btn btn-primary" @click="checklistdo(detailModalDO.purchaseorder.pono)">Daftarkan</button>
                        <button type="button" class="btn btn-success" @click="tambahdo(detailModalDO.purchaseorder.pono)">Terima</button>
                        <button type="button" class="btn btn-danger" @click="bataldo(detailModalDO.purchaseorder.pono)">Tolak</button>
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
                tab: 'salesorder',
                // SO
                dataSO: [],
                detailModalSO: [],
                modalSO: false,
                checkEkat: [],
                // PO
                dataPOEkat: [],
                dataPONonEkat: [],
                detailModalPOEkat: [],
                detailModalPONonEkat: [],
                modalPOEkat: false,
                modalPONonEkat: false,
                checkEkatPO: [],
                checkNonEkatPO: [],
                tabPO: 'ekat',
                // DO
                dataDO: [],
                detailModalDO: [],
                modalDO: false,
                checkDO: [],
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
                    })

                    await axios.get('https://sinko.api.hyperdatasystem.com/api/purchaseorder?type=ECAT', {
                        headers: {
                            Authorization: 'Bearer ' + sessionStorage.getItem('token')
                        }
                    }).then(response => {
                        this.dataPOEkat = response.data
                    })

                    await axios.get('https://sinko.api.hyperdatasystem.com/api/purchaseorder?type=NONECAT', {
                        headers: {
                            Authorization: 'Bearer ' + sessionStorage.getItem('token')
                        }
                    }).then(response => {
                        this.dataPONonEkat = response.data
                    })

                    await axios.get('https://sinko.api.hyperdatasystem.com/api/deliveryorder', {
                        headers: {
                            Authorization: 'Bearer ' + sessionStorage.getItem('token')
                        }
                    }).then(response => {
                        this.dataDO = response.data
                    })
                    this.loading = false
                } catch (error) {
                    console.log(error);
                }
            },
            detail(id) {
                this.detailModalSO = this.dataSO.find(item => item.epurno == id);
                this.modalSO = true;
                setTimeout(() => {
                    $('.modalSO').modal('show');
                }, 100);
            },
            detailPOEkat(id) {
                let dataPO = this.dataPOEkat.find(item => item.poid == id);
                let dataPerusahaanPO = this.dataSO.find(item => item.epurno == dataPO.salesorderno);
                this.detailModalPOEkat = {
                    'PO': dataPO,
                    'perusahaan': dataPerusahaanPO
                };
                this.modalPOEkat = true;
                setTimeout(() => {
                    $('.modalPOEkat').modal('show');
                }, 100);
            },
            detailPONonEkat(id) {
                this.detailModalPONonEkat = this.dataPONonEkat.find(item => item.poid == id);
                this.modalPONonEkat = true;
                setTimeout(() => {
                    $('.modalPONonEkat').modal('show');
                }, 100);
            },
            detailDO(id) {
                console.log(id);
                this.detailModalDO = this.dataDO.find(item => item.purchaseorder.poid == id);
                console.log(this.detailModalDO);
                this.modalDO = true;
                setTimeout(() => {
                    $('.modalDO').modal('show');
                }, 100);
            },
            tgl_format(tgl) {
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
            checkedpoekat(e) {
                if (e.target.checked) {
                    this.dataPOEkat.filter(item => {
                        if (item.poid == e.target.value) {
                            this.checkEkatPO.push(item);
                        }
                    })
                } else {
                    this.checkEkatPO.filter(item => {
                        if (item.poid == e.target.value) {
                            this.checkEkatPO.splice(this.checkEkatPO.indexOf(item), 1);
                        }
                    });
                }
            },
            checkedpononekat(e){
                if (e.target.checked) {
                    this.dataPONonEkat.filter(item => {
                        if (item.poid == e.target.value) {
                            this.checkNonEkatPO.push(item);
                        }
                    })
                } else {
                    this.checkNonEkatPO.filter(item => {
                        if (item.poid == e.target.value) {
                            this.checkNonEkatPO.splice(this.checkNonEkatPO.indexOf(item), 1);
                        }
                    });
                }
            },
            checkeddo(e){
                if (e.target.checked) {
                    this.dataDO.filter(item => {
                        if (item.dono == e.target.value) {
                            this.checkDO.push(item);
                        }
                    })
                } else {
                    this.checkDO.filter(item => {
                        if (item.dono == e.target.value) {
                            this.checkDO.splice(this.checkDO.indexOf(item), 1);
                        }
                    });
                }
            },
            formatRupiah(e) {
                const format = e.toString().split('').reverse().join('');
                const convert = format.match(/\d{1,3}/g);
                return convert.join('.').split('').reverse().join('');
            },

            subtotal(qty, price, ongkir) {
                return (parseInt(qty) * parseInt(price)) + parseInt(ongkir);
            },
            subtotalPO(qty, price, discount) {
                return (parseInt(qty) * parseInt(price) * ((100 - parseInt(discount)) / 100));
            },
            totalPO(type) {
                let total = 0;
                if (type == 'ekat') {
                    this.detailModalPOEkat.PO.purchaseorderdetail.forEach(item => {
                        total += this.subtotalPO(item.qty, item.price, item.discount);
                    });
                } else {
                    this.detailModalPONonEkat.purchaseorderdetail.forEach(item => {
                        total += this.subtotalPO(item.qty, item.price, item.discount);
                    });
                }
                return total;
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
            checklistpoekat(id){
                this.dataPOEkat.filter(item => {
                    if (item.poid == id) {
                        this.checkEkatPO.push(item);
                    }
                });
                this.$refs.cbcheckpoekat.forEach(item => {
                    if (item.value == id) {
                        item.checked = true;
                    }
                });
            },
            checklistpononekat(id){
                this.dataPONonEkat.filter(item => {
                    if (item.poid == id) {
                        this.checkNonEkatPO.push(item);
                    }
                });
                this.$refs.cbcheckpononekat.forEach(item => {
                    if (item.value == id) {
                        item.checked = true;
                    }
                });
            },
            checklistdo(id){
                this.dataDO.filter(item => {
                    if (item.purchaseorder.pono == id) {
                        this.checkDO.push(item);
                    }
                });
                this.$refs.cbcheckdo.forEach(item => {
                    if (item.value == id) {
                        item.checked = true;
                    }
                });
            },
            poNonEkat() {
                this.tabPO = 'nonekat'
                console.log($('#tablePoNonEKat').DataTable());
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
            },
        },
        updated() {
            $('.tableSo').DataTable();
            $('.tablePoEKat').DataTable();
            $('.tableDO').DataTable();
        },
    }

</script>
<style scoped>
    ul#status {
        padding: 0;
    }

    ul#status li {
        /* float: left; */
        display: inline;
        padding: 0;
        list-style-type: none;
        margin: 0;
        /* To remove default bottom margin */
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
