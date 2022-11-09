<script>
    import Header from '../components/header.vue'
    import DataTable from 'vue2-datatable-component'
    export default {
        components: {
            Header,
            DataTable
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
                currentPage: 1,
                perPage: 3,
                products: [
                    {
                        'kode': 'PRD-001',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 1',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 1',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 1',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 2',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'ABM',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 2',
                        'jenis': 'Jenis 3',
                    },
                    {
                        'kode': 'PRD-001',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 1',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 1',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 1',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 2',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'ABM',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 2',
                        'jenis': 'Jenis 3',
                    },
                    {
                        'kode': 'PRD-001',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 1',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 1',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 1',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 2',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'ABM',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 2',
                        'jenis': 'Jenis 3',
                    },
                    {
                        'kode': 'PRD-001',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 1',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 1',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 1',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 2',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'ABM',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 2',
                        'jenis': 'Jenis 3',
                    },
                    {
                        'kode': 'PRD-001',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 1',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 1',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 1',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'Produk 1',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 1',
                        'jenis': 'Jenis 2',
                    },
                    {
                        'kode': 'PRD-002',
                        'nama': 'ABM',
                        'deskripsi': 'Deskripsi Produk 2',
                        'jumlah': '10',
                        'satuan': 'pcs',
                        'kategori': 'Kategori 2',
                        'jenis': 'Jenis 3',
                    },
                ]
            }
        },
        methods: {
            tambahProduk() {

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
            nextPage(){
                if(this.currentPage < this.paginateProducts){
                    this.currentPage++
                }
            },
            previousPage(){
                if(this.currentPage != 1){
                    this.currentPage--
                }
            },
        },
        computed: {
            filteredProducts() {
                return this.products.filter((product) => {
                    return product.nama.toLowerCase().includes(this.search.toLowerCase())
                    || product.kode.toLowerCase().includes(this.search.toLowerCase())
                    || product.deskripsi.toLowerCase().includes(this.search.toLowerCase())
                    || product.jumlah.toLowerCase().includes(this.search.toLowerCase())
                    || product.satuan.toLowerCase().includes(this.search.toLowerCase())
                    || product.kategori.toLowerCase().includes(this.search.toLowerCase())
                    || product.jenis.toLowerCase().includes(this.search.toLowerCase())
                })
            },
            renderPaginate() {
                return this.filteredProducts.slice(this.perPage * (this.currentPage - 1), this.perPage * this.currentPage)
            },
            productsNested() {
                let nested = []
                let categories = []
                let types = []
                this.renderPaginate.forEach(product => {
                    if (!categories.includes(product.kategori)) {
                        categories.push(product.kategori)
                    }
                    if (!types.includes(product.jenis)) {
                        types.push(product.jenis)
                    }
                })
                categories.forEach(category => {
                    let categoryObj = {}
                    categoryObj[category] = []
                    types.forEach(type => {
                        let typeObj = {}
                        typeObj[type] = []
                        this.renderPaginate.forEach(product => {
                            if (product.kategori == category && product.jenis == type) {
                                typeObj[type].push(product)
                            }
                        })
                        if (typeObj[type].length > 0) {
                            categoryObj[category].push(typeObj)
                        }
                    })
                    nested.push(categoryObj)
                })
                return nested
            },
            paginateProducts() {
                return Math.ceil(this.filteredProducts.length / this.perPage)
            },
        },
        mounted() {
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
                                <a class="page-link"
                                :disabled="currentPage == 1"
                                @click="previousPage">Previous</a>
                            </li>
                            <li class="page-item" :class="paginate == currentPage ? 'active': ''" v-for="paginate in paginateProducts" :key="paginate">
                                <a class="page-link">{{ paginate }}</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link"
                                :disabled="currentPage == paginateProducts-1 ? true : false"
                                 @click="nextPage">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Body
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
