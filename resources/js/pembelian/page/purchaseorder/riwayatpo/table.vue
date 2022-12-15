<script>
    import moment from 'moment'
    import mix from '../../../mix'
    export default {
        mixins: [mix],
        props: {
            filteredDatatables: {
                type: Array,
                required: true
            }
        },
        methods: {
            formatDateIndonesia(date) {
                return moment(date).lang('id').format('LL')
            },
        },
    }

</script>
<template>
    <div>
        <table class="table dalamProsesTable text-center">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
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
                <tr v-for="(purchaseorder, idx) in renderPaginate" :key="purchaseorder.id">
                    <td>{{ idx + 1 }}</td>
                    <td>{{purchaseorder.no_po}}</td>
                    <td>{{purchaseorder.divisi}}</td>
                    <td>{{purchaseorder.supplier}}</td>
                    <td>{{formatDateIndonesia(purchaseorder.tanggal)}}</td>
                    <td>{{formatDateIndonesia(purchaseorder.estimasi)}}</td>
                    <td>{{ purchaseorder.ekspedisi }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-info">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
                <tr v-if="renderPaginate.length == 0">
                    <td colspan="7" class="text-center">Tidak Ditemukan</td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex flex-row-reverse bd-highlight">
            <nav aria-label="...">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" :disabled="currentPage == 1" @click="previousPage">Previous</a>
                    </li>
                    <li class="page-item" :class="paginate == currentPage ? 'active' : ''" v-for="paginate in pages"
                        :key="paginate">
                        <a class="page-link" @click="nowPage(paginate)">{{paginate}}</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" :disabled="currentPage == pages[pages.length - 1]"
                            @click="nextPage">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
