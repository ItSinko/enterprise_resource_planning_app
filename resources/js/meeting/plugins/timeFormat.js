import moment from "moment";

export default {
    install(Vue) {
        Vue.prototype.timeFormat = function (time) {
            return time ? moment(time, "HH:mm:ss").format("HH:mm") : "-";
        };
    },
};
