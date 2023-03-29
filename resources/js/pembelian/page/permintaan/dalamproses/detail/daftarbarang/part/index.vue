<script>
import Modal from './modal'
import status from '../../../../../../components/status.vue'
import axios from 'axios'
export default {
    components: {
        Modal,
        status
    },
    props: {
        dataTable: {
            type: Array,
            required: true,
            default: () => []
        }
    },
    data() {
        return {
            modal: false,
            titleModal: 'Detail BOM',
            detailBOM: {}
        }
    },
    methods: {
        async getDetailBOM(id){
            try {
                const { data } = await axios.get(`/api/pembelian/pp/${id}/part`).then(res => res.data)
                this.detailBOM = data
            } catch (error) {
                console.log(error)
            }
        },
        showModal(title, id) {
            this.getDetailBOM(id)
            this.modal = true
            this.titleModal = title
            this.$nextTick(() => {
                $('.modalDetailBOM').modal('show')
            })
        },
        close() {
            this.modal = false
            $('.modalDetailBOM').modal('hide')
        }
    }
}
</script>
<template>
    <div>
        <Modal :bom="detailBOM" :namabom="titleModal" @close-modal="close" v-if="modal"/>
        <button class="btn btn-sm btn-warning mb-5">
            <i class="fa fa-print"></i>
            Cetak PP
        </button>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Nama BOM</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, key) in dataTable" :key="key">
                    <td>{{ data.nama }}</td>
                    <td>
                        <!-- <status :status="data.status" /> -->
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" @click="showModal(data.nama, data.id)">
                            <i class="fa fa-eye"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>    
    </div>
</template>