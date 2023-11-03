<script>
import axios from 'axios'
export default {
    props: ['dataDetailSelected', 'showDialog'],
    data() {
        return {
            product: [],
            header: [
                { text: 'Nama', value: 'nama' },
            ],
            rules: {
                required: value => !!value || 'Required.',
            },
            valid: true,
        }
    },
    methods: {
        closeDialog() {
            this.$emit('closeDialog')
        },
        async getProduct() {
            try {
                const { produk } = await axios.get('/api/produk').then(res => res.data);
                this.product = produk.map(item => ({
                    text: item.nama,
                    value: item.id
                }))
            } catch (error) {
                console.log(error);
            }
        },
        tambah() {
            this.dataDetailSelected.child.push({
                nama: '',
            })
        },
        checkDuplicate(value) {
            return this.dataDetailSelected.child.filter(item => item.nama === value).length > 1 ? 'Nama produk tidak boleh sama' : true
        },
        simpan() {
            const isValid = this.$refs.formChild.validate()
            if (!isValid) return
            this.$emit('simpan')
        }
    },
    mounted() {
        this.getProduct()
    }
}
</script>
<template>
    <div>
        <v-dialog v-model="showDialog" max-width="70%" persistent>
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="closeDialog">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Tambah Detail Produk {{ dataDetailSelected.nama }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn text @click="simpan">Simpan</v-btn>
                </v-toolbar>
                <v-form ref="formChild" v-model="valid" lazy-validation>
                    <v-data-table :items="dataDetailSelected.child" :headers="header">
                        <template #top>
                            <div class="d-flex justify-end">
                                <v-btn class="mr-1 mt-1" color="primary" @click="tambah">Tambah</v-btn>
                            </div>
                        </template>

                        <template #item="{ item, index }">
                            <tr>
                                <td>
                                    <v-autocomplete v-model="item.nama" :items="product"
                                        :rules="[rules.required, checkDuplicate]"></v-autocomplete>
                                </td>
                                <td>
                                    <v-btn icon @click="dataDetailSelected.child.splice(index, 1)">
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                </td>
                            </tr>
                        </template>
                    </v-data-table>
                </v-form>
            </v-card>
        </v-dialog>
    </div>
</template>