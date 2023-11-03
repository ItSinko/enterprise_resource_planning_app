<script>
import axios from 'axios';
import Seriviatext from './seriviatext.vue';
import modalDetail from '../../riwayat/modalProduk/noseri.vue'
import pagination from '../../../../components/pagination.vue';
export default {
    components: {
        Seriviatext,
        modalDetail,
        pagination,
    },
    props: ['dataTable', 'headerTransfer'],
    data() {
        return {
            renderPaginate: [],
            search: '',
            checkAll: false,
            showmodalviatext: false,
            isScan: false,
            noSeriSelected: [],
            showModalDetail: false,
            dataModalDetail: null,
            layout: [
                {
                    id: 7,
                    label: 'Blok B'
                },
                {
                    id: 5,
                    label: 'Blok D'
                },
                {
                    id: 6,
                    label: 'E10'
                },
                {
                    id: 1,
                    label: 'E7'
                },
            ]
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        closeModal() {
            $('.modalTransfer').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            });
        },
        selectNoSeri(noseri) {
            if (this.noSeriSelected.find((data) => data === noseri)) {
                this.noSeriSelected = this.noSeriSelected.filter((data) => data !== noseri)
                this.checkAll = false
            } else {
                this.noSeriSelected.push(noseri)
            }

            if (this.noSeriSelected.length === this.dataTable.length) {
                this.checkAll = true
            }
        },
        checkAllSeri() {
            this.checkAll = !this.checkAll
            if (this.checkAll) {
                this.noSeriSelected = this.dataTable.map((data) => data)
            } else {
                this.noSeriSelected = []
            }
        },
        submit(noseri) {
            let noserinotfound = []

            let noseriarray = noseri.split(/[\n, \t]/)

            // remove empty string
            noseriarray = noseriarray.filter((data) => data !== "")

            // remove duplicate
            noseriarray = [...new Set(noseriarray)]

            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.dataTable.length; j++) {
                    if (noseriarray[i] === this.dataTable[j].noseri) {
                        if (!this.noSeriSelected.find((data) => data.noseri === noseriarray[i])) {
                            this.noSeriSelected.push(this.dataTable[j])
                        }
                        found = true
                        break
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }

            if (this.noSeriSelected.length == this.dataTable.length) {
                this.checkAll = true
            } else {
                this.checkAll = false
            }

            noserinotfound = [...new Set(noserinotfound)]

            if (noserinotfound.length > 0 && noserinotfound != "") {
                this.$swal('Peringatan', `Nomor Seri ${noserinotfound.join(', ')} tidx1ak ditemukan`, 'warning')
            }
        },
        autoSelect() {
            if (this.isScan) {
                let noserinotfound = []

                let noseriarray = this.search.split(/[\n, \t]/)
                for (let i = 0; i < noseriarray.length; i++) {
                    let found = false
                    for (let j = 0; j < this.dataTable.length; j++) {
                        if (noseriarray[i] === this.dataTable[j].noseri) {
                            if (!this.noSeriSelected.find((data) => data.noseri === noseriarray[i])) {
                                this.noSeriSelected.push(this.dataTable[j])
                            }
                            found = true
                            break
                        }
                    }

                    if (!found) {
                        noserinotfound.push(noseriarray[i])
                    }
                }

                this.search = ""

                if (this.noSeriSelected.length == this.dataTable.length) {
                    this.checkAll = true
                } else {
                    this.checkAll = false
                }

                if (noserinotfound.length > 0 && noserinotfound != "") {
                    this.$swal('Peringatan', `Nomor Seri ${noserinotfound.join(', ')} tidak ditemukan`, 'warning')
                }
            }
        },
        showSeriText() {
            this.showmodalviatext = true
            $('.modalTransfer').modal('hide')
            this.$nextTick(() => {
                $('.modalChecked').modal('show')
            })
        },
        closeModalSeriviatext() {
            this.showmodalviatext = false
            this.$nextTick(() => {
                $('.modalTransfer').modal('show')
            })
        },
        detailProdukSeri(data) {
            this.dataModalDetail = JSON.parse(JSON.stringify(data))
            this.showModalDetail = true

            this.$nextTick(() => {
                $('.modalTransfer').modal('hide')
                $('.modalDetailSeri').modal('show')
            })
        },
        closeModalDetail() {
            this.showModalDetail = false
            this.$nextTick(() => {
                $('.modalTransfer').modal('show')
            })
        },
        lihatPackingList(id) {
            window.open(`/produksiReworks/viewpackinglist/${id}`, '_blank');
        },
        cetakPackingList(id) {
            window.open(`/produksiReworks/cetakpackinglist?data=[${id}]`, '_blank');
        },
        async simpan() {
            try {
                const kirim = {
                    ...this.headerTransfer,
                    item: this.dataTable,
                }

                await axios.post('/api/gbj/rw/terima', kirim, {
                    headers: {
                        'Authorization': `Bearer` + localStorage.getItem('lokal_token'),

                    }
                })
                this.$swal({
                    title: 'Berhasil!',
                    text: 'Data berhasil disimpan',
                    icon: 'success',
                })
                this.$emit('refresh')
                this.closeModal()
            } catch (error) {
                console.log(error);
                this.$swal({
                    title: 'Gagal!',
                    text: 'Data gagal disimpan',
                    icon: 'error',
                })
            }
        },
        scanSeri() {
            this.isScan = !this.isScan
            this.search = ""
            this.$nextTick(() => {
                this.$refs.search.focus()
            })
        },
        changeLayout(event) {
            console.log(event);
            // change all layout noseriSelected
            this.noSeriSelected.forEach((data) => {
                data.layout = event
            })
            this.search = "t"
            this.search = ""
        },
    },
    computed: {
        filteredDalamProses() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
    },
}
</script>
<template>
    <div>
        <seriviatext v-if="showmodalviatext" @close="closeModalSeriviatext" @submit="submit" />
        <modalDetail v-if="showModalDetail" @closeModal="closeModalDetail" :dataModalDetailSeri="dataModalDetail" />
        <div class="modal fade modalTransfer" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
            role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Transfer Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-grow-1 bd-highlight"><button class="btn btn-primary"
                                    @click="showSeriText">Pilih No Seri Via
                                    Text</button>
                                <br>
                                <div class="custom-control custom-switch my-3">
                                    <input type="checkbox" class="custom-control-input" id="customSwitch1" :checked="isScan"
                                        @click="scanSeri">
                                    <label class="custom-control-label" for="customSwitch1">Scan Nomor Seri
                                    </label>
                                </div>
                            </div>
                            <div class="p-2 bd-highlight">
                                <input type="text" class="form-control mb-1" v-model="search" placeholder="Cari No Seri"
                                    ref="search" @keyup.enter="autoSelect" />
                                <v-select :options="layout" v-if="noSeriSelected.length > 0" @input="changeLayout($event)" placeholder="Ubah Layout Dipilih"></v-select>
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" :checked="checkAll" @click="checkAllSeri"></th>
                                    <th>Nomor Seri</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Packer</th>
                                    <th style="width: 20%;">Layout</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody v-if="renderPaginate.length > 0">
                                <tr v-for="(data, idx) in renderPaginate" :key="idx">
                                    <td><input type="checkbox"
                                            :checked="noSeriSelected && noSeriSelected.find((noseri) => noseri === data)"
                                            @click="selectNoSeri(data)"></td>
                                    <td>{{ data.noseri }}</td>
                                    <td>{{ dateFormat(data.tgl_buat) }}</td>
                                    <td>{{ data.packer ?? '-' }}</td>
                                    <td>
                                        <v-select :options="layout" v-model="data.layout"
                                            placeholder="Pilih Layout"></v-select>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info" @click="detailProdukSeri(data)">
                                            <i class="fa fa-info-circle"></i> Detail No. Seri Produk
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" @click="lihatPackingList(data.id)"><i
                                                class="fa fa-eye"></i> Lihat
                                            Packing List
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" @click="cetakPackingList(data.id)"><i
                                                class="fa fa-print"></i>
                                            Cetak Packing List
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data</td>
                                </tr>
                            </tbody>
                        </table>
                        <pagination :filteredDalamProses="filteredDalamProses"
                            @updateFilteredDalamProses="updateFilteredDalamProses" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
