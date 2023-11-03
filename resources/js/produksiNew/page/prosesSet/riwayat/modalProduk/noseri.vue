<script>
import pagination from '../../../../components/pagination.vue';
export default {
    props: ['seriSelected'],
    components: {
        pagination,
    },
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
        closeModal() {
            $('.modalSeri').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
    },
    computed: {
        filteredDalamProses() {
            return this.seriSelected.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
    },
}
</script>
<template>
    <div class="modal fade modalSeri" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Seri</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" v-model="search" class="form-control" placeholder="Cari...">
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No. Seri</th>
                                <th>Variasi</th>
                            </tr>
                        </thead>
                        <tbody v-if="renderPaginate.length > 0">
                            <tr v-for="(data, index) in renderPaginate" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ data.noseri }}</td>
                                <td>{{ data.variasi }}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data</td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination :filteredDalamProses="filteredDalamProses"
                                        @updateFilteredDalamProses="updateFilteredDalamProses" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                </div>
            </div>
        </div>
    </div>
</template>