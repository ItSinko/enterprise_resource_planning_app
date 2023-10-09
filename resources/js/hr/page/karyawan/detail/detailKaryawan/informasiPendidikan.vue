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
                    id: 1,
                    nama: 'SMA',
                    bidang: 'IPA'
                },
                {
                    id: 2,
                    nama: 'SMP',
                    bidang: 'IPA'
                },
                {
                    id: 3,
                    nama: 'SD',
                    bidang: '-'
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
                                <div><small class="text-muted">Sekolah</small></div>
                            </div>
                            <div class="margin"><b id="distributor">S1</b></div>
                        </div>
                        <div class="p-2 cust">
                            <div class="margin">
                                <div>
                                    <small class="text-muted">Bidang / Jurusan</small>
                                </div>
                            </div>
                            <div class="margin">
                                <b id="distributor">Teknik Informatika</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex bd-highlight">
                <div class="p-2 flex-grow-1 bd-highlight">
                    <h5>Informasi Sekolah Lanjutan</h5>
                </div>
                <div class="p-2 bd-highlight">
                    <input type="text" v-model="search" class="form-control" placeholder="Cari...">
                </div>
            </div>
            <table class="table text-center">
                <thead>
                    <tr>
                        <th>Sekolah</th>
                        <th>Bidang / Jurusan</th>
                    </tr>
                </thead>
                <tbody v-if="renderPaginate.length > 0">
                    <tr v-for="(data, index) in renderPaginate" :key="index">
                        <td>{{ data.nama }}</td>
                        <td>{{ data.bidang }}</td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="2">Data tidak ditemukan</td>
                    </tr>
                </tbody>
            </table>
            <pagination :DataTable="paginateData" @updatePage="updatePage" />
        </div>
    </div>
</template>
