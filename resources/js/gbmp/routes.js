import VueRouter from "vue-router";

import Transfer from "./page/transfer";
import BarangMasuk from "./page/barangmasuk";
import DetailBarangMasuk from "./page/barangmasuk/dalamproses/detail";
import BahanBakuRequest from "./page/permintaanbahanbaku";
import PenyerahanBarang from "./page/penyerahanbarang";
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
    },
    {
        path: "/gbmp/bahan-baku-request",
        name: "bahanbakurequest",
        component: BahanBakuRequest
    },
    {
        path: "/gbmp/permintaan-bahan-baku",
        name: "permintaanbahanbaku",
        component: BahanBakuRequest
    },
    // Penyerahan Barang
    {
        path: "/gbmp/penyerahan-barang",
        name: "penyerahanbarang",
        component: PenyerahanBarang
    }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;