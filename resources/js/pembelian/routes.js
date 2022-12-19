import VueRouter from "vue-router";

// Supplier
import Supplier from "./page/supplier";
import SupplierTambah from "./page/supplier/tambahedit.vue";
import SupplierDetail from "./page/supplier/detail";
// Permintaan
import Permintaan from "./page/permintaan";
import PermintaanTambahDalamProses from "./page/permintaan/dalamproses/tambah";
// Purchase Order
import PurchaseOrder from "./page/purchaseorder";
import PurchaseOrderDetail from "./page/purchaseorder/detail";
const routes = [
    // Supplier
    {
        path: "/pembelian/supplier",
        component: Supplier,
        name: "supplier",
    },
    {
        path: "/pembelian/supplier/create",
        component: SupplierTambah,
        name: "supplierTambah",
    },
    {
        path: "/pembelian/supplier/:id/edit",
        component: SupplierTambah,
        props: {
            title: "Edit Supplier",
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
        path: "/pembelian/supplier/:id",
        component: SupplierDetail,
        name: "supplierDetail",
    },
    // Permintaan
    {
        path: "/pembelian/permintaan",
        component: Permintaan,
        name: "permintaan",
    },
    {
        path: "/pembelian/create",
        component: PermintaanTambahDalamProses,
        name: "permintaanTambahDalamProses",
    },
    // Purchase Order
    {
        path: "/pembelian/po",
        component: PurchaseOrder,
        name: "purchaseorder",
    },
    {
        path: "/pembelian/detail",
        component: PurchaseOrderDetail,
        name: "purchaseorder",
    }
];

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;