<script>
import axios from 'axios';
import DataTable from '../../../components/DataTable.vue';
export default {
    props: ['dataGenerate'],
    components: {
        DataTable,
    },
    data() {
        return {
            showModalCetak: false,
            form: {
                kedatangan: 0,
                jml_noseri: 0,
                no_urut_terakhir: 0,
            },
            isError: false,
            seri: [],
            duplicate: [],
            available: [],
            searchPreview: '',
            searchDuplikasi: '',
            loading: false,
            headers: [
                {
                    text: 'No Seri',
                    value: 'seri',
                    align: 'text-left',
                }
            ],
            showNoUrutTerakhir: false,
            loadingNoUrut: false,
        }
    },
    methods: {
        keyUpperCase(e) {
            this.dataGenerate.no_bppb = e.target.value.toUpperCase()
        },
        closeModal() {
            $('.modalGenerate').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        async simpan() {
            const ceknotnull = Object.values(this.form).every(x => x !== null && x !== '' && x !== 0)
            const cekbppb = this.dataGenerate.no_bppb !== null && this.dataGenerate.no_bppb !== '' && this.dataGenerate.no_bppb !== '-'
            if (ceknotnull && cekbppb && this.jumlahRakit && this.validasiNoUrutTerakhir) {
                try {
                    this.loading = true
                    const kirim = {
                        ...this.dataGenerate,
                        ...this.form,
                    }

                    const { data } = await axios.post('/api/prd/fg/gen', kirim)
                    this.$swal('Berhasil', 'Berhasil generate seri', 'success')
                    this.$emit('refresh')
                    this.closeModal()
                } catch (error) {
                    const { message, seri, duplicate, available } = error.response.data
                    this.seri = seri
                    this.available = available
                    this.duplicate = duplicate.map(item => {
                        return {
                            seri: item,
                        }
                    })
                    if (this.seri.length > 0 || this.available > 0 || this.duplicate.length > 0) {
                        this.isError = true
                    } else {
                        this.isError = false
                    }
                    this.$swal('Gagal', message, 'error')
                } finally {
                    this.loading = false
                }

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Silahkan cek kembali form anda',
                })
                return
            }
        },
        async simpanSeri() {
            try {
                const kirim = {
                    seri: this.seri,
                    available: this.available,
                }
                const { data } = await axios.post('/api/prd/fg/gen/confirm', kirim)
                this.$swal('Berhasil', 'Berhasil menyimpan seri', 'success')
                this.$emit('refresh')
                this.closeModal()
            } catch (error) {
                console.log(error)
            }
        },
        closeModalCetak() {
            this.showModalCetak = false
            this.$nextTick(() => {
                $('.modalGenerate').modal('show')
            })
        },
        async checkNoUrut() {
            try {
                this.loadingNoUrut = true
                const { data } = await axios.get(`/api/prd/ongoing/${this.dataGenerate.id}`)
                if (data != 0) {
                    this.showNoUrutTerakhir = true
                    this.form.no_urut_terakhir = data
                } else {
                    this.showNoUrutTerakhir = false
                }
            } catch (error) {
                console.log(error)
            } finally {
                this.form.kedatangan = 1
                this.loadingNoUrut = false
            }
        }
    },
    computed: {
        jumlahRakit() {
            return this.dataGenerate.kurang >= this.form.jml_noseri ? true : false
        },
        validasiNoUrutTerakhir() {
            return this.form.no_urut_terakhir < 100000 ? true : false
        }
    },
    watch: {
        'form.kedatangan': function (val) {
            if (val > 26) {
                this.form.kedatangan = 26
            } else if (val <= 1) {
                this.form.kedatangan = 1
            } else {
                this.form.kedatangan = val
            }
        },
    },
    created() {
        this.checkNoUrut()
    }
}
</script>
<template>
    <div>
        <modalPilihan v-if="showModalCetak" @closeModal="closeModalCetak"></modalPilihan>
        <div class="modal fade modalGenerate" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1"
            role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Generate Seri</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card" v-if="!loadingNoUrut">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="">Nomor BPPB</label>

                                        <div class="card">
                                            <div class="card-body">
                                                <input type="text" name="no_bppb" id="no_bppb" class="form-control"
                                                    v-model="dataGenerate.no_bppb" :disabled="loading"
                                                    @keyup="keyUpperCase($event)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nama Produk</label>
                                        <div class="card" style="background-color: #F89F81">
                                            <div class="card-body">
                                                <span id="produk">{{ dataGenerate.nama_produk }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Kategori</label>
                                        <div class="card" style="background-color: #FCF9C4">
                                            <div class="card-body">
                                                <span id="kategori">{{ dataGenerate.kategori }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="">Jumlah Rakit</label>
                                        <div class="card" style="background-color: #FFCC83">
                                            <div class="card-body">
                                                <span id="jml">{{ dataGenerate.kurang_rakit }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Tanggal Mulai</label>
                                        <div class="card" style="background-color: #FFE0B4">
                                            <div class="card-body">
                                                <span id="start">{{ dataGenerate.tgl_mulai }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Tanggal Selesai</label>
                                        <div class="card" style="background-color: #FFECB2">
                                            <div class="card-body">
                                                <span id="end">{{ dataGenerate.tgl_selesai }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" v-if="!isError">
                                <form>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Kedatangan</label>
                                        <input type="number" class="form-control" v-model.number="form.kedatangan"
                                            @keypress="numberOnly($event)">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Jumlah Noseri yang dibuat</label>
                                        <input type="number" class="form-control"
                                            :class="jumlahRakit ? 'is-valid' : 'is-invalid'" @keypress="numberOnly($event)"
                                            v-model.number="form.jml_noseri">
                                        <div class="invalid-feedback" v-if="!jumlahRakit">
                                            Jumlah Noseri yang dibuat tidak boleh lebih dari jumlah rakit
                                        </div>
                                    </div>
                                    <div class="form-group" v-if="!showNoUrutTerakhir">
                                        <label for="exampleInputPassword1">No Urut Terakhir</label>
                                        <input type="number" class="form-control"
                                            :class="validasiNoUrutTerakhir ? 'is-valid' : 'is-invalid'"
                                            @keypress="numberOnly($event)" v-model.number="form.no_urut_terakhir">
                                        <div class="invalid-feedback" v-if="!validasiNoUrutTerakhir">
                                            Jumlah Noseri yang dibuat tidak boleh lebih dari jumlah rakit
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body" v-else>
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="pills-preview-tab" data-toggle="pill"
                                            data-target="#pills-preview" type="button" role="tab"
                                            aria-controls="pills-preview" aria-selected="true">Preview Generate No Seri</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-duplikasi-tab" data-toggle="pill"
                                            data-target="#pills-duplikasi" type="button" role="tab"
                                            aria-controls="pills-duplikasi" aria-selected="false">Duplikasi No Seri</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-preview" role="tabpanel"
                                        aria-labelledby="pills-preview-tab">
                                        <div class="d-flex flex-row-reverse bd-highlight">
                                            <div class="p-2 bd-highlight">
                                                <input type="text" v-model="searchPreview" class="form-control"
                                                    placeholder="Cari...">
                                            </div>
                                        </div>
                                        <DataTable :headers="headers" :items="seri" :search="searchPreview" />
                                    </div>
                                    <div class="tab-pane fade" id="pills-duplikasi" role="tabpanel"
                                        aria-labelledby="pills-duplikasi-tab">
                                        <div class="d-flex flex-row-reverse bd-highlight">
                                            <div class="p-2 bd-highlight">
                                                <input type="text" v-model="searchDuplikasi" class="form-control"
                                                    placeholder="Cari...">
                                            </div>
                                        </div>
                                        <DataTable :headers="headers" :items="duplicate" :search="searchDuplikasi" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="spinner-border" role="status" v-else>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        <button type="button" class="btn btn-success" v-if="!isError" :disabled="loading"
                            @click="simpan">Generate</button>
                        <button type="button" class="btn btn-success" v-if="seri.length > 0"
                            @click="simpanSeri">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>