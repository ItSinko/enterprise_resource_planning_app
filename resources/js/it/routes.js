import VueRouter from "vue-router";
import Kategori from "./page/kategori";
import FinishGoods from "./page/finishgoods";
import Reworks from "./page/reworks";

const routes = [
    {
        path: "/administrator/kategori_produk",
        component: Kategori,
        name: "kategori"
    },
    {
        path: "/administrator/finishgoods",
        component: FinishGoods,
        name: "finishgoods"
    },
    {
        path: "/administrator/reworks",
        component: Reworks,
        name: "reworks"
    },
];

const router = new VueRouter({
    routes,
    mode: "history"
});

export default router;