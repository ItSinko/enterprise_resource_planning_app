<script>
import axios from 'axios';
export default {
    data() {
        return {
            permohonanCuti: {
                jenis: 'Permohonan Cuti',
                jenisCuti: '',
                tanggalAwal: '',
                tanggalAkhir: '',
                jumlahHari: '',
                pelimpahan: '',
            },
            karyawan: []
        }
    },
    methods: {
        async getKaryawan() {
            try {
                const { data } = await axios.get('/api/karyawan_all');
                this.karyawan = data.map((item) => {
                    return {
                        label: item.nama,
                        value: item.id,
                    }
                });
            } catch (error) {
                console.log(error);
            }
        },
        batal() {
            this.$router.push({ name: 'form-pengajuan' });
        },

        simpan() {
            const cekIsNull = Object.values(this.permohonanCuti).every(x => (x !== null && x !== ''));

            if (cekIsNull) {
                this.$swal('Berhasil', 'Data berhasil disimpan', 'success');
            } else {
                this.$swal('Gagal', 'Data gagal disimpan', 'error');
            }
        },

        draft() {
            this.$swal('Berhasil', 'Data berhasil disimpan sebagai draft', 'success');
        },
    },
    watch: {
        'permohonanCuti.tanggalAwal': function (val) {
            this.permohonanCuti.tanggalAkhir = val;
        },
        'permohonanCuti.tanggalAkhir': function (val) {
            const date1 = new Date(this.permohonanCuti.tanggalAwal);
            const date2 = new Date(val);

            // Fungsi untuk memeriksa apakah tanggal berada di hari Sabtu atau Minggu
            const isWeekend = function (date) {
                const day = date.getDay();
                return day === 0 || day === 6;
            };

            const timeDiff = Math.abs(date2.getTime() - date1.getTime());
            const diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;

            // Menghitung selisih hari tanpa menghitung hari Sabtu dan Minggu
            let diff = 0;
            for (let i = 0; i < diffDays; i++) {
                const currentDate = new Date(date1);
                currentDate.setDate(date1.getDate() + i);

                if (!isWeekend(currentDate)) {
                    diff++;
                }
            }

            this.permohonanCuti.jumlahHari = diff;
        },
        'permohonanCuti.jenisCuti': function (val) {
            if (val == 'lain-lain') {
                this.permohonanCuti.jenisCutiLain = '';
            } else {
                delete this.permohonanCuti.jenisCutiLain;
            }
        }
    },
    computed: {
        minimumCuti() {
            // pengajuan cuti minimal 7 hari sebelum tanggal cuti
            const date = new Date();
            date.setDate(date.getDate() + 7);
            return date.toISOString().slice(0, 10);
        }
    },
    mounted() {
        this.getKaryawan();
    }
}
</script>
<template>
    <div class="p-3">
        <h3 class="text-center">{{ permohonanCuti.jenis }}</h3>
        <hr>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="">Jenis Permohonan Cuti</label> <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1"
                            value="tahunan" v-model="permohonanCuti.jenisCuti">
                        <label class="form-check-label" for="inlineRadio1">
                            Tahunan
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2"
                            value="menikah" v-model="permohonanCuti.jenisCuti">
                        <label class="form-check-label" for="inlineRadio2">
                            Menikah
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3"
                            value="melahirkan" v-model="permohonanCuti.jenisCuti">
                        <label class="form-check-label" for="inlineRadio3">
                            Melahirkan
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio4"
                            value="lain-lain" v-model="permohonanCuti.jenisCuti">
                        <label class="form-check-label" for="inlineRadio4">
                            Lain - Lain
                        </label>
                    </div>
                    <input type="text" v-model="permohonanCuti.jenisCutiLain" class="form-control"
                        v-if="permohonanCuti.jenisCuti == 'lain-lain'" placeholder="Silahkan isi jenis cuti lainnya">
                </div>

                <div class="form-group">
                    <label for="">Tanggal Tidak Masuk Kerja</label>
                    <div class="row">
                        <div class="col">
                            <input type="date" class="form-control" v-model="permohonanCuti.tanggalAwal"
                                :min="minimumCuti"
                                :max="permohonanCuti.tanggalAkhir"
                                placeholder="Tanggal Awal">
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" v-model="permohonanCuti.tanggalAkhir"
                                :min="permohonanCuti.tanggalAwal"
                                placeholder="Tanggal Akhir">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="">Jumlah Hari Tidak Masuk Kerja</label>
                    <input type="text" class="form-control" v-model="permohonanCuti.jumlahHari" disabled>
                </div>

                <div class="form-group">
                    <label for="">Pelimpahan Tugas Kepada</label>
                    <v-select v-model="permohonanCuti.pelimpahan" :options="karyawan"></v-select>
                </div>
            </div>
        </div>
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <button class="btn btn-danger" @click="batal">Batal</button>
            </div>
            <div class="p-2 bd-highlight">
                <button class="btn btn-secondary" @click="draft">Draft</button>
                <button class="btn btn-primary" @click="simpan">Simpan</button>
            </div>
        </div>
    </div>
</template>