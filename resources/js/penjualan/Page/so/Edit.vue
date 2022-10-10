<template>
    <section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center" id="penjualanform">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <div class="card-title">Form Tambah Data</div>
                    </div>
                    <div class="card-body">
                        <form method="post" autocomplete="off" action="">
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-11 col-md-12">
                                    <h4>Info Customer</h4>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-horizontal">
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Jenis Penjualan</label>
                                                    <div class="col-lg-5 col-md-12 col-form-label">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" id="jenis_penjualan1" value="ekatalog" v-model="jenis_penjualan" @change="jenisonchange()" />
                                                            <label class="form-check-label" for="jenis_penjualan1">E-Catalogue</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" id="jenis_penjualan2" value="spa" v-model="jenis_penjualan" @change="jenisonchange()"/>
                                                            <label class="form-check-label" for="jenis_penjualan2">SPA</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" id="jenis_penjualan3" value="spb" v-model="jenis_penjualan" @change="jenisonchange()"/>
                                                            <label class="form-check-label" for="jenis_penjualan3">SPB</label>
                                                        </div>
                                                        <div class="invalid-feedback" id="msgjenis_penjualan">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="penjualan" class="col-form-label col-lg-5 col-md-12 labelket">Barang</label>
                                                    <div class="col-5 col-form-label" >
                                                        <!-- <div class="form-check form-check-inline" v-for="item in barang_items" v-bind:key="item.id">
                                                        <span v-if="(item != 'produk')">
                                                            <input class="form-check-input" type="checkbox" v-model="barang" :id="item" :value='item' v-show="shownonekat" @change="barangonchange()">
                                                            <label class="form-check-label" :for="item" v-show="shownonekat">{{item}}</label>
                                                        </span>
                                                        <span v-else>
                                                            <input class="form-check-input" type="checkbox" v-model="barang" :id="item" value='{{item}}' @change="barangonchange()">
                                                            <label class="form-check-label" :for="item">{{item}}</label>
                                                        </span>
                                                        </div> -->
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="jenis_pen1" name="barang[]" value="produk" v-model="formgeneral.barang" @change="jenisbarangonchange()" :disabled="((this.jenis_penjualan.length <= 0 || (this.barang.length <= 1 && this.barang == 'produk')) ? true : false)">
                                                            <label class="form-check-label" for="jenis_pen1">Produk</label>
                                                        </div>
                                                        <div class="form-check form-check-inline" v-show="shownonekat">
                                                            <input class="form-check-input" type="checkbox" id="jenis_pen2" name="barang[]" value="sparepart" v-model="formgeneral.barang" @change="jenisbarangonchange()" :disabled="((this.jenis_penjualan.length <= 0 || (this.barang.length <= 1 && this.barang == 'sparepart')) ? true : false)">
                                                            <label class="form-check-label" for="jenis_pen2">Sparepart</label>
                                                        </div>
                                                        <div class="form-check form-check-inline" v-show="shownonekat">
                                                            <input class="form-check-input" type="checkbox" id="jenis_pen3" name="barang[]" value="jasa" v-model="formgeneral.barang" @change="jenisbarangonchange()" :disabled="((this.jenis_penjualan.length <= 0 || (this.barang.length <= 1 && this.barang == 'jasa')) ? true : false) ">
                                                            <label class="form-check-label" for="jenis_pen3">Jasa</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Nama Customer / Distributor</label>
                                                    <div class="col-lg-5 col-md-12 col-form-label">
                                                        <div class="form-check form-check-inline " id="sudah_dsb">
                                                            <input class="form-check-input" type="radio" name="namadistributor" v-model="formgeneral.customer.status" id="namadistributor1" value="sudah"  @change="customerstatusonchange()"/>
                                                            <label class="form-check-label" for="namadistributor1">Sudah Diketahui</label>
                                                        </div>
                                                        <div class="form-check form-check-inline " id="belum_dsb">
                                                            <input class="form-check-input" type="radio" name="namadistributor" v-model="formgeneral.customer.status" id="namadistributor2" value="belum"  @change="customerstatusonchange()"/>
                                                            <label class="form-check-label" for="namadistributor2">Belum Diketahui</label>
                                                        </div>
                                                        <div class="invalid-feedback" id="msgnamadistributor">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket"></label>
                                                    <div class="col-lg-5 col-md-12">
                                                        <v-select v-model="formgeneral.customer.id" :options="master_customer" @input="customeronchange(customer_id)" :clearable="false" :disabled="(this.formgeneral.customer.status != 'sudah') ? true : false">
                                                        </v-select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Alamat</label>
                                                    <div class="col-lg-7 col-md-12">
                                                        <textarea class="form-control col-form-label" v-model="formgeneral.customer.alamat" readonly></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Telepon</label>
                                                    <div class="col-lg-5 col-md-12">
                                                        <input type="text" class="form-control col-form-label" v-model="formgeneral.customer.telepon" readonly />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-11 col-md-12"  v-show="showekat">
                                    <h4>Info Ekatalog</h4>
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-pills mb-3 nav-justified" id="pills-tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="pills-penjualan-tab" data-toggle="pill" href="#pills-penjualan" role="tab" aria-controls="pills-penjualan" aria-selected="true">Deskripsi Ekatalog</a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="pills-instansi-tab" data-toggle="pill" href="#pills-instansi" role="tab" aria-controls="pills-instansi" aria-selected="false">Instansi</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade show active" id="pills-penjualan" role="tabpanel" aria-labelledby="pills-penjualan-tab">
                                                    <div class="card removeshadow">
                                                            <div class="card-header">
                                                                <h6>Deskripsi Ekatalog</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">No Urut</label>
                                                                    <div class="col-lg-2 col-md-6">
                                                                        <input type="number" class="form-control col-form-label" name="no_urut" id="no_urut" v-model="forminfoakn.deskripsi.nourut" @keyup="nourut(forminfoakn.deskripsi.nourut)"/>
                                                                        <div class="text-danger" id="msgno_urut" v-if="(this.errorinput.deskripsi.nourut == true)">
                                                                            No Urut sudah terpakai
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">No Paket</label>
                                                                    <div class="col-lg-5 col-md-12 input-group">
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend kode_paket">
                                                                                <v-select  v-model="jenis_paket" :options="[{label: 'AK1-', value: 'AK1-'}, {label: 'FKS-', value: 'FKS-'}]" :clearable="false" placeholder="Pilih AKN"></v-select>
                                                                            </div>
                                                                            <input type="text" class="form-control col-form-label no_paket" aria-label="ket_no_paket" v-model="forminfoakn.deskripsi.nopaket" :readonly="!forminfoakn.deskripsi.checkbox_no_paket" @keyup="nopaket(forminfoakn.deskripsi.nopaket)" />
                                                                            <div class="input-group-append" id="checkbox_nopaket" :hidden="(this.status_ekat != 'draft' && this.status_ekat != 'batal') ? true : false">
                                                                                <span class="input-group-text">
                                                                                    <div class="form-check form-check-inline">
                                                                                        <input class="form-check-input" type="checkbox" name="isi_nopaket" id="isi_nopaket" value="true" :checked="forminfoakn.deskripsi.checkbox_no_paket" @change="(forminfoakn.deskripsi.checkbox_no_paket = !forminfoakn.deskripsi.checkbox_no_paket)"/>
                                                                                        <label class="form-check-label" for="isi_nopaket"></label>
                                                                                    </div>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="text-danger" id="msgno_paket" v-if="(this.errorinput.deskripsi.nopaket == true)">
                                                                            No Paket sudah terpakai
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Status</label>
                                                                    <div class="col-lg-6 col-md-12 col-form-label">
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" v-model="status_ekat" id="satuan1" value="sepakat" @change="statusekatonchange()"/>
                                                                            <label class="form-check-label" for="satuan1">Sepakat</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" v-model="status_ekat" id="satuan2" value="negosiasi" @change="statusekatonchange()"/>
                                                                            <label class="form-check-label" for="satuan2">Negosiasi</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" v-model="status_ekat" id="satuan3" value="batal" @change="statusekatonchange()"/>
                                                                            <label class="form-check-label" for="satuan3">Batal</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" v-model="status_ekat" id="satuan4" value="draft" @change="statusekatonchange()"/>
                                                                            <label class="form-check-label" for="satuan4">Draft</label>
                                                                        </div>
                                                                        <div class="invalid-feedback" id="msgstatus">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row" v-show="allowproduk">
                                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket"></label>
                                                                    <div class="col-lg-6 col-md-12 col-form-label">
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="checkbox" name="isi_produk" id="isi_produk" value="isi" :checked="forminfoakn.deskripsi.isi_no_paket" @change="(forminfoakn.deskripsi.isi_no_paket = !forminfoakn.deskripsi.isi_no_paket)"/>
                                                                            <label class="form-check-label" for="isi_produk">Isi Produk</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Tanggal Buat</label>
                                                                    <div class="col-lg-4">
                                                                        <input type="date" class="form-control col-form-label" v-model="forminfoakn.deskripsi.tgl_buat" :max="this.curdate"/>
                                                                        <div class="invalid-feedback" id="msgtanggal_pemesanan">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Tanggal Edit</label>
                                                                    <div class="col-lg-4">
                                                                        <input type="date" class="form-control col-form-label" v-model="forminfoakn.deskripsi.tgl_edit" :min="this.forminfoakn.deskripsi.tgl_buat != '' ? this.forminfoakn.deskripsi.tgl_buat : this.curdate"/>
                                                                        <div class="invalid-feedback" id="msgtanggal_edit">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Tanggal Delivery</label>
                                                                    <div class="col-lg-4 col-md-12">
                                                                        <input type="date" class="form-control col-form-label" v-model="forminfoakn.deskripsi.batas_kontrak" :disabled="this.status_ekat != 'sepakat' ? true : false" :min="this.forminfoakn.deskripsi.tgl_buat != '' ? this.forminfoakn.deskripsi.tgl_buat : this.curdate"/>
                                                                        <div class="invalid-feedback" id="msgbatas_kontrak">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="tab-pane fade" id="pills-instansi" role="tabpanel" aria-labelledby="pills-penjualan-tab">
                                                    <div class="card removeshadow">
                                                            <div class="card-header">
                                                                <h6>Instansi</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Instansi</label>
                                                                    <div class="col-lg-7 col-md-12 autocomplete">
                                                                        <v-select taggable :options="master_instansi" v-model="forminfoakn.instansi.nama" :clearable="false"/>
                                                                        <!-- <input type="text" class="form-control col-form-label" name="instansi" id="instansi" autocomplete="off" /> -->
                                                                        <div class="invalid-feedback" id="msginstansi">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Satuan Kerja</label>
                                                                    <div class="col-lg-6 col-md-12">
                                                                        <v-select taggable :options="master_satuan" v-model="forminfoakn.instansi.satuankerja" :clearable="false"/>
                                                                        <!-- <input type="text" class="form-control col-form-label" name="satuan_kerja" id="satuan_kerja" /> -->
                                                                        <div class="invalid-feedback" id="msgsatuan_kerja">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Alamat Instansi</label>
                                                                    <div class="col-lg-7 col-md-12">
                                                                        <v-select taggable :options="master_alamat" v-model="forminfoakn.instansi.alamat" :clearable="false" class="alamat_v_select"/>
                                                                        <div class="invalid-feedback" id="msgalamatinstansi">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Provinsi</label>
                                                                    <div class="col-lg-7 col-md-12">
                                                                        <v-select :options="master_provinsi" :clearable="false" v-model="forminfoakn.instansi.provinsi" :disabled="this.status_ekat != 'sepakat' ? true : false">
                                                                        </v-select>
                                                                        <div class="invalid-feedback" id="msgprovinsi">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Deskripsi</label>
                                                                    <div class="col-lg-5 col-md-12">
                                                                        <v-select taggable :options="master_deskripsi" v-model="forminfoakn.instansi.deskripsi" :clearable="false" class="deskripsi_v_select"/>
                                                                        <div class="invalid-feedback" id="msgdeskripsi">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="keterangan" class="col-form-label col-lg-5 col-md-12 labelket">Keterangan</label>
                                                                    <div class="col-lg-5 col-md-12">
                                                                        <textarea class="form-control col-form-label" name="keterangan" v-model="forminfoakn.instansi.keterangan"></textarea>
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
                            <div class="row d-flex justify-content-center">
                                <div class="col-lg-11 col-md-12" v-show="shownonekat">
                                    <h4>Info Penjualan</h4>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Nomor PO</label>
                                                <div class="col-lg-4 col-md-12">
                                                    <input type="text" class="form-control col-form-label" id="no_po" v-model="formpenjualanpo.po.no_po" />
                                                    <div class="invalid-feedback" id="msgno_po">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Tanggal PO</label>
                                                <div class="col-lg-4 col-md-12">
                                                    <input type="date" class="form-control col-form-label" id="tanggal_po" name="tanggal_po"  v-model="formpenjualanpo.po.tgl_po" />
                                                    <div class="invalid-feedback" id="msgtanggal_po">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Delivery Order</label>
                                                <div class="col-lg-5 col-md-12 col-form-label">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="do" id="yes" value="yes" v-model="formpenjualanpo.do.status" @click="showDO = true"/>
                                                        <label class="form-check-label" for="yes">Tersedia</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="do" id="no" value="no" v-model="formpenjualanpo.do.status" @click="showDO = false"/>
                                                        <label class="form-check-label" for="no">Tidak tersedia</label>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-group row" id="do_detail_no" v-show="showDO">
                                                <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Nomor DO</label>
                                                <div class="col-lg-4 col-md-12">
                                                    <input type="text" class="form-control col-form-label" id="no_do" name="no_do" v-model="formpenjualanpo.do.no_do"/>
                                                    <div class="invalid-feedback" id="msgno_do">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="do_detail_tgl" v-show="showDO">
                                                <label for="" class="col-form-label col-lg-5 col-md-12 labelket">Tanggal DO</label>
                                                <div class="col-lg-4 col-md-12">
                                                    <input type="date" class="form-control col-form-label" id="tanggal_do" name="tanggal_do" v-model="formpenjualanpo.do.tgl_do"/>
                                                    <div class="invalid-feedback" id="msgtanggal_do">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="keterangan" class="col-form-label col-lg-5 col-md-12 labelket">Keterangan</label>
                                                <div class="col-lg-5 col-md-12">
                                                    <textarea class="form-control col-form-label" id="nonketerangan" name="keterangan" v-model="formpenjualanpo.po.keterangan"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row d-flex justify-content-center" id="dataproduk">
                                <div class="col-lg-11 col-md-12" v-show="(showproduk == true && forminfoakn.deskripsi.isi_no_paket == true) ? true : false">
                                    <h4>Data Produk</h4>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive justify-content-center">
                                                        <table class="table tableproduk" style="text-align: center; width:100%">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="7">
                                                                        <button type="button" class="btn btn-primary float-right" id="addrowproduk" @click="addProduk()"><i class="fas fa-plus"></i> Produk</button>
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th width="2%">No</th>
                                                                    <th width="35%">Nama Paket</th>
                                                                    <th width="10%">Jumlah</th>
                                                                    <th width="6%">Stok Distributor</th>
                                                                    <th width="15%">Harga</th>
                                                                    <th width="15%">Ongkir</th>
                                                                    <th width="15%">Subtotal</th>
                                                                    <th width="2%">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody v-if="loadingproduk">
                                                                <tr>
                                                                    <td colspan="8">
                                                                        <div class="spinner-border" role="status">
                                                                            <span class="sr-only">Loading...</span>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tbody v-else>
                                                                <tr v-for="(prd,idx) in formproduk" :key="idx+'1'">
                                                                    <td v-text="(idx+1)"></td>
                                                                    <td><v-select v-model="prd.produk" :options="master_produk" @input="inputProduk(idx, prd.produk)" :clearable="false" @keyup="produktableonchange()"></v-select>
                                                                        <div v-if="prd.detail_pesanan.length > 0">
                                                                            <p class="text-bold">Detail Produk</p>
                                                                            <div class="card">
                                                                                <div class="card-body">
                                                                                    <div v-for="(nmprd, indexprd) in prd.detail_pesanan" :key="'3'+indexprd">
                                                                                        <div v-for="(det, indexdetail) in nmprd.produk" :key="'4'+indexdetail">
                                                                                            <p>{{det.nama}}</p>
                                                                                            <div class="spinner-border" role="status" v-if="loadingvarian">
                                                                                                <span class="sr-only">Loading...</span>
                                                                                            </div>
                                                                                            <v-select v-else :options="produkvarian(det.gudang_barang_jadi, det.nama)" :clearable="false" v-model="prd.detail_pesanan_produk[indexdetail]" @input="inputVarian(idx,$event)">
                                                                                                <template slot="selected-option" slot-scope="option">
                                                                                                    <p class="text-center"> {{ option.nama }}
                                                                                                        <span class="badge badge-info" >{{ option.stok }}</span>
                                                                                                    </p>
                                                                                                </template>
                                                                                                <template v-slot:option="option">
                                                                                                    <p class="text-center">{{ option.nama }}
                                                                                                        <span class="badge badge-info" >{{ option.stok }}</span >
                                                                                                    </p>
                                                                                                </template>
                                                                                            </v-select>
                                                                                            <small class="text-danger" v-show="stokKurang(prd.qty, prd.detail_pesanan_produk[indexdetail])">
                                                                                                Jumlah Kurang {{prd.qty - prd.detail_pesanan_produk[indexdetail].stok}} dari permintaan
                                                                                            </small>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><input-qty v-model="prd.qty" :nilai="prd.qty" @input="calculate(idx)" @keyup="produktableonchange()"></input-qty></td>
                                                                    <td>
                                                                        <div class="form-check col-form-label">
                                                                            <input type="checkbox" class="form-check-input stok_dist" id="stok_dist" v-model="prd.stok_dist" value="1">
                                                                        </div>
                                                                    </td>
                                                                    <td><input-price v-model="prd.price" :nilai="prd.price" @input="calculate(idx)" @keyup="produktableonchange()"></input-price></td>
                                                                    <td><input-price v-model=" prd.shippingcharge" :nilai="prd.shippingcharge" @input="calculate(idx)" @keyup="produktableonchange()"></input-price></td>
                                                                    <td><div class="spinner-border" role="status" v-if="loadingtotal">
                                                                            <span class="sr-only">Loading...</span>
                                                                        </div>
                                                                        <p> Rp. {{ formatRupiah(  subtotal(  prd.qty,  prd.price,  prd.shippingcharge  ) ) }}</p>
                                                                    </td>
                                                                    <td><i class="fas fa-minus" style="color: red;" @click="removeprd(idx)"></i></td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr><div class="spinner-border" role="status" v-if="loadingtotal">
                                                                        <span class="sr-only">Loading...</span>
                                                                    </div>
                                                                    <th colspan="5" v-else style="text-align: right;">Total Harga </th>
                                                                    <th colspan="2" id="totalhargaprd" class="align-right">Rp. {{ formatRupiah( totalproduk() ) }} </th>
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
                            <div class="row d-flex justify-content-center hide" id="datapart" >
                                <div class="col-lg-11 col-md-12" v-show="showpart">
                                    <h4>Part</h4>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive justify-content-center">
                                                        <table class="table tablepart" style="text-align: center;">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="6">
                                                                        <button type="button" class="btn btn-primary float-right" id="addrowproduk" @click="addPart()"><i class="fas fa-plus"></i> Produk</button>
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th width="5%">No</th>
                                                                    <th width="35%">Nama Part</th>
                                                                    <th width="18%">Jumlah</th>
                                                                    <th width="20%">Harga</th>
                                                                    <th width="20%">Subtotal</th>
                                                                    <th width="5%">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody v-if="loadingsparepart">
                                                                <tr>
                                                                    <td colspan="6">
                                                                        <div class="spinner-border" role="status">
                                                                            <span class="sr-only">Loading...</span>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tbody v-else>
                                                                <tr v-for="(part,idx) in formsparepart" :key="idx+'1'">
                                                                    <td v-text="(idx+1)"></td>
                                                                    <td><v-select v-model="part.sparepart" :options="partselect" @input="inputPart(idx, part.sparepart)" :clearable="false"></v-select>
                                                                    </td>
                                                                    <td><input-qty v-model="part.qty" :nilai="part.qty" @input="calculate(idx)"></input-qty></td>
                                                                    <td><input-price v-model="part.price" :nilai="part.price" @input="calculate(idx)"></input-price></td>
                                                                    <td><div class="spinner-border" role="status" v-if="loadingtotal">
                                                                            <span class="sr-only">Loading...</span>
                                                                        </div>
                                                                        <p> Rp. {{ formatRupiah(  subtotal(  part.qty,  part.price,  0  ) ) }}</p>
                                                                    </td>
                                                                    <td><i class="fas fa-minus" style="color: red;" @click="removeprt(idx)"></i></td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr><div class="spinner-border" role="status" v-if="loadingtotal">
                                                                        <span class="sr-only">Loading...</span>
                                                                    </div>
                                                                    <th colspan="4" v-else style="text-align: right;">Total Harga </th>
                                                                    <th colspan="2" id="totalhargasparepart" class="align-right">Rp. {{ formatRupiah( totalsparepart() ) }} </th>
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
                            <div class="row d-flex justify-content-center hide" id="datajasa">
                                <div class="col-lg-11 col-md-12" v-show="showjasa">
                                    <h4>Jasa</h4>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="table-responsive justify-content-center">
                                                        <table class="table tablejasa" style="text-align: center;">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="5">
                                                                        <button type="button" class="btn btn-primary float-right" id="addrowproduk" @click="addJasa()"><i class="fas fa-plus"></i> Produk</button>
                                                                    </th>
                                                                </tr>
                                                                <tr>
                                                                    <th width="5%">No</th>
                                                                    <th width="35%">Nama Jasa</th>
                                                                    <th width="20%">Harga</th>
                                                                    <th width="20%">Subtotal</th>
                                                                    <th width="5%">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody v-if="loadingjasa">
                                                                <tr>
                                                                    <td colspan="5">
                                                                        <div class="spinner-border" role="status">
                                                                            <span class="sr-only">Loading...</span>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                            <tbody v-else>
                                                                <tr v-for="(jasa, idx) in formjasa" :key="idx+'1'">
                                                                    <td v-text="(idx+1)"></td>
                                                                    <td><v-select v-model="jasa.jasa" :options="jasaselect" @input="inputJasa(idx, jasa.jasa)" :clearable="false"></v-select>
                                                                    </td>
                                                                    <td><input-price v-model="jasa.price" :nilai="jasa.price" @input="calculate(idx)"></input-price></td>
                                                                    <td><div class="spinner-border" role="status" v-if="loadingtotal">
                                                                            <span class="sr-only">Loading...</span>
                                                                        </div>
                                                                        <p> Rp. {{ formatRupiah(  subtotal(  1,  jasa.price,  0  ) ) }}</p>
                                                                    </td>
                                                                    <td><i class="fas fa-minus" style="color: red;" @click="removeprt(idx)"></i></td>
                                                                </tr>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr><div class="spinner-border" role="status" v-if="loadingtotal">
                                                                        <span class="sr-only">Loading...</span>
                                                                    </div>
                                                                    <th colspan="3" v-else style="text-align: right;">Total Harga </th>
                                                                    <th colspan="2" id="totalhargaprd" class="align-right">Rp. {{ formatRupiah( totaljasa() ) }} </th>
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
                            <div class="row d-flex justify-content-center">
                                <div class="col-11">
                                    <span>
                                        <a href="" type="button" class="btn btn-danger">
                                            Batal
                                        </a>
                                    </span>
                                    <span class="float-right">
                                        <button type="submit" class="btn btn-info" id="btntambah" :disabled="!completeForm">
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
</section>
</template>
<script>
import axios from "axios";
// import moment from "moment";
// import mix from "../mixins/mix";
import vSelect from "vue-select";
import inputPrice from "../../components/inputprice";
import inputQty from "../../components/inputqty";
export default {
    components: {
        vSelect,
        inputPrice,
        inputQty,
    },
    data() {
        return {
            id: '',
            jenis: '',

            curdate: null,

            showekat: false,
            shownonekat: false,
            showDO: false,

            showproduk: false,
            showpart: false,
            showjasa: false,

            allowproduk: false,
            status_ekat: null,

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

            jenis_penjualan:'',
            jenis_paket: 'AK1-',

            master_customer: [],
            master_provinsi: [],

            master_instansi: [],
            master_satuan: [],
            master_alamat: [],
            master_deskripsi: [],

            master_produk: [],
            master_sparepart: [],
            master_jasa: [],

            search: "",
            offset: "",
            limit: 10,
            loadingtotal: false,
            // barang: {
            //     produk: true,
            //     sparepart: false,
            //     jasa: false,
            // },



            errorinput:{
                customer: {
                    id: null,
                    alamat: '',
                    telepon: '',
                },
                deskripsi: {
                    no_urut: null,
                    no_paket: null,
                    status: null,
                    tgl_buat: null,
                    tgl_edit: null,
                    tgl_delivery: null,
                },
                instansi: {
                    nama: null,
                    satuankerja: null,
                    alamat: null,
                    provinsi: null,
                    deskripsi: null,
                    keterangan: null,
                },
            },
            formgeneral: {
                barang: [],
                customer: {
                    id: null,
                    alamat: '',
                    telepon: '',
                    status: null,
                },
            },
            forminfoakn: {
                deskripsi: {
                    no_urut: null,
                    no_paket: null,
                    checkbox_no_paket: true,
                    isi_no_paket: true,
                    status: null,
                    tgl_buat: null,
                    tgl_edit: null,
                    batas_kontrak: null,
                },
                instansi: {
                    nama: null,
                    satuankerja: null,
                    alamat: null,
                    provinsi: null,
                    deskripsi: null,
                    keterangan: null,
                },
            },
            formpenjualanpo: {
                po: {
                    no_po: null,
                    tgl_po: null,
                    keterangan: null
                },
                do: {
                    no_do: null,
                    tgl_do: null,
                },
            },

            produk_id_null : 0,
            produk_qty_null : 0,
            produk_price_null : 0,
            formproduk: [
                {
                    id: null,
                    produk: null,
                    qty: 0,
                    stok_dist: '',
                    price: 0,
                    shippingcharge: 0,
                    subtotal: 0,
                    detail_pesanan: [],
                    detail_pesanan_produk: [],
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
        async getData(){
           this.id = this.$route.params.id;
           this.jenis = this.$route.params.jenis;

           if(this.jenis == "ekat"){
                this.showekat = true;
                this.shownonekat = false;
                this.showproduk = true;

                axios.get("/api/customer/get_instansi/").then((response) => {
                    this.master_instansi = response.data;
                });
                axios.get("/api/ekatalog/all_satuan").then((response) => {
                    this.master_satuan = response.data;
                });
                axios.get("/api/penjualan/check_alamat").then((response) => {
                    this.master_alamat = response.data;
                });
                axios.get("/api/ekatalog/all_deskripsi").then((response) => {
                    this.master_deskripsi = response.data;
                });
                axios.get("/api/provinsi/select").then((response) => {
                    this.master_provinsi = response.data;
                });
                axios.get("/api/penjualan_produk/select_param/ekatalog").then((response) => {
                    this.master_produk = response.data;
                });
           }else{
                this.showekat = false;
                this.shownonekat = true;
           }
        }
    }
}
</script>
