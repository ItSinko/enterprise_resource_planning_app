<script>
    import Header from '../components/header.vue'
    import UploadImages from "../components/upload-images.vue";
    import UploadFiles from "../components/upload-files.vue";
    import mix from './mixproduk'
    import axios from 'axios'
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
            }
        },
        methods: {
            async init(){
                await axios.get('/api/produk/teknik/data').then(res => {
                    this.products = res.data.data
                })
            },
            tambahProduk() {
                this.$router.push('/teknik/produk/tambah')
            },
            detailProduk(id) {

                this.$router.push(`/teknik/produk/detail/${id}`)    
            },
            editProduk(id) {
                this.$router.push('/teknik/produk/edit/' + id)
            },
            deleteProduk(id) {
                alert('delete')
            },
            
        },
        mounted() {
            this.init()
        },
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
                                                    @click="detailProduk(produk.id)">
                                                    <i class="fas fa-eye"></i>
                                                    Detail
                                                </button>

                                                <button @click="editProduk(produk.id)" class="dropdown-item"
                                                    type="button">
                                                    <i class="fas fa-pencil-alt"></i>
                                                    Edit
                                                </button>

                                                <button @click="deleteProduk(produk.id)" class="dropdown-item"
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

    </div>
</template>
