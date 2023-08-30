import VueRouter from "vue-router";
import Dashboard from "./page/meeting";
import DetailTerlaksana from "./page/meeting/detailterlaksana";
import DetailNonTerlaksana from "./page/meeting/detailnonterlaksana";

const routes = [
    {
        path: "/hr/meeting",
        component: Dashboard,
        name: "dashboard"
    },
    {
        path: "/hr/meeting/detailterlaksana/:id",
        component: DetailTerlaksana,
        name: "detail-meeting-terlaksana"
    },
    {
        path: "/hr/meeting/detailnonterlaksana/:id",
        component: DetailNonTerlaksana,
        name: "detail-meeting-nonterlaksana"
    }
];

const router = new VueRouter({
    routes,
    mode: "history"
});

export default router;