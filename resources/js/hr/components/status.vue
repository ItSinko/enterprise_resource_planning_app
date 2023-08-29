<script>
export default {
    props: ['status'],
    methods: {
        statusColor(status){
            status = status.toLowerCase()
            if(status == 'terlaksana'){
                return 'badge-success'
            }else if(status == 'belum_terlaksana'){
                return 'badge-danger'
            }else{
                return 'badge-warning'
            }
        },
        statusText(status){
            status = status.toLowerCase()
            // change underscore to space
            if(status == 'terlaksana'){
                return 'Terlaksana'
            }else if(status == 'belum_terlaksana'){
                return 'Belum Terlaksana'
            }else{
                return 'Menyusun Hasil Meeting'
            }
        }
    },
}
</script>
<template>
    <div>
        <span :class="'badge ' + statusColor(status)">{{ statusText(status) }}</span>
    </div>
</template>