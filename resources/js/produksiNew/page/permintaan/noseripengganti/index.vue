<script>
import DataTable from '../../../components/DataTable.vue'
import Status from '../../../components/status.vue'
import ModalTambah from './modalTambah.vue'
import ModalDetail from './modalDetail.vue'
export default {
    components: {
        DataTable,
        Status,
        ModalTambah,
        ModalDetail,
    },
    props: ['dataTable'],
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'No Urut',
                    value: 'no_urut',
                },
                {
                    text: 'Nama Produk',
                    value: 'nama',
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah',
                },
                {
                    text: 'Status',
                    value: 'status',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false,
                },
            ],
            modalTambah: false,
            modalDetail: false,
            detailSelected: null,
        }
    },
    methods: {
        tambah() {
            this.modalTambah = true;
            this.$nextTick(() => {
                $('.modalTambah').modal('show')
            })
        },
        detail(item) {
            this.detailSelected = item;
            this.modalDetail = true;
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        },
    },
}
</script>
<template>
    <div>
        <ModalTambah v-if="modalTambah" @closeModal="modalTambah = false" />
        <ModalDetail v-if="modalDetail" @closeModal="modalDetail = false" :headers="detailSelected" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="tambah">
                            <i class="fas fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" v-model="search" class="form-control" placeholder="Cari...">
                    </div>
                </div>
                <DataTable :items="dataTable" :headers="headers" :search="search">
                    <template #item.status="{ item }">
                        <Status :status="item.status" />
                    </template>
                    <template #item.aksi="{ item }">
                        <button class="btn btn-sm btn-outline-info" @click="detail(item)">
                            <i class="fas fa-info-circle"></i>
                            Detail
                        </button>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-print"></i>
                            Cetak BPBJ
                        </button>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</template>