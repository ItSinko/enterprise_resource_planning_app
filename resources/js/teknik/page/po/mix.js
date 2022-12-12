import moment from 'moment';
export default {
    methods: {
        formatDateIndonesia(date) {
            return moment(date).format('DD MMMM YYYY');
        },
        status(status) {
            switch (status) {
                case 'Barang Datang':
                    return '<span class="badge badge-primary">Barang Datang</span>'         
                
                case 'Menunggu Kedatangan':
                    return '<span class="badge badge-secondary">Menunggu Kedatangan</span>'

                case 'Selesai Pemeriksaan Gudang':
                    return '<span class="badge badge-secondary">Selesai Pemeriksaan Gudang</span>'

                case 'Selesai Pemeriksaan QC':
                    return '<span class="badge badge-secondary">Selesai Pemeriksaan QC</span>'

                case 'Pemeriksaan Lanjutan':
                    return '<span class="badge badge-danger">Pemeriksaan Lanjutan</span>'
                
                case 'Dalam Pemeriksaan Gudang':
                    return '<span class="badge badge-danger">Dalam Pemeriksaan Gudang</span>'
                
                case 'Dalam Pemeriksaan QC':
                    return '<span class="badge badge-warning">Dalam Pemeriksaan QC</span>'
                
                case 'Selesai':
                    return '<span class="badge badge-success">Selesai</span>'

                default:
                    return `<span class="badge badge-secondary">${status}</span>`
            }
        }
    },
}