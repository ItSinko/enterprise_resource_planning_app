<script>
import VueSelect from 'vue-select'
import axios from 'axios';
export default {
    props: ['meeting'],
    components: {
        VueSelect
    },
    data() {
        return {
            karyawan: [],
                hourRangeAkhir: [],
        }
    },
    methods: {
        closeModal() {
            $('.modalMeetingEdit').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        inputNumberOnly(e) {
            const re = /[0-9]+/g;
            if (!re.test(e.key)) {
                e.preventDefault();
            }
        },
        cekMulaiSelesai() {
            if (this.meeting.mulai > this.meeting.selesai) {
                this.$swal({
                    title: 'Perhatian!',
                    text: 'Jam mulai tidak boleh lebih besar dari jam selesai',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                })
                this.meeting.mulai = ''
                return false
            }
            return true
        },
        cekSelesaiMulai() {
            if (this.meeting.selesai < this.meeting.mulai) {
                this.$swal({
                    title: 'Perhatian!',
                    text: 'Jam selesai tidak boleh lebih kecil dari jam mulai',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                })
                this.meeting.selesai = ''
                return false
            }
            return true
        },
                calculateHourAkhir() {
            if (this.meeting.mulai !== '') {
                const waktu_awal = this.meeting.mulai.split(':')
                const hour = []
                for (let i = waktu_awal[0]; i <= 23; i++) {
                    hour.push(i)
                }
                this.hourRangeAkhir = hour
            } else {
                this.hourRangeAkhir = []
            }
        },
        async getDataKaryawan() {
            try {
                const response = await axios.get('/api/karyawan_all')
                this.karyawan = response.data
            } catch (error) {
                console.log(error)
            }
        },
        simpan() {
            this.cekMulaiSelesai()
            this.cekSelesaiMulai()
        }
    },
    mounted() {
        this.getDataKaryawan()
    }
}
</script>
<template>
    <div class="modal fade modalMeetingEdit" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jadwal Meeting</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Deskripsi / Agenda Meeting</label>
                        <textarea class="form-control" v-model="meeting.deskripsi"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Tanggal Meeting</label>
                        <input type="date" class="form-control" v-model="meeting.tanggal">
                    </div>
                    <div class="form-group row">
                        <label for="mulai" class="col-sm-2 col-form-label">Jam</label>
                            <div class="col-sm-4">
                                <vue-timepicker v-model="meeting.mulai" input-width="100%" autocomplete="on" @input="calculateHourAkhir" />
                            </div>
                            -
                            <div class="col-sm-4">
                                <vue-timepicker v-model="meeting.selesai" input-width="100%" autocomplete="on" :hour-range="hourRangeAkhir" />
                            </div>
                    </div>
                    <div class="form-group row">
                        <div class="col">
                            <label for="">Notulen</label>
                            <vue-select :options="karyawan" label="nama" :reduce="karyawan => karyawan.id" v-model="meeting.notulen"/>
                        </div>
                        <div class="col">
                            <label for="">Moderator</label>
                            <vue-select :options="karyawan" label="nama" :reduce="karyawan => karyawan.id" v-model="meeting.moderator"/>
                        </div>
                    </div>
                    <!-- diubah ke peserta seperti tambah dan value jumlah peserta menghitung array peserta -->
                    <div class="form-group">
                        <label for="">Peserta Meeting</label>
                        <vue-select multiple :options="karyawan" label="nama" :reduce="karyawan => karyawan.id" v-model="meeting.peserta"/>
                    </div>
                    <div class="form-group">
                        <label for="">Lokasi Meeting</label>
                        <input type="text" class="form-control" v-model="meeting.lokasi">
                    </div>
                    <div class="form-group">
                        <label for="">Alasan Perubahan Meeting</label>
                        <textarea class="form-control" v-model="meeting.alasan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>