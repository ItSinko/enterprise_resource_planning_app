<template>
    <div>
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div id="ekatalog" class="card">
                            <div class="card-body">
                                <h4 class="margin">Data Penjualan</h4>
                                <div class="row">
                                    <div class="col-lg-11 col-md-12">
                                        <div class="row d-flex justify-content-between">
                                            <div class="p-2 cust">
                                                <div>
                                                    <div class="margin"><small class="text-muted">Info
                                                            Instansi</small></div>
                                                    <div class="margin"><b>{{ ekatalog.header.instansi }}</b></div>
                                                    <div class="margin"><b>{{ ekatalog.header.customer }}</b></div>
                                                    <div class="margin"><b>{{ ekatalog.header.provinsi }}</b></div>
                                                </div>
                                                <div class="margin-top">
                                                    <div class="margin"><small class="text-muted">Info
                                                            Customer</small></div>
                                                    <div class="margin"><b>{{ ekatalog.header.customer }}</b></div>
                                                    <div class="margin"><b>{{ekatalog.header.alamat_customer}}</b></div>
                                                    <div class="margin"><b>{{ ekatalog.header.provinsi }}</b></div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No SO</small></div>
                                                    <div><b>{{ ekatalog.header.so }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">No AKN</small></div>
                                                    <div><b>{{ ekatalog.header.no_paket }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal
                                                            Order</small></div>
                                                    <div><b>{{ moment(ekatalog.header.tgl_order) }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal Batas
                                                            Kontrak</small></div>
                                                    <div>
                                                        <b>{{ ekatalog.header.tgl_kontrak ? '-' : moment(ekatalog.header.tgl_kontrak) }}</b>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Status</small></div>
                                                    <div v-html="status(ekatalog.header.status)"></div>
                                                </div>
                                            </div>
                                            <div class="p-2">
                                                <div class="margin">
                                                    <div><small class="text-muted">No PO</small></div>
                                                    <div><b>{{ ekatalog.header.no_po }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal PO</small></div>
                                                    <div>
                                                        <b>{{ ekatalog.header.tgl_po ? '-' : moment(ekatalog.header.tgl_po) }}</b>
                                                    </div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">No DO</small></div>
                                                    <div><b>{{ ekatalog.header.no_do }}</b></div>
                                                </div>
                                                <div class="margin">
                                                    <div><small class="text-muted">Tanggal DO</small></div>
                                                    <div>
                                                        <b>{{ ekatalog.header.tgl_do ? '-' : moment(ekatalog.header.tgl_do) }}</b>
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
                                        <div id="akn" class="row d-flex justify-content-center">
                                            <div class="col-lg-11 col-md-12">
                                                <h4>Info AKN</h4>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <ul id="pills-tab" role="tablist"
                                                            class="nav nav-pills mb-3 nav-justified">
                                                            <li role="presentation" class="nav-item"><a
                                                                    id="pills-penjualan-tab" data-toggle="pill"
                                                                    href="#pills-penjualan" role="tab"
                                                                    aria-controls="pills-penjualan" aria-selected="true"
                                                                    class="nav-link active">Deskripsi
                                                                    Ekatalog</a></li>
                                                            <li role="presentation" class="nav-item"><a
                                                                    id="pills-instansi-tab" data-toggle="pill"
                                                                    href="#pills-instansi" role="tab"
                                                                    aria-controls="pills-instansi" aria-selected="false"
                                                                    class="nav-link">Instansi</a></li>
                                                        </ul>
                                                        <div class="form-horizontal">
                                                            <div id="pills-tabContent" class="tab-content">
                                                                <div id="pills-penjualan" role="tabpanel"
                                                                    aria-labelledby="pills-penjualan-tab"
                                                                    class="tab-pane fade show active">
                                                                    <div class="card removeshadow">
                                                                        <div class="card-body">
                                                                            <div class="form-group row"><label for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket">No
                                                                                    Urut</label>
                                                                                <div class="col-lg-2 col-md-4"><input
                                                                                        type="number" name="no_urut"
                                                                                        id="no_urut"
                                                                                        v-model="ekatalog.form.no_urut"
                                                                                        class="form-control col-form-label"
                                                                                        disabled="disabled">
                                                                                    <div class="invalid-feedback">
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
                                                                            <div class="form-group row"><label for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket">Status</label>
                                                                                <div
                                                                                    class="col-lg-5 col-md-12 col-form-label">
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input type="radio"
                                                                                            name="status_akn"
                                                                                            id="status_akn1"
                                                                                            value="sepakat"
                                                                                            checked="checked"
                                                                                            v-model="ekatalog.header.status"
                                                                                            class="form-check-input">
                                                                                        <label for="status_akn1"
                                                                                            class="form-check-label">Sepakat</label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input type="radio"
                                                                                            name="status_akn"
                                                                                            id="status_akn2"
                                                                                            value="negosiasi"
                                                                                            v-model="ekatalog.header.status"
                                                                                            class="form-check-input">
                                                                                        <label for="status_akn2"
                                                                                            class="form-check-label">Negosiasi</label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input type="radio"
                                                                                            name="status_akn"
                                                                                            id="status_akn3"
                                                                                            value="batal"
                                                                                            v-model="ekatalog.header.status"
                                                                                            class="form-check-input">
                                                                                        <label for="status_akn3"
                                                                                            class="form-check-label">Batal</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row"><label for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                                                    Buat</label>
                                                                                <div class="col-lg-4 col-md-4"><input
                                                                                        type="date" name="tgl_buat"
                                                                                        id="tgl_buat"
                                                                                        v-model="ekatalog.form.tgl_buat"
                                                                                        class="form-control col-form-label">
                                                                                    <div id="msgtgl_buat"
                                                                                        class="invalid-feedback"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row"><label for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                                                    Edit</label>
                                                                                <div class="col-lg-4 col-md-4"><input
                                                                                        type="date" name="tgl_edit"
                                                                                        id="tgl_edit" :max="datemax"
                                                                                        v-model="ekatalog.form.tgl_edit"
                                                                                        class="form-control col-form-label">
                                                                                    <div id="msgtgl_edit"
                                                                                        class="invalid-feedback"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row"><label for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket">Tanggal
                                                                                    Delivery</label>
                                                                                <div class="col-lg-4 col-md-4"><input
                                                                                        type="date" name="batas_kontrak"
                                                                                        id="batas_kontrak"
                                                                                        :min="datemax"
                                                                                        v-model="ekatalog.form.tgl_delivery"
                                                                                        class="form-control col-form-label">
                                                                                    <div id="msgbatas_kontrak"
                                                                                        class="invalid-feedback"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div id="pills-instansi" role="tabpanel"
                                                                    aria-labelledby="pills-instansi-tab"
                                                                    class="tab-pane fade show">
                                                                    <div class="card removeshadow">
                                                                        <div class="card-header">
                                                                            <h6>
                                                                                Rencana
                                                                                Penjualan
                                                                            </h6>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="form-group row"><label for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket">Instansi</label>
                                                                                <div
                                                                                    class="col-lg-7 col-md-12 autocomplete">
                                                                                    <input type="text" name="instansi"
                                                                                        id="instansi" autocomplete="off"
                                                                                        v-model="ekatalog.form.instansi"
                                                                                        class="form-control col-form-label ui-autocomplete-input">
                                                                                    <div id="msginstansi"
                                                                                        class="invalid-feedback"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row"><label for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket">Satuan
                                                                                    Kerja</label>
                                                                                <div class="col-lg-7 col-md-12"><input
                                                                                        type="text" name="satuan_kerja"
                                                                                        id="satuan_kerja"
                                                                                        v-model="ekatalog.form.satuan"
                                                                                        autocomplete="off"
                                                                                        class="form-control col-form-label ui-autocomplete-input">
                                                                                    <div id="msgsatuan_kerja"
                                                                                        class="invalid-feedback"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row"><label for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket">Alamat
                                                                                    Instansi</label>
                                                                                <div class="col-lg-7 col-md-12">
                                                                                    <textautocomplete :items="alamat"
                                                                                        @input="checkAlamat($event)"
                                                                                        v-model="ekatalog.form.alamat" />
                                                                                    <div id="msgalamatinstansi"
                                                                                        class="invalid-feedback"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row"><label for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket">Provinsi</label>
                                                                                <div class="col-lg-7 col-md-12">
                                                                                    <v-select :options="selectProvinsi" v-model="ekatalog.form.provinsi"></v-select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row"><label for=""
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket">Deskripsi</label>
                                                                                <div class="col-lg-5 col-md-12">
                                                                                    <textautocomplete :items="deskripsi"
                                                                                        @input="checkDeskripsi($event)"
                                                                                        v-model="ekatalog.form.deskripsi" />
                                                                                    <div id="msgdeskripsi"
                                                                                        class="invalid-feedback"></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row"><label
                                                                                    for="keterangan"
                                                                                    class="col-form-label col-lg-5 col-md-12 labelket">Keterangan</label>
                                                                                <div class="col-lg-5 col-md-12">
                                                                                    <textarea id="keterangan"
                                                                                        name="keterangan"
                                                                                        v-model="ekatalog.form.ket"
                                                                                        class="form-control col-form-label"></textarea>
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
                                        <div id="dataproduk" class="row d-flex justify-content-center">
                                            <div class="col-lg-11 col-md-12">
                                                <h4>Data Produk</h4>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                Data Produk
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="table-responsive">
                                                                            <table id="tableproduk" class="table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th colspan="7"><button
                                                                                                type="button"
                                                                                                id="addrowproduk"
                                                                                                class="btn btn-primary float-right"><i
                                                                                                    class="fas fa-plus"></i>
                                                                                                Produk
                                                                                            </button></th>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <th width="5%">
                                                                                            No
                                                                                        </th>
                                                                                        <th width="35%">
                                                                                            Nama
                                                                                            Paket
                                                                                        </th>
                                                                                        <th width="10%">
                                                                                            Jumlah
                                                                                        </th>
                                                                                        <th width="15%">
                                                                                            Harga
                                                                                        </th>
                                                                                        <th width="15%">
                                                                                            Ongkir
                                                                                        </th>
                                                                                        <th width="15%">
                                                                                            Subtotal
                                                                                        </th>
                                                                                        <th width="5%">
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
                                                                                <tbody v-else>
                                                                                    <tr v-for="(prd, idx) in ekatalog.data.produk" :key="idx+'produk'">
                                                                                        <td v-text="idx+1"></td>
                                                                                        <td>
                                                                                            <div dir="auto"
                                                                                                class="v-select vs--single vs--searchable">
                                                                                                <div id="vs1__combobox"
                                                                                                    role="combobox"
                                                                                                    aria-expanded="false"
                                                                                                    aria-owns="vs1__listbox"
                                                                                                    aria-label="Search for option"
                                                                                                    class="vs__dropdown-toggle">
                                                                                                    <div
                                                                                                        class="vs__selected-options">
                                                                                                        <span
                                                                                                            class="vs__selected">
                                                                                                            BODY FAT PRO
                                                                                                            <!----></span>
                                                                                                        <input
                                                                                                            aria-autocomplete="list"
                                                                                                            aria-labelledby="vs1__combobox"
                                                                                                            aria-controls="vs1__listbox"
                                                                                                            type="search"
                                                                                                            autocomplete="off"
                                                                                                            class="vs__search">
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="vs__actions">
                                                                                                        <button
                                                                                                            type="button"
                                                                                                            title="Clear Selected"
                                                                                                            aria-label="Clear Selected"
                                                                                                            class="vs__clear"
                                                                                                            style="display: none;"><svg
                                                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                                                width="10"
                                                                                                                height="10">
                                                                                                                <path
                                                                                                                    d="M6.895455 5l2.842897-2.842898c.348864-.348863.348864-.914488 0-1.263636L9.106534.261648c-.348864-.348864-.914489-.348864-1.263636 0L5 3.104545 2.157102.261648c-.348863-.348864-.914488-.348864-1.263636 0L.261648.893466c-.348864.348864-.348864.914489 0 1.263636L3.104545 5 .261648 7.842898c-.348864.348863-.348864.914488 0 1.263636l.631818.631818c.348864.348864.914773.348864 1.263636 0L5 6.895455l2.842898 2.842897c.348863.348864.914772.348864 1.263636 0l.631818-.631818c.348864-.348864.348864-.914489 0-1.263636L6.895455 5z">
                                                                                                                </path>
                                                                                                            </svg></button>
                                                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                            width="14"
                                                                                                            height="10"
                                                                                                            role="presentation"
                                                                                                            class="vs__open-indicator">
                                                                                                            <path
                                                                                                                d="M9.211364 7.59931l4.48338-4.867229c.407008-.441854.407008-1.158247 0-1.60046l-.73712-.80023c-.407008-.441854-1.066904-.441854-1.474243 0L7 5.198617 2.51662.33139c-.407008-.441853-1.066904-.441853-1.474243 0l-.737121.80023c-.407008.441854-.407008 1.158248 0 1.600461l4.48338 4.867228L7 10l2.211364-2.40069z">
                                                                                                            </path>
                                                                                                        </svg>
                                                                                                        <div class="vs__spinner"
                                                                                                            style="display: none;">
                                                                                                            Loading...
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <ul id="vs1__listbox"
                                                                                                    role="listbox"
                                                                                                    style="display: none; visibility: hidden;">
                                                                                                </ul>
                                                                                            </div>
                                                                                            <div>
                                                                                                <p class="text-bold">
                                                                                                    Detail
                                                                                                    Produk
                                                                                                </p>
                                                                                                <div class="card">
                                                                                                    <div
                                                                                                        class="card-body">
                                                                                                        <div>
                                                                                                            <div>
                                                                                                                <p>
                                                                                                                    BODY
                                                                                                                    FAT
                                                                                                                    PRO
                                                                                                                </p>
                                                                                                                <div dir="auto"
                                                                                                                    class="v-select vs--single vs--searchable">
                                                                                                                    <div id="vs2__combobox"
                                                                                                                        role="combobox"
                                                                                                                        aria-expanded="false"
                                                                                                                        aria-owns="vs2__listbox"
                                                                                                                        aria-label="Search for option"
                                                                                                                        class="vs__dropdown-toggle">
                                                                                                                        <div
                                                                                                                            class="vs__selected-options">
                                                                                                                            <span
                                                                                                                                class="vs__selected"><span
                                                                                                                                    class="text-center">
                                                                                                                                    BODY
                                                                                                                                    FAT
                                                                                                                                    PRO
                                                                                                                                    <span
                                                                                                                                        class="badge badge-info">2124</span></span>
                                                                                                                                <!----></span>
                                                                                                                            <input
                                                                                                                                aria-autocomplete="list"
                                                                                                                                aria-labelledby="vs2__combobox"
                                                                                                                                aria-controls="vs2__listbox"
                                                                                                                                type="search"
                                                                                                                                autocomplete="off"
                                                                                                                                class="vs__search">
                                                                                                                        </div>
                                                                                                                        <div
                                                                                                                            class="vs__actions">
                                                                                                                            <button
                                                                                                                                type="button"
                                                                                                                                title="Clear Selected"
                                                                                                                                aria-label="Clear Selected"
                                                                                                                                class="vs__clear"
                                                                                                                                style="display: none;"><svg
                                                                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                                                                    width="10"
                                                                                                                                    height="10">
                                                                                                                                    <path
                                                                                                                                        d="M6.895455 5l2.842897-2.842898c.348864-.348863.348864-.914488 0-1.263636L9.106534.261648c-.348864-.348864-.914489-.348864-1.263636 0L5 3.104545 2.157102.261648c-.348863-.348864-.914488-.348864-1.263636 0L.261648.893466c-.348864.348864-.348864.914489 0 1.263636L3.104545 5 .261648 7.842898c-.348864.348863-.348864.914488 0 1.263636l.631818.631818c.348864.348864.914773.348864 1.263636 0L5 6.895455l2.842898 2.842897c.348863.348864.914772.348864 1.263636 0l.631818-.631818c.348864-.348864.348864-.914489 0-1.263636L6.895455 5z">
                                                                                                                                    </path>
                                                                                                                                </svg></button>
                                                                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                                                width="14"
                                                                                                                                height="10"
                                                                                                                                role="presentation"
                                                                                                                                class="vs__open-indicator">
                                                                                                                                <path
                                                                                                                                    d="M9.211364 7.59931l4.48338-4.867229c.407008-.441854.407008-1.158247 0-1.60046l-.73712-.80023c-.407008-.441854-1.066904-.441854-1.474243 0L7 5.198617 2.51662.33139c-.407008-.441853-1.066904-.441853-1.474243 0l-.737121.80023c-.407008.441854-.407008 1.158248 0 1.600461l4.48338 4.867228L7 10l2.211364-2.40069z">
                                                                                                                                </path>
                                                                                                                            </svg>
                                                                                                                            <div class="vs__spinner"
                                                                                                                                style="display: none;">
                                                                                                                                Loading...
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <ul id="vs2__listbox"
                                                                                                                        role="listbox"
                                                                                                                        style="display: none; visibility: hidden;">
                                                                                                                    </ul>
                                                                                                                </div>
                                                                                                                <small
                                                                                                                    class="text-danger"
                                                                                                                    style="display: none;">Jumlah
                                                                                                                    Kurang
                                                                                                                    -2056
                                                                                                                    dari
                                                                                                                    permintaan</small>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div value="68"><input
                                                                                                    type="text"
                                                                                                    class="form-control">
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div value="1760000"><input
                                                                                                    type="text"
                                                                                                    class="form-control">
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div value="5100000"><input
                                                                                                    type="text"
                                                                                                    class="form-control">
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <!---->
                                                                                            <p>
                                                                                                Rp.
                                                                                                124.780.000
                                                                                            </p>
                                                                                        </td>
                                                                                        <td><i class="fas fa-minus"
                                                                                                style="color: red;"></i>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                                <tfoot>
                                                                                    <tr>
                                                                                        <th colspan="5"
                                                                                            style="text-align: right;">
                                                                                            Total
                                                                                            Harga
                                                                                        </th>
                                                                                        <th id="totalhargaprd"
                                                                                            class="align-right">
                                                                                            Rp.
                                                                                            124.780.000
                                                                                        </th>
                                                                                    </tr>
                                                                                </tfoot>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!---->
                                                        <!---->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-center">
                                            <div class="col-lg-11 col-md-12"><span><a
                                                        href="/penjualan/request_emiindo/akn_po"
                                                        class="btn btn-danger router-link-active">Batal</a></span> <span
                                                    class="float-right"><button type="button" class="btn btn-warning">
                                                        Simpan
                                                    </button></span></div>
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
    import axios from 'axios';
    import mix from "../mixins/mix";
    import textautocomplete from "../components/textautocomplete";
    import vSelect from 'vue-select';
    export default {
        mixins: [mix],
        components: {
            textautocomplete,
            vSelect,
        },
        data() {
            return {
                deskripsi: null,
                alamat: null,
                provinsi: null,
                loadingproduk: false,
            }
        },
        props: {
            ekatalog: {
                type: Object,
                required: true
            }
        },
        methods: {
            async checkDeskripsi(e) {
                if (this.deskripsi == null) {
                    await axios.get('/api/ekatalog/all_deskripsi').then((response) => {
                        this.deskripsi = response.data.map((item) => {
                            return {
                                text: item.deskripsi
                            }
                        });
                    });
                }
            },
            async checkAlamat(e) {
                if (this.alamat == null) {
                    await axios.get('/api/penjualan/check_alamat').then((response) => {
                        this.alamat = response.data.map((item) => {
                            return {
                                text: item.alamat
                            }
                        });
                    });
                }
            },
        },
    }

</script>
