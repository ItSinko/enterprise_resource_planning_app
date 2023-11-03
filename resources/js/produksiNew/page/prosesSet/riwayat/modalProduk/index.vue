<script>
import modalDetail from '../../proses/modalDetail'
import DataTable from '../../../../components/DataTable.vue';
export default {
    props: ['dataSelected'],
    components: {
        modalDetail,
        DataTable,
    },
    data() {
        return {
            search: '',
            modalSeri: false,
            dataSeriSelected: null,
            dataModalDetail: null,
            showModalDetail: false,
            headers: [
                { text: 'No', value: 'no' },
                { text: 'Nomor Seri', value: 'noseri' },
                { text: 'Tanggal Dibuat', value: 'tgl_buat' },
                { text: 'Packer', value: 'packer' },
                { text: 'Aksi', value: 'aksi', sortable: false },
            ]
        }
    },
    methods: {
        closeModal() {
            $('.modalProduk').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        closeModalSeri() {
            this.modalSeri = false
            $('.modalProduk').modal('show')
        },
        detailSeri(data) {
            this.dataSeriSelected = JSON.parse(JSON.stringify(data))
            this.modalSeri = true
            $('.modalProduk').modal('hide')
            this.$nextTick(() => {
                $('.modalSeri').modal('show')
            })
        },
        detailProdukSeri(data) {
            this.dataModalDetail = JSON.parse(JSON.stringify(data))
            this.showModalDetail = true

            this.$nextTick(() => {
                $('.modalProduk').modal('hide')
                $('.modalDetailSeri').modal('show')
            })
        },
        closeModalDetail() {
            this.showModalDetail = false
            this.$nextTick(() => {
                $('.modalProduk').modal('show')
            })
        },
    },
}
</script>
<template>
    <div>
        <modalDetail v-if="showModalDetail" @closeModal="closeModalDetail" :dataModalDetailSeri="dataModalDetail" />
        <div class="modal fade modalProduk" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="">Nama Produk</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span id="so">{{ dataSelected.nama }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Jumlah Transfer</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="akn">{{ dataSelected.item.length }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Tanggal Transfer</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po">{{ dataSelected.tgl_tf }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <input type="text" v-model="search" class="form-control" placeholder="Cari...">
                                    </div>
                                </div>
                                <DataTable :items="dataSelected.item" :headers="headers" :search="search">
                                    <template #item.no = "{ item, index }">
                                        {{ index + 1 }}
                                    </template>
                                    <template #item.tgl_buat = "{ item }">
                                        {{ dateFormat(item.tgl_buat) }}
                                    </template>
                                    <template #item.aksi="{ item }">
                                        <button class="btn btn-sm btn-outline-info" @click="detailProdukSeri(item)">
                                            <i class="fa fa-info-circle"></i> Detail No. Seri Produk
                                        </button>
                                    </template>
                                </DataTable>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    </div>
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