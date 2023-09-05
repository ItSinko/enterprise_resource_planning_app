import VueRouter from "vue-router";
import Dashboard from "./page/meeting";
import DetailTerlaksana from "./page/meeting/detailterlaksana";
import DetailNonTerlaksana from "./page/meeting/detailnonterlaksana";
import JadwalMeetingPeserta from "./page/peserta"

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
    },
    {
        path: "/meeting/jadwal_meet",
        component: JadwalMeetingPeserta,
        name: "jadwal-meeting-peserta"
    }
];

const router = new VueRouter({
    routes,
    mode: "history"
});

export default router;