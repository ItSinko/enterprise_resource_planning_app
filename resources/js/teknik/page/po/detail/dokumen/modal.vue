<script>
import UploadFiles from '../../../../components/upload-files.vue'
export default {
    components: {
        UploadFiles
    },
    props: {
        header: {
            type: String,
            required: true
        },
    },
    data(){
        return {
            files: []
        }
    },
    methods: {
        uploadFiles(files){
            this.files = files[0]
        }
    }
}
</script>
<template>
    <div class="modal fade modalUploadDokumen" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ header }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <upload-files @changed="uploadFiles"></upload-files>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>