import VueRouter from "vue-router";
import Dashboard from "./page/meeting";
import DetailTerlaksana from "./page/meeting/detailterlaksana";
import DetailNonTerlaksana from "./page/meeting/detailnonterlaksana";
import JadwalMeetingPeserta from "./page/peserta"
import JadwalMeetingPesertaDetailTerlaksana from "./page/peserta/detailterlaksana"
import JadwalMeetingPesertaDetailNonTerlaksana from "./page/peserta/detailnonterlaksana"

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
    },
    {
        path: "/meeting/jadwal_meet_terlaksana/:id",
        component: JadwalMeetingPesertaDetailTerlaksana,
        name: "jadwal-meeting-peserta-detail-terlaksana"
    },
    {
        path: "/meeting/jadwal_meet_nonterlaksana/:id",
        component: JadwalMeetingPesertaDetailNonTerlaksana,
        name: "jadwal-meeting-peserta-detail-nonterlaksana"
    }
];

const router = new VueRouter({
    routes,
    mode: "history"
});

export default router;