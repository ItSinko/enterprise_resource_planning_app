<script>
import Status from "../../../components/status.vue";
import moment from "moment";

export default {
    props: ["meeting"],
    components: {
        Status,
    },
    data() {
        return {
            itemPendukung: [],
            selectedData: 0,
        }
    },
    methods: {
        changeFormatDate(date) {
            return moment(date).lang("id").format("dddd, DD MMMM YYYY");
        },
        selectItem(item, idx) {
            this.selectedData = idx;
            this.itemPendukung = item;
        },
    },
    mounted() {
        this.itemPendukung = this.meeting.dokumen_pendukung[0];
    },
};
</script>
<template>
    <div>
        <div class="card">
            <div class="card-body">
                <h4>{{ meeting.judul }}</h4>
                <div class="row">
                    <div class="col-lg-11 col-md-12">
                        <div class="row d-flex justify-content-between">
                            <div class="p-2 cust">
                                <div class="margin">
                                    <div>
                                        <small class="text-muted"
                                            >Jadwal Meeting</small
                                        >
                                    </div>
                                </div>
                                <div class="margin">
                                    <b id="distributor">{{
                                        changeFormatDate(meeting.tanggal)
                                    }}</b>
                                </div>
                                <small class="text-muted">Waktu</small>
                                <div class="margin">
                                    <b id="distributor">
                                        {{ meeting.mulai }} -
                                        {{ meeting.selesai }} WIB</b
                                    >
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="margin">
                                    <div>
                                        <small class="text-muted">Lokasi</small>
                                    </div>
                                    <div>
                                        <b id="no_so">{{ meeting.lokasi }}</b>
                                    </div>
                                </div>
                                <div class="margin">
                                    <div>
                                        <small class="text-muted">Jumlah</small>
                                    </div>
                                    <div>
                                        <b id="no_so"
                                            >{{
                                                meeting.jumlah_peserta
                                            }}
                                            Peserta</b
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="margin">
                                    <div>
                                        <small class="text-muted">Status</small>
                                    </div>
                                    <div>
                                        <b id="no_so">
                                            <Status :status="meeting.status" />
                                        </b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="margin">
                            <div>
                                <small class="text-muted">Deskripsi</small>
                            </div>
                            <div>
                                <b id="no_so">{{ meeting.deskripsi }}</b>
                            </div>
                        </div>
                        <div v-if="meeting.alasan_perubahan_meeting">
                            <small class="text-muted">Alasan Perubahan Meeting</small>
                            <div class="margin">
                                <b id="distributor">
                                    {{ meeting.alasan_perubahan_meeting }}
                                </b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" v-if="meeting.status == 'terlaksana'">
            <div class="card-body">
                <div class="margin">
                    <small class="text-muted">Dokumen Pendukung</small>
                    <ul
                        class="nav nav-pills mb-3"
                        id="pills-tab"
                        role="tablist"
                    >
                        <li class="nav-item" role="presentation"
                            v-for="(item, idx) in meeting.dokumen_pendukung"
                            :key="idx"
                        >
                            <a
                                class="nav-link btn-sm text-capitalize"
                                :class="{ active: idx === 0 }"
                                :id="'pills-' + item.jenis + '-tab'"
                                data-toggle="pill"
                                :data-target="'#pills-' + item.jenis"
                                type="button"
                                role="tab"
                                :aria-controls="'pills-' + item.jenis"
                                aria-selected="true"
                                @click="selectItem(item, idx)"
                            >
                                {{ item.jenis }}
                            </a>
                        </li>
                    </ul>
                    <a v-for="(item, idx) in itemPendukung.dokumen" :key="idx" :href="item.link" target="_blank">
                        <button class="btn btn-outline-primary btn-sm mr-2 mb-2">
                            {{ item.nama }}
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
