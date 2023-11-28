import VueRouter from "vue-router";
import setPemetian from './page/setPemetian'
import detailPemetian from './page/setPemetian/proses/detail'

const routes = [
    {
        path: "/logistik/pengiriman/pemetian",
        component: setPemetian,
        name: "setPemetian"
    },
    {
        path: "/logistik/pengiriman/pemetian/:id",
        component: detailPemetian,
        name: "detailPemetian"
    }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;
