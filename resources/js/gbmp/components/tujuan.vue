<script>
export default {
    props: {
        tujuan: {
            type: String,
            required: true
        }
    },
    methods: {
        tujuanCheck(tujuan){
            switch (tujuan) {
                case 'perakitan':
                    return 'badge badge-primary';
                case 'peminjaman':
                    return 'badge badge-success';
                case 'perbaikan':
                    return 'badge badge-danger';
                default:
                    return 'badge badge-light';
            }
        }
    },
}
</script>
<template>
    <div>
        <span :class="tujuanCheck(tujuan)">{{ tujuan }}</span>
    </div>
</template>