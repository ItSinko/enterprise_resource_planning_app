<script>
export default {
    data() {
        return {
            alasan: '',
        }
    },
    methods: {
        closeModal() {
            $('.modalBatal').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
    },
}
</script>
<template>
    <div class="modal fade modalBatal" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Batal Meeting</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" v-model="alasan" placeholder="Alasan Pembatalan"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>