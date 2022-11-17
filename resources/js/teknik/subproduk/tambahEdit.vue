<script>
    import Header from '../components/header.vue'
    import UploadImages from "../components/upload-images.vue";
    import UploadFiles from "../components/upload-files.vue";
    import axios from 'axios'
    export default {
        props: {
            breadcumbs: {
                type: Array,
                default: () => [{
                    name: 'Teknik',
                    link: '/teknik'
                }, {
                    name: 'Daftar Produk',
                    link: '/teknik/produk'
                }, {
                    name: 'Tambah',
                    link: '/teknik/produk/tambah'
                }]
            },
            judul: {
                type: String,
                default: 'Tambah Produk'
            }
        },
        components: {
            Header,
            UploadImages,
            UploadFiles
        },
        data() {
            return {
                loadingImages: false,
                jenisProduk: [],
                bahanProduk: [],
                merkProduk: [],
                satuanProduk: [],
                formUmum: {
                    jenis: null,
                    kode: null,
                    nama: null,
                    image: null,
                },
                formSpecs: {
                    panjang: null,
                    lebar: null,
                    tinggi: null,
                    bahan: [],
                    versi: null,
                    fungsi: null,
                    deskripsi: null,
                },
                formEkatalog: {
                    tipe: null,
                    merk: null,
                    noproduk: null,
                    satuan: null,
                    perusahaan: null,
                    jenis: null,
                    kode: null,
                    noizin: null,
                    tkdn: null,
                    expired: null,
                    lampiran: []
                },
            }
        },
        created() {
            this.init()
        },
        methods: {
            async init() {
                await axios.get("/api/jenis_part/selectdata").then((res) => {
                    this.jenisProduk = res.data.data;
                });

                await axios.get("/api/jenis_bahan/selectdata").then((res) => {
                    this.bahanProduk = res.data.data;
                });

                await axios.get("/api/jenis_satuan/selectdata").then((res) => {
                    this.satuanProduk = res.data.data;
                });
            },
            handleImages(images) {
                this.formUmum.image = images[0]
            },
            uploadEkatalog(files) {
                console.log(files)
                this.formEkatalog.lampiran = files
            },
            cancel() {
                this.$router.push('/teknik/produk')
            },
            async save() {
                let data = new FormData();
                data.append('jenis', this.formUmum.jenis.value);
                data.append('kode', this.formUmum.kode);
                data.append('nama', this.formUmum.nama);
                data.append('image', this.formUmum.image);
                data.append('panjang', this.formSpecs.panjang);
                data.append('lebar', this.formSpecs.lebar);
                data.append('tinggi', this.formSpecs.tinggi);
                for (let i = 0; i < this.formSpecs.bahan.length; i++) {
                    data.append('bahan[]', this.formSpecs.bahan[i].value);
                }
                data.append('versi', this.formSpecs.versi);
                data.append('fungsi', this.formSpecs.fungsi);
                data.append('deskripsi', this.formSpecs.deskripsi);
                data.append('tipe', this.formEkatalog.tipe);
                data.append('merk', this.formEkatalog.merk);
                data.append('noproduk', this.formEkatalog.noproduk);
                data.append('satuan', this.formEkatalog.satuan.value);
                data.append('perusahaan', this.formEkatalog.perusahaan);
                data.append('jenisekat', this.formEkatalog.jenis);
                data.append('kodeekat', this.formEkatalog.kode);
                data.append('noizin', this.formEkatalog.noizin);
                data.append('tkdn', this.formEkatalog.tkdn);
                data.append('expired', this.formEkatalog.expired);
                for (let i = 0; i < this.formEkatalog.lampiran.length; i++) {
                    data.append('lampiran[]', this.formEkatalog.lampiran[i]);
                }
                if (Object.keys(this.$route.params).length > 0) {
                    // this.update()
                } else {
                    await axios.post("/api/produk/teknik/store", data).then((res) => {
                        console.log(res)
                    });
                }
            },
            isNumber(evt) {
                evt = evt ? evt : window.event;
                var charCode = evt.which ? evt.which : evt.keyCode;
                if (
                    charCode > 31 &&
                    (charCode < 48 || charCode > 57) &&
                    charCode !== 46
                ) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },
        }
    }

