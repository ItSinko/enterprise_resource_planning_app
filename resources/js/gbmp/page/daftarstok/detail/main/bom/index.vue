<script>
import detail from './detail.vue'
export default {
    components: {
        detail
    },
    data() {
        return {
            search: '',
            dataTable: [
                {
                    id: 1,
                    nama_produk: 'SONO',
                    versi_bom: '1.0',
                    jumlah_kebutuhan: 100,
                }
            ],

            modalDetail: false,
        }
    },
    methods: {
        detail(id) {
            this.modalDetail = true
            this.$nextTick(() => {
                $('.modalDetailBOM').modal('show')
            })
        },
        closeDetailModal() {
            this.modalDetail = false
            $('.modalDetailBOM').modal('hide')
        }
    },
    computed: {
        filteredDataTable() {
            return this.dataTable.filter(data => {
                return Object.keys(data).some(key => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        }
    },
}
</script>
<template>
    <div>
        <detail v-if="modalDetail" @close="closeDetailModal"></detail>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari">
            </div>
        </div>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Versi BOM</th>
                    <th>Jumlah Kebutuhan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in filteredDataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.nama_produk }}</td>
                    <td>{{ data.versi_bom }}</td>
                    <td>{{ data.jumlah_kebutuhan }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" @click="detail(data.id)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>