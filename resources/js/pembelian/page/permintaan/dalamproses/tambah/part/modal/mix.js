export default {
    methods: {
        subtotal(price, qty) {
            return price * qty
        },
        formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(value)
        },
        onlyNumber(event) {
            return new RegExp('^[0-9]+$').test(event.key) || event.preventDefault()
        },
    },
}