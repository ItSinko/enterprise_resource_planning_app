<script>
import axios from 'axios';
export default {
    data() {
        return {
            permohonanKeperluanKantor: {
                jenis: 'Izin Keperluan Kantor',
                jenisIzin: '',
                tanggalIzin: '',
                waktuAwal: '',
                waktuAkhir: '',
                jumlahHari: '',
                keterangan: '',
                pelimpahan: '',
            },
            karyawan: [],
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
            const cekIsNull = Object.values(this.permohonanKeperluanKantor).every(x => (x !== null && x !== ''));
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
    mounted() {
        this.getKaryawan();
    },
    computed: {
        hourRangeAkhir () {
            if (this.permohonanKeperluanKantor.waktuAwal !== '') {
                const waktu_awal = this.permohonanKeperluanKantor.waktuAwal.split(':');
                const hour = []
                for (let i = waktu_awal[0]; i <= 23; i++) {
                    hour.push(i)
                }
                return hour
            } else {
                return []
            }
        },
    }
}
</script>
<template>
    <div class="p-3">
        <h3 class="text-center">{{ permohonanKeperluanKantor.jenis }}</h3>
        <hr>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="">Jenis Keperluan Izin</label> <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1"
                            value="izin" v-model="permohonanKeperluanKantor.jenisIzin">
                        <label class="form-check-label" for="inlineRadio1">
                            Izin
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2"
                            value="terlambat" v-model="permohonanKeperluanKantor.jenisIzin">
                        <label class="form-check-label" for="inlineRadio2">
                            Datang Terlambat
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Tanggal Izin</label>
                    <input type="date" class="form-control" v-model="permohonanKeperluanKantor.tanggalIzin">
                </div>

                <div class="form-group">
                    <label for="">Waktu Izin</label>
                    <div class="row">
                        <div class="col">
                            <vue-timepicker v-model="permohonanKeperluanKantor.waktuAwal" input-width="100%"
                                placeholder="Waktu Awal" autocomplete="on"
                                
                                ></vue-timepicker>
                        </div>
                        <div class="col">
                            <vue-timepicker v-model="permohonanKeperluanKantor.waktuAkhir" input-width="100%"
                                placeholder="Waktu Akhir" autocomplete="on"
                                :hour-range="hourRangeAkhir"
                                ></vue-timepicker>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="">Alasan Izin</label>
                    <textarea class="form-control" v-model="permohonanKeperluanKantor.keterangan" rows="7"></textarea>
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