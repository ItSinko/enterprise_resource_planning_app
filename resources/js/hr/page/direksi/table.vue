<script>
import status from "../../components/status.vue";
import date from "../../components/date.vue";
export default {
    props: ["dataTable"],
    components: {
        status,
        date,
    },
    methods: {
        detail(id, status) {
            if (status == "terlaksana" || status == "menyusun_hasil_meeting") {
                this.$router.push({
                    name: "jadwal-meeting-direksi-detail-terlaksana",
                    params: { id: id },
                });
            } else {
                this.$router.push({
                    name: "jadwal-meeting-direksi-detail-nonterlaksana",
                    params: { id: id },
                });
            }
        },
    },
};
</script>
<template>
    <div>
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
                        >
                        <a>
                            <button class="dropdown-item" v-if="data.status == 'terlaksana'">
                                    <i class="fas fa-print"></i>
                                    Cetak Hasil Meeting
                                </button>
                            <button class="dropdown-item" @click="detail(data.id, data.status)">
                                <i class="fas fa-eye"></i>
                                    Detail
                            </button>

                        </a>
                    </div>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
