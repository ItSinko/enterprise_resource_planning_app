<script>
import axios from 'axios';
import noseri from '../../permintaan/formPermintaan/noseri.vue';
import penggantiseri from './penggantiseri.vue';
import DataTable from '../../../../components/DataTable.vue';
export default {
    components: {
        DataTable,
        noseri,
        penggantiseri,
    },
    props: ['headerData'],
    data() {
        return {
            search: '',
            produkSelected: {},
            showSeri: false,
            dataTable: [],
            headers: [
                {
                    text: 'No.',
                    value: 'no',
                },
                {
                    text: 'Nama Produk',
                    value: 'nama',
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah',
                },
                {
                    text: 'Belum Transfer',
                    value: 'belum',
                },
                {
                    text: 'Jumlah No Seri Dipilih',
                    value: 'noseri',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ],
            showPenggantiSeri: false,
        }
    },
    methods: {
        closeModal() {
            $('.modalPenggantianSeri').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            });
        },
        closeModalSeri() {
            $('.modalSeri').modal('hide');
            this.showSeri = false;
            $('.modalPenggantianSeri').modal('show');
        },
        closeModalDetailSeri() {
            $('.modalDetailSeri').modal('hide');
            this.showDetailSeri = false;
            $('.modalPenggantianSeri').modal('show');
        },
        showDetailDiganti() {
            this.showPenggantiSeri = true;
            this.$nextTick(() => {
                $('.modalPenggantianSeri').modal('hide');
                $('.modalDetailSeri').modal('show');
            });
        },
        async getData() {
            try {
                const { data } = await axios.post('/api/gbj/rw/belum_kirim/produk', this.headerData);
                this.dataTable = data;
            } catch (e) {
                console.log(e);
            }
        },
        simpanSeri(produk) {
            let index = this.dataTable.findIndex((data) => data.nama === produk.nama);
            this.dataTable[index] = JSON.parse(JSON.stringify(produk));
            this.closeModalSeri();
            // make spacing on this.search
            this.search = "&nbsp;";
            setTimeout(() => {
                this.search = "";
            }, 1);
        },
        selectProduk(data) {
            this.produkSelected = data;
            this.showSeri = true;
            this.$nextTick(() => {
                $('.modalPenggantianSeri').modal('hide');
                $('.modalSeri').modal('show');
            });
        },
        transfer() {
            const dataKirim = [];

            this.dataTable.forEach((data) => {
                if (data.noseri) {
                    dataKirim.push(data);
                }
            });

            if (dataKirim.length === 0) {
                this.$swal('Peringatan', 'Tidak ada data yang dipilih', 'warning');
                return;
            }

            const success = () => {
                this.$swal("Berhasil", "Data berhasil ditransfer", "success");
                this.closeModal();
                this.$emit("refresh");
            };

            const error = () => {
                this.$swal("Gagal", "Data gagal ditransfer", "error");
            };

            success();
        }
    },
    created() {
        this.getData();
    },
}
</script>
<template>
    <div>
        <noseri :produk="produkSelected" v-if="showSeri" @closeModal="closeModalSeri" @simpan="simpanSeri" />
        <penggantiseri v-if="showPenggantiSeri" @closeModal="closeModalDetailSeri" />
        <div class="modal fade modalPenggantianSeri" data-backdrop="static" data-keyboard="false" tabindex="-1"
            role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Permintaan Penggantian No Seri</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row row-cols-2">
                                    <div class="col">
                                        <label for="">Nama Produk</label>
                                        <div class="card nomor-po">
                                            <div class="card-body">
                                                <span id="po">{{ headerData.nama }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <label for="">Jumlah Kebutuhan</label>
                                        <div class="card instansi">
                                            <div class="card-body">
                                                <span id="instansi">{{ headerData.belum }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <input type="text" v-model="search" class="form-control" placeholder="Cari..." />
                                    </div>
                                </div>
                                <DataTable :items="dataTable" :headers="headers" :search="search">
                                    <template #item.no="{ item, index }">
                                        <div>
                                            {{ index + 1 }}
                                        </div>
                                    </template>
                                    <template #item.noseri="{ item }">
                                        <div>
                                            {{ item?.noseri ? item.noseri.length : 0 }}
                                        </div>
                                    </template>
                                    <template #item.aksi="{ item }">
                                        <button type="button" class="btn btn-primary btn-sm" @click="selectProduk(item)">
                                            <i class="fa fa-qrcode"></i>
                                            Nomor Seri
                                        </button>

                                        <button type="button" class="btn btn-info btn-sm" @click="showDetailDiganti">
                                            <i class="fa fa-info-circle"></i>
                                            Detail Nomor Seri yang harus diganti
                                        </button>

                                    </template>
                                </DataTable>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        <button type="button" class="btn btn-success" @click="transfer">Transfer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.nomor-so {
    background-color: #717fe1;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.nomor-akn {
    background-color: #df7458;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.nomor-po {
    background-color: #85d296;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.instansi {
    background-color: #36425e;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}
</style>