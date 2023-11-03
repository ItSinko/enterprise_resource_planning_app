<script>
import noseri from './noseri.vue';
import DataTable from '../../../../components/DataTable.vue';
export default {
    props: ['dataSelected'],
    components: {
        noseri,
        DataTable
    },
    data() {
        return {
            search: '',
            modalSeri: false,
            dataSeriSelected: null,
            headers: [{
                text: 'No.',
                value: 'no'
            },
            {
                text: 'Nama Produk',
                value: 'kelompok'
            },
            {
                text: 'Jumlah',
                value: 'jumlah'
            },
            {
                text: 'Aksi',
                value: 'aksi'
            }
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
            this.$nextTick(() => {
                $('.modalProduk').modal('hide')
                $('.modalSeri').modal('show')
            })
        },
    },
}
</script>
<template>
    <div>
        <noseri v-if="modalSeri" :seriSelected="dataSeriSelected" @closeModal="closeModalSeri" />
        <div class="modal fade modalProduk" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
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
                                                <span id="so">{{ dataSelected.header.nama }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Jumlah Transfer</label>
                                        <div class="card nomor-akn">
                                            <div class="card-body">
                                                <span id="akn">{{ dataSelected.data.length }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Tanggal Transfer</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po">{{ dataSelected.header.tgl_tf }}</span>
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
                                <DataTable :headers="headers" :items="dataSelected.data" :search="search">
                                    <template #item.no="{ item, index }">
                                        <div>
                                            {{ index + 1 }}
                                        </div>
                                    </template>
                                    <template #item.namaproduk="{ item }">
                                        <span>{{ showNamaProduk(item) }}</span>
                                    </template>
                                    <template #item.aksi="{ item }">
                                        <div>
                                            <button class="btn btn-outline-primary btn-sm" @click="detailSeri(item)">
                                                <i class="fas fa-info-circle"></i>
                                                Detail
                                            </button>
                                        </div>
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