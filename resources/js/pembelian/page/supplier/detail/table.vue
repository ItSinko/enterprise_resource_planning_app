<script>
    import mix from '../mix'
    import moment from 'moment'
    export default {
        mixins: [mix],
        props: {
            dataTable: {
                type: Array,
                default: () => []
            }
        },
        data() {
            return {
                search: ''
            }
        },
        methods: {
            status(status){
                switch (status) {
                    case 'batal':
                        return `<span class="badge badge-danger">${status}</span>`
                        break;
                    case 'menunggu permintaan':
                        return `<span class="badge badge-warning">${status}</span>`
                        break;
                    case 'selesai':
                        return `
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <span class="text-bold">Selesai</span>
                        </div>
                        `
                        break;
                    default:
                        return `<span class="badge badge-primary">${status}</span>`
                        break;
                }
            },
            moment(date){
                return moment(date).lang('id').format('LL')
            }
        },
        computed: {
            filteredSuppliers() {
                const dataIsNotNull = (data) =>
                data !== null && data !== undefined && data !== "" ? data : "-";
                return this.dataTable.filter((data) => {
                    return (
                        dataIsNotNull(data.no_po)
                            .toString()
                            .toLowerCase()
                            .includes(this.search.toLowerCase()) ||
                        dataIsNotNull(data.divisi)
                            .toString()
                            .toLowerCase()
                            .includes(this.search.toLowerCase()) ||
                        dataIsNotNull(data.tanggal_diminta)
                            .toString()
                            .toLowerCase()
                            .includes(this.search.toLowerCase()) ||
                        dataIsNotNull(data.estimasi_kedatangan)
                            .toString()
                            .toLowerCase()
                            .includes(this.search.toLowerCase()) ||
                        dataIsNotNull(data.status)
                            .toString()
                            .toLowerCase()
                            .includes(this.search.toLowerCase())
                    )
                })
            }
        }
    }

</script>
<template>
    <div>
        <h5 class="text-bold">Riwayat Pembelian</h5>
        <div class="d-flex justify-content-end bd-highlight mb-3">
            <div class="p-2 bd-highlight">
                <label for="">Search</label>
                <input type="text" class="form-control" v-model="search">
            </div>
        </div>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No PO</th>
                    <th>Divisi</th>
                    <th>Tanggal Diminta</th>
                    <th>Estimasi Kedatangan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody v-if="renderPaginate.length > 0">
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.no_po }}</td>
                    <td>{{ data.divisi }}</td>
                    <td>{{ moment(data.tanggal_diminta) }}</td>
                    <td>{{ moment(data.estimasi_kedatangan) }}</td>
                    <td><div v-html="status(data.status)"></div></td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="6" class="text-center">Data tidak ditemukan</td>
                </tr>
            </tbody>
        </table>
                        <div class="d-flex flex-row-reverse bd-highlight">
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" :disabled="currentPage == 1" @click="previousPage">Previous</a>
                            </li>
                            <li class="page-item" :class="paginate == currentPage ? 'active' : ''"
                                v-for="paginate in pages" :key="paginate">
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
