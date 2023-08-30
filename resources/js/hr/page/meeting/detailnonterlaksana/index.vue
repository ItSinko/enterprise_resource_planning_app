<script>
import Header from "../../../components/header.vue";
import HeaderDetail from "./header.vue";
import Item from "./item.vue";  
export default {
    components: {
        Header,
        HeaderDetail,
        Item,
    },
    data() {
        return {
            title: "Detail Meeting",
            breadcumbs: [
                {
                    name: "Beranda",
                    link: "#",
                },
                {
                    name: "Meeting",
                    link: "/hr/meeting",
                },
                {
                    name: "Detail Meeting",
                    link: "/hr/meeting/detail",
                },
            ],
            meeting: [
                {
                    id: 1,
                    no_meeting: "Meet-1",
                    deskripsi: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                    tanggal: "2023-01-01",
                    notulen: "Notulen 1",
                    moderator: "Moderator 1",
                    mulai: "08:00",
                    selesai: "09:00",
                    jumlah_peserta: 10,
                    status: "belum_terlaksana",
                    alasan_perubahan_meeting: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                    lokasi: "Gedung A",
                    peserta: [
                        {
                            nama: "Peserta 1",
                            divisi: "Divisi 1",
                            alasan: "Catatan Peserta 1",
                            kehadiran: "hadir",
                        },
                        {
                            nama: "Peserta 2",
                            divisi: "Divisi 2",
                            kehadiran: "tidak_hadir",
                            alasan: "Catatan Peserta 2",
                            dokumen_pendukung: [
                                {
                                    nama: "Dokumen 1",
                                    link: "#",
                                },
                                {
                                    nama: "Dokumen 2",
                                    link: "#",
                                },
                            ],
                        },
                    ]
                },
                {
                    id: 2,
                    no_meeting: "Meet-1",
                    judul: "Meeting 1",
                    deskripsi: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                    tanggal: "2023-02-01",
                    notulen: "Notulen 1",
                    moderator: "Moderator 1",
                    mulai: "09:00",
                    selesai: "10:00",
                    jumlah_peserta: 10,
                    status: "batal",
                    lokasi: "Gedung A",
                    alasan_pembatalan_meeting: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                    peserta: [
                        {
                            nama: "Peserta 3",
                            divisi: "Divisi 3",
                            alasan: "Catatan Peserta 1",
                            kehadiran: "hadir",
                        },
                        {
                            nama: "Peserta 4",
                            divisi: "Divisi 4",
                            kehadiran: "tidak_hadir",
                            alasan: "Catatan Peserta 2",
                            dokumen_pendukung: [
                                {
                                    nama: "Dokumen 1",
                                    link: "#",
                                },
                                {
                                    nama: "Dokumen 2",
                                    link: "#",
                                },
                            ],
                        },
                    ]
                },
                {
                    id: 2,
                    no_meeting: "Meet-1",
                    judul: "Meeting 1",
                    deskripsi: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                    tanggal: "2023-02-01",
                    notulen: "Notulen 1",
                    moderator: "Moderator 1",
                    mulai: "09:00",
                    selesai: "10:00",
                    jumlah_peserta: 10,
                    status: "batal",
                    lokasi: "Gedung A",
                    alasan_pembatalan_meeting: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                    peserta: [
                        {
                            nama: "Peserta 3",
                            divisi: "Divisi 3",
                            alasan: "Catatan Peserta 1",
                            kehadiran: "hadir",
                        },
                        {
                            nama: "Peserta 4",
                            divisi: "Divisi 4",
                            kehadiran: "tidak_hadir",
                            alasan: "Catatan Peserta 2",
                            dokumen_pendukung: [
                                {
                                    nama: "Dokumen 1",
                                    link: "#",
                                },
                                {
                                    nama: "Dokumen 2",
                                    link: "#",
                                },
                            ],
                        },
                    ]
                },
            ],
            itemMeetingSelected: [],
            selectedData: 0,
        };
    },
    methods: {
        closeModal() {
            $(".modalDetail").modal("hide");
            this.$nextTick(() => {
                this.$emit("closeModal");
            });
        },
        selectItem(item, idx){
            this.selectedData = idx;
            this.itemMeetingSelected = JSON.parse(JSON.stringify(item));
        },
    },
    created() {
        this.itemMeetingSelected = this.meeting[0];
    },
    computed: {
        lengthMeeting() {
            return this.meeting.length;
        },
    },
};
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li
                    class="nav-item"
                    role="presentation"
                    v-for="(item, idx) in meeting"
                    :key="idx"
                >
                    <a
                        class="nav-link"
                        :id="'pills-' + idx + '-tab'"
                        :class="{ active: idx === 0 }"
                        data-toggle="pill"
                        :data-target="'#pills-' + idx"
                        type="button"
                        role="tab"
                        :aria-controls="'pills-' + idx"
                        @click="selectItem(item, idx)"
                    >
                        Rencana Meeting {{ idx + 1 }}
                    </a>
                </li>
            </ul>
        <HeaderDetail :meeting="itemMeetingSelected" :lengthMeet="lengthMeeting" :selectedIndex="selectedData"/>
        <Item :meeting="itemMeetingSelected.peserta" />
    </div>
</template>
