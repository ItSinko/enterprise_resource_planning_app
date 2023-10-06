import VueRouter from "vue-router";
import Karyawan from "./page/karyawan";
import TambahEditKaryawan from "./page/karyawan/tambahEdit.vue";
import DetailKaryawan from "./page/karyawan/detail";

const routes = [
    {
        path: "/hr/karyawan",
        component: Karyawan,
        name: "karyawan"
    },
    {
        path: "/hr/karyawan/tambah",
        component: TambahEditKaryawan,
        name: "tambah-karyawan"
    },
    {
        path: "/hr/karyawan/edit/:id",
        component: TambahEditKaryawan,
        name: "edit-karyawan"
    },
    {
        path: "/hr/karyawan/detail/:id",
        component: DetailKaryawan,
        name: "detail-karyawan"
    },
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;