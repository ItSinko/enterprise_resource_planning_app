<script>
    import Header from '../components/header.vue'
    import axios from 'axios'
    import mix from './mix'
    import DetailBOM from '../components/detailBOM.vue'
    import AddBOM from '../components/addBOM.vue'
    import DownloadFile from '../components/downloadFile.vue'
    export default {
        mixins: [mix],
        components: {
            Header,
            DetailBOM,
            AddBOM,
            DownloadFile,
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
                file : null,
                loading: true,

                // Modal BOM
                titleModalBOM: '',
                partBOM: [
                    {
                        namaPart: null,
                        jumlah: null,
                    }
                ],
                formBom: {
                    kode: null,
                    nama: null,
                    tanggal: null,
                    status: null,
                },

                detailBOMs: null,
            }
        },
        created() {
            this.getDetailProduk()
        },
        updated(){
                        this.tableBOM()
        },
        methods: {
            async getDetailProduk() {
                this.loading = true
                let id = this.$route.params.id
                try {
                    await axios.get('/api/produk/teknik/detail/' + id).then(res => {
                        this.headersDetail = res.data.data[0].header
                        this.detail = res.data.data[0].detail
                        this.file = res.data.data[0].file
                        this.loading = false
                    })
                } catch (error) {
                    console.log(error)
                }
                
            },

            tableBOM(){
                $('.tableBOM').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
            },

            edit(id) {
                this.$router.push(`/teknik/produk/edit/${id}`)
            },

            addBOM() {
                this.titleModalBOM = 'Tambah BOM'
                setTimeout(() => {
                    $('.modalAddBOM').modal('show')
                }, 100);
            },

            async editBOM(id){
                try {
                    await axios.get('/api/bom/edit/' + id).then(res => {
                        console.log(res.data.data)
                        this.titleModalBOM = 'Edit BOM'
                        this.formBom.id = id
                        this.formBom.kode = res.data.data.kode
                        this.formBom.nama = res.data.data.nama
                        this.formBom.tanggal = res.data.data.tanggal
                        this.formBom.status = res.data.data.status
                        this.partBOM = res.data.data.part
                        $('.modalAddBOM').modal('show')
                    })
                } catch (error) {
                    console.log(error)      
                }
            },

            async detailBOM(id) {
            try {
                await axios.get(`/api/bom/detail/${id}`).then((res) => {
                this.detailBOMs = res.data.data[0]
                });
                $(".modalDetailBOM").modal("show");
            $(".tableDetailBOM").DataTable({
                destroy: true,
                paging: true,
                lengthChange: false,
                searching: true,
                ordering: false,
                info: true,
                autoWidth: false,
                responsive: true,
            });
            } catch (error) {
                console.log(error);
            }

            },

            
            status(status){
                switch (status) {
                    case 'Aktif':
                        return `<span class="badge badge-success">${status}</span>`
                    default:
                        return `<span class="badge badge-danger">${status}</span>`
                }
            },

        },
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
                                                                {{ bahan.label }} <span
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
                                                <table class="table tableBOM text-center" v-if="!loading">
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
                                                            <td>{{ key+1 }}</td>
                                                            <td>{{ detail.kode }}</td>
                                                            <td>{{ detail.nama }}</td>
                                                            <td>{{ detail.tahun }}</td>
                                                            <td v-html="status(detail.status)"></td>
                                                            <td>
                                                                <span style="color:#17A2B8" class="pr-2"  @click="detailBOM(detail.id)">
                                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                                </span>
                                                                <span style="color:#FFC107" class="pr-2"  @click="editBOM(detail.id)">
                                                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                                                </span>
                                                                <span style="color:#DC3545"  @click="deleteBOM(detail.id)">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </span>
                                                            </td>
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
                                            <div class="card-text">
                                                <DownloadFile :url="'/storage/lampiran_produk/'" :filename="file"></DownloadFile>
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

        <!-- Modal Add BOM -->
    <AddBOM @refresh="getDetailProduk" :produk_id="$route.params.id" :formBom="formBom" :partBOM="partBOM" :titleModalBOM="titleModalBOM"></AddBOM>

    <!-- Detail BOM -->
    <DetailBOM :detailBOMs="detailBOMs" />
    
    </div>
</template>

