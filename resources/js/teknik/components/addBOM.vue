<script>
import axios from "axios";
    export default {
        props: {
            titleModalBOM: null,
            formBom: {
                type: Object,
                default: () => {}
            },
            partBOM: null,
            produk_id: {
                type: String,
                default: null
            },
        },
        data() {
            return {
            parts: [],
                // Pagination Parts BOM
            search: '',
            offset: 0,
            limit: 10,
            // Pagination Products BOM
            products: [],
            search_produk: '',
            offset_produk: 0,
            limit_produk: 10,
            }
        },
        created() {
            this.init();
        },
        methods: {
            async init(){
                this.$store.dispatch('setLoading', true)
                await axios.get('/api/part/data').then(res => {
                        res.data.data.forEach(element => {
                            this.parts.push({
                                value: element.id,
                                label: element.nama,
                                satuan: element.satuan_id
                            })
                        })
                    })

                if(this.produk_id == null){
                    await axios.get('/api/produk/teknik/data').then(response => {
                    response.data.data.forEach(element => {
                            this.products.push({
                                value: element.id,
                                label: element.nama,
                            })
                        });
                    });
                }
                this.$store.dispatch('setLoading', false)

            },
            async simpanBOM() {
                try {
                    let data = {
                        produk_id: this.produk_id == null ? this.formBom.produk_selected.value : this.produk_id,
                        kode: this.formBom.kode,
                        nama: this.formBom.nama,
                        tanggal: this.formBom.tanggal,
                        status: this.formBom.status,
                        part: this.partBOM
                    }
                    this.$store.dispatch('setLoading', true)
                    if (this.titleModalBOM == 'Tambah BOM') {
                        await axios.post('/api/bom/store', data).then(res => {
                            this.$swal('Berhasil', 'Berhasil menambahkan BOM', 'success')
                            $('.modalAddBOM').modal('hide')
                            this.init()
                        })
                    } else {
                        let id = this.formBom.id
                        await axios.post('/api/bom/update/' + id, data).then(res => {
                            this.$swal('Berhasil', 'Berhasil mengubah BOM', 'success')
                            $('.modalAddBOM').modal('hide')
                            this.init()
                        })
                    }
                    window.location.reload()
                    this.$store.dispatch('setLoading', false)
                } catch (error) {
                    console.log(error)
                }
            },
            isNumber(evt) {
                evt = evt ? evt : window.event;
                var charCode = evt.which ? evt.which : evt.keyCode;
                if (
                    charCode > 31 &&
                    (charCode < 48 || charCode > 57) &&
                    charCode !== 46
                ) {
                    evt.preventDefault();
                } else {
                    return true;
                }
            },
            addPartBOM() {
                this.partBOM.push({
                    namaPart: null,
                    jumlah: null,
                })
            },

            deletePartBOM(index) {
                if (this.partBOM.length > 1) {
                    this.partBOM.splice(index, 1)
                }
            },

            onSearch(query) {
                this.search = query
                this.offset = 0
            },

            onSearchProduk(query) {
                this.search_produk = query
                this.offset_produk = 0
            },
        },
        computed: {
            filtered() {
                if(!this.$store.state.loading){
                    return this.parts.filter(part => {
                        let string = part.label.toLowerCase()
                        // return string.includes(this.search.toLowerCase())
                        return string
                    })
                }
            },
            paginated() {
                if(!this.$store.state.loading){
                    return this.filtered.slice(this.offset, this.limit + this.offset)
                }
            },
            hasNextPage() {
                if(!this.$store.state.loading){
                    const nextOffset = this.offset + this.limit
                return Boolean(
                    this.filtered.slice(nextOffset, this.limit + nextOffset).length
                )
                }
            },
            hasPrevPage() {
                if(!this.$store.state.loading){
                    const prevOffset = this.offset - this.limit
                return Boolean(
                    this.filtered.slice(prevOffset, this.limit + prevOffset).length
                )
                }
            },

            filteredProduk() {
                return this.products.filter(produk => {
                    let string = produk.label.toLowerCase()
                    return string.includes(this.search_produk.toLowerCase())
                })
            },

            paginatedProduk() {
                return this.filteredProduk.slice(this.offset_produk, this.limit_produk + this.offset_produk)
            },

            hasNextPageProduk() {
                const nextOffset = this.offset_produk + this.limit_produk
                return Boolean(
                    this.filteredProduk.slice(nextOffset, this.limit_produk + nextOffset).length
                )
            },

            hasPrevPageProduk() {
                const prevOffset = this.offset_produk - this.limit_produk
                return Boolean(
                    this.filteredProduk.slice(prevOffset, this.limit_produk + prevOffset).length
                )
            },

            
        }
    }

