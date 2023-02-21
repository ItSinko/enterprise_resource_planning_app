import Vue from 'vue'
import Index from './Index.vue'
import VueSweetalert2 from 'vue-sweetalert2';
import VueRouter from 'vue-router';
import router from './router'
import "jquery/dist/jquery"
import "datatables.net/js/jquery.dataTables.min"
import Select from 'vue-select'
import "vue-select/dist/vue-select.css";
import './index.css'
import mix from './mix'

window.Vue = require('vue').default;
Vue.use(VueSweetalert2);
Vue.use(VueRouter);
Vue.component('v-select', Select);
Vue.mixin(mix);

const app = new Vue({
    el: '#app',
    router,
    components: {
        "index": Index
    },
});