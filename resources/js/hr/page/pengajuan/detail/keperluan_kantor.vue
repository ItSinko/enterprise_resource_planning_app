<script>
export default {
    props: ['item'],
    methods: {
        jenisKeperluan(jenis) {
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
                    <label for="">Jenis Keperluan Izin</label> <br>
                    <p>{{ jenisKeperluan(item.jenisIzin) }}</p>
                </div>

                <div class="form-group">
                    <label for="">Tanggal Izin</label>
                    <input type="date" class="form-control" v-model="item.tanggalIzin" disabled>
                </div>

                <div class="form-group">
                    <label for="">Waktu Izin</label>
                    <p>
                        {{ item.waktuAwal }} - {{ item.waktuAkhir }}
                    </p>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="">Alasan Izin</label>
                    <textarea class="form-control" v-model="item.keterangan" rows="7" disabled></textarea>
                </div>
            </div>
        </div>
    </div>
</template>