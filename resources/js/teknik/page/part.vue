<script>
    import Header from '../components/header.vue'
    import UploadImages from "vue-upload-drop-images";
    export default {
        components: {
            Header,
            UploadImages
        },
        mounted() {
            $('.partTable').DataTable({
                "destroy": true,
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        },
        data() {
            return {
                searchpart: '',
                parts: [{
                    id: 1,
                    image: 'https://picsum.photos/200/300',
                    kode: 'PN-0001',
                    name: 'IT',
                    jenis: 'PT. ABC',
                    deskripsi: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.',
                    jumlah: '500000',
                    satuan: 'pcs'
                }, {
                    id: 2,
                    image: 'https://picsum.photos/200/300',
                    kode: 'PN-0002',
                    name: 'IT',
                    jenis: 'PT. ABC',
                    deskripsi: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.',
                    jumlah: '200000',
                    satuan: 'cm'
                }],

                // Modal Detail Part
                tabsDetail: 'detail',
                detailProdukPart: [{
                        id: 1,
                        name: 'IT',
                        versi: 'Vr 1.1',
                        jumlah: '14',
                    },
                    {
                        id: 2,
                        name: 'IT',
                        versi: 'Vr 1.2',
                        jumlah: '14',
                    }
                ],

                // Modal Tambah & Edit Part
                formsTitle: '',
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
                    fungsi: null,
                    deskripsi: null,
                },
                breadcumbs: [{
                    name: 'Beranda',
                    link: '#'
                }, {
                    name: 'Daftar Part',
                    link: '/teknik/part'
                }],

                // BOM
                headerBOM: {
                    namaProduk: 'FOX',
                    namaBOM: 'BOM-001',
                    kodeBOM: 'BOM-001',
                    tahunBuat: '2021',
                    status: 'Aktif',
                }
            }
        },
        methods: {
            detailPart(idx) {
                $('.modalDetailPart').modal('show');
                $('.tableDetailPart').DataTable({
                    "destroy": true,
                    "paging": true,
                    "lengthChange": false,
                    "searching": true,
                    "ordering": false,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
            },
            textEllipsis(text, length) {
                if (text.length > length) {
                    return text.substring(0, length) + '...'
                } else {
                    return text
                }
            },
            numberFormat(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            isNumber(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            addPart() {
                $('.modalAddEdit').modal('show');
                this.formsTitle = 'Tambah Part';
            },
            handleImages(images) {
                this.formUmum.image = images[0];
            },
            editPart(idx) {
                $('.modalAddEdit').modal('show');
                this.formsTitle = 'Edit Part';
            },
            deletePart(idx) {
                this.$swal({
                    title: 'Hapus Part',
                    text: "Apakah anda yakin ingin menghapus part ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$swal(
                            'Terhapus!',
                            'Data berhasil dihapus.',
                            'success'
                        )
                    }
                })
            },
            simpan() {
                this.$swal({
                    title: 'Simpan Part',
                    text: "Apakah anda yakin ingin menyimpan part ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$swal(
                            'Tersimpan!',
                            'Data berhasil disimpan.',
                            'success'
                        )
                    }
                    $('.modalAddEdit').modal('hide');
                })
            },
            detailBOM() {
                $('.modalDetailBOM').modal('show');
                $('.tableDetailBOM').DataTable({
                    "destroy": true,
                    "paging": true,
                    "lengthChange": false,
                    "searching": true,
                    "ordering": false,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
            },
        },
        computed: {
            partsFiltered() {
                return this.parts.filter(part => {
                    return part.name.toLowerCase().includes(this.searchpart.toLowerCase()) ||
                        part.jenis.toLowerCase().includes(this.searchpart.toLowerCase()) ||
                        part.kode.toLowerCase().includes(this.searchpart.toLowerCase()) ||
                        part.deskripsi.toLowerCase().includes(this.searchpart.toLowerCase()) ||
                        part.jumlah.toLowerCase().includes(this.searchpart.toLowerCase()) ||
                        part.satuan.toLowerCase().includes(this.searchpart.toLowerCase())
                })
            }
        }
    }

</script>
<template>
    <div>
        <Header :title="'Daftar Part'" :breadcumbs="breadcumbs"></Header>
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight"><button @click="addPart" class="btn btn-primary"><i
                                class="fas fa-plus"></i> Tambah Part</button></div>
                    <div class="p-2 bd-highlight">
                        <input type="text" placeholder="Cari..." v-model="searchpart" class="form-control"></div>
                </div>
                <table class="table partTable">
                    <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody v-if="partsFiltered.length > 0">
                        <tr v-for="part in partsFiltered" :key="part.id">
                            <td><img :src="part.image" alt="" width="50px"></td>
                            <td>{{part.kode}}</td>
                            <td>{{part.name}}</td>
                            <td>
                                <div class="badge badge-info">{{part.jenis}}</div>
                            </td>
                            <td>{{ textEllipsis(part.deskripsi, 10) }}</td>
                            <td>{{ numberFormat(part.jumlah)}}</td>
                            <td>{{part.satuan}}</td>
                            <td>
                                <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                    aria-haspopup="true" aria-expanded="true"><i class="fas fa-ellipsis-v"></i></div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                    x-placement="bottom-start"
                                    style="position: absolute; transform: translate3d(-117px, 30px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <button class="dropdown-item" type="button" @click="detailPart(part.id)">
                                        <i class="fas fa-eye"></i>
                                        Detail
                                    </button>

                                    <button @click="editPart(part.id)" class="dropdown-item" type="button">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit
                                    </button>

                                    <button @click="deletePart(part.id)" class="dropdown-item" type="button">
                                        <i class="far fa-trash-alt"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr v-if="partsFiltered.length == 0">
                            <td colspan="8" class="text-center">Data tidak ditemukan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Detail Part -->
        <div class="modal modalDetailPart" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-bold">Detail Part</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container ">
                            <div class="row">
                                <div class="col-4">
                                    <div class="text-center pb-5">
                                        <img src="https://picsum.photos/200/300" max-width="300">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <h1 class="display-4 text-bold">Nama Part</h1>
                                    <p class="text-bold">SPC567</p>

                                    <div class="mt-1">
                                        <p>Jenis</p>
                                        <div class="btn btn-outline-secondary mt-n2">Screw</div>
                                    </div>

                                    <div class="mt-2">
                                        <p>Jumlah</p>
                                        <p class="text-bold mt-n2">5000 pcs</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-detail-tab" data-toggle="pill"
                                        href="#pills-detail" role="tab" aria-controls="pills-detail"
                                        aria-selected="true" @click="tabsDetail == 'detail'">Detail</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-bom-tab" data-toggle="pill" href="#pills-bom"
                                        role="tab" aria-controls="pills-bom" aria-selected="true"
                                        @click="tabsDetail == 'bom'">BOM</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-detail" role="tabpanel"
                                    aria-labelledby="pills-detail-tab">
                                    <div class="card bg-secondary mt-2">
                                        <div class="card-body">
                                            <h4 class="card-title">Deskripsi</h4>
                                            <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing
                                                elit. Hic earum quis, facilis ea, tempore non distinctio maxime
                                                consequatur impedit dolore aliquid voluptatem neque repellat, accusamus
                                                aut perspiciatis praesentium a. Veniam?</p>
                                        </div>
                                    </div>

                                    <div class="card bg-secondary mt-2">
                                        <div class="card-body">
                                            <h4 class="card-title">Spesifikasi</h4>
                                            <p class="card-text"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-bom" role="tabpanel"
                                    aria-labelledby="pills-bom-tab">
                                    <table class="table tableDetailPart mt-2">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Produk</th>
                                                <th>Versi BOM</th>
                                                <th>Jumlah Kebutuhan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(detail, key) in detailProdukPart" :key="`${detail.id}detail`">
                                                <td>{{ key + 1 }}</td>
                                                <td>{{ detail.name }}</td>
                                                <td>{{ detail.versi }}</td>
                                                <td>{{ numberFormat(detail.jumlah) }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-info" @click="detailBOM">
                                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Edit Part -->
        <div class="modal modalAddEdit" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ formsTitle }}</h5>
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
                                                    <select class="form-control" v-model="formUmum.jenis">
                                                        <option value="Screw">Screw</option>
                                                        <option value="Bolt">Bolt</option>
                                                        <option value="Nut">Nut</option>
                                                        <option value="Washer">Washer</option>
                                                        <option value="Other">Other</option>
                                                    </select>
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

                                            <div class="col">
                                                <UploadImages @changed="handleImages" :max="1"
                                                    maxError="Maksimal 1 gambar"
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
                                                    <select class="form-control" v-model="formSpecs.bahan"></select>
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
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail BOM -->
        <div class="modal modalDetailBOM" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-bold">Detail BOM</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="card bg-card-danger">
                                            <div class="card-body">
                                                <div class="card-title">Nama Produk</div>
                                                <div class="card-text">{{ headerBOM.namaProduk }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card bg-card-success">
                                            <div class="card-body">
                                                <div class="card-title">Nama BOM</div>
                                                <div class="card-text">{{ headerBOM.namaBOM }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="card bg-card-secondary">
                                            <div class="card-body">
                                                <div class="card-title">Kode BOM</div>
                                                <div class="card-text">{{ headerBOM.kodeBOM }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card bg-card-danger">
                                            <div class="card-body">
                                                <div class="card-title">Tahun Pembuatan</div>
                                                <div class="card-text">{{ headerBOM.tahunBuat }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card bg-card-secondary">
                                            <div class="card-body">
                                                <div class="card-title">Status</div>
                                                <div class="card-text">
                                                    <span class="badge badge-alert">{{ headerBOM.status }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="card-title text-bold">Part Bill Of Material</div>
                            <div class="card-text">
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Part</th>
                                            <th>Nama Part</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<style>
    .bg-card-danger{
        background-color: #ebdfdf;
        color: #9d6569;
    }
    .bg-card-success{
        background-color: #e0e2df;
        color: #697569;
    }
    .bg-card-secondary{
        background-color: #dce2e5;
        color: #536c7c;
    }
</style>
