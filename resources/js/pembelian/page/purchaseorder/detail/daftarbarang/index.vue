<script>
import Status from '../../../../components/status.vue'
import modalDetail from '../modalDetail/index.vue'
export default {
    props: {
        kurs: {
            type: String,
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
            dataTable: [
                {
                    nama_produk: 'Produk 1',
                    jumlah: 10,
                    status: 'selesai',
                },
                {
                    nama_produk: 'Produk 2',
                    jumlah: 10,
                    status: 'belum proses',
                }
            ],
            modal: false,
        }
    },
    methods: {
        checkAll() {
            this.$refs.child.forEach(item => {
                item.checked = !item.checked
            })
        },
        showModal(id) {
            this.modal = true
            setTimeout(() => {
                $('.modalDetailPO').modal('show')
            }, 100);
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
        <modal-detail :kurs="kurs" v-if="modal" @close="closeModal"/>
        <button class="btn btn-info">
            <i class="fa fa-check"></i>
            Tambah Penerimaan
        </button>
        <table class="table text-center mt-1">
            <thead class="thead-light">
                <tr>
                    <th v-if="divisi === 7"><input type="checkbox" @click="checkAll"></th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(table, index) in dataTable" :key="index">
                    <td v-if="divisi === 7"><input type="checkbox" ref="child" v-if="divisi === 7"></td>
                    <td>{{ table.nama_produk }}</td>
                    <td>{{ table.jumlah }}</td>
                    <td><status :status="table.status" /></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" @click="showModal(table.jumlah)">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>