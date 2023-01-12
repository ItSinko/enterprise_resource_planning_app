<script>
    import moment from "moment";
    import mix from "../../../mix";
    export default {
        mixins: [mix],
        props: {
            dataTables: {
                type: Array,
                default: () => []
            },
        },
        data() {
            return {
                search: ''
            }
        },
        methods: {
            tambah() {
                this.$router.push('/pembelian/permintaan/dalamproses/create')
            },
            moment(date) {
                return moment(date).lang('id').format('LL')
            },
            detail(id) {
                this.$emit('detail', id)
            },
            edit(id) {
                this.$emit('edit', id)
            },
            hapus(id) {
                this.$emit('hapus', id)
            },
            batal(id) {
                this.$emit('batal', id)
            },

            jenis(jenis) {
                switch (jenis) {
                    case 'umum':
                        return `<span class="badge badge-info">Umum</span>`
                        break;
                    case 'part':
                        return `<span class="badge badge-danger">Part</span>`
                        break;
                    default:
                        return `<span class="badge badge-warning">${jenis}</span>`
                        break;
                }
            },

            // PP
            classPP(pp) {
                switch (pp) {
                    case 'upload':
                        return 'btn-warning'
                        break;
                    case 'lihat':
                        return 'btn-outline-primary'
                        break;
                    default:
                        return 'btn-outline-secondary'
                        break;
                }
            },

            iconPP(pp) {
                switch (pp) {
                    case 'upload':
                        return 'fas fa-upload'
                        break;
                    case 'lihat':
                        return 'fas fa-eye'
                        break;
                    default:
                        return 'fas fa-question'
                        break;
                }
            },

            checkPP(id, no_pp, pp) {
                const upload = () => {
                    console.log('upload')
                    this.$emit('upload', id, no_pp)
                }

                const lihat = () => {
                    this.$emit('lihat', pp)
                }
                switch (pp) {
                    case 'upload':
                        return upload()
                        break;
                    case 'lihat':
                        return lihat()
                        break;
                    default:
                        return 'PP Tidak Ada'
                        break;
                }
            },

            status(status) {
                switch (status) {
                    case 'draft':
                        return `<span class="badge badge-info">Draft</span>`
                        break;
                    case 'minta persetujuan':
                        return `<span class="badge badge-info">Minta Persetujuan</span>`
                        break;
                    case 'menunggu persetujuan':
                        return `<span class="badge badge-warning">Menunggu Persetujuan</span>`
                        break;
                    case 'proses pembelian':
                        return `<span class="badge badge-primary">Proses Pembelian</span>`
                        break;
                    case 'batal':
                        return `<span class="badge badge-danger">Batal</span>`
                        break;
                    case 'ditolak pembelian':
                        return `<span class="badge badge-danger">Ditolak Pembelian</span>`
                        break;
                    default:
                        return `<span class="badge badge-success">${status}</span>`
                        break;
                }
            },

            showButtonEdit(status) {
                switch (status) {
                    case 'draft':
                        return true
                        break;
                    case 'minta persetujuan':
                        return false
                        break;
                    case 'menunggu persetujuan':
                        return false
                        break;
                    case 'proses pembelian':
                        return false
                        break;
                    case 'batal':
                        return true
                        break;
                    case 'ditolak pembelian':
                        return true
                        break;
                    default:
                        return false
                        break;
                }
            },

            showButtonBatal(status) {
                switch (status) {
                    case 'draft':
                        return true
                        break;
                    case 'minta persetujuan':
                        return true
                        break;
                    case 'menunggu persetujuan':
                        return false
                        break;
                    case 'proses pembelian':
                        return false
                        break;
                    case 'batal':
                        return false
                        break;
                    case 'ditolak pembelian':
                        return false
                        break;
                    default:
                        return false
                        break;
                }
            },

            showButtonHapus(status) {
                switch (status) {
                    case 'draft':
                        return true
                        break;
                    case 'minta persetujuan':
                        return true
                        break;
                    case 'menunggu persetujuan':
                        return false
                        break;
                    case 'proses pembelian':
                        return false
                        break;
                    case 'batal':
                        return true
                        break;
                    case 'ditolak pembelian':
                        return false
                        break;
                    default:
                        return false
                        break;
                }
            },
        },
        computed: {
            filteredDatatables() {
                const dataIsNotNull = (data) =>
                    data !== null && data !== undefined && data !== "" ? data : "-";
                return this.dataTables.filter((dataTable) => {
                    return (
                        dataIsNotNull(dataTable.no_pp)
                        .toString()
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                        dataIsNotNull(dataTable.jenis_barang)
                        .toString()
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                        dataIsNotNull(dataTable.tanggal_diminta)
                        .toString()
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                        dataIsNotNull(dataTable.tanggal_dibutuhkan)
                        .toString()
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                        dataIsNotNull(dataTable.pp)
                        .toString()
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                        dataIsNotNull(dataTable.status)
                        .toString()
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
                    );
                });
            }
        }
    }

