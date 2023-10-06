<script>
import axios from 'axios';
import Header from '../../components/header.vue';
import UploadFile from '../../components/uploadFile.vue'
import UploadImage from '../../components/uploadImage.vue'
import InputPrice from '../../../emiindo/components/inputprice.vue';
export default {
    components: {
        Header,
        UploadFile,
        UploadImage,
        InputPrice
    },
    data() {
        return {
            title: 'Form Karyawan',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Karyawan',
                    link: '/hr/karyawan'
                },
                {
                    name: this.$route.params.id ? 'Edit Karyawan' : 'Tambah Karyawan',
                    link: '/hr/karyawan/tambah'
                },
            ],
            currentStep: 1,
            divisi: [],
            sekolah: [
                "SD",
                "SMP",
                "SMA",
                "D1",
                "D2",
                "D3",
                "D4",
                "S1",
                "S2",
                "S3"
            ],
            statusKaryawan: [
                'Tetap',
                'Kontrak',
                'Outsourcing',
                'Magang'
            ],
            form: {
                image: null,
                nama_pegawai: null,
                jenis_kelamin: null,
                tempat_lahir: null,
                tanggal_lahir: null,
                email: null,
                nik: null,
                alamat_tinggal_sesuai_ktp: null,
                alamat_tinggal_saat_ini: null,
                nomor_telepon: null,
                tanggal_masuk: null,
                status_pegawai: 1,
                bagian: null,
                kode_akun: null,
                upah_lembur: 0,
                no_rekening: null,
                jabatan: null,
                divisi: null,
                status_karyawan: null,
                durasi_kontrak: null,
                nama_instansi_kontrak: null,
                no_npwp: null,
                no_bpjs_ketenagakerjaan: null,
                no_bpjs_kesehatan: null,
                faskes_kesehatan: null,
                sekolah: null,
                bidang_jurusan: null,
                sekolah_lanjutan: [
                    {
                        sekolah: null,
                        jurusan: null
                    }
                ],
                nama_keluarga: null,
                hubungan_keluarga: null,
                nomor_telepon_keluarga: null,
                status: null,
                nama_suami_istri: null,
                anak: [
                    {
                        nama_anak: null,
                        tempat_lahir: null,
                        tanggal_lahir: null,
                        no_bpjs: null
                    }
                ],
                dokumen_pendukung: [],
            },
        };
    },
    methods: {
        cekForm() {
            // cek validasi
            let valid = true
            if (this.currentStep == 1) {
                const cekStepSatu = [
                    'nama_pegawai',
                    'jenis_kelamin',
                    'tempat_lahir',
                    'tanggal_lahir',
                    'email',
                    'nik',
                    'alamat_tinggal_sesuai_ktp',
                    'alamat_tinggal_saat_ini',
                    'nomor_telepon',
                ]
                cekStepSatu.forEach(item => {
                    if (this.form[item] === null || this.form[item] === '') {
                        valid = false
                    }
                })
                if (!this.validateEmail(this.form.email)) {
                    valid = false
                }
            } else if (this.currentStep == 2) {
                valid = true
                const cekStepDua = [
                    'tanggal_masuk',
                    'jabatan',
                    'bagian',
                    'kode_akun',
                    'divisi',
                    'status_karyawan',
                    'status_pegawai',
                    'durasi_kontrak',
                ]
                cekStepDua.forEach(item => {
                    if (this.form[item] === null || this.form[item] === '') {
                        valid = false
                    }
                })
            }
            else if (this.currentStep == 3) {
                valid = true
                const cekStepTiga = [
                    'faskes_kesehatan',
                ]
                cekStepTiga.forEach(item => {
                    if (this.form[item] === null || this.form[item] === '') {
                        valid = false
                    }
                })
            } else if (this.currentStep == 4) {
                valid = true

                const cekStepEmpat = [
                    'sekolah',
                ]

                cekStepEmpat.forEach(item => {
                    if (this.form[item] === null || this.form[item] === '') {
                        valid = false
                    }
                })
            } else if (this.currentStep == 5) {
                valid = true

                const cekStepKelima = [
                    'nama_keluarga',
                    'hubungan_keluarga',
                    'nomor_telepon_keluarga',
                    'status',
                    'nama_suami_istri',
                ]

                cekStepKelima.forEach(item => {
                    if (this.form[item] === null || this.form[item] === '') {
                        valid = false
                    }
                })
            } else if (this.currentStep == 6) {
                valid = true

                const cekStepEnam = [
                    'image',
                    'dokumen_pendukung'
                ]

                cekStepEnam.forEach(item => {
                    if (this.form[item] === null || this.form[item] === '' || this.form[item].length === 0) {
                        valid = false
                    }
                })
            }
            return valid
        },
        validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        },
        nextStep() {
            if (this.cekForm()) {
                this.currentStep++
            } else {
                this.$swal('Error', 'Cek kembali form anda', 'error')
            }
        },
        previousStep() {
            this.currentStep--
        },
        judul(step) {
            switch (step) {
                case 1:
                    return 'Informasi Pribadi'
                    break;

                case 2:
                    return 'Informasi Pekerjaan'
                    break;

                case 3:
                    return 'Informasi Keuangan'
                    break;

                case 4:
                    return 'Pendidikan'
                    break;

                case 5:
                    return 'Keluarga'
                    break;
                case 6:
                    return 'Dokumen Pendukung'
                    break;
                default:
                    return ''
                    break;
            }
        },
        async getDivisi() {
            const { data } = await axios.get('/api/gbj/sel-divisi')
            this.divisi = data.map(item => {
                return {
                    id: item.id,
                    label: item.nama
                }
            })
        },
        tambahSekolah() {
            if (this.form.sekolah_lanjutan.length < 5) {
                this.form.sekolah_lanjutan.push({
                    sekolah: null,
                    jurusan: null
                })
            }
        },
        tambahAnak() {
            this.form.anak.push({
                nama_anak: null,
                tempat_lahir: null,
                tanggal_lahir: null,
                no_bpjs: null
            })
        },
        simpan() {
            if (this.cekForm()) {
                this.$swal('Berhasil', 'Data berhasil disimpan', 'success')
            } else {
                this.$swal('Error', 'Harap isi semua form', 'error')
            }
        }
    },
    computed: {
        isFirstStep() {
            return this.currentStep === 1
        },
        isLastStep() {
            return this.currentStep === 6 // Replace 3 with the total number of steps
        },
        widthProgress() {
            return 100 / 6 // Replace 3 with the total number of steps
        },
        calculateProgress() {
            return (this.currentStep) * this.widthProgress
        },
    },
    mounted() {
        this.getDivisi()
    },
    watch: {
        // status_karyawan
        'form.status_karyawan': function (val) {
            if (val === 'tetap') {
                delete this.form.durasi_kontrak
                delete this.form.nama_instansi_kontrak
            } else {
                this.form.durasi_kontrak = null
                this.form.nama_instansi_kontrak = null
            }
        },
        'form.no_bpjs_kesehatan': function (val) {
            if (val === null) {
                delete this.form.faskes_kesehatan
            } else {
                this.form.faskes_kesehatan = null
            }
        },
        'form.status': function (val) {
            if (val !== 'menikah') {
                delete this.form.nama_suami_istri
            } else {
                this.form.nama_suami_istri = null
            }
        },
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />

        <div>
            <div class="row">
                <div class="col-md-4">
                    <!-- photo -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Foto Karyawan
                                <span class="text-red">*</span>
                            </h5>

                        </div>
                        <div class="card-body d-flex justify-content-center">
                            <UploadImage :image="form.image" @fileRemoved="form.image = null"
                                @fileSelected="form.image = $event" />
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" :style="{ width: `${calculateProgress}%` }"
                            :aria-valuenow="`${calculateProgress}`" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                {{ currentStep }}. {{ judul(currentStep) }}
                                <span class="text-red" v-if="currentStep === 6">*</span>
                            </h5>
                        </div>
                        <div class="card-body">

                            <div v-if="currentStep === 1">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Nama Pegawai <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" v-model="form.nama_pegawai">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Jenis Kelamin <span class="text-red">*</span></label>
                                            <select class="form-control" v-model="form.jenis_kelamin">
                                                <option selected>Pilih Jenis Kelamin</option>
                                                <option value="laki-laki">Laki-laki</option>
                                                <option value="perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tempat Lahir <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" v-model="form.tempat_lahir">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tanggal Lahir <span class="text-red">*</span></label>
                                            <input type="date" class="form-control" v-model="form.tanggal_lahir">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Email <span class="text-red">*</span></label>
                                            <input type="email" class="form-control" v-model="form.email">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">NIK <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" v-model="form.nik"
                                                @keypress="numberOnly($event)">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Alamat Tinggal Sesuai KTP <span class="text-red">*</span></label>
                                            <textarea cols="5" class="form-control"
                                                v-model="form.alamat_tinggal_sesuai_ktp"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Alamat Tinggal Saat ini <span class="text-red">*</span></label>
                                            <textarea cols="5" class="form-control"
                                                v-model="form.alamat_tinggal_saat_ini"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Nomor Telepon <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" v-model="form.nomor_telepon"
                                                @keypress="numberOnly($event)" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="currentStep === 2">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Tanggal Masuk <span class="text-red">*</span></label>
                                            <input type="date" class="form-control" v-model="form.tanggal_masuk"
                                                placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Status Pegawai <span class="text-red">*</span></label>
                                            <select class="form-control" v-model="form.status_pegawai">
                                                <option selected>Pilih Status Pegawai</option>
                                                <option value="1">Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Bagian <span class="text-red">*</span></label>
                                            <v-select :options="divisi" v-model="form.bagian"
                                                placeholder="Pilih Bagian"></v-select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Kode Akun <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" v-model="form.kode_akun" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Upah Lembur <span class="text-red">*</span></label>
                                            <input-price v-model="form.upah_lembur" :nilai="form.upah_lembur" />
                                        </div>
                                        <div class="form-group">
                                            <label for="">No. Rekening</label>
                                            <input type="text" class="form-control" @keypress="numberOnly($event)"
                                                v-model="form.no_rekening" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Jabatan <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" v-model="form.jabatan" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Divisi <span class="text-red">*</span></label>
                                            <v-select :options="divisi" v-model="form.divisi"
                                                placeholder="Pilih Divisi"></v-select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Status Karyawan <span class="text-red">*</span></label>
                                            <select class="form-control" v-model="form.status_karyawan">
                                                <option selected>Pilih Status Karyawan</option>
                                                <option v-for="status in statusKaryawan" :value="status" :key="status">
                                                    {{ status }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group" v-if="form.status_karyawan !== 'tetap'">
                                            <label for="">Durasi Kontrak <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" v-model="form.durasi_kontrak"
                                                @keypress="numberOnly($event)">
                                        </div>
                                        <div class="form-group" v-if="form.status_karyawan !== 'tetap'">
                                            <label for="">Nama Instansi untuk Kontrak atau Outsourcing</label>
                                            <input type="text" class="form-control" v-model="form.nama_instansi_kontrak">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="currentStep === 3">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">No NPWP</label>
                                            <input type="text" class="form-control" v-model="form.no_npwp"
                                                @keypress="numberOnly($event)">
                                        </div>
                                        <div class="form-group">
                                            <label for="">No BPJS Ketenagakerjaan</label>
                                            <input type="text" class="form-control" v-model="form.no_bpjs_ketenagakerjaan"
                                                @keypress="numberOnly($event)">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">No BPJS Kesehatan</label>
                                            <input type="text" class="form-control" v-model="form.no_bpjs_kesehatan"
                                                @keypress="numberOnly($event)">
                                        </div>
                                        <div class="form-group"
                                            v-if="form.no_bpjs_kesehatan !== null && form.no_bpjs_kesehatan !== ''">
                                            <label for="">Faskes Kesehatan <span class="text-red">*</span></label>
                                            <select class="form-control" v-model="form.faskes_kesehatan">
                                                <option selected>Pilih Faskes Kesehatan</option>
                                                <option v-for="tingkat in 3" :value="tingkat" :key="tingkat">Tingkat {{
                                                    tingkat }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="currentStep === 4">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Sekolah <span class="text-red">*</span></label>
                                            <v-select :options="sekolah" v-model="form.sekolah"
                                                placeholder="Pilih Pendidikan Terakhir"></v-select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Bidang / Jurusan</label>
                                            <input type="text" class="form-control" v-model="form.bidang_jurusan">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="d-flex bd-highlight">
                                                    <div class="p-2 flex-grow-1 bd-highlight">
                                                        <h5>Sekolah Lanjutan</h5>
                                                    </div>
                                                    <div class="p-2 bd-highlight">
                                                        <!-- hanya bisa menambahkan maksimal 5 -->
                                                        <button class="btn btn-primary btn-sm"
                                                            @click="tambahSekolah">Tambah</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="scrollable">
                                                    <div class="row" v-for="(item, index) in form.sekolah_lanjutan"
                                                        :key="index">
                                                        <div class="col-5">
                                                            <div class="form-group">
                                                                <label for="">Sekolah</label>
                                                                <v-select :options="sekolah" v-model="item.sekolah"
                                                                    placeholder="Pilih Pendidikan Terakhir"></v-select>
                                                            </div>
                                                        </div>
                                                        <div class="col-5">
                                                            <div class="form-group">
                                                                <label for="">Jurusan</label>
                                                                <input type="text" class="form-control" placeholder=""
                                                                    v-model="item.jurusan">
                                                            </div>
                                                        </div>
                                                        <div class="col-2 d-flex align-items-center">
                                                            <button class="btn btn-outline-danger mt-3"
                                                                @click="form.sekolah_lanjutan.splice(index, 1)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="currentStep === 5">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Nama Keluarga yang dapat dihubungi <span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" v-model="form.nama_keluarga">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Hubungan Keluarga yang dapat dihubungi <span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" v-model="form.hubungan_keluarga">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Nomor Telepon Keluarga yang dapat dihubungi <span
                                                    class="text-red">*</span></label>
                                            <input type="text" class="form-control" v-model="form.nomor_telepon_keluarga"
                                                @keypress="numberOnly($event)">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Status <span class="text-red">*</span></label>
                                            <select class="form-control" v-model="form.status">
                                                <option selected>Pilih Status</option>
                                                <option value="belum_menikah">Belum Menikah</option>
                                                <option value="menikah">Menikah</option>
                                                <option value="duda" v-if="form.jenis_kelamin === 'laki-laki'">Duda</option>
                                                <option value="janda" v-else>Janda</option>
                                            </select>
                                        </div>
                                        <div class="form-group" v-if="form.status === 'menikah'">
                                            <label for="">Nama
                                                {{ form.jenis_kelamin === 'laki-laki' ? 'Istri' : 'Suami' }}</label>
                                            <span class="text-red">*</span>
                                            <input type="text" class="form-control" v-model="form.nama_suami_istri">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="d-flex bd-highlight">
                                                    <div class="p-2 flex-grow-1 bd-highlight">
                                                        <h5>Anak</h5>
                                                    </div>
                                                    <div class="p-2 bd-highlight">
                                                        <button class="btn btn-primary btn-sm"
                                                            @click="tambahAnak">Tambah</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="scrollable">
                                                    <div class="row" v-for="(item, index) in form.anak" :key="index">
                                                        <div class="col-1 d-flex align-items-center">
                                                            <p>{{ index + 1 }}.</p>
                                                        </div>
                                                        <div class="col-5">
                                                            <div class="form-group">
                                                                <label for="">Nama Anak</label>
                                                                <input type="text" class="form-control"
                                                                    v-model="item.nama_anak" placeholder="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Tempat Lahir</label>
                                                                <input type="text" class="form-control"
                                                                    v-model="item.tempat_lahir" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group">
                                                                <label for="">Tgl Lahir</label>
                                                                <input type="date" class="form-control"
                                                                    v-model="item.tanggal_lahir" placeholder="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">No BPJS</label>
                                                                <input type="text" class="form-control"
                                                                    v-model="item.no_bpjs" placeholder=""
                                                                    @keypress="numberOnly($event)">
                                                            </div>
                                                        </div>
                                                        <div class="col-2 d-flex align-items-center">
                                                            <button class="btn btn-sm btn-outline-danger mt-10"
                                                                @click="form.anak.splice(index, 1)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="currentStep === 6">
                                <UploadFile :uploaded="form.dokumen_pendukung" @changed="form.dokumen_pendukung = $event" />
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight">
                                    <p class="text-muted">
                                        <span class="text-red">*</span>
                                        Wajib diisi
                                    </p>
                                </div>
                                <div class="p-2 bd-highlight">
                                    <button class="btn btn-info" v-if="!isFirstStep" @click="previousStep">
                                        Sebelumnya
                                    </button>

                                    <button class="btn btn-primary ml-3" v-if="!isLastStep" @click="nextStep">
                                        Selanjutnya
                                    </button>

                                    <button class="btn btn-success ml-3" v-if="isLastStep" @click="simpan">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.rounded-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
}

.scrollable {
    overflow-y: auto;
    max-height: 300px;
    height: 200px;
}
</style>