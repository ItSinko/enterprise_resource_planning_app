export default {
    install(Vue) {
        Vue.prototype.numberOnly = function (event) {
            const charCode = (event.which) ? event.which : event.keyCode
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault()
            }
            return true
        }
    }
}