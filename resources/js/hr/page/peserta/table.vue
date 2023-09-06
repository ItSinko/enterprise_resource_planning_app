<script>
import date from "../../components/date.vue";
import status from "../../components/status.vue";
import kehadiran from "./aksi/kehadiran.vue";
export default {
    props: ["dataTable"],
    components: {
        date,
        status,
        kehadiran,
    },
    data() {
        return {
            modalKehadiran: false,
            dataKehadiran: null,
        };
    },
    methods: {
        openModalKehadiran(data) {
            this.dataKehadiran = JSON.parse(JSON.stringify(data));
            this.modalKehadiran = true;
            this.$nextTick(() => {
                $(".modalKehadiran").modal("show");
            });
        },
        detail(id, status) {
            if (status == "terlaksana" || status == "menyusun_hasil_meeting") {
                this.$router.push({
                    name: "jadwal-meeting-peserta-detail-terlaksana",
                    params: { id: id },
                });
            } else {
                this.$router.push({
                    name: "jadwal-meeting-peserta-detail-nonterlaksana",
                    params: { id: id },
                });
            }
        },
    },
};
</script>
<template>
    <div>
        <kehadiran
            v-if="modalKehadiran"
            @closeModal="modalKehadiran = false"
            :kehadiran="dataKehadiran"
        />
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nomor Meeting</th>
                    <th scope="col">Judul Meeting</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Mulai</th>
                    <th scope="col">Selesai</th>
                    <th scope="col">Lokasi</th>
                    <th scope="col">Status</th>
                    <th scope="col">Kehadiran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, idx) in dataTable" :key="idx">
                    <th scope="row">{{ idx + 1 }}</th>
                    <td>Meet-{{ idx + 1 }}</td>
                    <td>{{ data.nama }}</td>
                    <td><date :date="data.tanggal" /></td>
                    <td>{{ data.mulai }}</td>
                    <td>{{ data.selesai }}</td>
                    <td>{{ data.lokasi }}</td>
                    <td><status :status="data.status" /></td>
                    <td><status :status="data.kehadiran" /></td>
                    <td>
                        <div
                            class="dropdown-toggle"
                            data-toggle="dropdown"
                            id="dropdownMenuButton"
                            aria-haspopup="true"
                            aria-expanded="true"
                        >
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                        <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                            v-if="data.status == 'belum_terlaksana'"
                        >
                            <a>
                                <button
                                    class="dropdown-item"
                                    @click="openModalKehadiran(data)"
                                >
                                    <i class="fas fa-check-circle"></i>
                                    Kehadiran
                                </button>
                                <button
                                    class="dropdown-item"
                                    @click="detail(data.id, data.status)"
                                >
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </button>
                            </a>
                        </div>
                        <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                            v-if="data.status == 'menyusun_hasil_meeting'"
                        >
                            <a>
                                <button
                                    class="dropdown-item"
                                    type="button"
                                    @click="detail(data.id, data.status)"
                                >
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </button>
                            </a>
                        </div>
                        <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                            v-if="data.status == 'terlaksana'"
                        >
                            <a>
                                <button
                                    class="dropdown-item"
                                    type="button"
                                    @click="detail(data.id, data.status)"
                                >
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </button>
                            </a>
                        </div>
                        <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                            v-if="data.status == 'batal'"
                        >
                            <a>
                                <button
                                    class="dropdown-item"
                                    type="button"
                                    @click="detail(data.id, data.status)"
                                >
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </button>
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
