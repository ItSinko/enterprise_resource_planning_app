import VueRouter from "vue-router";
import Retur from "./page/retur";
import ReturFormMasuk from "./page/retur/masuk/create";
import ReturFormKeluar from "./page/retur/keluar/create";

const routes = [
    {
        path: "/as/retur/formMasuk",
        component: ReturFormMasuk,
        name: "retur-form-masuk"
    },
    {
        path: "/as/retur/formKeluar",
        component: ReturFormKeluar,
        name: "retur-form-keluar"
    },
    {
        path: "/as/retur/",
        component: Retur,
        name: "retur"
    }
];

const router = new VueRouter({
    routes,
    mode: "history"
});

export default router;