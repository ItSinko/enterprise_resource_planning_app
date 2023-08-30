<script>
import Header from "../../../components/header.vue";
import HeaderDetail from "./header.vue";
import Kehadiran from "./kehadiran";
import HasilNotulensi from "./notulen.vue";
import HasilMeeting from "./hasilmeeting.vue";
import DokumenPendukung from "./dokumenpendukung.vue";
export default {
    components: {
        Header,
        HeaderDetail,
        Kehadiran,
        HasilNotulensi,
        HasilMeeting,
        DokumenPendukung,
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
            meeting: {
                id: 1,
                no_meeting: "Meet-1",
                judul: "Meeting 1",
                deskripsi:
                    "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                tanggal: "2023-01-01",
                mulai: "08:00",
                selesai: "09:00",
                notulen: "Notulen 1",
                moderator: "Moderator 1",
                jumlah_peserta: 10,
                status: "terlaksana",
                lokasi: "Gedung A",
                alasan_perubahan_meeting:
                    "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                dokumen_pendukung: [
                    {
                        jenis: "foto",
                        dokumen: [
                            {
                                nama: "Foto 1",
                                link: "https://picsum.photos/200/300",
                            },
                            {
                                nama: "Foto 2",
                                link: "https://picsum.photos/200/300",
                            },
                            {
                                nama: "Foto 3",
                                link: "https://picsum.photos/200/300",
                            },
                            {
                                nama: "Foto 4",
                                link: "https://picsum.photos/200/300",
                            },
                            {
                                nama: "Foto 4",
                                link: "https://picsum.photos/200/300",
                            },
                        ],
                    },
                    {
                        jenis: "video",
                        dokumen: [
                            {
                                nama: "video 5",
                                link: "https://www.youtube.com/embed/D0UnqGm_miA?si=s1ltpng3TIeC1sGF",
                            },
                            {
                                nama: "video 6",
                                link: "https://www.youtube.com/embed/D0UnqGm_miA?si=s1ltpng3TIeC1sGF",
                            },
                            {
                                nama: "video 7",
                                link: "https://www.youtube.com/embed/D0UnqGm_miA?si=s1ltpng3TIeC1sGF",
                            },
                            {
                                nama: "video 8",
                                link: "https://www.youtube.com/embed/D0UnqGm_miA?si=s1ltpng3TIeC1sGF",
                            },
                        ],
                    },
                    {
                        jenis: "rekaman",
                        dokumen: [
                            {
                                nama: "rekaman 9",
                                link: "https://www.learningcontainer.com/wp-content/uploads/2020/02/Kalimba.mp3",
                            },
                            {
                                nama: "rekaman 10",
                                link: "https://www.learningcontainer.com/wp-content/uploads/2020/02/Kalimba.mp3",
                            },
                            {
                                nama: "rekaman 11",
                                link: "https://www.learningcontainer.com/wp-content/uploads/2020/02/Kalimba.mp3",
                            },
                            {
                                nama: "rekaman 12",
                                link: "https://www.learningcontainer.com/wp-content/uploads/2020/02/Kalimba.mp3",
                            },
                            {
                                nama: "rekaman 12",
                                link: "https://www.learningcontainer.com/wp-content/uploads/2020/02/Kalimba.mp3",
                            },
                        ],
                    },
                    {
                        jenis: "dokumen",
                        dokumen: [
                            {
                                nama: "Dokumen 13",
                                link: "#",
                            },
                            {
                                nama: "Dokumen 14",
                                link: "#",
                            },
                            {
                                nama: "Dokumen 15",
                                link: "#",
                            },
                            {
                                nama: "Dokumen 16",
                                link: "#",
                            },
                            {
                                nama: "Dokumen 16",
                                link: "#",
                            },
                        ],
                    },
                ],
                kehadiran: [
                    {
                        id: 1,
                        no_meeting: "Meet-1",
                        judul: "Meeting 1",
                        deskripsi:
                            "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                        tanggal: "2023-01-01",
                        notulen: "Notulen 1",
                        moderator: "Moderator 1",
                        mulai: "08:00",
                        selesai: "09:00",
                        jumlah_peserta: 10,
                        status: "belum_terlaksana",
                        alasan_perubahan_meeting:
                            "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
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
                        ],
                    },
                    {
                        id: 2,
                        no_meeting: "Meet-1",
                        judul: "Meeting 1",
                        deskripsi:
                            "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                        tanggal: "2023-02-01",
                        notulen: "Notulen 1",
                        moderator: "Moderator 1",
                        mulai: "09:00",
                        selesai: "10:00",
                        jumlah_peserta: 10,
                        status: "terlaksana",
                        lokasi: "Gedung A",
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
                        ],
                    },
                ],
                hasil_notulensi: [
                    {
                        penanggungjawab: "Tan Evi",
                        divisi: "Divisi 1",
                        isi: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                        kesesuaian: "sesuai",
                        created_at: "2023-01-01 08:00",
                    },
                    {
                        penanggungjawab: "Bangkit Nata Satria ",
                        divisi: "Divisi 1",
                        isi: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                        kesesuaian: "tidak_sesuai",
                        created_at: "2023-01-01 08:00",
                        catatan: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                    },
                ],
                hasil_rapat: [
                    {
                        isi: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                    },
                    {
                        isi: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                    },
                ],
            },
        };
    },
    methods: {
        closeModal() {
            $(".modalDetail").modal("hide");
            this.$nextTick(() => {
                this.$emit("closeModal");
            });
        },
    },
};
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <!-- menampilkan data terbaru -->
        <HeaderDetail :meeting="meeting" />
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a
                    class="nav-link active"
                    id="pills-kehadiran-tab"
                    data-toggle="pill"
                    data-target="#pills-kehadiran"
                    type="button"
                    role="tab"
                    aria-controls="pills-kehadiran"
                    aria-selected="true"
                >
                    Kehadiran
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a
                    class="nav-link"
                    id="pills-hasil-notulensi-tab"
                    data-toggle="pill"
                    data-target="#pills-hasil-notulensi"
                    type="button"
                    role="tab"
                    aria-controls="pills-hasil-notulensi"
                    aria-selected="false"
                >
                    Hasil Notulensi
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a
                    class="nav-link"
                    id="pills-hasil-meeting-tab"
                    data-toggle="pill"
                    data-target="#pills-hasil-meeting"
                    type="button"
                    role="tab"
                    aria-controls="pills-hasil-meeting"
                    aria-selected="false"
                >
                    Hasil Meeting
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a
                    class="nav-link"
                    id="pills-dokumen-pendukung-tab"
                    data-toggle="pill"
                    data-target="#pills-dokumen-pendukung"
                    type="button"
                    role="tab"
                    aria-controls="pills-dokumen-pendukung"
                    aria-selected="false"
                >
                    Dokumen Pendukung
                </a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div
                class="tab-pane fade show active"
                id="pills-kehadiran"
                role="tabpanel"
                aria-labelledby="pills-kehadiran-tab"
            >
                <kehadiran :meeting="meeting.kehadiran" />
            </div>
            <div
                class="tab-pane fade"
                id="pills-hasil-notulensi"
                role="tabpanel"
                aria-labelledby="pills-hasil-notulensi-tab"
            >
                <hasil-notulensi :meeting="meeting.hasil_notulensi" />
            </div>
            <div
                class="tab-pane fade"
                id="pills-hasil-meeting"
                role="tabpanel"
                aria-labelledby="pills-hasil-meeting-tab"
            >
                <hasil-meeting :meeting="meeting.hasil_rapat" />
            </div>
            <div
                class="tab-pane fade"
                id="pills-dokumen-pendukung"
                role="tabpanel"
                aria-labelledby="pills-dokumen-pendukung-tab"
            >
                <dokumen-pendukung :meeting="meeting.dokumen_pendukung" />
            </div>
        </div>
    </div>
</template>
