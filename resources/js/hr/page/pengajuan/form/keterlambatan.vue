<script>
import axios from 'axios';
export default {
    data() {
        return {
            permohonanKeterlambatan: {
                jenis: 'Izin Keterlambatan',
                waktuSampai: '',
                alasan: '',
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
            const cekIsNull = Object.values(this.permohonanKeterlambatan).every(x => (x !== null && x !== ''));
            if (cekIsNull) {
                this.$swal('Berhasil', 'Data berhasil disimpan', 'success');
            } else {
                this.$swal('Gagal', 'Data gagal disimpan', 'error');
            }
        },
        draft() {
            this.$swal('Berhasil', 'Data berhasil disimpan sebagai draft', 'success');
        }
    },
}
</script>
<template>
    <div class="p-3">
        <h3 class="text-center">
            {{ permohonanKeterlambatan.jenis }}
        </h3>
        <hr>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="">Waktu Sampai</label>
                    <vue-timepicker v-model="permohonanKeterlambatan.waktuSampai" input-width="100%" autocomplete="on" />
                </div>
                <div class="form-group">
                    <label for="">Keterlambatan Ke:</label>
                    <input type="text" class="form-control" value="1" disabled>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="">Alasan</label>
                    <textarea class="form-control" v-model="permohonanKeterlambatan.alasan" rows="5"></textarea>
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