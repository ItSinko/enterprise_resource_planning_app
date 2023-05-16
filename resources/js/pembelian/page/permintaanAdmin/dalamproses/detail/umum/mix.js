import moment from 'moment';
export default {
    methods: {
        formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(value);
        },
        moment(date) {
            return moment(date).lang('id').format('DD MMMM YYYY');
        }
    },
}