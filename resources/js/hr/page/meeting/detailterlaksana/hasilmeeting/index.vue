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
        save() {
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
        editHasilMeeting(data, idx){
            this.showModal = true;
            this.formhasilmeeting = JSON.parse(JSON.stringify(data));
            this.formhasilmeeting.idx = idx;
            this.$nextTick(() => {
                $(".modalHasilMeeting").modal("show");
            });
        }
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
        <modal :formhasilmeeting="formhasilmeeting" @save="save" @closeModal="close" />
        <div class="card-body">
            <div class="d-flex">
                <div class="mr-auto p-2">
                    <button class="btn btn-primary" @click="addHasilMeeting" v-if="status == 'menyusun_hasil_meeting'">
                        <i class="fa fa-plus"></i>
                        Tambah</button>
                </div>
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
                        <th v-if="status == 'menyusun_hasil_meeting'">Aksi</th>
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