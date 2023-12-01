import VueRouter from "vue-router";
import setPemetian from './page/setPemetian'
import detailPemetian from './page/setPemetian/proses/detail'
import detailPengkardusan from './page/setPemetian/kardus/detail'
import detailPembagian from './page/setPemetian/kardus/pembagian'    

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
    },
    {
        path: "/logistik/pengiriman/pengkardusan/pembagian/:id",
        component: detailPembagian,
        name: "detailPembagian"
    },
    {
        path: "/logistik/pengiriman/pengkardusan/setKardus/:id",
        component: detailPengkardusan,
        name: "detailPengkardusan"
    }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;
