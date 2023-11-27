<script>
export default {
    props: ['item'],
}
</script>
<template>
    <div class="p-3">
        <h3 class="text-center">
            {{ item.jenis }}
        </h3>
        <hr>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="">Waktu Sampai</label>
                    <p>{{ item.waktuSampai }}</p>
                </div>
                <div class="form-group">
                    <label for="">Keterlambatan Ke:</label>
                    <input type="text" class="form-control" value="1" disabled>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="">Alasan</label>
                    <textarea class="form-control" v-model="item.alasan" rows="5" disabled></textarea>
                </div>
            </div>
        </div>
    </div>
</template>