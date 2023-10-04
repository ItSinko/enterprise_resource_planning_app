<script>
import axios from 'axios';
import Header from '../../components/header.vue';
import UploadFile from '../../components/uploadFile.vue'
import UploadImage from '../../components/uploadImage.vue'
export default {
    components: {
        Header,
        UploadFile,
        UploadImage
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
                upah_lembur: null,
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
                dokumen_pendukung: [
                    {
                        nama_dokumen: null,
                        file: null
                    }
                ]
            }
        };
    },
    methods: {
        nextStep() {
            this.currentStep++
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
                    label: item.nama_divisi
                }
            })
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
                        <div class="card-body d-flex justify-content-center">
                            <UploadImage :image="image" @fileRemoved="image = null" @fileSelected="image = $event" />
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
                            </h5>
                        </div>
                        <div class="card-body">

                            <div v-if="currentStep === 1">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Nama Pegawai</label>
                                            <input type="text" class="form-control" v-model="form.nama_pegawai"
                                                placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Jenis Kelamin</label>
                                            <select class="form-control" v-model="form.jenis_kelamin">
                                                <option selected>Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki">Laki-laki</option>
                                                <option value="Perempuan">Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tempat Lahir</label>
                                            <input type="text" class="form-control" v-model="form.tempat_lahir"
                                                placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tanggal Lahir</label>
                                            <input type="date" class="form-control" v-model="form.tanggal_lahir"
                                                placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Email</label>
                                            <input type="email" class="form-control" v-model="form.email" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">NIK</label>
                                            <input type="text" class="form-control" v-model="form.nik" placeholder=""
                                                @keypress="numberOnly($event)">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Alamat Tinggal Sesuai KTP</label>
                                            <input type="text" class="form-control" v-model="form.alamat_tinggal_sesuai_ktp"
                                                placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Alamat Tinggal Saat ini</label>
                                            <input type="text" class="form-control" v-model="form.alamat_tinggal_saat_ini"
                                                placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Nomor Telepon</label>
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
                                            <label for="">Tanggal Masuk</label>
                                            <input type="date" class="form-control" v-model="form.tanggal_masuk"
                                                placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Status Pegawai</label>
                                            <select class="form-control" v-model="form.status_pegawai">
                                                <option selected>Pilih Status Pegawai</option>
                                                <option value="1">Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Bagian</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Kode Akun</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Upah Lembur</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">No. Rekening</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Jabatan</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Divisi</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Status Karyawan</label>
                                            <select class="form-control">
                                                <option selected>Pilih Status Karyawan</option>
                                                <option value="Tetap">Tetap</option>
                                                <option value="Kontrak">Kontrak</option>
                                                <option value="Outsourcing">Outsourcing</option>
                                                <option value="Magang">Magang</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Durasi Kontrak</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Nama Instansi untuk Kontrak atau Outsourcing</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="currentStep === 3">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">No NPWP</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">No BPJS Ketenagakerjaan</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">No BPJS Kesehatan</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Faskes Kesehatan</label>
                                            <select class="form-control">
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
                                            <label for="">Sekolah</label>
                                            <select class="form-control">
                                                <option selected>Pilih Pendidikan Terakhir</option>
                                                <option value="SD">SD</option>
                                                <option value="SMP">SMP</option>
                                                <option value="SMA">SMA</option>
                                                <option value="D1">D1</option>
                                                <option value="D2">D2</option>
                                                <option value="D3">D3</option>
                                                <option value="D4">D4</option>
                                                <option value="S1">S1</option>
                                                <option value="S2">S2</option>
                                                <option value="S3">S3</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Bidang / Jurusan</label>
                                            <input type="text" class="form-control" placeholder="">
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
                                                        <button class="btn btn-primary btn-sm">Tambah</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <div class="form-group">
                                                            <label for="">Sekolah</label>
                                                            <select class="form-control">
                                                                <option selected>Pilih Pendidikan Terakhir</option>
                                                                <option value="SD">SD</option>
                                                                <option value="SMP">SMP</option>
                                                                <option value="SMA">SMA</option>
                                                                <option value="D1">D1</option>
                                                                <option value="D2">D2</option>
                                                                <option value="D3">D3</option>
                                                                <option value="D4">D4</option>
                                                                <option value="S1">S1</option>
                                                                <option value="S2">S2</option>
                                                                <option value="S3">S3</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-5">
                                                        <div class="form-group">
                                                            <label for="">Jurusan</label>
                                                            <input type="text" class="form-control" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-2 d-flex align-items-center">
                                                        <button class="btn btn-outline-danger mt-3">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
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
                                            <label for="">Nama Keluarga yang dapat dihubungi</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Hubungan Keluarga yang dapat dihubungi</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Nomor Telepon Keluarga yang dapat dihubungi</label>
                                            <input type="text" class="form-control" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Status</label>
                                            <select class="form-control">
                                                <option selected>Pilih Status</option>
                                                <option value="belum_menikah">Belum Menikah</option>
                                                <option value="menikah">Menikah</option>
                                                <option value="duda">Duda</option>
                                                <option value="janda">Janda</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Nama Suami / Istri</label>
                                            <input type="text" class="form-control" placeholder="">
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
                                                        <!-- hanya bisa menambahkan maksimal 5 -->
                                                        <button class="btn btn-primary btn-sm">Tambah</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <div class="form-group">
                                                            <label for="">Nama Anak</label>
                                                            <input type="text" class="form-control" placeholder="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">Tempat Lahir</label>
                                                            <input type="text" class="form-control" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-5">
                                                        <div class="form-group">
                                                            <label for="">Tgl Lahir</label>
                                                            <input type="date" class="form-control" placeholder="">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="">No BPJS</label>
                                                            <input type="text" class="form-control" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-2 d-flex align-items-center">
                                                        <button class="btn btn-sm btn-outline-danger mt-10">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="currentStep === 6">
                                <UploadFile />
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="d-flex flex-row-reverse bd-highlight">
                                <div class="p-2 bd-highlight">
                                    <button class="btn btn-primary" v-if="!isFirstStep" @click="previousStep">
                                        Sebelumnya
                                    </button>

                                    <button class="btn btn-primary ml-3" v-if="!isLastStep" @click="nextStep">
                                        Selanjutnya
                                    </button>

                                    <button class="btn btn-success ml-3" v-if="isLastStep">
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
</style>