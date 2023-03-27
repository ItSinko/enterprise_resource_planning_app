<script>
import Terdaftar from './terdaftar'
import TidakTerdaftar from './tidakterdaftar'
export default {
    components: {
        Terdaftar,
        TidakTerdaftar
    },
    props: {
        namabom: {
            type: String,
            required: true,
            default: 'Nama BOM'
        },
    },
    methods: {
        closeModal() {
            this.$emit('close-modal')
        }
    },
}
</script>
<template>
    <div class="modal fade modalDetailBOM" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ namabom }}</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pills-terdaftar-tab" data-toggle="pill" data-target="#pills-terdaftar" type="button" role="tab" aria-controls="pills-terdaftar" aria-selected="true">Terdaftar</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-tidakterdaftar-tab" data-toggle="pill" data-target="#pills-tidakterdaftar" type="button" role="tab" aria-controls="pills-tidakterdaftar" aria-selected="false">Tidak Terdaftar</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-terdaftar" role="tabpanel" aria-labelledby="pills-terdaftar-tab">
                        <Terdaftar :dataTable="null"/>
                    </div>
                    <div class="tab-pane fade" id="pills-tidakterdaftar" role="tabpanel" aria-labelledby="pills-tidakterdaftar-tab">
                        <TidakTerdaftar :dataTable="null"/>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>