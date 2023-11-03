/**
 * @name Index
 * @summary This document is the entry point of PPIC project
 */

import Vue from 'vue'
import router from './router'
import store from './store'
import plugins from './plugins'
import numberOnly from './plugins/numberOnly'
import App from './App.vue'

import axios from 'axios'
import $ from "jquery"
import Pusher from "pusher-js"
import vSelect from 'vue-select'
 
// global library 
import "jquery/dist/jquery"
import "datatables.net/js/jquery.dataTables.min"
import "datatables-bulma/js/dataTables.bulma"
import '@mdi/font/css/materialdesignicons.css'


import VueSweetalert2 from "vue-sweetalert2"

 $.fn.DataTable.ext.pager.simple_numbers_no_ellipses = plugins.simple_numbers_no_ellipses

/**
 * @constant {String} api_token  constant that store api token used for authorization
 */
const api_token = document.querySelector('meta[name="api-token"]').content
/**
 * @constant {String} csrf_token constant that store csrf token used for form handler
 */
const csrf_token = document.querySelector('meta[name="csrf-token"]').content

store.state.csrf_token = csrf_token;

axios.defaults.headers.common = {
    'Accept': "application/json",
    'Authorization': "Bearer " + api_token,
    'X-CSRF-TOKEN': csrf_token
}
// axios.defaults.baseURL = "http://localhost:8000/"

Vue.use(VueSweetalert2);
Vue.component('v-select', vSelect)
Vue.use(numberOnly)

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount("#app")

if (store.state.enable_notif){
    let pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true
    });
    
    let channel = pusher.subscribe('notif');
    
    channel.bind("App\\Events\\PpicNotif", (data) => {
        store.commit('setNotif', {
            user: data.user,
            status: data.status,
            state: data.state
        })
    })
}