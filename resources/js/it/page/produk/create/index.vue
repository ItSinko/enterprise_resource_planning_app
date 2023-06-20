<script>
import Detailproduk from './detailproduk.vue'
import detailproduk from './detailproduk.vue'
import axios from 'axios'
export default {
    props: ['produk', 'dialogCreate'],
    components: {
    detailproduk,
    Detailproduk
},
    data() {
        return {
            form: [],
            headers: [
                { text: 'Nama Produk', value: 'nama'},
                { text: 'Aksi', value: 'action'},
            ],
            subProduk: null,
            indexProduk: 0,
            dialogDetail: false,
            valid: true,
            search: '',
        }
    },
    methods: {
        addProduk() {
            this.form.push({
                nama: '',
                detailproduk: []
            })
        },
        editProduk() {
            // change object produk to array
            if(this.produk){
                this.form.push(this.produk)
            } else{
                this.form.push({
                    nama: '',
                    detailproduk: []
                })
            }
        },
        editSubProdukDetail(item, index){
            const produk = []
            for (let i = 0; i < item.length; i++) {
                produk.push({
                    ...item[i],
                })
            }
            this.subProduk = Object.assign({}, produk)
            this.indexProduk = index
            this.dialogDetail = true
        },
        closeDetailDialog() {
            this.dialogDetail = false
            this.subProduk = null
        },
        closeDialog() {
            this.$emit('closeDialog')
            this.form = []
        },
        simpanSubProduk(item) {
            for (let i = 0; i < item.length; i++) {
                this.form[this.indexProduk].detailproduk = item
            }
        },
        async simpan() {
            const validation = await this.$refs.form.validate()
            if(validation){
                try {
                    await axios.post('/api/produk', this.form)
                    this.$emit('refresh')
                    this.$swal('Berhasil', 'Data berhasil disimpan', 'success')
                } catch (error) {
                    // show message error
                    const { response } = error
                    this.$swal('Gagal', `${response.data.message}`, 'error')
                }
            }
        }
    },
    watch: {
        dialogCreate(val) {
            val ? this.editProduk() : this.form = []
        }
    },
}
</script>
<template>
    <div>
        <Detailproduk
            :subProduk="subProduk"
            :dialogDetail="dialogDetail"
            :indexProduk="indexProduk"
            @closeDialog="closeDetailDialog"
            @simpan="simpanSubProduk"
        ></Detailproduk>
        <v-dialog
        v-model="dialogCreate"
        persistent
        max-width="50%"
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
                            Tambah Produk
                        </v-btn>
                    </div>
                    <v-form
                    v-model="valid"
                    ref="form"
                    lazy-validation
                    >
                    <v-data-table
                        :headers="headers"
                        :items="form"
                        :search="search"
                        fixed-header
                        height="500px"
                    >
                        <template #item.nama = "{item}">
                            <v-text-field
                            class="mt-5"
                            v-model="item.nama"
                            :rules="[v => !!v || 'Nama Produk harus diisi']"
                            outlined
                            ></v-text-field>
                        </template>

                        <template #item.action ="{item, index}">
                            <div>
                                <v-btn
                                icon
                                @click="editSubProdukDetail(item.detailproduk, index)"
                                >
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn
                                icon
                                v-if="item.id == undefined"
                                @click="form.splice(index, 1)"
                                >
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>
                            </div>
                        </template>
                    </v-data-table>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>