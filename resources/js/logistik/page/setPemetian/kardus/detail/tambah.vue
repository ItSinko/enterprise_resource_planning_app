<script>
import modalSeri from '../../../../../produksiNew/page/prosesSet/proses/modalCreate/modalSeri.vue'
import generatePackingList from '../../../../../produksiNew/page/prosesSet/proses/modalCreate/generatePackingList.vue'
import axios from 'axios'
export default {
    components: {
        modalSeri,
        generatePackingList,
    },
    data() {
        return {
            noseri: [],
            isDisable: false,
            errorValue: '',
            isError: false,
            idGenerate: null,
            loading: false,
            hasilGenerate: null,
            noseriGeneratePackingList: null,
            idGenerate: null,
            detailSeri: false,
        }
    },
    methods: {
        generateNoSeri() {
            for (let i = 0; i < 1; i++) {
                this.noseri.push({
                    seri: '',
                })
            }
        },
        closeModal() {
            $('.modalGenerate').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
                this.$emit('refresh')
            })
        },
        cetakSeri() {
            window.open(`/produksiReworks/cetakseriReworkAllKardus?data=[${this.idGenerate}]`, '_blank')
        },
        lihatSeri() {
            this.detailSeri = true;
            $('.modalGenerate').modal('hide');
            this.$nextTick(() => {
                $('.modalSeri').modal('show');
            });
        },
        closeModalSeri() {
            this.detailSeri = false;
            this.$nextTick(() => {
                $('.modalGenerate').modal('show');
            });
        },
        async simpanSeri() {
            this.noseri = this.noseri.map((data) => {
                delete data.error
                return data
            })

            const cek = this.noseri.filter((data) => {
                return data.seri.trim() === ''
            })

            if (cek.length > 0) {
                this.$swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No. Seri tidak boleh kosong!',
                })
                return
            }

            if (!this.isDisable && cek, length === 0) {
                try {
                    this.isDisable = true
                    this.loading = true

                    const { data } = await axios.post(`/api/logistik/rw/pack/store/${this.$route.params.id}`, {
                        noseri: this.noseri
                    }, {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('lokal_token'),
                        }
                    })

                    const { id, itemnoseri } = data
                    this.idGenerate = id
                    this.noseriGeneratePackingList = itemnoseri
                    this.hasilGenerate = this.noseri[0].seri
                    this.$swal('Berhasil', 'Data berhasil disimpan', 'success')
                } catch (error) {
                    const { message, values } = error.response.data
                    this.$swal('Gagal', message, 'error')
                    this.errorValue = message
                    this.isError = true

                    if (error.response.data?.values) {
                        this.noseri = this.noseri.map((data) => {
                            // trim no seri
                            data.seri = data?.seri.trim();
                            const find = values.find((data2) => data2 == data?.seri);
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
        reset() {
            this.noseri = []
            this.generateNoSeri()
            this.hasilGenerate = null
            this.idGenerate = null
            this.noseriGeneratePackingList = null
            this.isDisable = false
            this.$nextTick(() => {
                setTimeout(() => {
                    this.$refs.noseri[0].focus();
                }, 500);
            });
        }
    },
    created() {
        this.generateNoSeri()
    },
    mounted() {
        this.$nextTick(() => {
            setTimeout(() => {
                this.$refs.noseri[0].focus();
            }, 500);
        });
    },
}
</script>
<template>
    <div>
        <modalSeri v-if="detailSeri" @closeModal="closeModalSeri" :hasilGenerate="hasilGenerate" />
        <div class="modal fade modalGenerate" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Scan Produk</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No. Seri</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, idx) in noseri" :key="idx">
                                            <td>
                                                <input type="text" class="form-control"
                                                    :class="data.error ? 'is-invalid' : ''" ref="noseri"
                                                    @keyup.enter="simpanSeri"
                                                    :disabled="isDisable" v-model="data.seri">
                                                <div class="invalid-feedback">
                                                    {{ errorValue }}
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col" v-if="hasilGenerate">
                                <div class="row">
                                    <div class="col">
                                        <label for="">Nomor Seri</label>
                                        <div class="card nomor-so">
                                            <div class="card-body">
                                                <span>{{ hasilGenerate }}</span>
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

                                        <label for="">Detail Produk</label>
                                        <generatePackingList :dataTable="noseriGeneratePackingList" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-grow-1 bd-highlight">
                                <button class="btn btn-success" :disabled="isDisable" @click="simpanSeri">
                                    {{ loading ? 'Loading...' : 'Simpan' }}
                                    <div class="spinner-border spinner-border-sm" role="status" v-if="loading">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </button>
                            </div>
                            <div class="p-2 bd-highlight">
                                <button class="btn btn-primary" @click="reset">
                                    Reset
                                </button>
                            </div>
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
}
</style>