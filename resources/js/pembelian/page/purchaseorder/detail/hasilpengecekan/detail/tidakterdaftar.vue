<script>
import detailPart from './detailPart'
export default {
    components: {
        detailPart
    },
    data() {
        return {
            dataTable: [
                {
                    nama_packing_list: 'Packing List 1',
                    jumlah_pemesanan: 10,
                }
            ],
            search: '',
            modal: false
        }
    },
    methods: {
        close() {
            this.modal = false
            $('.modalDetailPart').modal('hide')
        },
        detail(index) {
            this.modal = true
            setTimeout(() => {
                $('.modalDetailPart').modal('show')
            }, 50);
        }
    },
    computed: {
        filteredData() {
            const dataIsNotNull = (data) => data !== null && data !== undefined && data !== "" ? data : "";
            return this.dataTable.filter((row) => {
                return Object.keys(row).some((key) => {
                    return String(dataIsNotNull(row[key])).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        }
    }
}
</script>
<template>
    <div>
        <detail-part v-if="modal" @close="close"></detail-part>
        <div class="d-flex">
            <div class="p-2">
                <input type="text" class="form-control" v-model="search" placeholder="Cari Part">
            </div>
        </div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Nama Packing List</th>
                    <th>Jumlah Pemesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in filteredData" :key="index">
                    <td>{{ item.nama_packing_list }}</td>
                    <td>{{ item.jumlah_pemesanan }}</td>
                    <td>
                        <button class="btn btn-outline-info btn-sm" @click="detail(index)">
                            <i class="fa fa-eye"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>