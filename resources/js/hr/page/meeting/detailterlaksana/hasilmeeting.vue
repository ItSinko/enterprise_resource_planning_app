<script>
import pagination from "../../../components/pagination.vue";
export default {
    props: ["meeting"],
    components: {
        pagination,
    },
    data() {
        return {
            search: "",
            renderPaginate: [],
        };
    },
    methods: {
        updatePage(page) {
            this.renderPaginate = page;
        },
    },
    computed: {
        paginateData() {
            return this.meeting.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key])
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                });
            });
        },
    },
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                    <div class="input-group">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Cari..."
                            v-model="search"
                        />
                    </div>
                </div>
            </div>
            <table class="table">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Hasil Rapat</th>
                    </tr>
                </thead>
                <tbody v-if="renderPaginate.length > 0">
                    <tr v-for="(item, idx) in renderPaginate" :key="idx">
                        <td class="text-center">{{ idx + 1 }}</td>
                        <td>{{ item.isi }}</td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="2" class="text-center">Tidak ada data</td>
                    </tr>
                </tbody>
            </table>
            <pagination :DataTable="paginateData" @updatePage="updatePage" />
        </div>
    </div>
</template>