<template>
    <div>
        <div v-if="loading">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <section class="content" v-else>
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div
                            class="card"
                            id="ekatalog"
                            v-if="
                                this.$route.params.jenis != 'nonekatpo' &&
                                ekat != null
                            "
                        >
                            <div class="card-body">
                                <h4 class="margin">Data Penjualan</h4>
                                <div class="row">
                                    <div class="col-lg-11 col-md-12">
                                        <div
                                            class="row d-flex justify-content-between"
                                        >
                                            <div class="p-2 cust">
                                                <div>
                                                    <div class="margin">
                                                        <small
                                                            class="text-muted"
                                                            >Info
                                                            Instansi</small
                                                        >
                                                    </div>
                                                    <div class="margin">
                                                        <b
                                                            v-if="
                                                                ekat.instansi ==
                                                                null
                                                            "
                                                            >-</b
                                                        >
                                                        <b v-else>{{
                                                            ekat.instansi
                                                                .instansinm
                                                        }}</b>
                                                    </div>
                                                    <div class="margin">
                                                        <b>{{
                                                            ekat.satuankerja
                                                                .customername
                                                        }}</b>
                                                    </div>
                                                    <div class="margin">
                                                        <b>{{
                                                            ekat.satuankerja
                                                                .kota.provinsi
                                                                .provnm
                                                        }}</b>
                                                    </div>
                                                </div>
                                                <div class="margin-top">
                                                    <div class="margin">
                                                        <small
                                                            class="text-muted"
                                                            >Info
                                                            Customer</small
                                                        >
                                                    </div>
                                                    <div class="margin">
                                                        <b
                                                            >PT. EMIINDO Jaya
                                                            Bersama</b
                                                        >
                                                    </div>
                                                    <div class="margin">
                                                        <b
                                                            >Komplek Perkantoran
                                                            Pulomas Jalan
                                                            Perintis Kemerdekaan
                                                            10 No. 8, pulo
                                                            Gadung, Jakarta
                                                            Timur, DKI
                                                            Jakarta</b
                                                        >
                                                    </div>
                                                    <div class="margin">
                                                        <b>DKI Jakarta</b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div>
                                                        <small
                                                            class="text-muted"
                                                            >No SO</small
                                                        >
                                                    </div>
                                                    <div><b>-</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div>
                                                        <small
                                                            class="text-muted"
                                                            >No AKN</small
                                                        >
                                                    </div>
                                                    <div>
                                                        <b>{{ ekat.epurno }}</b>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div>
                                                        <small
                                                            class="text-muted"
                                                            >Tanggal
                                                            Order</small
                                                        >
                                                    </div>
                                                    <div>
                                                        <b>{{
                                                            tgl_format(
                                                                ekat.createdate
                                                            )
                                                        }}</b>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div>
                                                        <small
                                                            class="text-muted"
                                                            >Tanggal Batas
                                                            Kontrak</small
                                                        >
                                                    </div>
                                                    <div><b>-</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div>
                                                        <small
                                                            class="text-muted"
                                                            >Status</small
                                                        >
                                                    </div>
                                                    <div
                                                        v-if="
                                                            this.$route.params
                                                                .jenis ==
                                                            'ekatso'
                                                        "
                                                    >
                                                        <div v-html="status(forminfoakn
                                                                    .deskripsi
                                                                    .status)"></div>
                                                    </div>
                                                    <div
                                                        class="badge green-text"
                                                        v-else
                                                    >
                                                        Sepakat
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div>
                                                        <small
                                                            class="text-muted"
                                                            >No PO</small
                                                        >
                                                    </div>
                                                    <div>
                                                        <b v-if="po == null"
                                                            >-</b
                                                        >
                                                        <b v-else>{{
                                                            po.pono
                                                        }}</b>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div>
                                                        <small
                                                            class="text-muted"
                                                            >Tanggal PO</small
                                                        >
                                                    </div>
                                                    <div>
                                                        <b v-if="po == null"
                                                            >-</b
                                                        >
                                                        <b v-else>{{
                                                            tgl_format(
                                                                po.podate
                                                            )
                                                        }}</b>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div>
                                                        <small
                                                            class="text-muted"
                                                            >No DO</small
                                                        >
                                                    </div>
                                                    <div>
                                                        <b v-if="Do == null"
                                                            >-</b
                                                        >
                                                        <b v-else>{{
                                                            Do.dono
                                                        }}</b>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div>
                                                        <small
                                                            class="text-muted"
                                                            >Tanggal DO</small
                                                        >
                                                    </div>
                                                    <div>
                                                        <b v-if="Do == null"
                                                            >-</b
                                                        >
                                                        <b v-else>{{
                                                            tgl_format(
                                                                Do.dodate
                                                            )
                                                        }}</b>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-warning">
                                <div class="card-title">Form Tambah Data</div>
                            </div>
                            <div class="card-body">
                                <div class="content">
                                    <form method="post">
                                        <div
                                            class="row d-flex justify-content-center"
                                            id="akn"
                                            v-if="
                                                this.$route.params.jenis ==
                                                'ekatso'
                                            "
                                        >
                                            <div class="col-lg-11 col-md-12">
                                                <h4>Info AKN</h4>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <ul
                                                            class="nav nav-pills mb-3 nav-justified"
                                                            id="pills-tab"
                                                            role="tablist"
                                                        >
                                                            <li
                                                                class="nav-item"
                                                                role="presentation"
                                                            >
                                                                <a
                                                                    class="nav-link active"
                                                                    id="pills-penjualan-tab"
                                                                    data-toggle="pill"
                                                                    href="#pills-penjualan"
                                                                    role="tab"
                                                                    aria-controls="pills-penjualan"
                                                                    aria-selected="true"
                                                                    >Deskripsi
                                                                    Ekatalog</a
                                                                >
                                                            </li>
                                                            <li
                                                                class="nav-item"
                                                                role="presentation"
                                                            >
                                                                <a
                                                                    class="nav-link"
                                                                    id="pills-instansi-tab"
                                                                    data-toggle="pill"
                                                                    href="#pills-instansi"
                                                                    role="tab"
                                                                    aria-controls="pills-instansi"
                                                                    aria-selected="false"
                                                                    >Instansi</a
                                                                >
                                                            </li>
                                                        </ul>

                                                        <div
                                                            class="form-horizontal"
                                                        >
                                                            <div
                                                                class="tab-content"
                                                                id="pills-tabContent"
                                                            >
                                                                <div
                                                                    class="tab-pane fade show active"
                                                                    id="pills-penjualan"
                                                                    role="tabpanel"
                                                                    aria-labelledby="pills-penjualan-tab"
                                                                >
                                                                    <div
                                                                        class="card removeshadow"
                                                                    >
                                                                        <div
                                                                            class="card-body"
                                                                        >
                                                                            <div
                                                                                class="form-group row"
                                                                            >
                                                                                <label
                                                                                    for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                                    >No
                                                                                    Urut</label
                                                                                >
                                                                                <div
                                                                                    class="col-lg-2 col-md-4"
                                                                                >
                                                                                    <input
                                                                                        type="number"
                                                                                        class="form-control col-form-label"
                                                                                        :class="{
                                                                                            'is-invalid':
                                                                                                errorinput
                                                                                                    .deskripsi
                                                                                                    .nourut,
                                                                                        }"
                                                                                        :disabled="
                                                                                            checknourut
                                                                                        "
                                                                                        name="no_urut"
                                                                                        id="no_urut"
                                                                                        v-model="
                                                                                            forminfoakn
                                                                                                .deskripsi
                                                                                                .nourut
                                                                                        "
                                                                                        @keyup="
                                                                                            nourut(
                                                                                                forminfoakn
                                                                                                    .deskripsi
                                                                                                    .nourut
                                                                                            )
                                                                                        "
                                                                                    />
                                                                                    <div
                                                                                        class="invalid-feedback"
                                                                                    >
                                                                                        no
                                                                                        urut
                                                                                        ada
                                                                                        yang
                                                                                        sama
                                                                                        atau
                                                                                        belum
                                                                                        diisi
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="form-group row"
                                                                            >
                                                                                <label
                                                                                    for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                                    >Status</label
                                                                                >
                                                                                <div
                                                                                    class="col-lg-5 col-md-12 col-form-label"
                                                                                >
                                                                                    <div
                                                                                        class="form-check form-check-inline"
                                                                                    >
                                                                                        <input
                                                                                            class="form-check-input"
                                                                                            type="radio"
                                                                                            name="status_akn"
                                                                                            id="status_akn1"
                                                                                            value="sepakat"
                                                                                            checked="checked"
                                                                                            v-model="
                                                                                                forminfoakn
                                                                                                    .deskripsi
                                                                                                    .status
                                                                                            "
                                                                                        />
                                                                                        <label
                                                                                            class="form-check-label"
                                                                                            for="status_akn1"
                                                                                            >Sepakat</label
                                                                                        >
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline"
                                                                                    >
                                                                                        <input
                                                                                            class="form-check-input"
                                                                                            type="radio"
                                                                                            name="status_akn"
                                                                                            id="status_akn2"
                                                                                            value="negosiasi"
                                                                                            v-model="
                                                                                                forminfoakn
                                                                                                    .deskripsi
                                                                                                    .status
                                                                                            "
                                                                                        />
                                                                                        <label
                                                                                            class="form-check-label"
                                                                                            for="status_akn2"
                                                                                            >Negosiasi</label
                                                                                        >
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline"
                                                                                    >
                                                                                        <input
                                                                                            class="form-check-input"
                                                                                            type="radio"
                                                                                            name="status_akn"
                                                                                            id="status_akn3"
                                                                                            value="batal"
                                                                                            v-model="
                                                                                                forminfoakn
                                                                                                    .deskripsi
                                                                                                    .status
                                                                                            "
                                                                                        />
                                                                                        <label
                                                                                            class="form-check-label"
                                                                                            for="status_akn3"
                                                                                            >Batal</label
                                                                                        >
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="form-group row"
                                                                            >
                                                                                <label
                                                                                    for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                                    >Tanggal
                                                                                    Buat</label
                                                                                >
                                                                                <div
                                                                                    class="col-lg-4 col-md-4"
                                                                                >
                                                                                    <input
                                                                                        type="date"
                                                                                        class="form-control col-form-label"
                                                                                        name="tgl_buat"
                                                                                        id="tgl_buat"
                                                                                        v-model="
                                                                                            forminfoakn
                                                                                                .deskripsi
                                                                                                .tgl_buat
                                                                                        "
                                                                                    />
                                                                                    <div
                                                                                        class="invalid-feedback"
                                                                                        id="msgtgl_buat"
                                                                                    ></div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="form-group row"
                                                                            >
                                                                                <label
                                                                                    for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                                    >Tanggal
                                                                                    Edit</label
                                                                                >
                                                                                <div
                                                                                    class="col-lg-4 col-md-4"
                                                                                >
                                                                                    <input
                                                                                        type="date"
                                                                                        class="form-control col-form-label"
                                                                                        name="tgl_edit"
                                                                                        id="tgl_edit"
                                                                                        v-model="
                                                                                            forminfoakn
                                                                                                .deskripsi
                                                                                                .tgl_edit
                                                                                        "
                                                                                        :max="
                                                                                            datemax
                                                                                        "
                                                                                    />
                                                                                    <div
                                                                                        class="invalid-feedback"
                                                                                        id="msgtgl_edit"
                                                                                    ></div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="form-group row"
                                                                            >
                                                                                <label
                                                                                    for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                                    >Tanggal
                                                                                    Delivery</label
                                                                                >
                                                                                <div
                                                                                    class="col-lg-4 col-md-4"
                                                                                >
                                                                                    <input
                                                                                        type="date"
                                                                                        class="form-control col-form-label"
                                                                                        name="batas_kontrak"
                                                                                        id="batas_kontrak"
                                                                                        v-model="
                                                                                            forminfoakn
                                                                                                .deskripsi
                                                                                                .tgl_delivery
                                                                                        "
                                                                                        :min="
                                                                                            datemax
                                                                                        "
                                                                                    />
                                                                                    <div
                                                                                        class="invalid-feedback"
                                                                                        id="msgbatas_kontrak"
                                                                                    ></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="tab-pane fade show"
                                                                    id="pills-instansi"
                                                                    role="tabpanel"
                                                                    aria-labelledby="pills-instansi-tab"
                                                                >
                                                                    <div
                                                                        class="card removeshadow"
                                                                    >
                                                                        <div
                                                                            class="card-header"
                                                                        >
                                                                            <h6>
                                                                                Rencana
                                                                                Penjualan
                                                                            </h6>
                                                                        </div>
                                                                        <div
                                                                            class="card-body"
                                                                        >
                                                                            <div
                                                                                class="form-group row"
                                                                            >
                                                                                <label
                                                                                    for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                                    >Instansi</label
                                                                                >
                                                                                <div
                                                                                    class="col-lg-7 col-md-12 autocomplete"
                                                                                >
                                                                                    <input
                                                                                        type="text"
                                                                                        name="instansi"
                                                                                        id="instansi"
                                                                                        class="form-control col-form-label ui-autocomplete-input"
                                                                                        v-model="
                                                                                            forminfoakn
                                                                                                .instansi
                                                                                                .nminstansi
                                                                                        "
                                                                                        autocomplete="off"
                                                                                    />
                                                                                    <div
                                                                                        class="invalid-feedback"
                                                                                        id="msginstansi"
                                                                                    ></div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="form-group row"
                                                                            >
                                                                                <label
                                                                                    for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                                    >Satuan
                                                                                    Kerja</label
                                                                                >
                                                                                <div
                                                                                    class="col-lg-7 col-md-12"
                                                                                >
                                                                                    <input
                                                                                        type="text"
                                                                                        class="form-control col-form-label ui-autocomplete-input"
                                                                                        name="satuan_kerja"
                                                                                        id="satuan_kerja"
                                                                                        v-model="
                                                                                            forminfoakn
                                                                                                .instansi
                                                                                                .satuankerja
                                                                                        "
                                                                                        autocomplete="off"
                                                                                    />
                                                                                    <div
                                                                                        class="invalid-feedback"
                                                                                        id="msgsatuan_kerja"
                                                                                    ></div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="form-group row"
                                                                            >
                                                                                <label
                                                                                    for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                                    >Alamat
                                                                                    Instansi</label
                                                                                >
                                                                                <div
                                                                                    class="col-lg-7 col-md-12"
                                                                                >
                                                                                    <textautocomplete 
                                                                                    :items="forminfoakn.instansi.dataalamat" 
                                                                                    @input="checkAlamat($event)" 
                                                                                    v-model="forminfoakn.instansi.alamat"
                                                                                    />
                                                                                    <div
                                                                                        class="invalid-feedback"
                                                                                        id="msgalamatinstansi"
                                                                                    ></div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="form-group row"
                                                                            >
                                                                                <label
                                                                                    for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                                    >Provinsi</label
                                                                                >
                                                                                <div
                                                                                    class="col-lg-7 col-md-12"
                                                                                >
                                                                                    <select
                                                                                        name=""
                                                                                        id=""
                                                                                        class="form-control"
                                                                                        disabled
                                                                                    >
                                                                                        <option
                                                                                            value=""
                                                                                        >
                                                                                            {{
                                                                                                forminfoakn
                                                                                                    .instansi
                                                                                                    .provinsi
                                                                                            }}
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="form-group row"
                                                                            >
                                                                                <label
                                                                                    for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                                    >Deskripsi</label
                                                                                >
                                                                                <div
                                                                                    class="col-lg-5 col-md-12"
                                                                                >
                                                                                <textautocomplete 
                                                                                    :items="forminfoakn.instansi.datadeskripsi" 
                                                                                    @input="checkDeskripsi($event)" 
                                                                                    v-model="forminfoakn.instansi.deskripsi"
                                                                                    />
                                                                                    <div
                                                                                        class="invalid-feedback"
                                                                                        id="msgdeskripsi"
                                                                                    ></div>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="form-group row"
                                                                            >
                                                                                <label
                                                                                    for="keterangan"
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                                    >Keterangan</label
                                                                                >
                                                                                <div
                                                                                    class="col-lg-5 col-md-12"
                                                                                >
                                                                                    <textarea
                                                                                        class="form-control col-form-label"
                                                                                        id="keterangan"
                                                                                        name="keterangan"
                                                                                        v-model="
                                                                                            forminfoakn
                                                                                                .instansi
                                                                                                .keterangan
                                                                                        "
                                                                                    ></textarea>
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
                                        <div
                                            class="row d-flex justify-content-center"
                                            id="nonakn"
                                            v-else
                                        >
                                            <div class="col-lg-11 col-md-12">
                                                <h4>Info Penjualan</h4>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div
                                                            class="form-group row"
                                                            v-if="
                                                                this.$route
                                                                    .params
                                                                    .jenis ==
                                                                'nonekatpo'
                                                            "
                                                        >
                                                            <label
                                                                for="penjualan"
                                                                class="col-form-label col-lg-5 col-md-12 labelket"
                                                                >Barang</label
                                                            >
                                                            <div
                                                                class="col-5 col-form-label"
                                                            >
                                                                <div
                                                                    class="form-check form-check-inline"
                                                                >
                                                                    <input
                                                                        class="form-check-input"
                                                                        type="checkbox"
                                                                        id="jenis_pen"
                                                                        value="produk"
                                                                        name="jenis_pen[]"
                                                                        ref="checkboxproduk"
                                                                        checked
                                                                        @change="
                                                                            getBarang
                                                                        "
                                                                    />
                                                                    <label
                                                                        class="form-check-label"
                                                                        for="inlineCheckbox1"
                                                                        >Produk</label
                                                                    >
                                                                </div>
                                                                <div
                                                                    class="form-check form-check-inline"
                                                                >
                                                                    <input
                                                                        class="form-check-input"
                                                                        type="checkbox"
                                                                        id="jenis_pen"
                                                                        value="sparepart"
                                                                        name="jenis_pen[]"
                                                                        ref="checkboxsparepart"
                                                                        @change="
                                                                            getBarang
                                                                        "
                                                                    />
                                                                    <label
                                                                        class="form-check-label"
                                                                        for="inlineCheckbox1"
                                                                        >Sparepart</label
                                                                    >
                                                                </div>
                                                                <div
                                                                    class="form-check form-check-inline"
                                                                >
                                                                    <input
                                                                        class="form-check-input"
                                                                        type="checkbox"
                                                                        id="jenis_pen"
                                                                        value="jasa"
                                                                        name="jenis_pen[]"
                                                                        ref="checkboxjasa"
                                                                        @change="
                                                                            getBarang
                                                                        "
                                                                    />
                                                                    <label
                                                                        class="form-check-label"
                                                                        for="inlineCheckbox1"
                                                                        >Jasa</label
                                                                    >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="form-group row"
                                                        >
                                                            <label
                                                                for=""
                                                                class="col-form-label col-lg-5 col-md-12 labelket"
                                                                >Nomor PO</label
                                                            >
                                                            <div
                                                                class="col-lg-4 col-md-12"
                                                            >
                                                                <input
                                                                    type="text"
                                                                    class="form-control col-form-label"
                                                                    id="no_po"
                                                                    name="no_po"
                                                                    v-model="
                                                                        formpenjualanpo
                                                                            .po
                                                                            .nomorpo
                                                                    "
                                                                />
                                                                <div
                                                                    class="invalid-feedback"
                                                                    id="msgno_po"
                                                                ></div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="form-group row"
                                                        >
                                                            <label
                                                                for=""
                                                                class="col-form-label col-lg-5 col-md-12 labelket"
                                                                >Tanggal
                                                                PO</label
                                                            >
                                                            <div
                                                                class="col-lg-4 col-md-12"
                                                            >
                                                                <input
                                                                    type="date"
                                                                    class="form-control col-form-label"
                                                                    id="tanggal_po"
                                                                    name="tanggal_po"
                                                                    v-model="
                                                                        formpenjualanpo
                                                                            .po
                                                                            .tgl_po
                                                                    "
                                                                    :max="
                                                                        datemax
                                                                    "
                                                                />
                                                                <div
                                                                    class="invalid-feedback"
                                                                    id="msgtanggal_po"
                                                                ></div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="form-group row"
                                                        >
                                                            <label
                                                                for=""
                                                                class="col-form-label col-lg-5 col-md-12 labelket"
                                                                >Delivery
                                                                Order</label
                                                            >
                                                            <div
                                                                class="col-lg-5 col-md-12 col-form-label"
                                                            >
                                                                <div
                                                                    class="form-check form-check-inline"
                                                                >
                                                                    <input
                                                                        class="form-check-input"
                                                                        type="radio"
                                                                        name="do"
                                                                        id="yes"
                                                                        value="yes"
                                                                        v-model="
                                                                            formpenjualanpo
                                                                                .do
                                                                                .status
                                                                        "
                                                                    />
                                                                    <label
                                                                        class="form-check-label"
                                                                        for="yes"
                                                                        >Tersedia</label
                                                                    >
                                                                </div>
                                                                <div
                                                                    class="form-check form-check-inline"
                                                                >
                                                                    <input
                                                                        class="form-check-input"
                                                                        type="radio"
                                                                        name="do"
                                                                        id="no"
                                                                        value="no"
                                                                        v-model="
                                                                            formpenjualanpo
                                                                                .do
                                                                                .status
                                                                        "
                                                                    />
                                                                    <label
                                                                        class="form-check-label"
                                                                        for="no"
                                                                        >Tidak
                                                                        tersedia</label
                                                                    >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            v-show="
                                                                formpenjualanpo
                                                                    .do
                                                                    .status ==
                                                                'yes'
                                                            "
                                                        >
                                                            <div
                                                                class="form-group row"
                                                                id="do_detail_no"
                                                            >
                                                                <label
                                                                    for=""
                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                    >Nomor
                                                                    DO</label
                                                                >
                                                                <div
                                                                    class="col-lg-4 col-md-12"
                                                                >
                                                                    <input
                                                                        type="text"
                                                                        class="form-control col-form-label"
                                                                        id="no_do"
                                                                        name="no_do"
                                                                        v-model="
                                                                            formpenjualanpo
                                                                                .do
                                                                                .nomordo
                                                                        "
                                                                        :disabled="disableddo"
                                                                    />
                                                                    <div
                                                                        class="invalid-feedback"
                                                                        id="msgno_do"
                                                                    ></div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="form-group row"
                                                                id="do_detail_tgl"
                                                            >
                                                                <label
                                                                    for=""
                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                    >Tanggal
                                                                    DO</label
                                                                >
                                                                <div
                                                                    class="col-lg-4 col-md-12"
                                                                >
                                                                    <input
                                                                        type="date"
                                                                        class="form-control col-form-label"
                                                                        id="tanggal_do"
                                                                        name="tanggal_do"
                                                                        :min="
                                                                            datemax
                                                                        "
                                                                        v-model="
                                                                            formpenjualanpo
                                                                                .do
                                                                                .tgl_do
                                                                        "
                                                                    />
                                                                    <div
                                                                        class="invalid-feedback"
                                                                        id="msgtanggal_do"
                                                                    ></div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="form-group row"
                                                            >
                                                                <label
                                                                    for="keterangan"
                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                    >Keterangan</label
                                                                >
                                                                <div
                                                                    class="col-lg-5 col-md-12"
                                                                >
                                                                    <textarea
                                                                        class="form-control col-form-label"
                                                                        id="nonketerangan"
                                                                        name="keterangan"
                                                                        v-model="
                                                                            formpenjualanpo
                                                                                .do
                                                                                .keterangan
                                                                        "
                                                                    ></textarea>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="form-group row"
                                                            >
                                                                <label
                                                                    for="keterangan"
                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                    >Alamat</label
                                                                >
                                                                <div
                                                                    class="col-lg-5 col-md-12"
                                                                >
                                                                    <textarea
                                                                        class="form-control col-form-label"
                                                                        id="nonketerangan"
                                                                        name="keterangan"
                                                                        v-model="
                                                                            formpenjualanpo
                                                                                .do
                                                                                .address
                                                                        "
                                                                    ></textarea>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="form-group row"
                                                            >
                                                                <label
                                                                    for="keterangan"
                                                                    class="col-form-label col-lg-5 col-md-12 labelket"
                                                                    >Ekspedisi</label
                                                                >
                                                                <div
                                                                    class="col-lg-5 col-md-12"
                                                                >
                                                                    <input
                                                                        type="text"
                                                                        class="form-control col-form-label"
                                                                        id="no_do"
                                                                        name="no_do"
                                                                        v-model="
                                                                            formpenjualanpo
                                                                                .do
                                                                                .expedisi
                                                                        "
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="row d-flex justify-content-center"
                                            id="dataproduk"
                                        >
                                            <div class="col-lg-11 col-md-12">
                                                <h4>Data Produk</h4>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div
                                                            class="card"
                                                            v-if="
                                                                this.$route
                                                                    .params
                                                                    .jenis ==
                                                                'ekatso'
                                                            "
                                                        >
                                                            <div
                                                                class="card-header"
                                                            >
                                                                Data Produk
                                                                Emiindo
                                                                (E-Katalog)
                                                            </div>
                                                            <div
                                                                class="card-body"
                                                            >
                                                                <table
                                                                    class="table tableprodukemiindo"
                                                                >
                                                                    <thead>
                                                                        <tr>
                                                                            <th>
                                                                                No
                                                                            </th>
                                                                            <th>
                                                                                Produk
                                                                            </th>
                                                                            <th>
                                                                                Qty
                                                                            </th>
                                                                            <th>
                                                                                Harga
                                                                            </th>
                                                                            <th>
                                                                                Ongkos
                                                                                Kirim
                                                                            </th>
                                                                            <th>
                                                                                Subtotal
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr
                                                                            v-for="(
                                                                                item,
                                                                                i
                                                                            ) in ekat.sodetail"
                                                                            :key="
                                                                                i
                                                                            "
                                                                        >
                                                                            <td
                                                                                class="nowraptxt"
                                                                            >
                                                                                {{
                                                                                    i +
                                                                                    1
                                                                                }}
                                                                            </td>
                                                                            <td>
                                                                                <b
                                                                                    class="wb"
                                                                                    >{{
                                                                                        item
                                                                                            .produk
                                                                                            .productnm
                                                                                    }}</b
                                                                                >
                                                                            </td>
                                                                            <td
                                                                                class="nowraptxt tabnum"
                                                                            >
                                                                                {{
                                                                                    item.qty
                                                                                }}
                                                                                {{
                                                                                    item
                                                                                        .datauom
                                                                                        .uom
                                                                                }}
                                                                            </td>
                                                                            <td
                                                                                class="nowraptxt tabnum"
                                                                            >
                                                                                Rp.
                                                                                {{
                                                                                    formatRupiah(
                                                                                        parseInt(
                                                                                            item.price
                                                                                        )
                                                                                    )
                                                                                }}
                                                                            </td>
                                                                            <td
                                                                                class="nowraptxt tabnum"
                                                                            >
                                                                                Rp.
                                                                                {{
                                                                                    formatRupiah(
                                                                                        parseInt(
                                                                                            item.shippingcharge
                                                                                        )
                                                                                    )
                                                                                }}
                                                                            </td>
                                                                            <td
                                                                                class="nowraptxt tabnum"
                                                                            >
                                                                                Rp.
                                                                                {{
                                                                                    formatRupiah(
                                                                                        subtotal(
                                                                                            item.qty,
                                                                                            item.price,
                                                                                            item.shippingcharge
                                                                                        )
                                                                                    )
                                                                                }}
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <td
                                                                                colspan="5"
                                                                            >
                                                                                Total
                                                                                Harga
                                                                            </td>
                                                                            <td
                                                                                class="tabnum nowraptxt"
                                                                            >
                                                                                Rp.
                                                                                {{
                                                                                    formatRupiah(
                                                                                        total(
                                                                                            ekat.sodetail
                                                                                        )
                                                                                    )
                                                                                }}
                                                                            </td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="card"
                                                            v-else
                                                        >
                                                            <div
                                                                class="card-header"
                                                            >
                                                                Data Produk
                                                                Emiindo (PO)
                                                            </div>
                                                            <div
                                                                class="card-body"
                                                            >
                                                                <table
                                                                    class="table tablepo"
                                                                >
                                                                    <thead>
                                                                        <tr>
                                                                            <th>
                                                                                No
                                                                            </th>
                                                                            <th>
                                                                                Produk
                                                                            </th>
                                                                            <th>
                                                                                Qty
                                                                            </th>
                                                                            <th>
                                                                                Harga
                                                                            </th>
                                                                            <th>
                                                                                Diskon
                                                                            </th>
                                                                            <th>
                                                                                Subtotal
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr
                                                                            v-for="(
                                                                                item,
                                                                                i
                                                                            ) in po.purchaseorderdetail"
                                                                            :key="
                                                                                i
                                                                            "
                                                                        >
                                                                            <td
                                                                                class="nowraptxt"
                                                                            >
                                                                                {{
                                                                                    i +
                                                                                    1
                                                                                }}
                                                                            </td>
                                                                            <td>
                                                                                <b
                                                                                    class="wb"
                                                                                    >{{
                                                                                        item
                                                                                            .produk
                                                                                            .productnm
                                                                                    }}</b
                                                                                >
                                                                            </td>
                                                                            <td
                                                                                class="nowraptxt tabnum"
                                                                            >
                                                                                {{
                                                                                    item.qty
                                                                                }}
                                                                                {{
                                                                                    item
                                                                                        .uom
                                                                                        .uom
                                                                                }}
                                                                            </td>
                                                                            <td
                                                                                class="nowraptxt tabnum"
                                                                            >
                                                                                Rp.
                                                                                {{
                                                                                    formatRupiah(
                                                                                        parseInt(
                                                                                            item.price
                                                                                        )
                                                                                    )
                                                                                }}
                                                                            </td>
                                                                            <td
                                                                                class="nowraptxt tabnum"
                                                                            >
                                                                                {{
                                                                                    parseInt(
                                                                                        item.discount
                                                                                    )
                                                                                }}
                                                                                %
                                                                            </td>
                                                                            <td
                                                                                class="nowraptxt tabnum"
                                                                            >
                                                                                Rp.
                                                                                {{
                                                                                    formatRupiah(
                                                                                        subtotalPO(
                                                                                            item.qty,
                                                                                            item.price,
                                                                                            item.discount
                                                                                        )
                                                                                    )
                                                                                }}
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <td
                                                                                colspan="5"
                                                                            >
                                                                                Total
                                                                                Harga
                                                                            </td>
                                                                            <td
                                                                                class="tabnum nowraptxt"
                                                                            >
                                                                                Rp.
                                                                                {{
                                                                                    formatRupiah(
                                                                                        totalPO(
                                                                                            "ekat",
                                                                                            po
                                                                                        )
                                                                                    )
                                                                                }}
                                                                            </td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div v-if="loadingbarang">
                                                        <div
                                                            class="spinner-border"
                                                            role="status"
                                                        >
                                                            <span
                                                                class="sr-only"
                                                                >Loading...</span
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="col-12" v-else>
                                                        <div
                                                            class="card"
                                                            v-if="
                                                                barang.produk ==
                                                                true
                                                            "
                                                        >
                                                            <div
                                                                class="card-header"
                                                            >
                                                                Data Produk
                                                            </div>
                                                            <div
                                                                class="card-body"
                                                            >
                                                                <div
                                                                    class="row"
                                                                >
                                                                    <div
                                                                        class="col-12"
                                                                    >
                                                                        <div
                                                                            class="table-responsive"
                                                                        >
                                                                            <table
                                                                                class="table"
                                                                                style="
                                                                                    text-align: center;
                                                                                "
                                                                                id="tableproduk"
                                                                            >
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th
                                                                                            colspan="7"
                                                                                        >
                                                                                            <button
                                                                                                type="button"
                                                                                                @click="
                                                                                                    addProduk()
                                                                                                "
                                                                                                class="btn btn-primary float-right"
                                                                                                id="addrowproduk"
                                                                                            >
                                                                                                <i
                                                                                                    class="fas fa-plus"
                                                                                                ></i>
                                                                                                Produk
                                                                                            </button>
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th
                                                                                            width="5%"
                                                                                        >
                                                                                            No
                                                                                        </th>
                                                                                        <th
                                                                                            width="35%"
                                                                                        >
                                                                                            Nama
                                                                                            Paket
                                                                                        </th>
                                                                                        <th
                                                                                            width="10%"
                                                                                        >
                                                                                            Jumlah
                                                                                        </th>
                                                                                        <th
                                                                                            width="15%"
                                                                                        >
                                                                                            Harga
                                                                                        </th>
                                                                                        <th
                                                                                            width="15%"
                                                                                        >
                                                                                            Ongkir
                                                                                        </th>
                                                                                        <th
                                                                                            width="15%"
                                                                                        >
                                                                                            Subtotal
                                                                                        </th>
                                                                                        <th
                                                                                            width="5%"
                                                                                        >
                                                                                            Aksi
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody
                                                                                    v-if="
                                                                                        loadingproduk
                                                                                    "
                                                                                >
                                                                                    <div
                                                                                        class="spinner-border"
                                                                                        role="status"
                                                                                    >
                                                                                        <span
                                                                                            class="sr-only"
                                                                                            >Loading...</span
                                                                                        >
                                                                                    </div>
                                                                                </tbody>
                                                                                <tbody
                                                                                    v-else
                                                                                >
                                                                                    <tr
                                                                                        v-for="(
                                                                                            prd,
                                                                                            idx
                                                                                        ) in formproduk"
                                                                                        :key="
                                                                                            idx +
                                                                                            '1'
                                                                                        "
                                                                                    >
                                                                                        <td
                                                                                            v-text="
                                                                                                idx +
                                                                                                1
                                                                                            "
                                                                                        ></td>
                                                                                        <td>
                                                                                            <v-select
                                                                                                v-model="
                                                                                                    prd.produk
                                                                                                "
                                                                                                :options="
                                                                                                    produkselect
                                                                                                "
                                                                                                @input="
                                                                                                    inputProduk(
                                                                                                        idx,
                                                                                                        prd.produk
                                                                                                    )
                                                                                                "
                                                                                                :clearable="
                                                                                                    false
                                                                                                "
                                                                                            >
                                                                                            </v-select>
                                                                                            <div
                                                                                                v-if="
                                                                                                    prd
                                                                                                        .detailproduk
                                                                                                        .length >
                                                                                                    0
                                                                                                "
                                                                                            >
                                                                                                <p
                                                                                                    class="text-bold"
                                                                                                >
                                                                                                    Detail
                                                                                                    Produk
                                                                                                </p>
                                                                                                <div
                                                                                                    class="card"
                                                                                                >
                                                                                                    <div
                                                                                                        class="card-body"
                                                                                                    >
                                                                                                        <div
                                                                                                            v-for="(
                                                                                                                nmprd,
                                                                                                                indexprd
                                                                                                            ) in prd.detailproduk"
                                                                                                            :key="
                                                                                                                '3' +
                                                                                                                indexprd
                                                                                                            "
                                                                                                        >
                                                                                                            <div
                                                                                                                v-for="(
                                                                                                                    det,
                                                                                                                    indexdetail
                                                                                                                ) in nmprd.produk"
                                                                                                                :key="
                                                                                                                    '4' +
                                                                                                                    indexdetail
                                                                                                                "
                                                                                                            >
                                                                                                                <p>
                                                                                                                    {{
                                                                                                                        det.nama
                                                                                                                    }}
                                                                                                                </p>
                                                                                                                <div
                                                                                                                    class="spinner-border"
                                                                                                                    role="status"
                                                                                                                    v-if="
                                                                                                                        loadingvarian
                                                                                                                    "
                                                                                                                >
                                                                                                                    <span
                                                                                                                        class="sr-only"
                                                                                                                        >Loading...</span
                                                                                                                    >
                                                                                                                </div>
                                                                                                                <v-select
                                                                                                                    v-else
                                                                                                                    :options="
                                                                                                                        produkvarian(
                                                                                                                            det.gudang_barang_jadi,
                                                                                                                            det.nama
                                                                                                                        )
                                                                                                                    "
                                                                                                                    :clearable="
                                                                                                                        false
                                                                                                                    "
                                                                                                                    v-model="
                                                                                                                        prd
                                                                                                                            .detailprodukvarian[
                                                                                                                            indexdetail
                                                                                                                        ]
                                                                                                                    "
                                                                                                                    @input="
                                                                                                                        inputVarian(
                                                                                                                            idx,
                                                                                                                            $event
                                                                                                                        )
                                                                                                                    "
                                                                                                                >
                                                                                                                    <template
                                                                                                                        slot="selected-option"
                                                                                                                        slot-scope="option"
                                                                                                                    >
                                                                                                                        <span
                                                                                                                            class="text-center"
                                                                                                                        >
                                                                                                                            {{
                                                                                                                                option.nama
                                                                                                                            }}
                                                                                                                            <span
                                                                                                                                class="badge badge-info"
                                                                                                                                >{{
                                                                                                                                    option.stok
                                                                                                                                }}</span
                                                                                                                            >
                                                                                                                        </span>
                                                                                                                    </template>
                                                                                                                    <template
                                                                                                                        v-slot:option="option"
                                                                                                                    >
                                                                                                                        <p
                                                                                                                            class="text-center"
                                                                                                                        >
                                                                                                                            {{
                                                                                                                                option.nama
                                                                                                                            }}
                                                                                                                            <span
                                                                                                                                class="badge badge-info"
                                                                                                                                >{{
                                                                                                                                    option.stok
                                                                                                                                }}</span
                                                                                                                            >
                                                                                                                        </p>
                                                                                                                    </template>
                                                                                                                </v-select>
                                                                                                                <small
                                                                                                                    class="text-danger"
                                                                                                                    v-show="
                                                                                                                        stokKurang(
                                                                                                                            prd.qty,
                                                                                                                            prd
                                                                                                                                .detailprodukvarian[
                                                                                                                                indexdetail
                                                                                                                            ]
                                                                                                                        )
                                                                                                                    "
                                                                                                                    >Jumlah
                                                                                                                    Kurang
                                                                                                                    {{
                                                                                                                        prd.qty -
                                                                                                                        prd
                                                                                                                            .detailprodukvarian[
                                                                                                                            indexdetail
                                                                                                                        ]
                                                                                                                            .stok
                                                                                                                    }}
                                                                                                                    dari
                                                                                                                    permintaan</small
                                                                                                                >
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input-qty
                                                                                                v-model="
                                                                                                    prd.qty
                                                                                                "
                                                                                                :nilai="
                                                                                                    prd.qty
                                                                                                "
                                                                                                @input="
                                                                                                    calculate(
                                                                                                        idx
                                                                                                    )
                                                                                                "
                                                                                            ></input-qty>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input-price
                                                                                                v-model="
                                                                                                    prd.price
                                                                                                "
                                                                                                :nilai="
                                                                                                    prd.price
                                                                                                "
                                                                                                @input="
                                                                                                    calculate(
                                                                                                        idx
                                                                                                    )
                                                                                                "
                                                                                            ></input-price>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input-price
                                                                                                v-model="
                                                                                                    prd.shippingcharge
                                                                                                "
                                                                                                :nilai="
                                                                                                    prd.shippingcharge
                                                                                                "
                                                                                                @input="
                                                                                                    calculate(
                                                                                                        idx
                                                                                                    )
                                                                                                "
                                                                                            ></input-price>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="spinner-border"
                                                                                                role="status"
                                                                                                v-if="
                                                                                                    loadingtotal
                                                                                                "
                                                                                            >
                                                                                                <span
                                                                                                    class="sr-only"
                                                                                                    >Loading...</span
                                                                                                >
                                                                                            </div>
                                                                                            <p>
                                                                                                Rp.
                                                                                                {{
                                                                                                    formatRupiah(
                                                                                                        subtotal(
                                                                                                            prd.qty,
                                                                                                            prd.price,
                                                                                                            prd.shippingcharge
                                                                                                        )
                                                                                                    )
                                                                                                }}
                                                                                            </p>
                                                                                        </td>
                                                                                        <td>
                                                                                            <i
                                                                                                class="fas fa-minus"
                                                                                                style="
                                                                                                    color: red;
                                                                                                "
                                                                                                @click="
                                                                                                    removeprd(
                                                                                                        idx
                                                                                                    )
                                                                                                "
                                                                                            ></i>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <div
                                                                                            class="spinner-border"
                                                                                            role="status"
                                                                                            v-if="
                                                                                                loadingtotal
                                                                                            "
                                                                                        >
                                                                                            <span
                                                                                                class="sr-only"
                                                                                                >Loading...</span
                                                                                            >
                                                                                        </div>
                                                                                        <th
                                                                                            colspan="5"
                                                                                            v-else
                                                                                            style="
                                                                                                text-align: right;
                                                                                            "
                                                                                        >
                                                                                            Total
                                                                                            Harga
                                                                                        </th>
                                                                                        <th
                                                                                            id="totalhargaprd"
                                                                                            class="align-right"
                                                                                        >
                                                                                            Rp.
                                                                                            {{
                                                                                                formatRupiah(
                                                                                                    totalproduk()
                                                                                                )
                                                                                            }}
                                                                                        </th>
                                                                                    </tr>
                                                                                </tfoot>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="card"
                                                            v-if="
                                                                barang.sparepart ==
                                                                true
                                                            "
                                                        >
                                                            <div
                                                                class="card-header"
                                                            >
                                                                Data Part
                                                            </div>
                                                            <div
                                                                class="card-body"
                                                            >
                                                                <div
                                                                    class="row"
                                                                >
                                                                    <div
                                                                        class="col-12"
                                                                    >
                                                                        <div
                                                                            class="table-responsive"
                                                                        >
                                                                            <table
                                                                                class="table"
                                                                                style="
                                                                                    text-align: center;
                                                                                "
                                                                                id="tableproduk"
                                                                            >
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th
                                                                                            colspan="6"
                                                                                        >
                                                                                            <button
                                                                                                type="button"
                                                                                                class="btn btn-primary float-right"
                                                                                                id="addrowproduk"
                                                                                                @click="
                                                                                                    addSparepart()
                                                                                                "
                                                                                            >
                                                                                                <i
                                                                                                    class="fas fa-plus"
                                                                                                ></i>
                                                                                                Part
                                                                                            </button>
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th
                                                                                            width="5%"
                                                                                        >
                                                                                            No
                                                                                        </th>
                                                                                        <th
                                                                                            width="35%"
                                                                                        >
                                                                                            Nama
                                                                                            Part
                                                                                        </th>
                                                                                        <th
                                                                                            width="18%"
                                                                                        >
                                                                                            Jumlah
                                                                                        </th>
                                                                                        <th
                                                                                            width="20%"
                                                                                        >
                                                                                            Harga
                                                                                        </th>
                                                                                        <th
                                                                                            width="20%"
                                                                                        >
                                                                                            Subtotal
                                                                                        </th>
                                                                                        <th
                                                                                            width="5%"
                                                                                        >
                                                                                            Aksi
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody
                                                                                    v-if="
                                                                                        loadingsparepart
                                                                                    "
                                                                                >
                                                                                    <div
                                                                                        class="spinner-border"
                                                                                        role="status"
                                                                                    >
                                                                                        <span
                                                                                            class="sr-only"
                                                                                            >Loading...</span
                                                                                        >
                                                                                    </div>
                                                                                </tbody>
                                                                                <tbody
                                                                                    v-else
                                                                                >
                                                                                    <tr
                                                                                        v-for="(
                                                                                            prt,
                                                                                            idx
                                                                                        ) in formsparepart"
                                                                                        :key="
                                                                                            idx +
                                                                                            'part'
                                                                                        "
                                                                                    >
                                                                                        <td
                                                                                            v-text="
                                                                                                idx +
                                                                                                1
                                                                                            "
                                                                                        ></td>
                                                                                        <td>
                                                                                            <v-select
                                                                                                v-model="
                                                                                                    prt.sparepart
                                                                                                "
                                                                                                :options="
                                                                                                    paginated
                                                                                                "
                                                                                                @search="
                                                                                                    onSearch
                                                                                                "
                                                                                                @input="
                                                                                                    inputSparepart(
                                                                                                        idx,
                                                                                                        prt.sparepart
                                                                                                    )
                                                                                                "
                                                                                                :clearable="
                                                                                                    false
                                                                                                "
                                                                                            >
                                                                                                <li
                                                                                                    slot="list-footer"
                                                                                                    class="pagination"
                                                                                                >
                                                                                                    <button
                                                                                                        class="btn btn-secondary"
                                                                                                        :disabled="
                                                                                                            !hasPrevPage
                                                                                                        "
                                                                                                        @click="
                                                                                                            offset -=
                                                                                                                limit
                                                                                                        "
                                                                                                    >
                                                                                                        Prev
                                                                                                    </button>
                                                                                                    <button
                                                                                                        class="btn btn-primary"
                                                                                                        :disabled="
                                                                                                            !hasNextPage
                                                                                                        "
                                                                                                        @click="
                                                                                                            offset +=
                                                                                                                limit
                                                                                                        "
                                                                                                    >
                                                                                                        Next
                                                                                                    </button>
                                                                                                </li>
                                                                                            </v-select>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input-qty
                                                                                                v-model="
                                                                                                    prt.qty
                                                                                                "
                                                                                                :nilai="
                                                                                                    prt.qty
                                                                                                "
                                                                                                @input="
                                                                                                    calculatespr(
                                                                                                        idx
                                                                                                    )
                                                                                                "
                                                                                            ></input-qty>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input-price
                                                                                                v-model="
                                                                                                    prt.price
                                                                                                "
                                                                                                :nilai="
                                                                                                    prt.price
                                                                                                "
                                                                                                @input="
                                                                                                    calculatespr(
                                                                                                        idx
                                                                                                    )
                                                                                                "
                                                                                            ></input-price>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="spinner-border"
                                                                                                role="status"
                                                                                                v-if="
                                                                                                    loadingtotalspr
                                                                                                "
                                                                                            >
                                                                                                <span
                                                                                                    class="sr-only"
                                                                                                    >Loading...</span
                                                                                                >
                                                                                            </div>
                                                                                            <p
                                                                                                v-else
                                                                                            >
                                                                                                Rp.
                                                                                                {{
                                                                                                    formatRupiah(
                                                                                                        prt.subtotal
                                                                                                    )
                                                                                                }}
                                                                                            </p>
                                                                                        </td>
                                                                                        <td>
                                                                                            <i
                                                                                                class="fas fa-minus"
                                                                                                style="
                                                                                                    color: red;
                                                                                                "
                                                                                                @click="
                                                                                                    removeprt(
                                                                                                        idx
                                                                                                    )
                                                                                                "
                                                                                            ></i>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <div
                                                                                            class="spinner-border"
                                                                                            role="status"
                                                                                            v-if="
                                                                                                loadingtotalspr
                                                                                            "
                                                                                        >
                                                                                            <span
                                                                                                class="sr-only"
                                                                                                >Loading...</span
                                                                                            >
                                                                                        </div>
                                                                                        <th
                                                                                            colspan="4"
                                                                                            v-else
                                                                                            style="
                                                                                                text-align: right;
                                                                                            "
                                                                                        >
                                                                                            Total
                                                                                            Harga
                                                                                        </th>
                                                                                        <th
                                                                                            id="totalhargaprd"
                                                                                            class="align-right"
                                                                                        >
                                                                                            Rp.
                                                                                            {{
                                                                                                formatRupiah(
                                                                                                    totalsparepart()
                                                                                                )
                                                                                            }}
                                                                                        </th>
                                                                                    </tr>
                                                                                </tfoot>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="card"
                                                            v-if="
                                                                barang.jasa ==
                                                                true
                                                            "
                                                        >
                                                            <div
                                                                class="card-header"
                                                            >
                                                                Jasa
                                                            </div>
                                                            <div
                                                                class="card-body"
                                                            >
                                                                <div
                                                                    class="row"
                                                                >
                                                                    <div
                                                                        class="col-12"
                                                                    >
                                                                        <div
                                                                            class="table-responsive"
                                                                        >
                                                                            <table
                                                                                class="table"
                                                                                style="
                                                                                    text-align: center;
                                                                                "
                                                                                id="tableproduk"
                                                                            >
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th
                                                                                            colspan="6"
                                                                                        >
                                                                                            <button
                                                                                                type="button"
                                                                                                class="btn btn-primary float-right"
                                                                                                id="addrowproduk"
                                                                                            >
                                                                                                <i
                                                                                                    class="fas fa-plus"
                                                                                                    @click="
                                                                                                        addJasa()
                                                                                                    "
                                                                                                ></i>
                                                                                                Jasa
                                                                                            </button>
                                                                                        </th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th
                                                                                            width="5%"
                                                                                        >
                                                                                            No
                                                                                        </th>
                                                                                        <th
                                                                                            width="35%"
                                                                                        >
                                                                                            Nama
                                                                                            Jasa
                                                                                        </th>
                                                                                        <th
                                                                                            width="20%"
                                                                                        >
                                                                                            Harga
                                                                                        </th>
                                                                                        <th
                                                                                            width="20%"
                                                                                        >
                                                                                            Subtotal
                                                                                        </th>
                                                                                        <th
                                                                                            width="5%"
                                                                                        >
                                                                                            Aksi
                                                                                        </th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody
                                                                                    v-if="
                                                                                        loadingjasa
                                                                                    "
                                                                                >
                                                                                    <div
                                                                                        class="spinner-border"
                                                                                        role="status"
                                                                                    >
                                                                                        <span
                                                                                            class="sr-only"
                                                                                            >Loading...</span
                                                                                        >
                                                                                    </div>
                                                                                </tbody>
                                                                                <tbody
                                                                                    v-else
                                                                                >
                                                                                    <tr
                                                                                        v-for="(
                                                                                            fj,
                                                                                            idx
                                                                                        ) in formjasa"
                                                                                        :key="
                                                                                            idx +
                                                                                            'jasa'
                                                                                        "
                                                                                    >
                                                                                        <td
                                                                                            v-text="
                                                                                                idx +
                                                                                                1
                                                                                            "
                                                                                        ></td>
                                                                                        <td>
                                                                                            <v-select
                                                                                                v-model="
                                                                                                    fj.jasa
                                                                                                "
                                                                                                :options="
                                                                                                    jasaSelect
                                                                                                "
                                                                                                :filterable="
                                                                                                    false
                                                                                                "
                                                                                                @input="
                                                                                                    inputJasa(
                                                                                                        idx,
                                                                                                        fj.jasa
                                                                                                    )
                                                                                                "
                                                                                                :clearable="
                                                                                                    false
                                                                                                "
                                                                                            ></v-select>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input-price
                                                                                                v-model="
                                                                                                    fj.price
                                                                                                "
                                                                                                :nilai="
                                                                                                    fj.price
                                                                                                "
                                                                                                @input="
                                                                                                    calculatejasa(
                                                                                                        idx
                                                                                                    )
                                                                                                "
                                                                                            ></input-price>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div
                                                                                                class="spinner-border"
                                                                                                role="status"
                                                                                                v-if="
                                                                                                    loadingtotaljasa
                                                                                                "
                                                                                            >
                                                                                                <span
                                                                                                    class="sr-only"
                                                                                                    >Loading...</span
                                                                                                >
                                                                                            </div>
                                                                                            <p
                                                                                                v-else
                                                                                            >
                                                                                                Rp.
                                                                                                {{
                                                                                                    formatRupiah(
                                                                                                        fj.subtotal
                                                                                                    )
                                                                                                }}
                                                                                            </p>
                                                                                        </td>
                                                                                        <td>
                                                                                            <i
                                                                                                class="fas fa-minus"
                                                                                                style="
                                                                                                    color: red;
                                                                                                "
                                                                                                @click="
                                                                                                    removejasa(
                                                                                                        idx
                                                                                                    )
                                                                                                "
                                                                                            ></i>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <div
                                                                                            class="spinner-border"
                                                                                            role="status"
                                                                                            v-if="
                                                                                                loadingtotaljasa
                                                                                            "
                                                                                        >
                                                                                            <span
                                                                                                class="sr-only"
                                                                                                >Loading...</span
                                                                                            >
                                                                                        </div>
                                                                                        <th
                                                                                            colspan="3"
                                                                                            v-else
                                                                                            style="
                                                                                                text-align: right;
                                                                                            "
                                                                                        >
                                                                                            Total
                                                                                            Harga
                                                                                        </th>
                                                                                        <th
                                                                                            id="totalhargaprd"
                                                                                            class="align-right"
                                                                                        >
                                                                                            Rp.
                                                                                            {{
                                                                                                formatRupiah(
                                                                                                    totaljasa()
                                                                                                )
                                                                                            }}
                                                                                        </th>
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
                                        <div
                                            class="row d-flex justify-content-center"
                                        >
                                            <div class="col-lg-11 col-md-12">
                                                <span>
                                                    <router-link
                                                        :to="{ name: 'Index' }"
                                                        class="btn btn-danger"
                                                        >Batal</router-link
                                                    >
                                                </span>
                                                <span class="float-right">
                                                    <button
                                                        type="button"
                                                        class="btn btn-warning"
                                                        @click="simpan"
                                                        :disabled="checkbarang"
                                                    >
                                                        Simpan
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import axios from "axios";
import moment from "moment";
import mix from "../mixins/mix";
import vSelect from "vue-select";
import inputPrice from "../components/inputprice";
import inputQty from "../components/inputqty";
import textautocomplete from "../components/textautocomplete";
export default {
    mixins: [mix],
    components: {
    vSelect,
    inputPrice,
    inputQty,
    textautocomplete,
},
    data() {
        return {
            loading: false,
            loadingproduk: false,
            loadingtotal: false,
            loadingvarian: false,
            loadingbarang: false,
            loadingsparepart: false,
            loadingtotalspr: false,
            loadingjasa: false,
            loadingtotaljasa: false,
            ekat: null,
            po: null,
            Do: null,
            rencanapenjualan: [],
            produk: [],
            sparepart: [],
            jasa: [],
            search: "",
            offset: "",
            limit: 10,
            loadingtotal: false,
            errorinput: {
                deskripsi: {
                    nourut: false,
                },
            },
            barang: {
                produk: true,
                sparepart: false,
                jasa: false,
            },
            forminfoakn: {
                deskripsi: {
                    ekatalogId: 0,
                    nourut: null,
                    status: null,
                    tgl_buat: null,
                    tgl_edit: null,
                    tgl_delivery: null,
                },
                instansi: {
                    nminstansi: null,
                    satuankerja: null,
                    alamat: null,
                    dataalamat: null,
                    provinsi: null,
                    deskripsi: null,
                    keterangan: null,
                    datadeskripsi: null,
                },
            },
            formpenjualanpo: {
                po: {
                    nomorpo: null,
                    tgl_po: null,
                },
                do: {
                    status: null,
                    nomordo: null,
                    tgl_do: null,
                    keterangan: null,
                    address: null,
                    expedisi: null,
                },
            },
            formproduk: [
                {
                    id: null,
                    produk: null,
                    qty: 0,
                    price: 0,
                    shippingcharge: 0,
                    subtotal: 0,
                    detailproduk: [],
                    detailprodukvarian: [],
                },
            ],
            formsparepart: [
                {
                    id: null,
                    sparepart: null,
                    qty: 0,
                    price: 0,
                    subtotal: 0,
                },
            ],
            formjasa: [
                {
                    id: null,
                    jasa: null,
                    price: 0,
                    subtotal: 0,
                },
            ],
        };
    },
    methods: {
        async getRencana() {
            if (
                this.$route.params.jenis != "nonekatpo" &&
                this.ekat != null &&
                this.ekat.instansi != null
            ) {
                try {
                    let year = new Date().getFullYear();
                    await axios
                        .post(
                            "/api/penjualan/rencana/produk/213/" +
                                this.ekat.instansi.nminstansi +
                                "/" +
                                year
                        )
                        .then((response) => {
                            this.rencanapenjualan = response.data.data;
                        });
                } catch (error) {
                    console.log(error);
                }
            } else {
                console.log("kosong");
            }
        },
        async loadData() {
            this.loading = true;
            let akn = this.$route.params.id;
            let jenis = this.$route.params.jenis;
            let SO = this.$store.state.SO;
            let PO = this.$store.state.POEkat;
            let PONonEkat = this.$store.state.PONonEkat;
            let DO = this.$store.state.DO;
            if (jenis == "nonekatpo") {
                PONonEkat.filter((item) => {
                    if (item.pono == akn) {
                        this.po = item;
                    }
                });
            } else {
                SO.filter((item) => {
                    if (item.epurno == akn) {
                        this.ekat = item;
                    }
                });
                PO.filter((item) => {
                    if (item.salesorderno == akn) {
                        this.po = item;
                    }
                });
            }
            setTimeout(() => {
                if (this.po != null) {
                    DO.filter((item) => {
                        if (item.purchaseorder.pono == this.po.pono) {
                            this.Do = item;
                        }
                    });
                }
            }, 100);
            try {
                if (jenis == "nonekatpo") {
                    await axios
                        .get("/api/penjualan_produk/select_param/spa")
                        .then((response) => {
                            this.produk = response.data;
                        });
                } else {
                    await axios
                        .get("/api/penjualan_produk/select_param/ekatalog")
                        .then((response) => {
                            this.produk = response.data;
                        });
                }
            } catch (error) {
                console.log(error);
            }
            setTimeout(() => {
                if (jenis == "ekatso") {
                    this.infoakn();
                    this.updateekatalog(akn);
                    if (this.ekat.instansi != null && this.forminfoakn.instansi.nminstansi == null) {
                        this.forminfoakn.instansi.nminstansi = this.ekat.instansi.instansinm;
                    }
                } else if (jenis == "ekatpo") {
                    this.updateekatalog(akn);
                } else if (jenis == "nonekatpo") {
                    this.updatespa(akn);
                }
                this.infopenjualan();
                this.loading = false;
            }, 800);
        },
        async checkAlamat(e){
            if(this.forminfoakn.instansi.dataalamat == null){
                await axios.get('/api/penjualan/check_alamat').then((response) => {
                this.forminfoakn.instansi.dataalamat = response.data.map((item) => {
                    return {
                        text: item.alamat
                    }
                });
            });
            }
        },
        async checkDeskripsi(e){
            if(this.forminfoakn.instansi.datadeskripsi == null){
                await axios.get('/api/ekatalog/all_deskripsi').then((response) => {
                this.forminfoakn.instansi.datadeskripsi = response.data.map((item) => {
                    return {
                        text: item.deskripsi
                    }
                });
            });
            }
        },
        async updateekatalog(akn) {
            let jenis = this.$route.params.jenis;
            try {
                await axios
                    .get("/api/penjualan/ekatalog_data/" + akn)
                    .then((response) => {
                        if (response.data.status == 200) {
                            // this.formproduk = [];
                            if (jenis == "ekatso") {
                                this.infoaknupdate(response.data.data);

                            } else if (jenis == "ekatpo") {
                                if (response.data.data.produk != null) {
                                    this.formproduk = response.data.data.produk;
                                }
                            }
                        } else {
                            console.log(response.data.message);
                        }
                    });
            } catch (error) {
                console.log(error);
            }
        },
        async updatespa(po) {
            try {
                await axios
                    .get("/api/penjualan/spa_data/" + po)
                    .then((response) => {
                        // console.log(response);
                        if (response.data.status == 200) {
                            if (response.data.data.sparepart == undefined) {
                                this.$refs.checkboxsparepart.checked = false;
                                this.getBarang(
                                    this.$refs.checkboxsparepart,
                                    "update"
                                );
                            } else {
                                this.formsparepart =
                                    response.data.data.sparepart;
                                this.$refs.checkboxsparepart.checked = true;
                                this.getBarang(
                                    this.$refs.checkboxsparepart,
                                    "update"
                                );
                            }
                            if (response.data.data.produk == undefined) {
                                this.$refs.checkboxproduk.checked = false;
                                this.getBarang(
                                    this.$refs.checkboxproduk,
                                    "update"
                                );
                            } else {
                                this.formproduk = response.data.data.produk;
                                this.$refs.checkboxproduk.checked = true;
                                this.getBarang(
                                    this.$refs.checkboxproduk,
                                    "update"
                                );
                            }
                            if (response.data.data.jasa == undefined) {
                                this.$refs.checkboxjasa.checked = false;
                                this.getBarang(
                                    this.$refs.checkboxjasa,
                                    "update"
                                );
                            } else {
                                this.formjasa = response.data.data.jasa;
                                this.$refs.checkboxjasa.checked = true;
                                this.getBarang(
                                    this.$refs.checkboxjasa,
                                    "update"
                                );
                            }
                        } else {
                            console.log(response.data.message);
                        }
                    });
            } catch (error) {
                console.log(error);
            }
        },
        infoakn() {
            this.forminfoakn = {
                deskripsi: {
                    ekatalogId: 0,
                    nourut: null,
                    status: "negosiasi",
                    tgl_buat: this.ekat.createdate,
                    tgl_edit: this.ekat.editdate,
                    tgl_delivery: null,
                },
                instansi: {
                    nminstansi: null,
                    satuankerja: this.ekat.satuankerja.customername,
                    alamat: this.ekat.satuankerja.address,
                    provinsi:
                        this.ekat.satuankerja.kota.provinsi.provnm.toUpperCase(),
                    deskripsi: this.ekat.namapaket,
                    keterangan: null,
                },
            };
        },
        infoaknupdate(data) {
            if (data.ekatalog_id != null) {
                this.forminfoakn.deskripsi.ekatalogId = data.ekatalog_id;
                this.forminfoakn.deskripsi.nourut = parseInt(data.no_urut);
            }
            if (data.produk != null ) {
                this.formproduk = data.produk;
            }
            if (data.status != null) {
                this.forminfoakn.deskripsi.status = data.status;
            } else {
                this.forminfoakn.deskripsi.status = "negosiasi";
            }
            if (data.tglbuat != null) {
                this.forminfoakn.deskripsi.tgl_buat = data.tglbuat;
            } else {
                this.forminfoakn.deskripsi.tgl_buat = this.ekat.createdate;
            }
            if (data.tgledit != null) {
                this.forminfoakn.deskripsi.tgl_edit = data.tgledit;
            } else {
                this.forminfoakn.deskripsi.tgl_edit = this.ekat.editdate;
            }
            if (data.tglkontrak != null) {
                this.forminfoakn.deskripsi.tgl_delivery = data.tglkontrak;
            } else {
                this.forminfoakn.deskripsi.tgl_delivery = null;
            }
            if (data.provinsi != null) {
                this.forminfoakn.instansi.provinsi = data.provinsi;
            } else {
                this.forminfoakn.instansi.provinsi =
                    this.ekat.satuankerja.kota.provinsi.provnm.toUpperCase();
            }
            if (data.instansi != null) {
                this.forminfoakn.instansi.nminstansi = data.instansi;
            }
            if (data.satuan != null) {
                this.forminfoakn.instansi.satuankerja = data.satuan;
            } else {
                this.forminfoakn.instansi.satuankerja =
                    this.ekat.satuankerja.customername;
            }
            if (data.deskripsi != null) {
                this.forminfoakn.instansi.deskripsi = data.deskripsi;
            } else {
                this.forminfoakn.instansi.deskripsi = this.ekat.namapaket;
            }
            if (data.alamat == "-") {
                this.forminfoakn.instansi.alamat =
                    this.ekat.satuankerja.address;
            } else if (data.alamat != null) {
                this.forminfoakn.instansi.alamat = data.alamat;
            } else {
                this.forminfoakn.instansi.alamat =
                    this.ekat.satuankerja.address;
            }
            if (data.ket != null) {
                this.forminfoakn.instansi.keterangan = data.ket;
            } else {
                this.forminfoakn.instansi.keterangan = null;
            }
        },
        infopenjualan() {
            if (this.Do != null) {
                if (this.Do.expedition != null) {
                    this.formpenjualanpo = {
                        po: {
                            nomorpo: this.po.pono,
                            tgl_po: this.po.createdate,
                        },
                        do: {
                            status: "yes",
                            nomordo: this.Do.dono,
                            tgl_do: this.Do.dodate,
                            keterangan: this.Do.donote,
                            address: this.Do.address,
                            expedisi: this.Do.expedition.expnm,
                        },
                    };
                } else {
                    this.formpenjualanpo = {
                        po: {
                            nomorpo: this.po.pono,
                            tgl_po: this.po.createdate,
                        },
                        do: {
                            status: "yes",
                            nomordo: this.Do.dono,
                            tgl_do: this.Do.dodate,
                            keterangan: this.Do.donote,
                            address: this.Do.address,
                            expedisi: null,
                        },
                    };
                }
            } else if (this.po != null) {
                this.formpenjualanpo = {
                    po: {
                        nomorpo: this.po.pono,
                        tgl_po: this.po.createdate,
                    },
                    do: {
                        status: "no",
                        nomordo: null,
                        tgl_do: null,
                        keterangan: null,
                        address: null,
                        expedisi: null,
                    },
                };
            } else {
                this.formpenjualanpo = {
                    po: {
                        nomorpo: null,
                        tgl_po: null,
                    },
                    do: {
                        status: "no",
                        nomordo: null,
                        tgl_do: null,
                        keterangan: null,
                        address: null,
                        expedisi: null,
                    },
                };
            }
        },
        async getBarang(event, status) {
            // console.log("event", event);
            // console.log("status", status);
            let barang = null;
            if (status == "update") {
                barang = event;
            } else {
                barang = event.target;
            }
            if (barang.value == "sparepart" && barang.checked == true) {
                this.loadingbarang = true;
                if (this.sparepart.length == 0) {
                    await axios.post("/api/gk/sel-m-spare").then((response) => {
                        this.sparepart = response.data;
                        this.loadingbarang = false;
                    });
                }
                this.barang.sparepart = true;
                this.loadingbarang = false;
            } else if (barang.value == "sparepart" && barang.checked == false) {
                this.loadingbarang = true;
                this.barang.sparepart = false;
                this.loadingbarang = false;
            } else if (barang.value == "jasa" && barang.checked == true) {
                this.loadingbarang = true;
                if (this.jasa.length == 0) {
                    await axios.post("/api/gk/sel-m-jasa").then((response) => {
                        this.jasa = response.data;
                    });
                }
                this.barang.jasa = true;
                this.loadingbarang = false;
            } else if (barang.value == "jasa" && barang.checked == false) {
                this.loadingbarang = true;
                this.barang.jasa = false;
                this.loadingbarang = false;
            } else if (barang.value == "produk" && barang.checked == true) {
                this.loadingbarang = true;
                if (this.produk.length == 0) {
                    await axios
                        .get("/api/penjualan_produk/select_param/spa")
                        .then((response) => {
                            this.produk = response.data;
                        });
                }
                this.barang.produk = true;
                this.loadingbarang = false;
            } else if (barang.value == "produk" && barang.checked == false) {
                this.loadingbarang = true;
                this.barang.produk = false;
                this.loadingbarang = false;
            }
        },
        async nourut(nomor) {
            let id = parseInt(this.forminfoakn.deskripsi.ekatalogId);
            try {
                await axios
                    .post("/api/penjualan/check_no_urut/" + id + "/" + nomor)
                    .then((response) => {
                        if (response.data == 1) {
                            this.errorinput.deskripsi.nourut = true;
                        } else {
                            this.errorinput.deskripsi.nourut = false;
                        }
                    });
            } catch (error) {
                console.log(error);
            }
        },
        onSearch(query) {
            this.search = query;
            this.offset = 0;
        },
        async inputProduk(index, prdid) {
            this.loadingproduk = true;
            let produks = this.produk.find((item) => {
                return item.id == prdid.value;
            });
            try {
                await axios
                    .get("/api/penjualan_produk/select/" + produks.id)
                    .then((response) => {
                        this.formproduk[index] = {
                            id: produks.id,
                            produk: produks.nama,
                            qty: 0,
                            price: produks.harga,
                            shippingcharge: 0,
                            subtotal: 0,
                            detailproduk: response.data,
                            detailprodukvarian: [],
                        };
                    });
                this.loadingproduk = false;
                this.autoinputVarian(
                    index,
                    this.formproduk[index].detailproduk[0].produk
                );
            } catch (error) {
                console.log(error);
            }
        },
        removeprd(index) {
            if (this.formproduk.length > 1) {
                this.formproduk.splice(index, 1);
            }
        },
        stokKurang(qty, varian) {
            if (qty > varian.stok) {
                return true;
            } else {
                return false;
            }
        },
        inputSparepart(idx, prtid) {
            this.loadingsparepart = true;
            let sparepart = this.sparepart.find((item) => {
                return item.id == prtid.value;
            });
            this.formsparepart[idx] = {
                id: sparepart.id,
                sparepart: sparepart.nama,
                qty: 0,
                price: 0,
                subtotal: 0,
            };
            this.loadingsparepart = false;
        },
        removeprt(index) {
            if (this.formsparepart.length > 1) {
                this.formsparepart.splice(index, 1);
            }
        },
        inputJasa(idx, jsid) {
            this.loadingjasa = true;
            let jasa = this.jasa.find((item) => {
                return item.id == jsid.value;
            });
            this.formjasa[idx] = {
                id: jasa.id,
                jasa: jasa.nama,
                price: 0,
                subtotal: 0,
            };
            this.loadingjasa = false;
        },
        removejasa(index) {
            if (this.formjasa.length > 1) {
                this.formjasa.splice(index, 1);
            }
        },
        calculate(idx) {
            this.loadingtotal = true;
            this.subtotalproduk(idx);
            this.stokKurang(
                this.formproduk[idx].qty,
                this.formproduk[idx].detailprodukvarian
            );
            this.totalproduk();
            this.loadingtotal = false;
        },
        calculatespr(idx) {
            this.loadingtotalspr = true;
            this.subtotalspr(idx);
            this.totalsparepart();
            this.loadingtotalspr = false;
        },
        calculatejasa(idx) {
            this.loadingtotaljasa = true;
            this.subtotaljasa(idx);
            this.totaljasa();
            this.loadingtotaljasa = false;
        },
        subtotalproduk(idx) {
            this.formproduk[idx].subtotal = this.subtotal(
                this.formproduk[idx].qty,
                this.formproduk[idx].price,
                this.formproduk[idx].shippingcharge
            );
        },
        subtotalspr(idx) {
            this.formsparepart[idx].subtotal = this.subtotal(
                this.formsparepart[idx].qty,
                this.formsparepart[idx].price,
                0
            );
        },
        subtotaljasa(idx) {
            this.formjasa[idx].subtotal = this.formjasa[idx].price;
        },
        totalproduk() {
            let total = 0;
            this.formproduk.forEach((item) => {
                total += item.subtotal;
            });
            return total;
        },
        totalsparepart() {
            let total = 0;
            this.formsparepart.forEach((item) => {
                total += item.subtotal;
            });
            return total;
        },
        totaljasa() {
            let total = 0;
            this.formjasa.forEach((item) => {
                total += item.subtotal;
            });
            return total;
        },
        produkvarian(varian, nama) {
            let data = varian.map((item) => {
                if (item.nama == "") {
                    return {
                        namaprd: nama,
                        value: item.id,
                        label: nama + " (" + item.stok + ")",
                        stok: item.stok,
                        nama: nama,
                    };
                } else {
                    return {
                        namaprd: nama,
                        value: item.id,
                        label: item.nama + " (" + item.stok + ")",
                        stok: item.stok,
                        nama: item.nama,
                    };
                }
            });
            return data;
        },
        inputVarian(idxproduk, varian) {
            this.loadingvarian = true;
            let produk = this.formproduk[idxproduk].detailprodukvarian;
            if (produk.length > 0) {
                produk.find((item) => {
                    if (item.namaprd == varian.namaprd) {
                        (item.id = varian.value),
                            (item.nama = varian.nama),
                            (item.stok = varian.stok);
                    } else {
                        produk.push({
                            id: varian.value,
                            nama: varian.nama,
                            stok: varian.stok,
                            varian: varian.namaprd,
                        });
                    }
                });
            } else {
                produk.push({
                    namaprd: varian.namaprd,
                    id: varian.value,
                    nama: varian.nama,
                    stok: varian.stok,
                });
            }
            this.loadingvarian = false;
        },
        autoinputVarian(index, produkvarian) {
            let inputproduk = this.formproduk[index].detailprodukvarian;
            produkvarian.forEach((item) => {
                let autoinput = item.gudang_barang_jadi[0];
                if (autoinput.nama == "") {
                    inputproduk.push({
                        namaprd: item.nama,
                        id: autoinput.id,
                        nama: item.nama,
                        label: item.nama + " (" + autoinput.stok + ") ",
                        value: autoinput.id,
                        stok: autoinput.stok,
                    });
                } else {
                    inputproduk.push({
                        namaprd: item.nama,
                        id: autoinput.id,
                        nama: autoinput.nama,
                        label: autoinput.nama + " (" + autoinput.stok + ") ",
                        value: autoinput.id,
                        stok: autoinput.stok,
                    });
                }
            });
        },
        addProduk() {
            this.formproduk.push({
                id: null,
                produk: null,
                qty: 0,
                price: 0,
                shippingcharge: 0,
                subtotal: 0,
                detailproduk: [],
                detailprodukvarian: [],
            });
        },
        addSparepart() {
            this.formsparepart.push({
                id: null,
                sparepart: null,
                qty: 0,
                price: 0,
                subtotal: 0,
            });
        },
        addJasa() {
            this.formjasa.push({
                id: null,
                jasa: null,
                price: 0,
                subtotal: 0,
            });
        },
        simpan() {
            let jenis = this.$route.params.jenis;
            if (jenis == "ekatso") {
                let dataSO = {
                    provinsi: this.ekat.satuankerja.kota.provinsi.provnm,
                    no_paket: this.ekat.epurno,
                    no_urut: this.forminfoakn.deskripsi.nourut,
                    deskripsi: this.forminfoakn.instansi.deskripsi,
                    instansi: this.forminfoakn.instansi.nminstansi,
                    alamat: this.forminfoakn.instansi.alamat,
                    satuan: this.forminfoakn.instansi.satuankerja,
                    tglkontrak: this.forminfoakn.deskripsi.tgl_delivery,
                    tglbuat: this.forminfoakn.deskripsi.tgl_buat,
                    tgledit: this.forminfoakn.deskripsi.tgl_edit,
                    ket: this.forminfoakn.instansi.keterangan,
                    status: this.forminfoakn.deskripsi.status,
                    produk: this.formproduk,
                };
                if (this.forminfoakn.deskripsi.nourut == null || this.forminfoakn.deskripsi.nourut == "") {
                    this.$swal(
                        "Perhatian",
                        "Nomor urut harus diisi",
                        "warning"
                    );
                    this.errorinput.deskripsi.nourut = true;
                } else {
                    this.$swal({
                        title: "Konfirmasi",
                        text: "Apakah anda yakin ingin menyimpan data ini?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya",
                        cancelButtonText: "Tidak",
                    }).then((result) => {
                        if (result.value) {
                            this.errorinput.deskripsi.nourut = false;
                            axios
                                .post(
                                    "/penjualan/penjualan/store_emindo",
                                    dataSO
                                )
                                .then((response) => {
                                    if(response.data.message == "Berhasil"){
                                        this.$swal(
                                        "Berhasil",
                                        "Data berhasil disimpan",
                                        "success"
                                    );
                                    try {
                                        let data = {
                                            refnumber: this.ekat.epurno,
                                        }
                                        axios.post('https://sinko.api.hyperdatasystem.com/api/salesorder/save', data, {
                                            headers: {
                                                Authorization: 'Bearer ' + sessionStorage.getItem('token')
                                            },
                                        }).then(response => {
                                            this.loadData()
                                            this.$router.push({
                                                name: "Index",
                                            });
                                        })
                                    } catch (error) {
                                        console.log(error);
                                    }
                                    }else{
                                        this.$swal(
                                            "Gagal",
                                            "Data gagal disimpan",
                                            "error"
                                        );
                                        this.$router.push({
                                            name: "Index",
                                        });
                                    }
                                })
                                .catch((error) => {
                                    console.log(error);
                                });
                        }
                    });
                }
            } else if (jenis == "ekatpo") {
                let dataPO = {
                    no_paket: this.$route.params.id,
                    no_po: this.formpenjualanpo.po.nomorpo,
                    tgl_po: this.formpenjualanpo.po.tgl_po,
                    no_do: this.formpenjualanpo.do.nomordo,
                    tgl_do: this.formpenjualanpo.do.tgl_do,
                    ket: this.formpenjualanpo.do.keterangan,
                    produk: this.formproduk,
                };
                this.$swal({
                    title: "Konfirmasi",
                    text: "Apakah anda yakin ingin menyimpan data ini?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak",
                }).then((result) => {
                    if (result.value) {
                        axios
                            .post(
                                "/penjualan/penjualan/store_emindo_po",
                                dataPO
                            )
                            .then((response) => {
                                if(response.data.status == 200){
                                    this.$swal(
                                    "Berhasil",
                                    "Data berhasil disimpan",
                                    "success"
                                    );
                                try {
                                    let data = {
                                        refnumber: this.formpenjualanpo.po.nomorpo,
                                    }
                                    axios.post('https://sinko.api.hyperdatasystem.com/api/purchaseorder/save', data, {
                                        headers: {
                                            Authorization: 'Bearer ' + sessionStorage.getItem('token')
                                        },
                                    }).then(response => {
                                        this.loadData()
                                        this.$router.push({
                                            name: "Index",
                                        });

                                    })
                                    if(dataPO.no_do != null || dataPO.no_do != ""){
                                        let data = {
                                            refnumber: dataPO.no_do,
                                        }
                                        axios.post('https://sinko.api.hyperdatasystem.com/api/deliveryorder/save', data, {
                                            headers: {
                                                Authorization: 'Bearer ' + sessionStorage.getItem('token')
                                            },
                                        }).then(response => {
                                            console.log("berhasil DO");
                                        })
                                    }
                                } catch (error) {
                                    console.log(error);
                                }
                                }else{
                                    this.$swal(
                                        "Gagal",
                                        "Data gagal disimpan",
                                        "error"
                                    );
                                    this.$router.push({
                                        name: "Index",
                                    });
                                }

                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                });
            } else if (jenis == "nonekatpo") {
                let dataSPA = null;
                if (
                    this.formproduk[0].id != null &&
                    this.formsparepart[0].id != null &&
                    this.formjasa[0].id != null
                ) {
                    dataSPA = {
                        no_po: this.formpenjualanpo.po.nomorpo,
                        tgl_po: this.formpenjualanpo.po.tgl_po,
                        no_do: this.formpenjualanpo.do.nomordo,
                        tgl_do: this.formpenjualanpo.do.tgl_do,
                        ket: this.formpenjualanpo.do.keterangan,
                        produk: this.formproduk,
                        sparepart: this.formsparepart,
                        jasa: this.formjasa,
                    };
                } else if (
                    this.formproduk[0].id != null &&
                    this.formsparepart[0].id != null &&
                    this.formjasa[0].id == null
                ) {
                    dataSPA = {
                        no_po: this.formpenjualanpo.po.nomorpo,
                        tgl_po: this.formpenjualanpo.po.tgl_po,
                        no_do: this.formpenjualanpo.do.nomordo,
                        tgl_do: this.formpenjualanpo.do.tgl_do,
                        ket: this.formpenjualanpo.do.keterangan,
                        produk: this.formproduk,
                        sparepart: this.formsparepart,
                    };
                } else if (
                    this.formproduk[0].id != null &&
                    this.formsparepart[0].id == null &&
                    this.formjasa[0].id != null
                ) {
                    dataSPA = {
                        no_po: this.formpenjualanpo.po.nomorpo,
                        tgl_po: this.formpenjualanpo.po.tgl_po,
                        no_do: this.formpenjualanpo.do.nomordo,
                        tgl_do: this.formpenjualanpo.do.tgl_do,
                        ket: this.formpenjualanpo.do.keterangan,
                        produk: this.formproduk,
                        jasa: this.formjasa,
                    };
                } else if (
                    this.formproduk[0].id == null &&
                    this.formsparepart[0].id !== null &&
                    this.formjasa[0].id != null
                ) {
                    dataSPA = {
                        no_po: this.formpenjualanpo.po.nomorpo,
                        tgl_po: this.formpenjualanpo.po.tgl_po,
                        no_do: this.formpenjualanpo.do.nomordo,
                        tgl_do: this.formpenjualanpo.do.tgl_do,
                        ket: this.formpenjualanpo.do.keterangan,
                        sparepart: this.formsparepart,
                        jasa: this.formjasa,
                    };
                } else if (
                    this.formproduk[0].id != null &&
                    this.formsparepart[0].id == null &&
                    this.formjasa[0].id == null
                ) {
                    dataSPA = {
                        no_po: this.formpenjualanpo.po.nomorpo,
                        tgl_po: this.formpenjualanpo.po.tgl_po,
                        no_do: this.formpenjualanpo.do.nomordo,
                        tgl_do: this.formpenjualanpo.do.tgl_do,
                        ket: this.formpenjualanpo.do.keterangan,
                        produk: this.formproduk,
                    };
                } else if (
                    this.formproduk[0].id == null &&
                    this.formsparepart[0].id != null &&
                    this.formjasa[0].id == null
                ) {
                    dataSPA = {
                        no_po: this.formpenjualanpo.po.nomorpo,
                        tgl_po: this.formpenjualanpo.po.tgl_po,
                        no_do: this.formpenjualanpo.do.nomordo,
                        tgl_do: this.formpenjualanpo.do.tgl_do,
                        ket: this.formpenjualanpo.do.keterangan,
                        sparepart: this.formsparepart,
                    };
                } else if (
                    this.formproduk[0].id == null &&
                    this.formsparepart[0].id == null &&
                    this.formjasa[0].id != null
                ) {
                    dataSPA = {
                        no_po: this.formpenjualanpo.po.nomorpo,
                        tgl_po: this.formpenjualanpo.po.tgl_po,
                        no_do: this.formpenjualanpo.do.nomordo,
                        tgl_do: this.formpenjualanpo.do.tgl_do,
                        ket: this.formpenjualanpo.do.keterangan,
                        jasa: this.formjasa,
                    };
                } else {
                    dataSPA = {
                        no_po: this.formpenjualanpo.po.nomorpo,
                        tgl_po: this.formpenjualanpo.po.tgl_po,
                        no_do: this.formpenjualanpo.do.nomordo,
                        tgl_do: this.formpenjualanpo.do.tgl_do,
                        ket: this.formpenjualanpo.do.keterangan,
                    };
                }
                this.$swal({
                    title: "Konfirmasi",
                    text: "Apakah anda yakin ingin menyimpan data ini?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak",
                }).then((result) => {
                    if (result.value) {
                        axios
                            .post(
                                "/penjualan/penjualan/store_emindo_spa",
                                dataSPA
                            )
                            .then((response) => {
                                if(response.data.status == 200){
                                    if(response.data.message == 'po'){
                                        this.$swal({
                                            title: "Gagal",
                                            text: "Nomor PO sudah ada",
                                            icon: "error",
                                            confirmButtonText: "OK",
                                        });
                                    }else{
                                        this.$swal({
                                            title: "Berhasil",
                                            text: "Data berhasil disimpan",
                                            icon: "success",
                                            confirmButtonText: "OK",
                                        });
                                    }
                                    try {
                                    let data = {
                                        refnumber: this.formpenjualanpo.po.nomorpo,
                                    }
                                    axios.post('https://sinko.api.hyperdatasystem.com/api/purchaseorder/save', data, {
                                        headers: {
                                            Authorization: 'Bearer ' + sessionStorage.getItem('token')
                                        },
                                    }).then(response => {
                                        this.loadData()
                                        this.$router.push({
                                            name: "Index",
                                        });
                                    })
                                    if(dataSPA.no_do != null || dataSPA.no_do != ""){
                                        let data = {
                                            refnumber: dataSPA.no_do,
                                        }
                                        axios.post('https://sinko.api.hyperdatasystem.com/api/deliveryorder/save', data, {
                                            headers: {
                                                Authorization: 'Bearer ' + sessionStorage.getItem('token')
                                            },
                                        }).then(response => {
                                            console.log("berhasil DO");
                                        })
                                    }
                                } catch (error) {
                                    console.log(error);
                                }
                                }else{
                                    this.$swal(
                                        "Gagal",
                                        "Data gagal disimpan",
                                        "error"
                                    );
                                    this.$router.push({
                                        name: "Index",
                                    });
                                }
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                });
            }
        },
    },
    computed: {
        produkselect() {
            let data = this.produk.map((item) => {
                return {
                    label: item.nama,
                    value: item.id,
                };
            });
            return data;
        },
        partSelect() {
            if (this.sparepart.length > 0) {
                let data = this.sparepart.map((item) => {
                    return {
                        label: item.nama,
                        value: item.id,
                    };
                });
                return data;
            }
        },
        filteredPart() {
            return this.partSelect.filter((item) =>
                item.label
                    .toLocaleLowerCase()
                    .includes(this.search.toLocaleLowerCase())
            );
        },
        paginated() {
            return this.filteredPart.slice(
                this.offset,
                this.limit + this.offset
            );
        },
        hasNextPage() {
            const nextOffset = this.offset + this.limit;
            return Boolean(
                this.filteredPart.slice(nextOffset, this.limit + nextOffset)
                    .length
            );
        },
        hasPrevPage() {
            const prevOffset = this.offset - this.limit;
            return Boolean(
                this.filteredPart.slice(prevOffset, this.limit + prevOffset)
                    .length
            );
        },
        jasaSelect() {
            if (this.jasa.length > 0) {
                let data = this.jasa.map((item) => {
                    return {
                        label: item.nama,
                        value: item.id,
                    };
                });
                return data;
            }
        },
        checkbarang() {
            if (
                this.barang.jasa == false &&
                this.barang.sparepart == false &&
                this.barang.produk == false
            ) {
                return true;
            }
            return false;
        },
        checknourut() {
            if (this.forminfoakn.deskripsi.ekatalogId != 0) {
                return true;
            }
            return false;
        },
        disableddo() {
            if (this.formpenjualanpo.do.nomordo != null) {
                return true;
            }
            return false;
        },
    },
    created() {
        this.loadData();
    },
    mounted() {
        this.getRencana();
    },
    updated() {
        $(".perencanaan").DataTable();
        $(".tableprodukemiindo").DataTable();
        $(".tablepo").DataTable();
    },
};
</script>
<style>
.blue-bg {
    background-color: #c8daea;
}
.pagination {
    display: flex;
    margin: 0.25rem 0.25rem 0;
}
.pagination button {
    flex-grow: 1;
}
.pagination button:hover {
    cursor: pointer;
}
</style>
