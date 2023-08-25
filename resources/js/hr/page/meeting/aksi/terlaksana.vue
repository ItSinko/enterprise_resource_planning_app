<script>
import dokumentasi from './dokumentasi.vue'
export default {
    components: {
        dokumentasi,
    },
    props: ['meeting'],
    data() {
        return {
            notulensi: '',
            dokumentasi: {
                foto: [],
                video: [],
                rekaman: [],
                lainnya: [],
            },
        }
    },
    methods: {
        closeModal(){
            this.$nextTick(() => {
                $('.modalterlaksana').modal('hide')
            })
            this.$emit('closeModal')
        }
    },
}
</script>
<template>
    <div class="modal fade modalterlaksana" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ meeting.nama }}</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" v-model="meeting.tanggal">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mulai" class="col-sm-2 col-form-label">Jam</label>
                        <div class="col-sm-4">
                            <input type="time" class="form-control" v-model="meeting.mulai">
                        </div>
                        -
                        <div class="col-sm-4">
                            <input type="time" class="form-control" v-model="meeting.selesai">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Hasil Notulen</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="10" v-model="notulensi"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label">Dokumentasi</label>
                        <div class="col-sm-10">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="pills-foto-tab" data-toggle="pill" data-target="#pills-foto" type="button" role="tab" aria-controls="pills-foto" aria-selected="true">Foto</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-video-tab" data-toggle="pill" data-target="#pills-video" type="button" role="tab" aria-controls="pills-video" aria-selected="false">Video</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-rekaman-tab" data-toggle="pill" data-target="#pills-rekaman" type="button" role="tab" aria-controls="pills-rekaman" aria-selected="false">Rekaman Suara</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="pills-lainnya-tab" data-toggle="pill" data-target="#pills-lainnya" type="button" role="tab" aria-controls="pills-rekaman" aria-selected="false">Dokumen Lainnya</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-foto" role="tabpanel" aria-labelledby="pills-foto-tab">
                                    <dokumentasi :file="dokumentasi.foto" format="jpg, jpeg, png" />
                                </div>
                                <div class="tab-pane fade" id="pills-video" role="tabpanel" aria-labelledby="pills-video-tab">
                                    <dokumentasi :file="dokumentasi.video" format="mp4, mkv" />
                                </div>
                                <div class="tab-pane fade" id="pills-rekaman" role="tabpanel" aria-labelledby="pills-rekaman-tab">
                                    <dokumentasi :file="dokumentasi.rekaman" format="mp3, wav" /> 
                                </div>
                                <div class="tab-pane fade" id="pills-lainnya" role="tabpanel" aria-labelledby="pills-lainnya-tab">
                                    <dokumentasi :file="dokumentasi.lainnya" />
                                </div>
                            </div>
                        </div>
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