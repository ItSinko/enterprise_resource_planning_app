<script>
import Status from '../../../../components/status.vue'
import Detail from './detail'
export default {
    components: {
        Status,
        Detail
    },
    data() {
        return {
            dataTable: [
                {
                    id: 1,
                    nama_bom: 'BOM-001',
                    no_laporan: 'LP-001',
                    checklist_kedatangan: 'Sudah',
                    status: 'selesai',
                    persentase: 80,
                    btn_aksi: 'terima',
                },
                {
                    id: 2,
                    nama_bom: 'BOM-002',
                    no_laporan: 'LP-002',
                    checklist_kedatangan: 'Sudah',
                    status: 'selesai',
                    btn_aksi: 'hasil',
                }
            ],
            modal: false
        }
    },
    methods: {
      detail(id){
        this.modal = true
        this.$nextTick(() => {
          $('.modalDetailBarangMasuk').modal('show')
        })
      }, 
      closeModalDetail(){
        this.modal = false
        $('.modalDetailBarangMasuk').modal('hide')
      }, 
    },
}
</script>
<template>
    <div>
        <detail v-if="modal" @close="closeModalDetail"/>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th>Nama BOM</th>
                    <th>No Laporan</th>
                    <th>Checklist Kedatangan</th>
                    <th>Status Sampling Rakit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="data in dataTable" :key="data.id">
                    <td>{{ data.nama_bom }}</td>
                    <td>{{ data.no_laporan }}</td>
                    <td><button class="btn btn-warning" @click="unduh(data.id)">Unduh Dokumen</button></td>
                    <td>
                        <status :status="data.status" :persentase="data.persentase"/>
                    </td>
                    <td>
                        <button class="btn btn-warning" v-if="data.btn_aksi == 'terima'">Terima dan Periksa</button>
                        <button class="btn btn-danger" v-if="data.btn_aksi == 'hasil'">Hasil Analisa Teknik</button>
                        <button class="btn btn-outline-info" @click="detail(data.id)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>