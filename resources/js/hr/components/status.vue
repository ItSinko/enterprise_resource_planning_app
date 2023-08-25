<script>
export default {
    props: ['status'],
    methods: {
        statusColor(status){
            status = status.toLowerCase()
            if(status == 'selesai'){
                return 'badge-success'
            }else if(status == 'belum_selesai'){
                return 'badge-danger'
            }else{
                return 'badge-warning'
            }
        }
    },
}
</script>
<template>
    <div>
        <span :class="'badge ' + statusColor(status)">{{ status }}</span>
    </div>
</template>