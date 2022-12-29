<script>
import Modal from './modal'
export default {
    components: {
        Modal
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
            dataTableDummy: [
                {
                    namaBom: 'BOM 1',
                    status: 'selesai',
                },
                {
                    namaBom: 'BOM 2',
                    status: 'proses',
                }
            ],
            modal: false,
            titleModal: 'Detail BOM'
        }
    },
    methods: {
        generateStatusHTML(status) {
            if (status === 'selesai') {
                return `<span class="badge badge-success text-capitalize">${status}</span>`
            } else {
                return `<span class="badge badge-warning text-capitalize">${status}</span>`
            }
        },
        showModal(title) {
            this.modal = true
            this.titleModal = title
            setTimeout(() => {
                $('.modalDetailBOM').modal('show')
            }, 100);
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
        <Modal :namabom="titleModal" @close-modal="close" v-if="modal"/>
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
                <tr v-for="data in dataTableDummy" :key="data.namaBom">
                    <td>{{ data.namaBom }}</td>
                    <td v-html="generateStatusHTML(data.status)"></td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" @click="showModal(data.namaBom)">
                            <i class="fa fa-eye"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>    
    </div>
</template>