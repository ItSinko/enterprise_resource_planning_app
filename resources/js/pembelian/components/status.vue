<script>
export default {
    props: {
        status: {
            type: String,
            required: true
        },
    },
    methods: {
        statusCheck(status){
            switch (status) {
                case 'batal':
                    return 'badge badge-danger';
                case 'pembelian ditolak':
                    return 'badge badge-danger';
                case 'belum proses':
                    return 'badge badge-danger';
                case 'menunggu diproses':
                    return 'badge badge-warning';
                case 'menunggu permintaan':
                    return 'badge badge-warning';
                case 'proses po':
                    return 'badge badge-primary';
                case 'selesai':
                    return 'badge badge-success';
                default:
                    return 'badge badge-light';
            }
        }
    }
}
</script>
<template>
    <div>
        <span :class="statusCheck(status)">{{ status }}</span>
    </div>
</template>