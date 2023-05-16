import moment from 'moment'

export default {
    methods: {
        formatTanggal(tanggal) {
            return tanggal ? moment(tanggal).lang('id').format('LL') : '-';
        },
        upperCase(text) {
            return text ? text.toUpperCase() : '';
        },
        formatRupiah(currency){
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(currency)
        },
        isNumber(event) {
            new RegExp('[0-9]').test(event.key) || event.preventDefault()
        },
        close() {
            this.$emit('close');
        }
    }
}