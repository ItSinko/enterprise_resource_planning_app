import VueRouter from "vue-router";

import Supplier from "./page/supplier";

const routes = [
    {
        path: "/pembelian/supplier",
        component: Supplier,
        name: "supplier",
    }
];

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;