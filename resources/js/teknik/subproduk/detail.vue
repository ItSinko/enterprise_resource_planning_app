<script>
    import Header from '../components/header.vue'
    import axios from 'axios'
    export default {
        components: {
            Header
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
                    },
                    {
                        name: 'Detail Produk',
                        link: `/teknik/produk/detail/${this.$route.params.id}`
                    }
                ],
                title: 'Detail Produk',
                loading: true,
                headersDetail: null,
                detail: null,
                parts: [],

                // Modal BOM
                titleModalBOM: '',
                partBOM: [
                    {
                        namaPart: null,
                        jumlah: null,
                    }
                ],

                // Pagination Parts BOM
                search: '',
                offset: 0,
                limit: 10,
            }
        },
        created() {
            this.init()
        },
        methods: {
            async init() {
                let id = this.$route.params.id
                try {
                    await axios.get('/api/produk/teknik/detail/' + id).then(res => {
                        this.headersDetail = res.data.data[0].header
                        this.detail = res.data.data[0].detail
                        this.loading = false
                    })

                    await axios.get('/api/part/data').then(res => {
                        res.data.data.forEach(element => {
                            this.parts.push({
                                value: element.id,
                                label: element.nama
                            })
                        })
                    })
                } catch (error) {
                    console.log(error)
                }
            },

            edit(id) {
                this.$router.push(`/teknik/produk/edit/${id}`)
            },

            addBOM() {
                this.titleModalBOM = 'Tambah BOM'
                $('.modalAddBOM').modal('show')
            },

            addPartBOM() {
                this.partBOM.push({
                    namaPart: null,
                    jumlah: null,
                })
            },

            deletePartBOM(index) {
                if (this.partBOM.length > 1) {
                    this.partBOM.splice(index, 1)
                }
            },

            onSearch(query) {
            this.search = query
            this.offset = 0
            },
        },

        computed: {
            filtered() {
            return this.parts.filter((part) =>
                part.label.toLocaleLowerCase().includes(this.search.toLocaleLowerCase())
            )
            },
            paginated() {
            return this.filtered.slice(this.offset, this.limit + this.offset)
            },
            hasNextPage() {
            const nextOffset = this.offset + this.limit
            return Boolean(
                this.filtered.slice(nextOffset, this.limit + nextOffset).length
            )
            },
            hasPrevPage() {
            const prevOffset = this.offset - this.limit
            return Boolean(
                this.filtered.slice(prevOffset, this.limit + prevOffset).length
            )
            },
        }
    }

</script>
<template>
    <div v-if="loading">
        <div class="d-flex justify-content-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div v-else>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="card-text">
                    <div class="container">
                        <div class="row">
                            <div class="col-4">
                                <div class="text-center pb-5">
                                    <img :src="`/storage/produk/default.jpg`" width="200" />
                                </div>
                            </div>
                            <div class="col-8">
                                <h1 class="display-4 text-bold">{{ headersDetail.nama }}</h1>
                                <p class="text-bold">{{ headersDetail.kode }}</p>

                                <div class="mt-1">
                                    <p>Jenis</p>
                                    <p class="text-bold mt-n2">{{ headersDetail.jenis }}</p>
                                </div>

                                <div class="mt-2">
                                    <p>Kategori</p>
                                    <p class="text-bold mt-n2">
                                        {{ headersDetail.kategori }}
                                    </p>
                                </div>

                                <div class="mt-2">
                                    <button class="btn btn-warning" @click="edit($route.params.id)">
                                        <i class="fas fa-pen"></i>
                                        Edit
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="container mt-5">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill"
                                        data-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                        aria-selected="true">Detail</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill"
                                        data-target="#pills-profile" type="button" role="tab"
                                        aria-controls="pills-profile" aria-selected="false">Bill Of Material</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill"
                                        data-target="#pills-contact" type="button" role="tab"
                                        aria-controls="pills-contact" aria-selected="false">Lampiran</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                    aria-labelledby="pills-home-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title text-bold">Deskripsi</h4>
                                            <p class="card-text">{{ detail.deskripsi }}</p>

                                            <div class="card-title text-bold">Informasi Ekatalog</div>
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <td>Model / Tipe</td>
                                                        <td class="text-bold"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>No. Produk Penyedia</td>
                                                        <td class="text-bold"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Merek</td>
                                                        <td class="text-bold"></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <div class="card-title text-bold">Spesifikasi</div>
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <td>Versi</td>
                                                        <td class="text-bold">{{ detail.spesifikasi.versi }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dimensi</td>
                                                        <td class="text-bold">{{ detail.spesifikasi.dimensi }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Bahan</td>
                                                        <td class="text-bold">
                                                            <span v-for="(bahan, id) in detail.spesifikasi.bahan"
                                                                :key="id">
                                                                {{ bahan }} <span
                                                                    v-if="id < detail.spesifikasi.bahan.length - 1">,</span>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Fungsi</td>
                                                        <td class="text-bold">{{ detail.spesifikasi.fungsi }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                    aria-labelledby="pills-profile-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title">
                                                <button class="btn btn-info mb-2" @click="addBOM">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                    Buat BOM
                                                </button>
                                            </div>
                                            <p class="card-text">
                                                <table class="table tableBOM" v-if="!loading">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Kode BOM</th>
                                                            <th>Nama BOM</th>
                                                            <th>Tahun Pembuatan</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(detail, key) in detail.bom" :key="key">
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                                    aria-labelledby="pills-contact-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title text-bold">Dokumen</h4>
                                            <p class="card-text"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Add BOM -->
        <div class="modal fade modalAddBOM" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ titleModalBOM }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Identitas BOM</h4>
                                <div class="card-text text-center">
                                    <div class="form-group row">
                                        <label for="" class="col-4 text-right">Kode BOM</label>
                                        <input type="text" class="form-control col-5">
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-4 text-right">Nama BOM</label>
                                        <input type="text" class="form-control col-5">
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-4 text-right">Tanggal Pembuatan</label>
                                        <input type="date" class="form-control col-5">
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-4 text-right">Status</label>
                                        <div class="row" style="padding-left: 20px">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                    id="inlineRadio1" value="import">
                                                <label class="form-check-label" for="inlineRadio1">Aktif</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                    id="inlineRadio2" value="pdn">
                                                <label class="form-check-label" for="inlineRadio2">Tidak Aktif</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Part BOM</h4>
                                <div class="card-text">
                                    <div class="d-flex flex-row-reverse bd-highlight">
                                        <div class="p-2 bd-highlight">
                                            <button class="btn btn-primary" @click="addPartBOM">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            Tambah
                                        </button>
                                        </div>
                                    </div>
                                        <table class="table text-center">
                                            <thead class="thead-light">
                                                <th>No</th>
                                                <th>Nama Part</th>
                                                <th>Jumlah</th>
                                                <th>Aksi</th>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(part, idx) in partBOM" :key="idx">
                                                    <td>{{ idx+1 }}</td>
                                                    <td>
                                                          <v-select v-model="part.namaPart" :options="paginated" :filterable="false" @search="onSearch">
                                                            <li slot="list-footer" class="pagination">
                                                            <button type="button" class="btn btn-secondary" :disabled="!hasPrevPage" @click="offset -= limit">Prev</button>
                                                            <button type="button" class="btn btn-primary" :disabled="!hasNextPage" @click="offset += limit">Next</button>
                                                            </li>
                                                        </v-select>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                        <input type="number" class="form-control col-3" v-model="part.jumlah">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <i style="color: red" class="fa fa-minus" aria-hidden="true" @click="deletePartBOM(idx)"></i>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
<style scoped>
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
