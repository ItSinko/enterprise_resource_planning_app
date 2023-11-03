<script>
export default {
    data() {
        return {
            noseri: ''
        }
    },
    methods: {
        submit() {
            this.$emit('submit', this.noseri)
            this.close()
        },
        close() {
            this.$emit('close')
            $('.modalChecked').modal('hide')
            this.$nextTick(() => {
                this.noseri = ''
            })
        }
    },
}
</script>
<template>
    <div class="modal fade modalChecked" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="">Masukkan Nomor Seri</label>
                    <textarea class="form-control" v-model="noseri" cols="30" rows="10"></textarea>
                    <div class="text-muted">
                        <small>
                            <ul>
                                <li>Gunakan koma (,) atau enter untuk memisahkan nomor seri</li>
                                <li>Contoh: 123456, 123457, 123458</li>
                            </ul>
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="close">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="submit">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>