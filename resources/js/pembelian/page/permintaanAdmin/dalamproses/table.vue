<script>
import mix from './mix'
export default {
    mixins: [mix],
    props: {
        dataTables: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            search: '',
            filterstatus: [],
        }
    },
    methods: {
        status(status, persentase){
            switch (status) {
                case 'menunggu diproses':
                    return '<span class="badge badge-warning">Menunggu Diproses</span>'
                case 'pembelian ditolak':
                    return '<span class="badge badge-danger">Pembelian Ditolak</span>'
                case 'selesai':
                    return `
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: ${persentase}%" aria-valuenow="${persentase}" aria-valuemin="0" aria-valuemax="100">${persentase}%</div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <span class="text-bold">Selesai</span>
                    </div>
                    `
                default:
                    return `<span class="badge badge-info">${status}</span>`
            }
        },
        clickFilterStatus(status) {
            if (this.filterstatus.includes(status)) {
                this.filterstatus = this.filterstatus.filter((data) => {
                    return data != status
                })
            } else {
                this.filterstatus.push(status)
            }
        },
        detail(id, jenis){
            this.$emit('detail', id, jenis)
        },
        tambah() {
            this.$router.push({
                name: 'permintaanTambahDalamProses',
                params: {
                    currentRoute: this.$route.name
                }
            })
        },
        terima(){
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Data yang sudah diterima tidak dapat diubah kembali!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Terima!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal(
                        'Terima!',
                        'Data berhasil diterima.',
                        'success'
                    )
                }
            })
        },
        tolak(){
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Data yang sudah ditolak tidak dapat diubah kembali!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Tolak!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal(
                        'Tolak!',
                        'Data berhasil ditolak.',
                        'success'
                    )
                }
            })
        },
    },
    computed: {
        filteredDatatables(){
            let filtered = []
            if (this.filterstatus.length == 0) {
                filtered = this.dataTables
            } else {
                this.filterstatus.forEach(filter => {
                    filtered = filtered.concat(this.dataTables.filter(data => data.status == filter))
                })
            }
            return filtered.filter((data) => {
                return data.no_pp.toLowerCase().includes(this.search.toLowerCase()) ||
                    data.jenis_barang.toLowerCase().includes(this.search.toLowerCase()) ||
                    data.pp.toLowerCase().includes(this.search.toLowerCase()) ||
                    data.status.toLowerCase().includes(this.search.toLowerCase())
            })
        },
        getAllStatusUnique() {
            return this.dataTables.map((data) => {
                return data.status
            }).filter((value, index, self) => {
                return self.indexOf(value) === index
            })
        },
    }
}   
</script>
<template>
    <div>
    <div class="d-flex bd-highlight mb-3">
            <div class="p-2 bd-highlight">
                <button class="btn btn-primary" @click="tambah">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    Tambah PP
                </button>
            </div>
            <div class="p-2 bd-highlight">
                                <span class="float-left filter">
                    <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="form-group">
                                    <label for="jenis_penjualan">Status</label>
                                </div>
                                <div class="form-group" v-for="status in getAllStatusUnique" :key="status">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" :ref="status" :value="status"
                                            id="status1" @click="clickFilterStatus(status)" />
                                        <label class="form-check-label" for="status1">
                                            {{ status }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </div>
            <div class="ml-auto p-2 bd-highlight">
                <div class="form-group row">
                    <label for="" class="col-3">Search</label>
                    <input type="text" class="form-control col" placeholder="Cari..." v-model="search">
                </div>
            </div>
        </div>
        <table class="table">
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
            <tbody v-if="filteredDatatables.length > 0">
                <tr v-for="(data, index) in filteredDatatables" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.no_pp }}</td>
                    <td>{{ data.jenis_barang }}</td>
                    <td>{{ data.tanggal_diminta }}</td>
                    <td>{{ data.tanggal_dibutuhkan }}</td>
                    <td><button class="btn btn-warning btn-sm">
                        <i class="fa fa-file" aria-hidden="true"></i>
                        </button></td>
                    <td>
                        <span v-html="status(data.status, data.persentase)" v-if="data.status !== 'minta persetujuan'"></span>
                        <span v-if="data.status === 'minta persetujuan'">
                            <button class="btn btn-success btn-sm" @click="terima">
                                Terima
                            </button>
                            <button class="btn btn-danger btn-sm" @click="tolak">
                                Tolak
                            </button>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-outline-primary btn-sm" @click="detail(data.id, data.jenis_barang)">
                            <i class="fa fa-eye"></i>
                        </button>
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
