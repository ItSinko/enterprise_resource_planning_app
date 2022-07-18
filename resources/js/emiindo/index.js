import Vue from 'vue'
import Index from './Index.vue'

window.Vue = require('vue').default;
import "jquery/dist/jquery"
import "datatables.net/js/jquery.dataTables.min"

new Vue({
    render: h => h(Index)
}).$mount('#app')