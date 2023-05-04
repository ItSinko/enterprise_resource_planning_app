<script>
import status from '../../components/status.vue'
import modal from './detail'
export default {
    components: {
        status,
        modal
    },
    props: {
        dataTable: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            modal: false
        }
    },
    methods: {
        openModal() {
            this.modal = true
            this.$nextTick(() => {
                $('.modalDetailPart').modal('show')
            })
        },
        closeDetail() {
            this.modal = false
            $('.modalDetailPart').modal('hide')
        }
    },
}
</script>
<template>
    <div>
        <modal v-if="modal" @close="closeDetail"/>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2"></th>
                    <th rowspan="2">Kode Part</th>
                    <th rowspan="2">Nama Part</th>
                    <th rowspan="2">Jenis</th>
                    <th rowspan="2">Deskripsi</th>
                    <th colspan="2">Jumlah</th>
                    <th rowspan="2">Satuan</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>OK</th>
                    <th>NOK</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(stok, index) in dataTable" :key="index">
                    <td>
                        <img :src="stok.img" class="small-img" alt="Responsive image">
                    </td>
                    <td>{{ stok.kode_part }}</td>
                    <td>{{ stok.nama_part }}</td>
                    <td>
                        <status :status="stok.jenis" />
                    </td>
                    <td>{{ stok.deskripsi }}</td>
                    <td>{{ stok.jumlah_ok }}</td>
                    <td>{{ stok.jumlah_nok }}</td>
                    <td>{{ stok.satuan }}</td>
                    <td>
                        <button class="btn btn-outline-info btn-sm" @click="openModal">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<style>
    .small-img {
        width: 25px;
    }
</style>