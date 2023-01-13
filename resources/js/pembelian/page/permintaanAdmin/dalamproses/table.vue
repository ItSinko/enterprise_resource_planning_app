<script>
export default {
    props: {
        dataTables: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            search: '',
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
        detail(id){
            this.$emit('detail', id)
        },
        tambah() {
            this.$router.push('/pembelian/permintaan/dalamproses/create')
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
                <button class="btn btn-outline-info">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                    Filter
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                </button>
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
            <tbody>
                <tr v-for="(data, index) in dataTables" :key="index">
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
                            <button class="btn btn-success btn-sm">
                                Terima
                            </button>
                            <button class="btn btn-danger btn-sm">
                                Tolak
                            </button>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-outline-primary btn-sm" @click="detail(data.id)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>