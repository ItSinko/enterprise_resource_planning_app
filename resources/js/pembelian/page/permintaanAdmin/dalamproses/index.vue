<script>
import Table from './table.vue'
import Detail from './detail/umum'
import DetailPart from './detail/part'
export default {
    components: {
        Table,
        Detail,
        DetailPart
    },
    data() {
        return {
            modal: false,
            modalPart: false,
            dalamProses: [
                {
                    id: 1,
                    no_pp: 'PP/2020/01/01',
                    jenis_barang: 'umum',
                    tanggal_diminta: '01 Januari 2020',
                    tanggal_dibutuhkan: '01 Januari 2020',
                    pp: 'Admin',
                    status: 'minta persetujuan'
                },
                {
                    id: 2,
                    no_pp: 'PP/2020/01/02',
                    jenis_barang: 'part',
                    tanggal_diminta: '01 Januari 2020',
                    tanggal_dibutuhkan: '01 Januari 2020',
                    pp: 'Admin',
                    status: 'menunggu diproses',
                },
                {
                    id: 3,
                    no_pp: 'PP/2020/01/03',
                    jenis_barang: 'umum',
                    tanggal_diminta: '01 Januari 2020',
                    tanggal_dibutuhkan: '01 Januari 2020',
                    pp: 'Admin',
                    status: 'pembelian ditolak',
                },
                {
                    no_pp: 'PP/2020/01/04',
                    jenis_barang: 'part',
                    tanggal_diminta: '01 Januari 2020',
                    tanggal_dibutuhkan: '01 Januari 2020',
                    pp: 'Admin',
                    status: 'selesai',
                    persentase: 25
                }
            ],
            idModal: null
        }
    },
    methods: {
        detail(id, jenis) {
            this.idModal = id
            if (jenis === 'umum') {
                this.modal = true
                setTimeout(() => {
                    $('.modalDetail').modal('show')
                }, 100);
            } else {
                this.modalPart = true
                
                setTimeout(() => {
                    $('.modalDetailPart').modal('show')
                }, 500);
            }
        },
        closeModal() {
            this.modal = false
            $('.modalDetail').modal('hide')
        },
        closeModalPart() {
            this.modalPart = false
            $('.modalDetailPart').modal('hide')
        }
    }
}
</script>
<template>
    <div>
        <Detail v-if="modal" @close="closeModal" :id="idModal" />
        <DetailPart v-if="modalPart" @close="closeModalPart" :id="idModal"/>
        <Table :dataTables="dalamProses" @detail="detail"/>
    </div>
</template>