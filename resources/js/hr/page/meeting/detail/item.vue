<script>
import moment from "moment";
import pagination from '../../../components/pagination.vue';
export default {
    components: {
        pagination,
    },
    data() {
        return {
            feedback: [
                {
                    tanggal: "2023-01-01",
                    peserta: [
                        {
                            nama: "Peserta 1",
                            catatan: "Catatan Peserta 1",
                        },
                        {
                            nama: "Peserta 2",
                            catatan: "Catatan Peserta 2",
                        },
                    ],
                },
                {
                    tanggal: "2023-01-02",
                    peserta: [
                        {
                            nama: "Peserta 3",
                            catatan: "Catatan Peserta 3",
                        },
                        {
                            nama: "Peserta 4",
                            catatan: "Catatan Peserta 4",
                        },
                    ],
                },
                {
                    tanggal: "2023-01-03",
                    peserta: [
                        {
                            nama: "Peserta 5",
                            catatan: "Catatan Peserta 5",
                        },
                        {
                            nama: "Peserta 6",
                            catatan: "Catatan Peserta 6",
                        },
                    ],
                },
            ],
            itemfeedback: [],
            search: "",
            renderPaginate: [],
        };
    },
    methods: {
        formatDate(date) {
            return moment(date).lang("id").format("dddd, DD MMMM YYYY");
        },
        selectItem(item) {
            this.itemfeedback = JSON.parse(JSON.stringify(item.peserta));
        },
        getFeedbackIndex0() {
            return this.feedback[0].peserta;
        },
        updatePage(page) {
            this.renderPaginate = page;
        },
    },
    mounted() {
        this.itemfeedback = this.getFeedbackIndex0();
    },
    computed: {
        paginateData(){
            return this.itemfeedback.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        },
    }
};
</script>
<template>
    <div class="card">
        <div class="card-body">
            <h4 class="mb-4">Feedback Peserta (Kehadiran)</h4>
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li
                    class="nav-item"
                    role="presentation"
                    v-for="(item, idx) in feedback"
                    :key="idx"
                >
                    <a
                        class="nav-link"
                        :id="'pills-' + item.tanggal + '-tab'"
                        :class="{ active: idx === 0 }"
                        data-toggle="pill"
                        :data-target="'#pills-' + item.tanggal"
                        type="button"
                        role="tab"
                        :aria-controls="'pills-' + item.tanggal"
                        @click="selectItem(item)"

                    >
                        {{ formatDate(item.tanggal) }}
                    </a>
                </li>
            </ul>
            <div class="d-flex bd-highlight mb-3">
                <div class="mr-auto p-2 bd-highlight">
                    
                </div>
                <div class="p-2 bd-highlight">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                    </div>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                    <th>Nama Peserta</th>
                    <th>Catatan</th>
                </tr>
                </thead>
                <tbody v-if="renderPaginate.length > 0">
                    <tr v-for="(peserta, idx) in renderPaginate" :key="idx">
                        <td>{{ peserta.nama }}</td>
                        <td>{{ peserta.catatan }}</td>
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
