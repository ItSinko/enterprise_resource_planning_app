import moment from 'moment'

export default {
    methods: {
        formatTanggal(tanggal) {
            return tanggal ? moment(tanggal).lang('id').format('LL') : '-';
        },
        upperCase(text) {
            return text ? text.toUpperCase() : '';
        }
    }
}