</script>
<template>
    <div class="container">
        <Header :title="judul" :breadcumbs="breadcumbs"></Header>
        <div class="card">
            <div class="card-body">
                <div class="card-title text-bold pb-2">Informasi Umum</div>
                <div class="card-text">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Jenis</label>
                                <v-select :options="jenisProduk" v-model="formUmum.jenis">
                                </v-select>
                            </div>

                            <div class="form-group">
                                <label for="">Kode Part</label>
                                <input type="text" class="form-control" v-model="formUmum.kode">
                            </div>

                            <div class="form-group">
                                <label for="">Nama Part</label>
                                <textarea class="form-control" cols="5" rows="5" v-model="formUmum.nama"></textarea>
                            </div>
                        </div>

                        <div class="col" v-if="!loadingImages">
                            <div class="card" style="width: 18rem"
                                v-if="formUmum.imageEdit != null && formUmum.imageEdit != undefined">
                                <img :src="formUmum.imageEdit">
                                <div class="card-img-overlay">
                                    <button class="btn btn-sm btn-danger" @click="deleteImage">
                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <UploadImages @changed="handleImages" :max="1" :images="formUmum.image" v-else
                                maxError="Maksimal 1 gambar" uploadMsg="Silahkan Upload Gambar Part Disini"
                                clearAll="Hapus semua gambar" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-bold pb-2">Informasi Spesifik</h4>
                <div class="card-text">
                    <p>Spesifikasi</p>
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <div class="col">
                                    <label for="">Panjang</label>
                                    <input type="text" v-model="formSpecs.panjang" @keypress="isNumber($event)"
                                        class="form-control">
                                </div>
                                <div class="col">
                                    <label for="">Lebar</label>
                                    <input type="text" v-model="formSpecs.lebar" @keypress="isNumber($event)"
                                        class="form-control">
                                </div>
                                <div class="col">
                                    <label for="">Tinggi</label>
                                    <input type="text" v-model="formSpecs.tinggi" @keypress="isNumber($event)"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Bahan</label>
                                <v-select v-model="formSpecs.bahan" multiple :options="bahanProduk">
                                </v-select>
                            </div>
                            <div class="form-group">
                                <label for="">Versi</label>
                                <input type="text" v-model="formSpecs.versi" class="form-control">
                            </div>
                        </div>
                        <div class="col-1"></div>
                        <div class="col-5">
                            <div class="form-group">
                                <label for="">Fungsi</label>
                                <textarea v-model="formSpecs.fungsi" cols="5" rows="3" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Deskripsi</label>
                                <textarea v-model="formSpecs.deskripsi" cols="5" rows="3"
                                    class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-bold pb-2">Informasi Ekatalog</h4>
                <div class="card-text">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group"><label for="">Model / Tipe</label><input v-model="formEkatalog.tipe"
                                    type="text" class="form-control">
                            </div>
                            <div class="form-group"><label for="">Merk</label>
                                <input type="text" class="form-control" v-model="formEkatalog.merk">
                            </div>
                            <div class="form-group"><label for="">No Produk Penyedia</label><input
                                    v-model="formEkatalog.noproduk" type="text" class="form-control"></div>
                            <div class="form-group"><label for="">Satuan</label>
                                <v-select :options="satuanProduk" v-model="formEkatalog.satuan">
                                </v-select>
                            </div>
                        </div>
                        <div class="col-6 pl-5">
                            <label for="">Jenis Produk
                                Ekatalog</label>
                            <div class="row" style="padding-left: 20px">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="inlineRadio1" value="import" v-model="formEkatalog.jenis">
                                    <label class="form-check-label" for="inlineRadio1">Impor</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                        id="inlineRadio2" value="pdn" v-model="formEkatalog.jenis">
                                    <label class="form-check-label" for="inlineRadio2">PDN</label>
                                </div>
                            </div>

                            <div class="form-group"><label for="">Kode KBKI</label><input type="text"
                                    class="form-control" v-model="formEkatalog.kode"></div>
                            <div class="form-group"><label for="">Nomor Izin Edar</label><input type="text"
                                    class="form-control" v-model="formEkatalog.nozin"></div>
                            <div class="form-group"><label for="">Nilai TKDN (%)</label><input type="text"
                                    class="form-control" v-model="formEkatalog.tkdn"></div>
                            <div class="form-group"><label for="">Masa Berlaku Produk</label><input type="date"
                                    class="form-control" v-model="formEkatalog.expired"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <upload-files @changed="uploadEkatalog" :file="formEkatalog.lampiran" :multiple="true">
                            </upload-files>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex bd-highlight mb-3">
            <div class="p-2 bd-highlight">
                <button class="btn btn-danger" @click="cancel">Batal</button>
            </div>
            <div class="ml-auto p-2 bd-highlight">
                <button class="btn btn-primary" @click="save">Simpan</button>
            </div>
        </div>
    </div>
</template>
