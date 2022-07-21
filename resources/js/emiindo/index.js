import Vue from 'vue'
import axios from 'axios'
import Index from './Index.vue'
import "jquery/dist/jquery"
import "datatables.net/js/jquery.dataTables.min"

window.Vue = require('vue').default;
const app = new Vue({
    el: '#app',
    components: {
        "index": Index
    },
});
