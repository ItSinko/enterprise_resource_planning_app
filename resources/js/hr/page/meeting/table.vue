<script>
import status from '../../components/status.vue';
import date from '../../components/date.vue';
import terlaksana from './aksi/terlaksana.vue';
import Edit from './aksi/edit'
import Batal from './aksi/batal'
import catatan from './aksi/catatan_peserta'
export default {
    props: ['dataTable'],
    components: {
        status,
        date,
        terlaksana,
        Edit,
        Batal,
        catatan,
    },
    data() {
        return {
            dataTerlaksana: {},
            modalTerlaksana: false,
            editData: null,
            modalEdit: false,
            modalBatal: false,
            batalData: null,
            cataanData: null,
            modalCatatan: false,
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
        },
        editMeeting(data){
            this.editData = JSON.parse(JSON.stringify(data))
            this.modalEdit = true
            this.$nextTick(() => {
                $('.modalMeetingEdit').modal('show');
            })
        },
        resetEdit(){
            this.editData = null
            this.modalEdit = false
        },
        batalMeeting(data){
            this.batalData = data
            this.modalBatal = true
            this.$nextTick(() => {
                $('.modalBatal').modal('show');
            })
        },
        resetBatal(){
            this.batalData = null
            this.modalBatal = false
        },
        catatanPeserta(data){
            this.catatanData = data
            this.modalCatatan = true
            this.$nextTick(() => {
                $('.modalCatatan').modal('show');
            })
        },
        resetCatatan(){
            this.catatanData = null
            this.modalCatatan = false
        },
    },
}
</script>
<template>
    <div>
        <Batal :meeting="batalData" v-if="modalBatal" @closeModal="resetBatal" />
        <Edit :meeting="editData" v-if="modalEdit" @closeModal="resetEdit" />
        <terlaksana :meeting="dataTerlaksana" v-if="modalTerlaksana" @closeModal="resetTerlaksana" />
        <catatan :meeting="catatanData" v-if="modalCatatan" @closeModal="resetCatatan"/>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nomor Meeting</th>
                    <th scope="col">Judul Meeting</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Mulai</th>
                    <th scope="col">Selesai</th>
                    <th scope="col">Lokasi</th>
                    <th scope="col">Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, idx) in dataTable" :key="idx">
                    <th scope="row">{{ idx + 1 }}</th>
                    <td>Meet-{{ idx + 1 }}</td>
                    <td>{{ data.nama }}</td>
                    <td><date :date="data.tanggal" /></td>
                    <td>{{ data.mulai }}</td>
                    <td>{{ data.selesai }}</td>
                    <td>{{ data.lokasi }}</td>
                    <td><status :status="data.status" /></td>

                    <td>
                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="true"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" v-if="data.status == 'menyusun'">
                            <a>
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-print"></i>
                                    Cetak Undangan Meeting
                                </button>
                            </a>
                            <a>
                                <button class="dropdown-item" type="button" @click="showTerlaksana(data)">
                                    <i class="fas fa-check"></i>
                                    Terlaksana
                                </button>
                            </a>
                            <a>
                                <button class="dropdown-item" type="button" >
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </button>
                            </a>
                            <a>
                                <button class="dropdown-item" type="button" @click="editMeeting(data)">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </button>
                            </a>
                            <a>
                                <button class="dropdown-item" type="button" @click="batalMeeting(data)">
                                    <i class="fas fa-times"></i>
                                    Batal
                                </button>
                            </a>
                        </div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" v-else-if="data.status == 'belum_terlaksana'">
                            <a>
                                <button class="dropdown-item" @click="catatanPeserta(data)">
                                    <i class="fas fa-eye"></i>
                                    Catatan Peserta
                                </button>
                            </a>
                        </div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" v-else-if="data.status == 'selesai'">
                            <a>
                                <button class="dropdown-item">
                                    <i class="fas fa-print"></i>
                                    Cetak Hasil Meeting
                                </button>
                            </a>
                        </div>
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