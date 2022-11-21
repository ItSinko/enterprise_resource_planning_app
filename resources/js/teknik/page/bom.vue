<script>
    import Header from '../components/header.vue'
    import DetailBOM from '../components/detailBOM.vue'
    import AddBOM from '../components/addBOM.vue'
    import mix from './mixbom'
    import axios from 'axios'
    export default {
        mixins: [mix],
        data() {
            return {
                breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Daftar Bill Of Material',
                        link: '/teknik/bom'
                    },
                ],
                title: 'Daftar Bill Of Material',
                boms: [],
                products: [],
                // Modal BOM
                titleModalBOM: '',
                partBOM: [{
                    namaPart: null,
                    jumlah: null,
                }],
                formBom: {
                    produk_selected: null,
                    kode: null,
                    nama: null,
                    tanggal: null,
                    status: null,
                },
                detailBOMs: null,
            }
        },
        created() {
            this.init();
        },
        methods: {
            async init() {
                try {
                    await axios.get('/api/bom/data').then(response => {
                        if (response.data.data.length > 1) {
                            this.boms = response.data.data
                        } else {
                            this.boms.push(response.data.data)
                        }
                    });


                } catch (error) {
                    console.log(error);
                }
            },
            addBOM() {
                this.titleModalBOM = 'Tambah BOM'
                setTimeout(() => {
                    $('.modalAddBOM').modal('show')
                }, 100);
            },
            async editBOM(id) {
                try {
                    await axios.get('/api/bom/edit/' + id).then(res => {
                        console.log(res.data.data)
                        this.titleModalBOM = 'Edit BOM'
                        this.formBom.id = id
                        this.formBom.produk_selected = res.data.data.produk
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

            async deleteBOM(id){
                await axios.post('/api/bom/delete/' + id).then(res => {
                    this.$swal('Berhasil!', 'Data berhasil di hapus', 'success')
                    this.init()
                })
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
            status(status) {
                switch (status) {
                    case 'Aktif':
                        return `<span class="badge badge-success">${status}</span>`
                    default:
                        return `<span class="badge badge-danger">${status}</span>`
                }
            },
        },
        components: {
            Header,
            DetailBOM,
            AddBOM
        },
    }

</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <AddBOM :products="products" :formBom="formBom" :partBOM="partBOM" :titleModalBOM="titleModalBOM" />
        <DetailBOM :detailBOMs="detailBOMs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="addBOM">
                            <i class="fas fa-plus"></i> Tambah BOM
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari BOM" v-model="search">
                        </div>
                    </div>
                </div>
                <table class="table bomsTable">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Kode BOM</th>
                            <th>Nama BOM</th>
                            <th>Tahun Pembuatan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody v-if="bomNested.length > 0">
                        <template v-for="produk in bomNested">
                            <tr class="table-info">
                                <td colspan="5">{{ Object.keys(produk)[0] }}</td>
                            </tr>
                            <template v-for="detail in Object.values(produk)[0]">
                                <tr class="text-center">
                                    <td>{{ detail.kode }}</td>
                                    <td>{{ detail.nama }}</td>
                                    <td>{{ detail.tahun_pembuatan }}</td>
                                    <td v-html="status(detail.status)"></td>
                                    <td>
                                        <span style="color:#17A2B8" class="pr-2" @click="detailBOM(detail.id)">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                        <span style="color:#FFC107" class="pr-2" @click="editBOM(detail.id)">
                                            <i class="fa fa-edit" aria-hidden="true"></i>
                                        </span>
                                        <span style="color:#DC3545" @click="deleteBOM(detail.id)">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </span>
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data</td>
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
