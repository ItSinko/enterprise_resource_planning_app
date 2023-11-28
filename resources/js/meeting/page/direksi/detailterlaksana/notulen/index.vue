<script>
import moment from "moment";
import kehadiran from "../../../../components/kehadiran.vue";
import pagination from "../../../../components/pagination.vue";
export default {
    props: ["meeting", "status"],
    components: {
        kehadiran,
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
        formatDateTime(date) {
            return moment(date).lang("id").format("DD/MM/YYYY HH:mm");
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
};
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex">
                <div class="mr-auto p-2"></div>
                <div class="p-2">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Cari..."
                        v-model="search"
                    />
                </div>
            </div>
            <table class="table">
                <thead class="text-center">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Penanggung Jawab</th>
                        <th rowspan="2">Uraian</th>
                        <th colspan="2">Kesesuian</th>
                        <th rowspan="2">Catatan</th>
                    </tr>
                    <tr>
                        <th>Hasil</th>
                        <th>Dicek Oleh</th>
                    </tr>
                </thead>
                <tbody v-if="renderPaginate.length > 0">
                    <tr v-for="(item, idx) in renderPaginate" :key="item.isi">
                        <td class="text-center">{{ idx + 1 }}</td>
                        <td class="text-center">
                            {{ item.penanggungjawab }} - {{ item.divisi }}
                        </td>
                        <td>{{ item.isi }}</td>
                        <td class="text-center">
                            <kehadiran :kehadiran="item.kesesuaian" />
                        </td>
                        <td class="text-center">
                            <div v-if="item.kesesuaian">
                                {{ item.penanggungjawab }},
                                {{ formatDateTime(item.created_at) }}
                            </div>
                            <div v-else>-</div>
                        </td>
                        <td>
                            {{ item.catatan ? item.catatan : "-" }}
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="7" class="text-center">
                            Data tidak ditemukan
                        </td>
                    </tr>
                </tbody>
            </table>
            <pagination :filteredDalamProses="paginateData" @updateFilteredDalamProses="updatePage" />
        </div>
    </div>
</template>
<style>
.tbodyDragNDrop > tr {
    cursor: grab;
}
</style>
