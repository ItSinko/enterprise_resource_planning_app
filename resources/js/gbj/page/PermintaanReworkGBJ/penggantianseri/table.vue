<script>
import formPenggantian from './formPenggantian'
import status from '../../../components/status.vue';
export default {
    props: ['dataTable'],
    components: {
        formPenggantian,
        status,
    },
    data() {
        return {
            dataSelected: {},
            showModal: false,
        }
    },
    methods: {
        detail(data) {
            this.dataSelected = JSON.parse(JSON.stringify(data));
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalPenggantianSeri').modal('show');
            });
        },
        refresh() {
            this.$emit('refresh');
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
    },
}
</script>
<template>
    <div>
        <formPenggantian v-if="showModal" :headerData="dataSelected" @closeModal="showModal = false" @refresh="refresh" />
        <table class="text-center table">
            <thead>
                <tr>
                    <th rowspan="2">No Urut</th>
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
                    <td>{{ data.nama }}</td>
                    <td>{{ data.selesai }}</td>
                    <td>{{ data.belum }}</td>
                    <td>
                        <status :status="statusReworks(data.belum, data.selesai, data.jumlah)" />
                    </td>
                    <td>
                        <button @click="detail(data)" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="6">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>