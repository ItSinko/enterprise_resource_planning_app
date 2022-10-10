import Vue from "vue";
import VueRouter from "vue-router";

import Home from "../../ppic_spa/views/Home.vue";
import Perakitan from "../../ppic_spa/views/Perakitan.vue";
import SalesOrder from "../../ppic_spa/views/SalesOrder.vue";
import LaporanPesanan from "../../ppic_spa/views/LaporanPesanan.vue";
import ProsesPesanan from "../../ppic_spa/views/ProsesPesanan.vue";
import JadwalPerencanaan from "../../ppic_spa/views/JadwalPerencanaan.vue";
import JadwalPelaksanaan from "../../ppic_spa/views/JadwalPelaksanaan.vue";
import PersetujuanPerencanaan from "../views/PersetujuanPerencanaan.vue";
import PersetujuanPelaksanaan from "../views/PersetujuanPelaksanaan.vue";
import Outgoing from "../views/Outgoing.vue";

Vue.use(VueRouter);

const routes = [
    {
        path: "/manager-teknik",
        name: "Home",
        component: Home,
    },
    {
        path: "/manager-teknik/perakitan",
        name: "Perakitan",
        component: Perakitan,
    },
    {
        path: "/manager-teknik/so",
        name: "SalesOrder",
        component: SalesOrder,
    },
    {
        path: "/manager-teknik/laporan_pesanan",
        name: "LaporanPesanan",
        component: LaporanPesanan,
    },
    {
        path: "/manager-teknik/proses_pesanan",
        name: "ProsesPesanan",
        component: ProsesPesanan,
    },
    {
        path: "/manager-teknik/outgoing",
        name: "Outgoing",
        component: Outgoing,
    },
    {
        path: "/manager-teknik/jadwal_perencanaan",
        name: "JadwalPerencanaan",
        component: JadwalPerencanaan,
    },
    {
        path: "/manager-teknik/jadwal_pelaksanaan",
        name: "JadwalPelaksanaan",
        component: JadwalPelaksanaan,
    },
    {
        path: "/manager-teknik/persetujuan_perencanaan",
        name: "PersetujuanPerencanaan",
        component: PersetujuanPerencanaan,
    },
    {
        path: "/manager-teknik/persetujuan_pelaksanaan",
        name: "PersetujuanPelaksanaan",
        component: PersetujuanPelaksanaan,
    },
];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes,
});

export default router;
