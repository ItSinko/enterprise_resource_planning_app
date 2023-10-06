<script>
export default {
    data() {
        return {
            
        }
    },
    methods: {
        closeModal() {
            $('.modalPegawai').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            })
        },
        simpan() {
            this.$swal('Berhasil!', 'Data berhasil disimpan.', 'success');
        }
    },
}
</script>
<template>
    <div class="modal fade modalPegawai" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Resign Karyawan</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="">Tanggal Keluar</label>
                      <input type="date" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>