<script>
    export default {
        props: {
            dataTable: {
                type: Array,
                default: () => []
            },
            headers: {
                type: Object,
                default: () => {}
            },
        },
        data() {
            return {
                headersDetail: null,
                detailParts: [],
                loading: false,
            }
        },
        methods: {
            detailPart(index) {
                this.loading = false
                this.detailParts = []
                const headers = {
                    namaProduk: this.headers,
                    nama_packing_list: this.dataTable[index].nama_packing_list,
                    jumlah_pemesanan: this.dataTable[index].jumlah_pemesanan,
                    index: index
                }
                this.headersDetail = headers
                this.dataTable[index].part.forEach((item) => {
                    this.detailParts.push(item)
                })
                this.loading = true

                setTimeout(() => {
                    $('.modalDetailPart').modal('show')
                }, 100);
            },

            addNew() {
                this.detailParts.push({
                    nama_part: {
                        value: null,
                        label: null
                    }
                })
            },
            removePart(index){
                this.detailParts.splice(index, 1)
            },
            close(){
                $('.modalDetailPart').modal('hide')
            },
            simpanDraft(){
                this.dataTable[this.headersDetail.index].part = this.detailParts
                this.close()
            }
        },
    }

</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-bold">Klasifikasi Part</h4>
            <div class="card-text mt-5">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Packing List</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dataTable" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ item.nama_packing_list }}</td>
                            <td>{{ item.jumlah_pemesanan }}</td>
                            <td>
                                <i class="fas fa-edit text-warning" @click="detailPart(index)"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- <detail-klasifikasi v-if="loading" :dataTable="detailParts" :headers="headersDetail" @addNew="addNew" @removeRow="removeRow"/> -->
                <div class="modal fade modalDetailPart" v-if="loading" id="modelId" tabindex="-1" role="dialog"
                    aria-labelledby="modelTitleId" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-bold">Klasifikasi Part</h5>
                                <button type="button" class="close" @click="close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title text-bold">Detail Packing List</h4>
                                        <div class="card-text">
                                            <div class="container mt-5 text-left">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="row">
                                                            <div class="col">Nama Produk</div>
                                                            <div class="col">
                                                                <p>{{ headersDetail.namaProduk }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="row">
                                                            <div class="col">Nama Packing List</div>
                                                            <div class="col">
                                                                <p>{{ headersDetail.nama_packing_list }}</p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="row">
                                                            <div class="col">Jumlah Pemesanan</div>
                                                            <div class="col">
                                                                <p>{{ headersDetail.jumlah_pemesanan }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn-primary"  @click="addNew">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                    Tambah Part
                                                </button>
                                            </div>
                                            <table class="table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Part</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(data, index) in detailParts" :key="index+'detail'"
                                                        :class="`${index}Table`">
                                                        <td>{{ index + 1 }}</td>
                                                        <td>
                                                            <v-select v-model="data.nama_part"></v-select>
                                                        </td>
                                                        <td>
                                                            <i class="fa fa-minus red" aria-hidden="true" @click="removePart(index)"></i>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="close">Batal</button>
                                <button type="button" class="btn btn-warning" @click="simpanDraft">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                    Tambahkan ke Draft
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
