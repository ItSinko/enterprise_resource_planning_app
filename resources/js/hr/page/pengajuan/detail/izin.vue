<script>
export default {
    props: ['item'],
    methods: {
        jenisIzin(jenis) {
            // text capitalize
            return jenis.replace(/(?:^|\s)\S/g, function (a) { return a.toUpperCase(); });
        }
    },
}   
</script>
<template>
    <div class="p-3">
        <h3 class="text-center">{{ item.jenis }}</h3>
        <hr>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="">Jenis Permohonan Izin</label> <br>
                    <p>{{ jenisIzin(item.jenisIzin) }}</p>
                </div>
                <div class="form-group">
                    <label for="">Tanggal Tidak Masuk Kerja</label>
                    <div class="row">
                        <div class="col">
                            <input type="date" class="form-control" v-model="item.tanggalAwal" disabled
                                :max="item.tanggalAkhir">
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" v-model="item.tanggalAkhir" disabled
                                :min="item.tanggalAwal">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Pelimpahan Tugas Kepada</label>
                    <v-select v-model="item.pelimpahan" :options="karyawan" disabled></v-select>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="">Jumlah Hari Tidak Masuk Kerja</label>
                    <input type="text" class="form-control" v-model="item.jumlahHari" disabled>
                </div>
                <div class="form-group">
                    <label for="">Keterangan</label>
                    <textarea class="form-control" v-model="item.keterangan" rows="5" disabled></textarea>
                </div>
            </div>
        </div>
    </div>
</template>