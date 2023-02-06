<script>
    export default {
        props: {
            url: {
                type: String,
                required: true
            },
            filename: {
                type: Array,
                required: true,
                default: () => []
            }
        },
        methods: {
            downloadFile(file) {
                window.open(this.url + file, '_blank');
            },
            showModalUpload(jenis) {
                this.$emit('show-modal-upload', jenis)
            }
        },
        computed: {
            dokumensByJenis() {
                let dokumensByJenis = {}
                this.filename.forEach(dokumen => {
                    if (dokumensByJenis[dokumen.jenis_dokumen] === undefined) {
                        dokumensByJenis[dokumen.jenis_dokumen] = []
                    }
                    dokumensByJenis[dokumen.jenis_dokumen].push(dokumen)
                })
                return dokumensByJenis
            },
        }
    }

</script>
<template>
    <div>
        <div class="card" v-for="(documents, jenis) in dokumensByJenis" :key="jenis">
            <img class="card-img-top" src="holder.js/100x180/" alt="">
            <div class="card-body">
                <h4 class="card-title text-bold pb-2">{{ jenis }}</h4>
                <div class="card-text">
                    <div class="row">
                        <div class="col-3 d-flex align-self-stretch" v-for="(file, idx) in documents" :key="idx">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-text">
                                        <p class="text-center text-secondary">{{ file.nama_dokumen }}</p>
                                        <div class="d-flex justify-content-center">
                                            <i class="fa fa-download py-2" aria-hidden="true"></i>
                                        </div>
                                        <a href="#" @click="downloadFile(file.file)">{{ file.file }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3 d-flex align-self-stretch" v-if="documents.at(-1)">
                            <div class="card-body">
                                <div class="d-flex align-items-center bd-highlight mt-4">
                                
                                <button class="btn btn-sm btn-warning bd-highlight" @click="showModalUpload(jenis)">
                                    <i class="fa fa-upload" aria-hidden="true"></i>
                                    Upload
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
