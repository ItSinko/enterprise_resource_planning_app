<script>
import DataTable from '../../../components/DataTable.vue';
import modalDetail from './modalDetail.vue';
export default {
    props: ['dataTable'],
    components: {
        DataTable,
        modalDetail,
    },
    data() {
        return {
            headers: [
                {
                    text: 'No. Urut',
                    value: 'no_urut'
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
                    text: 'Tanggal Penerimaan',
                    value: 'tgl_tf'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            search: '',
            selectDetail: null,
        }
    },
    methods: {
        detail(item) {
            this.selectDetail = item;
            this.$nextTick(() => {
                $('.modalDetail').modal('show');
            })
        },
    },
}
</script>
<template>
    <div>
        <modalDetail v-if="selectDetail" :headers="selectDetail" @closeModal="selectDetail = null" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <DataTable :headers="headers" :items="dataTable" :search="search">
            <template #item.tgl_tf="{ item }">
                {{ dateTimeFormat(item.tgl_tf) }}
            </template>

            <template #item.aksi = "{item}">
                <button class="btn btn-outline-info btn-sm" @click="detail(item)">
                    <i class="fas fa-eye"></i>
                    Detail
                </button>
            </template>
            
        </DataTable>
    </div>
</template>