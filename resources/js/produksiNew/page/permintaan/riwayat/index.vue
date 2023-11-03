<script>
import axios from 'axios'
import modalProduk from './modalProduk'
import DataTable from '../../../components/DataTable.vue';
export default {
    components: {
        modalProduk,
        DataTable,
    },
    data() {
        return {
            dataSelected: {},
            showModal: false,
            search: '',
            renderPaginate: [
            ],
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
                    text: 'Tanggal Transfer',
                    value: 'tgl_tf',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                    sortable: false,
                },
            ]
        }
    },
    props: ['dataTable'],
    methods: {
        async detail(header) {
            try {
                const { data } = await axios.get(`/api/gbj/rw/riwayat_permintaan/${header.id}`)
                this.dataSelected = {
                    header,
                    data: data.map(item => {
                        return {
                            ...item,
                            varian: item.varian ? item.varian : '-'
                        }
                    })
                }

                this.showModal = true
                this.$nextTick(() => {
                    $('.modalProduk').modal('show')
                })
            } catch (error) {
                console.log(error)
            }
        },

        cetakPengantar(id) {
            window.open(`/produksiReworks/surat_pengiriman/${id}`, '_blank')
        },
    },
}
</script>
<template>
    <div>
        <modalProduk v-if="showModal" @closeModal="showModal = false" :dataSelected="dataSelected" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="dataTable" :search="search">
            <template #item.aksi="{ item }">
                <button class="btn btn-sm btn-outline-info" @click="detail(item)">
                    <i class="fas fa-info-circle"></i>
                    Detail
                </button>
                <button class="btn btn-sm btn-outline-primary" @click="cetakPengantar(item.id)">
                    <i class="fas fa-print"></i>
                    Cetak FPBJ
                </button>
            </template>
        </data-table>
    </div>
</template>