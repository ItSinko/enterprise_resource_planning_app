<script>
import axios from 'axios'
import VueSelect from 'vue-select'
export default {
    components: {
        VueSelect
    },
    data() {
        return {
            karyawan: [],
            meeting: {
                judul: '',
                deskripsi: '',
                tanggal: '',
                mulai: '',
                selesai: '',
                jumlah_peserta: '',
                lokasi: '',
                peserta: [],
            }
        }
    },
    methods: {
        closeModal() {
            $('.modalMeetingBaru').modal('hide');
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
        async getDataKaryawan() {
            try {
                const response = await axios.get('/api/karyawan_all')
                this.karyawan = response.data
            } catch (error) {
                console.log(error)
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
    <div class="modal fade modalMeetingBaru" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jadwal Meeting Baru</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Judul Meeting</label>
                        <input type="text" class="form-control" v-model="meeting.judul">
                    </div>
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
                            <input type="time" class="form-control" v-model="meeting.mulai">
                        </div>
                        -
                        <div class="col-sm-4">
                            <input type="time" class="form-control" v-model="meeting.selesai">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Jumlah Peserta Meeting</label>
                        <input type="text" class="form-control" @keypress="inputNumberOnly" v-model.number="meeting.jumlah_peserta">
                    </div>
                    <div class="form-group">
                        <label for="">Lokasi Meeting</label>
                        <input type="text" class="form-control" v-model="meeting.lokasi">
                    </div>
                    <div class="form-group">
                        <label for="">Peserta Meeting</label>
                        <vue-select multiple :options="karyawan" label="nama" :reduce="karyawan => karyawan.id" v-model="meeting.peserta"/>
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