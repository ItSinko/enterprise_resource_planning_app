<script>
import mix from '../mix.js'
export default {
    mixins: [mix],
    mounted() {
        $('.riwayatProsesTable').DataTable({
            "destroy": true,
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    },
    props: {
        riwayatPO: {
            type: Array,
            required: true
        }
    },
    methods: {
        detail(id) {
            this.$router.push({ name: 'detailPO', params: { id: id } })
        }
    },
}
</script>
<template>
    <div>
        <table class="table riwayatProsesTable">
            <thead class="thead-light">
                <tr>
                    <th>No PO</th>
                    <th>Divisi</th>
                    <th>Supplier</th>
                    <th>Tanggal Diminta</th>
                    <th>Estimasi Kedatangan</th>
                    <th>Tanggal Kedatangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="riwayatPO in riwayatPO" :key="riwayatPO.id">
                    <td>{{riwayatPO.nopo}}</td>
                    <td>{{riwayatPO.divisi}}</td>
                    <td>{{riwayatPO.supplier}}</td>
                    <td>{{formatDateIndonesia(riwayatPO.tanggal_diminta)}}</td>
                    <td>{{formatDateIndonesia(riwayatPO.estimasi_kedatangan)}}</td>
                    <td>{{formatDateIndonesia(riwayatPO.tanggal_kedatangan)}}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" @click="detail(riwayatPO.id)">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>