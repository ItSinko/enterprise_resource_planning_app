<script>
export default {
    props: ['subProduk', 'dialogDetail', 'indexProduk'],
    data() {
        return {
            form: [],
            headers: [
                { text: 'Kelompok Produk', value: 'kelompok_produk_id'},
                { text: 'Merk', value: 'merk'},
                { text: 'Nama', value: 'nama'},
                { text: 'No AKD', value: 'no_akd'},
                { text: 'Status', value: 'status'},
            ],
            kelompok: [
                { text: 'Alat Kesehatan', value: 1},
                { text: 'Water Treatment', value: 2},
                { text: 'Aksesoris', value: 3},
                { text: 'Lain lain', value: 4},
                { text: 'Sparepart', value: 5}
            ],
            merk : ['ELITECH', 'MENTOR', 'VANWARD', 'RGB'],
            // form validation
            valid: true,
            rules: {
                required: value => !!value || 'Required.',
                mustBeNumber: value => !isNaN(value) || 'Must be a number',
            },
            search: '',
        }
    },
    methods: {
        addProduk() {
            this.form.push({
                kelompok_produk_id: '',
                merk: '',
                nama: '',
                no_akd: '',
                status: '1',
                gudang_barang_jadi: [
                    {
                        stok: 0,
                        stok_siap: 0,
                        satuan_id: 1,
                    }
                ]
            })
        },
        editSubProduk() {
            Object.assign(this.form, this.subProduk)
            const updatedForm = []
            for (let i = 0; i < this.form.length; i++) {
                updatedForm.push({
                    ...this.form[i],
                    status: this.form[i].status == '1' ? true : false
                })
            }
            this.form = updatedForm
        },
        closeDialog() {
            this.$emit('closeDialog')
            this.dialogDetail = false
        },
        simpan() {
            const validation = this.$refs.form.validate()
            if(validation){
                this.$emit('simpan', this.form)
                this.closeDialog()
            }
        },
        removeProduk(item) {
            const index = this.form.indexOf(item)
            if (index > -1) {
                this.form.splice(index, 1)
            }
        }
    },
    watch: {
        dialogDetail(val) {
            val ? this.editSubProduk() : this.form = []
        }
    },
}
</script>
<template>
    <div>
        <v-dialog
        v-model="dialogDetail"
        persistent
        max-width="60%"
        >
            <v-card>
                <v-toolbar
                dark
                color="primary"
                >
                    <v-btn
                        icon
                        dark
                        @click="closeDialog"
                    >
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                    text
                    @click="simpan"
                    >Simpan</v-btn>
                </v-toolbar>
                <v-card-text class="mt-5">
                    <div class="d-flex">
                        <v-card flat class="ml-5">
                            <v-text-field
                            v-model="search"
                            placeholder="Cari Produk"
                            ></v-text-field>
                        </v-card>

                        <v-btn
                        color="primary"
                        class="ml-auto"
                        @click="addProduk"
                        >
                            Tambah Sub Produk
                        </v-btn>
                    </div>
                    <v-form
                    ref="form"
                    v-model="valid"
                    lazy-validation
                    >
                    <v-data-table
                        :headers="headers"
                        :items="form"
                        :search="search"
                        fixed-header
                        height="500px"
                    >
                        <template #item = "{ item, index }">
                            <tr >
                                <td>
                                <v-autocomplete
                                class="mt-5"
                                    v-model="item.kelompok_produk_id"
                                    :items="kelompok"
                                    :rules="[rules.required]"
                                    outlined
                                    dense
                                ></v-autocomplete>
                            </td>

                            <td>
                                <v-autocomplete
                                class="mt-5 mx-1"
                                    v-model="item.merk"
                                    :items="merk"
                                    :rules="[rules.required]"
                                    outlined
                                    dense
                                ></v-autocomplete>
                            </td>

                            <td>
                                <v-text-field
                                class="mt-5 mx-1"
                                    v-model="item.nama"
                                    :rules="[rules.required]"
                                    outlined
                                    dense
                                ></v-text-field>
                            </td>

                            <td>
                                <v-text-field
                                class="mt-5 mx-1"
                                    v-model.number="item.no_akd"
                                    type="number"
                                    :rules="[rules.mustBeNumber]"
                                    outlined
                                    dense
                                ></v-text-field>
                            </td>

                            <td>
                                <v-switch
                                class="mt-5 mx-1"
                                    v-model="item.status"
                                    @click="item.status = item.status ? '1' : '0'"
                                    color="primary"
                                ></v-switch>
                            </td>

                            <td v-if="!item.id">
                                <v-btn
                                icon
                                    @click="removeProduk(item)"
                                >
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </td>
                            </tr>
                        </template>
                    </v-data-table>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>