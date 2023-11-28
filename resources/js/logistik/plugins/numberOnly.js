export default {
    install(Vue) {
        Vue.prototype.numberOnly = function ($event) {
            if ($event.charCode != 0) {
                const regex = new RegExp("^[0-9]+$");
                const key = String.fromCharCode(!$event.charCode ? $event.which : $event.charCode);
                if (!regex.test(key)) {
                    $event.preventDefault();
                    return false;
                }
            }
        }
    }
}