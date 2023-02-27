import VueRouter from "vue-router";

import BarangMasuk from "./page/barangmasuk";
import BarangMasukDetail from "./page/barangmasuk/detail";

const routes = [
    {
        path: "/qcinc/barang-masuk",
        name: "barangmasuk",
        component: BarangMasuk
    },
    {
        path: "/qcinc/barang-masuk-detail/:id",
        name: "barangmasukdetail",
        component: BarangMasukDetail
    }

]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;