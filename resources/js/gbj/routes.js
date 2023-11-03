import VueRouter from "vue-router";
import PermintaanReworkGBJ from "./page/PermintaanReworkGBJ";
import penerimaanRework from "./page/penerimaanRework";
import penggantianRework from "./page/penggantianRework";

const routes = [
    {
        path: "/gbj/rework/permintaan-rework",
        component: PermintaanReworkGBJ
    },
    {
        path: "/gbj/rework/penerimaan-rework",
        component: penerimaanRework
    },
    {
        path: "/gbj/rework/penggantian-rework",
        component: penggantianRework
    }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;