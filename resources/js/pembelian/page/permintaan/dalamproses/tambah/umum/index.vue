<script>
import Terdaftar from './terdaftar'
import TidakTerdaftar from './tidakterdaftar'
    export default {
        components: {
            Terdaftar,
            TidakTerdaftar
        },
        props: {
            accepted: {
                type: Array,
                default: () => []
            },
            notAccepted: {
                type: Array,
                default: () => []
            },
            sisa: {
                type: Object,
                default: () => {}
            }
        },
        methods: {
            trashAccepted(index) {
                this.$emit('trashAccepted', index)
            },
            addAccepted() {
                this.$emit('addAccepted')
            },
            trashNotAccepted(index) {
                this.$emit('trashNotAccepted', index)
            },
            addNotAccepted() {
                this.$emit('addNotAccepted')
            },
        }
    }
</script>
<template>
 <div class="card">
    <div class="card-body">
        <h5 class="card-title text-bold">Daftar Barang</h5>
        <div class="card-text mt-5">
             <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                    type="button" role="tab" aria-controls="pills-home" aria-selected="true">Part Terdaftar</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                    type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Part Tidak Terdaftar</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <terdaftar :dataTable="accepted" :sisa="sisa.accepted" @trash="trashAccepted" @add="addAccepted"/>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <tidak-terdaftar :dataTable="notAccepted" :sisa="sisa.notAccepted" @add="addNotAccepted" @trash="trashNotAccepted" ref="tidakterdaftar"/>
            </div>
        </div>
        </div>
    </div>
 </div>
</template>
