<script>
import Modal from './modal'
export default {
    props: ['reworks'],
    components: { Modal },
    data() {
        return {
            search: '',
            header: [
                { text: 'id', value: 'id' },
                { text: 'nama produk', value: 'nama_produk' },
                { text: 'status', value: 'status' },
            ],
            selectAll: false,
            selectReworks: [],
            showDialog: false,
        }
    },
    methods: {
        async deleteReworks() {
            this.$swal({
                text: `Yakin ingin menghapus ${this.selectReworks.length} produk?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then(async (result) => {
                this.$swal('Berhasil', 'Produk berhasil dihapus', 'success')
            })
        },
        refresh() {
            this.$emit('refresh')
        },
        changeStatus(item) {
            this.$emit('changeStatus', item)
        },
        checkAll() {
            if (this.selectAll) {
                this.selectReworks = [...this.reworks]
            } else {
                this.selectReworks = [];
            }
        },
    },
    watch: {
        selectReworks() {
            if (this.selectReworks.length === this.reworks.length) {
                this.selectAll = true
            } else {
                this.selectAll = false
            }
        }
    }
}
</script>
<template>
    <div>
        <Modal v-if="showDialog" :reworks="reworks" :showDialog="showDialog" :selectReworks="selectReworks"
            @close="showDialog = false">
        </Modal>
        <div class="d-flex">
            <v-card flat class="ml-5 mr-auto">
                <v-text-field v-model="search" placeholder="Cari Produk"></v-text-field>
            </v-card>
            <v-card flat>
                <v-btn color="primary" @click="showDialog = true">
                    Tambah atau Edit Produk
                </v-btn>
                <v-btn color="error" v-if="selectReworks.length" @click="deleteReworks">
                    Hapus Produk
                </v-btn>
            </v-card>
        </div>
        <v-data-table hide-default-header :headers="header" :items="reworks" :search="search" :group-by="['kategori']">
            <template #no-data>
                <div class="d-flex justify-center">
                    <v-btn color="primary" @click="refresh">Refresh</v-btn>
                </div>
            </template>

            <template #header>
                <thead>
                    <tr>
                        <th class="text-left">
                            <v-checkbox v-model="selectAll"
                                :indeterminate="selectReworks.length > 0 && selectReworks.length < reworks.length"
                                @click.native="checkAll" color="primary"></v-checkbox>
                        </th>
                        <th class="text-left" v-for="item in header" :key="item.text" v-if="item.text !== 'id'">
                            <span class="text-capitalize">
                                {{ item.text }}
                            </span>
                        </th>
                    </tr>
                </thead>
            </template>

            <template #item.id="{ item }">
                <v-checkbox v-model="selectReworks" :value="item"></v-checkbox>
            </template>

            <template #item.status="{ item }">
                <v-switch v-model="item.status" :input-value="item.status" color="primary" hide-details
                    @change="changeStatus(item)"></v-switch>
            </template>
        </v-data-table>
    </div>
</template>