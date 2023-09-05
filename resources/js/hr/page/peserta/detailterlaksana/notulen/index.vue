<script>
import moment from "moment";
import kehadiran from "../../../../components/kehadiran.vue";
import pagination from "../../../../components/pagination.vue";
import modal from "./modal.vue";
import Sortable from "sortablejs";
export default {
    props: ["meeting", "status"],
    components: {
        kehadiran,
        pagination,
        modal,
    },
    data() {
        return {
            search: "",
            showModal: false,
            renderPaginate: [],
            formnotulen: {
                penanggungjawab: "",
                isi: "",
                kesesuaian: "",
                catatan: "",
            },
        };
    },
    methods: {
        updatePage(page) {
            this.renderPaginate = page;
        },
        formatDateTime(date) {
            return moment(date).lang("id").format("DD/MM/YYYY HH:mm");
        },
        editNotulen(data, idx) {
            this.showModal = true;
            this.formnotulen = JSON.parse(JSON.stringify(data));
            this.formnotulen.idx = idx;
            this.$nextTick(() => {
                $(".modalNotulen").modal("show");
            });
        },
        addNotulen() {
            this.showModal = true;
            // reset formnotulen
            this.formnotulen = {
                penanggungjawab: "",
                isi: "",
                kesesuaian: "",
                catatan: "",
            };
            this.$nextTick(() => {
                $(".modalNotulen").modal("show");
            });
        },
        close() {
            this.showModal = false;
        },
        save() {
            this.showModal = false;
            if (this.formnotulen?.idx) {
                this.meeting[this.formnotulen.idx] = this.formnotulen;
            } else {
                this.meeting.push(this.formnotulen);
            }
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
    mounted() {
    },
};
</script>
<template>
    <div class="card">
        <modal
            :formnotulen="formnotulen"
            @save="save"
            v-if="showModal"
            @closeModal="close"
        />
        <div class="card-body">
            <div class="d-flex">
                <div class="mr-auto p-2">
                    <button
                        class="btn btn-primary"
                        @click="addNotulen"
                        v-if="status == 'menyusun_hasil_meeting'"
                    >
                        <i class="fa fa-plus"></i>
                        Tambah
                    </button>
                </div>
                <div class="p-2" v-if="status != 'menyusun_hasil_meeting'">
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
                        <th
                            rowspan="2"
                            v-if="status != 'menyusun_hasil_meeting'"
                        >
                            Aksi
                        </th>
                    </tr>
                    <tr>
                        <th>Hasil</th>
                        <th>Dicek Oleh</th>
                    </tr>
                </thead>
                <tbody
                    class="tbodyDragNDrop"
                    v-if="status == 'menyusun_hasil_meeting'"
                >
                    <tr v-for="(item, idx) in meeting" :key="item.isi">
                        <td class="text-center">{{ idx + 1 }}</td>
                        <td class="text-center">
                            {{
                                item.penanggungjawab?.nama
                                    ? item.penanggungjawab?.nama
                                    : item.penanggungjawab
                            }}
                            -
                            {{
                                item.penanggungjawab?.divisi?.nama
                                    ? item.penanggungjawab?.divisi?.nama
                                    : item.divisi
                            }}
                        </td>
                        <td>{{ item.isi }}</td>
                        <td class="text-center">
                            <kehadiran :kehadiran="item.kesesuaian" />
                        </td>
                        <td class="text-center">
                            <div v-if="status != 'menyusun_hasil_meeting'">
                                {{
                                    item.penanggungjawab?.nama
                                        ? item.penanggungjawab?.nama
                                        : item.penanggungjawab
                                }}
                                {{ formatDateTime(item.created_at) }}
                            </div>
                        </td>
                        <td>
                            {{ item.catatan ?? "-" }}
                        </td>
                        <td v-if="item?.id == undefined">
                            <button
                                class="btn btn-outline-warning"
                                @click="editNotulen(item, idx)"
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
                <tbody v-if="status != 'menyusun_hasil_meeting'">
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
                            {{ item.penanggungjawab }},
                            {{ formatDateTime(item.created_at) }}
                        </td>
                        <td>
                            {{ item.catatan ?? "-" }}
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
<style>
.tbodyDragNDrop > tr {
    cursor: grab;
}
</style>
