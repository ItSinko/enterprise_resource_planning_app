import VueRouter from "vue-router";

import Transfer from "./page/transfer";
import BarangMasuk from "./page/barangmasuk";
import DetailBarangMasuk from "./page/barangmasuk/dalamproses/detail";
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
    },
    {
        path: "/gbmp/barang-masuk/dalam-proses/:id",
        name: "barangmasukdalamproses",
        component: DetailBarangMasuk
    }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;