<script>
import moment from "moment";
import pagination from "../../../components/pagination.vue";
export default {
    components: {
        pagination,
    },
    data() {
        return {
            search: "",
            renderPaginate: [],
        };
    },
    props: ["meeting"],
    methods: {
        formatDate(date) {
            return moment(date).lang("id").format("dddd, DD MMMM YYYY");
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
            <h4 class="mb-4">Feedback Peserta (Kehadiran)</h4>
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
                <thead>
                    <tr>
                        <th>Nama Peserta</th>
                        <th>Kehadiran</th>
                        <th>Alasan</th>
                        <th>Dokumen Pendukung Ketidakhadiran</th>
                    </tr>
                </thead>
                <tbody v-if="renderPaginate.length > 0">
                    <tr v-for="(peserta, idx) in renderPaginate" :key="idx">
                        <td>{{ peserta.nama }}</td>
                        <td>{{ peserta.kehadiran }}</td>
                        <td>{{ peserta.alasan }}</td>
                        <td>
                            <div v-if="peserta.dokumen_pendukung">
                                <a 
                                    v-for="(dokumen, idx) in peserta.dokumen_pendukung"
                                    :key="idx"
                                    :href="dokumen.link">{{ dokumen.nama }}<span v-if="idx != peserta.dokumen_pendukung.length - 1">, </span>
                                </a>
                            </div>
                        </td>
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
