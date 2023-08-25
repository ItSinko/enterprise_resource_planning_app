<script>
import status from '../../components/status.vue';
import date from '../../components/date.vue';
import terlaksana from './aksi/terlaksana.vue';
export default {
    props: ['dataTable'],
    components: {
        status,
        date,
        terlaksana,
    },
    data() {
        return {
            dataTerlaksana: {},
            modalTerlaksana: false,
        }
    },
    methods: {
        showTerlaksana(data){
            this.dataTerlaksana = data
            this.modalTerlaksana = true
            this.$nextTick(() => {
                $('.modalterlaksana').modal('show')
            })
        },
        resetTerlaksana(){
            this.dataTerlaksana = {}
            this.modalTerlaksana = false
        }
    },
}
</script>
<template>
    <div>
        <terlaksana :meeting="dataTerlaksana" v-if="modalTerlaksana" 
        @closeModal="resetTerlaksana"
        />
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Mulai</th>
                    <th scope="col">Selesai</th>
                    <th scope="col">Jumlah Peserta</th>
                    <th scope="col">Status</th>
                    <th scope="col">Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, idx) in dataTable" :key="idx">
                    <td>{{ data.nama }}</td>
                    <td><date :date="data.tanggal" /></td>
                    <td>{{ data.mulai }}</td>
                    <td>{{ data.selesai }}</td>
                    <td>{{ data.jumlah_peserta }}</td>
                    <td><status :status="data.status" /></td>
                    <td>{{ data.lokasi }}</td>
                    <td v-if="data.status != 'selesai'">
                        <button class="btn btn-sm btn-primary" 
                        @click="showTerlaksana(data)"
                        >Terlaksana</button>
                        <button class="btn btn-sm btn-warning">Edit</button>
                        <button class="btn btn-sm btn-danger">Batal</button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>