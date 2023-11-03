<script>
import barcode from 'vue-barcode';
export default {
    props: ['hasilGenerate'],
    components: {
        barcode,
    },
    methods: {
        closeModal() {
            $('.modalSeri').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            });
        }
    },
}
</script>
<template>
    <div class="modal fade modalSeri" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nomor Seri</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- generate seri with barcode -->
                    <span class="company">Elitech</span>
                    <barcode :text="hasilGenerate">
                        Show this if the rendering fails.
                    </barcode>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.company {
    font-family: monospace;
    font-size: 20px;
    margin-bottom: -5px;
}
</style>