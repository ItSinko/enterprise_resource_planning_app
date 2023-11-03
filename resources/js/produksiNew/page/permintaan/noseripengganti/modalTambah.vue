<script>
import axios from 'axios'
import DataTable from '../../../components/DataTable.vue'
import seriviatext from '../../prosesSet/proses/modalTransfer/seriviatext.vue'
export default {
    components: {
        DataTable,
        seriviatext,
    },
    data() {
        return {
            loading: false,
            headers: [
                {
                    text: 'No Seri',
                    value: 'noseri',
                },
                {
                    text: 'Nama Produk',
                    value: 'nama',
                },
                {
                    text: 'Varian',
                    value: 'varian',
                },
                {
                    text: 'No Produk',
                    value: 'no_produk',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false,
                }
            ],
            noseri: [],
            loadingData: false,
            formNoseri: [
                {
                    noseri: null,
                }
            ],
            search: '',
            offset: 0,
            limit: 10,
            keterangan: '',
            showTextSeri: false,
        }
    },
    methods: {
        async getNoseri() {
            try {
                this.loadingData = true
                const { data } = await axios.get('/api/prd/fg/riwayat')
                this.noseri = data.map(item => {
                    return {
                        label: item.noseri,
                        ...item,
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.loadingData = false
            }
        },
        closeModal() {
            $('.modalTambah').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        onSearch(query) {
            this.search = query;
            this.offset = 0;
        },
        notifikasiSeri(noseri) {
            const duplikasi = this.formNoseri.filter(item => item.noseri?.label === noseri?.label)
            if (duplikasi.length > 1) {
                return 'No seri sudah ada'
            }
            if (noseri == null) {
                return 'No seri tidak boleh kosong'
            }
            return null
        },
        transfer() {
            const uniqueNoseriItems = [...new Set(this.formNoseri.map(item => item.noseri?.label))]
            if (uniqueNoseriItems.length < this.formNoseri.length) {
                this.$swal('Peringatan', 'No seri tidak boleh sama', 'warning')
                return
            }

            const cekkosong = this.formNoseri.filter(item => item.noseri == null)
            if (cekkosong.length > 0) {
                this.$swal('Peringatan', 'No seri tidak boleh kosong', 'warning')
                return
            }

            if (this.keterangan == '') {
                this.$swal('Peringatan', 'Keterangan tidak boleh kosong', 'warning')
                return
            }

            this.loading = true
            this.$swal('Berhasil', 'Berhasil transfer', 'success')
            this.closeModal()
        },
        showSeriViaText() {
            this.showTextSeri = true
            $('.modalTambah').modal('hide')
            this.$nextTick(() => {
                $('.modalChecked').modal('show')
            })
        },
        closeTextSeri() {
            this.showTextSeri = false
            this.$nextTick(() => {
                $('.modalTambah').modal('show')
            })
        },
        submit(noseri) {
            let noserinotfound = []

            let noseriarray = noseri.split(/[\n, \t]/)

            // remove empty string
            noseriarray = noseriarray.filter(item => item !== '')

            // remove duplicate
            noseriarray = [...new Set(noseriarray)]

            // remove null in formNoseri
            this.formNoseri = this.formNoseri.filter(item => item.noseri != null)

            for (let i = 0; i < noseriarray.length; i++) {
                let found = false
                for (let j = 0; j < this.noseri.length; j++) {
                    if (noseriarray[i] === this.noseri[j].label) {
                        if (!this.formNoseri.find(item => item.noseri?.label === this.noseri[j].label)) {
                            this.formNoseri.push({
                                noseri: this.noseri[j],
                            })
                        }
                        found = true
                        break
                    }
                }
                if (!found) {
                    noserinotfound.push(noseriarray[i])
                }
            }
            noserinotfound = [...new Set(noserinotfound)]

            if(noserinotfound.length > 0 && noserinotfound != '') {
                this.$swal('Peringatan', `No seri ${noserinotfound.join(', ')} tidak ditemukan`, 'warning')
            }
        }
    },
    mounted() {
        this.getNoseri()
    },
    computed: {
        noSeriSelect() {
            if (this.noseri.length > 0) {
                let data = this.noseri.map((item) => {
                    return {
                        label: item.noseri,
                        ...item,
                    };
                });
                return data;
            }
        },
        filteredNoSeri() {
            if (this.noseri.length > 0) {
                return this.noSeriSelect.filter((item, idx) =>
                    Object.values(item).some(
                        (val) =>
                            val != null &&
                            val.toString().toLowerCase().includes(this.search.toLowerCase())
                    )
                );
            }
        },
        paginated() {
            if (this.noseri.length > 0) {
                return this.filteredNoSeri.slice(
                    this.offset,
                    this.limit + this.offset
                );
            }
        },
        hasNextPage() {
            if (this.noseri.length > 0) {
                const nextOffset = this.offset + this.limit;
                return Boolean(
                    this.filteredNoSeri.slice(nextOffset, this.limit + nextOffset)
                        .length
                );
            }
        },
        hasPrevPage() {
            if (this.noseri.length > 0) {
                const prevOffset = this.offset - this.limit;
                return Boolean(
                    this.filteredNoSeri.slice(prevOffset, this.limit + prevOffset)
                        .length
                );
            }
        },
    }
}
</script>
<template>
    <div>
        <seriviatext v-if="showTextSeri" @close="closeTextSeri" @submit="submit" />
        <div class="modal fade modalTambah" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Penggantian Nomor Seri</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-grow-1 bd-highlight">
                                <button class="btn btn-primary" @click="showSeriViaText" :disabled="loadingData">
                                    Pilih No Seri Via Text
                                </button>
                            </div>
                            <div class="p-2 bd-highlight">
                                <button class="btn btn-primary mb-1" @click="formNoseri.push({ noseri: null })">
                                    <i class="fas fa-plus"></i>
                                    Tambah
                                </button>
                            </div>
                        </div>
                        <div class="spinner-border" role="status" v-if="loadingData">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div class="scrollable" v-else>
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th style="width: 300px;">No Seri</th>
                                        <th>Nama Produk</th>
                                        <th>Varian</th>
                                        <th>No Produksi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in formNoseri" :key="index">
                                        <td>
                                            <v-select :options="paginated" v-model="item.noseri" @search="onSearch" style="width: 300px">
                                                <li slot="list-footer" class="pagination">
                                                    <button type="button" class="btn btn-secondary" :disabled="!hasPrevPage"
                                                        @click="offset -= limit">
                                                        Prev
                                                    </button>
                                                    <button type="button" class="btn btn-primary" :disabled="!hasNextPage"
                                                        @click="offset += limit">
                                                        Next
                                                    </button>
                                                </li>
                                            </v-select>
                                            <small class="text-danger" v-if="notifikasiSeri(item.noseri)">{{
                                                notifikasiSeri(item.noseri) }}</small>
                                        </td>
                                        <td>
                                            {{ item.noseri?.nama ?? '-' }}
                                        </td>
                                        <td>
                                            {{ item.noseri?.varian ?? '-' }}
                                        </td>
                                        <td>
                                            {{ item.noseri?.no_produk ?? '-' }}
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" @click="formNoseri.splice(index, 1)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label for="">Keterangan</label>
                            <textarea cols="5" class="form-control" v-model="keterangan"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        <button type="button" class="btn btn-success" :disabled="loading"
                            @click="transfer">Transfer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
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

.scrollable {
    height: 300px;
    overflow-y: auto;
}
</style>