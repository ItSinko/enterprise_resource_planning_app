export default {
    methods: {
        subtotal(jumlah, estimasiHarga) {
            return this.formatRupiah(jumlah * estimasiHarga)
        },
    }
}