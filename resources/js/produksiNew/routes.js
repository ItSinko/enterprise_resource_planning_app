import VueRouter from "vue-router";
import PermintaanReworks from './page/permintaan'
import prosesSetReworks from './page/prosesSet'
import prosesSetReworksDetail from './page/prosesSet/proses/detail'
// import perakitanBerlangsung from './Page/perakitanBerlangsung'

const routes = [
    {
        path: "/produksi/permintaanreworks",
        component: PermintaanReworks,
        name: "permintaanreworks"
    },
    {
        path: "/produksi/prosesSetReworks",
        component: prosesSetReworks,
        name: "prosesSetReworks"
    },
    {
        path: "/produksi/prosesSetReworks/:id",
        component: prosesSetReworksDetail,
        name: "prosesSetReworksDetail"
    },
    // {
    //     path: "/produksi/jadwal_perakitan",
    //     component: perakitanBerlangsung,
    //     name: "perakitanBerlangsung"
    // }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;