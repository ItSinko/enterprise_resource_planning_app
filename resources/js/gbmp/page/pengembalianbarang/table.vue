<script>
import Status from '../../components/status.vue'
import Detail from '../permintaanbahanbaku/dalamproses/detail/table/penyerahan/tambah/detail.vue'
import detail from './detail'
export default {
    components: {
        Status,
        detail,
        Detail
    },
    props: {
        renderPaginate: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            modal: false,
        }
    },
    methods: {
        terima(id) {
            this.$swal({
                title: 'Terima Barang',
                text: "Anda yakin telah menerima Pengembalian ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya'
            }).then((result) => {
                if (result.value) {
                    this.$swal(
                        'Terima Pengembalian!',
                        'Data berhasil di terima.',
                        'success'
                    )
                }
            })
        },
        detail(id){
            this.modal = true
            this.$nextTick(() => {
                $('.modalPengembalian').modal('show')
            })
        },
        closeDetail(){
            this.modal = false
            $('.modalPengembalian').modal('hide')
        },
    },
}
</script>
<template>
    <div>
        <detail v-if="modal" @close="closeDetail"/>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No Permintaan</th>
                    <th>No Penyerahan</th>
                    <th>Tujuan</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(pengembalian, index) in renderPaginate" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ pengembalian.no_permintaan }}</td>
                    <td>{{ pengembalian.no_penyerahan }}</td>
                    <td>
                        <status :status="pengembalian.tujuan" />
                    </td>
                    <td>{{ formatTanggal(pengembalian.tgl_pengembalian) }}</td>
                    <td>
                        <button class="btn btn-info btn-sm" v-if="pengembalian.status === 'terima pengembalian'" @click="terima(pengembalian.id)">Terima Pengembalian</button>
                        <status :status="pengembalian.status" v-else />
                    </td>
                    <td>
                        <button @click="detail(pengembalian.id)" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>