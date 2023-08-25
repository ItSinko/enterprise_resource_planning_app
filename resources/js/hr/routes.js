import VueRouter from "vue-router";
import Dashboard from "./page/meeting";

const routes = [
    {
        path: "/hr/meeting",
        component: Dashboard,
        name: "dashboard"
    }
];

const router = new VueRouter({
    routes,
    mode: "history"
});

export default router;