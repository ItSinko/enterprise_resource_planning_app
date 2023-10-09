<script>
import pagination from '../../../../components/pagination.vue';
export default {
    components: {
        pagination,
    },
    data() {
        return {
            search: '',
            dataTable: [
                {
                    nama: 'Lorem',
                    tanggal_lahir: '1999-01-01',
                    tempat_lahir: 'Lorem',
                    no_bpjs: '123456',
                },
                {
                    nama: 'Lorem',
                    tanggal_lahir: '1999-01-01',
                    tempat_lahir: 'Lorem',
                    no_bpjs: '123456',
                },
            ],
            renderPaginate: [],
        }
    },
    methods: {
        updatePage(page) {
            this.renderPaginate = page
        },
    },
    computed: {
        paginateData() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1
                })
            })
        }
    },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-11 col-md-12">
                    <div class="row d-flex justify-content-between">
                        <div class="p-2 cust">
                            <div class="margin">
                                <div><small class="text-muted">Nama Keluarga yang dapat dihubungi</small></div>
                            </div>
                            <div class="margin"><b id="distributor">
                                    Lorem
                                </b></div>
                            <div class="margin">
                                <div><small class="text-muted">Hubungan Keluarga yang dapat dihubungi</small></div>
                            </div>
                            <div class="margin"><b id="distributor">
                                    Lorem
                                </b></div>
                        </div>
                        <div class="p-2 cust">
                            <div class="margin">
                                <div>
                                    <small class="text-muted">Nomor Telepon Keluarga yang dapat dihubungi</small>
                                </div>
                            </div>
                            <div class="margin">
                                <b id="distributor">08xxx</b>
                            </div>
                            <div class="margin">
                                <div>
                                    <small class="text-muted">Status</small>
                                </div>
                            </div>
                            <div class="margin">
                                <b id="distributor">Menikah</b>
                            </div>
                        </div>
                        <div class="p-2 cust">
                            <div class="margin">
                                <div>
                                    <small class="text-muted">Jumlah Anak</small>
                                </div>
                            </div>
                            <div class="margin">
                                <b id="distributor">{{ dataTable.length }}</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-grow-1 bd-highlight">
                    <h5>Informasi Anak</h5>
                </div>
                <div class="p-2 bd-highlight">
                    <input type="text" v-model="search" class="form-control" placeholder="Cari...">
                </div>
            </div>
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>Nama Anak</th>
                        <th>Tanggal Lahir</th>
                        <th>Tempat Lahir</th>
                        <th>No BPJS</th>
                    </tr>
                </thead>
                <tbody v-if="renderPaginate.length > 0">
                    <tr v-for="(data, index) in renderPaginate" :key="index">
                        <td>{{ data.nama }}</td>
                        <td>{{ dateFormat(data.tanggal_lahir) }}</td>
                        <td>{{ data.tempat_lahir }}</td>
                        <td>{{ data.no_bpjs }}</td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="4">Data tidak ditemukan</td>
                    </tr>
                </tbody>
            </table>
            <pagination :DataTable="paginateData" @updatePage="updatePage" />
        </div>
    </div>
</template>
