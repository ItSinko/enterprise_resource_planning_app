import Vue from 'vue'
import axios from 'axios'
import Index from './Page/so/Edit.vue'
import router from './routes'
import VueRouter from 'vue-router';
// import Store from './store'
// import Vuex from 'vuex'
import "jquery/dist/jquery"
import "datatables.net/js/jquery.dataTables.min"
import "vue-select/dist/vue-select.css";
import VueSweetalert2 from 'vue-sweetalert2';

window.Vue = require('vue').default;
Vue.use(VueRouter);
// Vue.use(Vuex);
Vue.use(VueSweetalert2);

// const store = new Vuex.Store(Store);

const app = new Vue({
    el: '#app',
    router,
    // store,
    components: {
        "index": Index
    },
});
