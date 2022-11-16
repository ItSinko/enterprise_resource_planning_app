<script>
    import Header from '../components/header.vue'
    import UploadImages from "../components/upload-images.vue";
    import UploadFiles from "../components/upload-files.vue";
    import mix from './mixproduk'
    export default {
        mixins: [mix],
        components: {
            Header,
            UploadImages,
            UploadFiles,
        },
        data() {
            return {
                breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Daftar Produk',
                        link: '/teknik/produk'
                    }
                ],
                search: '',
                products: [],

                // Modal Add & Edit
                jenisProduk: [],
                bahanProduk: [],

                merkProduk: [],
                satuanProduk: [],

                modalTitle: null,
                loadingImages: false,
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
                    bahan: null,
                    versi: null,
                    satuan: null,
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
        methods: {
            tambahProduk() {
                this.modalTitle = 'Tambah Produk'
                setTimeout(() => {
                    $('.modalAddEdit').modal('show')
                }, 100)
            },
            detailProduk(id) {
                alert(id)
            },
            editProduk(id) {
                alert('edit')
            },
            deleteProduk(id) {
                alert('delete')
            },
            handleImages(images) {
                this.formUmum.image = images[0]
            },
            uploadEkatalog(files) {
                console.log(files)
                this.formEkatalog.lampiran = files
            },
        },
        mounted() {},
    }

</script>
<template>
    <div>
        <Header title="Daftar Produk" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="tambahProduk">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <div class="input-group">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari Produk">
                        </div>
                    </div>
                </div>
                <table class="table produkTable">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Deskripsi</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody v-if="productsNested.length > 0">
                        <template v-for="kategori in productsNested">
                            <tr class="table-secondary">
                                <td colspan="6">{{ Object.keys(kategori)[0] }}</td>
                            </tr>
                            <template v-for="jenis in Object.values(kategori)[0]">
                                <tr class="table-info">
                                    <td colspan="6">{{ Object.keys(jenis)[0] }}</td>
                                </tr>
                                <template v-for="produk in Object.values(jenis)[0]">
                                    <tr>
                                        <td>{{ produk.kode }}</td>
                                        <td>{{ produk.nama }}</td>
                                        <td>{{ produk.deskripsi }}</td>
                                        <td>{{ produk.jumlah }}</td>
                                        <td>{{ produk.satuan }}</td>
                                        <td>
                                            <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                                aria-haspopup="true" aria-expanded="true"><i
                                                    class="fas fa-ellipsis-v"></i></div>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                                x-placement="bottom-start"
                                                style="position: absolute; transform: translate3d(-117px, 30px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <button class="dropdown-item" type="button"
                                                    @click="detailProduk(part.id)">
                                                    <i class="fas fa-eye"></i>
                                                    Detail
                                                </button>

                                                <button @click="editProduk(part.id)" class="dropdown-item"
                                                    type="button">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    Edit
                                                </button>

                                                <button @click="deleteProduk(part.id)" class="dropdown-item"
                                                    type="button">
                                                    <i class="far fa-trash-alt"></i>
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </template>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="d-flex flex-row-reverse bd-highlight">
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" :disabled="currentPage == 1" @click="previousPage">Previous</a>
                            </li>
                            <li class="page-item" :class="paginate == currentPage ? 'active': ''"
                                v-for="paginate in pages" :key="paginate">
                                <a class="page-link" @click="nowPage(paginate)">{{ paginate }}</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" :disabled="currentPage == pages[pages.length - 1]"
                                    @click="nextPage">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade modalAddEdit" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ modalTitle }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
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
                                                    <textarea class="form-control" cols="5" rows="5"
                                                        v-model="formUmum.nama"></textarea>
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
                                                <UploadImages @changed="handleImages" :max="1" :images="formUmum.image"
                                                    v-else maxError="Maksimal 1 gambar"
                                                    uploadMsg="Silahkan Upload Gambar Part Disini"
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
                                                        <input type="text" v-model="formSpecs.panjang"
                                                            @keypress="isNumber($event)" class="form-control">
                                                    </div>
                                                    <div class="col">
                                                        <label for="">Lebar</label>
                                                        <input type="text" v-model="formSpecs.lebar"
                                                            @keypress="isNumber($event)" class="form-control">
                                                    </div>
                                                    <div class="col">
                                                        <label for="">Tinggi</label>
                                                        <input type="text" v-model="formSpecs.tinggi"
                                                            @keypress="isNumber($event)" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Bahan</label>
                                                    <v-select v-model="formSpecs.bahan" :options="bahanProduk">
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
                                                    <textarea v-model="formSpecs.fungsi" cols="5" rows="3"
                                                        class="form-control"></textarea>
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
                                                <div class="form-group"><label for="">Model / Tipe</label><input
                                                        v-model="formEkatalog.tipe" type="text" class="form-control">
                                                </div>
                                                <div class="form-group"><label for="">Merk</label>
                                                    <v-select v-model="formEkatalog.merk" :options="merkProduk">
                                                    </v-select>
                                                </div>
                                                <div class="form-group"><label for="">No Produk Penyedia</label><input
                                                        v-model="formEkatalog.noproduk" type="text"
                                                        class="form-control"></div>
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
                                                        <input class="form-check-input" type="radio"
                                                            name="inlineRadioOptions" id="inlineRadio1" value="import"
                                                            v-model="formEkatalog.jenis">
                                                        <label class="form-check-label" for="inlineRadio1">Impor</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            name="inlineRadioOptions" id="inlineRadio2" value="pdn"
                                                            v-model="formEkatalog.jenis">
                                                        <label class="form-check-label" for="inlineRadio2">PDN</label>
                                                    </div>
                                                        </div>
                                                    
                                                <div class="form-group"><label for="">Kode KBKI</label><input
                                                        type="text" class="form-control" v-model="formEkatalog.kode"></div>
                                                <div class="form-group"><label for="">Nomor Izin Edar</label><input
                                                        type="text" class="form-control" v-model="formEkatalog.nozin"></div>
                                                <div class="form-group"><label for="">Nilai TKDN (%)</label><input
                                                        type="text" class="form-control" v-model="formEkatalog.tkdn"></div>
                                                <div class="form-group"><label for="">Masa Berlaku Produk</label><input
                                                        type="date" class="form-control" v-model="formEkatalog.expired"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <upload-files @changed="uploadEkatalog" :file="formEkatalog.lampiran" :multiple="true"></upload-files>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
