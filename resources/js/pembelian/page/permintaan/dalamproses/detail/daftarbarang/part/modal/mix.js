export default {
    data() {
        return {
            dataTableDummy: [
                {
                    nama: 'Sparepart 1',
                    jumlah: 10,
                    estimasiHarga: 100000,
                    status: 'selesai',
                },
                {
                    nama: 'Sparepart 2',
                    jumlah: 20,
                    estimasiHarga: 200000,
                    status: 'proses',
                }
            ],
        }
    },
    methods: {
        formatRupiah(currency){
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(currency)
        },
        subtotal(jumlah, estimasiHarga) {
            return this.formatRupiah(jumlah * estimasiHarga)
        },
        generateStatusHTML(status) {
            if (status === 'selesai') {
                return `<span class="badge badge-success text-capitalize">${status}</span>`
            } else {
                return `<span class="badge badge-warning text-capitalize">${status}</span>`
            }
        },
    }
}