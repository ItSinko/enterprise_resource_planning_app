import Vue from 'vue'
import Index from './Index.vue'
import router from './routes'
import VueRouter from 'vue-router';
import VueSweetalert2 from "vue-sweetalert2"
import Vuex from 'vuex';
import storeData from './store'
import DateFormat from './plugins/dateFormat'
import numberOnly from './plugins/numberOnly'
import "vue-select/dist/vue-select.css";

import 'jquery/dist/jquery.js'
import 'bootstrap/dist/js/bootstrap.js'

window.Vue = require('vue').default;
Vue.use(VueRouter);
Vue.use(VueSweetalert2);
Vue.use(DateFormat);
Vue.use(numberOnly)

const store = new Vuex.Store(storeData);

const app = new Vue({
    el: '#app',
    store,
    router,
    components: {
        "index": Index
    }
}); 