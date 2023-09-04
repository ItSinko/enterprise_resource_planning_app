<script>
export default {
    methods: {
        save() {
            this.$emit('save')
            this.closeModal()
        },
        closeModal() {
            this.$nextTick(() => {
                $('.modalDokumenPendukung').modal('hide')
            })
            this.$emit('closeModal')
        }
    },
}
</script>
<template>
    <div class="modal fade modalDokumenPendukung" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dokumen Pendukung</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>