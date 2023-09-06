<script>
import pagination from "../../../../components/pagination.vue";
export default {
    props: ["meeting", "status"],
    components: {
        pagination,
    },
    data() {
        return {
            search: "",
            renderPaginate: [],
            formhasilmeeting: {
                isi: "",
            },
            showModal: false,
        };
    },
    methods: {
        updatePage(page) {
            this.renderPaginate = page;
        },
        saveHasilMeeting() {
            this.showModal = false;
            if (this.formhasilmeeting?.idx) {
                this.meeting[this.formhasilmeeting.idx - 1] =
                    this.formhasilmeeting;
            } else {
                this.meeting.push(this.formhasilmeeting);
            }
            this.$nextTick(() => {
                $(".modalHasilMeeting").modal("hide");
            });
        },
        close() {
            this.showModal = false;
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
                        <td colspan="2" class="text-center">
                            Data tidak ditemukan
                        </td>
                    </tr>
                </tbody>
            </table>
            <pagination :DataTable="paginateData" @updatePage="updatePage" />
        </div>
    </div>
</template>
