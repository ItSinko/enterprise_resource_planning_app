import moment from "moment";
export default {
    methods: {
        tgl_format(tgl) {
            return moment(tgl).format('DD MMMM YYYY');
        },
        formatRupiah(e) {
            if(isNaN(e)){
                return 0;
            }else{
                return e.toString().split('').reverse().join('').match(/\d{1,3}/g).join('.').split('').reverse().join('');
            }
        },
        subtotal(qty, price, ongkir) {
            return (parseInt(qty) * parseInt(price)) + parseInt(ongkir);
        },
        subtotalPO(qty, price, discount) {
            return (parseInt(qty) * parseInt(price) * ((100 - parseInt(discount)) / 100));
        },
        totalPO(type, data) {
            let total = 0;
            if (type == 'ekat') {
                data.purchaseorderdetail.forEach(item => {
                    total += this.subtotalPO(item.qty, item.price, item.discount);
                });
            } else {
                data.purchaseorderdetail.forEach(item => {
                    total += this.subtotalPO(item.qty, item.price, item.discount);
                });
            }
            return total;
        },
        totalHargaSOEkat(total){
            let totalHarga = 0;
            total.forEach(item => {
                totalHarga += this.subtotal(item.qty, item.price, item.shippingcharge);
            });
            return totalHarga;
        },
        totalHargaPO(total){
            let ttl = 0;
            total.forEach(item => {
                ttl += this.subtotalPO(item.qty, item.price, item.discount);
            });;
            return ttl;
        },
        total(detail) {
            let total = 0;
            detail.forEach(item => {
                total += this.subtotal(item.qty, item.price, item.shippingcharge);
            });
            return total;
        },
        isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();;
            } else {
                return true;
            }
        },
        moment(date) {
            return moment(date).format("YYYY-MM-DD");
        },
        status(status){
            switch (status) {
                case 'sepakat':
                    return '<span class="badge green-text">Sepakat</span>'
                case 'negosiasi':
                    return '<span class="badge warning-text">Negosiasi</span>'
                case 'batal':
                    return '<span class="badge red-text">Batal</span>'
                default:
                    break;
            }
        },
    },
    computed: {
        datemax() {
            let date = new Date();
            return moment(date).format("YYYY-MM-DD");
        },
        selectProvinsi(){
            return this.$store.state.provinsi.map((item) => {
                return {
                    value: item.id,
                    label: item.nama
                }
            });
        },
    }
}