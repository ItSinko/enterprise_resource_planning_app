import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '../views/Home.vue'
import GudangBarangJadi from "../views/GudangBarangJadi.vue"
import GudangKarantina from "../views/GudangKarantina.vue"
import Perakitan from "../views/Perakitan.vue"
import SalesOrder from "../views/SalesOrder.vue"
import LaporanPesanan from "../views/LaporanPesanan.vue"
import ProsesPesanan from "../views/ProsesPesanan.vue"
import JadwalPerencanaan from "../views/JadwalPerencanaan.vue"
import JadwalPelaksanaan from "../views/JadwalPelaksanaan.vue"

/**
 * This module is defined routes component in PPIC project
 * @namespace Router
 */

Vue.use(VueRouter)
/**
 * This constant is array of object of all routes used for routing
 * @memberof Router
 */
const routes = [
    {
        path: '/ppic',
        name: 'Home',
        component: Home
    },
    {
        path: '/ppic/gbj',
        name: 'GBJ',
        component: GudangBarangJadi,
    },
    {
        path: '/ppic/gk',
        name: 'GK',
        component: GudangKarantina,
    },
    {
        path: '/ppic/perakitan',
        name: 'Perakitan',
        component: Perakitan,
    },
    {
        path: '/ppic/so',
        name: 'SalesOrder',
        component: SalesOrder,
    },
    {
        path: '/ppic/jadwal_perencanaan',
        name: 'JadwalPerencanaan',
        component: JadwalPerencanaan,
    },
    {
        path: '/ppic/jadwal_pelaksanaan',
        name: 'JadwalPelaksanaan',
        component: JadwalPelaksanaan,
    },
    {
        path: '/ppic/laporan_pesanan',
        name: 'LaporanPesanan',
        component: LaporanPesanan,
    },
    {
        path: '/ppic/proses_pesanan',
        name: 'ProsesPesanan',
        component: ProsesPesanan,
    },

]

/**
 * This constant is vue router object created to handle all url routing with
 * some option setting
 * @memberof Router
 */
const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

export default router
