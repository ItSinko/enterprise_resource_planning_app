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
                    <div class="form-check form-check-inline col-form-label">
                        <input class="form-check-input" type="radio"
                            name="pilih_jenis_retur"
                            value="peminjaman"
                            v-model="form.jenis_retur"
                            />
                        <label class="form-check-label"
                            for="pilih_jenis_retur1">Peminjaman</label>
                    </div>
                    <div class="form-check form-check-inline col-form-label">
                        <input class="form-check-input" type="radio"
                            name="pilih_jenis_retur"
                            v-model="form.jenis_retur"
                            value="komplain" />
                        <label class="form-check-label"
                            for="pilih_jenis_retur2">Komplain</label>
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