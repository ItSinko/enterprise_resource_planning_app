<script>
import Modal from './modalCreateEdit.vue'
import axios from 'axios'
export default {
    components: { Modal },
    props: ['product'],
    data() {
        return {
            search: '',
            header: [
                { text: 'id', value: 'id' },
                { text: 'nama', value: 'nama' },
                { text: 'kategori', value: 'kategori' },
                { text: 'status', value: 'status'}
            ],
            selectAll: false,
            selectProduct: [],
            showDialog: false,
        }
    },
    methods: {
        getProduct() {
            this.$emit('getProduct')
        },
        checkAll() {
            if (this.selectAll) {
                this.selectProduct = [...this.product]
            } else {
                this.selectProduct = [];
            }
        },
        async deleteProduk() {
            this.$swal({
                text: `Yakin ingin menghapus ${this.selectProduct.length} produk?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then(async (result) => {
                try {
                    const { data } = await axios.delete('/api/produk', {
                        data: this.selectProduct
                    })
                    this.$swal('Berhasil', 'Produk berhasil dihapus', 'success')
                    this.getProduct()
                } catch (error) {
                    console.log(error)
                    this.$swal('Gagal', 'Produk gagal dihapus', 'error')
                }
            })
        },
        async updateStatus(item) {
            try {
                const { data } = await axios.post('/api/changeStatusProduk', {
                id: item.id,
                status: item.status
            })

            this.$swal('Berhasil', 'Status berhasil diubah', 'success')
            } catch (error) {
                this.$swal('Gagal', 'Status gagal diubah', 'error')
            }
        },
    },
}
</script>
<template>
    <div>
        <Modal
            @closeDialog="showDialog = false"
            @getProduct="getProduct"
            :selectProduct="selectProduct"
            :dialogCreate="showDialog"
            :product="product"
            v-if="showDialog"
        ></Modal>
        <div class="d-flex">
            <v-card flat class="ml-5 mr-auto">
                <v-text-field
                v-model="search"
                placeholder="Cari Produk"
                ></v-text-field>
            </v-card>
            <v-card flat>
                <v-btn color="primary" @click="showDialog = true">
                    Tambah atau Edit Produk
                </v-btn>
                <v-btn color="error" v-if="selectProduct.length" @click="deleteProduk">
                    Hapus Produk
                </v-btn>
            </v-card>
        </div>
        <v-data-table
        hide-default-header
        :headers="header"
        :items="product"
        :search="search"
        :group-by="['kategori']"
        >
                <!-- No Data -->
        <template #no-data>
            <div class="d-flex justify-center">
                <v-btn color="primary" @click="getProduct">Refresh</v-btn>
            </div>
        </template>

        <template #header>
            <thead>
                <tr>
                    <th class="text-left">
                        <v-checkbox
                            :indeterminate="selectProduct.length > 0 && selectProduct.length < product.length"
                            @click.native="checkAll"
                            v-model="selectAll"
                        ></v-checkbox>
                    </th>
                    <th class="text-left">
                        Nama Produk
                    </th>
                    <th class="text-left">
                        Status
                    </th>
                </tr>
            </thead>
        </template>

        <template #item.id = "{ item }">
            <v-checkbox
                v-model="selectProduct"
                :value="item"
            ></v-checkbox>
        </template>

        <template #item.status="{ item }">
            <v-switch
                @click="updateStatus(item)"
                v-model="item.status"
                :label="item.status ? 'Aktif' : 'Tidak Aktif'"
            ></v-switch>
        </template>

        </v-data-table>
    </div>
</template>