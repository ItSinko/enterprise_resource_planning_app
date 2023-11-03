<script>
export default {
    props: ['status'],
    methods: {
        statusProses(status) {
            switch (status) {
                case 'Proses':
                    return 'badge-warning'
                    break;

                case 'belum_dikerjakan':
                    return 'badge-danger'
                    break;

                case 'sedang_dikerjakan':
                    return 'badge-warning'
                    break;

                case 'selesai':
                    return 'badge-success'
                    break;
            
                default:
                    return 'badge-primary'
                    break;
            }
        },
        statusText(status) {
            switch (status) {
                case 'Proses':
                    return 'Menunggu Proses Gudang'
                    break;
                case 'belum_dikerjakan':
                    return 'Belum Dikerjakan'
                    break;
                case 'sedang_dikerjakan':
                    return 'Sedang Dikerjakan'
                    break;
                case 'selesai':
                    return 'Selesai'
                    break;
                default:
                    return 'Permintaan Baru'
                    break;
            }
        }
    },
}
</script>
<template>
    <div>
        <span class="badge" :class="statusProses(status)">{{ statusText(status) }}</span>
    </div>
</template>