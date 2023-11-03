<script>
import DataTable from '../../../components/DataTable.vue';
import status from '../../../components/status.vue';
import ModalTransfer from './modalTransfer';
export default {
    props: ['dataTable'],
    components: {
        status,
        ModalTransfer,
        DataTable
    },
    data() {
        return {
            showModalTransfer: false,
            transferDetail: null,
            headerTransfer: null,
            headers: [{
                text: 'No Transfer',
                value: 'no_surat',
            },
            {
                text: 'No Urut',
                value: 'urutan',
            },
            {
                text: 'Nama Produk',
                value: 'nama'
            },
            {
                text: 'Jumlah',
                value: 'jumlah'
            },
            {
                text: 'Aksi',
                value: 'aksi'
            }
            ],
            search: '',
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
        closeModalSeri() {
            $('.modalSet').modal('hide');
            this.$nextTick(() => {
                this.showModal = false;

            });
        },
        statusReworks(belum, selesai, jumlah) {
            if (selesai == jumlah) {
                return 'selesai'
            }

            if (selesai == 0) {
                return 'belum_dikerjakan'
            }

            if (selesai != jumlah && belum != 0) {
                return 'sedang_dikerjakan'
            }
        },
        transferRework(data) {
            this.headerTransfer = JSON.parse(JSON.stringify(data))
            // delete item on headerTransfert
            delete this.headerTransfer.item
            this.transferDetail = data.item.map(item => {
                return {
                    ...item,
                    layout: {
                        id: 7,
                        label: 'Blok B',
                    }
                }
            })
            this.showModalTransfer = true
            this.$nextTick(() => {
                $('.modalTransfer').modal('show')
            })
        },
        refresh() {
            this.$emit('refresh')
        },
        cetak(id) {
            window.open(`/produksiReworks/surat_penyerahan/${id}/produksi`, '_blank')
        }
    },
}
</script>
<template>
    <div>
        <ModalTransfer v-if="showModalTransfer" :headerTransfer="headerTransfer" :dataTable="transferDetail" @closeModal="showModalTransfer = false"
            @refresh="refresh" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <DataTable :headers="headers" :items="dataTable" :search="search">
            <template #item.aksi="{ item }">
                <button class="btn btn-sm btn-outline-primary" @click="transferRework(item)">
                    <i class="fas fa-check"></i>
                    Terima
                </button>

                <button class="btn btn-sm btn-outline-info" @click="cetak(item.id)">
                    <i class="fas fa-print"></i>
                    Cetak BPBJ
                </button>
            </template>
        </DataTable>
    </div>
</template>
