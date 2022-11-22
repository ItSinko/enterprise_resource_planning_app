<script>
    import Header from '../components/header.vue'
    import UploadImages from "../components/upload-images.vue";
    import UploadFiles from "../components/upload-files.vue";
    import axios from 'axios'
    import mix from './mix'
    export default {
        mixins: [mix],
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
                kategoriProduk: [],
                bahanProduk: [],
                merkProduk: [],
                satuanProduk: [],
                formUmum: {
                    jenis: null,
                    kategori: null,
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
                    lampiran: [],
                    lampiranEdit: [],
                },
            }
        },
        created() {
            this.init()
        },
        methods: {
            async init() {
                await axios.get("/api/produk/selectkelompok").then((res) => {
                    this.jenisProduk = res.data.data;
                });

                await axios.get("/api/produk/selectkategori").then((res) => {
                    this.kategoriProduk = res.data.data;
                });

                await axios.get("/api/jenis_bahan/selectdata").then((res) => {
                    this.bahanProduk = res.data.data;
                });

                await axios.get("/api/jenis_satuan/selectdata").then((res) => {
                    this.satuanProduk = res.data.data;
                });

                if(this.$route.params.id != undefined){
                    await axios.get("/api/produk/teknik/edit/"+this.$route.params.id).then((res) => {
                        this.formUmum = res.data.data.formUmum;
                        this.formSpecs = res.data.data.formSpecs;
                        this.formEkatalog = res.data.data.formEkatalog;
                        
                        this.formUmum.imageEdit = res.data.data.formUmum.image;
                        this.formEkatalog.lampiran = [];
                        this.$store.dispatch('setProdukLampiran', res.data.data.formEkatalog.lampiranEdit);
                    });
                }
            },
            handleImages(images) {
                this.formUmum.image = images[0]
            },
            deleteImage() {
                this.$store.dispatch('setLoading', true)
                this.formUmum.image = null
                delete this.formUmum.imageEdit
                this.$store.dispatch('setLoading', false)
            },
            deleteLampiran(index) {
                this.$store.dispatch('setLoading', true)
                this.$store.dispatch('deleteProdukLampiran', index)
                this.$store.dispatch('setLoading', false)
            },
            uploadEkatalog(files) {
                console.log(files)
                this.formEkatalog.lampiran.push(files[0])
            },
            cancel() {
                this.$router.push('/teknik/produk')
            },
            // save() {
            //     if(Object.keys(this.$route.params).length > 0){
            //         let mergeArrayFile = this.formEkatalog.lampiran.concat(this.formEkatalog.lampiranEdit);
            //         this.formEkatalog.lampiran = mergeArrayFile;
            //         this.saving()
            //     }else{
            //         this.formEkatalog.lampiran = this.formEkatalog.lampiran;
            //         this.saving()
            //     }
            // },
            async save(){
                let lampiran = [];
                if(Object.keys(this.$route.params).length > 0){
                    let mergeArrayFile = this.formEkatalog.lampiran.concat(this.formEkatalog.lampiranEdit);
                    lampiran = mergeArrayFile;
                }else{
                    lampiran = this.formEkatalog.lampiran;
                }
                console.log(lampiran)

                let data = new FormData();
                data.append('jenis', this.formUmum.jenis.value);
                data.append('kategori', this.formUmum.kategori.value);
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
                for (let i = 0; i < lampiran.length; i++) {
                    data.append('lampiran[]', lampiran[i]);
                }
                if (Object.keys(this.$route.params).length > 0) {
                    await axios.post("/api/produk/teknik/update/"+this.$route.params.id, data).then((res) => {
                        this.$swal('Berhasil', 'Data berhasil diubah', 'success')
                        this.$router.push('/teknik/produk')
                    });
                } else {
                    await axios.post("/api/produk/teknik/store", data).then((res) => {
                        this.$swal('Berhasil', 'Data berhasil disimpan', 'success')
                        this.$router.push('/teknik/produk')
                    });
                }
            }
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
                                <label for="">Jenis Produk</label>
                                <v-select :options="jenisProduk" v-model="formUmum.jenis">
                                </v-select>
                            </div>

                            <div class="form-group">
                                <label for="">Kategori Produk</label>
                                <v-select :options="kategoriProduk" v-model="formUmum.kategori">
                                </v-select>
                            </div>

                            <div class="form-group">
                                <label for="">Kode Produk</label>
                                <input type="text" class="form-control" v-model="formUmum.kode">
                            </div>

                            <div class="form-group">
                                <label for="">Nama Produk</label>
                                <textarea class="form-control" cols="2" rows="2" v-model="formUmum.nama"></textarea>
                            </div>
                        </div>

                        <div class="col" v-if="!this.$store.state.loading">
                            <div class="card" style="width: 18rem"
                                v-if="formUmum.imageEdit != null && formUmum.imageEdit != undefined">
                                <img :src="`/storage/produk/${formUmum.imageEdit}`">
                                <div class="card-img-overlay">
                                    <button class="btn btn-sm btn-danger" @click="deleteImage">
                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <UploadImages @changed="handleImages" :max="1" v-else
                                maxError="Maksimal 1 gambar" uploadMsg="Silahkan Upload Gambar Produk Disini"
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
                            <div class="form-group"><label for="">Nama Perusahaan</label>
                                <input type="text" class="form-control" v-model="formEkatalog.perusahaan">
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
                    <div class="row" v-if="!$store.state.loading">
                        <div :class="$store.state.produkLampiran.length > 0 ? 'col-8' : 'col-12'">
                            <upload-files @changed="uploadEkatalog" :multiple="true">
                            </upload-files>
                        </div>
                        <div class="col" v-if="$store.state.produkLampiran.length > 0">
                            <div class="row">
                                <div class="col-6" v-for="(lampiran, index) in $store.state.produkLampiran" :key="index">
                                    <div class="row">
                                        <div class="col-10">
                                            <a :href="`/storage/lampiran_produk/${lampiran.path}`" target="_blank">
                                                <i class="fa fa-file"></i>
                                                {{lampiran.path}}
                                            </a>
                                        </div>
                                        <div class="col-2">
                                            <button class="btn btn-danger btn-sm" @click="deleteLampiran(index)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
