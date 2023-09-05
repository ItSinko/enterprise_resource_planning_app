<script>
import pagination from "../../../../components/pagination.vue";
import modal from "./modal.vue";
export default {
    props: ["meeting", "status"],
    components: {
        pagination,
        modal,
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
        addHasilMeeting() {
            this.showModal = true;
            // reset formhasilmeeting
            this.formhasilmeeting = {
                isi: "",
            };
            this.$nextTick(() => {
                $(".modalHasilMeeting").modal("show");
            });
        },
        saveHasilMeeting() {
            this.showModal = false;
            if (this.formhasilmeeting?.idx) {
                this.meeting[this.formhasilmeeting.idx] = this.formhasilmeeting;
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
        editHasilMeeting(data, idx) {
            this.showModal = true;
            this.formhasilmeeting = JSON.parse(JSON.stringify(data));
            this.formhasilmeeting.idx = idx;
            this.$nextTick(() => {
                $(".modalHasilMeeting").modal("show");
            });
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
        <modal
            :formhasilmeeting="formhasilmeeting"
            @save="saveHasilMeeting"
            @closeModal="close"
            v-if="showModal"
        />
        <div class="card-body">
            <div class="d-flex">
                <div class="mr-auto p-2">
                    <button
                        class="btn btn-primary"
                        @click="addHasilMeeting"
                        v-if="status == 'menyusun_hasil_meeting'"
                    >
                        <i class="fa fa-plus"></i>
                        Tambah
                    </button>
                </div>
                <div class="p-2">
                    <input
                        v-if="status != 'menyusun_hasil_meeting'"
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
                        <th v-if="status == 'menyusun_hasil_meeting'">Aksi</th>
                    </tr>
                </thead>
                <tbody v-if="status == 'menyusun_hasil_meeting'">
                    <tr v-for="(item, idx) in meeting" :key="idx">
                        <td class="text-center">{{ idx + 1 }}</td>
                        <td>{{ item.isi }}</td>
                        <td v-if="item?.id == undefined">
                            <button
                                class="btn btn-outline-warning"
                                @click="editHasilMeeting(item, idx)"
                            >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button
                                class="btn btn-outline-danger"
                                @click="meeting.splice(idx, 1)"
                            >
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <pagination
                :DataTable="paginateData"
                @updatePage="updatePage"
                v-if="status != 'menyusun_hasil_meeting'"
            />
        </div>
    </div>
</template>
