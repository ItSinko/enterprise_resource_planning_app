<script>
import Status from '../../../../components/status.vue'
import modalDetail from '../modalDetail/index.vue'
export default {
    props: {
        kurs: {
            type: String,
            required: true
        },
        dataTable: {
            type: Array,
            required: true
        }
    },
    components: {
        Status,
        modalDetail
    },
    data() {
        return {
            divisi: parseInt(localStorage.getItem('divisi')),
            modal: false,
            detail: [],
            jenis: this.$route.params.jenis,
            addPermintaan: [],
        }
    },
    methods: {
        checkAll() {
            this.$refs.child.forEach(item => {
                item.checked = !item.checked
            })

            this.addPermintaan = this.$refs.child.every(item => item.checked) ? this.dataTable : []
        },
        checked(item, event){
            this.addPermintaan = event.target.checked ? [...this.addPermintaan, item] : this.addPermintaan.filter(data => data.id !== item.id)
        },  
        showModal(detail) {
            this.modal = true
            this.detail = detail
            this.$nextTick(() => {
                $('.modalDetailPO').modal('show')
            })
        },
        closeModal() {
            this.modal = false
            $('.modalDetailPO').modal('hide')
        }
    },
}
</script>
<template>
    <div>
        <modal-detail :dataTables="detail" :kurs="kurs" v-if="modal" @close="closeModal"/>
        <button class="btn btn-info" v-if="$route.params.open !== 'riwayat'">
            <i class="fa fa-check"></i>
            Tambah Penerimaan
        </button>
        <table class="table text-center mt-1">
            <thead class="thead-light">
                <tr>
                    <th v-if="divisi === 7 && $route.params.open !== 'riwayat'"><input type="checkbox" @click="checkAll"></th>
                    <th>Nama Produk</th>
                    <th v-if="jenis == 'umum'">Jumlah</th>
                    <!-- <th>Status</th> -->
                    <th v-if="jenis != 'umum'">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(table, index) in dataTable" :key="index">
                    <td v-if="divisi === 7 && $route.params.open !== 'riwayat'">
                        <input type="checkbox"
                        @click="checked(table, $event)"
                        ref="child" 
                        v-if="divisi === 7"></td>
                    <td>{{ table.nama_produk }}</td>
                    <td v-if="jenis == 'umum'">{{ table.jumlah }}</td>
                    <!-- <td><status :status="table.status" /></td> -->
                    <td v-if="jenis != 'umum'">
                        <button class="btn btn-sm btn-outline-primary" @click="showModal(table.detail)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>