</script>
<template>
<div v-if="$store.state.loading"></div>
    <div v-else class="modal fade modalAddBOM" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ titleModalBOM }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Identitas BOM</h4>
                            <div class="card-text text-center">
                                <div class="form-group row" v-show="produk_id == null">
                                    <label for="" class="col-4 text-right">Produk</label>
                                    <div class="col-5">
                                         <v-select v-model="formBom.produk_selected" :options="paginatedProduk"
                                                    :filterable="false" @search="onSearchProduk">
                                            <li slot="list-footer" class="pagination">
                                                <button type="button" class="btn btn-secondary"
                                                    :disabled="!hasPrevPageProduk"
                                                    @click="offset_produk -= limit_produk">Prev</button>
                                                <button type="button" class="btn btn-primary"
                                                    :disabled="!hasNextPageProduk"
                                                    @click="offset_produk += limit_produk">Next</button>
                                            </li>
                                        </v-select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-4 text-right">Kode BOM</label>
                                    <input type="text" class="form-control col-5" v-model="formBom.kode">
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-4 text-right">Nama BOM</label>
                                    <input type="text" class="form-control col-5" v-model="formBom.nama">
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-4 text-right">Tanggal Pembuatan</label>
                                    <input type="date" class="form-control col-5" v-model="formBom.tanggal">
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-4 text-right">Status</label>
                                    <div class="row" style="padding-left: 20px">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                v-model="formBom.status" id="inlineRadio1" value="1">
                                            <label class="form-check-label" for="inlineRadio1">Aktif</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                v-model="formBom.status" id="inlineRadio2" value="0">
                                            <label class="form-check-label" for="inlineRadio2">Tidak Aktif</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Part BOM</h4>
                            <div class="card-text">
                                <div class="d-flex flex-row-reverse bd-highlight">
                                    <div class="p-2 bd-highlight">
                                        <button class="btn btn-primary" @click="addPartBOM">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            Tambah
                                        </button>
                                    </div>
                                </div>
                                <table class="table text-center">
                                    <thead class="thead-light">
                                        <th>No</th>
                                        <th>Nama Part</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(part, idx) in partBOM" :key="idx">
                                            <td>{{ idx+1 }}</td>
                                            <td>
                                                <v-select v-model="part.namaPart" :options="paginated"
                                                    :filterable="false" @search="onSearch">
                                                    <li slot="list-footer" class="pagination">
                                                        <button type="button" class="btn btn-secondary"
                                                            :disabled="!hasPrevPage"
                                                            @click="offset -= limit">Prev</button>
                                                        <button type="button" class="btn btn-primary"
                                                            :disabled="!hasNextPage"
                                                            @click="offset += limit">Next</button>
                                                    </li>
                                                </v-select>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <input type="number" class="form-control col-3" @keypress="isNumber"
                                                        v-model="part.jumlah">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <i style="color: red" class="fa fa-minus" aria-hidden="true"
                                                        @click="deletePartBOM(idx)"></i>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" @click="simpanBOM">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.pagination {
  display: flex;
  margin: 0.25rem 0.25rem 0;
}
.pagination button {
  flex-grow: 1;
}
.pagination button:hover {
  cursor: pointer;
}
</style>
