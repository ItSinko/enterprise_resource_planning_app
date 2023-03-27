<script>
    import mix from '../mix.js'

    export default {
        mixins: [mix],
        mounted() {
            $('.dalamProsesTable').DataTable({
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
            purchaseorders: {
                type: Array,
                required: true
            }
        },
        methods: {
            detail(id) {
            this.$router.push({ name: 'detailPO', params: { id: id } })
            }
        }
    }

</script>
<template>
    <div>
        <table class="table dalamProsesTable">
            <thead class="thead-light">
                <tr>
                    <th>No PO</th>
                    <th>Divisi</th>
                    <th>Supplier</th>
                    <th>Tanggal Dimitra</th>
                    <th>Estimasi Kedatangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="purchaseorder in purchaseorders" :key="purchaseorder.id">
                    <td>{{purchaseorder.nopo}}</td>
                    <td>{{purchaseorder.divisi}}</td>
                    <td>{{purchaseorder.supplier}}</td>
                    <td>{{formatDateIndonesia(purchaseorder.tanggal)}}</td>
                    <td>{{formatDateIndonesia(purchaseorder.estimasi)}}</td>
                    <td v-html="status(purchaseorder.status)"></td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" @click="detail(id)">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
                <tr v-if="purchaseorders.length == 0">
                    <td colspan="7" class="text-center">Tidak Ditemukan</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
