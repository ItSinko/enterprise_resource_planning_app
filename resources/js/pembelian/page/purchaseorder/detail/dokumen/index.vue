<script>
import Dokumen from './dokumen.vue'
import ModalDokumen from './modal.vue'
export default {
    components: {
        Dokumen,
        ModalDokumen
    },
    data() {
        return {
            dokumens: [
                {
                    nama_dokumen: 'packing list',
                    file: 'packing_list.pdf',
                    jenis_dokumen: 'Persiapan Pembelian',
                },
                                {
                    nama_dokumen: 'packing list',
                    file: 'packing_list.pdf',
                    jenis_dokumen: 'Persiapan Pembelian',
                },
                {
                    nama_dokumen: 'Surat Jalan 1',
                    file: 'surat_jalan_1.pdf',
                    jenis_dokumen: 'Surat Jalan',
                }
            ],
            url: '/storage/lampiran_produk/',
            jenis: ''
        }
    },
    methods: {
        showModal(jenis) {
            this.jenis = jenis
            setTimeout(() => {
                $('.modalUploadDokumen').modal('show')
            }, 100);
        }
    }
}
</script>
<template>
    <div class="card">
        <modal-dokumen :header="jenis"/>
        <div class="card-body">
            <div class="card-text">
                <dokumen :url="url" :filename="dokumens" @show-modal-upload="showModal"/>
            </div>
        </div>
    </div>
</template>