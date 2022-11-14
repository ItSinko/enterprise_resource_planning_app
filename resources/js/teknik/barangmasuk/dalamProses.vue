<script>
    import mix from './mix.js'
    export default {
        mixins: [mix],
        props: {
            purchaseorders: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                searchdalamProses: '',
                filterdalamProses: [],
            }
        },
        methods: {
            clickFilterdalamProses(filter) {
                if (this.filterdalamProses.includes(filter)) {
                    this.filterdalamProses = this.filterdalamProses.filter(item => item !== filter)
                } else {
                    this.filterdalamProses.push(filter)
                }
            },
        },
        mounted() {
            $('.dalamProsesTable').DataTable({
                "destroy": true,
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        },
        computed: {
            filtereddalamProses() {
                let filtered = []
                if (this.filterdalamProses.length == 0) {
                    filtered = this.purchaseorders
                } else {
                    this.filterdalamProses.forEach(filter => {
                        filtered = filtered.concat(this.purchaseorders.filter(purchaseorder => purchaseorder
                            .status == filter))
                    })
                }
                return filtered.filter((purchaseorder) => {
                    return purchaseorder.nopo.toLowerCase().includes(this.searchdalamProses.toLowerCase()) ||
                        purchaseorder.divisi.toLowerCase().includes(this.searchdalamProses.toLowerCase()) ||
                        purchaseorder.supplier.toLowerCase().includes(this.searchdalamProses.toLowerCase()) ||
                        purchaseorder.tanggal.toLowerCase().includes(this.searchdalamProses.toLowerCase()) ||
                        purchaseorder.estimasi.toLowerCase().includes(this.searchdalamProses.toLowerCase()) ||
                        purchaseorder.status.toLowerCase().includes(this.searchdalamProses.toLowerCase())
                })
            },
            getAllStatusUnique() {
                return this.purchaseorders.map((purchaseorder) => {
                    return purchaseorder.status
                }).filter((value, index, self) => {
                    return self.indexOf(value) === index
                })
            },
        },
    }

</script>
<template>
    <div>
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <span class="float-left filter">
                    <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="form-group">
                                    <label for="jenis_penjualan">Status</label>
                                </div>
                                <div class="form-group" v-for="status in getAllStatusUnique" :key="status">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" :ref="status" :value="status"
                                            id="status1" @click="clickFilterdalamProses(status)" />
                                        <label class="form-check-label" for="status1">
                                            {{ status }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="searchdalamProses" placeholder="Cari...">
            </div>
        </div>
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
                <tr v-for="purchaseorder in filtereddalamProses" :key="purchaseorder.id">
                    <td>{{purchaseorder.nopo}}</td>
                    <td>{{purchaseorder.divisi}}</td>
                    <td>{{purchaseorder.supplier}}</td>
                    <td>{{formatDateIndonesia(purchaseorder.tanggal)}}</td>
                    <td>{{formatDateIndonesia(purchaseorder.estimasi)}}</td>
                    <td v-html="status(purchaseorder.status)"></td>
                    <td>
                        <button class="btn btn-sm btn-outline-info">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
                <tr v-if="filtereddalamProses.length == 0">
                    <td colspan="7" class="text-center">Tidak Ditemukan</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
