<script>
export default {
    props: ['formhasilmeeting'],
    methods: {
        save() {
            this.$emit('save')
            this.closeModal()
        },
        closeModal() {
            this.$nextTick(() => {
                $('.modalHasilMeeting').modal('hide')
            })
            this.$emit('closeModal')
        }
    }
}
</script>
<template>
    <div class="modal fade modalHasilMeeting" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Hasil Meeting</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="">Hasil Rapat</label>
                      <textarea class="form-control" v-model="formhasilmeeting.isi"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="save">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>