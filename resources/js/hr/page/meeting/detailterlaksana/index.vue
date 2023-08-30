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
            meeting: {
                id: 1,
                judul: "Meeting 1",
                deskripsi: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                tanggal: "2023-01-01",
                mulai: "08:00",
                selesai: "09:00",
                jumlah_peserta: 10,
                status: "terlaksana",
                lokasi: "Gedung A",
                alasan_perubahan_meeting: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.",
                dokumen_pendukung: [
                    {
                        jenis: "foto",
                        dokumen: [
                            {
                                nama: "Foto 1",
                                link: "#",
                            },
                            {
                                nama: "Foto 2",
                                link: "#",
                            },
                            {
                                nama: "Foto 3",
                                link: "#",
                            },
                            {
                                nama: "Foto 4",
                                link: "#",
                            }
                        ]
                    },
                    {
                        jenis: "video",
                        dokumen: [
                            {
                                nama: "video 5",
                                link: "#",
                            },
                            {
                                nama: "video 6",
                                link: "#",
                            },
                            {
                                nama: "video 7",
                                link: "#",
                            },
                            {
                                nama: "video 8",
                                link: "#",
                            }
                        ]
                    },
                    {
                        jenis: "rekaman",
                        dokumen: [
                            {
                                nama: "rekaman 9",
                                link: "#",
                            },
                            {
                                nama: "rekaman 10",
                                link: "#",
                            },
                            {
                                nama: "rekaman 11",
                                link: "#",
                            },
                            {
                                nama: "rekaman 12",
                                link: "#",
                            }
                        ]
                    },
                    {
                        jenis: "dokumen lainnya",
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
                            }
                        ]
                    }
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
        <HeaderDetail :meeting="meeting" />
        <Item :meeting="meeting" />
    </div>
</template>
