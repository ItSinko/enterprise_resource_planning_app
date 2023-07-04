import Vue from 'vue'
import Index from './Index.vue'
import router from './routes'
import VueRouter from 'vue-router';
import VueSweetalert2 from "vue-sweetalert2"
import Vuex from 'vuex';
import storeData from './store'
import "jquery/dist/jquery"
import "vue-select/dist/vue-select.css";
import "datatables.net/js/jquery.dataTables.min"
import vSelect from "vue-select";

window.Vue = require('vue').default;
Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(VueSweetalert2);
Vue.component("v-select", vSelect);

const store = new Vuex.Store(storeData);

const app = new Vue({
    el: '#app',
    store,
    router,
    components: {
        "index": Index
    }
}); 