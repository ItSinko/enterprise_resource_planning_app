<script>
    import UploadFiles from '../../../../components/upload-files.vue'
    export default {
        props: {
            dataUpload: {
                type: Object,
                required: true
            },
        },
        data() {
            return {
                files: [],
            }
        },
        components: {
            UploadFiles
        },
        methods: {
            uploadFiles(files) {
                this.files = files[0]
                console.log("files", files)
            },
        }
    }

</script>
<template>
    <div class="modal fade modalUpload" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ dataUpload.no_pp }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <upload-files @changed="uploadFiles" :max="1"></upload-files>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                    <button type="button" class="btn btn-primary">Kirim</button>
                </div>
            </div>
        </div>
    </div>
</template>
