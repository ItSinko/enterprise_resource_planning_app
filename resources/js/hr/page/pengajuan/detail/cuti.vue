<script>
export default {
    props: ['item'],
    methods: {
        jenisCuti(jenis) {
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
                    <label for="">Jenis Permohonan Cuti</label> <br>
                    <p v-if="item.jenisCuti != 'lain-lain'">{{ jenisCuti(item.jenisCuti) }}</p>
                    <p v-if="item.jenisCuti == 'lain-lain'">{{ jenisCuti(item.jenisCutiLain) }}</p>
                </div>

                <div class="form-group">
                    <label for="">Tanggal Tidak Masuk Kerja</label>
                    <div class="row">
                        <div class="col">
                            <input type="date" class="form-control" v-model="item.tanggalAwal"  placeholder="Tanggal Awal" disabled>
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" v-model="item.tanggalAkhir" disabled
                                placeholder="Tanggal Akhir">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="">Jumlah Hari Tidak Masuk Kerja</label>
                    <input type="text" class="form-control" v-model="item.jumlahHari" disabled>
                </div>

                <div class="form-group">
                    <label for="">Pelimpahan Tugas Kepada</label>
                    <v-select v-model="item.pelimpahan" disabled></v-select>
                </div>
            </div>
        </div>
    </div>
</template>