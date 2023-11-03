<script>
import pagination from '../../../../components/pagination.vue';
export default {
    components: {
        pagination
    },
    props: ['dataModalDetailSeri'],
    data() {
        return {
            search: '',
            renderPaginate: []
        }
    },
    methods: {
        closeModal() {
            $('.modalDetailSeri').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            });
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
    },
    computed: {
        filteredDalamProses() {
            return this.dataModalDetailSeri.seri.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
    },
}
</script>
<template>
    <div class="modal fade modalDetailSeri" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Nomor Seri</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm">
                            <label for="">Nomor Seri</label>
                            <div class="card nomor-so">
                                <div class="card-body">
                                    <span id="so">
                                        {{ dataModalDetailSeri.noseri }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm">
                            <label for="">Tanggal Dibuat</label>
                            <div class="card nomor-akn">
                                <div class="card-body">
                                    <span id="akn">
                                        {{ dateFormat(dataModalDetailSeri.tgl_buat) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm">
                            <label for="">Packer</label>
                            <div class="card nomor-po">
                                <div class="card-body">
                                    <span id="po">
                                        {{ dataModalDetailSeri.packer ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" placeholder="Cari Produk" v-model="search">
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Nomor Seri</th>
                            </tr>
                        </thead>
                        <tbody v-if="renderPaginate.length > 0">
                            <tr v-for="(data, index) in renderPaginate" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ data.produk }} {{ data.varian }}</td>
                                <td>{{ data.noseri }}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data</td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination :filteredDalamProses="filteredDalamProses"
                        @updateFilteredDalamProses="updateFilteredDalamProses" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.nomor-so {
    background-color: #717FE1;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}

.nomor-akn {
    background-color: #DF7458;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}

.nomor-po {
    background-color: #85D296;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}
</style>