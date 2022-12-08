import VueRouter from "vue-router";

import Part from "./page/part.vue";
import Produk from "./page/produk.vue";
import Bom from "./page/bom.vue";
import ProdukTambah from "./subproduk/tambahEdit.vue";
import ProdukDetail from "./subproduk/detail.vue";
import PurchaseOrder from "./page/po";
import DetailPO from "./page/po/detail";


const routes = [
    {
        path: "/teknik/part",
        component: Part
    },
    {
        path: "/teknik/produk",
        component: Produk
    },
    {
        path: "/teknik/detailPO",
        component: DetailPO
    },
    {
        path: "/teknik/purchaseOrder",
        component: PurchaseOrder
    },
    {
        path: "/teknik/produk/tambah",
        component: ProdukTambah,
    },
    {
        path: "/teknik/produk/edit/:id",
        component: ProdukTambah,
        props: { 
            judul: "Edit Produk",
            breadcumbs: [
                {
                    name: 'Teknik',
                    link: '/teknik'
                }, {
                    name: 'Daftar Produk',
                    link: '/teknik/produk'
                }, {
                    name: 'Edit',
                    link: '/teknik/produk/edit'
                }
            ],
        }
    },
    {
        path: "/teknik/produk/detail/:id",
        component: ProdukDetail,
    },
    {
        path: "/teknik/bom",
        component: Bom,
    }
];

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;