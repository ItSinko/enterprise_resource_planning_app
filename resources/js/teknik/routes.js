import VueRouter from "vue-router";

import Part from "./page/part.vue";
import Produk from "./page/produk.vue";
import BarangMasuk from "./page/barangMasuk.vue";
import PurchaseOrder from "./page/purchaseOrder.vue";

const routes = [
    {
        path: "/teknik/part",
        component: Part
    },
    {
        path: "/teknik/produk",
        component: Produk
    },
    {
        path: "/teknik/barangMasuk",
        component: BarangMasuk
    },
    {
        path: "/teknik/purchaseOrder",
        component: PurchaseOrder
    }
];

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;