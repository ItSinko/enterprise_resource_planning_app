<script>
export default {
    props: {
        form: {
            type: Object,
            default: () => {
                return {
                    tanggal_permintaan: '',
                    tujuan: 'perakitan',
                    no_referensi: '',
                    tanggal_peminjaman: '',
                    tanggal_pengembalian: '',
                }
            }
        }
    },
    data() {
        return {
            selectTujuan: [
                'perakitan',
                'peminjaman',
            ]
        }
    },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Info Permintaan</h4>
            <div class="card-text">
                <div class="form-group row">
                    <div class="col-5 text-right">Tanggal Permintaan</div>
                    <div class="col-6"><input type="date" v-model="form.tanggal_permintaan" class="form-control"></div>
                </div>
                <div class="form-group row">
                    <div class="col-5 text-right">Tujuan</div>
                    <div class="col-6">
                        <v-select v-model="form.tujuan" :options="selectTujuan" />
                    </div>
                </div>
                <div class="form-group row" v-if="form.tujuan === 'perakitan'">
                    <div class="col-5 text-right">No Referensi</div>
                    <div class="col-6">
                        <v-select v-model="form.no_referensi"></v-select>
                    </div>
                </div>
                <div v-else>
                    <div class="form-group row">
                    <div class="col-5 text-right">Tanggal Peminjaman</div>
                    <div class="col-6"><input type="date" v-model="form.tanggal_peminjaman" class="form-control"></div>
                </div>
                <div class="form-group row">
                    <div class="col-5 text-right">Tanggal Pengembalian</div>
                    <div class="col-6"><input type="date" v-model="form.tanggal_pengembalian" class="form-control"></div>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>