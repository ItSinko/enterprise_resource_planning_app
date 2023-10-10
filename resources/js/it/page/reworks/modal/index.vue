<script>
import axios from 'axios'
export default {
    props: ['selectReworks', 'showDialog', 'reworks'],
    data() {
        return {
            header: [
                {
                    text: 'Kelompok Produk',
                    value: 'kelompok_produk_id',
                },
                {
                    text: 'Merk',
                    value: 'merk',
                },
                {
                    text: 'Kode Produk',
                    value: 'kode_produk',
                },
                {
                    text: 'Nama',
                    value: 'nama',
                },
                {
                    text: 'Kategori',
                    value: 'kategori',
                },
                {
                    text: 'No AKD',
                    value: 'no_akd',
                },
                {
                    text: 'Status',
                    value: 'status',
                },
            ],
            selectAll: false,
            category: null,
            showDialog: false,
            loading: false,
            kelompok: [
                { text: 'Alat Kesehatan', value: 1 },
                { text: 'Water Treatment', value: 2 },
                { text: 'Aksesoris', value: 3 },
                { text: 'Lain lain', value: 4 },
                { text: 'Sparepart', value: 5 }
            ],
            valid: true,
            merk: ['ELITECH', 'MENTOR', 'VANWARD', 'RGB'],
            rules: {
                required: value => !!value || 'Required.',
                mustBeNumber: value => !isNaN(value) || 'Must be a number',
                nameUnique: (id, value) => {
                    return id ? true :
                        !this.product.some(item => item.nama === value) || 'Nama produk sudah ada'
                }
            },
        }
    },
    methods: {
        async getCategory() {
            const { kategori } = await axios.get('/api/kategori').then(res => res.data)
            this.category = kategori.map(item => ({
                text: item.nama,
                value: item.id
            }))
        },
        closeDialog() {
            this.$emit('closeDialog')
            this.$emit('refresh')
        },
        tambah() {
            this.selectReworks.push({
                kelompok_produk_id: '',
                merk: '',
                kode_produk: '',
                nama: '',
                produk_id: '',
                no_akd: '',
                status: '1',
                detailPaket: [
                    {
                        nama_produk: '',
                        jumlah: 0,
                    }
                ]
            })
        }
    },
    created() {
        this.getCategory()
    },
    watch: {
        status(val) {
            if (val == '1') {
                this.selectProduct.status = true
            } else {
                this.selectProduct.status = false
            }
        }
    }
}
</script>
<template>
    <div>
        <v-dialog v-model="showDialog" persistent max-width="70%">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="closeDialog">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Tambah Reworks</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn :loading="loading" :disabled="loading" text>Simpan</v-btn>
                </v-toolbar>

                <v-form ref="formProducts" v-model="valid" lazy-validation>
                    <v-data-table :headers="header" :items="selectReworks">
                        <template #top>
                            <div class="d-flex justify-end">
                                <v-btn class="mr-1 mt-1" color="primary" @click="tambah">Tambah</v-btn>
                            </div>
                        </template>

                        <template #item="{ item, index }">
                            <tr>
                                <td>
                                    <v-autocomplete class="mt-5" v-model="item.kelompok_produk_id" :items="kelompok"
                                        :rules="[rules.required]" outlined dense></v-autocomplete>
                                </td>
                                <td>
                                    <v-autocomplete class="mt-5" v-model="item.merk" :items="merk" :rules="[rules.required]"
                                        outlined dense>
                                    </v-autocomplete>
                                </td>
                                <td>
                                    <v-text-field type="number" class="mt-5" v-model="item.kode_produk"
                                        :rules="[rules.mustBeNumber]" outlined dense></v-text-field>
                                </td>
                                <td>
                                    <v-text-field class="mt-5" v-model="item.nama"
                                        @input="item.nama = item.nama.toUpperCase()"
                                        :rules="[rules.required, rules.nameUnique(item.id, item.nama)]" outlined
                                        dense></v-text-field>
                                </td>
                                <td>
                                    <v-autocomplete class="mt-5" v-model="item.produk_id" :items="category"
                                        :rules="[rules.required]" outlined dense></v-autocomplete>
                                </td>
                                <td>
                                    <v-switch class="mt-5 mx-1" v-model="item.status" color="primary"></v-switch>
                                </td>
                            </tr>
                        </template>
                    </v-data-table>
                </v-form>
            </v-card>
        </v-dialog>
    </div>
</template>
