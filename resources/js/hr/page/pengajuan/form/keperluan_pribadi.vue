<script>
import axios from 'axios';
export default {
    data() {
        return {
            izinKeperluanPribadi: {
                jenis: 'Izin Keperluan Pribadi',
                jenisIzin: '',
                alasan: '',
            },
            karyawan: [],
            hourRangeAkhir: [],
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
            const cekIsNull = Object.values(this.izinKeperluanPribadi).every(x => (x !== null && x !== ''));
            if (cekIsNull) {
                this.$swal('Berhasil', 'Data berhasil disimpan', 'success');
            } else {
                this.$swal('Gagal', 'Data gagal disimpan', 'error');
            }
        },
        draft() {
            this.$swal('Berhasil', 'Data berhasil disimpan sebagai draft', 'success');
        },
        calculateHourAkhir() {
            if(this.izinKeperluanPribadi.waktuAwal !== '') {
                const waktu_awal = this.izinKeperluanPribadi.waktuAwal.split(':');
                const hour = []
                for (let i = waktu_awal[0]; i <= 23; i++) {
                    hour.push(i)
                }
                this.hourRangeAkhir = hour
            }else{
                this.hourRangeAkhir = []
            }
        }
    },
    mounted() {
        this.getKaryawan();
    },
    watch: {
        'izinKeperluanPribadi.jenisIzin': function (val) {
            if (val === 'pulang') {
                this.izinKeperluanPribadi.waktu = '';
                delete this.izinKeperluanPribadi.waktuAwal;
                delete this.izinKeperluanPribadi.waktuAkhir;
            } else {
                this.izinKeperluanPribadi.waktuAwal = '';
                this.izinKeperluanPribadi.waktuAkhir = '';
                delete this.izinKeperluanPribadi.waktu;
            }
        },
    },
}
</script>
<template>
    <div class="p-3">
        <h3 class="text-center">{{ izinKeperluanPribadi.jenis }}</h3>
        <hr>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="">Jenis Permohonan Izin</label> <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1"
                            value="pulang" v-model="izinKeperluanPribadi.jenisIzin">
                        <label class="form-check-label" for="inlineRadio1">
                            Pulang Lebih Awal
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2"
                            value="keluar" v-model="izinKeperluanPribadi.jenisIzin">
                        <label class="form-check-label" for="inlineRadio2">
                            Keluar Kantor
                        </label>
                    </div>
                </div>

                <div class="form-group" v-if="izinKeperluanPribadi.jenisIzin">
                    <label for="">Waktu</label>
                    <vue-timepicker v-model="izinKeperluanPribadi.waktu" input-width="100%" placeholder="Waktu"
                        autocomplete="on" v-if="izinKeperluanPribadi.jenisIzin == 'pulang'" />

                    <div class="row" v-else>
                        <div class="col">
                            <vue-timepicker v-model="izinKeperluanPribadi.waktuAwal" input-width="100%"
                                placeholder="Waktu Awal" autocomplete="on" @input="calculateHourAkhir"></vue-timepicker>
                        </div>
                        <div class="col">
                            <vue-timepicker v-model="izinKeperluanPribadi.waktuAkhir" input-width="100%"
                                placeholder="Waktu Akhir" autocomplete="on"
                                :hour-range="hourRangeAkhir"
                                ></vue-timepicker>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="">Alasan</label>
                    <textarea class="form-control" id="" rows="4" v-model="izinKeperluanPribadi.alasan"></textarea>
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