</script>
<template>
    <div>
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <button class="btn btn-primary" @click="tambah">
                    <i class="fas fa-plus"></i>
                    Tambah PP
                </button>
            </div>
            <div class="p-2 bd-highlight">
                <div class="form-group row">
                    <label for="" class="col-3">Search</label>
                    <input type="text" class="form-control col" placeholder="Cari PP" v-model="search">
                </div>
            </div>
        </div>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No PP</th>
                    <th>Jenis Barang</th>
                    <th>Tanggal Diminta</th>
                    <th>Tanggal Dibutuhkan</th>
                    <th>PP</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="renderPaginate.length > 0">
                <tr v-for="(data, index) in renderPaginate" :key="data.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.no_pp }}</td>
                    <td>
                        <div v-html="jenis(data.jenis_barang)"></div>
                    </td>
                    <td>{{ moment(data.tanggal_diminta) }}</td>
                    <td>{{ moment(data.tanggal_dibutuhkan) }}</td>
                    <td>
                        <button class="btn btn-sm" :class="classPP(data.pp)" @click="checkPP(data.id, data.no_pp, data.pp)">
                            <i :class="iconPP(data.pp)"></i>
                            {{ data.pp }}</button></td>
                    <td>
                        <div v-html="status(data.status)"></div>
                    </td>
                    <td>
                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true"
                            aria-expanded="true">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="bottom-start"
                            style="
                                position: absolute;
                                transform: translate3d(-117px, 30px, 0px);
                                top: 0px;
                                left: 0px;
                                will-change: transform;
                            ">
                            <button 
                            @click="detail(data.id)" class="dropdown-item" type="button">
                                <i class="fas fa-eye"></i>
                                Detail
                            </button>

                            <button 
                            v-if="showButtonEdit(data.status)" 
                            @click="edit(data.id)" class="dropdown-item" type="button">
                                <i class="fas fa-pencil-alt"></i>
                                Ubah
                            </button>

                            <button 
                            v-if="showButtonBatal(data.status)"
                            @click="batal(data.id)" class="dropdown-item" type="button">
                                <i class="fas fa-times"></i>
                                Batal
                            </button>

                            <button 
                            v-if="showButtonHapus(data.status)"
                            @click="hapus(data.id)" class="dropdown-item" type="button">
                                <i class="fas fa-trash"></i>
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex flex-row-reverse bd-highlight">
            <nav aria-label="...">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" :disabled="currentPage == 1" @click="previousPage">Previous</a>
                    </li>
                    <li class="page-item" :class="paginate == currentPage ? 'active' : ''" v-for="paginate in pages"
                        :key="paginate">
                        <a class="page-link" @click="nowPage(paginate)">{{paginate}}</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" :disabled="currentPage == pages[pages.length - 1]"
                            @click="nextPage">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
