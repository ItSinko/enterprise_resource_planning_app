import VueRouter from "vue-router";

import Supplier from "./page/supplier";
import SupplierTambah from "./page/supplier/tambahedit.vue";
import SupplierDetail from "./page/supplier/detail";

const routes = [
    {
        path: "/pembelian/supplier",
        component: Supplier,
        name: "supplier",
    },
    {
        path: "/pembelian/edit",
        component: SupplierTambah,
        name: "supplierTambah",
    },
    {
        path: "/pembelian/edit",
        component: SupplierTambah,
        props: {
            judul: "Edit Supplier",
            breadcumbs: [{
                name: 'Beranda',
                link: '#'
            }, {
                name: 'Supplier',
                link: '/pembelian/supplier'
            }, {
                name: 'Edit Supplier',
                link: '/pembelian/supplier/edit/:id'
            }]
        }
    },
    {
        path: "/pembelian/detail",
        component: SupplierDetail,
        name: "supplierDetail",
    }
    
];

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;