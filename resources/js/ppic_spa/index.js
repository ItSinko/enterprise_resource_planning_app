import Vue from 'vue'
import router from './router'
import store from './store'
import App from './App.vue'

import axios from 'axios'

// global library
import "jquery/dist/jquery"
import "datatables.net/js/jquery.dataTables.min"
import "datatables.net-bulma/js/dataTables.bulma"

import VueSweetalert2 from "vue-sweetalert2"

import 'sweetalert2/dist/sweetalert2.min.css'

const api_token = document.querySelector('meta[name="api-token"]').content
const csrf_token = document.querySelector('meta[name="csrf-token"]').content

store.state.csrf_token = csrf_token;

axios.defaults.headers.common = {
    'Accept': "application/json",
    'Authorization': "Bearer " + api_token,
    'X-CSRF-TOKEN': csrf_token
}
// axios.defaults.baseURL = "http://localhost:8000/"

Vue.use(VueSweetalert2);

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount("#app")
