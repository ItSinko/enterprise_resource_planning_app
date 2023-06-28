<script>
export default {
    props: ['form']
}
</script>
<template>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <div class="card-title">
                Informasi Transaksi
            </div>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label class="col-lg-5 col-md-12 col-form-label text-right">Tanggal Retur</label>
                <div class="col-lg-2 col-md-6">
                    <input type="date" class="form-control" v-model="form.tgl_retur">
                </div>
            </div>
            <div class="form-group row">
                <label for="pilih_jenis_retur"
                    class="col-lg-5 col-md-12 col-form-label labelket text-right">Jenis Retur</label>
                <div class="col-lg-4 col-md-12 ">
                    <slot name="jenisretur"></slot>
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-lg-5 col-md-12 col-form-label labelket text-right">Garansi</label>
                <div class="col-lg-4 col md-12">
                    <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" class="custom-control-input" id="customSwitch1" v-model="form.garansi">
                        <label class="custom-control-label" for="customSwitch1">{{ form.garansi ? 'Ya' : 'Tidak' }}</label>
                    </div>
                </div>
            </div>
            <div class="form-group row" >
                <label for="keterangan"
                    class="col-lg-5 col-md-12 col-form-label text-right">Keterangan
                    Retur</label>
                <div class="col-lg-4 col-md-12">
                    <textarea name="keterangan" 
                        v-model="form.keterangan"
                        class="form-control col-form-label "></textarea>
                </div>
            </div>
        </div>
    </div>
</template>