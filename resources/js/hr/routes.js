import VueRouter from "vue-router";
import Dashboard from "./page/meeting";
import Detail from "./page/meeting/detail";

const routes = [
    {
        path: "/hr/meeting",
        component: Dashboard,
        name: "dashboard"
    },
    {
        path: "/hr/meeting/detail/:id",
        component: Detail,
        name: "detail"
    }
];

const router = new VueRouter({
    routes,
    mode: "history"
});

export default router;