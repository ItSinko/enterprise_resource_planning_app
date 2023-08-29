<script>
import Status from "../../../components/status.vue";
import moment from "moment";

export default {
    props: ["meeting"],
    components: {
        Status,
    },
    methods: {
        changeFormatDate(date) {
            return moment(date).lang("id").format("dddd, DD MMMM YYYY");
        },
    },  
};
</script>
<template>
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
                                        >{{ meeting.jumlah_peserta }} Peserta</b
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
                </div>
            </div>
        </div>
    </div>
</template>
