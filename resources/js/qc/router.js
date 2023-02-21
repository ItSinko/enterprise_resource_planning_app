import VueRouter from "vue-router";

import BarangMasuk from "./page/barangmasuk";

const routes = [
    {
        path: "/qcinc/barang-masuk",
        name: "barangmasuk",
        component: BarangMasuk
    }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;