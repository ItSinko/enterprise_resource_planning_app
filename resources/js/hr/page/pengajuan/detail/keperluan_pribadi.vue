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
                    <label for="">Jenis Permohonan Izin</label> <br>
                    <p>{{ jenisKeperluan(item.jenisIzin) }}</p>
                </div>

                <div class="form-group" v-if="item.jenisIzin">
                    <label for="">Waktu</label>
                    <p v-if="item.jenisIzin == 'pulang'">{{ item.waktu }}</p>
                    <p v-else>
                        {{ item.waktuAwal }} - {{ item.waktuAkhir }}
                    </p>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="">Alasan</label>
                    <textarea class="form-control" id="" rows="4" v-model="item.alasan" disabled></textarea>
                </div>
            </div>
        </div>
    </div>
</template>