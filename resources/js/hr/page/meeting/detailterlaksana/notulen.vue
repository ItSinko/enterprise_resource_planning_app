<script>
import moment from "moment";
import pagination from "../../../components/pagination.vue";
import kehadiran from "../../../components/kehadiran.vue"
export default {
    props: ["meeting"],
    components: {
        pagination,
        kehadiran,
    },
    data() {
        return {
            search: "",
            renderPaginate: [],
        };
    },
    methods: {
        formatDateTime(date) {
            return moment(date).lang("id").format("DD/MM/YYYY HH:mm");
        },
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
};
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
                    <tr v-for="(item, idx) in renderPaginate" :key="idx">
                        <td class="text-center">{{ idx + 1 }}</td>
                        <td class="text-center">
                            {{ item.penanggungjawab }} - {{ item.divisi }}
                        </td>
                        <td>{{ item.isi }}</td>
                        <td class="text-center">
                            <kehadiran :kehadiran="item.kesesuaian" />
                        </td>
                        <td class="text-center">
                            {{ item.penanggungjawab }},
                            {{ formatDateTime(item.created_at) }}
                        </td>
                        <td>
                            {{ item.catatan ?? '-' }}
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                </tbody>
            </table>
            <pagination :DataTable="paginateData" @updatePage="updatePage" />
        </div>
    </div>
</template>
