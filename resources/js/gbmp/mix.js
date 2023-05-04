import moment from 'moment';
export default {
    data() {
        return {
            divisi: localStorage.getItem('divisi'),          
        }
    },
    methods: {
        formatTanggal(date){
            return date ? moment(date).lang('id').format('LL') : '-';
        },
        inputNumberOnly(evt){
            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (
                charCode > 31 &&
                (charCode < 48 || charCode > 57) &&
                charCode !== 46
            ) {
                evt.preventDefault();
            } else {
                return true;
            }
        },
        closeModal() {
            this.$emit('close')
        },
    },
}