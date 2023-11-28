import VueRouter from "vue-router";
import Karyawan from "./page/karyawan";
import TambahEditKaryawan from "./page/karyawan/tambahEdit.vue";
import DetailKaryawan from "./page/karyawan/detail";
import FormPengajuan from "./page/pengajuan";
import TambahEditForm from "./page/pengajuan/form";
import DetailPengajuan from "./page/pengajuan/detail";

const routes = [ 
    {
        path: "/hr/karyawan",
        component: Karyawan,
        name: "karyawan"
    },
    {
        path: "/hr/karyawan/tambah",
        component: TambahEditKaryawan,
        name: "tambah-karyawan"
    },
    {
        path: "/hr/karyawan/edit/:id",
        component: TambahEditKaryawan,
        name: "edit-karyawan"
    },
    {
        path: "/hr/karyawan/detail/:id",
        component: DetailKaryawan,
        name: "detail-karyawan"
    },
    {
        path: "/hr/form_pengajuan",
        component: FormPengajuan,
        name: "form-pengajuan"
    },
    {
        path: "/hr/form_pengajuan/tambah",
        component: TambahEditForm,
        name: "tambah-pengajuan"
    },
    {
        path: "/hr/form_pengajuan/edit/:id",
        component: TambahEditForm,
        name: "edit-pengajuan"
    },
    {
        path: "/hr/form_pengajuan/detail/:id",
        component: DetailPengajuan,
        name: "detail-pengajuan"
    }
]

const router = new VueRouter({
    mode: "history",
    routes
});

export default router;