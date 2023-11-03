
export default {
    install(Vue) {
        Vue.prototype.numberOnly = function (event) {
            const keyCode = event.keyCode ? event.keyCode : event.which
            const keyValue = String.fromCharCode(keyCode)
            if (/\D/.test(keyValue)) {
                event.preventDefault()
            }
        }
    }
}