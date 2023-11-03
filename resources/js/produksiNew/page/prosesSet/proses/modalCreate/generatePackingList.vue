<script>
import pagination from '../../../../components/pagination.vue';
export default {
    components: {
        pagination,
    },
    props: ['dataTable'],
    data() {
        return {
            search: '',
            renderPaginate: [],
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
    },
    computed: {
        filteredDalamProses() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
    },
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Contents</th>
                        <th>Qty</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody v-if="renderPaginate.length > 0">
                    <tr v-for="(data, idx) in renderPaginate" :key="idx">
                        <td>{{ idx + 1 }}</td>
                        <td>{{ `${data.produk} ${data.varian}` }}</td>
                        <td>1</td>
                        <td>{{ data.noseri }}</td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                </tbody>
            </table>
            <pagination :filteredDalamProses="filteredDalamProses" @updateFilteredDalamProses="updateFilteredDalamProses" />
        </div>
    </div>
</template>