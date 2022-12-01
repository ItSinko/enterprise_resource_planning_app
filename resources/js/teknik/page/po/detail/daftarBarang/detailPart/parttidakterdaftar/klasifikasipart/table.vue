<script>
import detailKlasifikasi from './detailKlasifikasi'
export default {
    components: {
        detailKlasifikasi
    },
    props: {
        dataTable: {
            type: Array,
            default: () => []
        },
        headers : {
            type: Object,
            default: () => {}
        },
    },
    data() {
        return {
            headersDetail: null
        }
    },
    methods: {
        detailPart(index) {
            const headers = {
                namaProduk: this.headers,
                nama_packing_list: this.dataTable[index].nama_packing_list,
                jumlah_pemesanan: this.dataTable[index].jumlah_pemesanan,
            }
            this.headersDetail = headers
            
            setTimeout(() => {
                $('.modalDetailPart').modal('show')
            }, 100);
        }
    },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-bold">Klasifikasi Part</h4>
            <div class="card-text mt-5">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Packing List</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dataTable" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>{{ item.nama_packing_list }}</td>
                            <td>{{ item.jumlah_pemesanan }}</td>
                            <td>
                                <i class="fas fa-edit text-warning" @click="detailPart(index)"></i>
                            </td>
                            <detail-klasifikasi :dataTable="item.part" :headers="headersDetail"/>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>