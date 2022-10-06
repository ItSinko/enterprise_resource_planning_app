import VueRouter from 'vue-router';
import SoEdit from './Page/so/Edit'

const routes = [
    {
        path: '/penjualan/so/edit/:jenis/:id',
        name: 'detail',
        component: SoEdit
    }
];

const router = new VueRouter({
    routes,
    mode: 'history'
});

export default router;
