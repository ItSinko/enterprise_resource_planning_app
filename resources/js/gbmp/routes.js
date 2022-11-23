import VueRouter from "vue-router";

import Transfer from "./page/transfer";
const routes = [
    {
        path: "/gbmp/transfer",
        name: "transfer",
        component: Transfer
    }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;