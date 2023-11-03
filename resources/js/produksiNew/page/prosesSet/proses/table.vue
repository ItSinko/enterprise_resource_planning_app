<script>
import status from '../../../components/status.vue';
import ModalGenerate from './modalDetail';
import ModalTransfer from './modalTransfer';
export default {
    props: ['dataTable'],
    components: {
        status,
        ModalGenerate,
        ModalTransfer,
    },
    data() {
        return {
            showModal: false,
            dataGenerate: {},
            showModalTransfer: false,
            idTransfer: null,
        }
    },
    methods: {
        kirim(id) {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Anda akan mengirim data permintaan ini",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal({
                        title: 'Berhasil!',
                        text: 'Data berhasil dikirim',
                        icon: 'success',
                    })
                }
            })
        },
        generateSeri(data) {
            this.dataGenerate = JSON.parse(JSON.stringify(data));
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalSet').modal('show');
            });
        },
        closeModalSeri() {
            $('.modalSet').modal('hide');
            this.$nextTick(() => {
                this.showModal = false;

            });
        },
        detailRework(id, set) {
            this.$router.push({
                name: 'prosesSetReworksDetail',
                params: {
                    id: id,
                },
            });
        },
        statusReworks(belum, selesai, jumlah) {
            if(selesai == jumlah) {
                return 'selesai'
            }

            if(selesai == 0){
                return 'belum_dikerjakan'
            }

            if(selesai != jumlah && belum !=0) {
                return 'sedang_dikerjakan'
            }
        },
        transferRework(id) {
            this.idTransfer = id
            this.showModalTransfer = true
            this.$nextTick(() => {
                $('.modalTransfer').modal('show')
            })
        },
        refresh() {
            this.$emit('refresh')
        },
    },
}
</script>
<template>
    <div>
        <ModalGenerate v-if="showModal" :dataGenerate="dataGenerate" @closeModal="closeModalSeri" />
        <ModalTransfer v-if="showModalTransfer" :id="idTransfer" @closeModal="showModalTransfer = false" @refresh="refresh" />
        <table class="table text-center">
            <thead>
                <tr>
                    <th rowspan="2">No Urut</th>
                    <th rowspan="2">Tanggal Mulai</th>
                    <th rowspan="2">Tanggal Selesai</th>
                    <th rowspan="2">Nama Produk</th>
                    <th colspan="2">Jumlah</th>
                    <th rowspan="2">Status</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Selesai</th>
                    <th>Belum Selesai</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ data.no_urut }}</td>
                    <td>{{ data.tgl_mulai }}</td>
                    <td>{{ data.tgl_selesai }}</td>
                    <td>{{ data.nama }}</td>
                    <td>{{ data.selesai }}</td>
                    <td>{{ data.belum }}</td>
                    <td>
                        <status :status="statusReworks(data.belum, data.selesai, data.jumlah)" />
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" @click="transferRework(data.urutan)" v-if="data.csiaptf != 0">
                            <i class="fas fa-paper-plane"></i>
                            Transfer
                        </button>
                        <button class="btn btn-sm btn-outline-info" @click="detailRework(data.urutan, data.set)">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
