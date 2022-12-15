<script>
import Modal from './modal'
export default {
    components: {
        Modal
    },
    props: {
        dataTable: {
            type: Array,
            default: () => []
        },
        nopp: {
            type: String,
            default: ''
        }
    },
    methods: {
        addPart() {
            $('.modalPart').modal('show')
        }
    }
}
</script>
<template>
    <div class="card">
        <Modal/>
        <div class="card-body">
            <h4 class="card-title text-bold">Daftar Part</h4>
            <div class="card-text">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" @click="addPart">
                        <i class="fa fa-plus"></i>
                        Tambah Part</button>
                </div>

                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Versi BOM</th>
                            <th>Jumlah</th>
                            <th>Detail</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody v-if="dataTable.length > 0"></tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>