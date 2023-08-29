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
                deskripsi: "Deskripsi Meeting 1",
                tanggal: "2023-01-01",
                mulai: "08:00",
                selesai: "09:00",
                jumlah_peserta: 10,
                status: "belum_terlaksana",
                lokasi: "Gedung A",
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
