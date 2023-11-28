<script>
import modalSeri from './modalSeri.vue'
import generatePackingList from './generatePackingList.vue';
import axios from 'axios';
export default {
    components: {
        modalSeri,
        generatePackingList
    },
    props: ['selectSeri'],
    data() {
        return {
            noseri: [],
            hasilGenerate: null,
            isDisable: false,
            detailSeri: false,
            noseriGeneratePackingList: [],
            isError: false,
            errorValue: '',
            idGenerate: null,
            loading: false,
        }
    },
    methods: {
        closeModal() {
            this.$emit('closeModal');
            this.$emit('refresh')
        },
        generateNoSeri() {
            if (!this.selectSeri?.id) {
                for (let i = 0; i < this.$store.state.setSeri.set; i++) {
                    this.noseri.push({
                        seri: '',
                    })
                }
            } else {
                // get noseri from array selectSeri.seri and mapping to noseri
                this.noseri = this.selectSeri.seri.map((data) => {
                    return {
                        seri: data.noseri
                    }
                })
            }
        },
        autoTab(e, idx) {
            // jika ada object key error true, maka akan di hapus
            if (this.noseri[idx].error) {
                delete this.noseri[idx].error
            }

            // jika tidak ada object key error, maka isError false
            if (!this.noseri.find((data) => data.error)) {
                this.isError = false
            }

            if (e.target.value) {
                // jika value nya ada 13 digit, maka akan otomatis ke inputan selanjutnya
                // if (e.target.value.length === 13) {
                const cek = this.noseri.filter((data) => {
                    return data.seri.trim() === '';
                });

                if (!this.isError && !this.selectSeri?.id && cek.length === 0) {
                    this.generateSeri();
                }
                if (idx < this.noseri.length - 1) {

                    this.$refs.noseri[idx + 1].focus();

                }
                // }
            }
        },
        async generateSeri() {
            // hapus semua object key error
            this.noseri = this.noseri.map((data) => {
                delete data.error
                return data
            })
            const cek = this.noseri.filter((data) => {
                return data.seri.trim() === '';
            });

            const noSeriUnique = this.noseri.filter((data, idx) => {
                return this.noseri.findIndex((data2) => data2.seri === data.seri) === idx;
            });

            if (noSeriUnique.length !== this.noseri.length) {
                this.isError = true
                this.errorValue = 'tidak boleh sama'
                this.noseri = this.noseri.map((data) => {
                    if (this.noseri.findIndex((data2) => data2.seri === data.seri) !== this.noseri.lastIndexOf(data)) {
                        data.error = true
                    }
                    return data
                })
                this.$swal({
                    title: 'Gagal!',
                    text: 'No. Seri tidak boleh sama',
                    icon: 'error',
                })
                return;
            }

            // jika ada nomor seri yang kosong atau null muncul error
            if (cek.length > 0) {
                this.$swal({
                    title: 'Gagal!',
                    text: 'No. Seri tidak boleh kosong',
                    icon: 'error',
                })
                return;
            }


            if (!this.isDisable && cek.length === 0 && noSeriUnique.length === this.noseri.length) {
                try {
                    this.isDisable = true;
                    this.loading = true;
                    const { data } = await axios.post('/api/prd/rw/gen', {
                        ...this.$store.state.setSeri,
                        noseri: this.noseri
                    }, {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('lokal_token'),
                        }
                    })

                    const { id, noseri, itemnoseri } = data
                    this.hasilGenerate = noseri
                    this.idGenerate = id
                    this.noseriGeneratePackingList = itemnoseri
                    this.$swal('Berhasil', 'Berhasil generate no seri', 'success')
                } catch (error) {
                    const { message, values } = error.response.data
                    this.$swal('Gagal', `${message}`, 'error')
                    this.errorValue = message
                    this.isError = true

                    if (error.response.data?.values) {
                        console.log('masuk', values);
                        // tambahkan object key error true pada noseri yang gagal
                        this.noseri = this.noseri.map((data) => {
                            // trim no seri
                            data.seri = data?.seri.trim();
                            const find = values.find((data2) => data2 == data?.seri);
                            console.log('find', find);
                            if (find) {
                                return {
                                    ...data,
                                    error: true
                                };
                            }
                            return data;
                        });
                    }

                    this.isDisable = false
                } finally {
                    this.loading = false;
                }
            }

        },
        async updateSeri() {
            // hapus semua object key error
            this.noseri = this.noseri.map((data) => {
                delete data.error
                return data
            })
            const cek = this.noseri.filter((data) => {
                return data.seri.trim() === '';
            });

            const noSeriUnique = this.noseri.filter((data, idx) => {
                return this.noseri.findIndex((data2) => data2.seri === data.seri) === idx;
            });

            if (noSeriUnique.length !== this.noseri.length) {
                this.$swal({
                    title: 'Gagal!',
                    text: 'No. Seri tidak boleh sama',
                    icon: 'error',
                })
                return;
            }

            // jika ada nomor seri yang kosong atau null muncul error
            if (cek.length > 0) {
                this.$swal({
                    title: 'Gagal!',
                    text: 'No. Seri tidak boleh kosong',
                    icon: 'error',
                })
                return;
            }


            if (!this.isDisable && cek.length === 0 && noSeriUnique.length === this.noseri.length) {
                try {
                    this.isDisable = true;
                    this.loading = true;
                    const { data } = await axios.put(`/api/prd/rw/gen/${this.selectSeri.id}`, {
                        noseri: this.noseri
                    }, {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('lokal_token'),
                        }
                    })

                    const { noseri, itemnoseri } = data
                    this.hasilGenerate = noseri
                    this.noseriGeneratePackingList = itemnoseri
                    this.$swal('Berhasil', 'Berhasil update no seri', 'success')
                } catch (error) {
                    const { message, values } = error.response.data
                    this.$swal('Gagal', `${message}`, 'error')
                    this.errorValue = message
                    this.isError = true

                    if (error.response.data?.values) {
                        console.log('masuk', values);
                        // tambahkan object key error true pada noseri yang gagal
                        this.noseri = this.noseri.map((data) => {
                            // trim no seri
                            data.seri = data?.seri.trim();
                            const find = values.find((data2) => data2 == data?.seri);
                            console.log('find', find);
                            if (find) {
                                return {
                                    ...data,
                                    error: true
                                };
                            }
                            return data
                        });
                    }

                    this.isDisable = false
                } finally {
                    this.loading = false;
                }
            }
        },
        resetModal() {
            this.noseri = [];
            this.generateNoSeri();
            this.hasilGenerate = null;
            this.isDisable = false;
            this.isError = false;
            this.$nextTick(() => {
                setTimeout(() => {
                    this.$refs.noseri[0].focus();
                }, 200);
            });
        },
        lihatSeri() {
            this.detailSeri = true;
            $('.modalSet').modal('hide');
            this.$nextTick(() => {
                $('.modalSeri').modal('show');
            });
        },
        closeModalSeri() {
            this.detailSeri = false;
            this.$nextTick(() => {
                $('.modalSet').modal('show');
            });
        },
        cetakSeri() {
            // open new tab
            let id = this.selectSeri?.id ? this.selectSeri.id : this.idGenerate;
            window.open(`/produksiReworks/cetakseriReworkAll?data=[${id}]`, '_blank');
        },
        viewPackingList() {
            let id = this.selectSeri?.id ? this.selectSeri.id : this.idGenerate;
            window.open(`/produksiReworks/viewpackinglist/${id}`, '_blank');
        },
        cetakPackingList() {
            let id = this.selectSeri?.id ? this.selectSeri.id : this.idGenerate;
            window.open(`/produksiReworks/cetakpackinglist?data=[${id}]`, '_blank');
        },
        mappingEdit() {
            if (this.selectSeri?.id) {
                this.hasilGenerate = this.selectSeri.noseri
                this.noseriGeneratePackingList = this.selectSeri.seri
            }
        },
    },
    mounted() {
        this.generateNoSeri();
        this.mappingEdit();
        this.$nextTick(() => {
            setTimeout(() => {
                this.$refs.noseri[0].focus();
            }, 200);
        });
    },
}
</script>
<template>
    <div>
        <modalSeri v-if="detailSeri" @closeModal="closeModalSeri" :hasilGenerate="hasilGenerate" />
        <div class="modal fade modalSet" data-backdrop="static" id="modelId" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Scan Produk Rework</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col">
                                <form>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No. Seri</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(data, idx) in noseri" :key="idx">
                                                <td>
                                                    <input type="text" class="form-control" v-model="data.seri"
                                                        :class="data.error ? 'is-invalid' : ''"
                                                        @keyup.enter="autoTab($event, idx)" ref="noseri"
                                                        :disabled="isDisable">
                                                    <div class="invalid-feedback">
                                                        Nomor Seri {{ errorValue }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <div class="col" v-if="hasilGenerate">
                                <div class="row">
                                    <div class="col">
                                        <label for="">Nomor Seri</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span id="so">{{ hasilGenerate }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex bd-highlight">
                                            <div class="p-2 flex-grow-1 bd-highlight">
                                                <button class="btn btn-sm btn-outline-primary" @click="cetakSeri">
                                                    Cetak No. Seri <i class="fa fa-print"></i>
                                                </button>
                                            </div>
                                            <div class="p-2 bd-highlight">
                                                <!-- bentuk modal untuk view nya -->
                                                <button class="btn btn-sm btn-outline-info" @click="lihatSeri">
                                                    View No. Seri <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- dokumen -->
                                        <label for="">Dokumen Packing List</label>
                                        <generatePackingList :dataTable="noseriGeneratePackingList" />

                                        <div class="d-flex bd-highlight">
                                            <div class="p-2 flex-grow-1 bd-highlight">
                                                <button class="btn btn-sm btn-outline-primary" @click="cetakPackingList">
                                                    Cetak Packing List <i class="fa fa-print"></i>
                                                </button>
                                            </div>
                                            <div class="p-2 bd-highlight">
                                                <!-- bentuk pdf untuk view nya -->
                                                <button class="btn btn-sm btn-outline-info" @click="viewPackingList">
                                                    Lihat Packing List <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex bd-highlight mb-3 mx-3">
                        <div class="mr-auto p-2 bd-highlight">
                            <button class="btn btn-success" :disabled="isDisable"
                                @click="selectSeri?.id ? updateSeri() : generateSeri()">
                                {{ loading ? 'Loading...' : (selectSeri?.id ? 'Simpan' : 'Generate') }}
                                <div class="spinner-border spinner-border-sm" role="status" v-if="loading">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>
                        </div>
                        <div class="p-2 bd-highlight ml-auto">
                            <button type="button" class="btn btn-primary" @click="resetModal"
                                v-if="!selectSeri?.id">Reset</button>
                        </div>
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

.instansi {
    background-color: #36425E;
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px
}
</style>
