<script>
export default {
    props: {
        status: {
            type: String,
            required: true
        },
        persentase: {
            type: Number,
            required: false
        }
    },
    methods: {
        statusCheck(status){
            const textSmall = status.toLowerCase()
            switch (textSmall) {
                // status
                case 'batal':
                    return 'badge badge-danger';
                case 'draft':
                    return 'badge badge-info';
                case 'pembelian ditolak':
                    return 'badge badge-danger';
                case 'belum proses':
                    return 'badge badge-danger';
                case 'menunggu diproses':
                    return 'badge badge-warning';
                case 'menunggu permintaan':
                    return 'badge badge-warning';
                case 'menunggu penerimaan':
                    return 'badge badge-warning';
                case 'proses po':
                    return 'badge badge-primary';
                case 'proses':
                    return 'badge badge-warning';
                case 'kirim barang':
                    return 'badge badge-danger';
                case 'done':
                    return 'badge badge-success';
                case 'selesai':
                    return 'badge badge-success';
                // tujuan
                case 'gudang':
                    return 'badge badge-info';
                case 'produksi':
                    return 'badge badge-primary';
                case 'perbaikan':
                    return 'badge badge-danger';
                case 'perakitan':
                    return 'badge badge-primary';
                case 'peminjaman':
                    return 'badge badge-secondary';
                // Jenis
                case 'screw':
                    return 'badge badge-primary';
                case 'dioda':
                    return 'badge badge-primary';
                case '-':
                    return ''
                default:
                    return 'badge badge-light';
            }
        }
    }
}
</script>
<template>
    <div>       
        <div v-if="persentase">
            <div class="progress">
                <div class="progress-bar bg-success" role="progressbar" :style="`width: ${persentase}%`" 
                :aria-valuenow="persentase" aria-valuemin="0" aria-valuemax="100">{{ persentase }}%</div>
            </div>
            <div class="d-flex justify-content-center">
                <span class="text-bold">Selesai</span>
            </div>
        </div>
        <span v-else :class="statusCheck(status)">{{ status }}</span>
    </div>
</template>