import VueRouter from "vue-router";

import Transfer from "./page/transfer";
import BarangMasuk from "./page/barangmasuk";
const routes = [
    {
        path: "/gbmp/transfer",
        name: "transfer",
        component: Transfer
    },
    {
        path: "/gbmp/barang-masuk",
        name: "barangmasuk",
        component: BarangMasuk
    }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;